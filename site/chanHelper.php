<?php 
/**
 * Channel Helper file for Contus HD Video Share
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

/** Include common helper file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'commonhelper.php');

/**
 * Function to get user IDs of users who have subscribed to a channel of a user.
 *
 * @param integer $userId The user id whose subscriber user IDs are to be fetched.
 *
 * @return array The user IDs
 */
function getUserSubscriberIDs ( $userId )  {
 /** Get subscriber id for the user id */
 $subId = getSubscriberID ( $userId );

 /** Check subscriber id is exist */
 if (! empty ( $subId )) {
  /** Decode the subscriber id's json data */
  $usersId    = json_decode ( $subId, true );
  $usersId [] = $userId;
 } else {
  /** Set user id to an array */
  $usersId [] = $userId;
 }
 return $usersId;
}

/**
 * Function is used to get search video details
 *
 * @param int $uid
 * @param string $videoTitle
 *
 * @return mixed
 */
function getSearchedVideoDetail($uid, $videoTitle) {
 global $contusDB, $contusQuery, $loggedUser;
 if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
  /** Get searched video details */
  $contusQuery->clear ()->select ( array('a.*','b.category', 'b.seo_category', 'd.username', 'e.catid', 'e.vid', 'wl.video_id', 'wh.VideoId' ))->from ( '#__hdflv_upload AS a' )
  ->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$uid )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$uid )
  ->where ( $contusDB->quoteName ( VIDEOPUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )->where ( $contusDB->quoteName ( CATPUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )->where ( $contusDB->quoteName ( 'a.type' ) . ' = ' . $contusDB->quote ( '0' ) )->where ( $contusDB->quoteName ( USERBLOCK ) . ' = ' . $contusDB->quote ( '0' ) )
  ->where ( $contusDB->quoteName ( 'memberid' ) . ' = ' . $contusDB->quote ( $uid ) . ' AND ' . $contusDB->quoteName ( 'title' ) . ' LIKE ' . $contusDB->quote ( '%' . $videoTitle . '%' ) )->group ( $contusDB->escape ( 'e.vid' ) )->order ( $contusDB->escape ( 'a.id' . ' ' . 'DESC' ) );
  $contusDB->setQuery ( $contusQuery );
  return $contusDB->loadObjectList ();
 } else {
  echo json_encode ( array ( ERRORMSG => 'true', ERRMSG => '404' ) );
  exitAction ( '' );
 }
}


/**
 * Function updateUserName is used to update channel user name using user id
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 * update() function update channel records
 *
 * @param string $userName user name to update
 *
 * @param int $uid user id
 */
function updateUserName ( $userName, $uid ) {
 global $contusDB, $contusQuery;

 /** Set username and user id values into an array */
 $fields     = array ( $contusDB->quoteName ( USER_NAME ) . ' = ' . $contusDB->quote ( $userName ) );
 $condition  = array ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $uid ) );

 /** Update channel username to the given user id */
 $contusQuery->clear()->update ( $contusDB->quoteName ( CHANNELTABLE ) )->set ( $fields )->where ( $condition );
 $contusDB->setQuery ( $contusQuery );
 $contusDB->execute ();
}

/**
 * Function checkUserName is used to check whether user name is already exists in database
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 * loadResult() return just a single value back from your database query.
 * This is often the result of a 'count' query to get a number of records:
 * or where you are just looking for a single field from a single row of the table
 * (or possibly a single field from the first row returned).
 * loadObjectList() returns an indexed array of PHP objects from the table records returned by the query
 * @param string $userName user name
 *
 * @param int $uid user id
 *
 * @return int
 */
function checkUserName ( $userName, $uid ) {
 global $contusDB, $contusQuery;

 /** Check the user name is exist for the given user id */
 $contusQuery->clear ()->select ( $contusDB->quoteName ( 'id' ) )->from ( $contusDB->quoteName ( CHANNELTABLE ) )
 ->where ( $contusDB->quoteName ( USER_NAME ) . ' = ' . $contusDB->quote ( $userName ) . ' AND ' . $contusDB->quoteName ( USER_ID ) . ' != ' . $contusDB->quote ( $uid ) );
 $contusDB->setQuery ( $contusQuery );
 return $contusDB->loadResult ();
}

/**
 * Function is used to get banner image details
 *
 * @param  int $uid
 * @return
 */
function bannerImageDetail ( $uid ) {
 global $contusDB, $contusQuery;

 /** Get banner image details for the given user id */
 $contusQuery->clear()->select ( $contusDB->quoteName ( USER_CONTENT ) ) ->from ( $contusDB->quoteName ( CHANNELTABLE ) )
 ->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $uid ) );
 $contusDB->setQuery ( $contusQuery );
 return $contusDB->loadResult ();
}

/**
 * Fucntion is used to update banner image details
 *
 * @param object $jsonDetails
 * @param int    $uid
 *
 * @return mixed
 */
