<?php
/**
 * HVS Article plugin for HD Video Share
 *
 * This file is to fetch video details that matches the shortcode entered inside article 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access to this file */
define ( '_JEXEC', 1 );

$rootPath = substr(dirname(__FILE__), 0, -26);
/** Include component helper file */ 
include_once ($rootPath . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** Get current directory path and define base path*/
$path   = explode ( "plugins", dirname ( __FILE__ ) );
define ( 'JPATH_BASE', $path [0] );

/** Define directory separator */
define ( 'DS', DIRECTORY_SEPARATOR );

/** Include library files
 * Include library configuration file */
require_once JPATH_BASE . DS . 'configuration.php';
/** Include library defines file */
require_once JPATH_BASE . DS . 'includes' . DS . 'defines.php';
/** Include library framework file */
require_once JPATH_BASE . DS . 'includes' . DS . 'framework.php';
/** Include library factory file */
require_once JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php';

/** Variable Initialization for hvsarticle plugin */
$playlistautoplay = $download = $islive = 'false';
$hdvideo = $timage = $streamername = $targeturl = $order = '';
$postrollid = $prerollid = 0;

/** Get type value from request URL parameters */
$type     = JRequest::getVar ( 'type' );

/** Get db connection for hvs plugin */
$db       = JFactory::getDbo ();
$query    = $db->getQuery ( true );

/** Get base url for hvs plugin */ 
$baseUrl  = JURI::base ();
$baseUrl1 = parse_url ( $baseUrl );
$baseUrl1 = $baseUrl1 ['scheme'] . '://' . $baseUrl1 ['host'];
$baseUrl2 = str_replace ( '/plugins/content/hvsarticle', '', $baseUrl );

/** Check type value. 
 * Based on that assign where condition and order */
switch ($type) {
  case 'rec' :
    $order = "a.id DESC ";
    break;
  case 'fea' :
    $query->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) );
    $order = " a.ordering ASC ";
    break;
  case 'pop' :
    $order = "a.times_viewed DESC ";
    break;
  default:
    break;
}

/** Get player settings from component helper */
$player_icons = getPlayerIconSettings('icon');

/** Check playlist autoplay is enabled */
if ($player_icons ['playlist_autoplay'] == 1) {
  /** Set true to playlist autoplay option */
  $playlistautoplay = "true";
}

/** Check download is enabled */
if ($player_icons ['enabledownload'] == 1) {
  /** Set true to download option */
  $download = "true";
}

/** Query to get Video details */
$query->clear () ->select ( array ( 'a.*', 'b.category', 'd.username', 'e.*' ) )
->from ( '#__hdflv_upload AS a' ) ->leftJoin ( '#__users AS d ON a.memberid=d.id' ) 
->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' ) ->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )
->where ( $db->quoteName ( 'a.published' ) . ' = ' . $db->quote ( '1' ) . ' AND ' . $db->quoteName ( 'b.published' ) . ' = ' . $db->quote ( '1' ) )
->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( 'd.block' ) . ' = ' . $db->quote ( '0' ) )
->where ( $db->quoteName ( 'a.filepath' ) . ' != ' . $db->quote ( 'Embed' ) )
->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( $order ) );
$db->setQuery ( $query );
/** Get video details for article plugin */ 
$records = $db->loadObjectList ();

/** Get videos directory path */
$current_path = "components/com_contushdvideoshare/videos/";

/** Playlist XML starts 
 * Clear page content */
ob_clean ();
header ( "Cache-Control: no-cache, must-revalidate" );
header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header ( "content-type: text/xml" );
/** Set xml version */
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<playlist autoplay="' . $playlistautoplay . '">';

