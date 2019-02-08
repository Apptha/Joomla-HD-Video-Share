<?php 
/**
 * Subscriber view file for channel view
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/**
 * This is used to diplay all subscriber user on the front page
 * This page show subscriber thumb image with subscriber name
 * Next to subscriber name we displayed subscriber channel description 
 * Next to subscriber channel description we provide a button "subscribe"
 * If user click that button then notification is send to that subscribed user about this user
 * Then that subscribed user is added to his my subscribtion page
 * We can also provide an option to view that subscriber channel before he/she subscribe that user
 * That is by clicking subscriber thumb image or subscriber name
 * We use a function subscriperDetailsView to get subscriber details on the front page
 * This function also used to display searched subscriber details on the front page
 * This fucntion contains channelSearchTitle variable that hold searched subscriber name
 * If channelSearchTitle has any null value then channelMyVideos fucntion call subscriperDetailsModel method
 * If channelSearchTitle has any value value then channelMyVideos fucntion call subscriperSearchDetailsModel method
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
	$dataAttr='subscriber';
} else {
	$dataAttr='channel';
}

$result = $profileImage = $subUrl =  '';
$subscripeDetails = $this->subscripeUserDetails;
foreach($subscripeDetails as $subscripeDetail) {
    $userContent = json_decode($subscripeDetail->user_content,true);
$profileImage = ($userContent['profileImage'] != '') ? JURI::base(). "images/channel/banner/profile/".$userContent['profileImage'] : JURI::base(). "components/com_contushdvideoshare/images/".'subs.png';
$subUrl = JRoute::_('index.php?option=com_contushdvideoshare&task=subscripe&ukey='.$subscripeDetail->user_key);
$subsName = (strlen($subscripeDetail->user_name) > 20 ) ? substr($subscripeDetail->user_name,0,20).".." : $subscripeDetail->user_name;
$result .=' <div class="subscripRow">
       <div class="subscripeImage" ><img src="'.$profileImage.'" style="width:160px;height:160px;"></div>
        <div class="subscripeContent">
            <a href="'.$subUrl.'" style="text-decoration:none !important" target="_blank"><h3 class="mysubscripeTitle"><span style="cursor:pointer">'.$subsName.'</span></h3></a>
            <p class="subscripeDescription">';
            if(strlen($userContent[DESCRIPTION]) > 150 ) {
            $result .= substr($userContent[DESCRIPTION],0,150) . '...';
} else{
$result .= $userContent[DESCRIPTION];
} 
          $result .= '</p>
        </div>
        <div style="clear:both"></div>
        <div class="subscripeLink">
            <span class="subscripeLinkButton" onclick="saveSubscriper(this, \''.$dataAttr.'\');">Subscribe</span><input type="hidden" class="subscriperId" value="'.$subscripeDetail->user_id.'">
        </div>
    </div>';
}
if(!empty($subscripeDetails)) {
	echo $result;	
}
else {
	echo "<p class='novideostext'>No channel to subscribe</p><div style='clear:both'></div>";
}
exitAction ( '' );