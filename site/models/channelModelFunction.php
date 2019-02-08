<?php 
/**
 * Channel helper file for HD Video Share
 *
 * This file is used for channel model
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** This file contains all the channel model funcions. */
jimport ( 'joomla.application.component.model' );

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/**
 * Function is used to update subcriber details by calling updateSubscriperDetils function
 *
 * @param string $uid          
 * @param string $msid          
 * 
 * @return mixed
 */
function closeSubscripeModels ( $uid, $msid ) {  
  /** Get subsrciber id based on the user id */
  $subId = getSubscriberID ( $uid );
  
  /** check subscriber id is exists */
  if (! empty ( $subId )) {
    $usersId = json_decode ( $subId, true );
    
    if (in_array ( $msid, $usersId )) {
      $key = array_search ( $msid, $usersId );
      unset ( $usersId [$key] );
      /** Call function to update subsriber dtails */
      updateSubscriperDetails ( json_encode ( $usersId ), $uid );
      return mySubscriperDetailsModels ( $uid );
    } else {
      echo ERROR;
      exitAction ( '' );
    }
  }
}

/**
 * Function is used to insert new subscriber details into database
 *
 * @param int $sId
 * 
 * @return void        
 */
function saveSubscriperIds($sId) {
  global $loggedUser;
  /** Get subscriber id */
  $subId = getSubscriberID ( $loggedUser->id );
  if (! empty ( $subId )) {
    $usersId = json_decode ( $subId, true );
    if (in_array ( $sId, $usersId )) {
      echo ERROR;
      exitAction ( '' );
    } else {
      $usersId [] = $sId;
      /** Update subsriber details if not exist */
      updateSubscriperDetails ( json_encode ( $usersId ), $loggedUser->id );
    }
  } else {
    $usersId [] = $sId;
    /** Insert new subsriber details */
    insertSubscriper ( json_encode ( $usersId ) );
  }
}

/**
 * Function is used to display current users subsriber channel
 *
 * @param int $uid          
 *
 * @return mixed
 */
function mySubscriperDetailsModels($uid) {
  global $contusDB, $contusQuery, $loggedUser;
  /** Ge subsriber id's as json data */
  $mySubId = json_decode ( getMysubscriperId ( $uid ), true );
  if (! empty ( $mySubId )) {
    if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
      $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) );
      $contusQuery->where ( $contusDB->quoteName ( USER_ID ) . ' IN( ' . implode ( ',', $mySubId ) . ')' );
      $contusDB->setQuery ( $contusQuery );
      return $contusDB->loadObjectList ();
    } else {
      echo json_encode ( array (
          ERRORMSG => 'true',
          ERRMSG => '404' 
      ) );
      exitAction ( '' );
    }
  } else {
    return '';
  }
}

/**
 * Function is used to update or delete notification details
 *
 * @param int $delId          
 *
 * @return void
 */
function updateNotificationModels($delId) {
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
  
  /** Get subscriber id for current user */ 
  $contusQuery->clear ()->select ( SUB_ID )->from ( $contusDB->quoteName ( $tablePrefix . CHANNEL_NOTIFICATION ) );
  $contusQuery->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $loggedUser->id ) );
  $contusDB->setQuery ( $contusQuery );
  $subscriperId = json_decode ( $contusDB->loadResult (), true );

  if (in_array ( $delId, $subscriperId )) {
    $delkey = array_search ( $delId, $subscriperId );
    unset ( $subscriperId [$delkey] );
    if (! empty ( $subscriperId )) {
      $updatedId = json_encode ( $subscriperId );
      $fields = array ( $contusDB->quoteName ( SUB_ID ) . ' = ' . $contusDB->quote ( $updatedId ) );
      $condition = array ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $loggedUser->id ) );
      
      /** Update notification detials */ 
      $contusQuery->clear()->update ( $contusDB->quoteName ( $tablePrefix . CHANNEL_NOTIFICATION ) )->set ( $fields )->where ( $condition );
      $contusDB->setQuery ( $contusQuery );
      $contusDB->execute ();
    } else {
      /** Call function to delete notification detials */
      deleteNotificationModels ();
    }
  } else {
    echo ERROR;
    exitAction ( '' );
  }
}

