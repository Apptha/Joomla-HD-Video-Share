<?php
/**
 * Myvideos view file for channel view
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
/**
 * This page is used to display all channel video uploaded by user on the front page.
 * We use a function channelMyVideos in view page that is used to display channel videos on the front page
 * This function also used to display searched video on the front page
 * This fucntion contains videoSearchTitle variable that hold video searched name
 * If videoSearchTitle has any null value then channelMyVideos fucntion call getChannelVideoDetails method
 * If videoSearchTitle has any value value then channelMyVideos fucntion call getSearchedVideoDetails method
 * In video link we first display video thumb image
 * Next to video thumb image we display video name
 * Next to video name we display video category 
 * Next to video category we display video rate count 
 * Next to video rate count we display video count. 
 * A template controls the overall look and layout of a site.
 * It provides the framework that brings together common elements,
 * modules and components as well as providing the cascading style sheet for the site.
 * Both the front-end and the back-end of the site have templates.
 * A template is used to manipulate the way content is delivered to a web browser or screen reader.
 * Joomla needs to determine which component, view, and task to execute. 
 * It does so by reading the values from the url.
 * If the URL does not specify a task, Joomla looks for a default tasked named display. 
 * The display task is setup within our view.html.php file.
 * The template is the place where the design of the main layout is set for your site.
 * This includes where you place different elements (modules).
 * For example: You can control the placement of menus,
 * a log in form, advertising banners, polls, etc
 * One of these tasks is to create the aesthetic (the look, feel and layout) of the site. 
 * This includes making decisions such as which content elements (components, modules and plugins) 
 * you may want to place in any given page.
 */
if(isset($this->subscriberId)) {
  $dataAttr='data-view="subscriber"';
} else {
  $dataAttr='data-view="channel"';
}

/** Get current user ID */
$userId         = (int) getUserID ();
$myVideos       = '';
$videoDetails   = $this->channelMyVideosDetails; 
if(!empty($videoDetails)) {
foreach($videoDetails as $videoDetail) {
	if($videoDetail->filepath == 'File' || $videoDetail->filepath == 'Embed') {
		$thumbUrl = JURI::base(). "components/com_contushdvideoshare/videos/".$videoDetail->thumburl;
	}else {
		$thumbUrl = $videoDetail->thumburl;
	}
	global $tablePrefix, $contusDB, $contusQuery;
	$contusQuery->clear();
	$contusQuery->select('category');
	$contusQuery->from($contusDB->quoteName($tablePrefix.'hdflv_category'));
	$contusQuery->where($contusDB->quoteName('id').' = '.$contusDB->quote($videoDetail->playlistid));
	$contusDB->setQuery($contusQuery);
	$videoCategory = $contusDB->loadResult();
	$contusQuery->clear();
	$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );
	if (isset ( $videoDetail->ratecount ) && $videoDetail->ratecount != 0) {
		$ratestar = round ( $videoDetail->rate / $videoDetail->ratecount );
	} else {
		$ratestar = 0;
	}	
	/** Function call for fetching featured page Itemid from helper */
	$Itemid = getmenuitemid_thumb ('player', '');
	$videoPageURL = generateVideoTitleURL ( $Itemid, $videoDetail, '' );
	$videoTitle = (strlen($videoDetail->title) > 15) ? substr($videoDetail->title,0,15)."..." : $videoDetail->title;
	$starimg = JURI::base().'components/com_contushdvideoshare/videos/star.png';
	?>
<div class="videoRow">
  <div class="video_thumb_wrap">
    <a class="thumbImage" <?php echo $dataAttr; ?> href="<?php echo $videoPageURL; ?>"> 
        <img src="<?php echo $thumbUrl; ?>" style="width:180px;height:100px;"/>
        <?php if(!empty($userId) && !empty($videoDetail->VideoId)) { ?>
        <div class="watched_overlay"></div>
        <?php } ?>
        </a>
<?php /** Display watch later icon except watch later pages */
/** Check user id is exist or not.
 * If user id is exist, then display watch later icon */ 
    if(!empty($userId)) { 
        if(empty($videoDetail->video_id)){ 
?>
<a class="watch_later_wrap" href="javascript:void(0)" onclick="addWatchLater('<?php echo $videoDetail->vid; ?>','<?php echo JURI::base(); ?>', this)">
  <span class="watch_later default-watch-later" title="<?php echo JText::_ ( 'HDVS_ADD_TO_LATER_VIDEOS' ); ?>"  ></span> </a>
<?php 
} else { ?>
<a class="watch_later_wrap" href="javascript:void(0)"> 
<span class="watch_later success-watch-later" title="<?php echo JText::_ ( 'HDVS_ADDED_TO_LATER_VIDEOS' );?>"></span> 
</a>
<?php 
 } 
    } 
    
        /** Display Add to playlist icon on the thumb image */ ?> 
<a href="javascript:void(0)" onclick="return openplaylistpopup('chanvideos',<?php echo $videoDetail->vid;?>)"
    	class="add_to_playlist_wrap"> <span class="add_to_playlist" title="<?php echo JText::_ ( 'HDVS_ADD_TO_PLAYLIST' ); ?>"></span>
    </a>
<?php displayPlaylistPopup ( $videoDetail, 'chanvideos', $Itemid ); ?>
    </div>
<h3 class="videoTitle"><?php echo $videoTitle; ?></h3>
<p class="videoCategory"><?php echo $videoCategory; ?></p>
<span class="chanvideos ratethis1 <?php echo $ratearray[$ratestar]; ?> "></span>
<span class="videoCount"><?php echo $videoDetail->times_viewed .' views'; ?></span>
<input type="hidden" name="vid" class="vid" value="<?php echo $videoDetail->id; ?>">
<input type="hidden" name="pid" class="pid" value="<?php echo $videoDetail->playlistid;?>">
</div>
<?php } ?>
<div style="clear:both"></div>
<?php }
else {
	echo "<p class='novideostext'>No video in this channel</p><div style='clear:both'></div>";
}
exitAction ( '' );
?>