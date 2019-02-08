<?php
/**
 * Category videos view file
 *
 * This file is to display Category videos
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

/** Set array for rating stars */
$ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");

/** Gets user details */
$user   =  (int) getUserID();

/** Gets Thumb image details */
$thumbview    = unserialize($this->playlistrowcol[0]->thumbview);

/** Gets settings details */
$dispenable   = unserialize($this->playlistrowcol[0]->dispenable);

/** Gets Player value */
$player_values 	= unserialize($this->player_values->player_values);
$player_icons 	= unserialize($this->player_values->player_icons);

/** Replace slashes in URL */
$baseurl 		= getBaseURL ();

/** Get item id */
$Itemid         = $this->Itemid;

/** Including style in document  */
addStyleForViews ( $thumbview['playlistwidth'] );

/**
 * Login and Registration links
 * Function to call from helper top menu
 */ 
playlistMenu($Itemid, JUri::getInstance() );
/** Get requested URL */
$requestpage = JRequest::getInt('video_pageid');
?>
<div class="player clearfix" id="clsdetail">

<?php
/** Set rows and columns */
$totalrecords = $thumbview['playlistcol'] * $thumbview['playlistrow'];
/** Grid view for thumb to show */
if (count($this->categoryview) - 6 < $totalrecords){
	/** Set total rows in pagination  */
	$totalrecords = count($this->categoryview) - 6;
}
/** Check wheather the total record result is 0 */
if ($totalrecords <= 0){
/**
 * If the count is 0 then this part will be executed
 */ 
?>
		<h1 class="home-link hoverable"><?php if(isset($this->categoryview[0])) { 
		  echo $this->categoryview[0]->category; 
		  }?></h1>
		<?php
		echo '<div class="hd_norecords_found"> ' . JText::_('HDVS_NO_PLAYLIST_VIDEOS_FOUND') . ' </div>';
}
	else{
/**
 * If the count is not 0 then this part will be executed
 */
		?>
		<div id="video-grid-container" class="clearfix">

	<?php
			/**
			 * Specifying the no of columns
			 */ 
			$no_of_columns = $thumbview['playlistcol'];
			/** For each results of videos added */
			foreach ($this->categoryList as $val){ 
				/** initialize view for playlist */
				$current_column = 1;
				$l = 0;
				/** Show grid view for videos */
				for ($i = 0; $i < $totalrecords; $i++){
					/** If the first aded video id is available */
					if ($val->parent_id == $this->categoryview[$i]->parent_id
						&& $val->category == $this->categoryview[$i]->category){
						/** Takes modulo of the columns */
						$colcount = $current_column % $no_of_columns;
						/** If its the first column */
						if ($colcount == 1 && $l == 0){
							/** Show video details */
							echo "<div class='clear'></div><h1 class='home-link hoverable'>". ucfirst($val->category)."</h1>";
						}

						if ($colcount == 1 || $no_of_columns == 1){
							/** Show Thumbnail image */
							echo "</ul><ul class='ulvideo_thumb clearfix'>";
							/** increment the video id */
							$l++;
						}

						/**
						 * For SEO settings
						 */ 
						$catgoryVideo ="";
						
						/** Check File type */
						if ($this->categoryview[$i]->filepath == "File"
							|| $this->categoryview[$i]->filepath == "FFmpeg"
							|| $this->categoryview[$i]->filepath == "Embed"){
							/** Link for amazon s3 */
							if (isset($this->categoryview[$i]->amazons3) && $this->categoryview[$i]->amazons3 == 1){
								$src_path = $dispenable['amazons3link']
										. $this->categoryview[$i]->thumburl;
							}
							else{
								/** Link for Uploaded file */
								$src_path = "components/com_contushdvideoshare/videos/" . $this->categoryview[$i]->thumburl;
							}
						}

						if ($this->categoryview[$i]->filepath == "Url"
							|| $this->categoryview[$i]->filepath == "Youtube"){
							/** Link for Youtube video */
							$src_path = $this->categoryview[$i]->thumburl;
						}
						?>
				<?php
				/** Check category view */
				if ($this->categoryview[$i]->id != ''){
					/** If category id is available */
					?>
							<li class="video-item" >
							<a id="removePlaylist" title="Remove Video" onclick="var flg = deletePlaylistVideo(<?php echo $this->categoryview[$i]->vid; ?>,<?php echo $this->categoryview[$i]->catid; ?>);return flg;">X</a>
								<div class="home-thumb">
									<div class="video_thumb_wrap">
									<?php /** Display watch later, add to playlist on search videos thumb */
                                      displayVideoThumbImage ( $Itemid, $src_path, $this->categoryview[$i], 'playlist', 100 ); 

                                      /** Display playlist popup in recent videos page*/
			displayPlaylistPopup ( $this->categoryview[$i], 'playlist', $Itemid );	?>									
									</div>
									<?php 
									/** Display videos title for recent video thumbs */
			displayVideoTitle ( $Itemid, $this->categoryview[$i], 'playlist' );
									?>
		
			<?php
			/** Display Video link 
			 * Get valuse for the player
			 * Load it into url
			 */
			$playvideo = generateVideoTitleURL ( $Itemid, $this->categoryview[$i], 'playlist' );			
			/** 
			 * Display Video thumb
			 * Show play and delete buttons
			 */
			?>
		</div>
								<!--Tooltip Starts Here-->
<div class="htmltooltip">
		<?php
		/** Display category Description */
		if ($this->categoryview[$i]->description){
			/** If description available */
		?>
<p class="tooltip_discrip">
	<?php
	/** Display truncated category description if length is > then 120 */
	 echo JHTML::_('string.truncate', (strip_tags($this->categoryview[$i]->description)), 120); ?></p>
		<?php
		}
		/** 
		 *  Show category
		 *  Category views
		 *  */
		
		/** Display category, viewcounts in tootip for playlist videos */
		toolTip ( $this->categoryview[$i]->category, $this->categoryview[$i]->times_viewed, $dispenable['viewedconrtol'], $i );
		/** Tooltip ends and PAGINATION STARTS HERE */
		?>
			</div>
				</li>
						<?php 		}
							
						 /** First row */
						
						if ($colcount == 0){ 
							/** UL in loop */
							echo '</ul>';
							/** Set current column as 0 */
							$current_column = 0;
						}
						/** increment column */
						$current_column++;
					}
				}
			}
			?>

		</div>
		
		<ul class="hd_pagination">
			<?php /** Pagination starts here for playlist */
videosharePagination ($this->categoryview, $requestpage ); 
/** Pagination ends here for playlist */
			?>
		</ul>
<?php
	}
