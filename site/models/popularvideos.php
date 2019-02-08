<?php
/**
 * Popular videos model file
 *
 * This file is to fetch Popular videos detail from database
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

/**
 * Popular videos model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharepopularvideos extends ContushdvideoshareModel {
  /**
   * Function to display the popular videos
   *
   * @return array
   */
  public function getpopularvideos() {
    /** Get db connection for popular videos */
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    $user = JFactory::getUser();
    $userId = $user->get('id');
    /** Query is to get the pagination for popularvideos */
    $query->select ( 'count(a.id)' )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__hdflv_category AS b ON a.playlistid=b.id' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) );
    $db->setQuery ( $query );
    /** Get total videos for popular video page */
    $total = $db->loadResult ();
    $popPageNo = 1;
    /** Get page id from request for popular videos */
    if (JRequest::getInt ( 'video_pageid' )) {
      $popPageNo = JRequest::getInt ( 'video_pageid' );
    }
    
    /** Get popular videos page row and col settings */
    $popLimitrow = getpagerowcol ();
    $popThumbView = unserialize ( $popLimitrow [0]->thumbview );
    /** Calculate limit and pages for popular videos */
    $popLength = $popThumbView ['popularrow'] * $popThumbView ['popularcol'];
    $popPages = ceil ( $total / $popLength );
    /** Set start and length for popular videos query */
    if ($popPageNo == 1) {
      $popStart = 0;
    } else {
      $popStart = ($popPageNo - 1) * $popLength;
    }
    
    /** Query is to fetch the popular videos */
    if(!empty($userId)) {
    $query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
        'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category','b.seo_category', 'd.username', 'e.catid', CATVIDEOID, 'wl.video_id', 'wh.VideoId' 
    ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( TIMESVIEWED . ' ' . 'DESC' ) );
    }
    else {
    	$query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
    			'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category','b.seo_category', 'd.username', 'e.catid', CATVIDEOID
    	) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( TIMESVIEWED . ' ' . 'DESC' ) );
    }
    $db->setQuery ( $query, $popStart, $popLength );
    /** Get popular video details */
    $rows = $db->LoadObjectList ();
    /** Check popular video details are exists */
    if (count ( $rows ) > 0) {
      /** Store popular page number in row array */
      $rows ['pageno'] = $popPageNo;
      /** Store total popular pages in row array */
      $rows ['pages'] = $popPages;
      /** Store start limit in row array for popular videos */
      $rows ['start'] = $popStart;
      /** Store length limit in row array for popular videos */
      $rows ['length'] = $popLength;
    }
    /** Return popular videos data */
    return $rows;
  }
  /** Popular videos model class ends */
}
