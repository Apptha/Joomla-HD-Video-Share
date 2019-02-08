<?php
/**
 * Channel view file
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
/**
 * This is used to display channel for the registered user, This page has the following features
 * This page show cover image and profile image on the top of page
 * Following the banner and profile image this template display channel user name and notification icon
 * Notification icon not visible to user unless any other user subscribe this user channel.
 * Following the channel name and notification icon this template show video search bar
 * Next to video search bar, template show browse channel button
 * Next to browse channel button, template show upload video button.
 * Next to search bar and some of button then it show My Videos button
 * Next to  My Videos and some of button then it show My Subscription button
 * Next to  My Subscription and some of button then it show About button
 * On Cover image there is an edit button on the right top of cover image.
 * Edit icon visible once user mouseover the cover image
 * If user click that icon then template show upload image area to upload cover image
 * There are two ways to upload cover image.
 * One way is by clicking Select image button
 * Other way is by drag and drop image to that area to upload image
 * Once the cover image is uploaded then it show that image to user to crop image
 * After cropping then that image visibe in channel cover area.
 * On Profile image there is an edit button on the right top of cover image.
 * Edit icon visible once user mouseover the Profile image
 * If user click that icon then template show upload image area to upload Profile image
 * There are two ways to upload Profile image.
 * One way is by clicking Select image button
 * Other way is by drag and drop image to that area to upload image
 * Once the Profile image is uploaded then it show that image to user to crop image
 * After cropping then that image visibe in channel Profile area.
 * Search bar is used for two purposes one is to search channel videos
 * Another one is to search channel.
 * If user click Browse channel button then it show all channel user to subscribe.
 * If user click upload video button then it video upload area on the new page.
 * If user click My Video button then it show all videos uploaded by registered user.
 * If user click My Subscription then it show all subscribed user link
 * If user click About Us link then it show channel name and channel description
 * We can directly edit channel name and channel description by clicking save button on that  area.
 */
$profileImage = $coverImage = $userId = $user = $profileImageUrl = $coverImageUrl = $subscripeDetails = '';
$user            = JFactory::getUser();
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$appObj = JFactory::getApplication();
$tablePrefix = $appObj->getCfg('dbprefix');
$userId          = $user->id;
define('PROFILE_IMAGE','profileImage');
define('SUBCID','sub_id');
$channel_subscribe = 'hdflv_channel_subscribe';
$user_ID = 'user_id';
define('SUBSCRIBER_LINK','index.php?option=com_contushdvideoshare&task=subscripe&ukey=');
$notification_image = 'subs.png';
$profile_path = 'images/channel/banner/profile/';
$profileImage    = $this->userContent[PROFILE_IMAGE];
$coverImage      = $this->userContent['coverImage'];
$description     = $this->userContent['description'];
$channelUserName = $this->channelContent->user_name;
$profileImageUrl = JURI::base(). $profile_path.$profileImage;
$coverImageUrl   = JURI::base(). "images/channel/banner/cover/".$coverImage;
$mysubscriperCount = count(json_decode($this->mysubscriperCount,true));
$notificationDetails = $this->notificationDetails;
$u_agent = $_SERVER['HTTP_USER_AGENT'];
preg_match('/MSIE/i',$u_agent,$ie);
$query->clear();
$query->select(SUBCID);
$query->from($db->quoteName($tablePrefix.$channel_subscribe));
$query->where($db->quoteName($user_ID). ' = '.$db->quote($user->id));
$db->setQuery($query);
$subId = json_decode($db->loadResult(),true);
$query->clear();

