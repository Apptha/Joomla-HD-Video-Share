<?php
/**
 * Common helper file for Contus HD Video Share
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Include constants file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'vs_constants.php');

/**
 * Function is used to return Base URL
 *
 * @return mixed
 */
function getBaseURL () {
  /** Replace ':' in the base URL */
  $baseURL = str_replace ( ':', '%3A', JURI::base () );

  /** Find substring and replace '/' in the base URL */
  $urlBase = substr_replace ( $baseURL, "", - 1 );
  return str_replace ( '/', '%2F', $urlBase );
}

/**
 * Fucntion to get user id
 *
 * @return mixed
 */
function getUserID () {
  /** Get user object */
  global $loggedUser;
  /** Return current user id */
  return $loggedUser->get ( 'id' );
}

/**
 * Fucntion to get joomla version
 * 
 * @return string
 */
function getJoomlaVersion () {
  /** Check joomla version */
  if (version_compare ( JVERSION, '1.7.0', 'ge' )) {
    /** Return version 1.7 */
    $version = '1.7';
  } elseif (version_compare ( JVERSION, '1.6.0', 'ge' )) {
    /** Return version 1.6 */
    $version = '1.6';
  } else {
    /** Return version 1.5 */
    $version = '1.5';
  }
  return $version;
}

/**
 * Fucntion to get language direction
 *
 * @return number
 */
function getLanguageDirection () {
  /** Get joomla language object */
  $lang           = JFactory::getLanguage();
  /** check rtl is used */
  $langDirection  = (bool) $lang->isRTL();
  /** If rtl is used then return 1
   * Else return 0 */
  if ($langDirection == 1) {
    $rtlLang = 1;
  } else {
    $rtlLang = 0;
  }
  /** Return value based on the language direction */
  return $rtlLang;
}

/**
 * Function to validate array elements
 *
 * @param mixed $array_element
 *
 * @return boolean
 */
function isNumber ( $array_element ) {
  /** Check array element is number */
  return is_numeric($array_element);
}

/**
 * Function to remove slashes from string
 *
 * @param string $string
 *          string to be remove slash
 * @param string $type
 *          type of action to be performed
 *
 * @return phpSlashes
 */
function phpSlashes($string, $type = 'add') {
  $output = '';
  /** Check the type value */
  if ($type == 'add') {
    /** Check magic quotes is turned on / off */
    if (get_magic_quotes_gpc ()) {
      $output =  $string;
    } else {
      /** Check add slashes function is exist */
      if (function_exists ( 'addslashes' )) {
        $output =  addslashes ( $string );
      } else {
        /** If addslashes not exist, then use mysql function */
        $output = mysql_real_escape_string ( $string );
      }
    }
    return $output;
  } elseif ($type == 'strip') {
    /** Strip slashes in the given string */
    return stripslashes ( $string );
  } else {
    exitAction ( 'error in PHP_slashes (mixed,add | strip)' );
  }
}

/**
 * Function is used to check mobile is detected
 * 
 * @return int
 */
function checkServerDetails () {
  $mobile_browser = 0;
  /** Get server detials */
  $_SERVER [ALLHTTP] = isset ( $_SERVER [ALLHTTP] ) ? $_SERVER [ALLHTTP] : '';
  
  if ((isset ( $_SERVER ['HTTP_ACCEPT'] )) && (strpos ( strtolower ( $_SERVER ['HTTP_ACCEPT'] ), 'application/vnd.wap.xhtml+xml' ) !== false)) {
    $mobile_browser ++;
  }
  
  if (isset ( $_SERVER ['HTTP_X_WAP_PROFILE'] )) {
    $mobile_browser ++;
  }
  
  if (isset ( $_SERVER ['HTTP_PROFILE'] )) {
    $mobile_browser ++;
  }
  
  if (strpos ( strtolower ( $_SERVER [ALLHTTP] ), 'operamini' ) !== false) {
    $mobile_browser ++;
  }  
  return $mobile_browser;
}

/**
 * Function to Detect mobile device for category videos
 *
 * @return boolean
 */
