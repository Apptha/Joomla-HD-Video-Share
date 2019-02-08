<?php
/**
 * Subscriber view file
 * 
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
$userId          = $user->id;
define('PROFILE_IMAGE','profileImage');
$profile_path = 'images/channel/banner/profile/';
$profileImage    = $this->userContent[PROFILE_IMAGE];
$coverImage      = $this->userContent['coverImage'];
$description     = $this->userContent['description'];
$profileImageUrl = JURI::base(). $profile_path.$profileImage;
$coverImageUrl   = JURI::base(). "images/channel/banner/cover/".$coverImage;
$mysubscriperCount = count(json_decode($this->mysubscriperCount,true));
$channelUserName = $this->channelContent->user_name;
if($userId !=0 && $user->guest == 0) {
	$isAdmin = in_array('8',$user->groups);
}
$u_agent = $_SERVER['HTTP_USER_AGENT'];
preg_match('/MSIE/i',$u_agent,$ie);

/** Call function to display myvideos, myplaylists link for subcriber page*/
playlistMenu( '', JUri::getInstance() );
?>
<div style="clear: both;"></div>
<div class="Channelcontainer" id="Channelcontainer">
<div class="loadingBar" id="loadingBar"></div>
<div class="bannerContainer" id="bannerContainer">
<div class="coverContainer" id="coverContainer">
<?php if(empty($coverImage) || !file_exists(JPATH_ROOT. "/images/channel/banner/cover/".$coverImage) ) {
?>	
<img src="<?php echo JURI::base(). "components/com_contushdvideoshare/images/normalCover.jpg"; ?>" class="coverImages" id="coverImages" style="height:250px; width:100%" />
<?php } 
else {
?>
<img src="<?php echo $coverImageUrl; ?>" class="coverImages" id="coverImages" style="height:250px;"/>
<?php } 
if($isAdmin) {
?>
<div class="coverEditor" id="coverEditor">
<img src="<?php echo JURI::base(). "components/com_contushdvideoshare/images/edit.gif"; ?>" style="height:37px;width:37px;" />
</div>
<?php 
}
?>
</div>
<div class="profileContainer" id="profileContainer">
<?php if(empty($profileImage) || !file_exists(JPATH_ROOT. "/".$profile_path.$profileImage)) { 
?>
<img src="<?php echo JURI::base(). "components/com_contushdvideoshare/images/subs.png"; ?>"  class="profileImages" id="profileImages" style="width:160px;height:160px; opacity:0.7" />
<?php } 
else { 
?>
<img src="<?php echo $profileImageUrl; ?>"  class="profileImages" id="profileImages" style="width:inherit;height:inherit;" />
<?php }
if($isAdmin) {
?>
<div class="profileEditor" id="profileEditor">
<img src="<?php echo JURI::base(). "components/com_contushdvideoshare/images/edit.gif"; ?>" style="height:37px;width:37px;" />
</div>
<?php 
}
?>
</div>
<div class="dragContainer" id="dragContainer" data-view="subscriber">
<div class="dragRow" id="dragRow">
<p class="closeButton" id="closeButton"><span>X</span></p>
<?php 
if(empty($ie)) {
?>
<h3 class="dropHeading" id="dropHeading">Drop image to upload</h3>
<p class="orText" id="orText">or</p>
<div class="fileContainer" id="fileContainer">
<p class="imageButtonRow" id="imageButtonRow"><span class="imageButton" id="imageButton">Select Image</span></p>
<?php 
}
?>
<?php 
if(!empty($ie)) {
?>
<form action="<?php echo JURI::base().'index.php?option=com_contushdvideoshare&task=saveSubscriperImage'; ?>" method="POST" enctype="multipart/form-data" class="uploadSubmitButton" name="uploadSubmitButton">
<input type="file" name="images" class="fileContent browseieonly" id="browseieonly" data-view="subscriber">
<input type="hidden"  name="subid" class="ui"  id="ui" value="<?php echo $this->subscriberId; ?>">
<input type="hidden"  name="uploadType" class="uploadTypeValue" id="class="uploadTypeValue"" >
<input type="submit" value="Upload Image" class="onlyie_button" id="onlyie_button">
</form>
<?php 
}
else {
?>
<input type="file" name="images" class="fileContent" id="fileContent" style="display:none" data-view="subscriber">
<?php 
}
?>
</div>
<p class="pixelCondition" id="pixelCondition"></p>
</div>
</div>
 <div class="cropContainer" id="cropContainer" style="background-image:url('<?php echo $coverImageUrl; ?>');">