/**
 * Function is used to delete all notification details for current loggedin user
 *
 * @return void
 */
function deleteNotificationModels() {
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
  $conditions = array ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $loggedUser->id ) );
  /** Delete all notification details from db */
  $contusQuery->clear()->delete ( $contusDB->quoteName ( $tablePrefix . CHANNEL_NOTIFICATION ) )->where ( $conditions );
  $contusDB->setQuery ( $contusQuery );
  $contusDB->execute ();
  exitAction ( '' );
}

/**
 * Function is used to add new user details when the user is logged in
 *
 * @return string
 */
function addnewusers() {
  global $contusDB, $contusQuery, $loggedUser;
  $userKey = strip_tags ( JRequest::getVar ( 'ukey' ) );
  $userKey = md5 ( $loggedUser->name . $loggedUser->id );
  $user_content = json_encode ( array ( 'profileImage' => '', 'coverImage' => '', DESCRIPTION => '' ) );
  $channelName = $loggedUser->name; 
  
  /** Set values to add new user details */
  $columns     = array ( USER_ID, USER_NAME, 'user_key', USER_CONTENT, 'channel_name' );
  $values      = array ( $loggedUser->id, $contusDB->quote ( $loggedUser->name ), $contusDB->quote ( $userKey ), $contusDB->quote ( $user_content ), $contusDB->quote ( $channelName ) );
  
  /** Insert new user details into db */
  $contusQuery->clear()->insert ( $contusDB->quoteName ( CHANNELTABLE ) )->columns ( $contusDB->quoteName ( $columns ) );
  $contusQuery->values ( implode ( ',', $values ) );
  $contusDB->setQuery ( $contusQuery );
  $insertResult = $contusDB->execute ();

  /** Check isert action is done */
  if ($insertResult) {
    return $userKey;
  }
}

/**
 * Function is used to get current users channel details
 *
 * @return mixed
 */
function getUserChannel() {
  global $contusDB, $contusQuery, $loggedUser;

  /** Check user id is exist or it is a guest user */
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    /** Fetch current user channel details */
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) );
    $contusQuery->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $loggedUser->id ) );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObjectList ();
  } else {
    JError::raiseError ( 404, JText::_ ( PAGE_NOT_FOUND ) );
  }
}

/**
 * Function is used to send notification email to the subscribed user when the current user subscribe
 *
 * @param int $sid          
 *
 * @return void
 */
function sendNotificationMail ( $sid ) {
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    /** Get email from user table */
    $contusQuery->clear ()->select ( 'email' )->from ( $contusDB->quoteName ( $tablePrefix . 'users' ) );
    $contusQuery->where ( $contusDB->quoteName ( 'id' ) . ' = ' . $contusDB->quote ( $sid ) );
    $contusDB->setQuery ( $contusQuery );
    $to = $contusDB->loadResult ();
    
    /** Get user channel details */
    $userDetails = getUserChannel ();
    /** Get user name and description */
    $userName = $userDetails [0]->user_name;
    $userContent = json_decode ( $userDetails [0]->user_content, true );
    $userDescription = $userContent[DESCRIPTION];
    /** Get profile iamge */
    $decodedProfileImage = $userContent['profileImage'];
    if (! empty ( $decodedProfileImage )) {
      $userProfileImage = JURI::base () . "images/channel/banner/profile/" . $decodedProfileImage;
    } else {
      $userProfileImage = JURI::base () . "components/com_contushdvideoshare/images/channel/user.png";
    }
    /** Get config object and get from mail address */
    $config = JFactory::getConfig ();
    $from = $config->get ( 'mailfrom' );
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "Return-path: " . $from;
    $subject = 'Subscribed user';
    /** Set mail content */
    $content = '<div class="mailContainer">
<div class="mailRow" style="background:ghostwhite;padding:5px;height:160px;">
<img src="' . $userProfileImage . '" class="eimg" style="float:left;">
<p class="ep" style="float:left;margin-left:5px;font-family:sans-serif;">
<span style="font-size:15px;font-weight:bold;">' . $userName . '</span><br><br> ' . $userDescription . ' </p>
</div></div>';
    mail ( $to, $subject, $content, $headers );
  } else {
    echo json_encode ( array ( ERRORMSG => 'true', ERRMSG => '404' ) );
    exitAction ( '' );
  }
}

