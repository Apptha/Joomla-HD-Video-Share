<?php
/**
 * Related videos view file
 *
 * This file is to display Related videos detail
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/**  * Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Rating array for related videos page */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );

/** Check user logged in */
$userId = (int) getUserID();

/** Get current related videos page number */
$requestpage = JRequest::getInt ( 'video_pageid' );

/** Get settings for related videos */
$thumbview = unserialize ( $this->relatedvideosrowcol [0]->thumbview );
$dispenable = unserialize ( $this->relatedvideosrowcol [0]->dispenable );

/** Get itemid for realted videos */
$Itemid = $this->Itemid;
/** Add styles for related videos */
addStyleForViews ( $thumbview ['relatedwidth'] );

/** Display related videos page starts */ 
?>
<div class="standard clearfix">
<?php /** Display related videos page title */ ?>
	<h1 class="home-link hoverable"><?php echo JText::_('HDVS_RELATED_VIDEOS'); ?></h1>
	<div id="video-grid-container" class="clearfix">
<?php /** Calculate total thumb count for related videos page */ 
  $totalrecords = $thumbview ['relatedcol'] * $thumbview ['relatedrow'];
  
  if (count ( $this->relatedvideos ) - 4 < $totalrecords) {
    $totalrecords = count ( $this->relatedvideos ) - 4;
  }
  
  $no_of_columns = $thumbview ['relatedcol'];
  $current_column = 1;
  /** Looping related video details */
  for($i = 0; $i < $totalrecords; $i ++) {
    $colcount = $current_column % $no_of_columns;
    
    if ($colcount == 1 || $no_of_columns == 1) {
      echo '<ul class="ulvideo_thumb clearfix">';
    }
    
    /** Get thumb url for related videos page */
    if ($this->relatedvideos [$i]->filepath == "File" || $this->relatedvideos [$i]->filepath == "FFmpeg" || $this->relatedvideos [$i]->filepath == "Embed") {
      if (isset ( $this->relatedvideos [$i]->amazons3 ) && $this->relatedvideos [$i]->amazons3 == 1) {
        $src_path = $dispenable ['amazons3link'] . $this->relatedvideos [$i]->thumburl;
      } else {
        $src_path = "components/com_contushdvideoshare/videos/" . $this->relatedvideos [$i]->thumburl;
      }
    }
    
    if ($this->relatedvideos [$i]->filepath == "Url" || $this->relatedvideos [$i]->filepath == "Youtube") {
      $src_path = $this->relatedvideos [$i]->thumburl;
    }
    
    /** Get related videos content */
    ?>
			<li class="video-item">
			<div class="video_thumb_wrap">
			<?php /** Display related videos thumb */ 
			 /** Display watch later, add to playlist on search videos thumb */
		  displayVideoThumbImage ( $Itemid, $src_path, $this->relatedvideos[$i], 'related', 100);

				/** Display playlist popup in recent videos page*/
			displayPlaylistPopup ( $this->relatedvideos[$i], 'related', $Itemid ); ?>
			</div>
<?php /** Display related videos title */ 
displayVideoTitle ( $Itemid, $this->relatedvideos[$i], '' );
?>
			
<?php /** Calculate rate count for related videos */ 
    if ($dispenable ['ratingscontrol'] == 1) { 
      if (isset ( $this->relatedvideos [$i]->ratecount ) && $this->relatedvideos [$i]->ratecount != 0) {
        $ratestar = round ( $this->relatedvideos [$i]->rate / $this->relatedvideos [$i]->ratecount );
      } else {
        $ratestar = 0;
      }
      /** Display related videos rating */
      ?>
					<div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div>
					<?php  }
					
					
if ($dispenable ['viewedconrtol'] == 1) { 
/** Display views for related videos  */ ?>
					<span class="floatright viewcolor"><?php echo $this->relatedvideos[$i]->times_viewed; ?>
						<?php echo JText::_('HDVS_VIEWS'); ?></span>
				
				<?php } ?>
			</li>
<?php if ($colcount == 0) {
      echo '</ul>';
      $current_column = 0;
    }
    
    $current_column ++;
  }
  ?>
	</div>
</div>

<?php /** Tooltip Starts Here realted videos */
for($i = 0; $i < $totalrecords; $i ++) {
  ?>
<div class="htmltooltip">
			<?php  if ($this->relatedvideos [$i]->description) { 
			/** Display description in related videos tooltip */ ?>
			<p class="tooltip_discrip"><?php echo JHTML::_('string.truncate', (strip_tags($this->relatedvideos[$i]->description)), 120); ?></p>
			<?php } ?>
		<div class="tooltip_category_left">
		<span class="title_category"><?php echo JText::_('HDVS_CATEGORY'); ?>: </span>
		<span class="show_category"><?php echo $this->relatedvideos[$i]->category; ?></span>
	</div>
	<?php /** Display category title in related videos tooltip */
	 if ($dispenable ['viewedconrtol'] == 1) { 
/** Display views in related videos tooltip */ ?>
			<div class="tooltip_views_right">
		<span class="view_txt"><?php echo JText::_('HDVS_VIEWS'); ?>: </span>
		<span class="view_count"><?php echo $this->relatedvideos[$i]->times_viewed; ?> </span>
	</div>
	<div id="htmltooltipwrapper<?php echo $i; ?>">
		<div class="chat-bubble-arrow-border"></div>
		<div class="chat-bubble-arrow"></div>
	</div>
<?php } ?>
	</div>
<?php } 
/** Tooltip end Here for related vidoes  */
?>
<ul class="hd_pagination">
<?php /** Related Videos pagination starts here */
videosharePagination ($this->relatedvideos, $requestpage );
/** Related Videos pagination ends here */ 
?>
</ul>
<?php /** Get current request url for related videos page */
$rel_page = $_SERVER ['REQUEST_URI'];
$hidden_rel_page = '';
/** Get current page number for related videos */
if ($requestpage) {
  $hidden_rel_page = $requestpage;
} 
/** Display pagination form for related vidoes */ ?>
<form name="pagination" id="pagination" action="<?php echo $rel_page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_rel_page ?>" />
</form>
<?php /** Get language direction for myvideos */
$relRtlLang = getLanguageDirection(); 
?>
<script type="text/javascript">
  rtlLang = <?php echo $relRtlLang; ?>;
</script>