</div>
<div class="profileDragContainer" id="profileCropContainer" style="display:none;">
</div>
<div class="dragBox" id="dragBox">
<div class="rotate" id="rotate">
  <div class="line" id="line"><i></i></div>
  <div class="line" id="line1"><i></i></div>
  <div class="line" id="line2"><i></i></div>
  <div class="line" id="line3"><i></i></div>
  <p class="channel_profile_text" id="channel_profile_text">Drag reposition</p>
  </div>
  </div>
<div class="channel_dragreposition" id="channel_dragreposition"><p>Drag image to select area to crop</p></div>
</div>
<input type="hidden" class="ui" id="usi" value="<?php echo JFactory::getUser()->id; ?>">
<div class="saveButtonContainer" id="saveButtonContainer">
<p class="saveButtonRow" id="saveButtonRow"><span class="saveButton" data-view="subscriber">Crop Image</span><span class="cancelButton">Cancel</span></p>
</div>
<div class="dragButtonContainer" id="dragButtonContainer">
<p class="dragButtonRow" id="dragButtonRow"><span class="saveProfileImage" data-view="subscriber">Crop Image</span><span class="cancelButton">Cancel</span></p>
</div>
<div class="user_profile_name">
	<h3 class="authorHeading" id="authorHeading"><?php echo $this->channelContent->user_name; ?></h3>
</div>
 <div class="videoTopContainer" id="videoTopContainer">
<div class="videoTop clearfix" id="videoTop">
<input type="text" name="search" class="search"><span class="searchButton" data-view="subscriber">Search</span>
</div>
</div>
<div class="channelContentContainer" id="channelContentContainer">
<div class="channelMenuContainer" id="channelMenuContainer">
    <p class="myVideosButton" id="myVideosButton" data-view="subscriber">Videos</p>  
    <?php 
    if($isAdmin) {
    ?>  
    <p class="mySubscriptionButton" id="mySubscriptionButton" data-view="subscriber">My Subscription &nbsp;&nbsp;(<span class="subscriptionCount"><?php echo ($mysubscriperCount != 0) ? $mysubscriperCount : '0'; ?></span>)</p>
    <?php 
    }
    ?>
    <p class="aboutButton active" id="aboutButton" data-view="subscriber">About</p>
</div>
<div class="videoContainer" >
<div class="videoContent" id="videoContent">
</div>
</div>
<div class="aboutContainer" id="aboutContainer">
<?php
if($isAdmin) {
?>
<h3 class="descriptionHeading" id="descriptionHeading">Channel Name</h3>
<input type="field" name="userName" class="userName" id="userName" value="<?php echo $channelUserName; ?>"><br>
<?php 
}
?>
<h3 class="descriptionHeading" id="descriptionHeading">Channel Description</h3>
<textarea class="channelDescription" id="channelDescription"><?php echo $description; ?></textarea>
<?php
if($isAdmin) {
?>
<p class="descriptionButtonRow" id="descriptionButtonRow"><span class="saveDescription" data-view="subscriber">Save</span></p>
<?php 
}
?>
</div>
<div class="mysubscriptionContainer" id="mysubscriptionContainer">
<div class="mysubscriptionRow" id="mysubscriptionRow">
</div>
<div style="clear:both"></div>
</div>
<div class="subscripeContainer" id="subscripeContainer">
</div>
<div style="clear:both"></div>
</div>
<div class="playerContainer" id="playerContainer">
<div class="popup_player" id="player">
</div>
</div>
</div>
<?php 
if(!$isAdmin) {
?>
<script>
jQuery('.channelDescription').prop("disabled", "disabled");
</script>
<?php 
}
?>