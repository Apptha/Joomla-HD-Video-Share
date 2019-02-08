<?php
/**
 * Show videos template file
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
/** Define constants for show videos layout file */
define ('COMMENT', 'comment');
define ('DISABLE', 'Disable');
define ('ENABLE', 'Enable');
define ('STATE_FILTER', 'state_filter');
define ('FEATURED_FILTER', 'featured_filter');
define ('LISTS', 'lists');
define ('SELECTED', 'selected=selected');
/** Import joomla filter library */
jimport ( 'joomla.filter.output' );
/** Variable initialization */
$videolist1 = $Itemid = '';
/** Get video list and itemid details */
if (isset ( $this->videolist )) {
  $videolist1 = $this->videolist;
}
if (isset ( $this->Itemid )) {
  $Itemid = $this->Itemid;
}
/** Get site and component base URL */
$baseurl    = JURI::base ();
$thumbpath1 = JURI::base () . "/components/com_contushdvideoshare";
/** Import joomla tooltip library and array values */
JHTML::_ ( 'behavior.tooltip' );
/** Declare the tooltip array variable **/
$toolTipArray = array ( 'className' => 'custom2', 'showDelay' => '0', 'hideDelay' => '500', 'fixed' => 'true',
    'onShow' => "function(tip) {tip.effect('opacity',{duration: 500, wait: false}).start(0,1)}",
    'onHide' => "function(tip) {tip.effect('opacity',{duration: 500, wait: false}).start(1,0)}" 
);
/** Class="hasTip2" titles */
JHTML::_ ( 'behavior.tooltip', '.hasTip2', $toolTipArray );
/** Get document object and include css files */
$document = JFactory::getDocument ();
/** Include cc.css to the  head of the document */
$document->addStyleSheet ( 'components/com_contushdvideoshare/css/cc.css' );
/** Include style.css to the  head of the document */
$document->addStyleSheet ( 'components/com_contushdvideoshare/css/styles.css' );
/** Include js file based on the joomla version */
if (version_compare ( JVERSION, JOOM3, 'ge' )) {
  /** Include jquery.ui to the header of the document for joomle version  greater version */
  JHtml::_ ( 'jquery.ui', array ( 'core', 'sortable' ) );
} else {
  /** Include jquery-1.3.2.min.js file to the head of the document */
  $document->addScript ( 'components/com_contushdvideoshare/js/jquery-1.3.2.min.js' );
  /** Include jquery-ui-1.7.1.custom.min.js file to the head of the document */
  $document->addScript ( 'components/com_contushdvideoshare/js/jquery-ui-1.7.1.custom.min.js' );
}
/** Get option value*/
$option   = JRequest::getCmd ( 'option' );
/** Get user value*/
$user     = JRequest::getVar ( 'user' );
/** Check user is admin */
$userUrl  = ($user == 'admin') ? "&user=$user" : "";
/** Get page value from request */
$page     = JRequest::getVar ( 'page', '', 'get', 'string' );
/** Get limit values */
if (! empty ( $videolist1 ['limit'] )) {
  /** set limit value based on the videolist array result */
  $current_page = ceil ( ($videolist1 ['limitstart'] + 1) / $videolist1 ['limit'] );
} else {
  /** set default value for current page */
  $current_page = 1;
}
/** Joomal version comparison */
if (version_compare ( JVERSION, '1.6.0', 'le' )) {
/** Declare internal style to list videos */
?>
<style>
table tr td a img { width: 16px; }
td.center,th.center,.center { text-align: center; float: none; }
</style>
<?php } ?>
<style>
fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
#commentlist tr td { text-align: left; }
#commentlist .pagination div.limit { float: none; }
</style>
<script type="text/javascript">
  <?php /** When the document is ready set up our sortable with it's inherant function(s) */ ?>
  var dragdr = jQuery.noConflict();
  <?php /** Declare array valirable for videoid */ ?>
  var videoid = new Array();
  dragdr(document).ready(function() {
  <?php /** Table sort function */ ?>
  dragdr("#test-list").sortable({
    handle: '.handle',
    <?php /** Function to call while updating */ ?>
    update: function() {
    	<?php /** Get dragorder */ ?>
    var order = dragdr('#test-list').sortable('serialize');
    orderid = order.split("listItem[]=");
    for (i = 1; i < orderid.length; i++){
    <?php /** videoid */ ?>
    videoid[i] = orderid[i].replace('&', "");
    <?php /** Order id */ ?>
    oid = "ordertd_" + videoid[i];
    document.getElementById(oid).innerHTML = ((<?php echo $current_page; ?> * 20) + (i - 1)) - 20;
    }
    <?php /** set link for sorting */ 
    $dragURL = $baseurl . '/index.php?option=com_contushdvideoshare&task=videos&layout=sortorder&pagenum=' . $current_page; ?>
    dragdr.post("<?php echo $dragURL; ?>", order);
  }
  });
  });
