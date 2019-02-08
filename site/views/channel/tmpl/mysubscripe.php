<?php
/**
 * Mysubscribe view file
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
 
/**
 * This page display my subscriber link on the front page
 * If user click My Subscription button then all his subscriber link with thumb image are displayed on the front page
 * User can also remove subscriber from its page by clicking X button on the top right corner of the thumb image
 * Below the thumb image we displayed subscriber name
 * If user click thumb image or subscriber name then we open that subscriber page on new window
 * We use closeChannel funciton to exit the execution.
 * This page uses mySubscriperDetailsView function to display my subscribtion on the front page
 * This fucntion call mySubscriperDetailsModel method to get the my subscriber details
 * This function assign mySubscriperDetails to mySubscripeUserDetails variable
 * A template controls the overall look and layout of a site.
 * It provides the framework that brings together common elements,
 * modules and components as well as providing the cascading style sheet for the site.
 * Both the front-end and the back-end of the site have templates.
 * Joomla needs to determine which component, view, and task to execute. 
 * It does so by reading the values from the url.
 * If the URL does not specify a task, Joomla looks for a default tasked named display. 
 * The display task is setup within our view.html.php file.
 * A template is used to manipulate the way content is delivered to a web browser or screen reader.
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
$mySubscripeDetails = $this->mySubscripeUserDetails;
if(!empty($mySubscripeDetails)) {
	foreach($mySubscripeDetails as $mySubscripeDetail) {
            $userContent = json_decode($mySubscripeDetail->user_content,true);
		$profileImage = ($userContent['profileImage'] != '') ? JURI::base(). "images/channel/banner/profile/".$userContent['profileImage'] : JURI::base(). "components/com_contushdvideoshare/images/".'subs.png';
		$subUrl = JRoute::_('index.php?option=com_contushdvideoshare&task=subscripe&ukey='.$mySubscripeDetail->user_key);
		$subsName = (strlen($mySubscripeDetail->user_name) > 20 ) ? substr($mySubscripeDetail->user_name,0,20).".." : $mySubscripeDetail->user_name;
		$result .= '<div class="mysubscription" style="position:relative">
<a href="'.$subUrl.'" style="text-decoration:none !important" target="_blank"><img src="'.$profileImage.'" style="width:160px;height:160px;"></a>
<a href="'.$subUrl.'" style="text-decoration:none !important;" target="_blank"><h3 class="subscripeTitle" style="padding-left:5px;">'.$subsName.'</h3></a>
<div class="closeSubscripe" style="cursor:pointer" onclick="closemysubscripers(this,\''.$dataAttr.'\')"><img src="'.JURI::base().'components/com_contushdvideoshare/images/playerclose.png"></div>
<input type="hidden" class="msid" value="'.$mySubscripeDetail->user_id.'">
		</div>';
	}
	echo $result;
	closeChannel();
}
else {
	echo "<p class='novideostext'>No subscription</p><div style='clear:both'></div>";
	closeChannel();
}
function closeChannel() {
	exitAction ( '' );
}