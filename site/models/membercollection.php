<?php
/**
 * Model file to get member videos
 *
 * This file is to fetch member videos detail from database
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

/** Import Joomla model library */
jimport ( 'joomla.application.component.model' );

define ('MEMBERID', 'memberid');
/**
 * Member videos model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharemembercollection extends ContushdvideoshareModel {
  /**
   * Function to display the videos of a particular registered member
   *
   * @return array
   */
  public function getmembercollection() {
    /** Get db connection for member collection page */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    $session = JFactory::getSession ();
    $user = JFactory::getUser();
    $userId = $user->get('id');
    /** Get member id value from request */
    $mbridvalue = JRequest::getInt ( 'memberidvalue' );
    
    /** Check member id is exists */
    if ($mbridvalue) {
      /** Set member id into session */ 
      $session->set ( MEMBERID, $mbridvalue );
    }
    
    /** Query for fetching membercollection total for pagination */
    $query->select ( 'count(a.id)' )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__hdflv_category AS b ON a.playlistid=b.id' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( VIDMEMBERID ) . ' = ' . $db->quote ( $session->get ( MEMBERID, 'empty' ) ) );
    $db->setQuery ( $query );
    $resulttotal = $db->loadResult ();
    $total = $resulttotal;
    $memberPageNo = 1;
    
    /** Get page id for member collection page */
    if (JRequest::getInt ( 'video_pageid' )) {
      $memberPageNo = JRequest::getInt ( 'video_pageid' );
    }
    
    /** Function call for fetching member collection settings */
    $memberLimitrow = getpagerowcol ();
    $memberThumbView = unserialize ( $memberLimitrow [0]->thumbview );
    $memberLength = $memberThumbView ['memberpagerow'] * $memberThumbView ['memberpagecol'];
    $memberPages = ceil ( $total / $memberLength );
    
    /** Set starting limit for member collection query */
    if ($memberPageNo == 1) {
      $memberStart = 0;
    } else {
      $memberStart = ($memberPageNo - 1) * $memberLength;
    }
    
    /** Query for displaying the member collection videos when click on his name */
    if(!empty($userId)) {
    $query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
        'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category', 'b.seo_category', 'd.username', 'e.catid', CATVIDEOID, 'wl.video_id', 'wh.VideoId' 
    ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( VIDMEMBERID ) . ' = ' . $db->quote ( $session->get ( MEMBERID, 'empty' ) ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    }
    else {
    	$query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
    			'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category', 'b.seo_category', 'd.username', 'e.catid', CATVIDEOID
    	) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( VIDMEMBERID ) . ' = ' . $db->quote ( $session->get ( MEMBERID, 'empty' ) ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    }
    $db->setQuery ( $query, $memberStart, $memberLength );
    $rows = $db->LoadObjectList ();
    
    /** Below code is to merge the pagination values like pageno,pages,start value,length value */
    if (count ( $rows ) > 0) {
      $rows ['pageno'] = $memberPageNo;
      $rows ['pages'] = $memberPages;
      $rows ['start'] = $memberStart;
      $rows ['length'] = $memberLength;
    }
    /** Return member collection details */
    return $rows;
  }
  /** Member videos model class ends */
}
