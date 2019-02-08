<?php
/**
 * Show ads template file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */
/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');
/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
/** Get ads page list, filter option */ 
$arrAdsList     = $this->showads ['adsList'];
/** Get ads filter option */
$arrAdsFilter   = $this->showads ['adsFilter'];
/** Get ads page page navigation */
$arrAdsPageNav  = $this->showads ['pageNav'];
/** version comparision */
if (version_compare ( JVERSION, '1.6.0', 'le' )) { ?>
  <style> table tr td a img { width: 16px; }
  td.center,th.center,.center { text-align: center; float: none; }
  </style>
<?php  } ?>
<?php /** Adslayout starts */?>
<form action="" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"> 
<?php /** Fieldset for adslayout starts */?>
  <fieldset id="filter-bar" <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'class="btn-toolbar"';
  } ?>> 
  <?php /** Ads search container starts */?>
  <div class="filter-search fltlft" style="float: left;"> 
  <?php /** Display ads search box */ ?>
  <?php /** version comparision for search filter */ ?>
  <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
  <?php /** Serach text box for version 3.0.0 */ ?> 
    <input type="text" title="Search in module title." placeholder="Search Ads" id="ads_search" name="ads_search" style="float: left; margin-right: 10px;"> 
    <?php /** search button group container starts for version 3.0.0  */ ?> 
    <div class="btn-group pull-left">
    <?php /** search submit button for version 3.0.0  */ ?>  
      <button type="submit" class="btn hasTooltip"> <i class="icon-search"></i> </button> 
      <?php /** search clear button for version 3.0.0  */ ?>
      <button class="btn hasTooltip" onclick="document.getElementById('ads_search').value = ''; this.form.submit();" type="button"> 
        <i class="icon-remove"></i> 
      </button><?php /** search button group contianer ends for version 3.0.0  */ ?>  </div>
