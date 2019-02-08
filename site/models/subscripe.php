<?php
/**
 * Subscribe model file for HD Video Share
 *
 * This file is used to insert or update subscribe details
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

/** Include subscriber helper files */
include_once(JPATH_ROOT.'/components/com_contushdvideoshare/models/subscriberModelFunction.php');


/**
 * Subscribe model class.
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Modelcontushdvideosharesubscripe extends ContushdvideoshareModel {
	/**
	 * Function getSubscriberChannelName is used to retrieve channel user details from user key
	 * 
	 * @return string
	 */
    public function getSubscriberChannelName() {
    	return getChannelName();
    }
    
    /**
     * Function getSubscriberChannel is used to retrieve Channel details for particular user using user id
     * 
     * @return array 
     */
    public function getSubscriberChannel() {
    	return getChannel();
    }
   
    /**
     * Function getChannelVideoDetails is used to retrieve channel video details for particular user
     * 
     * @param int uid
     * 
     * @return array
     */
    public function getSubscriberChannelVideoDetails( $uid ) {
    	return getChannelVideoDetail( $uid );
    }
    
    /**
     * Function getSearchedVideoDetails is used to retrieve user searched video details
     * 
     * @param  string  $videoTitle  video title
     * 
     * @return array
     */
    public function getSubscriberSearchedVideoDetails($uid, $videoTitle) {
    	return getSearchedVideoDetail($uid, $videoTitle);
    }

    /**
     * Function channelDescriptionModel is used to insert channel description and channel name
     * This function also used to update channel description and channel name
     * This function first check whether channel name to insert or update is already exists or not
     * 
     * @param int $uid user id
     * @param string $chDescription channel description
     * @param string $userName channel name
     * 
     * @return mixed
     */
    public function subscriberDescriptionModel($chDescription,$userName) {
        $uid = (isset($_POST[SUBID]) && $_POST[SUBID] !=null) ? intVal($_POST[SUBID]) : '';
    	return channelDescriptionModels ( $uid, $chDescription, $userName);
    }
    /**
     * Function subscriberDetailsModel is used to retrieve full subsciber details
     * getQuery() Returns the query part of the URI represented by the JURI object.
     * If true then the query items are returned as an associative array;
     * otherwise they are returned as a string.
     * loadResult() return just a single value back from your database query.
     * This is often the result of a 'count' query to get a number of records:
     * or where you are just looking for a single field from a single row of the table
     * (or possibly a single field from the first row returned).
     * loadObjectList() returns an indexed array of PHP objects from the table records returned by the query
     * 
     * @param int $userId user id
     * 
     * @return Ambigous <mixed, NULL, multitype:unknown mixed >
     */
    public function getSubscriperDetailsModel($userId) {
    	return subscriberDetailsModel($userId);
    }

    /**
     * Function getMySubscriperId  is used to subscriber id for the currently logged user
     * 
     * @param int $uid user id
     * 
     * @return int
     */
    public function getMySubscriperChannelId($uid) {
    	return getMySubscriperId($uid);
    }
    
    /**
     * Function mySubscriperDetailsModel is used to display my subscribed user channel
     * 
     * @return array
     */
    public function getMySubscriperDetailsModel() {
    	return mySubscriperDetailsModel();
    }
    
    /**
     * Function closeSubscripeDetailModel is used to update subcriber details by calling updateSubscriperDetils function
     * @param int $msid subscriber id to remove
     * 
     * @return Ambigous <Ambigous, mixed, NULL, multitype:unknown mixed >
     */
    public function closeSubscripeChannelDetailModel($msid) {
    	return closeSubscripeDetailModel($msid);
    }
}