function videoshare_Detect_mobile () {

  /** Call function to check mobile is detected */
  $mobile_browser = checkServerDetails ();

  /** Get mobile agent */
  $agent      = strtolower ( $_SERVER ['HTTP_USER_AGENT'] );
  $mobile_ua  = substr ( $agent, 0, 4 );

  if (preg_match ( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent )) {
    $mobile_browser ++;
  }
  
  /** Pre-final check to reset everything if the user is on Windows */
  if (strpos ( $agent, 'windows' ) !== false) {
    $mobile_browser = 0;
  }
  
  /** But WP7 is also Windows, with a slightly different characteristic */
  if (strpos ( $agent, 'windows phone' ) !== false) {
    $mobile_browser ++;
  }
  
  $mobile_agents = array (  'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac', 'blaz', 'brew', 'cell', 
      'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno', 'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 
      'lge-', 'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-', 'newt', 'noki', 'oper', 'palm', 'pana', 
      'pant', 'phil', 'play', 'port', 'prox', 'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-', 
      'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 
      'wapa', 'wapi', 'wapp', 'wapr', 'webc', 'winw', 'xda', 'xda-' );

  /** Check detected mobile agent is exists in the array */
  if (in_array ( $mobile_ua, $mobile_agents )) {
    $mobile_browser ++;
  }

  /** If agent is exists in an array then return true */ 
  if ($mobile_browser > 0) {
    return true;
  }
}

/**
 * Fucntion to remove script, embed, styles, object and html tags code in given text
 *
 * @param  string $text
 *
 * @return string
 */
