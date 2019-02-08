<?php
/**
 * User video view file
 *
 * This file is to display logged in user videos
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

$userId = (int) getUserID();

/** Get thumbview settings for my videos page */ 
$thumbview = unserialize ( $this->myvideorowcol [0]->thumbview );
/** Get dispenable for my videos page */
$dispenable = unserialize ( $this->myvideorowcol [0]->dispenable );

/** Get video page id from request for myvideos page */
$requestpage = JRequest::getInt ( 'video_pageid' );
/** Get item id for myvideos page */
$Itemid = $this->Itemid;
/** Get component path for myvideos page */
$src_path = "";
$componentPath = 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare';
/** Call function to display myvideos, myplaylists link for myvideos page*/
playlistMenu( $Itemid, JUri::getInstance() );
/** Display search box in myvideos page */
?>
<div class="player clearfix" id="clsdetail">
	<h1> <?php echo JText::_('HDVS_MY_VIDEOS'); ?></h1>
	<div class="myvideospage_topitem">
		<div id="myvideos_topsearch">
<?php /** Get myvideos page search box content */ 
$searchtxtbox = JRequest::getVar ( 'searchtxtboxmember' );
  $searchval = JRequest::getVar ( 'searchval' );
  $hidsearchtxtbox = JRequest::getVar ( 'hidsearchtxtbox' );
  /** Check search text is exists */
  if (isset ( $searchtxtbox )) {
    $searchboxval = $searchtxtbox;
  } elseif (isset ( $searchval )) {
    $searchboxval = isset ( $searchval ) ? $searchval : '';
  } else {
    $searchboxval = $hidsearchtxtbox;
  }
  /** Add styles for myvideos page */
  ?>
<style type="text/css">
.search_snipt { float: left; position: relative; height: 50px; }
.search_snipt #searcherrormessage { color: red; clear: both; position: absolute; bottom: 0; }
</style>
<?php /** Display search form in myvideos page starts */ ?>
<form name="hsearch" id="hsearch" method="post" action='<?php
    echo JRoute::_ ( $componentPath . '&view=myvideos', true );
    ?>' onsubmit="return searchValidation();">
				<div class="search_snipt">
					<input type="text" value="<?php echo $searchboxval; ?>"
						name="searchtxtboxmember" id="searchtxtboxmember"
						class="clstextfield clscolor"
						onkeypress="validateenterkey(event, 'hsearch');" />
					<div id="searcherrormessage"></div>
				</div>
<?php /** Display search button in myvideos page */ ?>
				<input type="submit" name="search_btn" id="search_btn"
					class="button myvideos_search"
					value="<?php
    echo JText::_ ( 'HDVS_SEARCH' );
    ?>" /> <input type="hidden" name="searchval" id="searchval"
					value=" <?php echo $searchboxval; ?>" />
				<?php /** Check user's allow upload option value */ 
				if ($this->allowupload ['allowupload'] == 1) {
/** If user have upload option 
 * then display add videos button */ 
?>
					<input type="button" class="button"
					value="<?php echo JText::_('HDVS_ADD_VIDEO'); ?>"
					onclick="window.open('<?php echo JRoute::_($componentPath . '&view=videoupload'); ?>', '_self');">
				<?php } ?>
			</form>
			<script type="text/javascript">
				function searchValidation() {
					if (document.getElementById('searchtxtboxmember').value == '') {
						document.getElementById('searcherrormessage').innerHTML = '<?php echo JText::_('HDVS_KEYWORD_TO_SEARCH'); ?>';
						return false;
					}
				}
			</script>
		</div>
	</div>
		
	<div class="clear"></div>
	<?php /** Display search form in myvideos page ends */ ?>
<?php $totalrecords = $thumbview ['myvideorow'] * $thumbview ['myvideocol'];