/**
 * Function is used to get all notification details for the current user
 *
 * @param int $uid          
 *
 * @return mixed
 */
function getNotificationDetail($uid) {
  global $contusDB, $contusQuery;
  $notificationId = $myNotificationDetails = '';
  /** Get user notificaton detials */
  $myNotificationDetails = getMyNotificationId ( $uid );
  if (! empty ( $myNotificationDetails )) {
    $notificationId = json_decode ( $myNotificationDetails->sub_id, true );
  }
  /** Get all notification details */
  if (! empty ( $notificationId )) {
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) )->where ( $contusDB->quoteName ( USER_ID ) . ' IN( ' . implode ( ',', $notificationId ) . ')' );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObjectList ();
  }
}

/**
 * Function is used to insert and update new notification details
 *
 * @param int $sid          
 *
 * @return void
 */
function saveNotifications($sid) {
  global $loggedUser;
  $notificationDetail = getMyNotificationId ( $sid );
  $sub_id = '';
  if (! empty ( $notificationDetail )) {
    $sub_id = json_decode ( $notificationDetail->sub_id, true );
  }
  if (! empty ( $sub_id )) {
    if (in_array ( $loggedUser->id, $sub_id )) {
      return;
    } else {
      $sub_id [] = $loggedUser->id;
      /** Call function to update notification detials */ 
      updateNotificationId ( $sid, json_encode ( $sub_id ) );
    }
  } else {
    /** Call function to insert notification details */
    insertNotificationId ( $sid, json_encode ( array ( $loggedUser->id ) ) );
  }
}

/**
 * Function is used to retrive subscriber id for current logged in user
 *
 * @param int $uid          
 *
 * @return mixed
 */
function getMysubscriperId($uid) {
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    /** Get subscrber id for current user */
    $contusQuery->clear ()->select ( $contusDB->quoteName ( SUB_ID ) )->from ( $contusDB->quoteName ( $tablePrefix . CHANNEL_SUBSCRIBE ) )->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $uid ) );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadResult ();
  } else {
    JError::raiseError ( 404, JText::_ ( PAGE_NOT_FOUND ) );
    exitAction ( '' );
  }
}

/**
 * Function is used to get subsriber details based on the channel title
 *
 * @param int $userId          
 * @param string $searchTitle          
 *
 * @return mixed
 */
function subscriperSearchDetailsModels($userId, $searchTitle) {
  global $contusDB, $contusQuery, $loggedUser;
  $usersId = getUserSubscriberIDs ( $userId );
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    /** Get searched subscriber detaiuls */
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) );
    $contusQuery->where ( $contusDB->quoteName ( USER_ID ) . ' NOT IN( ' . implode ( ',', $usersId ) . ' )' . ' AND ' . $contusDB->quoteName ( USER_NAME ) . ' LIKE ' . $contusDB->quote ( '%' . $searchTitle . '%' ) );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObjectList ();
  } else {
    echo json_encode ( array ( ERRORMSG => 'true', ERRMSG => '404' ) );
    exitAction ( '' );
  }
}

/**
 * Function is used to get channel details using user key
 *
 * @return string
 */
