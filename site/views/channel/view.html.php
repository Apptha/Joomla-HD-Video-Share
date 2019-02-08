<?php
/**
 * Channel view file
 * 
 * This file is to display channel
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
/**
 * Import Joomla view library
 */
jimport('joomla.application.component.view');
/**
 * Channel view class.
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class ContushdvideoshareViewchannel extends ContushdvideoshareView {
	/**
	 * Function to set layout and model for view page.
	 * The display method of the JView class is called with the display task of the JController class.
	 * In our case, this method will display data using the tmpl/default.php file. 
	 * With your favorite file manager and editor, 
	 * create a file site/views/helloworld/tmpl/default.php able to display the default view
	 * 
	 * @param   boolean  $urlparams  An array of safe url parameters and their variable types
	 * @return  ContushdvideoshareViewadsxml		This object to support chaining.
	 * @since   1.5
	 */
	public function display($tpl = NULL) {
	    global $appObj;
		$model = $this->getModel();
		$user = JFactory::getUser();
		if(!empty($user->id)) {
    		$decodeContent = $getName = $mySubscriperId = $notificationDetails = '';
    	
    		$channelUser = $model->getChannel();
    		if(empty($channelUser[0])) {
    		  $channelUser = $model->insertNewUser();
    		}
    		$channel = $channelUser[0];
    		$this->assignRef('channelContent',$channel);
    		$decodeContent = json_decode($channel->user_content,true);
    		$this->assignRef('userContent',$decodeContent);
    		$getName = $model->getChannelName();
    		$this->assignRef('name',$getName);
    		$mySubscriperId = $model->getMySubscriperId($user->id);
    		$notificationDetails = $model->getNotificationDetails($user->id);
    		$this->assignRef('mysubscriperCount',$mySubscriperId);
    		$this->assignRef('notificationDetails',$notificationDetails);
    		parent::display();
		} else {
		  $currentURL = JUri::getInstance();
		  $loginURL    = JURI::base () . "index.php?option=com_users&amp;view=login&return=" . base64_encode ( $currentURL );
		  $appObj->redirect($loginURL, JText::_('HDVS_LOGIN_FIRST'),MESSAGE);
		}
	}
	
	/**
	 * Function channelMyVideos is used to display channel videos on the front page
	 * This function also used to display searched video on the front page
	 * This function contains videoSearchTitle variable that hold video searched name
	 * If videoSearchTitle has any null value then channelMyVideos function call getChannelVideoDetails and getSearchedVideoDetails methods
	 */
	public function channelMyVideos() {
		$model = $this->getModel();
		$user = JFactory::getUser();
		$channelVideoDetails = $searchedVideoDetails = '';
		$videoSearchTitle = strip_tags((isset($_POST[VIDEO_SEARCH]) && $_POST[VIDEO_SEARCH] !='') ? $_POST[VIDEO_SEARCH] : '');
		if(empty($videoSearchTitle)) {
			$channelVideoDetails = $model->getChannelVideoDetails($user->id);
			$this->assignRef('channelMyVideosDetails',$channelVideoDetails);
		}
		else {
			$searchedVideoDetails = $model->getSearchedVideoDetails($user->id,$videoSearchTitle);
			$this->assignRef('channelMyVideosDetails',$searchedVideoDetails);
		}		
		parent::display();
	}
	
	/**
	 * Function channelDescriptionView is used to display channel description and name in front page
	 * This function calls channelDescriptionModel method to display them
	 */
	public function channelDescriptionView() {
		$cdescription = strip_tags((isset($_POST[CHANNEL_DESCRIPTION]) && $_POST[CHANNEL_DESCRIPTION] !='') ? $_POST[CHANNEL_DESCRIPTION] : '');
		$userName =trim(strip_tags((isset($_POST[USERNAME]) && $_POST[USERNAME] !='') ? $_POST[USERNAME] : ''));
		$model = $this->getModel();
		$user = JFactory::getUser();
		$model->channelDescriptionModel ($user->id,$cdescription,$userName);
	}
	
	/**
	 * Function subscriperDetailsView is used to display subscriber details on the front page
	 */
	public function subscriperDetailsView() { 
		$model = $this->getModel();
		$user = JFactory::getUser();
		$subscriperDetailsModel = $subscriperSearchDetailsModel = '';
		$channelSearchTitle = strip_tags((isset($_POST[VIDEO_SEARCH]) && $_POST[VIDEO_SEARCH] !='') ? $_POST[VIDEO_SEARCH] : '');
		    if(empty($channelSearchTitle)) {
			$subscriperDetailsModel = $model->subscriperDetailsModel($user->id);
			$this->assignRef('subscripeUserDetails',$subscriperDetailsModel);
		}
		else {
            $subscriperSearchDetailsModel = $model->subscriperSearchDetailsModel($user->id,$channelSearchTitle);
			$this->assignRef('subscripeUserDetails',$subscriperSearchDetailsModel);
		}
		parent::display();
	}
	
	/**
	 * Function mySubscriperDetailsView is used to display my subscribtion on the front page
	 * This function call mySubscriperDetailsModel method to get the my subscriber details
	 * This function assign mySubscriperDetails to mySubscripeUserDetails variable
	 */
	public function mySubscriperDetailsView() {
		$model = $this->getModel();
		$user = JFactory::getUser();
		$mySubscriperDetailsModel = '';
		$mySubscriperDetailsModel = $model->mySubscriperDetailsModel($user->id);
		$this->assignRef('mySubscripeUserDetails',$mySubscriperDetailsModel);
		parent::display();
	}
	
	/**
	 * Function closeSubscripeView is used to remove subscriber details on the front page
	 * This function contains msid variable that hold my subscriber id to remove
	 * This function call closeSubscripeModel method to remove the my subscriber details and get present my subscriber details
	 * This function assign mySubscriperDetails to mySubscripeUserDetails variable
	 */
	public function closeSubscripeView() {
		$model = $this->getModel();
		$user = JFactory::getUser();
		$closeSubscripeModel = '';
		$msid = intVal((isset($_POST[MSID]) && $_POST[MSID] !='') ? $_POST[MSID] : '');
		$closeSubscripeModel = $model->closeSubscripeModel($user->id,$msid);
		$this->assignRef('mySubscripeUserDetails',$closeSubscripeModel);
		parent::display();
	}
}