<?php
/**
 * Watch History videos model for HD Video Share
 *
 * This file is to fetch watch history videos details from database for Watch history videos view 
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
 * Featured videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharewatchhistoryvideos extends ContushdvideoshareModel {
  /**
   * Function to display a watch history videos
   *
   * @return array
   */
  public function getvideoHistory() {
    global $contusDB, $contusQuery;    
    $userId = (int) getUserID();
       
    /** Query is to get the pagination for watch history videos */
    $contusQuery->clear();
    $contusQuery->select ( 'count(VideoId)' ) ->from ( WATCHHISTORYTABLE ) ->where ( $contusDB->quoteName ( 'userId' ) . ' = ' . $contusDB->quote ( $userId) );
    $contusDB->setQuery ( $contusQuery );
    $watchPagetotal = $contusDB->loadResult ();
    
    $watchPageno = 1;
    /** Get page number from request for watch history page */
    if (JRequest::getInt ( 'video_pageid' )) {
      $watchPageno = JRequest::getInt ( 'video_pageid' );
    }
    /** Get watch history videos row, col values */
    $watchLimitrow = getpagerowcol ();
    $watchThumbView = unserialize ( $watchLimitrow [0]->thumbview );
    $watchLength = $watchThumbView ['historyrow'] * $watchThumbView ['historycol'];
    /** Calculate total number of pages for watch history videos */
    $watchPages = ceil ( $watchPagetotal / $watchLength );
    /** Calculate start and total count for watch history videos */
    if ($watchPageno == 1) {
      $watchPageStart = 0;
    } else {
      $watchPageStart = ($watchPageno - 1) * $watchLength;
    }
    /** Query is to display the watch history videos */
     $contusQuery->clear()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,'a.ratecount', 
                      'a.rate', 'a.amazons3', 'a.seotitle', 'b.category', 'b.seo_category', 'd.username', 'e.catid',
                      'e.vid', 'wl.video_id', 'wh.VideoId') )
    ->from ( '#__hdflv_upload AS a' )
    ->leftJoin ( '#__users AS d ON a.memberid=d.id' ) ->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )
    ->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )
    ->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )
    ->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )
    ->where ( $contusDB->quoteName ( VIDEOPUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )
    ->where ( $contusDB->quoteName ( 'wh.userId' ) . ' = ' . $contusDB->quote ( $userId) )
    ->where ( $contusDB->quoteName ( CATPUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )
    ->where ( $contusDB->quoteName ( 'a.type' ) . ' = ' . $contusDB->quote ( '0' ) )
    ->where ( $contusDB->quoteName ( USERBLOCK ) . ' = ' . $contusDB->quote ( '0' ) )
    ->group ( $contusDB->escape ( 'e.vid' ) )
    ->order ( $contusDB->escape ( 'wh.watchedOn' . ' ' . 'DESC' ) );
    $contusDB->setQuery ( $contusQuery, $watchPageStart, $watchLength );
    $rows = $contusDB->LoadObjectList ();
    /** Store pagenumber, pages, start and total count in array */
    if (count ( $rows ) > 0) {
      $rows ['pageno'] = $watchPageno;
      $rows ['pages'] = $watchPages;
      $rows ['start'] = $watchPageStart;
      $rows ['length'] = $watchLength;
    }
    /** Return the result array */
    return $rows;
  }
  /**
   * Method to Clear history of the user videos
   *
   * @return string success
   */
  public function ClearHistory($event,$UserId,$VideoId) {
    global $contusDB, $contusQuery;
    if(isset($event) && $event == 'all'){
      $conditions = array($contusDB->quoteName('userId') . '='. $UserId);
    }else{
      $conditions = array( $contusDB->quoteName('userId') . '='. $UserId, $contusDB->quoteName('VideoId') . ' = ' . $VideoId );
    }
    $contusQuery->clear()->delete($contusDB->quoteName( WATCHHISTORYTABLE ))->where($conditions);
    $contusDB->setQuery($contusQuery);
    if (!$contusDB->query()){
      throw new Exception($contusDB->getErrorMsg());
    }else{
    	return VS_SUCCESS;
    }
  }
  /**
   * Method to pause the history state of the user
   * 
   * @return string success
   */
  public function PauseHistory($UserId, $HistoryStatus){
  	/** Check whether an entry is available for the current user in the hdflv_user table */
    global $contusDB, $contusQuery;
    $contusQuery->select('COUNT(*)')->from($contusDB->quoteName(VSUSERTABLE))->where($contusDB->quoteName('member_id')." = ".$UserId);
    $contusDB->setQuery($contusQuery);
    $count = $contusDB->loadResult();
    if (!empty ($UserId)) {
      if ($count == 0) {
        /** Insert into hdflv user table */
        /** Get site settings */
        $dispenable = getSiteSettings ();
        if ($dispenable ["allowupload"] == 1) {
          $allowUpload = 1;
        } else {
          $allowUpload = 0;
        }      
  
        $userTableColumns = array ( 'member_id', 'allowupload', PAUSEHISTORYSTATE );
        $userTableValues = array ( $UserId, $allowUpload, $HistoryStatus );
        $contusQuery->clear()->insert ( $contusDB->quoteName ( VSUSERTABLE ) )->columns ( $contusDB->quoteName ( $userTableColumns ) )->values ( implode ( ',', $userTableValues ) );
        $contusDB->setQuery ( $contusQuery );
        if (! $contusDB->query ()) {
          throw new Exception ( $contusDB->getErrorMsg () );
        } else {
          return VS_SUCCESS;
        }
      } else {
        /** Update the hdflv user table */
        $fields = array ( $contusDB->quoteName ( PAUSEHISTORYSTATE ) . ' = ' . $contusDB->quote ( $HistoryStatus ) );
        $conditions = array ( $contusDB->quoteName ( 'member_id' ) . ' = ' . $contusDB->quote ( $UserId ) );
        $contusQuery->clear()->update ( $contusDB->quoteName ( VSUSERTABLE ) )->set ( $fields )->where ( $conditions );
        $contusDB->setQuery ( $contusQuery );
        if (! $contusDB->query ()) {
          throw new Exception ( $contusDB->getErrorMsg () );
        } else {
          return VS_SUCCESS;
        }
      }
    }
  }
  
  /**
   * Method to check the history state of the user.
   * 
   * @return int history state
   */
  public function HistoryState(){
    global $contusDB, $contusQuery;
    $userId = (int) getUserID();    
    $contusQuery-> clear()-> select ( PAUSEHISTORYSTATE ) ->from ( VSUSERTABLE ) ->where ( $contusDB->quoteName ( 'member_id' ) . ' = ' . $contusDB->quote ( $userId) );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadResult();
  }
}
