<?php
/**
 * Recent videos model file
 *
 * This file is to fetch Recent videos detail from database
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

define ('TABLEVIDEOID' , 'e.vid');
/**
 * Recent videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharerecentvideos extends ContushdvideoshareModel {
  /**
   * Function to display a Recent videos
   *
   * @return array
   */
  public function getrecentvideos() {
    /** Get db connection for recent videos model */
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    $user = JFactory::getUser();
    $userId = $user->get('id');
    /** Query is to get the pagination for recent videos */
    $query->clear ()->select ( 'a.id' )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( USERSTABLE . ' AS d ON a.memberid=d.id' )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . ' AS b ON e.catid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( TABLEVIDEOID ) )->order ( $db->escape ( TABLEVIDEOID . ' ' . 'DESC' ) );
    $db->setQuery ( $query );
    /** Get total videos for recent video page */
    $total_query = $db->LoadObjectList ();
    /** Count recent videos */
    $total = count ( $total_query );
    $recPageNo = 1;
    /** Get page id from request for recent videos */
    if (JRequest::getInt ( 'video_pageid' )) {
      $recPageNo = JRequest::getInt ( 'video_pageid' );
    }
    /** Get recent videos page row and col settings */ 
    $recLimitrow = getpagerowcol ();
    $recThumbView = unserialize ( $recLimitrow [0]->thumbview );
    /** Calculate limit and pages for recent videos */
    $recLength = $recThumbView ['recentrow'] * $recThumbView ['recentcol'];
    $recPages = ceil ( $total / $recLength );
    /** Set start and length for recent videos query */
    if ($recPageNo == 1) {
      $recStart = 0;
    } else {
      $recStart = ($recPageNo - 1) * $recLength;
    }
    
    /** Query is to fetch the recent videos */
    if(!empty($userId)) {
    $query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
        'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category','b.seo_category', 'd.username', 'e.catid', 'e.vid', 'wl.video_id', 'wh.VideoId' 
    ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( USERSTABLE . ' AS d ON a.memberid=d.id' )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . ' AS b ON e.catid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( TABLEVIDEOID ) )->order ( $db->escape ( TABLEVIDEOID . ' ' . 'DESC' ) );
    }
    else {
    	$query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
    			'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category','b.seo_category', 'd.username', 'e.catid', 'e.vid'
    	) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( USERSTABLE . ' AS d ON a.memberid=d.id' )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . ' AS b ON e.catid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( TABLEVIDEOID ) )->order ( $db->escape ( TABLEVIDEOID . ' ' . 'DESC' ) );
    }
    $db->setQuery ( $query, $recStart, $recLength );
    $rows = $db->LoadObjectList ();
    /** Check recent video details are exists */ 
    if (count ( $rows ) > 0) {
      /** Store recent page number in row array */
      $rows ['pageno'] = $recPageNo;
      /** Store total recent pages in row array */
      $rows ['pages'] = $recPages;
      /** Store start limit in row array for recent videos */
      $rows ['start'] = $recStart;
      /** Store length limit in row array for recent videos */
      $rows ['length'] = $recLength;
    }
    /** Return recent videos data */
    return $rows;
  }
  /** Recent videos model class ends */
}