/** Call function to display myvideos, myplaylists link for my channel page*/
playlistMenu( '', JUri::getInstance() );
?>
<div style="clear: both;"></div>
<div class="Channelcontainer">
<div class="loadingBar"></div>
<div class="bannerContainer">
<div class="coverContainer">
<?php if(empty($coverImage) || !file_exists(CHANNEL_DIRPATH . 'cover/' . $coverImage)) {
?>	
<img src="<?php echo IMAGE_DIRPATH . 'normalCover.jpg'; ?>" class="coverImages" style="height:250px; width:100%" />
<?php } 
else {
?>
<img src="<?php echo $coverImageUrl; ?>" class="coverImages" style="height:250px;"/>
<?php } 
?>
<div class="coverEditor">
<img src="<?php echo IMAGE_DIRPATH . 'edit.gif'; ?>" title="Edit Cover Image" />
</div>
</div>
<div class="profileContainer">
<?php if(empty($profileImage) || !file_exists(JPATH_ROOT. "/".$profile_path.$profileImage) ) {
?>
<img src="<?php echo IMAGE_DIRPATH . $notification_image; ?>"  class="profileImages" style="width:160px;height:160px; opacity:0.7" />
<?php } 
else { 
?>
<img src="<?php echo $profileImageUrl; ?>"  class="profileImages" style="width:inherit;height:inherit;" />
<?php } 
?>
<div class="profileEditor">
<img src="<?php echo IMAGE_DIRPATH . 'edit.gif'; ?>" title="Edit Profile"/>
</div>
</div>
<div class="dragContainer" id="dragContainer" data-view="channel">
<div class="dragRow">
<p class="closeButton"><span>X</span></p>
<?php 
if(empty($ie)) {
?>
<h3 class="dropHeading" id="dropHeading">Drop image to upload</h3>
<p class="orText">or</p>
<div class="fileContainer">
<p class="imageButtonRow"><span class="imageButton">Select Image</span></p>
<?php 
}
if(!empty($ie)) {
?>
<form action="<?php echo JURI::base().'index.php?option=com_contushdvideoshare&task=imageUpload'; ?>" method="POST" enctype="multipart/form-data" class="uploadSubmitButton" name="uploadSubmitButton">
<input type="file" name="images" class="fileContent browseieonly" data-view="channel">
<input type="hidden"  name="ui" class="ui" value="<?php echo JFactory::getUser()->id; ?>">
<input type="hidden"  name="uploadType" class="uploadTypeValue" >
<input type="submit" value="Upload Image" class="onlyie_button" >
</form>
<?php 
}
else {
?>
<input type="file" name="images" class="fileContent" style="display:none" data-view="channel">
<?php 
}
?>
</div>
<p class="pixelCondition"></p>
</div>
</div>
<div class="cropContainer" id="cropContainer" style="background-image:url('<?php echo $coverImageUrl; ?>');">
</div>
<div class="profileDragContainer" id="profileCropContainer" style="display:none;">
</div>
<div class="dragBox">
<div class="rotate">
  <div class="line"><i></i></div>
  <div class="line"><i></i></div>
  <div class="line"><i></i></div>
  <div class="line"><i></i></div>
  <p class="channel_profile_text">Drag reposition</p>
  </div>
  </div>
<div class="channel_dragreposition"><p>Drag image to select area to crop</p></div>
</div>
<!-- <div class="dragButtonRow"><p>Drag image to select area to crop</p></div> -->
<input type="hidden" class="ui" value="<?php echo JFactory::getUser()->id; ?>">
<div class="saveButtonContainer">
<!-- <div class="dragButtonRow"><p>Drag image to select area to crop</p></div> -->
<p class="saveButtonRow"> <br> <span class="saveButton" data-view="channel">Crop Image</span><span class="cancelButton">Cancel</span></p>
</div>
<div class="dragButtonContainer">
<p class="dragButtonRow"><span class="saveProfileImage" data-view="channel">Crop Image</span><span class="cancelButton">Cancel</span></p>
</div>
<div class="user_profile_name">
<h3 class="authorHeading"><?php echo $this->channelContent->user_name; ?></h3>
<?php 
if(!empty($notificationDetails)) {
?>
<div class="notifi notificationsection">
<img src="<?php echo IMAGE_DIRPATH. 'notification.png'; ?>" class="notificationLink">
<span class="ncount"><span class="countno"><?php echo count($notificationDetails)?></span></span>
<div class="notificationContainers">
<div class="notificationRows">
<ul class="notificationParent">
<?php 
$notificationCount = '';
$notificationCount = count($notificationDetails);
foreach($notificationDetails as $notification) {
    $userContent = json_decode($notification->user_content,true);
$notificationImage = ($userContent[PROFILE_IMAGE] != '') ? JURI::base(). $profile_path.$userContent[PROFILE_IMAGE] : IMAGE_DIRPATH . $notification_image;
$userUrl = JRoute::_(SUBSCRIBER_LINK.$notification->user_key);
?>
<li class="notificationLis" id="<?php echo 'n'.$notification->user_id; ?>">
<a href="<?php echo $userUrl;?>" target="_blank">
<img src="<?php echo $notificationImage; ?>" class="subImage" style="width:50px;height:50px;">
</a><a href="<?php echo $userUrl;?>" target="_blank">
<span class="notificationText"><?php echo (strlen($notification->user_name) > 20 ) ? substr($notification->user_name,0,20).'... ' : $notification->user_name ; ?></span></a>
<p style="margin:0 !important" class="notButtonRow">

<?php 
if(empty($subId) || !in_array($notification->user_id,$subId)) {
?>
<span class="subButton" data-view="channel">Subscribe</span>
<?php 
}
?>
<span class="subDeleteButton" data-view="channel">Delete</span><input type="hidden" class="subscriperId" value="<?php echo $notification->user_id; ?>"></p>
</li>
<?php 
}
?>
</ul>
</div>
<?php 
if($notificationCount > 3) {
?>
<div class="seeMoreLink">See More</div>
<?php 
}
?>
</div>
</div>
<?php 
}
?>
<div style="clear:both"></div>
</div>
 <div class="videoTopContainer">