<?php } else { ?> 
<?php /** Search label*/ ?>
      <label for="ads_search" class="filter-search-lbl">Filter:</label>
      <?php /** Serach text box  */ ?> 
      <input type="text" title="Search in module title." 
      value=" <?php if (isset ( $arrAdsFilter ['ads_search'] )) { 
        echo $arrAdsFilter ['ads_search']; 
      } ?>" id="ads_search" name="ads_search">
      <?php /** search submit button */ ?>  
      <button type="submit" style="padding: 1px 6px;"><?php echo JText::_('Search'); ?></button> 
      <?php /** search clear button */ ?>
      <button onclick="document.getElementById('ads_search').value = ''; this.form.submit();" type="button" style="padding: 1px 6px;"> 
      <?php echo JText::_('Clear'); ?></button> <?php 
  } ?> 
    </div>
     <?php /** Ads search container ends */?>
     <?php /** Ads filter container starts */?> 
    <div class="filter-select fltrt" style="float: right;"> 
    <?php /** Display filter box based on ads status */ ?>
      <select onchange="this.form.submit()" class="inputbox" name="ads_status">
      <?php /** Ads default status */?> 
        <option selected="selected" value="">- Select Status -</option> 
         <?php /** Ads published status */?> 
        <option value="1" <?php if (isset ( $arrAdsFilter ['ads_status'] ) && $arrAdsFilter ['ads_status'] == '1') { 
          echo 'selected=selected'; 
        } ?>>Published</option> 
        <?php /** Ads unpublished status */?>
        <option value="2" <?php if (isset ( $arrAdsFilter ['ads_status'] ) && $arrAdsFilter ['ads_status'] == '2') { 
          echo 'selected=selected'; 
        } ?>>Unpublished</option> 
        <?php /** Ads trashed status */?>
        <option value="3" <?php if (isset ( $arrAdsFilter ['ads_status'] ) && $arrAdsFilter ['ads_status'] == '3') { 
          echo 'selected=selected'; 
        } ?>>Trashed</option> 
        </select>
        <?php /** select box for Ads type starts */?>
        <select onchange="this.form.submit()" class="inputbox" name="ads_type">
        <?php /** Display filter box based on ads type */ ?> 
        <option selected="selected" value="">- Select Ad Type -</option>
        <?php /** Pre/Post roll ads */ ?>  
        <option value="1" <?php if (isset ( $arrAdsFilter ['ads_type'] ) && $arrAdsFilter ['ads_type'] == '1') { 
          echo 'selected=selected'; 
        } ?>>Pre/Post Roll</option>
         <?php /** Mid Roll ads */ ?>   
        <option value="2" <?php if (isset ( $arrAdsFilter ['ads_type'] ) && $arrAdsFilter ['ads_type'] == '2') { 
          echo 'selected=selected'; 
        } ?>>Mid Roll</option> 
        <?php /** IMA ads */ ?>   
        <option value="3" <?php if (isset ( $arrAdsFilter ['ads_type'] ) && $arrAdsFilter ['ads_type'] == '3') { 
          echo 'selected=selected'; 
        } ?>>IMA</option> </select>
          <?php /** select box for Ads type ends */?> 
    </div> 
    <?php /** Ads filter container ends */?> 
  </fieldset> 
  <?php /** Fieldset for adslayout ends */?>
   <?php /** Ads grid display table starts */?>
  <table class="adminlist <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
    echo 'table table-striped'; 
  } ?>">
  <?php /** Display ads table head starts. */ ?> 
  <?php /** Display ads grid page headings */ ?>
    <thead> <tr> <th>#</th>
    <?php /** column to select the ads using checkbox */ ?>  
    <th <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
      echo 'class="center"'; 
    } ?>><input type="checkbox" name="toggle" value="" onClick=" <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { 
        ?> checkAll(<?php echo count($arrAdsList); ?>); <?php 
    } else {  ?> 
    Joomla.checkAll(this)
    <?php } ?>" /></th>
    <?php /** column for Ads title */ ?> 
    <th class="left"> <?php echo JHTML::_ ( 'grid.sort', 'Ad Name', 'adsname', @$arrAdsFilter ['filter_order_Dir_ads'], @$arrAdsFilter ['filter_order_ads'] ); ?> 
    </th>
    <?php /** column for Ads type */ ?>
    <th> <?php /** Grid sort for ads type */
    echo JHTML::_ ( 'grid.sort', 'Ad Type', 'typeofadd', @$arrAdsFilter ['filter_order_Dir_ads'], @$arrAdsFilter ['filter_order_ads'] ); ?> 
        </th>
        <?php /** column for Ads path */ ?> 
    <th>Ad Video Path</th>
    <?php /** column to publish or unpublish a Ad. */ ?>
    <th 
    <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
      echo 'class="center"'; 
    } ?>>
    <?php /** Grid sort for ads status. */ ?>
    <?php echo JHTML::_ ( 'grid.sort', 'Published', 'published', @$arrAdsFilter ['filter_order_Dir_ads'], @$arrAdsFilter ['filter_order_ads'] ); ?> 
    </th>
    <?php /** column for Ads click count. */ ?>
    <th <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
      echo 'class="center"'; 
    } ?>>
    <?php /** Grid sort for ads click count. */ ?> 
    <?php echo JHTML::_ ( 'grid.sort', 'Ad Visits', 'clickcounts', @$arrAdsFilter ['filter_order_Dir_ads'], @$arrAdsFilter ['filter_order_ads'] ); ?> 
    </th>
    <?php /** column for Ads impression count. */ ?>
     <th <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
      echo 'class="center"'; 
    } ?>>
     <?php /** Grid sort for ads impression count. */ ?>  
    <?php 
    echo JHTML::_ ( 'grid.sort', 'Impression Hits', 'impressioncounts', @$arrAdsFilter ['filter_order_Dir_ads'], @$arrAdsFilter ['filter_order_ads'] ); 
    ?> </th>
     <?php /** column for Ads id. */ ?>
    <th <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
      echo 'class="center"'; 
    } ?>>
     <?php /** Grid sort for ads ids. */ ?> 
    <?php 
    echo JHTML::_ ( 'grid.sort', 'ID', 'Id', @$arrAdsFilter ['filter_order_Dir_ads'], @$arrAdsFilter ['filter_order_ads'] ); 
    ?> </th> 
    </tr> 
  </thead>
  <?php /** Display ads table head ends. */ ?> 
  <?php /** Display ads table body starts. */ ?>  
  <tbody> <?php jimport ( 'joomla.filter.output' );
  /**  store the count of ads list */ 
  $n = count ( $arrAdsList );  
  for($i = 0; $i < $n; $i ++) {
  /** Array variable with Ads list */ 
    $arrAd = $arrAdsList [$i];
    /** Unserialize IMA ads details */
    $imaaddetail = unserialize ( $arrAd->imaaddet );
    /** Grid sort with Ads id */
    $checked = JHTML::_ ( 'grid.id', $i, $arrAd->id );
    /** Array varaible to store the process of states */
    $states = array (
        - 2 => array ( 'trash.png', 'messages.unpublish', 'JTRASHED', 'COM_MESSAGES_MARK_AS_UNREAD' ),
        1 => array ( 'tick.png', 'messages.publish', 'COM_MESSAGES_OPTION_READ', 'COM_MESSAGES_MARK_AS_UNREAD' ),
        0 => array ( 'publish_x.png', 'messages.unpublish', 'COM_MESSAGES_OPTION_UNREAD', 'COM_MESSAGES_MARK_AS_READ' ) 
    );
    /** Version comparison for publish grid */    
    if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
    /** set published grid varaible for version greater than 1.6.0 */
      $published = JHtml::_ ( 'jgrid.published', $arrAd->published, $i );
    } else {
    /** set published grid varaible */
      $published = JHtml::_ ( 'grid.published', $arrAd, $i, $states [$arrAd->published] [0], $states [$arrAd->published] [0], '', 'cb' );
    }
    /** set ad's layout link for editing */    
    $link = JRoute::_ ( 'index.php?option=com_contushdvideoshare&layout=ads&task=editads&cid[]=' . $arrAd->id );
    /** Check the type of Ad */
    if (($arrAd->typeofadd == 'prepost') || ($arrAd->typeofadd == '')) {
      /** set $videoType to 'Pre/post-roll' */
      $videoType = 'Pre/Post-roll Ad';
      /** Store Pre/post-roll ad path  */
      $videoPath = $arrAd->postvideopath;
    } elseif (($arrAd->typeofadd == 'ima')) {
      /** set $videoType to 'Ima' */
      $videoType = 'IMA Ad';
      /** check string length of the IMA Ad patth  */
      if (strlen ( $imaaddetail ['imaadpath'] ) > 45) {
        /** set videopath for IMA ad > 45 */
        $videoPath = (substr ( $imaaddetail ['imaadpath'], 0, 45 )) . '..';
      } else {
      /** set videopath for IMA ad */
        $videoPath = $imaaddetail ['imaadpath'];
      }
    } else {
    /** set $videoType to 'Mid-roll' */
      $videoType = 'Mid-roll Ad';
      /** set Midroll ad path */
      $videoPath = '';
    }
    /** Display checkbox to select ads */ 
    ?>
    <tr class="<?php echo 'row' . ($i % 2); ?>"> 
    <td class="center"><?php echo $i + 1; ?></td> 
    <td class="center"><?php echo $checked; ?></td> 
    <td class="left"><a href=" <?php
    echo $link;
    ?>">  <?php /** Display ads title */
    echo $arrAd->adsname;
    ?></a></td>
    <?php /** Display Ads type. */ ?>