function getChannelNameByKey() {
  global $contusDB, $contusQuery, $loggedUser;
  $userKey = $userDetails = '';
  $userKey = strip_tags ( JRequest::getVar ( 'ukey' ) );
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    /** Get channel details */
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) );
    $contusQuery->where ( $contusDB->quoteName ( 'user_key' ) . ' = ' . $contusDB->quote ( $userKey ) );
    $contusDB->setQuery ( $contusQuery );
    $userDetails = $contusDB->loadObject ();
    $contusQuery->clear ();
  }
  if (! empty ( $userDetails ) && ($userDetails->user_id == $loggedUser->id)) {
    return 'Hi ' . $userDetails->user_name . ', your key is ' . $userDetails->user_key;
  }
}

/**
 * Function updateNotificationId is user to update notification details for the currently logged user
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 *
 * @param int $sid
 *          subscriber id
 * @param string $json
 *          subscriber id in json format
 *          
 * @return mixed
 */
function updateNotificationId($sid, $json) {
  global $tablePrefix, $contusDB, $contusQuery;
  $contusQuery->clear ();
  $fields = array ( $contusDB->quoteName ( SUB_ID ) . ' = ' . $contusDB->quote ( $json ) );
  $condition = array ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $sid ) );
  /** Update channel notification details */
  $contusQuery->update ( $contusDB->quoteName ( $tablePrefix . CHANNEL_NOTIFICATION ) )->set ( $fields )->where ( $condition );
  $contusDB->setQuery ( $contusQuery );
  return $contusDB->execute ();
}

/**
 * Function insertNotificationId is used to insert notification id to the currently logged in user id
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 *
 * @param int $sid
 *          subscriber id
 * @param string $json
 *          notification id in json format
 *          
 * @return void
 */
function insertNotificationId($sid, $json) {
  global $tablePrefix, $contusDB, $contusQuery;
  $contusQuery->clear ();
  $columns = array ( USER_ID, SUB_ID );
  $values = array ( $sid, $contusDB->quote ( $json ) );
  /** Insert channel nottification details into db */
  $contusQuery->insert ( $contusDB->quoteName ( $tablePrefix . CHANNEL_NOTIFICATION ) )->columns ( $contusDB->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
  $contusDB->setQuery ( $contusQuery );
  $contusDB->execute ();
  return $contusQuery->clear ();
}

/**
 * Function insertSubscriper is used to insert subscriber id to the user channel details who subscribed that channel
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 * This function uses updata function to update subscriber id to required channel user details
 *
 * @param string $jsonDetail
 *          subscriber id in json format
 *          
 * @return boolean
 */
function insertSubscriper($jsonDetail) {
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    $contusQuery->clear ();
    $columns = array ( USER_ID, SUB_ID );
    $values = array ( $loggedUser->id, $contusDB->quote ( $jsonDetail ) );
    
    /** Insert subscriber detials */
    $contusQuery->insert ( $contusDB->quoteName ( $tablePrefix . CHANNEL_SUBSCRIBE ) )->columns ( $contusDB->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
    $contusDB->setQuery ( $contusQuery );
    $insertResult = $contusDB->execute ();
    $contusQuery->clear ();
    if ($insertResult) {
      $returnVal =  true;
    } else {
      $returnVal = false;
    }
    return $returnVal;
  } else {
    echo json_encode ( array ( ERRORMSG => 'true', ERRMSG => '404' ) );
    exitAction ( '' );
  }
}

/**
 * Function getMyNotificationId is used to retrieve user id who subscibed our channel
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 * loadResult() return just a single value back from your database query.
 * This is often the result of a 'count' query to get a number of records:
 * or where you are just looking for a single field from a single row of the table
 * (or possibly a single field from the first row returned).
 * loadObjectList() returns an indexed array of PHP objects from the table records returned by the query
 *
 * @param int $uid
 *          user id
 *          
 * @return mixed
 */
function getMyNotificationId ( $uid ) {
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    /** Get current user notification details */ 
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( $tablePrefix . CHANNEL_NOTIFICATION ) )->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $uid ) );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObject ();
  } else {
    JError::raiseError ( 404, JText::_ ( PAGE_NOT_FOUND ) );
    exitAction ( '' );
  }
}