<div class="videoTop clearfix">
	<input type="text" name="search" class="search channelsearch plcholdercls" id="dynamicplacholder">
	<span class="searchChannelButton" data-view="channel">Search Channel</span>
	<span class="searchButton" id="searchButton" data-view="channel">Search</span>
	<span class="browseChannelButton" data-view="channel">Browse Channel</span>
<?php $videoUpload = JRoute::_('index.php?option=com_contushdvideoshare&view=videoupload'); ?>
<a href="<?php echo $videoUpload; ?>" target="_blank"><span class="uploadButton">Upload Videos</span></a>
</div>
</div>
<div class="channelContentContainer">
<div class="channelMenuContainer">
    <a id="ch_myVideosButton" href="<?php echo JRoute::_ ( "index.php?option=com_contushdvideoshare&view=myvideos" ); ?>">My Videos</a>    
    <p class="mySubscriptionButton" id="ch_mySubscriptionButton" data-view="channel">My Subscription &nbsp;&nbsp;(<span class="subscriptionCount"><?php echo ($mysubscriperCount != 0) ? $mysubscriperCount : '0'; ?></span>)</p>
    <p class="aboutButton active" id="ch_aboutButton" data-view="channel">About</p>
</div>
<div class="videoContainer">
<div class="videoContent">
</div>
</div>
<div class="aboutContainer">
<h3 class="descriptionHeading">Channel Name</h3>
<input type="field" name="userName" class="userName" value="<?php echo $channelUserName; ?>"><br>
<h3 class="descriptionHeading">Channel Description</h3>
<textarea class="channelDescription"><?php echo $description; ?></textarea>
<p class="descriptionButtonRow"><span class="saveDescription" data-view="channel">Save</span></p>
</div>
<div class="mysubscriptionContainer"> 
<div class="mysubscriptionRow">
</div>
<div style="clear:both"></div>
</div>
<div class="subscripeContainer">
</div>
<?php 
if(!empty($notificationDetails)) {
?>
<div class="notificationContainer">
<div class="notificationRow">
<ul>
<?php 
$notificationCount = '';
$notificationCount = count($notificationDetails);
foreach($notificationDetails as $notification) {
    $userContent = json_decode($notification->user_content,true);
$notificationImage = ($userContent[PROFILE_IMAGE] != '') ? JURI::base(). $profile_path.$userContent[PROFILE_IMAGE] : IMAGE_DIRPATH . $notification_image;
$userUrl = JRoute::_(SUBSCRIBER_LINK.$notification->user_key);
?>
<li class="notificationLi" id="<?php echo 'n'.$notification->user_id; ?>">
<a href="<?php echo $userUrl;?>" target="_blank">
<img src="<?php echo $notificationImage; ?>" class="subImage" style="width:50px;height:50px;"></a>
<a href="<?php echo $userUrl;?>" target="_blank">
<span class="notificationText"><?php echo (strlen($notification->user_name) > 20 ) ? substr($notification->user_name,0,20).'... ' : $notification->user_name ; ?></span></a>
<p style="margin:0 !important" class="notButtonRow">
<?php 
if(!in_array($notification->user_id,$subId)) {
?>
<span class="subButton" id="subButton" data-view="channel">Subscripe</span>
<?php 
}
?>
<span class="subDeleteButton" id="subDeleteButton" data-view="channel">Delete</span><input type="hidden" class="subscriperId" value="<?php echo $notification->user_id; ?>"></p>
</li>
<?php 
}
?>
</ul>
</div>
<p class="descriptionButtonRow"><span class="deleteNotification" data-view="channel">Delete</span><span class="cancelNotification">Cancel</span></p>
</div>
<?php 
}
?>
<div style="clear:both"></div>
</div>
<div class="playerContainer">
<div class="popup_player" id="player">
</div>
</div>
</div>