function updateImageDetail ( $jsonDetails, $uid ) {
 global $contusDB,$contusQuery;
 $contusQuery->clear();
 $fields     = array($contusDB->quoteName(USER_CONTENT). ' = '. $contusDB->quote($jsonDetails));
 $condition  = array($contusDB->quoteName(USER_ID) .' = '.$contusDB->quote($uid));

 /** Update banner image details */
 $contusQuery->update($contusDB->quoteName( CHANNELTABLE )) ->set($fields) ->where($condition);
 $contusDB->setQuery($contusQuery);
 return $contusDB->execute();
}

/**
 * Fucntion is used to update image details for the given user
 *
 * @param unknown $uid
 * @param unknown $chDescription
 * @param unknown $userName
 */
function channelDescriptionModels ( $uid, $chDescription, $userName ) {

 /** Get banner image details as json data */
 $decodedDescription               = json_decode( bannerImageDetail( $uid ),true);
 $decodedDescription[DESCRIPTION]  = $chDescription;
 /** Encode the json data */
 $encodeDescription                = json_encode($decodedDescription);

 /** Check user name is exists */
 if(!empty( $userName )) {
  /** Check user name is exists for the given user id */
  $nameChecking = checkUserName( $userName, $uid );
  /** If user name is empty update user name */
  if(empty($nameChecking)) {
   updateUserName( $userName, $uid );
  } else {
   /** Otherwise display error message */
   echo json_encode(array(ERRORMSG=>'true', ERRMSG=>'User Name Already Exist'));
   exitAction ( '' );
  }
 } else {
  /** Display error message user name is required */
  echo json_encode(array(ERRORMSG=>'true', ERRMSG=>'User Name Should Not Be Empty'));
  exitAction ('' );
 }

 /** Update image details for the given user id */
 $saveDescription = updateImageDetail( $encodeDescription, $uid );

 /** Check iamges are updated or not */
 if($saveDescription) {
  echo json_encode(array(ERRORMSG=>'false',ERRMSG=>'Content Saved'));
 }
 exitAction ( '' );
}

/**
 * Function updateSubscriperDetails is used to update subscriber id to the user channel details who subscribed that channel
 * getQuery() Returns the query part of the URI represented by the JURI object.
 * If true then the query items are returned as an associative array;
 * otherwise they are returned as a string.
 * This function uses updata function to update subscriber id to required channel user details
 * @param string $jsonDetail subscriber id in json format
 *
 * @param int $uid user id who subscribed that channel
 *
 * @return mixed
 */
function updateSubscriperDetails ( $jsonDetail, $uid ) {
 global $tablePrefix, $contusDB, $contusQuery, $loggedUser;
 if($loggedUser->id != 0 && $loggedUser->guest == 0) {
  $fields     = array($contusDB->quoteName(SUB_ID). ' = '. $contusDB->quote($jsonDetail));
  $condition  = array($contusDB->quoteName(USER_ID) .' = '.$contusDB->quote($uid));

  /** Update the subscriber details who subscribe the users channel */
  $contusQuery->clear()->update($contusDB->quoteName($tablePrefix.CHANNEL_SUBSCRIBE))->set($fields)->where($condition);
  $contusDB->setQuery($contusQuery);
  return $contusDB->execute();
 }
 else {
  echo json_encode(array(ERRORMSG=>'true',ERRMSG=>'404'));
  exitAction ( '' );
 }
}

/**
 * Function subscriperDetailsModels is used to retrieve full subsciber details
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
 * @return Ambigous <mixed, NULL, multitype>
 */
function subscriperDetailsModels ( $userId ) {
 global $contusDB, $contusQuery, $loggedUser;

 /** Get subscriber id's for the current user */
 $usersId = getUserSubscriberIDs( $userId );
 if($loggedUser->id != 0 && $loggedUser->guest == 0) {
  /** Get subscriber details for the current users channel */
  $contusQuery->clear()->select('*')->from($contusDB->quoteName( CHANNELTABLE ))->where($contusDB->quoteName(USER_ID) .' NOT IN( '.implode(',',$usersId).' )');
  $contusDB->setQuery($contusQuery);
  return $contusDB->loadObjectList();
 }
 else {
  echo json_encode(array(ERRORMSG=>'true',ERRMSG=>'404'));
  exitAction ( '' );
 }
}

/**
 * Function getSubscriperId is used to retrieve subscrber id from database
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
 * @return Ambigous <mixed, NULL>
 */
function getSubscriberID ( $userId ) {
 global $tablePrefix, $contusDB, $contusQuery, $loggedUser;

 /** Check user id is exist or user is a guest user */
 if ($loggedUser->id != 0 && $loggedUser->guest == 0) {

  /** Get subscriber Id for the given user id */
  $contusQuery->clear ()->select ( $contusDB->quoteName ( SUB_ID ) )->from ( $contusDB->quoteName ( $tablePrefix . CHANNEL_SUBSCRIBE ) )->where ( $contusDB->quoteName ( USER_ID ) . ' = ' . $contusDB->quote ( $userId ) );
  $contusDB->setQuery ( $contusQuery );
  return $contusDB->loadResult ();
 } else {
  echo json_encode ( array ( ERRORMSG => 'true', ERRMSG => '404' ) );
  exitAction ( '' );
 }
}