function strip_html_tags( $text ) {
  /** Remove invisible content
   * Add line breaks before and after blocks */
  $text = preg_replace( array('@<head[^>]*?>.*?</head>@siu','@<style[^>]*?>.*?</style>@siu','@<script[^>]*?.*?</script>@siu','@<object[^>]*?.*?</object>@siu',
      '@<embed[^>]*?.*?</embed>@siu','@<applet[^>]*?.*?</applet>@siu','@<noframes[^>]*?.*?</noframes>@siu','@<noscript[^>]*?.*?</noscript>@siu','@<noembed[^>]*?.*?</noembed>@siu',
      '@</?((address)|(blockquote)|(center)|(del))@iu','@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu','@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu','@</?((table)|(th)|(td)|(caption))@iu',
      '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu','@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu','@</?((frameset)|(frame)|(iframe))@iu'),
      array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0","\n\$0", "\n\$0",),$text );
  return strip_tags( $text );
}


/**
 * Get image file extension type
 *
 * @param string $imageType
 *
 * @return string
 */
function getImageExtension ( $imageType ) {
  if($imageType == 'image/jpeg') {
    $extension = 'jpeg';
  } elseif($imageType == 'image/png') {
    $extension = 'png';
  } elseif($imageType == 'image/gif') {
    $extension = 'gif';
  } else {
    $extension = '';
    echo json_encode(array('errormsg'=>'true','errmsg'=>'Invalid type'));
    exitAction ( '' );
  }
  return $extension;
}

/**
 * Function is used to crop the given image
 * 
 * @param string $imgExtension
 * @param string $imageName
 * @param int $cropX
 * @param int $cropY
 * @param int $cropWidth
 * @param int $cropHeight
 * @param string $coverImagePath
 * @param string $destImagePath
 * 
 * @return void
 */
function croppingImage ( $imgExtension, $cropX, $cropY, $cropWidth, $cropHeight, $coverImagePath,$destImagePath) {
  /** Check image extension type is jpg */
  if($imgExtension == 'jpeg') {
    $imageSrc = imagecreatefromjpeg($coverImagePath);
    $imageDest = ImageCreateTrueColor($cropWidth, $cropHeight);
    imagecopyresampled($imageDest, $imageSrc, 0, 0, $cropX, $cropY, $cropWidth, $cropHeight,$cropWidth,$cropHeight);
    imagejpeg($imageDest,$destImagePath,100);
  }
  /** Check image extension type is png */
  if($imgExtension == 'png') {
    $imageSrcs = imagecreatefrompng($coverImagePath);
    $imageDests = ImageCreateTrueColor($cropWidth, $cropHeight);
    imagecopyresampled($imageDests, $imageSrcs, 0, 0, $cropX, $cropY, $cropWidth, $cropHeight,$cropWidth,$cropHeight);
    imagepng($imageDests,$destImagePath);
  }
  /** Check image extension type is gif */
  if($imgExtension == 'gif') {
    $imageSrc = imagecreatefromgif($coverImagePath);
    $imageDest = ImageCreateTrueColor($cropWidth, $cropHeight);
    imagecopyresampled($imageDest, $imageSrc, 0, 0, $cropX, $cropY, $cropWidth, $cropHeight,$cropWidth,$cropHeight);
    imagegif($imageDest,$destImagePath,100);
  }
}

/** 
 * function is used to check image and raise error 
 * 
 * @param string $ie
 * @param string $msg
 * 
 * @return void
 */
function checkType ( $ie, $msg) {
  if(empty($ie)) {
    echo json_encode(array('errormsg'=>'true','errmsg'=>'404'));
    exitAction ( '' );
  } else {
    JError::raiseError(404, JText::_($msg));
  }
}

/**
 * Function is used to get values from session
 * 
 * @param string $sessionVar
 * 
 * @return mixed
 */
function getSessionValue ( $sessionVar ) {
	if(!isset($_SESSION[ $sessionVar ]) && $_SESSION[ $sessionVar ] == null) {
		exitAction ( '' );
	} else {
		return $_SESSION[ $sessionVar ];
	}
}

/**
 * Function is used to get values from session
 *
 * @param string $sessionVar
 *
 * @return mixed
 */
function removeSessionValue ( $sessionVar ) {
	if(isset($_SESSION[ $sessionVar ]) && $_SESSION[ $sessionVar ] !=null) {
			unset($_SESSION[ $sessionVar ]);
	}
}

/**
 * Function to make SEO title
 *
 * @param string $title
 *
 * @return string
 */
function makeSEOTitle ( $title ) {
  $seoTitle = JApplication::stringURLSafe ( $title );
  if (trim ( str_replace ( '-', '', $seoTitle ) ) == '') {
    $seoTitle = JFactory::getDate ()->format ( 'Y-m-d-H-i-s' );
  }
  return $seoTitle;
}

/**
 * Function to get dailymotion Video ID from the given video URL
 *
 * @param string $text
 *
 * @return multitype
 */
function getDailymotionVideoID ( $text ) {
  /** Explode dailymotion video url with slash */
  $split      = explode ( '/', $text );

  /** Return dailymotion vieo id */
  return  explode ( '_', $split [4] );
}

/**
 * Function to get youtube video id 
 * 
 * @param string $text
 * 
 * @return mixed
 */
function getYoutubeVideoID ( $text ) {
  /**
   * Match non-linked youtube URL in the wild. (Rev:20130823)
   *
   * Required scheme. Either http or https.
   * Optional subdomain.
   * Group host alternatives.
   * Either youtu.be,
   * or youtube.com or
   * youtube-nocookie.com
   * followed by
   * Allow anything up to VIDEO_ID,
   * but char before ID is non-ID char.
   * End host alternatives.
   * $1: VIDEO_ID is exactly 11 chars.
   * Assert next char is non-ID or EOS.
   * Assert URL is not pre-linked.
   * Allow URL (query) remainder.
   * Group pre-linked alternatives.
   * Either inside a start tag,
   * or inside <a> element text contents.
   * End recognized pre-linked alts.
   * End negative lookahead assertion.
   * Consume any URL (query) remainder.
   */
  return preg_replace('~https?://(?:[0-9A-Z-]+\.)?(?:youtu\.be/| youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>| </a>))[?=&+%\w.-]*~ix', '$1', $text);
}

/**
 * Function to get youtube videos detail
 *
 * @return array
 */
function fetchYouTubeDetails () {
  $result = array ();
  /** Get application object */
  global $appObj;

  /** Check joomla version.
   * Based on that get videourl param from request */
  $videourl = JRequest::getString ( 'videourl' );
  $video_id = getYoutubeVideoID ($videourl) ;

  /** Get YouTube video details */
  $dispenable = getSiteSettings();
  $key        = $dispenable ['youtubeapi'];

  /** Check key is applied in settings page */
  if($key == ""){
    $result = "Sorry unable to fetch YouTube video details. Contact Site administrator";
    print_r(json_encode($result));
  } else{
    /** Generate URL to fetch YouTube video details */
    $url = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . '&part=contentDetails,snippet,statistics&key=' . $key;
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,4));

    /** Check file option is enabled*/
    if (ini_get ( 'allow_url_fopen' )) {
      /** Get contents from the given URL */
      $data = file_get_contents ( $url );
      /** Get json data*/
      $obj  = json_decode ( $data );
      /** Store data into an array */
      if(!empty($obj->{'items'})){
        $result_array = ($obj->{'items'} [0]->snippet);
      }
    } else {
      /** curl options */
      $options  = array ( CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_BINARYTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => false, CURLOPT_TIMEOUT => 5 );
      $ch       = curl_init ();
      curl_setopt_array ( $ch, $options );
      $json     = curl_exec ( $ch ) || exitAction ( curl_error ( $ch ) );
      curl_close ( $ch );
      if ($data = json_decode ( $json )) {
        $result_array = ($obj->{'items'} [0]->snippet);
      }
    }

    /** Check YouTube data is exist */
    if(isset($result_array) && !empty($result_array)) {
      $result ['urlpath'] = $protocol . '://www.youtube.com/watch?v=' . $video_id;
      $result ['title'] = $result_array->title;
      $result ['description'] = $result_array->description;
      if (isset ( $result_array->tags )) {
        $result ['tags'] = $result_array->tags;
      }
      print_r ( json_encode ( $result ) );
    } else {
      /** Display error message if information is not fetched */
      $appObj->enqueueMessage ( 'Could not retrieve Youtube video information' );
    }
  }
  exitAction ('');
}

