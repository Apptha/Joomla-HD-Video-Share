<?php
/**
 * Popular videos view file
 *
 * This file is to dispaly Popular videos
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Rating array for popular videos */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );
/** Check user logged in */
$userId = (int) getUserID();
/** Get current page number for popular view */
$requestpage = JRequest::getInt ( 'video_pageid' );
$thumbview = unserialize ( $this->popularvideosrowcol [0]->thumbview );
$dispenable = unserialize ( $this->popularvideosrowcol [0]->dispenable );

/** Add style for popular videos page */
addStyleForViews ( $thumbview ['popularwidth'] );

/** Get item id and component path for popular videos page */
$Itemid = $this->Itemid;
$componentPath = 'index.php?option=com_contushdvideoshare';

/** Call function to display myvideos, myplaylists link for popular videos page*/
playlistMenu( $Itemid, JUri::getInstance() );
/** Popular videos page display starts */
?>
<div class="standard clearfix">
<?php /** Display popular videos page title */?>
  <h1 class="home-link hoverable"><?php echo JText::_('HDVS_POPULAR_VIDEOS'); ?></h1> 
  <div id="video-grid-container" class="clearfix">
<?php /** Calculate total records for popular videos page */
$totalrecords = $thumbview ['popularcol'] * $thumbview ['popularrow'];

if (count ( $this->popularvideos ) - 4 < $totalrecords) {
  $totalrecords = count ( $this->popularvideos ) - 4;
}

$no_of_columns = $thumbview ['popularcol'];
$pop_current_column = 1;
/** Looping for popular videos */
for($i = 0; $i < $totalrecords; $i ++) {
  $colcount = $pop_current_column% $no_of_columns;
  
  if ($colcount == 1 || $no_of_columns == 1) {
    echo '<ul class="ulvideo_thumb clearfix">';
  }
  
   /** Get thumb url for popular video thumbs */
  if ($this->popularvideos [$i]->filepath == "File" || $this->popularvideos [$i]->filepath == "FFmpeg" || $this->popularvideos [$i]->filepath == "Embed") {
    if (isset ( $this->popularvideos [$i]->amazons3 ) && $this->popularvideos [$i]->amazons3 == 1) {
      $src_path = $dispenable ['amazons3link'] . $this->popularvideos [$i]->thumburl;
    } else {
      $src_path = "components/com_contushdvideoshare/videos/" . $this->popularvideos [$i]->thumburl;
    }
  }
  
  if ($this->popularvideos [$i]->filepath == "Url" || $this->popularvideos [$i]->filepath == "Youtube") {
    $src_path = $this->popularvideos [$i]->thumburl;
  } 
  
  /** Display popular video page content */ ?> 
    <li class="video-item"> 
    <?php /** Display popular video thumbs */ ?>
      <div class="video_thumb_wrap"> 
      <?php /** Display watch later, add to playlist on search videos thumb */
		  displayVideoThumbImage ( $Itemid, $src_path, $this->popularvideos[$i], 'popular', 100 ); 

		  /** Display playlist popup, category title in search page*/ 
		  displayPlaylistPopup ( $this->popularvideos[$i], 'popular', $Itemid );
		  ?>
	
      </div> 
      <?php /** Display popular video title */ ?>
      <div class="video_thread"> 
      <?php /** Display videos title for popular video thumbs */
			displayVideoTitle ( $Itemid, $this->popularvideos[$i], '' );
			 
   /** Calculate rating for popular videos */ 
  if ($dispenable ['ratingscontrol'] == 1) {
    if (isset ( $this->popularvideos [$i]->ratecount ) && $this->popularvideos [$i]->ratecount != 0) {
      $ratestar = round ( $this->popularvideos [$i]->rate / $this->popularvideos [$i]->ratecount );
    } else {
      $ratestar = 0;
    }
    /** Display rating for popular videos */
    ?> 
    <div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div> 
    <?php  } 
    
	
    ?> 
    <?php if ($dispenable ['viewedconrtol'] == 1) { 
    /** Display popular videos views */ ?> 
    <span id="pop_page" class="floatright viewcolor"><?php echo $this->popularvideos[$i]->times_viewed; ?> 
    <?php echo JText::_('HDVS_VIEWS'); ?></span> 
    
    </div> 
    <?php } 
    ?> 
    </li> 
    <?php if ($colcount == 0) { 
      echo '</ul>'; 
      $pop_current_column= 0;
  }  
  $pop_current_column++;
} 
/** Popular video page content ends */ ?>
</div> </div>
<?php /** Tooltip Starts Here for popular videos */
for($i = 0; $i < $totalrecords; $i ++) { ?>
<div class="htmltooltip">
<?php if ($this->popularvideos [$i]->description) { 
/** Display description in popular videos tooltip */ ?>
  <p class="tooltip_discrip">
<?php echo JHTML::_('string.truncate', (strip_tags($this->popularvideos[$i]->description)), 120); ?>
  </p>
<?php } 
/** Display category in popular videos tooltip */ 
toolTip ( $this->popularvideos[$i]->category, $this->popularvideos[$i]->times_viewed, $dispenable['viewedconrtol'], $i );?>
  </div>
<?php }
/** Tooltip end Here for popular videos */ ?>
<ul class="hd_pagination">
<?php /** Popular videos pagination starts here */
videosharePagination ($this->popularvideos, $requestpage );
/** Popular videos pagination ends here */ ?>
</ul>
<?php  /** Get current request url for popular videos page */
$pop_page = $_SERVER ['REQUEST_URI'];
$hidden_pop_page = '';
/** Get current page number for popular videos page */
if ($requestpage) {
  $hidden_pop_page = $requestpage;
} 
/** Display pagination form for popular videos page */ ?>
<form name="pagination" id="pagination" action="<?php echo $pop_page; ?>" 
method="post"> 
<input type="hidden" id="video_pageid" name="video_pageid" value="<?php echo $hidden_pop_page ?>" /> 
</form>
<?php /** Get language direction for myvideos */
$popRtlLang = getLanguageDirection();  ?>
<script type="text/javascript">
  rtlLang = <?php echo $popRtlLang; ?>;
</script>
