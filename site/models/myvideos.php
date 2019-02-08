<?php
/**
 * User video model file
 *
 * This file is to fetch logged in user videos detail from database for my videos view
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
 * Myvidos model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharemyvideos extends ContushdvideoshareModel {
  /**
   * Function to delete a particular video and display videos of user who logged in
   *
   * @return array
   */
  public function getmembervideo() {
    /** Get member id from component helper */
    $memberid = getUserID();

    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    /** Variable initailization and set page number */
    $where = $search = '';
    $pageno = 1;
    
    /** Call function to delete videos in myvideos page */ 
    $this->deleteMyVideos ();
        
    /** Get myvideos search box content */
    $searchtextbox = JRequest::getString ( 'searchtxtboxmember' );
    $hiddensearchbox = JRequest::getString ( 'hidsearchtxtbox' );    
    if ($searchtextbox) {
      $search = $searchtextbox;
    } else {
      $search = $hiddensearchbox;
    }
    
    /** Call component helper for myvideos page */
    $search = phpSlashes ( $search );    
    /** Check search text is exists */
    if ($search) {
      $where = '(' . $db->quoteName ( 'a.title' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ' || ' . $db->quoteName ( 'a.description' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ' || ' . $db->quoteName ( 'a.tags' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ' || ' . $db->quoteName ( 'b.category' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ')';
    }
    
    /** Query to get the total videos for user */
    $query->clear ()->select ( 'count(a.id)' )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_category AS b ON b.id=a.playlistid' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.memberid' ) . ' = ' . $db->quote ( $memberid ) );
    
    /** Check where condition is not empty */
    if (! empty ( $where )) {
      /** Then set condition based on that 'where' */
      $query->where ( $where );
    }
    
    /** Execute query to get total count for my videos page */
    $db->setQuery ( $query );
    $total = $db->loadResult ();
    
    /** Get myvideos row and column values */
    $limitrow = getpagerowcol ();    
    $thumbview = unserialize ( $limitrow [0]->thumbview );
    $dispenable = unserialize ( $limitrow [0]->dispenable );
    /** Calculate total count for myvideos page */
    $length = $thumbview ['myvideorow'] * $thumbview ['myvideocol'];
    
    /** Calculate total pages for myvideos page */
    $pages = ceil ( $total / $length );
    
    /** Get current page number form  request */
    if (JRequest::getInt ( 'video_pageid' )) {
      $pageno = JRequest::getInt ( 'video_pageid' );
    }
    /** Set start and offset value for myvideos query */
    if ($pageno == 1) {
      $start = 0;
    } else {
      $start = ($pageno - 1) * $length;
    }

    /** Call function to get my videos page results */
    $rows = $this->getMyVideosResults ( $where, $start, $length );
    
    /** Get allow upload option value from settings and user table */
    $row1 ['allowupload'] = $this->checkAllowUpload ( $dispenable );
    
    if (count ( $rows ) > 0) {
      $rows ['pageno'] = $pageno;
      $rows ['pages'] = $pages;
      $rows ['start'] = $start;
      $rows ['length'] = $length;
    }
    
    /** Return my videos page resuts */
    return array ( 'rows' => $rows, 'row1' => $row1 );
  }
  
  /**
   * Fucntion to get allow upload value
   * 
   * @param unknown $dispenable
   * @return Ambigous <number, unknown>
   */
  public function checkAllowUpload ( $dispenable ) {
    /** Get db connection to check allow upload option */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Get allow upload value from site settings */
    $allowupload = $dispenable ['allowupload'];
    
    /** Query is to select the allow upload option of the logged in users */
    $query->clear ()->select ( 'allowupload' )->from ( '#__hdflv_user' )
    ->where ( $db->quoteName ( 'member_id' ) . ' = ' . $db->quote ( getUserID() ) );
    $db->setQuery ( $query );
    $row = $db->LoadObjectList ();
    
    /** Check upload is enable */
    if ($allowupload == 1) {
      if (isset ( $row [0] )) {
        $allowupload = $row [0]->allowupload;
      }
      if (! isset ( $allowupload )) {
        $allowupload = 1;
      }
    }
    /** Return allow upload value */
    return $allowupload;
  }
  
  /**
   * Function to delete videos in myvideos page 
   */
  public function deleteMyVideos () {    
    /** Get db connection to delete videos */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Check deletevideo param is exist in request */
    if (JRequest::getInt ( 'deletevideo' )) {
      /** Getting the video id which is going to be deleted */
      $id = JRequest::getInt ( 'deletevideo' );
    
      /** Query for deleting a selected video */
      $query->update ( $db->quoteName ( '#__hdflv_upload' ) )->set ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '-2' ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $id ) );
      $db->setQuery ( $query );
      $db->query ();

      /** Set redirect url to myvideos page */
      $url = JRoute::_ ( JURI::base() . 'index.php?option=com_contushdvideoshare&view=myvideos' );
    
      /** Redirect to myvideos page with deleted message */
      JFactory::getApplication ()->redirect ( $url, JText::_ ( 'HDVS_DELETE_SUCCESS' ), MESSAGE );
    }
  }
  
  /**
   * Function to get my videos page results 
   * 
   * @param unknown $where
   * @param unknown $start
   * @param unknown $length
   * @return Ambigous <mixed, NULL, multitype:unknown mixed >
   */
  public function getMyVideosResults ( $where, $start, $length ) {  
    $order = '';
    /** Get db connection to get myvideos results */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    $userId = (int) getUserID();
    $session = JFactory::getSession ();
    if (JRequest::getInt ( 'sorting' )) { 
      $session->set ( 'sorting', JRequest::getInt ( 'sorting' ) );
    }
    
    if ($session->get ( 'sorting', 'empty' ) == "1") {
      /** Query is to display the myvideos results order by title */
      $order = $db->escape ( 'a.title' . ' ' . 'ASC' );
    } elseif ($session->get ( 'sorting', 'empty' ) == "2") {
      /** Query is to display the myvideos results order by added date */
      $order = $db->escape ( 'a.addedon' . ' ' . 'DESC' );
    } elseif ($session->get ( 'sorting', 'empty' ) == "3") {
      /** Query is to display the myvideos results order by time of views */
      $order = $db->escape ( TIMESVIEWED . ' ' . 'DESC' );
    } else {
      /** Query is to display the myvideos results */
      $order = $db->escape ( 'a.id' . ' ' . 'DESC' );
    }
    
    /** Query is to display the myvideos results */
    if(!empty($userId)) {
      $query->clear ()->select ( array ( 'a.*', 'b.category', 'b.seo_category', 'd.username', 'e.*', 'count(f.videoid) AS total', 'wl.video_id', 'wh.VideoId'
      ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON b.id=e.catid' )->leftJoin ( '#__hdflv_comments AS f ON f.videoid=a.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.memberid' ) . ' = ' . $db->quote ( getUserID() ) );
    } else {
      $query->clear ()->select ( array ( 'a.*', 'b.category', 'b.seo_category', 'd.username', 'e.*', 'count(f.videoid) AS total'
      ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON b.id=e.catid' )->leftJoin ( '#__hdflv_comments AS f ON f.videoid=a.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.memberid' ) . ' = ' . $db->quote ( getUserID() ) );
    }
    if (! empty ( $where )) {
      $query->where ( $where );
    }
    
    $query->group ( $db->escape ( 'a.id' ) )->order ( $order );
    $db->setQuery ( $query, $start, $length );
    return $db->LoadObjectList ();
  }
  
  /**
   * Function to get video comment
   *
   * @param int $vid
   *          video id
   *          
   * @return int
   */
  public static function getmyvideocomment($vid) {
    /** Get db connection for video comments */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query to count comments for the particulr video */
    $query->select ( 'count(message)' )->from ( '#__hdflv_comments' )->where ( $db->quoteName ( 'videoid' ) . ' = ' . $db->quote ( $vid ) );
    $db->setQuery ( $query );
    
    /** Return comment count */
    return $db->loadResult ();
  }
}
