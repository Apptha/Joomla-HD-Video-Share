<?php
/**
 * Category template file
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

/** Check joomla version and add styles for category page */
if (version_compare ( JVERSION, '1.6.0', 'le' )) { ?>
  <style> 
  table tr td a img { width: 16px; }
  td.center,th.center,.center { text-align: center; float: none; }
  </style>
<?php
}

/** Check whether the task is add or edit */
if (JRequest::getVar ( 'task' ) == 'edit' || JRequest::getVar ( 'task' ) == 'add') { 
/** Form for add category begins */ ?>
<form action='index.php?option=com_contushdvideoshare&layout=category' method="POST" name="adminForm" id="adminForm" style="position: relative;"> 
  <fieldset class="adminform"> 
    <legend>Category</legend> 
    <table class="admintable"> 
      <tr> 
      <?php /** Display paren category field to select parent */ ?>
        <td class="key">Parent Category</td> 
        <td><select id="parent_id" name="parent_id"> 
          <option id="" value="0">Main</option> 
          <?php /** Looping to display categories in drop down */ 
          foreach ( $this->categorylist as $val ) { 
            $selected = '';
            
            if ($this->category->parent_id == $val->value) { 
              $selected = 'selected="selected"'; 
            } ?>
            <option id="<?php echo $val->value; ?>" 
            value="<?php echo $val->value; ?>" 
            <?php echo $selected; ?>> 
            <?php echo $val->text; ?> </option> 
    <?php } ?> 
            </select></td> 
       </tr> 
       <?php /** Display category name field */ ?>
       <tr> <td class="key">Category Name</td> 
          <td><input type="text" name="category" id="category" size="32" maxlength="250" value="<?php echo $this->category->category; ?>" /> </td> 
       </tr> 
       <?php /** Display category status field */ ?>
       <tr> <td class="key">Status</td> 
         <td><select name="published" id="published"> <option value="1" <?php if ($this->category->published == 1 || $this->category->published == '') { 
           echo 'selected'; 
         } ?>>Published</option> 
         <option value="0" <?php if ($this->category->published == 0 && $this->category->published != '') { 
           echo 'selected'; 
         } ?>>Unpublished</option> 
         <option value="-2" <?php if ($this->category->published == - 2) { 
           echo 'selected'; 
         } ?>>Trashed</option> 
         </select></td> 
       </tr> 
      </table> 
     </fieldset> 
     <input type="hidden" name="option" value="<?php echo JRequest::getVar('option'); ?>" /> 
     <input type="hidden" value="<?php echo $this->category->id; ?>" name="id" /> 
     <input value="" type="hidden" name="task" />
</form>
<?php /** Form for add category end */
} else {
  /** Get category values */ 
  $category           = $this->category;
  $arrCategoryFilter  = $this->category ['categoryFilter'];
  $limitstart         = $this->category ['limitstart'];
  $limit              = $this->category ['limit'];
  $baseurl            = JURI::base ();
  $document           = JFactory::getDocument ();
  
  /** Add stylesheet for cateogry page */
  $document->addStyleSheet ( 'components/com_contushdvideoshare/css/styles.css' );
  
  /** Check joomla versiona dn load js file based on the version */
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    JHtml::_ ( 'jquery.ui', array ( 'core', 'sortable' ) );
  } else {
    $document->addScript ( 'components/com_contushdvideoshare/js/jquery-1.3.2.min.js' );
    $document->addScript ( 'components/com_contushdvideoshare/js/jquery-ui-1.7.1.custom.min.js' );
  }
  
  /** Calculate current page values */
  if (! empty ( $limit )) {
    $current_page = ceil ( ($limitstart + 1) / $limit );
  } else {
    $current_page = 1;
  }  ?>