/**
 * Function to get vimeo video details
 * 
 * @param string $url
 * 
 * @return multitype
 */
function getVimeoDetails ( $url ) {
    /** Split vimeo video url */ 
    $split = explode ( '/', $url );
    
    /** Check fopen is enabled in server */
    if (ini_get ( 'allow_url_fopen' )) {
      /** Create dom object */
      $doc = new DOMDocument ();
      /** Get vimeo video details using fopen method */ 
      $doc->load ( 'http://vimeo.com/api/v2/video/' . $split [3] . '.xml' );
      $videotags = $doc->getElementsByTagName ( 'video' );
    
      foreach ( $videotags as $videotag ) {
        $imgnode = $videotag->getElementsByTagName ( 'thumbnail_medium' );
        $img = $imgnode->item ( 0 )->nodeValue;
        $vidtags = $videotag->getElementsByTagName ( 'tags' );
        $tags = $vidtags->item ( 0 )->nodeValue;
      }
    } else {
      /** Fetch vimeo details using curl method */
      $url = 'http://vimeo.com/api/v2/video/' . $split [3] . '.xml';
      $curl = curl_init ( $url );
      curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
      $result = curl_exec ( $curl );
      curl_close ( $curl );
      $xml = simplexml_load_string ( $result );
      $img = $xml->video->thumbnail_medium;
      $tags = $xml->video->tags;
    }
    /** Return vimeo thumb and tag details */
    return array($img, $tags );
}

/**
 * Function to display view count for videos below the video thumbnails.
 *
 * @param string $times The view count of a video.
 *
 * @return mixed
 */
function timesViewed ( $times ) {
  /** Display view count under the thumbnails */ ?>
<span class="floatright viewcolor"> <?php echo $times . ' ' .JText::_ ( 'HDVS_VIEWS' ); ?></span>
<?php 
}

/**
 * Function to display home link.
 *
 * @param string $container The view of the link.
 * @param string $itemId The item id.
 * @param string $name The display name of the link.
 *
 * @return mixed
 */
function homeLink ( $container, $itemId, $name ){ ?>
<h2 class="home-link hoverable">
	<a
		href="<?php echo JRoute::_ ( "index.php?Itemid=" . $itemId . "&amp;option=com_contushdvideoshare&amp;view=" . $container . "" ); ?>"
		title=" <?php echo $name; ?>">  
    <?php echo $name; ?> </a>
</h2>
<?php 
}

/**
 * Function is used to assign styles for view tempaltes
 *
 * @param unknown $width
 */
function addStyleForViews ( $width ) {
  global $document;

  /** Add styles for view templates using document object */
  $style    = '#video-grid-container .ulvideo_thumb .video-item{margin-right:' . $width . 'px; }';
  $document->addStyleDeclaration ( $style );
}

/** 
 * Function is used to include common css and js files used for component
 */