/** Loop through playlist details */
foreach ( $records as $record ) {
  /** Check joomla version and get user access level */
  $member = getUserAccessLevel ($record->useraccess, '' );
  
  /** Initialize hd settings value */
  $hd_bol = "false";
  /** Check hd url is eixsts in article plugin */
  if ($record->hdurl) {
    /** Set true if it is enabled */
    $hd_bol = "true";
  } 
  
  $video = $record->videourl;
  $hdvideo = "";
  
  /** Assign default preview image */
  $preview_image = 'default_preview.jpg';
  /** Check preview image is exists */
  if (! empty ( $record->previewurl )) {
    /** Set preview image value */
    $preview_image = $record->previewurl;
  } 
  
  /** To get the video, thumb and preview url */
  switch($record->filepath) {
    case 'File':
    case 'FFmpeg':
      $video = $baseUrl2 . $current_path . $record->videourl;
      if ($record->hdurl != "") {
        $hdvideo = $baseUrl2 . $current_path . $record->hdurl;
      }    
      $previewimage = $baseUrl2 . $current_path . $preview_image;
      $timage       = $baseUrl2 . $current_path . $record->thumburl;  
      break;  
    case 'Url':
      if (empty ( $record->previewurl )) {
        $previewimage = $baseUrl2 . $current_path . 'default_preview.jpg';
      }      
      $timage   = $record->thumburl;
      $hdvideo  = $record->hdurl;
      break;
    case 'Youtube':
      $str2   = strstr ( $record->previewurl, 'components' );      
      if ($str2 != "") {
        $previewimage = $baseUrl2 . $record->previewurl;
        $timage       = $baseUrl2 . $record->thumburl;
      } else {
        $previewimage = $record->previewurl;
        $timage       = $record->thumburl;
      } 
      break;
    default:
      break;
  }
  
  /** Check lighttpd method is used */
  if ($record->streameroption == "lighttpd") {
    /** Set streamer option */
    $streamername = $record->streameroption;
  }
  
  /** Check rtmp is used */
  if ($record->streameroption == "rtmp") {
    /** Set streamer path */
    $streamername = $record->streamerpath;
  }
  
  /** To get site settings from helper for article plugin */
  $dispenable     = getSiteSettings();
  
  /** Get seo category title for article plugin */
  $query->clear ()->select ( 'seo_category' )->from ( '#__hdflv_category' )->where ( 'id = ' . $db->quote ( $record->playlistid ) );
  $db->setQuery ( $query );
  $seo_category = $db->loadResult ();
  
  /** Check seo settings for article plugin */
  if ($dispenable ['seo_option'] == 1) {
    /** If seo option enabled */
    $fbCategoryVal  = "category=" . $seo_category;
    $fbVideoVal     = "video=" . $record->seotitle;
  } else {
    /** If seo option disabled */
    $fbCategoryVal  = "catid=" . $record->playlistid;
    $fbVideoVal     = "id=" . $record->id;
  }
  
  /** Get fb path value for playxml in article plugin */
  $fbPath = $baseUrl1 . '/index.php?option=com_contushdvideoshare&view=player&' . $fbCategoryVal . '&' . $fbVideoVal;
  
  /** Get post roll ad id for video */
  $rs_postads   = getPrePostAdDetails ( $record->postrollid, '' );
  $postroll     = ' allow_postroll = "false"';
  $postroll_id  = ' postroll_id = "0"';
  /** Check count of postroll ads and ad is enabled */
  if (count ( $rs_postads ) > 0 && $record->postrollads == 1) {
      $postroll     = ' allow_postroll = "true"';
      $postroll_id  = ' postroll_id = "' . $record->postrollid . '"';
  }
  
  /** Get pre roll ad id for video */
  $rs_preads  = getPrePostAdDetails ( $record->prerollid, '' );
  $preroll    = ' allow_preroll = "false"';
  $preroll_id = ' preroll_id = "0"';
  /** Check count of preroll ads and ad is enabled */
  if (count ( $rs_preads ) > 0 && $record->prerollads == 1) {
      $preroll    = ' allow_preroll = "true"';
      $preroll_id = ' preroll_id = "' . $record->prerollid . '"';
  }
  
  /** Get mid ad id for video in article plugin */
  $rs_ads   = getPrePostAdDetails ('', 'mid');
  $midroll  = ' allow_midroll = "false"';
  /** Check count of midroll ads and ad is enabled */
  if (count ( $rs_ads ) > 0 && $record->midrollads == 1) {
      $midroll = ' allow_midroll = "true"';
  }
  
  /** Get ima ad for video in article plugin */
  $rs_imaads  = getPrePostAdDetails ('', 'ima');
  $imaad      = ' allow_ima = "false"';
  
  /** Check count of ima ads and ad is enabled */
  if (count ( $rs_imaads ) > 0 && $record->imaads == 1) {
    /** Set ima ad as true */
      $imaad  = ' allow_ima = "true"';
  }
  
  /** Check target url is exists */
  if ($record->targeturl != "") {
    /** Get target url for a video */
    $targeturl = $record->targeturl;
  }
  
  /** Check postroll ad setting is enabled */
  if ($record->postrollads == "1") {
    /** Get pre roll id for a video */
    $postrollid = $record->postrollid;
  }
  
  /** Check preroll ad setting is enabled */
  if ($record->prerollads == "1") {
    /** Get post roll id for a video */
    $prerollid = $record->prerollid;
  }
  
  /** Check video upload method is youtube */
  if ($record->filepath == "Youtube") {
    /** Set download as false for youtube method */ 
    $download = "false";
  }
  
  /** Check view is enabled in settings */
  $views = '';
  if ($dispenable ['viewedconrtol'] == 1) {
    /** Set view count for the video */
    $views = $record->times_viewed;
  } 
  /** Check streamer name and islive option */ 
  if ($streamername != "" && $record->islive == 1) {
    /** Set islive option as true */
      $islive = "true";
  }
  
  /** Display playxml content for hvs plugin */
  echo '<mainvideo views="' . $views . '"  streamer_path="' . $streamername . '" video_isLive="' . $islive . '" video_id = "' . htmlspecialchars ( $record->id ) . '"  
      fbpath = "' . $fbPath . '"   video_url = "' . htmlspecialchars ( $video ) . '" thumb_image = "' . htmlspecialchars ( $timage ) . '" 
      preview_image = "' . htmlspecialchars ( $previewimage ) . '" 
      ' . $midroll . ' ' . $imaad . ' ' . $postroll . ' ' . $preroll . ' ' . $postroll_id . ' ' . $preroll_id . ' 
      allow_download = "' . $download . '"  video_hdpath = "' . $hdvideo . '" copylink = ""> 
      <title> <![CDATA[' . htmlspecialchars ( $record->title ) . ']]></title> 
      <tagline targeturl="' . $targeturl . '"><![CDATA[' . htmlspecialchars ( $record->description ) . ']]></tagline> 
      </mainvideo>';
}
/** Playxml ends for article plugin */
echo "</playlist>";
/** Exit action for article plugin */
exitAction ( '' );