<td
class="<?php
/** version comparison to assing class to videotype row */
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'left';
    } else {
      echo 'center';
    }
    ?>">
<?php echo $videoType; ?>
</td>
<?php /** Display ads video path */ ?>
<td class="left"><?php echo $videoPath; ?>
</td>
<?php /** Display ads video status */ ?>
<td class="center"><?php echo $published; ?>
</td>
<?php /** Display ads video clickcounts */ ?>
<td class="center"><?php echo $arrAd->clickcounts; ?>
</td>
<?php /** Display ads video impression counts */ ?>
<td class="center"><?php echo $arrAd->impressioncounts; ?>
</td>
<?php /** Display ads video id */ ?>
<td class="center"><?php echo $arrAd->id; ?>
</td>
</tr>
<?php
  }
  ?>
</tbody>
<?php /** Display ads table body ends. */ ?> 
<?php /** Display ads table footer starts. */ ?> 
<tfoot>
<?php /** Display pagination for ads page */ ?>
<td colspan="15"><?php echo $arrAdsPageNav->getListFooter(); ?></td>
</tfoot>
<?php /** Display ads table footer ends. */ ?> 
</table>
<?php /** Ads grid display table starts */?>
<?php /** Hidden fields for filter ads order */?>
<input type="hidden" name="filter_order"
value="<?php echo @$arrAdsFilter['filter_order_ads']; ?>" />
<?php /** Hidden fields for filter ads order direction */?> 
<input
type="hidden" name="filter_order_Dir"
value="<?php echo @$arrAdsFilter['filter_order_Dir_ads']; ?>" /> <input
type="hidden" name="task" value="" />
<?php /** Hidden fields for ads field checked */?> 
<input type="hidden"
name="boxchecked" value="0"> <input type="hidden" name="submitted"
value="true" id="submitted">
</form>
<?php /** Adslayout ends */?>
