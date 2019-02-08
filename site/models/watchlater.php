<?php
/**
 * Watch Later model for HD Video Share
 *
 * This file is to fetch watch later videos details from database for watch later view 
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

/** No direct acesss */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla model library */
jimport ( 'joomla.application.component.model' );

/**
 * Featured videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharewatchlater extends ContushdvideoshareModel {
  /**
   * Function to display a featured videos
   *
   * @return array
   */
  public function getwatchlater() {
    /** declare global varaibles for database and setting query */
    global $contusDB, $contusQuery; 
    /** get current user Id */
    $userId = (int) getUserID();
    
    /** Query is to get the pagination for watch later values */
    $contusQuery->clear ()->select ( 'count(video_id)' )->from ( '#__hdflv_watchlater' )->where ( $contusDB->quoteName ( 'user_id' ) . ' = ' . $contusDB->quote ( $userId ) );
    /** Set query */
    $contusDB->setQuery ( $contusQuery );
    /** get the total count of the video */
    $total = $contusDB->loadResult ();
    /** set inital pageno as 1 */
    $pageno = 1;
    /** check id video page id is set */
    if (JRequest::getInt ( 'video_pageid' )) {
      /** declare pageno based on the fetched varaible */
      $pageno = JRequest::getInt ( 'video_pageid' );
    }
    /** Get row and column for the watch later page */
    $limitrow = getpagerowcol ();
    /** unserialize the row and column data */
    $thumbview = unserialize ( $limitrow [0]->thumbview );
    /** calculate the length of the watch later videos */
    $length = $thumbview ['watchlaterrow'] * $thumbview ['watchlatercol'];
    /** variable declaration of total pages */
    $pages = ceil ( $total / $length );
    if ($pageno == 1) {
      /**  set the varaible for page start */
      $start = 0;
    } else {
      /** set the varaible for page ending */
      $start = ($pageno - 1) * $length;
    }
    /** Query is to display the watch later videos */
    $contusQuery->clear ()->select ( array ( 'a.*', 'c.*', 'd.*', 'wl.video_id', 'wh.VideoId', 'wl.user_id' ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__hdflv_video_category AS c ON c.vid=a.id' )->leftJoin ( '#__hdflv_category AS d ON c.catid=d.id' )->leftJoin ( WATCHLATERTABLE . ' AS wl ON a.id=wl.video_id AND wl.user_id=' . $userId )->leftJoin ( WATCHHISTORYTABLE . ' AS wh ON a.id=wh.VideoId AND wh.userId=' . $userId )->where ( $contusDB->quoteName ( 'wl.user_id' ) . ' = ' . $userId )->order ( $contusDB->escape ( 'a.ordering' . ' ' . 'ASC' ) );
    /** set query */
    $contusDB->setQuery ( $contusQuery, $start, $length );
    /** store the array of data returned */
    $rows = $contusDB->LoadObjectList ();
    if (count ( $rows ) > 0) {
      /** store the page no */
      $rows ['pageno'] = $pageno;
      /** store the total number of pages */
      $rows ['pages'] = $pages;
      /** store the start and ending of the pagination */
      $rows ['start'] = $start;
      /** set the lenght variable. */
      $rows ['length'] = $length;
    } 
    /** return array of row variable */   
    return $rows;
  }
}