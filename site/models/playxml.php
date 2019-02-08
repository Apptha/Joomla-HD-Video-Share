<?php
/**
 * Play XML model file
 *
 * This file is to fetch videos detail from database
 * and pass values to player
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );
/** Define constants */
define ('DISTINCTVID', 'DISTINCT a.*');
define('CATEGORYLEFTPLAYLISTID','#__hdflv_category AS b ON a.playlistid=b.id');
define('CATEGORYLEFTPARENTPLAYLISTID','#__hdflv_category AS b ON ( a.playlistid=b.id or a.playlistid=b.parent_id )');
define('AMAZONS3LINK','amazons3link');
/**
 * Playxml model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideoshareplayxml extends ContushdvideoshareModel {
  /**
   * Function to get videos for player
   *
   * @return array
   */
  public function playgetrecords() {
    /** Get database connection for playxml */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );    
    /** Varaible initialization */
    $videoid = 0;
    $publish = $adminview = '';
    $playlist = array ();    
    /** Get video id from url request */
    $vid = JRequest::getvar ( 'id' );
    /** Get category id from url request */
    $categ_id = JRequest::getvar ( 'catid' );
    $playId = JRequest::getInt('playid');
    /** Get mid from url request */
    $mid = JRequest::getString ( 'mid' );
    /** Get admin view from url request 
     * for admin video preview in video grid view */
    $adminview = JRequest::getString ( 'adminview' );
    /** Get videos limit for playxml videos */
    $limitvideos = $this->getVideosLimit ();
    
    /** If adminview true then preview the video even it is unpublished */
    if (! $adminview) {
      $publish = $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' );
    }
    
    /** Call function to get video details */
    $rows = $this->getVideoDetails ($publish);    
    
    /** Check whether the banner player is assigned */
    if ($mid == 'playerModule' && count ( $rows ) > 0) {
        /** Fetch featured videos from database */
        $query->clear ()->select ( array ( DISTINCTVID, CATEGORYTITLE ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( CATEGORYLEFTPARENTPLAYLISTID );
        /** Check publish is not empty */
        if ($publish != '') {
          $query->where ( $publish );
        }
        $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.id' ) . ' != ' . $db->quote ( $vid ) )->where ( $db->quoteName ( 'a.filepath' ) . ' != ' . $db->quote ( 'Embed' ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
        /** Execute query and limit data to 3 */
        $db->setQuery ( $query, 0, 3 );        
        /** Store db values */
        $playlist_loop = $db->loadObjectList ();        
        /** Call function to sort playlist data */
        $playlist = $this->dataSorting ($playlist_loop, $rows [0]->id, $query );
        
    } elseif ($vid) {
      /** Get video id, category id from the request */
      $videoid = $vid;
      $videocategory = $rows [0]->playlistid;      
      /** Store category id fetched from url request */
      if ($categ_id) {
        $videocategory = $categ_id;
      }       
      if ($rows [0]->playlistid == $categ_id && count ( $rows ) > 0) {
          /** Fetch video details and category from database except current video */
          $query->clear ()->select ( array ( DISTINCTVID, CATEGORYTITLE ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( CATEGORYLEFTPLAYLISTID );
          if ($publish != '') {
            $query->where ( $publish );
          }          
          $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( '(' . $db->quoteName ( 'b.id' ) . ' = ' . $db->quote ( $videocategory ) . ' OR ' . $db->quoteName ( 'b.parent_id' ) . ' = ' . $db->quote ( $videocategory ) . ')' )->where ( $db->quoteName ( 'a.id' ) . ' != ' . $db->quote ( $videoid ) )->where ( $db->quoteName ( 'a.filepath' ) . ' != ' . $db->quote ( EMBED ) );
          /** Execute query and limit data to setting limit */
          $db->setQuery ( $query, 0, $limitvideos );
          $playlist_loop = $db->loadObjectList ();    
          /** Call function to rearrange playlist data */      
          $playlist = $this->dataSorting ($playlist_loop, $rows [0]->id, $query ); 
        }
        
        if(!empty($playId) && $this->isValidPlaylistId($playId)) {
        	/** Fetch video details and category from database except current video */
        	$query->clear ()->select ( array ( DISTINCTVID, CATEGORYTITLE ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( CATEGORYLEFTPLAYLISTID )->leftJoin('#__hdflv_video_playlist AS c ON a.id=c.vid');
        	if ($publish != '') {
        		$query->where ( $publish );
        	}
        	$query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where($db->quoteName('c.catid').' = '.$db->quote($playId))->where ( $db->quoteName ( 'a.id' ) . ' != ' . $db->quote ( $videoid ) )->where ( $db->quoteName ( 'a.filepath' ) . ' != ' . $db->quote ( EMBED ) );
        	/** Execute query and limit data to setting limit */
        	$db->setQuery ( $query, 0, $limitvideos );
        	$playlist_loop = $db->loadObjectList ();
        	/** Call function to rearrange playlist data */
        	$playlist = $this->dataSorting ($playlist_loop, $rows [0]->id, $query );
        }
    } else {
      $rs_video = $this->getHomePlayerData ( $limitvideos, $publish);
    }    
    /** If $rows not empty merge with related videos array $playlist */
    if (isset ( $rows ) && count ( $rows ) > 0) {
      $rs_video = array_merge ( $rows, $playlist );
    }  
    /** Call function to display playxml data */ 
    $this->showxml ( $rs_video );
  }
  
  /**
   * Function to get current video detials 
   * 
   * @param unknown $publish
   * @return multitype:
   */
  public function getVideoDetails ($publish) {
    /** Get db connection to get video details */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Get video id from url request */
    $vid = JRequest::getvar ( 'id' );
    /** Get category id from url request */
    $categ_id = JRequest::getvar ( 'catid' );
    
    if ($vid != 0) {
    
      /** Fetch video details, category from database for the current video */
      $query->clear ()->select ( array ( DISTINCTVID, CATEGORYTITLE ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( CATEGORYLEFTPLAYLISTID );
    
      /** Check publish datya is not empty */
      if ($publish != '') {
        $query->where ( $publish );
      }
      $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $vid ) )->where ( $db->quoteName ( 'a.filepath' ) . ' != ' . $db->quote ( EMBED ) );
      /** Execute query to get video details */ 
      $db->setQuery ( $query );
      $rows = $db->loadObjectList ();
      /** Check cat id is not equal to cat id fetched from db */ 
      if (! empty ( $categ_id ) && $rows [0]->playlistid != $categ_id) {
        /** Return empty array data */
        $rows = array ();
      }
      /** Return video details */
      return $rows;
    }
  }
  
  /**
   * Function to get videos for home page player 
   * 
   * @param unknown $limitvideos
   * @param unknown $publish
   * @return Ambigous <mixed, NULL, multitype:unknown mixed >
   */
  public function getHomePlayerData ( $limitvideos, $publish) {
    /** Get db connection to fetch video detials for home page player */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );

    /** Query to get featured video detials */
    $query->clear ()->select ( array ( 'a.*', CATEGORYTITLE, 'b.seo_category', 'd.username', 'e.*' ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' );
    if ($publish != '') {
        $query->where ( $publish );
     }
    $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( FILEPATH ) . ' != ' . $db->quote ( EMBED ) )->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( 'a.ordering' . ' ' . 'ASC' ) );
    /** Query is to display recent videos in home page */
    $db->setQuery ( $query, 0, ($limitvideos + 1) );
    $rs_video = $db->loadObjectList ();

    /** Check featured video data is exists */
    if (count ( $rs_video ) == 0) {
      /** Query to get recent videos for player */
      $query->clear ( 'where' ) ->clear ( 'order' )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( FILEPATH ) . ' != ' . $db->quote ( EMBED ) )->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
      if ($publish != '') {
        $query->where ( $publish );
      }
      /** Query is to display recent videos in home page */
      $db->setQuery ( $query, 0, 1 );
      $rs_video = $db->loadObjectList ();
    }
    /** Return home page player video detials */ 
    return $rs_video;
  }
  
  /**
   * Function to show playxml
   *
   * @param array $rs_video
   *          video detail in array format
   *          
   * @return void
   */
  public function showxml( $rs_video ) {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    $rows = $uid = $hdvideo = $timage = $targeturl = $previewimage = $views = '';
    $postrollid = $prerollid = 0;
    $download = $playlistautoplay = $islive = "false";
    $current_path = "components/com_contushdvideoshare/videos/";
    $adminview = JRequest::getString ( 'adminview' );
    
    /** Get player settings */   
    $player_icons = getPlayerIconSettings('icon');
    /** Call helper function to get site settings */
    $dispenable = getSiteSettings ();
    
    /** Get playlist autoplay value */
    if ($player_icons ['playlist_autoplay'] == 1) {
      $playlistautoplay = "true";
    }
    
    /** Get hddefault value */
    $hddefault = $player_icons ['hddefault'];
    
    /** Generate Playlist xml here */
    ob_clean ();
    /** Set XML header */
    header ( "content-type: text/xml" );
    /** Generate Playlist xml here */
    echo '<?xml version="1.0" encoding="utf-8"?>';
    /** Set playlist autoplay enable/dsiable here */
    echo '<playlist autoplay="' . $playlistautoplay . '">';
    
    if (count ( $rs_video ) > 0) {
      foreach ( $rs_video as $rows ) {
        $streamername = '';
        /** Get user access level for video from helper */
        $member = getUserAccessLevel ($rows->useraccess, $adminview );
        
        /** Get details of upload and FFMPEG type videos */
        if ($rows->filepath == "File" || $rows->filepath == "FFmpeg") {
          if ($hddefault == 1 && $rows->hdurl != '') {            
            $hd_bol = "true";
            $hdvideo = JURI::base () . $current_path . $rows->hdurl;
            if (isset ( $rows->amazons3 ) && $rows->amazons3 == 1) {
              $hdvideo = $dispenable [AMAZONS3LINK] . $rows->hdurl;
            }
          } else {
            $hd_bol = "false";            
          }
          $video = JURI::base () . $current_path . $rows->videourl;
          if (isset ( $rows->amazons3 ) && $rows->amazons3 == 1) {
          	$video = $dispenable [AMAZONS3LINK] . $rows->videourl;
          }
          /** Get video preview image */
          $previewimage = JURI::base () . $current_path . $rows->previewurl;
          /** Get video preview image from amazon */
          if (isset ( $rows->amazons3 ) && $rows->amazons3 == 1 && ! empty ( $rows->previewurl )) {
            $previewimage = $dispenable [AMAZONS3LINK] . $rows->previewurl;
          }
          
          /** Get video thumb image */
          $timage = JURI::base () . $current_path . $rows->thumburl;
          /** Get video thumb image from amazon */
          if (isset ( $rows->amazons3 ) && $rows->amazons3 == 1) {
            $timage = $dispenable [AMAZONS3LINK] . $rows->thumburl;
          }
        }  elseif ($rows->filepath == "Url") {
          /** Get details of URL type videos */
        	if ($hddefault == 1 && $rows->hdurl != '') {
        		$hd_bol = "true";
        		$hdvideo = $rows->hdurl;
        	} else {
	          $hd_bol = "false";	          
        	}
        	$video = $rows->videourl;
          $previewimage = JURI::base () . $current_path . 'default_preview.jpg';
          $timage = $rows->thumburl;
          /** Get video preview image */
          if (! empty ( $rows->previewurl )) {
            $previewimage = $rows->previewurl;
          }          
        } elseif ($rows->filepath == "Youtube") {
          /** Get details of Youtube type videos */
          
          $video = $rows->videourl;
          $str2 = strstr ( $rows->thumburl, 'components' );
          if ($str2 != "") {
            $timage = JURI::base () . $rows->thumburl;
          } else {
            $timage = $rows->thumburl;
          }
          $str2 = strstr ( $rows->previewurl, 'components' );
          if ($str2 != "") {
            $previewimage = JURI::base () . $rows->previewurl;
          } else {
            $previewimage = $rows->previewurl;
          }          
          $hd_bol = "false";
          $hdvideo = "";
          $download = "false";
        } else {
          $hdvideo = '';
        }
        
        /** Get streaming option */
        if ($rows->streameroption == "lighttpd") {
          $streamername = 'pseudostreaming';
        }
        
        /** Get RTMP path */
        if ($rows->streameroption == "rtmp") {
          $streamername = $rows->streamerpath;
        }
        
        /** Get subtitles from helper  */       
        $subtitle = getSubtitleValues ( $rows );
                
        /** Get post roll ad id for video */
        $rs_postads = getPrePostAdDetails (  $rows->postrollid, '' );
        $postroll = ' allow_postroll = "false"';
        $postroll_id = ' postroll_id = "0"';
        if (count ( $rs_postads ) > 0 && $rows->postrollads == 1) {
            $postroll = ' allow_postroll = "true"';
            $postroll_id = ' postroll_id = "' . $rows->postrollid . '"';
        }
        
        /** Get pre roll ad id for video */
        $rs_preads = getPrePostAdDetails (  $rows->prerollid, '' );
        $preroll = ' allow_preroll = "false"';
        $preroll_id = ' preroll_id = "0"';
        if (count ( $rs_preads ) > 0 && $rows->prerollads == 1) {
            $preroll = ' allow_preroll = "true"';
            $preroll_id = ' preroll_id = "' . $rows->prerollid . '"';
        }
        
        /** Get mid ad id for video */
        $rs_ads = getPrePostAdDetails ( '', 'mid' );
        $midroll = ' allow_midroll = "false"';
        if (count ( $rs_ads ) > 0 && $rows->midrollads == 1) {
            $midroll = ' allow_midroll = "true"';
        }
        
        /** Get ima ad for video */
        $rs_imaads = getPrePostAdDetails ( '', 'ima' );
        $imaad = ' allow_ima = "false"';
        if (count ( $rs_imaads ) > 0 && $rows->imaads == 1) {
            $imaad = ' allow_ima = "true"';
        }
        
        /** Get download option for particular video */
        if ($rows->download == 1) {
          $download = "true";
        }
             
        /** Video restriction based on access level ends here */
        $query->clear ()->select ( 'seo_category' )->from ( '#__hdflv_category' )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $rows->playlistid ) );
        $db->setQuery ( $query );
        /** Get seo category title */
        $seo_category = $db->loadResult ();        
        if ($dispenable ['seo_option'] == 1) {
          /** If seo option enabled */
          $fbCategoryVal = "category=" . $seo_category;
          $fbVideoVal = "video=" . $rows->seotitle;
        } else {
          /** If seo option disabled */
          $fbCategoryVal = "catid=" . $rows->playlistid;
          $fbVideoVal = "id=" . $rows->id;
        }
        
        /** Genearte Base URL */
        $baseUrl1 = parse_url ( JURI::base () );
        $baseUrl1 = $baseUrl1 ['scheme'] . '://' . $baseUrl1 ['host'];
        
        /** Call function to get item id */
        $Itemid = getmenuitemid_thumb ( 'player', '' );
        
        /** Generate URL for every video */
        $playId = JRequest::getInt('playid');
        if(!empty($playId)){
        	// Generate URL for every playlist video
        	$fbPlayVal = "playid=" . $playId . "&play=1";
        	$fbPath = $baseUrl1 . JRoute::_('index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=player&' . $fbPlayVal . '&' . $fbVideoVal);
        }
        else{
        	// Generate URL for every category video
        	$fbPath = $baseUrl1 . JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=player&' . $fbCategoryVal . '&' . $fbVideoVal );
        }
        
        if ($rows->targeturl != "") {
          /** Get target url for a video */
          $targeturl = $rows->targeturl;
          
          if (! preg_match ( "~^(?:f|ht)tps?://~i", $targeturl )) {
            $targeturl = "http://" . $targeturl;
          }
        }
        
        if ($rows->postrollads == "1") {
          /** Get pre roll id for a video */
          $postrollid = $rows->postrollid;
        }
        
        if ($rows->prerollads == "1") {
          /** Get post roll id for a video */
          $prerollid = $rows->prerollid;
        }
        
        /** Get title of the video */
        $title = $rows->title;
        
        /** Get view count of the video */
        if ($dispenable ['viewedconrtol'] == 1) {
          $views = $rows->times_viewed;
        } 
        
        /** Get video ID */
        $video_id = $rows->id;
        
        /** Get video Description */
        $description = $rows->description;
        
        if ($rows->filepath == "Youtube") {
          /** Display download option for youtube videos */
          $download = "false";
        }
        
        if ($streamername != "" && $rows->islive == 1) {
            /** Check for RTMP video is live one or not */
            $islive = "true";
        }
        
        /** Restrict playxml for vimeo videos. */
        if (! preg_match ( '/vimeo/', $video )) {
          echo '<mainvideo member="' . $member . '" uid="' . $uid . '" subtitle ="' . $subtitle . '"  views="' . $views . '" 
              streamer_path="' . $streamername . '" video_isLive="' . $islive . '" video_id = "' . htmlspecialchars ( $video_id ) . '" 
              fbpath = "' . $fbPath . '" video_url = "' . htmlspecialchars ( $video ) . '" thumb_image = "' . htmlspecialchars ( $timage ) . '" 
              preview_image = "' . htmlspecialchars ( $previewimage ) . '" 
                  ' . $midroll . ' ' . $imaad . ' ' . $postroll . ' ' . $preroll . ' ' . $postroll_id . ' ' . $preroll_id . ' allow_download = "' . $download . '" video_hdpath = "' . $hdvideo . '" copylink = ""> 
               <title><![CDATA[' . htmlspecialchars ( $title ) . ']]></title> 
               <tagline targeturl="' . $targeturl . '"> <![CDATA[' . strip_tags ( $description ) . ']]></tagline> 
          </mainvideo>';
        }
      }
    }    
    echo '</playlist>';
    exitAction ( '' );
  }
  
  /**
   * Function to perform sorting action in playlist data
   * 
   * @param unknown $playlist_loop
   * @param unknown $vid
   * @param unknown $query
   * @return multitype:
   */
  public function dataSorting ($playlist_loop, $vid, $query ) {
    /** Array rotation to autoplay the videos correctly */
    $arr1 = $arr2 = array ();
    $db = JFactory::getDBO ();
    
    /** Check array data is exist */ 
    if (count ( $playlist_loop ) > 0) {
      /** Loop through laylist data */
      foreach ( $playlist_loop as $r ) {
    /** Clear where condition in the query receiven in arguments */
        $query->clear ('where');
        /** Set where condition to sort the array results */
        $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( FILEPATH ) . ' != ' . $db->quote ( EMBED ) );
        
        /** Fetch video details and category from database without the current video */
        if ($r->id > $vid) {
          
          /** Storing greater values in an array */
          $query->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $r->id ) );
          $db->setQuery ( $query );
          $greaterArray = $db->loadObject ();
          /** Store values in array of video ids which is greater than current video id */
          $arr1 [] = $greaterArray;
        } else {

          /** Storing lesser values in an array */
          $query->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $r->id ) );          
          $db->setQuery ( $query );
          $lesserArray = $db->loadObject ();
          $arr2 [] = $lesserArray;
        }
      }
    }    
    /** Merge the sorted arrays */
    return array_merge ( $arr2, $arr1 );    
  } 
  
  /**
   * Function to get limit for playlist  
   * 
   * @return Ambigous <number, mixed>
   */
  public function getVideosLimit () {
    /** Call helper function to get site settings */
    $dispenable = getSiteSettings ();
    
    /** Get videos limit from settings for related videos inside player */
    if (isset ( $dispenable ['limitvideo'] )) {
      $limitvideos = $dispenable ['limitvideo'];
    } else {
      /** Set default videos limit for related videos inside player */
      $limitvideos = 100;
    }
    /** Return videos limit for playxml */
    return $limitvideos;
  } 
  
  /**
   * Function to check whether a playlist id is valid for the current user.
   * 
   * @param unknown $playlistId The playlist id which is to be checked.
   * @return boolean True if id is valid and False if not.
   */
  function isValidPlaylistId($playlistId) {
  	$user = JFactory::getUser();
  	$userId=$user->id;
  	$db = JFactory::getDbo();
  	$query = $db->getQuery(true);
  	$query->select('COUNT(*)');
  	$query->from($db->quoteName('#__hdflv_playlist'));
  	$query->where($db->quoteName('id')." = ".$playlistId);
  	$query->where($db->quoteName('member_id')." = ".$userId);
  	
  	/** Reset the query using our newly populated query object. */
  	$db->setQuery($query);
  	$count = $db->loadResult();
  	
  	if($count==0) {
  		$returnVal = false;
  	}
  	else {
  		$returnVal = true;
  	}
  	return $returnVal;
  }
  /** Playxml model ends */
}
