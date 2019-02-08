<?php
/**
 * Channel model for HD Video Share
 *
 * This file is used to insert, update channel details
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

/** Include channel helper file */
include_once(JPATH_ROOT.'/components/com_contushdvideoshare/models/channelModelFunction.php');

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/**
 * Channel model class.
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Modelcontushdvideosharechannel extends ContushdvideoshareModel {
	/**
	 * Function getChannelName is used to retrieve channel user details from user key
	 * 
	 * @return string
	 */
    public function getChannelName() {
    	return getChannelNameByKey();
    }
    
    /**
     * Function bannerImageDetails is used to retrive banner image details for particular user
     * 
     * @param int $uid user id
     * 
     * @return array
     */
    public function bannerImageDetails($uid) {
    	return bannerImageDetail($uid);
    }

    /**
     * Function getChannel is used to retrieve Channel details for particular user using user id
     * 
     * @return array
     */
    public function insertNewUser() {
      global $contusDB, $contusQuery, $loggedUser;
      $userKey = md5 ( $loggedUser->name . $loggedUser->id );
      $user_content = json_encode ( array ( 'profileImage' => '', 'coverImage' => '', DESCRIPTION => '' ) );
      $channelName = $loggedUser->name;
      
      /** Set values to add new user details */
      $columns     = array ( USER_ID, USER_NAME, 'user_key', USER_CONTENT, 'channel_name' );
      $values      = array ( $loggedUser->id, $contusDB->quote ( $channelName ), $contusDB->quote ( $userKey ), $contusDB->quote ( $user_content ), $contusDB->quote ( $channelName ) );
      
      /** Insert new user details into db */
      $contusQuery->clear()->insert ( $contusDB->quoteName ( CHANNELTABLE ) )->columns ( $contusDB->quoteName ( $columns ) );
      $contusQuery->values ( implode ( ',', $values ) );
      $contusDB->setQuery ( $contusQuery );
      $insertResult = $contusDB->execute ();
      $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) );
      $contusQuery->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $loggedUser->id ) );
      $contusDB->setQuery ( $contusQuery );
      return $contusDB->loadObjectList ();
    }
    /**
     * Function getChannel is used to retrieve Channel details for particular user using user id
     * 
     * @return array
     */
    public function getChannel() {
    	return getUserChannel();
    }
    
    /**
     * Function updateImageDetails is used to update user image details
     * 
     * @param string $jsonDetails
     * @param int $uid
     * 
     * @return array
     */
    public function updateImageDetails($jsonDetails,$uid) {
    	return updateImageDetail($jsonDetails,$uid);
    }
    
    /**
     * Function addnewuser is used to add new user details to channel table when new user logged in
     *  
     * @return string
     */
    public function addnewuser() {
    	return addnewusers();
    }
    
    /**
     * Function getChannelVideoDetails is used to retrieve channel video details for particular user
     * 
     * @param int $uid user id
     * 
     * @return array
     */
    public function getChannelVideoDetails($uid) {
    	return getChannelVideoDetail($uid);
    }
    
    /**
     * Function getSearchedVideoDetails is used to retrieve user searched video details
     * 
     * @param int $uid user id
     * @param string $videoTitle video title
     * 
     * @return array
     */
    public function getSearchedVideoDetails($uid,$videoTitle) {
    	return getSearchedVideoDetail($uid,$videoTitle);
    }

    /**
     * Function channelDescriptionModel is used to insert channel description and channel name
     * 
     * @param int $uid user id
     * @param string $chDescription channel description
     * @param string $userName channel name
     * 
     * @return array
     */
    public function channelDescriptionModel($uid,$chDescription,$userName) {
    	return channelDescriptionModels ( $uid, $chDescription, $userName );
    }
    
    /**
     * Function subscriberDetailsModel is used to retrieve full subsciber details
     * 
     * @param int user id
     * 
     * @return array
     */
    public function subscriperDetailsModel($userId) {
    	return subscriperDetailsModels($userId);
    }
    
    /**
     * Function subscriperSearchDetailsModel is used to retrieve searched subscriber details using channel title
     * 
     * @param  int     $userId       user id
     * @param  string  $searchTitle  channel name
     * 
     * @return array
     */
    public function subscriperSearchDetailsModel($userId,$searchTitle) {
    	return subscriperSearchDetailsModels($userId,$searchTitle);
    }
    
    /**
     * Function getMySubscriperId  is used to get subscriber id for the currently logged user
     * 
     * @param int $uid user id
     * 
     * @return int
     */
    public function getMySubscriperId($uid) {
    	return getMysubscriperId($uid);
    }
    
    /**
     * Function mySubscriperDetailsModel is used to display my subscribed user channel
     * 
     * @param int $uid user id
     * 
     * @return array
     */
    public function mySubscriperDetailsModel($uid) {
    	return mySubscriperDetailsModels($uid);
    }
    
    /**
     * Function closeSubscripeModel is used to update subcriber details by calling updateSubscriperDetils function
     * 
     * @param   int  $uid   user id
     * @param   int  $msid  subscriber id to remove
     * 
     * @return  int
     */
    public function closeSubscripeModel($uid,$msid) {
    	return closeSubscripeModels($uid,$msid);
    }
    
    /**
     * Funciton getNotificationDetails is retrieve all notification details for the currently logged in user
     * 
     * @param int $uid user id
     * 
     * @return array
     */
    public function getNotificationDetails($uid) {
        return getNotificationDetail($uid);
    }
    
}