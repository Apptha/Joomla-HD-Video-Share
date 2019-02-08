<?php
/**
 * Constatns helper file for Contus HD Video Share
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

/** Declare global variables */ 
global $tablePrefix, $contusDB, $contusQuery, $loggedUser, $document, $appObj, $tableField;
$loggedUser   = JFactory::getUser();
$document     = JFactory::getDocument();
$appObj       = JFactory::getApplication();
$tablePrefix  = $appObj->getCfg('dbprefix');
$contusDB     = JFactory::getDBO();
$contusQuery  = $contusDB->getQuery(true);
$tableField = array ( 'a.*', 'b.category', 'd.username', 'e.*');


/** Define directory separator */
if (! defined ( 'DS' )) {
  define ( 'DS', DIRECTORY_SEPARATOR );
}

/** Define constant for component values */
define ('COMPONENT', 'com_contushdvideoshare');

/** Define constant for videoshare modules */
define('VS_MODULES','mod_hdvideosharemodules');

/** Message Constatns */ 
define ('MESSAGE', 'message');
define ('SAVE_SUCCESS', 'Saved Successfully');

/** Datatype Constants */
define ('VS_FALSE', 'false');
define ('VS_SUCCESS', 'success');
define ('VS_TRUE', 'true');
define ('TYPE_STRING', 'string');
define ('TYPE_INT', 'int');
define ('GET_METHOD', 'get');
define ('POST_METHOD', 'post');
define ('HDFLV', 'hdflv_');
define ('ALLHTTP', 'ALL_HTTP');

/** Videoshare Table constants */
define ('PLAYERTABLE', $tablePrefix . HDFLV . 'upload');
define ('VIDEOCATEGORYTABLE', $tablePrefix . HDFLV . 'video_category');
define ('CATEGORYTABLE', $tablePrefix . HDFLV . 'category');
define ('PLAYERSETTINGSTABLE', $tablePrefix . HDFLV . 'player_settings');
define ('SITESETTINGSTABLE', $tablePrefix . HDFLV . 'site_settings');
define ('GOOGLEADTABLE', $tablePrefix . HDFLV . 'googlead');
define ('COMMENTSTABLE', $tablePrefix . HDFLV . 'comments');
define ('ADSTABLE', $tablePrefix. HDFLV . 'ads');
define ('PLAYLISTTABLE', $tablePrefix. HDFLV . 'playlist');
define ('VIDEOPLAYLISTTABLE', $tablePrefix. HDFLV . 'video_playlist');
define ('WATCHLATERTABLE', $tablePrefix . HDFLV . 'watchlater');
define ('WATCHHISTORYTABLE', $tablePrefix . HDFLV .  'watchhistory');
define ('CHANNELTABLE', $tablePrefix . HDFLV . 'channel');
define ('CHANNEL_SUBSCRIBE', HDFLV . 'channel_subscribe' );
define ('CHANNEL_NOTIFICATION', HDFLV . 'channel_notification' );
define ('VSUSERTABLE', $tablePrefix . HDFLV . 'user');

/** Joomla table constants */
define ('USERSTABLE', $tablePrefix . 'users');
define ('MENUTABLE', $tablePrefix . 'menu');

/** Commonly used constants */
define ('JOOM3', '3.0.0');
define ('ADMINISTRATOR' , 'administrator');
define ('COMPONENTTEXT', 'component');
define ('ADMIN' , 'admin');
define ('HIDDENCATEGORYMENU', 'hiddencategorymenu');
define ('CATEGORY_SEARCH' , 'category_search');
define ('CATEGORY_STATUS', 'category_status');
define ('VIDEOURL', 'videourl');
define ('HDURL', 'hdurl');
define ('THUMBURL', 'thumburl');
define ('PREVIEWURL', 'previewurl');
define ('THUMB', 'thumb');
define ('YOUTUBE','Youtube');
define ('FFMPEGPATH','ffmpegpath');
define ('FILEOPTION','fileoption');
define ('EMBED', 'Embed');
define ('UPLOADTYPE', 'uploadType');
define ('UPLOADS','uploads');
define ('IMAGE', 'image');
define ('IMAGES', 'images');
define ('CATEGORY','category');
define ('SEOCATEGORY','seo_category');
define ('SEARCH', 'search');
define ('DESCRIPTION', 'description');
define ('MODELCONTUSHDVIDEOSHARE', 'Modelcontushdvideoshare');
define ('IMAGENAME', 'imageName');
define ('WIDTH', 'width');
define ('HEIGHT', 'height');
define ('VIDEOFORPLAYER', 'videoForPlayer');
define ('PAUSEHISTORYSTATE', 'Pause_History_State');
define ('DISPENABLE', 'dispenable');
define ('VERSIONCOMPARE', '1.6.0');
define ('PLAYLIST', 'playlist');
define ('VIDEO', 'video');