<script type="text/javascript">
  var dragdr      = jQuery.noConflict();
  var categoryid  = new Array(); 
  var curPage     = "<?php echo $current_page; ?>";
  var baseURL     = "<?php echo $baseurl; ?>";
  var URL         =  baseURL + "/index.php?option=com_contushdvideoshare&task=category&layout=sortorder&pagenum=" + curPage;
  dragdr(document).ready(function() { 
     dragdr("#test-list").sortable({ 
        handle: '.handle', 
        update: function() { 
           var order = dragdr('#test-list').sortable('serialize'); 
           orderid = order.split("listItem[]="); 
           for (i = 1; i < orderid.length; i++) { 
              categoryid[i] = orderid[i].replace('&', ""); 
              oid = "ordertd_" + categoryid[i]; 
              document.getElementById(oid).innerHTML = ((<?php echo $current_page; ?> * 20) + (i - 1)) - 20; 
           } 
           dragdr.post(URL, order); 
        } 
     }); 
  }); 
</script>
<?php /** Form for display category begin */ ?>
<form action='index.php?option=com_contushdvideoshare&layout=category' method="POST" name="adminForm" id="adminForm"> 
  <fieldset id="filter-bar" <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
    echo 'class="btn-toolbar"'; 
  } ?>> 
  <div class="filter-search fltlft" style="float: left;"> 
  <?php /** Display category search box */ 
  if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
      <label for="category_search" class="filter-search-lbl">Filter:</label> 
      <input type="text" title="Search in module title." value="<?php if (isset ( $arrCategoryFilter ['category_search'] )) { 
        echo $arrCategoryFilter ['category_search']; 
      } ?>" id="category_search" name="category_search"> 
      <button type="submit" style="padding: 1px 6px;"><?php echo JText::_('Search'); ?></button> 
      <button onclick="document.getElementById('category_search').value = ''; this.form.submit();" type="button" style="padding: 1px 6px;"> 
      <?php echo JText::_('Clear'); ?></button> 
<?php } else { ?>
    <input type="text" title="Search in module title." placeholder="Search Category" id="category_search" style="float: left; margin-right: 10px;" name="category_search"> 
    <div class="btn-group pull-left"> 
      <button type="submit" class="btn hasTooltip"> 
        <i class="icon-search"></i> 
      </button> 
      <button onclick="document.getElementById('category_search').value = ''; this.form.submit();" type="button" class="btn hasTooltip"> 
        <i class="icon-remove"></i> 
      </button> 
    </div> 
<?php } ?>
  </div> 
  <?php /** Display status filter field to filter categories */ ?>
  <div class="filter-select fltrt" style="float: right;"> 
    <select onchange="this.form.submit()" class="inputbox" name="category_status"> 
      <option selected="selected" value="0">- Select Status -</option> 
      <option value="1" <?php if (isset ( $arrCategoryFilter ['category_status'] ) && $arrCategoryFilter ['category_status'] == '1') { 
        echo 'selected=selected'; 
      } ?>>Published</option> 
      <option <?php if (isset ( $arrCategoryFilter ['category_status'] ) && $arrCategoryFilter ['category_status'] == '2') { 
        echo 'selected=selected'; 
      } 
      ?> value="2" >Unpublished</option> 
      <option <?php  if (isset ( $arrCategoryFilter ['category_status'] ) && $arrCategoryFilter ['category_status'] == '3') { 
        echo 'selected=selected'; 
      } ?> value="3" >Trashed</option> 
    </select> 
  </div> 
