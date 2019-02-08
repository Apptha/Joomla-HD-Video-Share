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
/** Rating array for featured videos */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );

/** Check user logged in */
$userId = (int) getUserID();

/** Get current page number for featured videos page */
$requestpage = JRequest::getInt ( 'video_pageid' );
$thumbview = unserialize ( $this->featurevideosrowcol [0]->thumbview );
$dispenable = unserialize ( $this->featurevideosrowcol [0]->dispenable );

$document = JFactory::getDocument ();
/** Add styles for featured videos page */
addStyleForViews ( $thumbview ['featurwidth'] );

/** Get itemid for featured videos */
$Itemid = $this->Itemid;

/** Call function to display my videos, myplaylists in featured videos page */ 
playlistMenu( $Itemid, JUri::getInstance() );
/** Featured videos display starts here */ 
?>
<div class="standard clearfix">
<?php /** Display featured videos page title */ ?>
	<h1 class="home-link hoverable"><?php echo JText::_('HDVS_FEATURED_VIDEOS'); ?></h1>
	<div id="video-grid-container" class="clearfix">
<?php /** Calculate total featured videos count */ 
$totalrecords = $thumbview ['featurcol'] * $thumbview ['featurrow'];
  
  if (count ( $this->featuredvideos ) - 4 < $totalrecords) {
    $totalrecords = count ( $this->featuredvideos ) - 4;
  }
  
  $no_of_columns = $thumbview ['featurcol'];
  $current_column = 1;
  
  /** Looping for total count */
  for($i = 0; $i < $totalrecords; $i ++) {
    $colcount = $current_column % $no_of_columns;
    
    if ($colcount == 1 || $no_of_columns == 1) {
      echo '<ul class="ulvideo_thumb clearfix">';
    }
    
    /** Get thumb url for featured videos page */
    if ($this->featuredvideos [$i]->filepath == "File" || $this->featuredvideos [$i]->filepath == "FFmpeg" || $this->featuredvideos [$i]->filepath == "Embed") {
      if (isset ( $this->featuredvideos [$i]->amazons3 ) && $this->featuredvideos [$i]->amazons3 == 1) {
        $src_path = $dispenable ['amazons3link'] . $this->featuredvideos [$i]->thumburl;
      } else {
        $src_path = "components/com_contushdvideoshare/videos/" . $this->featuredvideos [$i]->thumburl;
      }
    }
    
    if ($this->featuredvideos [$i]->filepath == "Url" || $this->featuredvideos [$i]->filepath == "Youtube") {
      $src_path = $this->featuredvideos [$i]->thumburl;
    }
   
    /** Display featured videos in row and column */ 
    ?>
			<li class="video-item">
			<div class="video_thumb_wrap">
			<?php /** Display featured videos thumb */ 
			/** Display watch later, add to playlist on search videos thumb */
			displayVideoThumbImage ( $Itemid, $src_path, $this->featuredvideos[$i], 'featured', 100 );
			
			/** Display playlist popup, category title in featured videos page*/
			displayPlaylistPopup ( $this->featuredvideos[$i], 'featured', $Itemid );
			?>
				</div>
			<div class="video_thread">
			<?php /** Display videos title for recent video thumbs */
			displayVideoTitle ( $Itemid, $this->featuredvideos[$i], '' );
			
/** Calculate ratecount for featured videos */
    if ($dispenable ['ratingscontrol'] == 1) {
      if (isset ( $this->featuredvideos [$i]->ratecount ) && $this->featuredvideos [$i]->ratecount != 0) {
        $ratestar = round ( $this->featuredvideos [$i]->rate / $this->featuredvideos [$i]->ratecount );
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

<span class="floatright viewcolor"><?php echo $this->featuredvideos[$i]->times_viewed; ?>
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
  ?>
	</div>
</div>
<?php 
/** Featured videos display ends 
 * Tooltip for featured video starts */
for($i = 0; $i < $totalrecords; $i ++) {
/** Display tooltip for featured video thumbs */
  ?>
<div class="htmltooltip">
			<?php /** Display description for featured thumb in tooltip  */
  if ($this->featuredvideos [$i]->description) {
    ?>
			<p class="tooltip_discrip">
				<?php echo JHTML::_('string.truncate', (strip_tags($this->featuredvideos[$i]->description)), 120); ?>
			</p>
			<?php
  }
  /** Display category name for featured thumb in tooltip  */
  toolTip ( $this->featuredvideos[$i]->category, $this->featuredvideos[$i]->times_viewed, $dispenable['viewedconrtol'], $i ); ?>
	</div>
<?php
}
/** Tooltip end Here for featured videos */
?>

<ul class="hd_pagination">
<?php /** Featured Videos pagination starts here */
videosharePagination ($this->featuredvideos, $requestpage ); 
/** Featured Videos pagination ends here */
?>
</ul>
<?php /** Get current request url */
$fea_page = $_SERVER ['REQUEST_URI'];
$hidden_fea_page = '';
/** Get page number for featured videos page */
if ($requestpage) {
  $hidden_fea_page = $requestpage;
} ?>
<form name="pagination" id="pagination" action="<?php echo $fea_page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_fea_page ?>" /> 
</form>
<?php /** Get language direction for featured videos page */
$feaRtlLang = getLanguageDirection(); 
?>
<script type="text/javascript">
  rtlLang = <?php echo $feaRtlLang; ?>;
</script>