/** Constants used for queries */ 
define ('LIMITSTART', 'limitstart');
define ('PUBLISH', 'published');
define ('SEOTITLE', 'a.seotitle');
define ('SEO_CATEGORY', 'b.seo_category');
define ('CATEGORYTITLE', 'b.category');
define ('VIDEOPUBLISH', 'a.published');
define ('VIDMEMBERID', 'a.memberid');
define ('CATPUBLISH', 'b.published');
define ('VIDEOCATELEFTJOIN', ' AS e ON e.vid=a.id');
define ('VIDEOMEMBERLEFTJOIN' ,  ' AS d ON a.memberid=d.id');
define ( 'VID', 'vid' );
define ( 'CID', 'cid' );
define ( 'MSID', 'msid' );
define ( 'WHERE', 'where' );
define ( 'PARENTID', 'parentid' );
define ( 'PARENT_ID', 'parent_id' );
define ('FEATURED', 'a.featured');
define ('TYPE', 'a.type');
define ('CATVIDEOID', 'e.vid');
define ('TIMESVIEWED', 'a.times_viewed');
define ('VIDEOID', 'videoid');
define ('ORDERING', 'a.ordering');
define ('USERBLOCK', 'd.block');
define ('RATECOUNT', 'a.ratecount');
define ('FILEPATH', 'a.filepath');
define ('VIDTHUMBURL', 'a.thumburl');
define ('VIDTITLE', 'a.title');
define ('VIDDESCRIPTION', 'a.description');
define ('AMAZONS3', 'a.amazons3');
define ('USRNAME', 'd.username');
define ('RATE', 'a.rate');
define ('PLAYLISTID', 'a.playlistid');
define ('VIDEOTABLECONSTANT', ' AS a');
define ('PLAYID', 'playid');
define ('CATID', 'catid' );

/** All constants of the channel functionality */
define ( 'CHANNEL', 'channel' );
define ( 'BANNER', 'banner' );
define ( 'SUBSCRIBE', 'subscripe' );
define ( 'MYSUBSCRIBE', 'mysubscripe' );
define ( 'USER_KEY', 'user_key' );
define ( 'USER_CONTENT', 'user_content' );
define ( 'USER_ID', 'user_id' );
define ( 'PAGE_NOT_FOUND', 'Page Not Found' );
define ( 'USER_NAME', 'user_name' );
define ( 'ERRORMSG', 'errormsg' );
define ( 'ERRMSG', 'errmsg' );
define ( 'ERROR', 'error' );
define ( 'SUB_ID', 'sub_id' );
define ( 'SUBID', 'subid' );
define ( 'SUBSCRIBERID', 'subscriperId' );
define ( 'SUBSCID', 'subid' );
define ( 'VIDEO_SEARCH', 'videoSearch' );
define ( 'CHANNEL_DESCRIPTION', 'channelDescription' );
define ( 'USERNAME', 'userName' );
define ( 'IMAGEUPLOADTYPE' , 'imageUploadType' );
define ( 'IMAGEEXTENSION' , 'imageExtension' );

/** Images directory path constants */
define ('IMAGE_DIRPATH', JURI::base(). 'components/com_contushdvideoshare/images/');
define ('CHANNEL_DIRPATH', JPATH_ROOT. '/images/channel/banner/');
define ('CHAN_COVERPATH', JPATH_ROOT. '/images/channel/banner/cover/');
define ('CHAN_PROFILEPATH', JPATH_ROOT. '/images/channel/banner/profile/');
define ('PLAYERPATH', JURI::base () . "components/com_contushdvideoshare/hdflvplayer/hdplayer.swf");

/**
 * Function to perform the exit action
 *
 * @param unknown $text
 *
 * @return void
 */
function exitAction ($text) {
  /** Exit the current action */
  exit($text);
}
?>