?>
</div>
<?php
/** Get url */
$page = $_SERVER['REQUEST_URI'];
/** Set video id as empty */
$deleteVideo = '';
/** Check video id availavbe in post */
if (JRequest::getInt('deletevideo')){
	/** Get video id from post */
	$deleteVideo = JRequest::getInt('deletevideo');
}
/** Check category is available in POST */
if (JRequest::getInt('deletecat')){
	/** Get category to be deleted in POST */
	$deleteCat = JRequest::getInt('deletecat');
}
?>
<?php /** 
*Hidden video id deleted
*Hidden category deleted
*/ ?>
<form name="deletemyvideoplay"  action="<?php echo $page; ?>" method="post">
	<input type="hidden" name="deletevideo" id="deletevideo" value="<?php echo $deleteVideo; ?>">
	<input type="hidden" name="deletecat" id="deletecat" value="">
</form>
<?php 
/** Set hidden page as empty */
$hidden_page = '';
/** If request page is available*/
if ($requestpage){
	/** Set request page to hidden */
	$hidden_page = $requestpage;
}
else{
/** Initalise hidden page as empty */
	$hidden_page = '';
}

/** 
 * var page hold page name
 * hidden page
 * hidden search key
 * */
?>
<form name="pagination" id="pagination" action="<?php echo $page; ?>" method="post">
	<input type="hidden" id="video_pageid" name="video_pageid" value="<?php echo $hidden_page ?>" />	
</form>