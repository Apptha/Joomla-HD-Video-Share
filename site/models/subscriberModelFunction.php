<?php
/**
 * Subscriber model helperfile
 *
 * This file is used for subscriber model
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Import libraries */
jimport ( 'joomla.application.component.model' );

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/**
 * Function getChannelName is used to retrieve channel user details from user key
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 * loadResult() return just a single value back from your database query.
 * This is often the result of a 'count' query to get a number of records:
 * or where you are just looking for a single field from a single row of the table
 * (or possibly a single field from the first row returned).
 * loadObjectList() returns an indexed array of PHP objects from the table records returned by the query
 *
 * @return string
 */
function getChannelName() {
  global $contusDB, $contusQuery, $loggedUser;
  $userDetails = '';
  $userKey = strip_tags ( JRequest::getVar ( 'ukey' ) );
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) )->where ( $contusDB->quoteName ( USER_KEY ) . ' = ' . $contusDB->quote ( $userKey ) );
    $contusDB->setQuery ( $contusQuery );
    $userDetails = $contusDB->loadObject ();
    $contusQuery->clear ();
  }
  if (! empty ( $userDetails ) && ($userDetails->user_id == $loggedUser->id)) {
    return 'Hi ' . $userDetails->user_name . ', your key is ' . $userDetails->user_key;
  }
}

/**
 * Function bannerImageDetails is used to retrive banner image details for particular user
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
 * @return Ambigous <mixed, NULL>
 */
function bannerImageDetails($uid) {
  global $contusDB, $contusQuery;
  $contusQuery->clear ()->select ( $contusDB->quoteName ( 'user_content' ) )->from ( $contusDB->quoteName ( CHANNELTABLE ) );
  $contusQuery->where ( $contusDB->quoteName ( 'user_id' ) . ' = ' . $contusDB->quote ( $uid ) );
  $contusDB->setQuery ( $contusQuery );
  $userDetails = $contusDB->loadResult ();
  return $userDetails;
}

/**
 * Function getChannel is used to retrieve Channel details for particular user using user id
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 * loadResult() return just a single value back from your database query.
 * This is often the result of a 'count' query to get a number of records:
 * or where you are just looking for a single field from a single row of the table
 * (or possibly a single field from the first row returned).
 * loadObjectList() returns an indexed array of PHP objects from the table records returned by the query
 *
 * @return Ambigous <mixed, NULL, multitype:unknown mixed >
 */
function getChannel() {
  global $contusDB, $contusQuery, $loggedUser;
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    $contusQuery->clear ()->select ( USER_ID )->from ( $contusDB->quoteName ( CHANNELTABLE ) )->where ( $contusDB->quoteName ( USER_KEY ) . ' = ' . $contusDB->quote ( strip_tags ( $_REQUEST ['ukey'] ) ) );
    $contusDB->setQuery ( $contusQuery );
    $subId = $contusDB->loadResult ();
    $_SESSION ['subscriperId'] = $subId;
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) )->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $subId ) );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObjectList ();
  }
}

/**
 * Function updateImageDetails is used to update user image details
 * 
 * @param string $jsonDetails
 *          getQuery() Returns the query part of the URI represented by the JURI object.
 *          If true then the query items are returned as an associative array;
 *          otherwise they are returned as a string.
 *          update() function update channel records
 *          
 * @param int $uid          
 *
 * @return mixed
 */
function updateImageDetails($jsonDetails, $uid) {
  global $contusDB, $contusQuery;
  $fields = array ( $contusDB->quoteName ( 'user_content' ) . ' = ' . $contusDB->quote ( $jsonDetails ) );
  $condition = array ( $contusDB->quoteName ( 'user_id' ) . ' = ' . $contusDB->quote ( $uid ) );
  $contusQuery->clear ()->update ( $contusDB->quoteName ( CHANNELTABLE ) );
  $contusQuery->set ( $fields );
  $contusQuery->where ( $condition );
  $contusDB->setQuery ( $contusQuery );
  return $contusDB->execute ();
}

/**
 * Function getMySubscriperId is used to subscriber id for the currently logged user
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
 * @return Ambigous <mixed, NULL>
 */
function getMySubscriperId($uid) {
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    $contusQuery->clear()->select ( $contusDB->quoteName ( SUB_ID ) )->from ( $contusDB->quoteName ( $tablePrefix . CHANNEL_SUBSCRIBE ) );
    $contusQuery->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $uid ) );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadResult ();
  }
}

/**
 * Function mySubscriperDetailsModel is used to display my subscribed user channel
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
 * @return Ambigous <mixed, NULL, multitype:unknown mixed >
 */
function mySubscriperDetailsModel() {
  global $contusDB, $contusQuery, $loggedUser;
  $uid = (isset ( $_POST [SUBID] ) && $_POST [SUBID] != null) ? intVal ( $_POST [SUBID] ) : '';
  $mySubId = json_decode ( getMySubscriperId ( $uid ), true );
  if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
    $contusQuery->clear ()->select ( '*' )->from ( $contusDB->quoteName ( CHANNELTABLE ) );
    $contusQuery->where ( $contusDB->quoteName ( USER_ID ) . ' IN( ' . implode ( ',', $mySubId ) . ')' );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObjectList ();
  }
}

/**
 * Function closeSubscripeDetailModel is used to update subcriber details
 * using updateSubscriperDetils function
 *
 * @param int $msid
 *          subscriber id to remove
 *          
 * @return Ambigous <Ambigous, mixed, NULL, multitype:unknown mixed >
 */
function closeSubscripeDetailModel($msid) {
  $uid = (isset ( $_POST [SUBID] ) && $_POST [SUBID] != null) ? intVal ( $_POST [SUBID] ) : '';
  $subId = getSubscriberID ( $uid );
  if (! empty ( $subId )) {
    $usersId = json_decode ( $subId, true );
    if (in_array ( $msid, $usersId )) {
      $key = array_search ( $msid, $usersId );
      unset ( $usersId [$key] );
      updateSubscriperDetails ( json_encode ( $usersId ), $uid );
      return mySubscriperDetailsModel ();
    } else {
      echo 'error';
      exitAction ( '' );
    }
  }
}