</script>
<script language="javascript">
function deletecomment(cmtid, vid, user) {
  <?php /** Declare userurl variable. */ ?>
   var userurl = ''; 
   if (user == '1') {
	   <?php /** set userurl */ ?> 
      userurl = '&user=admin'; 
   }
   <?php /** set link to redirect to comment view section. */ ?> 
   window.open('index.php?option=com_contushdvideoshare&layout=adminvideos' + userurl + '&page=comment&id=' + vid + '&cmtid=' + cmtid, '_self', false); 
}
</script>
<?php /** Check page is comment to display details */ 
if ($page == COMMENT && $page != 'myvideos' && $page != 'deleteuser') {
  /** Declare comment varaible. */
  $cmd = $this->comment [COMMENT];
  /** store total count of comment */
  $tot = count ( $cmd );
  /** Get the varaible ID */
  $id = JRequest::getVar ( 'id', '', 'get', 'int' );  
  if ($id) {
  /** Set currentid */
    $cid = "&id=" . $id;
  }
  /** set form action */
  $formAction = "index.php?option=". $option . "&layout=adminvideos". $userUrl. "&page=comment" . $cid ; ?>
  <?php /** Display video and comment grid section starts */ ?>
<form action="<?php echo $formAction; ?>" method="post" id="adminForm"
name="adminForm">
<div id="videocontent" align="center">
<div class="videocont">
<div class="clearfix">
<?php /** Display video title */ ?>
<h1><?php echo $this->comment['videotitle']; ?></h1>
</div>
<h2>Comments</h2>
<?php /** Grid display of comment start. */ ?>
<table border="0" width="600" id="commentlist">
<?php
/** Check if total value is greater tha 0 */
  if ($tot > 0) {
  /** Foreach statement for the comments **/
    foreach ( $this->comment [COMMENT] as $row ) {
    /** check if parent id is 0 */
      if ($row->parentid == 0) {
        ?>
<tr>
<td> <?php /** Display comment message */ ?>
<div class="clearfix">
<p class="subhead" style="color: #132855;">
<?php /** Display user name of the comment */ ?>
<b><?php echo $row->name; ?> :  </b>
</p>
</div>
<?php /** Display comment message */ ?>
<p> <?php echo $string = nl2br($row->message); ?></p>
</td>
<?php /** Display delete option */ ?>
<?php /** Column for delete button */ ?>
<td valign="center"
<?php /** compare Jommla version. */ ?>
<?php if (version_compare ( JVERSION, JOOM3, 'ge' )) { 
  echo 'class="videoshare_closeimage"'; 
} ?>
align="center"><img id="<?php echo $row->id; ?>"
src="components/com_contushdvideoshare/images/delete_x.png"
title="Delete"
onclick="deletecomment(id,<?php echo ($userUrl) ? "$row->videoid,1" : "$row->videoid,0"; ?>);"
style="cursor: pointer;" /></td>
</tr>
<tr>
<td colspan="2"><hr></td>
</tr>
<?php
} else {
        ?>
<tr>
<td><?php /** Display user name */ ?>
<p>
<strong>Re : <span style="color: #132855;"><?php echo $row->name; ?></span></strong>
</p>
<?php /** display comment message of the user */ ?>
<p><?php echo $string = nl2br($row->message); ?></p>
<?php /** column for  delete image for the video.. */ ?>
</td>
<td valign="center" align="center"><img
id="<?php echo $row->id; ?>"
src="components/com_contushdvideoshare/images/delete_x.png"
onclick="deletecomment(id,<?php echo ($userUrl) ? "$row->videoid,1" : "$row->videoid,0"; ?>);"
style="cursor: pointer;" /></td>
<tr>
<td colspan="2"><hr
style="background-color: #fff; border: 1px dotted #132855; border-style: none none dotted;"></td>
</tr>
<?php
      }
    }
  }
  ?>
  <?php /** Table footer starts */ ?>
 <tfoot>
  <?php /** Pagination for the video. */ ?>
<td colspan="2"><?php echo $this->comment['pageNav']->getListFooter(); ?></td>
</tfoot>
 <?php /** Table footer ends */ ?>
</table>
<?php /** Grid display of commnent ends. */ ?>
</div>
</div>
 <?php /** Hidden field for the videoid */ ?>
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
 <?php /** Hidden field for the submitted value true */ ?>
<input type="hidden" name="submitted" value="true" id="submitted" />
 <?php /** Hidden field for the task value as 1. */ ?> 
<input type="hidden" name="task" id="task" value="1" />
 <?php /** Hidden field for the boxes checked */ ?> 
<input type="hidden" name="boxchecked" value="0" />
 <?php /** Genrates Form token */ ?>
<?php echo JHTML::_('form.token'); ?>
</form>
<?php /** Display video and comment grid section ends */ ?>
<?php
} else {
/** Display videos section starts */
  ?>
<form
action="index.php?option=com_contushdvideoshare&layout=adminvideos<?php echo $userUrl; ?>"
method="post" id="adminForm" name="adminForm">
<fieldset id="filter-bar"
<?php /** Display search box */
/** compare Joomla version for greater than 3 */  
if (version_compare ( JVERSION, JOOM3, 'ge' )) {
  /** set the class for button toolbar */ 
  echo 'class="btn-toolbar"'; 
} ?>>
<?php /** Display filter options */ ?>
<div class="filter-search fltlft" style="float: left;">
<?php  if (! version_compare ( JVERSION, JOOM3, 'ge' )) { 
?><?php /** Filter label */ ?><label
for="search" class="filter-search-lbl">Filter:</label> <input
type="text" title="Search in module title."
value="<?php if (isset ( $videolist1 [LISTS] [SEARCH] )) { 
  echo $videolist1 [LISTS] [SEARCH]; 
} ?>"
id="search" name="search">
<?php /** search button */ ?>
<button type="submit" style="padding: 1px 6px;"><?php echo JText::_(SEARCH); ?></button>
<?php /** search clear button */ ?>
<button
onclick="document.getElementById('search').value = ''; this.form.submit();"
type="button" style="padding: 1px 6px;"> <?php echo JText::_ ( 'Clear' ); ?></button>
<?php
  } else {
    ?>
    <?php /** Search field for the videos */ ?>
<input type="text" title="Search in module title."
value="<?php if (isset ( $videolist1 [LISTS] [SEARCH] )) { 
  echo $videolist1 [LISTS] [SEARCH]; 
} ?>"
placeholder="Search Videos" id="search" name="search"
style="float: left; margin-right: 10px;">
<?php /** Submit button for the search. */ ?>
<div class="btn-group pull-left">
<button type="submit" class="btn hasTooltip">
<?php /** Search icon image */ ?>
<i class="icon-search"></i>
</button>
<?php /** clear button for the search field. */ ?>
<button
onclick="document.getElementById('search').value = ''; this.form.submit();"
type="button" class="btn hasTooltip">
<?php /** Search clear icon image. */ ?>
<i class="icon-remove"></i>
</button>
</div>
<?php
  }
  ?>
