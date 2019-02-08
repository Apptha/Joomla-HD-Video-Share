<?php
/**
 * Search module for HD Video Share
 *
 * This file is to display Search module 
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

/** Check whether the component is exist 
 * If not exist, then include module css file */ 
if (JRequest::getVar ( 'option' ) != 'com_contushdvideoshare') {
  $document = JFactory::getDocument ();
  $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/mod_stylesheet.min.css' );
}
$Itemid = getmenuitemid_thumb ('player','');

/** Display search form starts */
?>
<div class="module_menu module_videos <?php echo $class; ?> "> 
  <form name="hsearch" id="hsearch" method="post" enctype="multipart/form-data" 
  action="<?php echo JRoute::_('index.php?Itemid=' . $Itemid . '&option=com_contushdvideoshare&view=hdvideosharesearch'); ?>" >
  <?php /** Display search box to search videos */ ?> 
      <input type="text" value="<?php $searchtxtbox = JRequest::getVar ( 'searchtxtbox' );  
          if (isset ( $searchtxtbox )) {
            echo $searchtxtbox;
          } else {
            $searchval = JRequest::getVar ( 'searchval' );
            echo isset ( $searchval ) ? $searchval : '';
          }  ?>" name="searchtxtbox" id="searchtxtbox" class="clstextfield" onkeypress="validateenterkey(event, 'hsearch');" />
          <?php /** Display search button to submit search */ ?> 
      <input type="submit" name="search_btn" id="search_btn" class="button" value="<?php echo JText::_('HDVS_SEARCH'); ?>" /> 
      <input type="hidden" name="searchval" id="searchval" value=" <?php if (isset ( $searchtxtbox )) {
            echo $searchtxtbox;
          } else {
            $searchval = JRequest::getVar ( 'searchval' );
            echo isset ( $searchval ) ? $searchval : '';
          }
          ?>" /> 
  </form>
</div>
<?php /** Display search form ends */ ?>
<div class="clear"></div>