function includeCommonJSCss () {
  global $document;
  
  /** Get site settings values 
   * Unserialize setiings data and assign to a variable */
  $dispenable = getSiteSettings ();
  
  /** Define constant for user login */
  define ( 'USER_LOGIN', $dispenable ['user_login'] );

  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    /** Include joomla jquery framework */  
    JHtml::_ ( 'jquery.framework',False); 
  } else {
    $document->addScript ( JURI::base () . "components/com_contushdvideoshare/js/jquery.js", $type = "text/javascript", $defer = false, $async = true );
  }
  
  /** Get language to check direction */
  $rtlLang = getLanguageDirection ();
  
  /** Include css file based on the ltr and rtl direction */
  if ($rtlLang == 1) {
    $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/stylesheet_rtl.min.css' );
  } else {
    $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/stylesheet.min.css' );
  }
  
  /** Add script variables */
  $document->addScriptDeclaration ( 'var rtlLang = ' . $rtlLang . ';' );
  $document->addScriptDeclaration ( 'var baseurl = "' . JURI::base () . '";' );
  $document->addScriptDeclaration ( 'var plylistadd = "' . JText::_ ( 'HDVS_PLAYLIST_ADDED' ) . '",plylistaddvideo = "' . JText::_ ( 'HDVS_PLAYLIST_ADDED_VIDEO' ) . '",plylistremove = "' . JText::_ ( 'HDVS_PLAYLIST_REMOVED' ) . '",plylistpblm = "' . JText::_ ( 'HDVS_PLAYLIST_PROBLEM' ) . '",hdvswait = "' . JText::_ ( 'HDVS_WAIT' ) . '",plylistexist = "' . JText::_ ( 'HDVS_MY_PLAYLIST_ALREADY_EXIST_ERROR' ) . '",plylistrestrict = "' . JText::_ ( 'HDVS_RESTRICTION_INFORMATION' ) . '",plylistnofound = "' . JText::_ ( 'HDVS_PLAYLIST_NO_FOUND' ) . '",plylistavail = "' . JText::_ ( 'HDVS_MY_PLAYLIST_AVAILABLE' ) . '",playlistlimit = "' . $dispenable ['playlist_limit'] . '",plylistnameerr = "' . JText::_ ( 'HDVS_PLAYLIST_NAME_ERROR' ) . '",confirmDeleteVideo="' . JText::_ ( 'HDVS_CONFIRM_DELETE_VIDEO' ) . '";' );
  $document->addScriptDeclaration ( 'var enterVideoURL="' . JText::_ ( 'HDVS_ENTER_VIDEO_URL' ) . '",invalidFileFormat="' . JText::_ ( 'HDVS_INVALID_FILE_FORMAT' ) . '",addedToWatchLater="' . JText::_ ( 'HDVS_ADDED_TO_LATER_VIDEOS' ) . '",addWatchLaterError="' . JText::_ ( 'HDVS_ADD_WATCH_LATER_ERROR' ) . '",confirmRemoveWatchLater="' . JText::_ ( 'HDVS_CONFIRM_REMOVE_WATCH_LATER' ) . '",watchLaterCleared="' . JText::_ ( 'HDVS_WATCH_LATER_CLEARED' ) . '",clearWatchLaterError="' . JText::_ ( 'HDVS_CLEAR_WATCH_LATER_ERROR' ) . '",confirmClearWatchHistory="' . JText::_ ( 'HDVS_CONFIRM_CLEAR_WATCH_HISTORY' ) . '",confirmClearWatchHistorySingle="' . JText::_ ( 'HDVS_CONFIRM_CLEAR_WATCH_HISTORY_SINGLE' ) . '",pauseWatchHistory="' . JText::_ ( 'HDVS_PAUSE_WATCH_HISTORY' ) . '",resumeWatchHistory="' . JText::_ ( 'HDVS_RESUME_WATCH_HISTORY' ) . '",invalidType="' . JText::_ ( 'HDVS_INVALID_TYPE' ) . '",imageDimensionAlert="' . JText::_ ( 'HDVS_IMAGE_DIMENSION_ALERT' ) . '",confirmUnsubscribe="' . JText::_ ( 'HDVS_CONFIRM_UNSUBSCRIBE' ) . '";' );
  
  /** Include js files for compoenent */
  $document->addScript ( JURI::base () . "components/com_contushdvideoshare/js/htmltooltip.js", $type = "text/javascript", $defer = false, $async = true );
  $document->addScript ( JURI::base () . 'components/com_contushdvideoshare/js/script-min.js', $type = "text/javascript", $defer = true, $async = false );  
  $document->addScript ( JURI::base () . "components/com_contushdvideoshare/js/playlist-min.js" );
}

