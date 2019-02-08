<?php
/**
 * Recent videos view file
 *
 * This file is to display Recent videos detail
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

/** Rating array for recent videos */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );

/** Check user logged in */
$userId = (int) getUserID();

/** Get current recent videos page number */
$requestpage = JRequest::getInt ( 'video_pageid' );
$thumbview = unserialize ( $this->recentvideosrowcol [0]->thumbview );
$dispenable = unserialize ( $this->recentvideosrowcol [0]->dispenable );

/** Get item id for recent videos page */
$Itemid = $this->Itemid;
/** Define css styles for recent videos page */
addStyleForViews ( $thumbview ['recentwidth'] );

/** Call function to display myvideos, myplaylists link for recent videos page*/
playlistMenu( $Itemid, JUri::getInstance() );
/** Recent video page starts here */
?>
<div class="standard clearfix">
<?php /** Display recent videos page title */ ?>
	<h1 class="home-link hoverable"><?php echo JText::_('HDVS_RECENT_VIDEOS'); ?></h1>
	<div id="video-grid-container" class="clearfix">
		<?php /** calculate total count for recent videos page */
  $totalrecords = $thumbview ['recentcol'] * $thumbview ['recentrow'];
  
  if (count ( $this->recentvideos ) - 4 < $totalrecords) {
    $totalrecords = count ( $this->recentvideos ) - 4;
  }
  
  $no_of_columns = $thumbview ['recentcol'];
  $current_column = 1;
  /** Looping recent video details */
  for($i = 0; $i < $totalrecords; $i ++) {
    $colcount = $current_column % $no_of_columns;
    
    if ($colcount == 1 || $no_of_columns == 1) {
      echo '<ul class="ulvideo_thumb clearfix">';
    }
    
    /** Get recent videos thumb url */
    if ($this->recentvideos [$i]->filepath == "File" || $this->recentvideos [$i]->filepath == "FFmpeg" || $this->recentvideos [$i]->filepath == "Embed") {
      if (isset ( $this->recentvideos [$i]->amazons3 ) && $this->recentvideos [$i]->amazons3 == 1) {
        $src_path = $dispenable ['amazons3link'] . $this->recentvideos [$i]->thumburl;
      } else {
        $src_path = "components/com_contushdvideoshare/videos/" . $this->recentvideos [$i]->thumburl;
      }
    }
    
    if ($this->recentvideos [$i]->filepath == "Url" || $this->recentvideos [$i]->filepath == "Youtube") {
      $src_path = $this->recentvideos [$i]->thumburl;
    }
    
    /** Display recent videos content */
    ?>
			<li class="video-item">
			<div class="video_thumb_wrap">
			<?php /** Display recent videos thumb */ 
			/** Display watch later, add to playlist on recent videos thumb */
			displayVideoThumbImage ( $Itemid, $src_path, $this->recentvideos[$i], 'recent', 100);
			
			/** Display playlist popup in recent videos page*/
			displayPlaylistPopup ( $this->recentvideos[$i], 'recent', $Itemid );			
			?>
			</div>
			<?php /** Display recent videos title */ ?>
			<div class="video_thread">
				<?php /** Display videos title for recent video thumbs */
			displayVideoTitle ( $Itemid, $this->recentvideos[$i], '' );

			/** Calculate rate count for recent videos */ 
if ($dispenable ['ratingscontrol'] == 1) {
      if (isset ( $this->recentvideos [$i]->ratecount ) && $this->recentvideos [$i]->ratecount != 0) {
        $ratestar = round ( $this->recentvideos [$i]->rate / $this->recentvideos [$i]->ratecount );
      } else {
        $ratestar = 0;
      }
      /** Display rating for recent video thumbs */
      ?>
						<div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div>
						<?php  }
						
		
/** Display views for recent video thumbs */
    if ($dispenable ['viewedconrtol'] == 1) { ?>
						<span class="floatright viewcolor"><?php echo $this->recentvideos[$i]->times_viewed; ?>
							<?php echo JText::_('HDVS_VIEWS'); ?></span>
				
			</div>
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
<?php 
/** Tooltip Starts Here for recent videos page */
for($i = 0; $i < $totalrecords; $i ++) { ?>
<div class="htmltooltip">
<?php
  if ($this->recentvideos [$i]->description) {
/** Display description in recent videos tooltip */
    ?>
			<p class="tooltip_discrip"><?php echo JHTML::_('string.truncate', (strip_tags($this->recentvideos[$i]->description)), 120); ?></p>
<?php
  }
    /** Display category, viewcounts in tootip for watch history videos */ 
	toolTip ( $this->recentvideos[$i]->category, $this->recentvideos[$i]->times_viewed, $dispenable['viewedconrtol'], $i );
  ?>	
	</div>
<?php

}
/** Tooltip end Here for recent videos */ ?>
<ul class="hd_pagination">
<?php /** Recent videos pagination starts here */
videosharePagination ( $this->recentvideos, $requestpage );
/** Recent videos pagination ends here */
?>
</ul>
<?php
$rec_page = $_SERVER ['REQUEST_URI'];
$hidden_rec_page = '';
if ($requestpage) {
  $hidden_rec_page = $requestpage;
} ?>
<form name="pagination" id="pagination" action="<?php echo $rec_page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_rec_page ?>" /> 
</form>
<?php  /** Get language direction for myvideos */
$recRtlLang = getLanguageDirection();
?>
<script type="text/javascript">
rtlLang = "<?php echo $recRtlLang; ?>";
</script>
