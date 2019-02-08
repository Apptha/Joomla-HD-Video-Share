<?php
/**
 * RSS module for HD Video Share
 *
 * This file is to display Video Share RSS module 
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access to this file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Include the model file */
/** Get type of rss feed from module params */
$class    = $params->get ( 'moduleclass_sfx' );
$rssTypeValue = $params->get ( 'rsstype' );
if(!empty($rssTypeValue)) {
    $rsstype  = $rssTypeValue->rsstype;
} else {
    $rsstype  = 'recent';
}

/** Get category id if rss type is selected as category */
$catIDValue = $params->get ( 'catid' );
if(!empty($catIDValue)) {
    $catid  = $catIDValue->catid;
} else {
    $catid  = '14';
}

/** Get item id for rss module */
$Itemid   = getmenuitemid_thumb ('player', '');

/** To display the html layout path for rss module */
require JModuleHelper::getLayoutPath ( 'mod_hdvideosharerss' );