/**
 * Function is used to include js and css files for channel, subscribe pages
 */
function includeChannelSubscribeJS () {
  global $document;
  /** Call helper function to include component common css and js files */
  includeCommonJSCss();  
  $document->addScript(JURI::base(). "components/com_contushdvideoshare/js/jquery-ui.js");
  /** Get language to check direction */
  $rtlLang = getLanguageDirection ();
  
  /** Include css file based on the ltr and rtl direction */
  if ($rtlLang == 1) {
    $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/channel_rtl.min.css' );
  } else {
    $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/channel.min.css' );
  }
  $document->addScript(JURI::base(). "components/com_contushdvideoshare/js/imagecrop-min.js");
  $document->addScript(JURI::base(). "components/com_contushdvideoshare/js/channelVideo-min.js");
  $document->addScript(JURI::base(). "components/com_contushdvideoshare/js/channel-min.js");
}

/**
 * Function to set Login / register URL
 *
 *  @return array
 */
function generateLoginRegisterURL () {
  /** Check joomla version and based on that set login / register link */
  if (version_compare(JVERSION, '1.6.0', 'ge')) {
    $loginURL    = JURI::base() . "index.php?option=com_users&amp;view=login&return=" . base64_encode( JUri::getInstance() );
    $registerURL = "index.php?option=com_users&amp;view=registration";
  } else {
    $loginURL    = JURI::base() . "index.php?option=com_user&amp;view=login&return=" . base64_encode( JUri::getInstance() );
    $registerURL = "index.php?option=com_user&amp;view=register";
  }
  return array ( $loginURL, $registerURL );
}

/**
 * Function to display login and register link in the myvideos / myplaylist page 
 * 
 * @return mixed
 */
function displayLoginRegister () {
  /** Get login/ register url using helper function */
  $result = generateLoginRegisterURL (); 
  if( !empty( $result)) {
    $loginURL = $result[0];
    $regURL =  $result[1];
  }
  /** Display Login / Register Link */
  ?>
  <div class="login-info"> <p> <?php echo  JText::_('HDVS_PLAYLIST_ADD_LOGIN_INFORMATION2'); ?>&nbsp;&nbsp; 
    <a href="<?php echo JRoute::_( $loginURL );?>">
    <?php echo  JText::_('HDVS_LOGIN'); ?></a> | 
    <a href="<?php echo JRoute::_( $regURL );?>">
    <?php echo  JText::_('HDVS_REGISTER'); ?></a> </p>
  </div>
<?php
}

/**
 * Function to display tooltip for all the video thumbnails.
 * This tooltip displays information of the video like video category, view count, etc.
 *
 * @param string $category The video category of the current video.
 * @param string $timesViewed The view count of the current video.
 * @param integer $viewControl The settings value to display the view count or not.
 * @param string $i Random string which is appended to an id of a div.
 *
 * @return mixed
 */
function toolTip ( $category, $timesViewed, $viewControl, $i ) {
  /** Display category in tooltip */ ?>
<div class="tooltip_category_left">
	<span class="title_category"><?php echo JText::_('HDVS_CATEGORY'); ?>: </span>
	<span class="show_category"><?php echo $category; ?></span>
</div>

<?php /** Check view count is exist and display view count in tooltip */ 
  if ($viewControl == 1) { ?>
<div class="tooltip_views_right">
	<span class="view_txt"><?php echo JText::_('HDVS_VIEWS'); ?>: </span> <span
		class="view_count"> <?php echo $timesViewed; ?> </span>
</div>
<div id="htmltooltipwrapper<?php echo $i; ?>">
	<div class="chat-bubble-arrow-border"></div>
	<div class="chat-bubble-arrow"></div>
</div>
<?php }
}

/**
 * Function to display pagination in all views
 *
 * @param array $videos
 * @param int $requestpage
 *
 * @return mixed
 */
