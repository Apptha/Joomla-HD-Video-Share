<?php
/**
 * View file to display member videos
 *
 * This file is to display member videos
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

/** Check user logged in */
$userId = (int) getUserID();

/** Rating array for member collection */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );
/** Get video page id from request for member collection page */
$requestpage = JRequest::getInt ( 'video_pageid' );
/** Get thumb view, dispenbale settings from request for member collection page */
$thumbview = unserialize ( $this->memberpagerowcol [0]->thumbview );
$dispenable = unserialize ( $this->memberpagerowcol [0]->dispenable );
$Itemid = $this->Itemid;

addStyleForViews ( $thumbview ['memberpagewidth'] );

/** Call function to display myvideos, myplaylists link for members page */
playlistMenu( $Itemid, JUri::getInstance() );
?>
<div class="clearfix">
<?php /** Looping through member video details */
foreach ( $this->membercollection as $rows ) {
/** Display member page title */ 
  ?>
		<h1><?php echo JText::_('HDVS_VIDEO_ADDED_BY'); ?>
		<?php
  if ($rows->username == '') {
    echo "Administrator";
  } else {
    echo ucwords ( $rows->username );
  }
  ?></h1>
<?php break;
}

/** Get total records for member page */
$totalrecords = count ( $this->membercollection );
$totalrecords = $thumbview ['memberpagecol'] * $thumbview ['memberpagerow'];

if (count ( $this->membercollection ) - 4 < $totalrecords) {
  $totalrecords = count ( $this->membercollection ) - 4;
}
?>
	<div id="video-grid-container" class="clearfix">
	<?php
$no_of_columns = $thumbview ['memberpagecol'];
$current_column = 1;
/** Member page display starts here */
for($i = 0; $i < $totalrecords; $i ++) {
  $colcount = $current_column % $no_of_columns;
  
  if ($colcount == 1 || $no_of_columns == 1) {
    echo '<ul class="ulvideo_thumb clearfix">';
  }
  
  if ($this->membercollection [$i]->filepath == "File" || $this->membercollection [$i]->filepath == "FFmpeg" || $this->membercollection [$i]->filepath == "Embed") {
    if (isset ( $this->membercollection [$i]->amazons3 ) && $this->membercollection [$i]->amazons3 == 1) {
      $src_path = $dispenable ['amazons3link'] . $this->membercollection [$i]->thumburl;
    } else {
      $src_path = "components/com_contushdvideoshare/videos/" . $this->membercollection [$i]->thumburl;
    }
  }
  
  if ($this->membercollection [$i]->filepath == "Url" || $this->membercollection [$i]->filepath == "Youtube") {
    $src_path = $this->membercollection [$i]->thumburl;
  }
  
  if ($this->membercollection [$i]->id != '') {
    ?>
				<li class="video-item">
			<div class="home-thumb" id="member_thread">
				<div class="video_thumb_wrap">
				<?php /** Display video thumb in member page */ 
								
				/** Display watch later, add to playlist on member videos thumb */
			displayVideoThumbImage ( $Itemid, $src_path, $this->membercollection[$i], 'membercollection', 100);
			
			/** Display playlist popup in member videos page*/
			displayPlaylistPopup ( $this->membercollection[$i], 'membercollection', $Itemid );	?>

			</div>
				<div class="video_thread">
				<?php /** Display video title in member page */ 
					displayVideoTitle ( $Itemid, $this->membercollection[$i], '' ); ?>
					
<?php if ($dispenable ['ratingscontrol'] == 1) { ?><?php 
      if (isset ( $this->membercollection [$i]->ratecount ) && $this->membercollection [$i]->ratecount != 0) {
        $ratestar = round ( $this->membercollection [$i]->rate / $this->membercollection [$i]->ratecount );
      } else {
        $ratestar = 0;
      }
      /** Display video rating in member page */
      ?>
								<div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div>
								<?php 
    }
    
	
    ?>

							<?php /** Display video view count in member page */
    if ($dispenable ['viewedconrtol'] == 1) {
      ?>
<span class="floatright viewcolor"><?php echo $this->membercollection[$i]->times_viewed; ?>
	<?php echo JText::_('HDVS_VIEWS'); ?></span>
	
				</div>
							<?php
    }
    ?>
					</div>
		</li>
						<?php
  }
  
  if ($colcount == 0) {
    echo '</ul>';
    $current_column = 0;
  }
  
  $current_column ++;
} ?>
</div>
<?php /** Tooltip Starts Here for member page */ 
  for($i = 0; $i < $totalrecords; $i ++) { ?>
    <div class="htmltooltip">
<?php if ($this->membercollection [$i]->description) { ?>
				<p class="tooltip_discrip">
					<?php echo JHTML::_('string.truncate', (strip_tags($this->membercollection[$i]->description)), 120); ?>
				</p>
			<?php
    }
    /** Display category, viewcounts in tootip for member videos */
    toolTip ( $this->membercollection[$i]->category, $this->membercollection[$i]->times_viewed, $dispenable['viewedconrtol'], $i );
    ?>		
    </div>
<?php
  }
  /** Tooltip end Here */ ?>
  <ul class="hd_pagination">
<?php /** Member collection pagination starts here */
  videosharePagination ($this->membercollection, $requestpage );
  /** Member collection pagination ends here */ ?>
	</ul>
</div>
<?php
if (JRequest::getInt ( 'memberidvalue' )) {
  $memberidvalue = JRequest::getInt ( 'memberidvalue' );
}

$memberidvalue = isset ( $memberidvalue ) ? $memberidvalue : '';
?>
<form name="memberidform" id="memberidform"
	action="
	<?php echo JRoute::_('index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=membercollection'); ?>"
	method="post">
	<input type="hidden" id="memberidvalue" name="memberidvalue"
		value="<?php echo $memberidvalue; ?>" />
</form>
<?php
$page = $_SERVER ['REQUEST_URI'];
$hidden_page = '';

if ($requestpage) {
  $hidden_page = $requestpage;
} else {
  $hidden_page = '';
} ?>
<form name="pagination" id="pagination" action="<?php echo $page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_page ?>" /> 
</form>
<?php /** Get language direction for member collection */
$rtlLang = getLanguageDirection(); ?>
<script type="text/javascript">
  rtlLang = <?php echo $rtlLang; ?>;
</script>
