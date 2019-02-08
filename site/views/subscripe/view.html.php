<?php
/**
 * Subscribe view file
 * 
 * This file is for Subscribe view
 * 
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Subscribe view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewsubscripe extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   * The display method of the JView class is called with the display task of the JController class.
   * In our case, this method will display data using the tmpl/default.php file.
   * With your favorite file manager and editor,
   * create a file site/views/helloworld/tmpl/default.php able to display the default view
   * 
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewadsxml object to support chaining.
   * @since 1.5
   */
  public function display($tpl = NULL) {
    global $document;
    $model = $this->getModel ();
    $decodeContent = $getName = $getMySubscriperId = '';
    
    $channelUser = $model->getSubscriberChannel ();
    $channel = $channelUser [0];
    $document->addScriptDeclaration ( 'var subscriberId=' . $channel->user_id . ';' );
    $this->assignRef ( 'subscriberId', $channel->user_id );
    $this->assignRef ( 'channelContent', $channel );
    $decodeContent = json_decode ( $channel->user_content, true );
    $this->assignRef ( 'userContent', $decodeContent );
    $getName = $model->getSubscriberChannelName ();
    $this->assignRef ( 'name', $getName );
    $getMySubscriperId = $model->getMySubscriperChannelId ( $channel->user_id );
    $this->assignRef ( 'mysubscriperCount', $getMySubscriperId );
    parent::display ();
  }
    
  /**
   * Function channelMyVideos is used to display channel videos on the front page
   * This function also used to display searched video on the front page
   * This fucntion contains videoSearchTitle variable that hold video searched name
   * If videoSearchTitle has any null value then channelMyVideos fucntion call getChannelVideoDetails method
   * If videoSearchTitle has any value value then channelMyVideos fucntion call getSearchedVideoDetails method
   * 
   * @return void
   */
  public function channelMyVideos() {
    $model = $this->getModel ();
    $channelVideoDetails = $searchedVideoDetails = '';
    $uid = (isset ( $_POST [SUBID] ) && $_POST [SUBID] != null) ? intVal ( $_POST [SUBID] ) : '';
    $videoSearchTitle = strip_tags ( (isset ( $_POST [VIDEO_SEARCH] ) && $_POST [VIDEO_SEARCH] != '') ? $_POST [VIDEO_SEARCH] : '' );
    if (empty ( $videoSearchTitle )) {
      $channelVideoDetails = $model->getSubscriberChannelVideoDetails ( $uid );
      $this->assignRef ( 'channelMyVideosDetails', $channelVideoDetails );
    } else {
      $searchedVideoDetails = $model->getSubscriberSearchedVideoDetails ( $uid, $videoSearchTitle );
      $this->assignRef ( 'channelMyVideosDetails', $searchedVideoDetails );
    }
    parent::display ();
  }
  
  /**
   * Function subscriperDetailsView is used to display subscriber details on the front page
   * This function also used to display searched subscriber details on the front page
   * This fucntion contains channelSearchTitle variable that hold searched subscriber name
   * If channelSearchTitle has any null value then channelMyVideos fucntion call subscriperDetailsModel method
   * If channelSearchTitle has any value value then channelMyVideos fucntion call subscriperSearchDetailsModel method
   * 
   * @return void
   */
  public function subscriperDetailsView() {
    $model = $this->getModel ();
    $user = JFactory::getUser ();
    $subscriperDetailsModel = $subscriperSearchDetailsModel = '';
    $channelSearchTitle = strip_tags ( (isset ( $_POST [VIDEO_SEARCH] ) && $_POST [VIDEO_SEARCH] != '') ? $_POST [VIDEO_SEARCH] : '' );
    if (empty ( $channelSearchTitle )) {
      $subscriperDetailsModel = $model->getSubscriperDetailsModel ( $user->id );
      $this->assignRef ( 'subscripeUserDetails', $subscriperDetailsModel );
    } else {
      $subscriperSearchDetailsModel = $model->subscriperSearchDetailsModel ( $user->id, $channelSearchTitle );
      $this->assignRef ( 'subscripeUserDetails', $subscriperSearchDetailsModel );
    }
    parent::display ();
  }
  
  /**
   * Function channelDescriptionView is used to display channel description on the front page
   * This function also used to display channel name on the front page
   * This fucntion contains cdescription variable that hold channel description
   * This fucntion also contains userName variable that hold channel name
   * This fucntion call channelDescriptionModel method
   * 
   * @return void
   */
  public function channelDescriptionView() {
    $model = $this->getModel ();
    $cdescription = strip_tags ( (isset ( $_POST [CHANNEL_DESCRIPTION] ) && $_POST [CHANNEL_DESCRIPTION] != '') ? $_POST [CHANNEL_DESCRIPTION] : '' );
    $userName = trim ( strip_tags ( (isset ( $_POST [USERNAME] ) && $_POST [USERNAME] != '') ? $_POST [USERNAME] : '' ) );
    $model->subscriberDescriptionModel ( $cdescription, $userName );
  }
  
  /**
   * Function mySubscriperDetailsView is used to display my subscribtion on the front page
   * This fucntion call mySubscriperDetailsModel method to get the my subscriber details
   * This function assign mySubscriperDetails to mySubscripeUserDetails variable
   * 
   * @return void
   */
  public function getMySubscriperDetailsView() {
    $model = $this->getModel ();
    $mySubscriperDetailsModel = '';
    $mySubscriperDetailsModel = $model->getMySubscriperDetailsModel ();
    $this->assignRef ( 'mySubscripeUserDetails', $mySubscriperDetailsModel );
    parent::display ();
  }
  
  /**
   * Function closeSubscripeView is used to remove subscriber details on the front page
   * This fucntion contains msid variable that hold my subscriber id to remove
   * This fucntion call closeSubscripeModel method to remove the my subscriber details and get present my subscriber details
   * This function assign mySubscriperDetails to mySubscripeUserDetails variable
   * 
   * @return void
   */
  public function closeSubscripeDetailView() {
    $model = $this->getModel ();
    $closeSubscripeDetailModel = '';
    $msid = intVal ( (isset ( $_POST [MSID] ) && $_POST [MSID] != '') ? $_POST [MSID] : '' );
    $closeSubscripeDetailModel = $model->closeSubscripeChannelDetailModel ( $msid );
    $this->assignRef ( 'mySubscripeUserDetails', $closeSubscripeDetailModel );
    parent::display ();
  }
}