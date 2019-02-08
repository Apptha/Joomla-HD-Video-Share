<?php
/**
 * Search view for HD Video Share
 *
 * This file is to display videos based on search keyword 
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

/** Get joomla session for search view */
$session = JFactory::getSession ();
$playlists = $this->playlists;
/** Check user logged in */
$userId = (int) getUserID ();
/** Rating array for search videos */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );

/** Get current search page */
$requestpage = JRequest::getInt ( 'video_pageid' );

/** Get thumb view settings for search view */
$thumbview = unserialize ( $this->searchrowcol [0]->thumbview );
/** Get dispenable settings for search view */
$dispenable = unserialize ( $this->searchrowcol [0]->dispenable );
/** Get searchval for search view */
$serachVal = JRequest::getVar ( 'searchtxtbox' );
/** Get search text from request */
$serachVal = isset ( $serachVal ) ? $serachVal : $session->get ( 'search' );

addStyleForViews ( $thumbview ['searchwidth'] );

/** Get item id for search page  */
$Itemid = $this->Itemid;

/** Search page section starts here */
?>
<div class="player clearfix" id="clsdetail">
<?php /** Call function to display myvideos, myplaylists link for search */
playlistMenu( $Itemid, JUri::getInstance() ); ?>
<div class="standard clearfix">
<?php /** Get total count for search page */
$totalRecords = $thumbview ['searchcol'] * $thumbview ['searchrow'];

if (count ( $this->search ) - 4 < $totalRecords) {
  $totalRecords = count ( $this->search ) - 4;
}

if ($totalRecords == - 4) {
/** Check total record is empty 
 * Display no search results */
  ?>
			<h1><?php echo JText::_('HDVS_SEARCH_RESULT') . " - $serachVal"; ?></h1>
			<?php
  echo '<div class="hd_norecords_found"> ' . JText::_ ( 'HDVS_NO_RECORDS_FOUND_SEARCH' ) . '"' . $serachVal . '"' . ' </div></div>';
} else {
/** Display search results starts 
 * Display search page title */
  ?>

			<h1 class="home-link hoverable"><?php echo JText::_('HDVS_SEARCH_RESULT') . " - $serachVal"; ?></h1>
		<div id="video-grid-container" class="clearfix">
			<?php /** Get no.of columns for search page */ 
  $no_of_columns = $thumbview ['searchcol'];
  $current_column = 1;
  /** Looping throught search video details */
  for($i = 0; $i < $totalRecords; $i ++) {
    $colcount = $current_column % $no_of_columns;
    
    if ($colcount == 1 || $no_of_columns == 1) {
      echo '<ul class="clearfix ulvideo_thumb">';
    }
    
    /** Check upload method
     * Get thumb url for search page  
     */
    if ($this->search [$i]->filepath == "File" || $this->search [$i]->filepath == "FFmpeg" || $this->search [$i]->filepath == "Embed") {
      if (isset ( $this->search [$i]->amazons3 ) && $this->search [$i]->amazons3 == 1) {
        $src_path = $dispenable ['amazons3link'] . $this->search [$i]->thumburl;
      } else {
        $src_path = "components/com_contushdvideoshare/videos/" . $this->search [$i]->thumburl;
      }
    } elseif ($this->search [$i]->filepath == "Url" || $this->search [$i]->filepath == "Youtube") {
      $src_path = $this->search [$i]->thumburl;
    } else {
      $src_path = '';
    }
    /** Display search page content */
    ?>
					<li class="video-item">
					<?php 
    if ($this->search [$i]->vid != '') {
/** Display video thumb for search results starts */
      ?>
						<div class="home-thumb">
					<div class="video_thumb_wrap">
		<?php /** Display video thumbs in search results */   
		  
		  /** Display watch later, add to playlist on search videos thumb */
		  displayVideoThumbImage ( $Itemid, $src_path, $this->search[$i], 'search', 100 );
		  
		  /** Display playlist popup in search page*/ 
		  displayPlaylistPopup  ( $this->search[$i], 'search', $Itemid ); ?>

		  </div>
		  <?php 
		  /** Display videos title for search thumbs */
		  			displayVideoTitle ( $Itemid, $this->search[$i], '' );
		  			?>
		
		<?php /** Display ratings in search view */
      if ($dispenable ['ratingscontrol'] == 1) {
        ?>
									<?php /** Calculate rate count for search results */
        if (isset ( $this->search [$i]->ratecount ) && $this->search [$i]->ratecount != 0) {
          $ratestar = round ( $this->search [$i]->rate / $this->search [$i]->ratecount );
        } else {
          $ratestar = 0;
        }
        ?>
									<div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div>
									<?php 
      }
		
      ?>

								<?php /** Display view count in search page */
      if ($dispenable ['viewedconrtol'] == 1) {
        ?>
									<span class="floatright viewcolor"><?php
        echo $this->search [$i]->times_viewed;
        ?>  <?php
        echo JText::_ ( 'HDVS_VIEWS' );
        ?></span>
								<?php
      }
      ?>
      
							</div>
							<?php
    }
    ?>
					</li>
						<?php /** First row for search page */
    if ($colcount == 0) {
      echo '</ul><div class="clear"></div>';
      $current_column = 0;
    }
    
    $current_column ++;
  }
  ?>
			</div>
	</div>
	<?php /** Tooltip Starts Here for search page */
  for($i = 0; $i < $totalRecords; $i ++) {
    ?>
			<div class="htmltooltip">
			<?php
    if ($this->search [$i]->description) {
/** Display video description in search results */
      ?>
					<div class="tooltip_discrip">
						<?php echo JHTML::_('string.truncate', (strip_tags($this->search[$i]->description)), 120); ?>
					</div>
				<?php
    }
    /** Display category name in tooltip for search page */
    ?>
				<div class="tooltip_category_left">
			<span class="title_category"><?php echo JText::_('HDVS_CATEGORY'); ?>: </span>
			<span class="show_category"><?php echo $this->search[$i]->category; ?></span>
		</div>
		<?php /** Display views in search results tooltip */
    if ($dispenable ['viewedconrtol'] == 1) {
      ?>
					<div class="tooltip_views_right">
			<span class="view_txt"><?php echo JText::_('HDVS_VIEWS'); ?>: </span>
			<span class="view_count"><?php echo $this->search[$i]->times_viewed; ?> </span>
		</div>
		<div id="htmltooltipwrapper<?php echo $i; ?>">
			<div class="chat-bubble-arrow-border"></div>
			<div class="chat-bubble-arrow"></div>
		</div>
		<?php
    }
    ?>
			</div>
			<?php
  }
  ?>
		<?php /** Tooltip ends for search page  */ ?>
	<ul class="hd_pagination">
<?php /** Pagination starts here for search */
videosharePagination ($this->search, $requestpage ); 
/** Pagination ends here for search*/
?> 
</ul>
<?php } ?>
</div>
<?php /** Get request url for search page */
$search_page = $_SERVER ['REQUEST_URI'];
$hidden_search_page = '';

if ($requestpage) {
  $hidden_search_page = $requestpage;
} 

/** Get language direction for search */
$searchRtlLang = getLanguageDirection(); 
?>
<script type="text/javascript">
<?php /** Assign search page language direction to script */?>
  rtlLang = <?php echo $searchRtlLang; ?>;
</script>
<?php /** Display pagination form for search page starts */?>
<form name="pagination" id="pagination" action="<?php echo $search_page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_search_page ?>" />
</form>
<?php /** Display pagination form for search page ends */?>