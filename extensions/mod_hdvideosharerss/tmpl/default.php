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

/** Include component helper */
include_once (JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_contushdvideoshare'.DIRECTORY_SEPARATOR.'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Get site base URL */
$baseURL = JURI::base ();

$rssURL = 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=rss&type=';
/** Check rsstype */ 
switch ($rsstype) {
  case 1 :
    /** Generate URL for featured videos feed */
    $rssURL = JRoute::_ ( $rssURL . 'featured' );
    break;
  case 2 :
    /** Generate URL for popular videos feed */
    $rssURL = JRoute::_ ( $rssURL . 'popular' );
    break;
  case 3 :
    /** Generate URL for particular category feed */
    $rssURL = JRoute::_ ( $rssURL . 'category&catid=' . $catid );
    break;
  case 0 :
  default :
      /** Generate URL for recent videos feed */
      $rssURL = JRoute::_ ( $rssURL . 'recent' );  
      break;
}
/** Display RSS feed icon */ 
?>
<div class="module_menu <?php echo $class; ?> module_videos" style="display:inline-block;float:none">
  <a href="<?php echo $rssURL; ?>" id="rssfeedicon" target="_blank">
    <img src="<?php echo $baseURL; ?>/components/com_contushdvideoshare/images/rss_button.png">
  </a>
</div>