</div>
<?php /** Display status filter option */ ?>
<?php /** Version compare for Filter options */ ?>
<div
class="<?php if (! version_compare ( JVERSION, JOOM3, 'ge' )) { 
?>filter-select fltrt<?php 
} else { 
?>btn-group pull-right hidden-phone<?php 
} ?>"
			style="float: right; margin-left: 5px;">
			<select onchange="this.form.submit()"
				class="<?php if (! version_compare ( JVERSION, JOOM3, 'ge' )) { 
			?>inputbox<?php 
} else { 
?>input-medium chzn-done<?php 
} ?>"
name="filter_state">
<?php /** Default filter state */ ?>
<option selected="selected" value="">- Select Status -</option>
<?php /** Filter state for published. */ ?>
<option value="1"
<?php if (isset ( $videolist1 [LISTS] [STATE_FILTER] ) && $videolist1 [LISTS] [STATE_FILTER] == '1') { 
  echo SELECTED; 
} ?>>Published</option>
<?php /** Filter state for unpublished. */ ?>
<option value="2"
<?php if (isset ( $videolist1 [LISTS] [STATE_FILTER] ) && $videolist1 [LISTS] [STATE_FILTER] == '2') { 
  echo SELECTED; 
} ?>>Unpublished</option>
<?php /** Filter state for trashed. */ ?>
<option value="3"
<?php if (isset ( $videolist1 [LISTS] [STATE_FILTER] ) && $videolist1 [LISTS] [STATE_FILTER] == '3') { 
  echo SELECTED; 
} ?>>Trashed</option>
</select>
<?php
 /** Version comparison for filter options Featured video submit option  */ 
  if (! version_compare ( JVERSION, JOOM3, 'ge' )) {
    ?>
<select name="filter_featured" class="inputbox"
onchange="this.form.submit()">
<?php
  } else {
    ?>
</div>
<?php /** Display featured filter option */ ?>
<div class="btn-group pull-right hidden-phone">
<select name="filter_featured" class="input-medium chzn-done"
onchange="this.form.submit()">
<?php } ?>
<?php /** Deafult filter state for featured */ ?>
<option value="">- Select Featured -</option>
<?php /** Filter state for featured videos. */ ?>
<option value="1"
<?php if (isset ( $videolist1 [LISTS] [FEATURED_FILTER] ) && $videolist1 [LISTS] [FEATURED_FILTER] == '1') { 
  echo SELECTED; 
} ?>>Featured</option>
<?php /** Filter state for unfeatured videos. */ ?>
<option value="2"
<?php if (isset ( $videolist1 [LISTS] [FEATURED_FILTER] ) && $videolist1 [LISTS] [FEATURED_FILTER] == '2') { 
  echo SELECTED; 
} ?>>Unfeatured</option>
</select>
<?php
/** version comparision for video category */
  if (! version_compare ( JVERSION, JOOM3, 'ge' )) {
    ?>
<select name="filter_category" class="inputbox"
onchange="this.form.submit()">
<?php } else { ?>
</div>
<?php /** Display category filter option */ ?>
<div class="btn-group pull-right hidden-phone">
<select name="filter_category" class="input-medium chzn-done"
onchange="this.form.submit()">
<?php } ?>
<?php /** Default state for video category */ ?>
<option value="">- Select Category -</option>			
<?php
/** foreach statement for video categories */
foreach ( $videolist1 ['rs_showplaylistname'] as $arrCategories ) {
  ?>
  <?php /** display the list for categories */ ?>		
<option value="<?php echo $arrCategories->id; ?>"
<?php if (isset ( $videolist1 [LISTS] ['category_filter'] ) && $videolist1 [LISTS] ['category_filter'] == $arrCategories->id) { 
  echo SELECTED; 
} ?>><?php echo $arrCategories->category; ?></option>					
	<?php
  }
  ?>
