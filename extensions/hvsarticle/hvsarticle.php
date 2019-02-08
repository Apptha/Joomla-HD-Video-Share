<?php
/**
 * HVS Article plugin for HD Video Share
 *
 * This file is to dispaly video details that matches the shortcode entered inside article 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla plugin library */
jimport ( 'joomla.plugin.plugin' );

/**
 * HVS Article Plugin class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class PlgContenthvsarticle extends JPlugin {
  /**
   * Constructor function
   *
   * @param
   *          string &$subject subject
   * @param string $config
   *          config detail
   *          
   * @return PlgContenthvsarticle
   */
  public function __construct(&$subject, $config) {
    /** Define constants for hvsarticle plugin */
    define ('AUTOPLAY', 'autoplay');
    define ('PLAYLISTAUTOPLAY', 'playlistautoplay');
    parent::__construct ( $subject, $config );
  }
  
  /**
   * Function to load content in content prepare hook
   *
   * @param string $context
   *          context
   * @param
   *          string &$article article content
   * @param
   *          string &$params article param
   * @param int $page
   *          page no
   *          
   * @return onContentPrepare
   */
  public function onContentPrepare($context, &$article, &$params, $page = 0) {
    $this->onPrepareContent ( $article, $params, $page );
  }
  
  /**
   * Function to load content in prepare content hook
   *
   * @param
   *          string &$row article content
   * @param
   *          string &$params article param
   * @param int $limitstart
   *          data per page
   *          
   * @return onPrepareContent
   */
  public function onPrepareContent(&$row, &$params, $limitstart) {
    /** Declare the variables */
    $thumImg = $videos = $filepath = $type = null;    
    /** Get database object */
    $db     = JFactory::getDBO ();
    $query  = $db->getQuery ( true );    
    /** Pattern to match short code in articles */
    $patterncode = '/\[hdvs(.*?)]/i';
    /** Match the short code in the aritcle content & get shortcode values */
    preg_match_all ( $patterncode, $row->text, $matches );
    $code   = $matches [0];
    $count  = count ( $code );    
    for($i = 0; $i < $count; $i ++) {
      $string   = $code [$i];
      $pattern  = array ( "[", "]", "hdvs" );
      $chk_shortCode_pattern  = str_replace ( $pattern, "", $string );
      $trim_shortCode         = trim ( strip_tags ( $chk_shortCode_pattern ) );
      $utf8conevert_shortCode = iconv ( 'utf-8', 'ascii//translit', $trim_shortCode );
      $shortCode              = preg_replace ( "/\s+/", "|", $utf8conevert_shortCode );
      $finalCode              = explode ( "|", trim ( $shortCode, "|" ) );
      $pwidth = $pheight = $pautoplay = $playautoplay = $idval = $swidth = $sheight = $sautoplay = $splayautoplay = $categoryid = null;
      foreach ( $finalCode as $val ) {
        $data = explode ( "=", $val );
        /** To get the video details from the shortcode */
        switch($data [0]) {
          case 'videoid':
            $idval = $data [1];
            break;
          case WIDTH:
            $swidth = $data [1];
            break;
          case HEIGHT:
            $sheight = $data [1];
            break;
          case AUTOPLAY:
            $sautoplay = $data [1];
            break;
          case PLAYLISTAUTOPLAY:
            $splayautoplay = $data [1];
            break;
          case 'categoryid':
            $categoryid = $data [1];
            break;
          case 'type':
            $type = $data [1];
            break;
          default:
            break;
        }
      }      
      if ($categoryid != '' || $idval != '' || $type != '') {
      	$query->clear ()->select ( array ( 'streamerpath', 'streameroption', 'filepath', 'videourl', 'thumburl', 'embedcode'  ) )->from ( '#__hdflv_upload' );
        /** Get the video details from the database using id */
        if ($categoryid != '' && $idval != '') {          
          $query->clear ('where')->where ( 'id = ' . ( int ) $idval )->where ( 'playlistid = ' . ( int ) $categoryid );
          $db->setQuery ( $query );
          $field = $db->loadObjectList ();
        } elseif ($categoryid != '') {          
          $query->clear ('where')->where ( 'playlistid = ' . ( int ) $categoryid );
          $db->setQuery ( $query );
          $field = $db->loadObjectList ();          
          if (! empty ( $field )) {
            $idval = $field [0]->id;
          }
        } elseif ($idval != '') {          
          $query->clear ('where')->where ( 'id = ' . ( int ) $idval );
          $db->setQuery ( $query );
          $field = $db->loadObjectList ();
        } elseif ($type != '') {          
          switch ($type) {
            case 'rec' :
              $order = " a.id DESC ";
              break;
            case 'fea' :
              $query->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) );
              $order = " a.id DESC ";
              break;
            case 'pop' :
              $order = " a.times_viewed DESC ";
              break;
            default:
              break;
          }          
          $query->clear ()->select ( array ( 'a.streamerpath', 'a.streameroption', 'a.filepath', 'a.videourl', 'a.thumburl', 'a.embedcode' ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->where ( $db->quoteName ( 'a.published' ) . ' = ' . $db->quote ( '1' ) . ' AND ' . $db->quoteName ( 'b.published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( $order ) );
          $db->setQuery ( $query );
          $field = $db->loadObjectList ();
        } else {
          $field = '';
        }     
        
        if (! empty ( $field )) {          
          $filepath       = $field [0]->filepath;
          $streameroption = $field [0]->streameroption;
          $streamerpath   = $field [0]->streamerpath;  
          /** If file option Youtube then, below fetch will work for Video & Thumb URL */
          $thumImgURL     = $field [0]->thumburl;
          $videos         = $field [0]->videourl;
          $current_path   = JURI::base () . "components/com_contushdvideoshare/videos/";
          $thumImg 		  = $current_path . $thumImgURL;
          
          switch ($filepath) {
            case 'File':
            case 'FFmpeg':
              $videos = $current_path . $field [0]->videourl;
              break;
            case 'Embed':
            	/** If file option File or FFMpeg then, below fetch will work for Video & Thumb URL */
	            $videos = $field [0]->embedcode;
	            break;
            case 'Url':
                /** If file option URL or RTMP then, below fetch will work for Video & Thumb URL */
                if ($streameroption == 'rtmp') {
	                $rtmp = str_replace ( 'rtmp', 'http', $streamerpath );
	                $videos = $rtmp . '_definst_/mp4:' . $field [0]->videourl . '/playlist.m3u8';
                } else {
                  $videos = $field [0]->videourl;
                }
                $thumImg = $thumImgURL;
                break;
            default:
              break;
          }
        }        
        /** Fetch the height and width from the default settings using component helper */
        $rs_settings = getPlayerIconSettings('both') ;
        $player_icons = unserialize ( $rs_settings->player_icons );
        $player_values = unserialize ( $rs_settings->player_values );        
        if ($player_icons ['playlist_autoplay'] == 1) {
          $playautoplay = "true";
        } else {
          $playautoplay = 'false';
        }  
        /** To assign the width */
        if ($swidth != "") {
          $width = $swidth;
        } else {
          $width = $player_values [WIDTH];
        }    
        /** To assign the height */
        if ($sheight != "") {
          $height = $sheight;
        } else {
          $height = $player_values [HEIGHT];
        }        
        /** To assign the autoplay */
        if ($sautoplay != "") {
          $autoplay = $sautoplay;
        }  else {
          if ($player_icons [AUTOPLAY] == 1) {
            $autoplay = "true";
          } else {
            $autoplay = "false";
          }
        }        
        /** To assign the playlist autoplay */
        if ($splayautoplay != "") {
          $playautoplay = $splayautoplay;
        }       
        $videoData = array( WIDTH => $width, HEIGHT =>  $height, 'id' => $idval, 'catid' => $categoryid, AUTOPLAY => $autoplay, 'filepath' => $filepath, 'video' => $videos, 'thumb' => $thumImg, 'type' => $type, PLAYLISTAUTOPLAY => $playautoplay);
        $replace = $this->addVideoHdvideo ( $videoData );
        $row->text = str_replace ( $string, $replace, $row->text );
      }
    }
  }
  
  /**
   * Function to find shortcode type
   *
   * @param string $shortcode
   *          shortcode from article
   *          
   * @return getthetype
   */
  public function getthetype($shortcode) {
    /** Check shortcode and set types */
    switch (true) {
      case (strstr ( $shortcode, 'pop' )) :
        $type = 'pop';
        break;
      
      case (strstr ( $shortcode, 'rec' )) :
        $type = 'rec';
        break;
      
      case (strstr ( $shortcode, 'fea' )) :
        $type = 'fea';
        break;
      
      default :
        $type = '';
        break;
    }
    /** Return short code types */ 
    return $type;
  }
  
  /**
   * Function to removes extra space from the given string
   *
   * @param string $str1
   *          remove space from the given string
   *          
   * @return removesextraspace
   */
  public function removesextraspace($str1) {
    return trim ( str_replace ( "]", "", (trim ( $str1 )) ) );
  }
    
  /**
   * Function for loading player with necessary inputs
   *
   * @param int $width
   *          player width
   * @param int $height
   *          player height
   * @param int $idval
   *          video id
   * @param int $categoryid
   *          categoryid
   * @param int $autoplay
   *          check autoplay enable or not
   * @param string $filepath
   *          upload method type
   * @param string $videos
   *          video url
   * @param string $thumImg
   *          thumb image url
   * @param string $type
   *          video type
   * @param int $playautoplay
   *          check playlist autoplay enable or not
   *          
   * @return addVideoHdvideo
   */
  public function addVideoHdvideo ($videodata) {
    /** Variables initialization */
    $playlist_auto = $playxml = $replace = $windo = $videos = $thumImg = $autoplay = $filepath = $playautoplay = '';
    $width = $height = $idval = $categoryid = $type = 0; 
    $baseurl1   = substr_replace ( JURI::base (), "", - 1 );    
    /** Get video details for hvsarticle plugin */
    if(isset($videodata) && !empty ($videodata)) {
      $width = $videodata [WIDTH]; 
      $height = $videodata [HEIGHT]; 
      $idval = $videodata ['id']; 
      $categoryid = $videodata ['catid']; 
      $autoplay = $videodata [AUTOPLAY];
      $filepath = $videodata ['filepath']; 
      $videos = $videodata ['video']; 
      $thumImg = $videodata ['thumb']; 
      $type = $videodata ['type']; 
      $playautoplay = $videodata [PLAYLISTAUTOPLAY];
    }
    $idval      = trim ( $idval );
    $playerpath = JURI::base () . 'components/com_contushdvideoshare/hdflvplayer/hdplayer.swf';
    $mobile     = videoshare_Detect_mobile ();
    
    /** Get current user agent */
    $useragent  = $_SERVER ['HTTP_USER_AGENT'];    
    /** Check for windows phone */
    if (strpos ( $useragent, 'Windows Phone' ) > 0) {
      $windo = 'Windows Phone';
    }
    
    if ($type) {
      if (version_compare ( JVERSION, '3.0', 'ge' ) || version_compare ( JVERSION, '1.6', 'ge' ) || version_compare ( JVERSION, '1.7', 'ge' ) || version_compare ( JVERSION, '2.5', 'ge' )) {
        $playlistpath = JURI::base () . "plugins/content/hvsarticle/playlist.php?type=" . $type;
      } else {
        $playlistpath = JURI::base () . "plugins/content/playlist.php?type=" . $type;
      }      
      $playxml = "&amp;playlistXML=" . $playlistpath;
    }    
    if (! empty ( $idval ) && ! empty ( $categoryid )) {
      $video_params = "&amp;id=" . $idval . "&amp;catid=" . $categoryid;
    } elseif (! empty ( $categoryid )) {
      $video_params = "&amp;catid=" . $categoryid;
    } else {
      $video_params = "&amp;id=" . $idval;
    }    
    if ($playautoplay) {
      $playlist_auto = "&amp;playlist_autoplay=" . $playautoplay;
    }
        
    if ($filepath == "Embed") {
      $replace .= $videos;
    } elseif (strpos ( $videos, 'vimeo' ) > 0) {
      /** Checks for Vimeo Player */
      $split = explode ( "/", $videos );
      $replace .= '<iframe src="http://player.vimeo.com/video/' . $split [3] . '?title=0&amp;byline=0&amp;portrait=0" 
          width="' . $width . '" height="' . $height . '" frameborder="0"></iframe>';
    } else {
      if ($mobile === true) {
        /** HTML5 player start here */
        if ($filepath == "Youtube" > 0) {
          if (strpos ( $videos, 'youtube.com' ) > 0) {
            /** If youtube video */            
            $videoid = getYoutubeVideoID ($videos);
            $video = "http://www.youtube.com/embed/$videoid";
            $replace .= '<iframe src="' . $video . '" class="iframe_frameborder" ></iframe>';
          } elseif (strpos ( $videos, 'dailymotion' ) > 0) {
            /** If dailymotion video */
            $video = $videos;
            $replace .= '<iframe src="' . $video . '" class="iframe_frameborder" ></iframe>';
          } elseif (strpos ( $videos, 'viddler' ) > 0) {
            /** If viddler video */
            $imgstr = explode ( "/", $videos );
            $replace .= '<iframe id="viddler-' . $imgstr . '" frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true" 
                src="//www.viddler.com/embed/' . $imgstr . '/?f=1&autoplay=0&player=full&secret=26392356&loop=false&nologo=false&hd=false" ></iframe>';
          }
        } else if ($filepath == "File" || $filepath == "FFmpeg" || $filepath == "Url") {
          /** Checks for File or FFMpeg or url */
          $replace .= '<video id="video" style="width:100%" poster="' . $thumImg . '" src="' . $videos . '" autobuffer controls onerror="failed(event)">
Html5 Not support This video Format.
</video>';
        } else {
          $replace .= ' <style type="text/css"> .login_msg{vertical-align: middle;height:' . $height . 'px;display: table-cell; color: #fff;}.login_msg a{background: #999; color:#fff; padding: 5px;} </style>    
<div id="video" style="height:' . $height . 'px; background-color:#000000; position: relative;" >
  <div class="login_msg"><h3>Theer is no videos in this playlist</h3></div> </div>';
        }
      } else {
        /** Else normal player */
        $replace .= '<div class="videoshareplayer" id="videoshareplayer" style="width:' . $width . 'px;height:' . $height . 'px;" > 
            <embed src="' . $playerpath . '" allowFullScreen="true"  allowScriptAccess="always" type="application/x-shockwave-flash" 
            flashvars="baserefJHDV=' . $baseurl1 . $playxml . $video_params . $playlist_auto . '&amp;mid=1&amp;mtype=playerModule&amp;autoplay=' . $autoplay . '"  
            wmode="opaque" style="width:' . $width . 'px;height:' . $height . 'px;" /></embed> </div>';
      }      
      $replace .= '<script> var txt     =  navigator.platform ; var windo   = "' . $windo . '"; function failed(e) {
  if(txt =="iPod" || txt =="iPad" || txt =="iPhone" || windo=="Windows Phone"  || txt =="Linux armv7l" || txt =="Linux armv6l") {
  alert("Player doesnot support this video.");
  } } </script>';
    }
    return $replace;
  }
  /** HVS Article Plugin class ends */
}