function videosharePagination ( $videos , $requestpage ) {
  $q = 0;
  if( isset($videos ['pageno'])) {
    $q      = $videos ['pageno'];
  }
  $q1     = $q - 1;

  /** Display Previous link in pagination */
  if ($q > 1) {
    echo "<li align='right'><a onclick='changepage($q1);'>" . JText::_ ( 'HDVS_PREVIOUS' ) . "</a></li>";
  }

  /** Calculate page and pagination values */
  if ($requestpage && $requestpage > 3) {
    $page = $requestpage - 1;
    /** Display page links */
    if ($requestpage > 3) {
      echo "<li><a onclick='changepage(1)'>1</a></li>";
      echo "<li>...</li>";
      if ($requestpage >= 7) {
        $next_page = ceil ( $requestpage / 2 );
        echo "<li><a onclick='changepage(" . $next_page . ")'>$next_page</a></li>";
        echo "<li>...</li>";
      }
    }
  } else {
    $page = 1;
  }
  displayPaginationNumbers ( $videos, $page );
}

/**
 * Call function to display numbers in pagination
 *
 * @param array $videos
 * @param int $page
 *
 * @return mixed
 */
function displayPaginationNumbers ( $videos, $page ) {
  $q = $pages = 0;
  if( isset($videos ['pageno'])) {
    $q      = $videos ['pageno'];
  }
  if( isset($videos ['pages'])) {
    $pages  = $videos ['pages'];
  }

  /** Display pagination numbers */
  if ($pages > 1) {
    for($i = $page, $j = 1; $i <= $pages; $i ++, $j ++) {
      if ($q != $i) {
        echo "<li><a onclick='changepage(" . $i . ")'>" . $i . "</a></li>";
      } else {
        echo "<li><a onclick='changepage($i);' class='activepage'>$i</a></li>";
      }
      if ($j > 3) {
        break;
      }
    }

    if ($i < $pages) {
      if ($i + 1 != $pages) {
        echo "<li>...</li>";
      }
      echo "<li><a onclick='changepage(" . $pages . ")'>" . $pages . "</a></li>";
    }

    $p = $q + 1;
    /** Display Next link in pagination */
    if ($q < $pages) {
      echo "<li><a onclick='changepage($p);'>" . JText::_ ( 'HDVS_NEXT' ) . "</a></li>";
    }
  }
}

/**
 * Function to set radio button as checked
 *
 * @param int $checkValue
 *
 * @return void|string
 */
function radioButtonCheck ( $checkValue ) {
 /** Check radio button value */
 if (isset ( $checkValue ) && $checkValue == 1) {
  /** If it is 1 then checked the enable radio button */
  return 'checked="checked" ';
 } else {
  return;
 }
}

/**
 * Function to set radio button as unchecked
 *
 * @param unknown $checkValue
 *
 * @return void|string
 */
function radioButtonUnCheck ( $checkValue ) {
 /** Check radio button value to deselect */
 if ((isset ( $checkValue ) && $checkValue == 0) || $checkValue == '')  {
  /** If it is 0 then checked the disable radio button */
  return 'checked="checked" ';
 } else {
  return;
 }
}

/**
 * Fucntion to get subtitle values to pass to flash player
 *
 * @param object $rows
 *
 * @return string
 */
function getSubtitleValues ( $rows ) {
 /** Get subtitle 1 & 2 values */
 $subtitle1 = $rows->subtitle1;
 $subtitle2 = $rows->subtitle2;

 /** Get videos directory path */
 $current_path = "components/com_contushdvideoshare/videos/";
 /** Get subtitle directory path */
 $subtitle = $subtitle_path = JURI::base () . $current_path;

 /** Check subtitle1 & subtitle2 is exists */
 if (! empty ( $subtitle1 ) && ! empty ( $subtitle2 )) {
  $subtitle = $subtitle_path . $subtitle1 . ',' . $subtitle_path . $subtitle2;
 } elseif (! empty ( $subtitle1 )) {
  /** Check subtitle1 is exists */
  $subtitle = $subtitle_path . $subtitle1;
 } elseif (! empty ( $subtitle2 )) {
  /** Check subtitle2 is exists */
  $subtitle = $subtitle_path . $subtitle2;
 } else {
  $subtitle = '';
 }
 /** Return subtitle values */
 return $subtitle;
}


/**
 * Function to display restriction information.
 *
 * @param string $Itemid The item id.
 * @param string $container The container id.
 * @param string $id The id.
 *
 * @return mixed
 */
function restriction_info ( $Itemid, $container, $id ){
 /** Set attribute name */
  $divName =  $container . '_restrict' . $id; ?>
<div id="<?php echo $divName;?>" 
name="<?php echo $divName;?>" class="restrict" style="display: none">
  <p> <?php echo  JText::_('HDVS_RESTRICTION_INFORMATION'); ?> 
      <a class="playlist_button" href="<?php echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists"); ?>"> 
        <?php echo JText::_('HDVS_MY_PLAYLIST'); ?></a>
  </p>
