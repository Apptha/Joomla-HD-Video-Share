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

/** No direct acesss */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Rating array */
$ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");

$userId = (int) getUserID();

/** Get current page number */
$requestpage = JRequest::getInt('video_pageid');
$thumbview = unserialize($this->watchlaterrowcol[0]->thumbview);
$dispenable = unserialize($this->watchlaterrowcol[0]->dispenable);

/** Define css styles for recent videos page */
addStyleForViews ( $thumbview ['featurwidth'] );

$Itemid = $this->Itemid;

/** Call function to display myvideos, myplaylists link for recent videos page*/
playlistMenu( $Itemid, JUri::getInstance() );
?>

<div class="standard clearfix">
	<h1 class="home-link hoverable"><?php echo JText::_('HDVS_LATER_VIDEOS'); ?></h1>
	<?php if(count($this->watchlater)) { ?>
	<div id="remove-watch-later" class="clearfix">
		<button class="button2" type="button" onclick="removeWatchLater(<?php echo $userId;?>)">Clear Watch Later Videos</button>
	</div>
	<?php } else{
	echo '<h3 class="novideos">'.JText::_('HDVS_NO_VIDEOS_IN_THE_LIST').'</h3>';
} ?>
	<div id="video-grid-container" class="clearfix">
		<?php
		$totalrecords = $thumbview['watchlatercol'] * $thumbview['watchlaterrow'];

		if (count($this->watchlater) - 4 < $totalrecords) {
			$totalrecords = count($this->watchlater) - 4;
		}

		$no_of_columns = $thumbview['watchlatercol'];
		$current_column = 1;

		for ($i = 0; $i < $totalrecords; $i++) {
			$colcount = $current_column % $no_of_columns;

			if ($colcount == 1 || $no_of_columns == 1) {
				echo '<ul class="ulvideo_thumb clearfix">';
			}

			if ($this->watchlater[$i]->filepath == "File"
				|| $this->watchlater[$i]->filepath == "FFmpeg"
				|| $this->watchlater[$i]->filepath == "Embed") {
				if (isset($this->watchlater[$i]->amazons3) && $this->watchlater[$i]->amazons3 == 1) {
					$src_path = $dispenable['amazons3link']
							. $this->watchlater[$i]->thumburl;
				}
				else {
					$src_path = "components/com_contushdvideoshare/videos/" . $this->watchlater[$i]->thumburl;
				}
			}

			if ($this->watchlater[$i]->filepath == "Url" || $this->watchlater[$i]->filepath == "Youtube") {
				$src_path = $this->watchlater[$i]->thumburl;
			}
			?>
			<li class="video-item">
				<div class="video_thumb_wrap">
			        <?php  
			        /** Display watch later, add to playlist on watch later videos thumb */
			displayVideoThumbImage ( $Itemid, $src_path, $this->watchlater[$i], 'later', 100);
			
			/** Display playlist popup in watch later videos page*/
			displayPlaylistPopup ( $this->watchlater[$i], 'later', $Itemid );	?>
				</div>
				<div class="video_thread">
					
<?php /** Display videos title for recent video thumbs */
			displayVideoTitle ( $Itemid, $this->watchlater[$i], '' );
			
if ($dispenable['ratingscontrol'] == 1) {
	if (isset($this->watchlater[$i]->ratecount) && $this->watchlater[$i]->ratecount != 0) {
		$ratestar = round($this->watchlater[$i]->rate / $this->watchlater[$i]->ratecount);
	} else {
		$ratestar = 0;
	}
?>
<div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div>

<?php
}

					if ($dispenable['viewedconrtol'] == 1) {
						?>

<span class="floatright viewcolor"><?php echo $this->watchlater[$i]->times_viewed; ?>
	<?php echo JText::_('HDVS_VIEWS'); ?></span></div>
					<?php
					}
					?>
			</li>
					<?php
					if ($colcount == 0) {
						echo '</ul>';
						$current_column = 0;
					}

					$current_column++;
		}
				?>
	</div>
</div>

		<?php /** Tooltip Starts Here watch later videos  */
		for ($i = 0; $i < $totalrecords; $i++) {
			?>
	<div class="htmltooltip">
			<?php
			if ($this->watchlater[$i]->description) {
			?>
			<p class="tooltip_discrip">
				<?php echo JHTML::_('string.truncate', (strip_tags($this->watchlater[$i]->description)), 120); ?>
			</p>
			<?php
			}
	/** Display category, viewcounts in tootip for watch later videos  */ 
	toolTip ( $this->watchlater[$i]->category, $this->watchlater[$i]->times_viewed, $dispenable['viewedconrtol'], $i );
	?>
	</div>
	<?php
		}
		/** Tooltip Starts Here for watch later videos */
	?>

<ul class="hd_pagination">
<?php /** Watch later Videos pagination starts here */
videosharePagination ($this->watchlater, $requestpage ); 
/** Watch later Videos pagination ends here */
?>
</ul>
	<?php
	$page = $_SERVER['REQUEST_URI'];
	$hidden_page = '';
	if ($requestpage) {
		$hidden_page = $requestpage;
	}	
	?>
<form name="pagination" id="pagination" action="<?php echo $page; ?>" method="post">
	<input type="hidden" id="video_pageid" name="video_pageid" value="<?php echo $hidden_page ?>" />
</form>
<?php /** Get language direction for Watch later videos page */
$laterRtlLang = getLanguageDirection(); 
?>
<script type="text/javascript">
  rtlLang = <?php echo $laterRtlLang; ?>;
</script>
