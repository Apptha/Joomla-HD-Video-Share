<?php
/**
 * Channel helper file for HD Video Share
 *
 * This file is used for channel
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

include_once(JPATH_ROOT.'/components/com_contushdvideoshare/models/channelModelFunction.php');

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/**
 * Adsxml model class.
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class ModelcontushdvideosharechannelHelper extends ContushdvideoshareModel {
    /**
     * Function saveNotification is used to call insertNotificationId function to insert new notification details
     * This is also used to call updateNotificationId to update notification details
     * 
     * @param int $sid subscriber id
     * 
     * @return void
     * 
     */
    public function saveNotification($sid) {
    	return saveNotifications($sid);
    }
    
    /**
     * Function deleteNotificationModel is used to delete all notification details for currently logged in user
     * This function uses delete function to delete all notification details
     * 
     * @return  int
     */
    public function deleteNotificationModel() {
    	return deleteNotificationModels();
    }

    /**
     * Function subscriberDetailsModel is used to retrieve full subsciber details
     * 
     * @param int $userId user id
     * 
     * @return array
     */
    public function subscriperDetailsModel($userId) {
    	return subscriperDetailsModels($userId);
    }

    /**
     * Function saveSubscriberId is used to call insertSubscriper function to insert new subsciber details
     * This is also used to call updateSubscriperDetails to update subscriber details
     * 
     * @param int $sId subscriber to insert or update
     * 
     * @return  void
     */
    public function saveSubscriperId($sId) {
    	return saveSubscriperIds($sId);
    }

    /**
     * Function updateNofiticatoinModel is used to update or delete notification details
     * 
     * @param  int  $delId  notification id to delete
     * 
     * @return  int
     */
    public function updateNotificationModel($delId) {
    	return updateNotificationModels($delId);
    }

    /**
     * Function notificationMail is used to notification email to subscribed user when user click 
     * subscribe button on front page
     * 
     * @param  int  $sid
     * 
     * @return boolean
     */
    public function notificationMail($sid) {
    	return sendNotificationMail($sid);
    }
}