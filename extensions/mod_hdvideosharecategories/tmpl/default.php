<?php
/**
 * Categories module for HD Video Share
 *
 * This file is to display all the categories name in the module 
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Check component is used */
if (JRequest::getVar ( 'option' ) != 'com_contushdvideoshare') {
  /** Get document object 
   * Include module css file for categories module */
  $document = JFactory::getDocument ();
  $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/mod_stylesheet.min.css' );
}

/** Get site settings from categories module file */
$dispenable = $result_settings ;
$seoOption = $dispenable ['seo_option'];

/** Categories Module display starts */
?>
<div class="video_module module_menu <?php echo $class; ?> ">
	<ul class="menu">
		<?php if (count ( $result ) > 0) {
    foreach ( $result as $row ) {
      $oriname = $row->category;
      
      /** Category name changed here for seo url purpose */
      $newrname = explode ( ' ', $oriname );
      $link = implode ( '-', $newrname );
      $link1 = explode ( '&', $link );
      $category = implode ( 'and', $link1 );
      
      /** Get parent category details */
      $result1 = Modcategorylist::getparentcategory ( $row->id );
      
      /** Get item id for categories menu */
      $Itemid = getmenuitemid_thumb ( 'category', $row->id );
      
      /** For SEO settings in categories module */
      if ($seoOption == 1) {
        $featureCategoryVal = "category=" . $row->seo_category;
      } else {
        $featureCategoryVal = "catid=" . $row->id;
      }
      /** Display category names as list */
      ?>
				<li class="item27">
					<?php echo str_repeat('<span class="gi">|&mdash;</span>', $row->level) ?>	
					<a
			href="<?php
      echo JRoute::_ ( "index.php?Itemid=$Itemid&option=com_contushdvideoshare&view=category&" . $featureCategoryVal );
      ?>"> <span><?php echo $row->category; ?></span></a>
		</li>
				<?php
    }
  } else {
  /** If no cateogry found then display message */ 
    echo "<li class='hd_norecords_found'>No Category</li>";
  }
  ?>
	</ul>
</div>
<div class="clear"></div>