/**
 * Function is used to get users channel video details
 *
 * @param int $uid
 *
 * @return mixed
 */
function getChannelVideoDetail ( $uid ) { 
 global $contusDB, $contusQuery, $loggedUser;

 if ($loggedUser->id != 0 && $loggedUser->guest == 0) {
  /** Get user channel videos */
  $contusQuery->clear ()->select ( array('a.*','b.category', 'b.seo_category', 'd.username', 'e.catid', 'e.vid', 'wl.video_id', 'wh.VideoId' ))->from ( '#__hdflv_upload AS a' )
  ->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='. $loggedUser->id )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='. $loggedUser->id )
  ->where ( $contusDB->quoteName ( VIDEOPUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )->where ( $contusDB->quoteName ( CATPUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )->where ( $contusDB->quoteName ( 'a.type' ) . ' = ' . $contusDB->quote ( '0' ) )->where ( $contusDB->quoteName ( USERBLOCK ) . ' = ' . $contusDB->quote ( '0' ) )
  ->where ( $contusDB->quoteName ( 'memberid' ) . ' = ' . $contusDB->quote ( $uid ))->group ( $contusDB->escape ( 'e.vid' ) )->order ( $contusDB->escape ( 'a.id' . ' ' . 'DESC' ) );
  $contusDB->setQuery ( $contusQuery );
  return $contusDB->loadObjectList ();
 } else {
  echo json_encode ( array ( ERRORMSG => 'true', ERRMSG => '404' ) );
  exitAction ( '' );
 }
}

/** Function is used to send mail to members
 *
 * @param int     $lastinsertID
 * @param string  $playlistName
 * @param string  $viewName
 *
 * @return void
 */
function sendMail ( $lastinsertID, $playlistName, $viewName ) {
 global $contusDB, $contusQuery, $loggedUser;

 /** Get base url and user, configuration object */
 $baseurl  = JURI::base();
 $config   = JFactory::getConfig();

 /** Get Seo option value from ite settings
  * and Unserialize settings data */
 $disenable =  getSiteSettings ();
 /** Get seo value */
 $seoSetting  =  $disenable['seo_option'];

 /** Get mailer object and set mail subject */
 $mailer   = JFactory::getMailer();
 $subject  = 'New playlist added by ' . $loggedUser->username . ' on your site.';

 /** Get mail information from gloabl configuration */
 $sender   = $config->get('mailfrom');
 /** Get user email */
 $mailer->setSender($loggedUser->email);
 /** Get sender email */
 $mailer->addRecipient($sender);

 /** Get seo category name for the given cat id */
 $contusQuery->clear()->select($contusDB->quoteName('seo_category'))->from($contusDB->quoteName( PLAYLISTTABLE ))->where( $contusDB->quoteName('id').'='.$lastinsertID);
 $contusDB->setQuery($contusQuery);
 $seo_category = $contusDB->loadResult();

 /** Check view name is category or playlist
  * Based on that assign view name */
 if ( $viewName == CATEGORY) {
  $viewSEOURL     = "view=category&category=".$seo_category;
  $viewNonSEOURL  = "view=category&catid=".$lastinsertID;
 } else{
  $viewSEOURL     = "view=playlist&playlist=".$seo_category;
  $viewNonSEOURL  = "view=playlist&playid=".$lastinsertID;
 }

 /** Check SEO setting is enabled
  * Based on that assign URL */
 if( $seoSetting ) {
  $playlistURL = $baseurl."index.php?option=com_contushdvideoshare&".$viewSEOURL;
 } else {
  $playlistURL = $baseurl."index.php?option=com_contushdvideoshare&".$viewNonSEOURL;
 }

 /** Get email template for playlist */
 $get_html_message = file_get_contents($baseurl . '/components/com_contushdvideoshare/emailtemplate/memberplaylist.html');
 $update_baseurl   = str_replace('{baseurl}', $baseurl, $get_html_message);
 $update_username  = str_replace('{username}', $loggedUser->username, $update_baseurl);
 $categoryName     = str_replace( '{playlistName}' , $playlistName ,$update_username );
 $playlist_url     = str_replace('{playlist_url}' , $playlistURL , $categoryName );
 $message          = $playlist_url;
 $mailer->isHTML(true);

 /** Set mail subject into mail object */
 $mailer->setSubject($subject);
 $mailer->Encoding = 'base64';

 /** Set mail contnet into mail object */
 $mailer->setBody($message);
 $mailer->Send();
}
?>