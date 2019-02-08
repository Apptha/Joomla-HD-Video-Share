<?php
/**
 * Modules for HD Video Share
 *
 * This file is to fetch the particular category details
 *
 * @category   Apptha
 * @package    mod_hdvideosharemodules
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Define DS */
if (! defined ( 'DS' )) {
	define ( 'DS', DIRECTORY_SEPARATOR );
}
/** Include component helper */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Include the model file */
require_once (dirname ( __FILE__ ) . DS . 'helper.php');


/** Variable initialization and get document object */
$document = JFactory::getDocument ();
$catid    = '';

/** Get language for cateogry module */
if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  $jlang = JFactory::getLanguage ();
  $jlang->load ( VS_MODULES, JPATH_SITE, $jlang->get ( 'tag' ), true );
  $jlang->load ( VS_MODULES, JPATH_SITE, null, true );
}

/** Get module class suffix from params */ 
$class = $params->get ( 'moduleclass_sfx' );

/** Get module type */ 
$moduleType = $params->get ( 'vsmodtype' );
if(!empty($moduleType->vsmodtype)){
    $modType = $moduleType->vsmodtype;
} else {
    $modType = 0;
}

if($modType == 5) {
  /** Get cateogry id from params */
  $catid = $params->get ( 'catid' ) -> catid;
}

/** Get item id for category module */ 
$Itemid = getmenuitemid_thumb ('player', '');

/** Get module settings from module helper file */
$result1 = Modvideosharemodules::getmoduleVideossettings (8, ''); 
/** Get column count from module helper file to display thumbs */
$sidethumbview = Modvideosharemodules::getmoduleVideossettings ( $modType, 'view' );
$morePageURL = "index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=";
switch($modType){
  case 0:
    /** Get recent video details */
    $result = Modvideosharemodules::getModuleVideos ( $modType, 'a.id', $catid );
    $moreURL = $morePageURL . "recentvideos";
    break;
  case 1:
    /** Get featured video details */
    $result = Modvideosharemodules::getModuleVideos ( $modType, 'a.ordering', $catid );
    $moreURL = $morePageURL . "featuredvideos";
    break;
  case 2:
    /** Get popular video details */
    $result = Modvideosharemodules::getModuleVideos ( $modType , 'a.times_viewed', $catid );
    $moreURL = $morePageURL . "popularvideos";
    break;
  case 3:
    /** Get random video details */
    $result = Modvideosharemodules::getModuleVideos ( $modType, '', $catid );
    $moreURL = "";
    break;
  case 4:
    /** Get related video details */
    $relatedResult = Modvideosharemodules::getModuleVideos ( $modType, '', $catid );
    $result = Modvideosharemodules::changeRelatedVideosOrder ($relatedResult);
    $moreURL = $morePageURL . "relatedvideos";
    break;
  case 5:
    /** Get category video details */
    $result = Modvideosharemodules::getModuleVideos ( $modType, 'a.id', $catid );
    $moreURL = $morePageURL . "category";
    break;
   case 6:
    /** Get watch later video details */
    $result = Modvideosharemodules::getModuleVideos ( $modType, 'a.ordering', $catid );
    $moreURL = $morePageURL . "watchlater";
    break;
   case 7:
    /** Get watch history video details */
    $result = Modvideosharemodules::getModuleVideos( $modType, '', '' );
    $moreURL = $morePageURL . "watchhistoryvideos";
    break;
  default:
    break;
}

$play_limit = unserialize($result1[0]->dispenable);
$document = JFactory::getDocument();
$document->addScriptDeclaration('var playlistlimit = "' . $play_limit['playlist_limit'] . '";');

/** To display the html layout path for category module */
require JModuleHelper::getLayoutPath ( VS_MODULES );