</fieldset> 
<table class="adminlist <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
  echo 'table table-striped'; 
} ?>"> 
<?php /** Display grid page headings to sort categories */ ?>
  <thead> 
    <tr> <th width="1%"><a href="#" onclick="Joomla.tableOrdering('a.ordering', 'asc', '');" class="hasTip" title=""> <i class="icon-menu-2"></i> </a></th> 
      <th width="10"><input type="checkbox" name="toggle" value="" onclick="<?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
      checkAll(<?php echo count($category['categorylist']); ?>) 
      <?php } else { ?> 
      Joomla.checkAll(this) 
      <?php } ?>" /></th> 
      <th> <?php echo JHTML::_('grid.sort', 'Category', 'a.category', @$arrCategoryFilter['order_Dir'], @$arrCategoryFilter['order']); ?> </th> 
      <th> <?php echo JHTML::_ ( 'grid.sort', 'Order', 'a.ordering', @$arrCategoryFilter ['order_Dir'], @$arrCategoryFilter ['order'] ); ?> </th> 
      <th> <?php echo JHTML::_('grid.sort', 'Status', 'a.published', @$arrCategoryFilter['order_Dir'], @$arrCategoryFilter['order']); ?> </th> 
      <th width="10"> <?php echo JHTML::_('grid.sort', 'ID', 'a.id', @$arrCategoryFilter['order_Dir'], @$arrCategoryFilter['order']); ?> </th> 
    </tr> 
  </thead> 
  <tbody id="test-list" <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
    echo 'class="ui-sortable"'; 
  } ?>> <?php 
  foreach ( $category ['categorylist'] as $i => $item ) { 
    $states = array ( 
            -2 => array ( 'trash.png', 'messages.unpublish', 'JTRASHED', 'COM_MESSAGES_MARK_AS_UNREAD' ), 
            1 => array ( 'tick.png', 'messages.publish', 'COM_MESSAGES_OPTION_READ', 'COM_MESSAGES_MARK_AS_UNREAD' ), 
            0 => array ( 'publish_x.png', 'messages.unpublish', 'COM_MESSAGES_OPTION_UNREAD', 'COM_MESSAGES_MARK_AS_READ' ) 
    );
    
    if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
      $published = JHtml::_ ( 'jgrid.published', $item->published, $i );
    } else {
      $published = JHtml::_ ( 'grid.published', $item, $i, $states [$item->published] [0], $states [$item->published] [0], '', 'cb' );
    }
    
    $link = JRoute::_ ( 'index.php?option=com_contushdvideoshare&layout=category&task=edit&cid[]=' . $item->value );
    $checked = JHTML::_ ( 'grid.id', $i, $item->value ); ?> 
    <tr id="listItem_<?php echo $item->value; ?>" 
      class="row<?php echo $i % 2; ?>"> 
      <td> <p class="hasTip content" title="Click and Drag" style="padding: 6px;"> 
      <img src="<?php echo JURI::base() . 'components/com_contushdvideoshare/images/arrow.png'; ?>" 
      alt="move" width="16" height="16" class="handle" /> 
      </p> </td> 
      <td><?php echo $checked; ?></td> 
      <td> <?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level) ?> 
      <a href="<?php echo $link; ?>"> 
      <?php echo $this->escape($item->text); ?></a> </td> 
      <td id="<?php echo $item->value; ?>" align="center" style="width: 20px;"><p style="padding: 6px;" 
      id="ordertd_<?php echo $item->value; ?>">
      <?php echo $item->ordering; ?></td> 
      <td align="center" style="width: 70px;"><?php echo $published; ?></td> 
      <td align="center" style="width: 90px;"><?php echo $item->value; ?></td> 
    </tr>
<?php } ?>
    </tbody> 
    <tfoot> 
      <td colspan="15"><?php echo $this->category['pageNav']->getListFooter(); ?></td> 
    </tfoot> 
</table> 
  <input type="hidden" name="task" value="" />  <input name="filter_order" type="hidden" value="<?php echo $arrCategoryFilter['order']; ?>" /> 
  <input type="hidden" name="filter_order_Dir" value="<?php echo $arrCategoryFilter['order_Dir']; ?>" />  <input type="hidden" value="0" name="boxchecked" /> 
  <input type="hidden" name="hidemainmenu" value="0" /> <input type="hidden" name="parent_id" value="-1" />
</form>
<?php /** Form for display category ends */
} ?>
<script language="JavaScript" type="text/javascript">
<?php if (version_compare ( JVERSION, '1.6.0', 'ge' )) { ?> 
    Joomla.submitbutton = function(pressbutton) {
<?php } else { ?> 
    function submitbutton(pressbutton) {
<?php } ?> 
       if (pressbutton == "save") { 
          if (document.getElementById('category').value == "") { 
             alert("<?php echo JText::_('You must provide a category name', true); ?>") 
             return; 
          }
       submitform(pressbutton); 
       return; 
       } 
       submitform(pressbutton); 
       return; 
    }
</script>