</select>
</div>
</fieldset>
<?php /** Grid display of videos start. */ ?>
<table
class="adminlist <?php if (version_compare ( JVERSION, JOOM3, 'ge' )) { 
  echo "table table-striped"; 
} ?>">
<?php /** Videos grid table header start */ ?>
<thead>
<?php /** display check box to select all videos */
  if (! version_compare ( JVERSION, JOOM3, 'ge' )) {
    ?>
 <?php /** Sorting column */ ?>
<th width="5%">Sorting</th>
<?php /** video select checkbox column. */ ?>
<th width="2%"><input type="checkbox" name="toggle" value=""
onClick="<?php if (! version_compare ( JVERSION, JOOM3, 'ge' )) { ?>
checkAll(<?php echo count($videolist1['rs_showupload']); ?>);<?php 
} else { ?>
Joomla.checkAll(this);
<?php } ?>" /></th>
<?php
  } else {
    ?>
    <?php /** Sorting column of videos */ ?>
<th width="1%"><a href="#"
onclick="Joomla.tableOrdering('a.ordering', 'asc', '');"
class="hasTip" title=""> <i class="icon-menu-2"></i></a></th>
<?php /** videos select checkbox column. */ ?>
<th width="1%"><input type="checkbox" name="toggle" value=""
onClick="Joomla.checkAll(this)" /></th><?php } ?>
<?php /** Display grid section titles */ ?>
<th class="left"><?php  echo JHTML::_ ( 'grid.sort', 'Title', 'a.title', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?></th>
<?php /** videos comment column */ ?>
<th width="5%"><?php echo JText::_('Comments'); ?></th>
<?php /** videos category column */ ?>
<th><?php  echo JHTML::_ ( 'grid.sort', 'Category', 'playlistid', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?></th>
<?php /** videos view column */ ?>
<th width="5%"><?php  echo JHTML::_ ( 'grid.sort', 'Viewed', 'times_viewed', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?></th>
<?php /** videos streamer option column */ ?>
<th><?php echo JText::_('Streamer Name'); ?></th>
<?php if (! JRequest::getVar ( 'user', '', 'get' )) { ?>
<?php /** videos Username column */ ?>
<th><?php echo JText::_('User Name'); ?></th>
<?php } ?>
<?php /** videos videolink column */ ?>
<th><?php echo JHTML::_ ( 'grid.sort', 'Video Link', 'videourl', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?></th>
<?php /** videos thumburl column */ ?>
<th><?php  echo JHTML::_ ( 'grid.sort', 'Thumb Link', 'thumburl', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?></th>
<?php /** videos postroll column */ ?>
<th>Postroll Ad</th>
<?php /** videos preroll column */ ?>
<th>Preroll Ad</th>
<?php /** videos midroll column */ ?>
<th>Midroll Ad</th>
<?php /** videos ordering column */ ?>
<th> <?php echo JHTML::_ ( 'grid.sort', 'Order', 'ordering', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?></th>
<?php /** videos status column */ ?>
<th width="4%"> <?php echo JHTML::_ ( 'grid.sort', 'Status', 'published', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?></th>
<?php /** videos featured column */ ?>
<th width="4%"><?php echo JHTML::_ ( 'grid.sort', 'Featured', 'featured', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?> </th>
<?php /** videos id column */ ?>
<th width="2%"> <?php echo JHTML::_ ( 'grid.sort', 'ID', 'Id', @$videolist1 [LISTS] ['order_Dir'], @$videolist1 [LISTS] ['order'] ); ?> </th>
</thead>
<?php /** Videos grid table header ends */ ?>
<?php /** Videos grid table body starts */ ?>
<tbody id="test-list"
<?php /** version comparision for to add class for sorting */ ?>
<?php if (version_compare ( JVERSION, JOOM3, 'ge' )) { 
  echo 'class="ui-sortable"'; 
} ?>>
<?php /** store video thumimage path. */ ?>
<?php $imagepath = JURI::base () . "components/com_contushdvideoshare/images";
/** static variable */
  $k = 0;
  /** Import filter option */
  jimport ( 'joomla.filter.output' );
  /** store pagination start value */
  $j = $videolist1 ['limitstart'];
  /** store total count of videolist1 array result */
  $n = count ( $videolist1 ['rs_showupload'] );
  /** check if array result is avaialble */
  if ($n >= 1) {
    for($i = 0; $i < $n; $i ++) {
      /** store result array in a variable */
      $row_showupload = $videolist1 ['rs_showupload'] [$i];
      /** store video is for selecting the checkbox */
      $checked = JHTML::_ ( 'grid.id', $i, $row_showupload->id );
      /** get the userId value */
      $userId = (JRequest::getVar ( 'user', '', 'get' ) == 'admin') ? "&user=" . JRequest::getVar ( 'user', '', 'get' ) : "";
      /** Set the link for editing the video. */
      $link = JRoute::_ ( 'index.php?option=com_contushdvideoshare&layout=adminvideos&task=editvideos' . $userId . '&cid[]=' . $row_showupload->id );
      /** Replace administrator baseurl with the component base url */
      $str1 = explode ( ADMINISTRATOR, JURI::base () );
      /** set videopath. */
      $videopath = $str1 [0] . "components/com_contushdvideoshare/videos/";
?>
<?php /** videoid row */ ?>
<tr id="listItem_<?php echo $row_showupload->id; ?>"
class="<?php echo 'row' . ($i % 2); ?>">
<td> <?php /** Display drag and drop option */ ?>
<p class="hasTip content" title="Click and Drag"
style="padding: 6px;">
<img src="<?php echo $imagepath . '/arrow.png'; ?>" alt="move"
width="16" height="16" class="handle" />
</p>
</td>
<?php /** Display check box for video */?>
<td class="center"><?php echo $checked; ?></td>
<?php /** Display video title */ ?>
<td class="left"><a href="<?php echo $link; ?>"
style="word-wrap: break-word; width: 130px; display: block;"><?php echo $row_showupload->title; ?></a></td>
<td align="center"><?php if (isset ( $row_showupload->cvid )) {
  /** call showvideo model **/ 
$model = $this->getModel ( 'showvideos' );
/** get the count of comments from the model function getCommentcount  */ 
$commentCount = $model->getCommentcount ( $row_showupload->cvid ); 
if ($row_showupload->cvid == $row_showupload->id) {
/** set comment url  */ 
        $commentUrl = 'index.php?option=' . $option . '&layout=adminvideos' . $userUrl . '&page=comment&id=' . $row_showupload->id; ?>
<a href="<?php echo $commentUrl ?>"> 
Comments(<?php echo $commentCount; ?>)</a>
<?php } 
} else { ?>
No Comments
<?php } ?></td>
<?php /** Display video category title */ ?>
<td class="center"><?php $showname = ""; 
($row_showupload->category == "" ? $showname = "None" : $showname = $row_showupload->category); 
echo $newtext = wordwrap ( $showname, 15, "\n", true ); ?></td>
<?php /** Display video hit count */ ?>
<td class="center"><?php echo $row_showupload->times_viewed; ?></td>
<?php /** Display video streaming value */ ?>
<td class="center"><?php echo $newtext = wordwrap($row_showupload->streameroption, 15, "\n", true); ?></td>
<?php if (! JRequest::getVar ( 'user', '', 'get' )) { 
/** Display user name */ ?>				
<td class="center"><?php echo $newtext = wordwrap($row_showupload->username, 15, "\n", true); ?></td>
<?php } ?>
<td class="center">
<?php
      /** explode administartor base url **/ 
      $str1 = explode ( ADMINISTRATOR, JURI::base () );
      $videopath1 = $str1 [0];
      /** set URL for the video player  */
      $videolink1 = 'index.php?Itemid=' . $Itemid . '&option=com_contushdvideoshare&view=player&id=' . $row_showupload->id . '&catid=' . $row_showupload->playlistid . '&adminview=true';
      /** concatenate the URL. */
      $videolink = $videopath1 . $videolink1;
      /** check filepath */      
      if ($row_showupload->filepath == "File" || $row_showupload->filepath == "FFmpeg") {
        /** store video url */
        $videolink2 = $row_showupload->videourl;        
        if ($videolink2 != "") {
/** Display video url */
          ?>
<?php /** set published status for the video. */?>
<a href="javascript:void(0)"
<?php if ($row_showupload->published == 1) { ?>
onclick="window.open('<?php echo $videolink; ?>', '', 'width=300,height=200,maximize=yes,menubar=no,status=no,location=yes,toolbar=yes,scrollbars=yes')"
<?php } ?>>
<?php echo $newtext = wordwrap($row_showupload->videourl, 15, "\n", true); ?></a>
<?php } else { ?> &nbsp; 
<?php }
      } elseif ($row_showupload->filepath == "Url" || $row_showupload->filepath == "Youtube") {
        $videolink2 = $row_showupload->videourl;
        
        if ($videolink2 != "") {
          ?>
          <?php /** set link for video player. */?>
<a href="javascript:void(0)"
onclick="window.open('<?php echo $videolink; ?>', '', 'width=600,height=500,maximize=yes,menubar=no,status=no,location=yes,toolbar=yes,scrollbars=yes')"> 
<?php echo $newtext = wordwrap($videolink2, 15, "\n", true); ?> </a>
<?php } else { ?> &nbsp; 
<?php } 
} ?>	
</td>
<?php /** Display video thumb url */ ?>
<td class="center"><?php
      $str1 = explode ( ADMINISTRATOR, JURI::base () );
      /** set thumbimage path. */
      $thumbpath1 = $str1 [0] . "components/com_contushdvideoshare/videos/";
      /** Get thumbimage based on the file path */      
      if ($row_showupload->filepath == "File" || $row_showupload->filepath == "FFmpeg" || $row_showupload->filepath == "Embed") {
         /** check if Amazons3 opton is enabled. */
        if (isset ( $row_showupload->amazons3 ) && $row_showupload->amazons3 == 1) {
          /** Get thumbimage url form Amazon s3 link */
          $thumblink2 = $videolist1 ['dispenable'] ['amazons3link'] . $row_showupload->thumburl;
        } else {
              /** Get thumbimage url */
          $thumblink2 = $thumbpath1 . $row_showupload->thumburl;
        }        
        if ($thumblink2 != "") {
          ?>
<a href="javascript:void(0)"
onclick="window.open('<?php echo $thumblink2; ?>', '', 'width=300,height=200,menubar=yes,status=yes,location=yes,toolbar=yes,scrollbars=yes')"> 
<?php echo $newtext = wordwrap($row_showupload->thumburl, 15, "\n", true); ?></a>
<?php } else { ?> &nbsp; <?php }
      } elseif ($row_showupload->filepath == "Url" || $row_showupload->filepath == "Youtube") {
        $thumblink2 = $row_showupload->thumburl;
        
        if ($thumblink2 != "") {
          ?>
<a href="javascript:void(0)"
onClick="window.open('<?php echo trim($thumblink2); ?>', '', 'width=600,height=500,menubar=yes,status=yes,location=yes,toolbar=yes,scrollbars=yes')">
<?php echo $newtext = wordwrap($thumblink2, 15, "\n", true); ?> </a>
<?php } else { 
?> &nbsp; 
<?php } 
} else { ?> &nbsp; 
<?php } ?></td>
<?php /** Display post and pre roll ad details */ ?>
<?php /** set postroll value of the video */ ?>
<td class="center"><?php if ($row_showupload->postrollads == 1) { 
  $postrollads = ENABLE; 
} else { 
$postrollads = DISABLE; 
} ?><?php echo $postrollads; ?></td>
<?php /** set preroll value of the video */ ?>
<td class="center"><?php if ($row_showupload->prerollads == 1) { 
  $prerollads = ENABLE; 
} else { 
$prerollads = DISABLE; 
} ?><?php echo $prerollads; ?></td>
<?php /** Display midroll ad details */ ?>
<td class="center"><?php 
if ($row_showupload->midrollads == 1) { 
$midrollads = ENABLE; 
} else { 
$midrollads = DISABLE; 
} ?>
<?php echo $midrollads; ?></td>
<?php /** Display video ordering */ ?>
<td id="<?php echo $row_showupload->id; ?>"><p style="padding: 6px;"
id="ordertd_<?php echo $row_showupload->id; ?>">
<?php echo $row_showupload->ordering; ?> </p></td>
<?php /** Display video status */ ?>
<td class="center"><?php
      $published = $row_showupload->published;
      
      if ($published == "1") {
        $pub = '<a title="Unpublish Item"
      onclick="return listItemTask(\'cb' . $i . '\',\'unpublish\')" href="javascript:void(0);">
<img src="components/com_contushdvideoshare/images/tick.png" /></a>';
      } else {
        $pub = '<a title="Publish Item"
onclick="return listItemTask(\'cb' . $i . '\',\'publish\')" href="javascript:void(0);">
<img src="components/com_contushdvideoshare/images/publish_x.png" /></a>';
      }
      ?><?php echo $pub; ?>
      <?php /** Display featured video status. */ ?>
      </td>
<td class="center"><?php /** Display videos featured section */				
      $featured = $row_showupload->featured;      
      if ($featured == "1") {
        $fimg = '<a title="Unfeatured Item" onclick="return listItemTask(\'cb' . $i . '\',\'unfeatured\')"
href="javascript:void(0);">
<img src="components/com_contushdvideoshare/images/tick.png" /></a>';
      } else {
        $fimg = '<a title="Featured Item" onclick="return listItemTask(\'cb' . $i . '\',\'featured\')"
					href="javascript:void(0);"><img src="components/com_contushdvideoshare/images/publish_x.png" /></a>';
      }
      ?><?php echo $fimg; ?></td>
<td class="center"><?php echo $row_showupload->id; ?></td>
</tr>
<?php $k = 1 - $k;
      $j ++;
    }
    ?>
<tr> <?php /** Display pagination for videos page */ ?>
<td colspan="17"><?php echo $videolist1['pageNav']->getListFooter(); ?></td>
</tr>
<?php
  }
  /** If condition for count */
  ?>
</tbody>
<?php /** Videos grid table body ends */ ?>
</table>
<?php /** Videos grid table  ends */ ?>
<?php /** To sort Table Ordering */ ?>
<?php /** sorting field and sorting order */ ?>
<input type="hidden" name="filter_order"
value="<?php echo $videolist1[LISTS]['order']; ?>" /><input
type="hidden" name="filter_order_Dir"
value="<?php echo $videolist1[LISTS]['order_Dir']; ?>" /><input
type="hidden" name="task" id="task" value="" /><input type="hidden"
name="boxchecked" value="0" /> 
<?php echo JHTML::_('form.token'); ?>
</form>
<?php
}
