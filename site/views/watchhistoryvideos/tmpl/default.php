<?php 
/**
 * Featured videos view for HD Video Share
 *
 * This file is to display featured videos 
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
$thumbview = array ();
/** Rating array for featured videos */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );

/** Get current page number for featured videos page */
$requestpage = JRequest::getInt ( 'video_pageid' );
$thumbview = unserialize ( $this->historyvideosrowcol [0]->thumbview );
$dispenable = unserialize ( $this->historyvideosrowcol [0]->dispenable );

/** Get item id for watch history videos page */
$Itemid = $this->Itemid;

/** Call function to display myvideos, myplaylists link for recent videos page*/
playlistMenu( $Itemid, JUri::getInstance() );

/** Define css styles for recent videos page */
addStyleForViews ( $thumbview ['historywidth'] );

$userId = (int) getUserID();
$HistoryState = $this->HistoryState;
if(isset($HistoryState) && $HistoryState == 1){
  $statusText = JText::_('HDVS_RESUME_WATCH_HISTORY');
  $status = 0;
}else{
  $statusText = JText::_('HDVS_PAUSE_WATCH_HISTORY');
  $status = 1;
}

/** Watch Histoy videos display starts here */ 
?>
<script type="text/javascript">
var frontbase = '<?php echo JURI::base (); ?>';
</script>
<div class="standard clearfix">
<?php /** Display featured videos page title */ ?>
	<h1 class="home-link hoverable"><?php echo JText::_('HDVS_WATCH_HISTORY_VIDEOS'); ?></h1>
	<?php if(count($this->HistoryVideos )) {?>
	<div id="historyActions">
	 <button id="ClearHistory" class="button2" type="button" onclick="ClearHistory('all','');"><?php echo JText::_('HDVS_CLEAR_ALL_WATCH_HISTORY'); ?></button>
	 <button id="PauseHistory" class="button2" type="button" onclick="PauseHistory(<?php echo $status;?>);"><?php echo $statusText; ?></button>
	</div>
	<?php } else{ 
	?>
	<div id="historyActions">	
	  <button id="PauseHistory" class="button2" type="button" onclick="PauseHistory(<?php echo $status;?>);"><?php echo $statusText; ?></button>
	</div>
	<?php echo '<h3 class="novideos">'.JText::_('HDVS_NO_VIDEOS_IN_THE_LIST').'</h3>';
}?>
	<div id="video-grid-container" class="clearfix historypage">
<?php /** Calculate total watch history videos count */ 
$totalrecords = $thumbview ['historycol'] * $thumbview ['historyrow'];
  
  if (count ( $this->HistoryVideos ) - 4 < $totalrecords) {
    $totalrecords = count ( $this->HistoryVideos ) - 4;
  }
  
  if($totalrecords > 0){
  $no_of_columns = $thumbview ['historycol'];
  $current_column = 1;
  
  /** Looping for total count */
  for($i = 0; $i < $totalrecords; $i ++) {
    $colcount = $current_column % $no_of_columns;
    
    if ($colcount == 1 || $no_of_columns == 1) {
      echo '<ul class="ulvideo_thumb clearfix">';
    }
        
    /** Get thumb url for featured videos page */
    if ($this->HistoryVideos [$i]->filepath == "File" || $this->HistoryVideos [$i]->filepath == "FFmpeg" || $this->HistoryVideos [$i]->filepath == "Embed") {
      if (isset ( $this->HistoryVideos [$i]->amazons3 ) && $this->HistoryVideos [$i]->amazons3 == 1) {
        $src_path = $dispenable ['amazons3link'] . $this->HistoryVideos [$i]->thumburl;
      } else {
        $src_path = "components/com_contushdvideoshare/videos/" . $this->HistoryVideos [$i]->thumburl;
      }
    }
    
    if ($this->HistoryVideos [$i]->filepath == "Url" || $this->HistoryVideos [$i]->filepath == "Youtube") {
      $src_path = $this->HistoryVideos [$i]->thumburl;
    }
    /** Display featured videos in row and column */ 
    ?>
			<li class="video-item">
			<a id="remove" title="Remove Video" onclick="ClearHistory('single',<?php echo $this->HistoryVideos [$i]->id; ?>);">X</a>
			<div class="video_thumb_wrap">
			<?php /** Display featured videos thumb */ 
			 /** Display watch later, add to playlist on search videos thumb */
		  displayVideoThumbImage ( $Itemid, $src_path, $this->HistoryVideos[$i], 'history', 100 );
		  
			        /** Display playlist popup in recent videos page*/
			displayPlaylistPopup ( $this->HistoryVideos[$i], 'history', $Itemid ); ?>
			</div>
			<div class="video_thread">
			<?php /** Display featured videos title */ 
			displayVideoTitle ( $Itemid, $this->HistoryVideos[$i], '' );

			/** Calculate ratecount for featured videos */
    if ($dispenable ['ratingscontrol'] == 1) {
      if (isset ( $this->HistoryVideos [$i]->ratecount ) && $this->HistoryVideos [$i]->ratecount != 0) {
        $ratestar = round ( $this->HistoryVideos [$i]->rate / $this->HistoryVideos [$i]->ratecount );
      } else {
        $ratestar = 0;
      }
      /** Display ratecount for featured videos */
      ?>
<div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div>
<?php
    }
    
    
    /** Display views for featured videos */
    if ($dispenable ['viewedconrtol'] == 1) {
      ?>

<span class="floatright viewcolor"><?php echo $this->HistoryVideos[$i]->times_viewed; ?>
	<?php echo JText::_('HDVS_VIEWS'); ?></span>
			</div>
					<?php
    }
    ?>
			</li>
					<?php
    if ($colcount == 0) {
      echo '</ul>';
      $current_column = 0;
    }
    
    $current_column ++;
  }
}
  ?>
	</div>
</div>
<?php 
/** Watch history videos display ends 
 * Tooltip for watch history video starts */
for($i = 0; $i < $totalrecords; $i ++) {
/** Display tooltip for featured video thumbs */
  ?>
<div class="htmltooltip">
			<?php /** Display description for featured thumb in tooltip  */
  if ($this->HistoryVideos [$i]->description) {
    ?>
			<p class="tooltip_discrip">
				<?php echo JHTML::_('string.truncate', (strip_tags($this->HistoryVideos[$i]->description)), 120); ?>
			</p>
			<?php
  }
  /** Display category, viewcounts in tootip for watch history videos */ 
	toolTip ( $this->HistoryVideos[$i]->category, $this->HistoryVideos[$i]->times_viewed, $dispenable['viewedconrtol'], $i );
  
  ?>
	</div>
<?php
}
/** Tooltip end Here for watch history videos */
?>

<ul class="hd_pagination">
<?php /** Watch history Videos pagination starts here */
videosharePagination ($this->HistoryVideos, $requestpage ); 
/** Watch history Videos pagination ends here */
?>
</ul>
<?php /** Get current request url */
$fea_page = $_SERVER ['REQUEST_URI'];
$hidden_fea_page = '';
/** Get page number for Watch history videos page */
if ($requestpage) {
  $hidden_fea_page = $requestpage;
} ?>
<form name="pagination" id="pagination" action="<?php echo $fea_page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_fea_page ?>" /> 
</form>
<?php /** Get language direction for Watch history videos page */
$historyRtlLang = getLanguageDirection(); 
?>
<script type="text/javascript">
  rtlLang = <?php echo $historyRtlLang; ?>;
</script>