if (count ( $this->deletevideos ) - 4 < $totalrecords) {
  $totalrecords = count ( $this->deletevideos ) - 4;
}
/** Display search results for myvideos page */
if ($totalrecords == - 4) {
  if (! empty ( $searchboxval )) {
/** Display search results heading in myvideos page */ ?>
			<h3><?php echo JText::_('HDVS_SEARCH_RESULT') . " - $searchboxval"; ?></h3>
			<?php echo '<div class="hd_norecords_found"> ' . JText::_ ( 'HDVS_NO_RECORDS_FOUND_SEARCH' ) . '"' . $searchboxval . '"' . ' </div>';
  } else {
        echo '<div class="hd_norecords_found"> ' . JText::_ ( 'HDVS_NO_RECORDS_FOUND_MYVIDEOS' ) . ' </div>';
  }
} else {
      if (! empty ( $searchboxval )) { ?>
			<h3 class="home-link hoverable"><?php echo JText::_('HDVS_SEARCH_RESULT') . " - $searchboxval"; ?></h3>
<?php } ?>
	<ul class="myvideos_tab">
    <?php for($i = 0; $i < $totalrecords; $i ++) {
          if ((($i) % $thumbview ['myvideocol']) == 0) {
            echo '</ul><ul class="myvideos_tab clearfix">';
          } ?>
		<li class="rightrate"> 
    <?php /** Get thumb url for myvideos */ 
    if (($this->deletevideos [$i]->filepath == "File" || $this->deletevideos [$i]->filepath == "FFmpeg" || $this->deletevideos [$i]->filepath == "Embed") && $this->deletevideos [$i]->thumburl != "") {
        $src_path = "components/com_contushdvideoshare/videos/" . $this->deletevideos [$i]->thumburl;
        if (isset ( $this->deletevideos [$i]->amazons3 ) && $this->deletevideos [$i]->amazons3 == 1) {
          $src_path = $dispenable ['amazons3link'] . $this->deletevideos [$i]->thumburl;
        }
    }
    
    if ($this->deletevideos [$i]->filepath == "Url" || $this->deletevideos [$i]->filepath == "Youtube") {
      $src_path = $this->deletevideos [$i]->thumburl;
    }
    
    if ($this->deletevideos [$i]->vid != '') {
/** Myvideos page content starts */  
?>
		<div id="imiddlecontent1" class="clearfix">
			<div class="middleleftcontent clearfix">
			<div class="video_thumb_wrap">
<?php /** Display thumb image in myvideos page */
      /** Display watch later, add to playlist on myvideos thumb */
      displayVideoThumbImage ( $Itemid, $src_path, $this->deletevideos[$i], 'myvideos', 100 );   

      /** Display playlist popup in myvideos page*/ 
		  displayPlaylistPopup  ( $this->deletevideos[$i], 'myvideos', $Itemid ); ?>
								
					<div class="clear"></div>
					</div>

<?php /** Check comment type to display comment count */
if ($dispenable ['comment'] == 2) {
        $comment_count_row = Modelcontushdvideosharemyvideos::getmyvideocomment ( $this->deletevideos [$i]->vid );
        ?>
									<span class="floatleft viewcolor view comment-overwraping"> <a
						href="<?php
        echo generateVideoTitleURL ( $Itemid, $this->deletevideos[$i], 'myvideos' );
        ?>">
											<?php
        if (isset ( $comment_count_row )) {
          echo $comment_count_row;
        }
        ?></a>
										<?php $commentText = JText::_ ( 'HDVS_COMMENT' );
										/** Display comment text in myvideos page */
        if ($comment_count_row > 1) {
          $commentText = JText::_ ( 'HDVS_COMMENTS' );
        }  echo $commentText; ?>
									</span>
			<?php
      }
      
      if ($dispenable ['viewedconrtol'] == 1) {
/** Display view count in myvideos page */
        ?>
			<span class="floatright viewcolor views-overwraping">
				<?php echo $this->deletevideos[$i]->times_viewed . ' ' . JText::_('HDVS_VIEWS'); ?></span>
			<?php
      }
      ?>
							</div>
				<div class="featureright">
					<p class="myview myvideopage_title">
					<?php /** Disply video title in myvideos page */ ?>
						<a
							href="<?php echo generateVideoTitleURL ( $Itemid, $this->deletevideos[$i], 'myvideos' );  ?>"
							title="<?php echo $this->deletevideos[$i]->title; ?>">
										<?php /** Find substring for videos title displayed in myvideos page */
      if (strlen ( $this->deletevideos [$i]->title ) > 40) {
        echo JHTML::_ ( 'string.truncate', ($this->deletevideos [$i]->title), 40 );
      } else {
        echo $this->deletevideos [$i]->title;
      }
      ?></a>
					</p>
								<?php /** Display added on date in myvideos page */
      $addeddate = $this->deletevideos [$i]->addedon;
      $addedon = date ( 'j-M-Y', strtotime ( $addeddate ) );
      ?>
								<p class="myview"> <?php echo JText::_('HDVS_UPDATEDON') . ' : ' . $addedon; ?></p>
								<?php /** Display video type in myvideos page */ 
      if ($this->deletevideos [$i]->type == 0) {
        $vtype = JText::_ ( 'HDVS_PUBLIC' );
      } else {
        $vtype = JText::_ ( 'HDVS_PRIVATE' );
      }
      ?>
								<p class="myview viewcolor"> <?php echo JText::_('HDVS_VIDEO') . " : " . ' ' . $vtype; ?>
			<?php /** Get paly and edit button url */
      $playvideo = generateVideoTitleURL ( $Itemid, $this->deletevideos[$i], 'myvideos' );
      $editvideo = JRoute::_ ( $componentPath . '&view=videoupload&id=' . $this->deletevideos [$i]->vid . '&type=edit' );
      ?></p>
					<div class="myvideosbtns">
					<?php /** Display play, edit and delete button */ ?>
						<input type="button" name="playvideo" id="playvideo"
							onclick="window.open('<?php echo $playvideo; ?>', '_self')"
							value="<?php echo JText::_('HDVS_PLAY'); ?>" class="button" /> <input
							type="button" name="videoedit" id="videoedit"
							onclick="window.open('<?php echo $editvideo; ?>', '_self')"
							value="<?php echo JText::_('HDVS_EDIT'); ?>" class="button" /> <input
							type="button" name="videodelete" id="videodelete"
							value="<?php echo JText::_('HDVS_DELETE'); ?>" class="button"
							onclick="var flg = my_message(<?php echo $this->deletevideos[$i]->vid; ?>);
					return flg;" />
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php
    }
  }
  ?>
  </ul>
<?php /** Myvideos pagination starts here */ ?>
    <ul class="hd_pagination">
<?php videosharePagination ($this->deletevideos, $requestpage ); ?>
    </ul>
<?php /** Myvideos pagination ends here */
} ?>
</div>
<?php /** Get request url */ 
$page = $_SERVER ['REQUEST_URI'];
$deleteVideo = $sorting = '';
/** Get deletevideo param from request */
if (JRequest::getInt ( 'deletevideo' )) {
  $deleteVideo = JRequest::getInt ( 'deletevideo' );
}
/** Get sorting param from request */
if (JRequest::getString ( 'sorting' )) {
  $sorting = JRequest::getString ( 'sorting' );
}
/** Display form in myvideos page for delete videos */
?>
<form name="deletemyvideo" action="<?php echo $page; ?>" method="post">
	<input type="hidden" name="deletevideo" id="deletevideo"
		value="<?php echo $deleteVideo; ?>">
</form>
<?php /** Get search box values for hidden fields */
$searchtextbox = JRequest::getString ( 'searchtxtboxmember' );
$hiddensearchbox = JRequest::getString ( 'hidsearchtxtbox' );
/** Get current page number for myvideo page */
if ($requestpage) {
  $hidden_page = $requestpage;
} else {
  $hidden_page = '';
} 
/** Display pagination form in myvideos page */ ?>
<form name="pagination" id="pagination" action="<?php echo $page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_page ?>" /> 
</form>
<?php /** Display pagination form in myvideos page ends */ ?>
<form name="sortform" action="<?php echo $page; ?>" method="post">
	<input type="hidden" name="sorting" id="sorting"
		value="<?php echo $sorting; ?>">
</form>
<?php /** Get language direction for myvideos */
$rtlLang = getLanguageDirection(); ?>
<script type="text/javascript">
<?php 
/** Assign langauge direction to script
 * Get item id and assign to script */ ?>
  rtlLang = <?php echo $rtlLang; ?>;
  itemid = <?php echo $Itemid; ?>;
</script>
