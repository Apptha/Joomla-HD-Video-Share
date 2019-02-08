<?php
/**
 * HD Video Share Player module
 *
 * This file is to fetch details for Player module 
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
/** Include component helper file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');
/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Declare global mainframe variable */
global $mainframe;

/** Define DS */
if (! defined ( 'DS' )) {
  define ( 'DS', DIRECTORY_SEPARATOR );
}

/** Include the model file */
require_once (dirname ( __FILE__ ) . DS . 'helper.php');

/** Check joomla version and get current version */
$version = getJoomlaVersion ();

/** Get module param values from module for joomla 2.5+ */
if ($version != '1.5') {
  /** Get module params */
  $params = Modvideoshare::getvideoshareParam ();
}

/** Get module class suffix for videoshare player module */
$class        = $params->get ( 'moduleclass_sfx' );
/** Get player settings from module parameters 
 * Get player width from module params */
$width        = $params->get ( 'player_width' );
/** Get player height from module params */ 
$height       = $params->get ( 'player_height' );
/** Get show playlist from module params */
$showPlaylist = ($params->get ( 'showPlaylist' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get fullscreen from module params */
$fullscreen   = ($params->get ( 'fullscreen' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get share from module params */
$share        = ($params->get ( 'share' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get player timer from module params */
$timer        = ($params->get ( 'timer' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get zoom from module params */
$zoom         = ($params->get ( 'zoom' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get player volume from module params */
$volume       = ($params->get ( 'volume' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get playlist open from module params */
$playlistopen = ($params->get ( 'playlistOpen' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get skin hide from module params */
$skinhide     = ($params->get ( 'skinAutohide' ) == 0) ? VS_FALSE : VS_TRUE;
/** Get autoplay from module params */
$autoplay     = ($params->get ( 'autoplay' ) == 0) ? VS_FALSE : VS_TRUE;

/** Get the video details and video ID */
$videoList    = Modvideoshare::getVideoListDetails ();
$videoId      = '';
if(!empty($videoList)) {
  $videoId    = $videoList->id;
}

/** Get player settings for player module */
$player_icons = getPlayerIconSettings( 'icon' ); 
/** Get site settings player module */
$dispenable   = getSiteSettings ();

/** Player settings to pass in embed code */
$playsettings = '&id=' . $videoId . '&autoplay=' . $autoplay . '&playlist_open=' . $playlistopen . '&skin_autohide=' . $skinhide . '&showPlaylist=' . $showPlaylist . '&timer=' . $timer . '&shareIcon=' . $share . '&fullscreen=' . $fullscreen . '&zoomIcon=' . $zoom . '&volumecontrol=' . $volume;

/** To display the html layout path */
require JModuleHelper::getLayoutPath ( 'mod_videoshare' );