</div>
<?php }

/**
 * Fucntion to display myvideos, myplaylists link in all views
 *
 * @param int $Itemid
 * @param string $current_url
 *
 * @return mixed
 */
function playlistMenu ( $Itemid, $current_url ) {
 global $contusDB,$contusQuery;
 $itemidInURL = '';
 /** Get joomla base url */
 $baseurl      = JURI::base ();
 /** Check joomla version */
 if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  /** Set login URL for joomla 1.6 */
  $url          = $baseurl . "index.php?option=com_users&view=login";
  /** Set logout URL for joomla 1.6 */
  $logoutURL    =  JRoute::_ ( 'index.php?option=com_users&task=user.logout&' . JSession::getFormToken () . '=1&return=' . base64_encode (JUri::root ()) );
  /** Set login with return URL for joomla 1.6 */
  $login_url    = JURI::base () . "index.php?option=com_users&amp;view=login&return=" . base64_encode ( $current_url );
  /** Set registration URL for joomla 1.6 */
  $register_url = "index.php?option=com_users&amp;view=registration";
 } else {
  /** Set login URL above joomla 1.6 */
  $url          = $baseurl . "index.php?option=com_user&view=login";
  /** Set logout URL above joomla 1.6 */
  $logoutURL    = "index.php?option=com_user&amp;task=logout";
  /** Set login with return URL above joomla 1.6 */
  $login_url = JURI::base () . "index.php?option=com_user&amp;view=login&return=" . base64_encode ( $current_url );
  /** Set registration URL above joomla 1.6 */
  $register_url = "index.php?option=com_user&amp;view=register";
 }

 /** Login and Registration links for all views
  * Check if any iser is logged in */
 if (USER_LOGIN == '1') {
  $userid =  (int) getUserID() ;
  /** Check user id is exists */
  if ( !empty( $userid )) {
   /** Set URL for Myvideos page */
    if(!empty($Itemid)){
      $itemidInURL =  "Itemid=" . $Itemid . "&amp;";
    }
   $myVideosURL    = JRoute::_ ( "index.php?" . $itemidInURL . "option=com_contushdvideoshare&view=myvideos" );
   $myPlaylistsUrl = JRoute::_("index.php?" . $itemidInURL . "option=com_contushdvideoshare&view=myplaylists"); ?>
<div class="toprightmenu">
  <?php /** Get user key from channel table */
  $contusQuery->clear()->select($contusDB->quoteName('user_key'))->from($contusDB->quoteName( CHANNELTABLE ));
  $contusQuery->where($contusDB->quoteName('user_id'). ' = '. $contusDB->quote($userid));
  $contusDB->setQuery($contusQuery);
  $userKey = $contusDB->loadResult();
  
  /** Check user key is exists 
   * Based on that generate channel page URL*/
  if(!empty($userKey)) {
    $channelURL = JRoute::_('index.php?option=com_contushdvideoshare&task=channel&ukey='.$userKey);
  } else {
    $channelURL = JRoute::_('index.php?option=com_contushdvideoshare&task=addnewchannel');
  }   
  /** Display playlists / channel / myvideos link */ ?>  
  <a href="<?php echo $channelURL; ?>"> 
  <?php echo JText::_('HDVS_MY_CHANNEL'); ?></a> | <a
		href="<?php echo $myPlaylistsUrl; ?>"> 
  <?php echo JText::_('HDVS_MY_PLAYLISTS'); ?></a> | <a
		href="<?php echo $myVideosURL; ?>"> 
  <?php echo JText::_ ( 'HDVS_MY_VIDEOS' ); ?></a> | <a
		href="<?php echo $logoutURL; ?>"> 
  <?php echo JText::_('HDVS_LOGOUT'); ?></a>
</div>
<?php } else { 
/** Display Register / Login link */ ?>
<span class="toprightmenu"> <a href="<?php  echo $register_url; ?>"> 
            <?php echo JText::_ ( 'HDVS_REGISTER' ); ?> </a> | <a
	href="<?php echo $login_url; ?>"> 
            <?php echo JText::_ ( 'HDVS_LOGIN' ); ?> </a>
<?php } ?>
  </span>
<?php } 
} ?>