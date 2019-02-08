<?php
/**
 * Related videos model file
 *
 * This file is to fetch Related videos detail from database
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
 * Related videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharerelatedvideos extends ContushdvideoshareModel {
  /**
   * Function to display a Related videos
   *
   * @return array
   */
  public function getrelatedvideos() {
    /** Get db connection for related video model */
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    $user = JFactory::getUser();
    $userId = $user->get('id');
    /** Get row, col settings for related videos page */
    $limitrow = getpagerowcol ();
    $rows = '';
    /** Call function to get video id */
    $result = $this->getVideoCatID () ;
    /** Check result is exists */
    if(isset ($result) && !empty ($result)) {
      $video = $result[0];
      $videoid = $result[1];
    }
    
    /** Query for getting the pagination values for related video page */
    $query->clear ()->select ( array ( 'COUNT(a.id)' ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( PLAYLISTID ) . ' = ' . $db->quote ( $video ) );
    $db->setQuery ( $query );
    /** Get total number of videos */ 
    $total = $db->loadResult ();
    $pageno = 1;
    
    /** Get page id from request */
    if (JRequest::getInt ( 'video_pageid' )) {
      $pageno = JRequest::getInt ( 'video_pageid' );
    }
    /** Calculate thumb limit of related videos page */
    $thumbview = unserialize ( $limitrow [0]->thumbview );
    $length = $thumbview ['relatedrow'] * $thumbview ['relatedcol'];
    $pages = ceil ( $total / $length );
    
    /** Set page number and start, length for query */
    if ($pageno == 1) {
      $start = 0;
    } else {
      $start = ($pageno - 1) * $length;
    }
    
    /** Check video id and video value is exists */  
    if (isset ( $videoid ) && (isset ( $video )) && ! empty ( $video )) {
      /** Query to get related video details */
    	if(!empty($userId)) {
      $query->clear ()->select ( array ( $db->quoteName ( 'a.id' ), $db->quoteName ( 'a.filepath' ),
          $db->quoteName ( 'a.thumburl' ), $db->quoteName ( 'a.title' ), $db->quoteName ( 'a.description' ),
          $db->quoteName ( TIMESVIEWED ), $db->quoteName ( 'a.ratecount' ), $db->quoteName ( 'a.rate' ),
          $db->quoteName ( 'a.amazons3' ), $db->quoteName ( 'a.seotitle' ), 
          $db->quoteName ( 'b.id' ) . ' AS catid', $db->quoteName ( 'b.category' ), 
          $db->quoteName ( 'b.seo_category' ), $db->quoteName ( 'e.catid' ), $db->quoteName ( 'e.vid' ),
      	  $db->quoteName ( 'wl.video_id' ), $db->quoteName ( 'wh.VideoId' )
      ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( PLAYLISTID ) . ' = ' . $db->quote ( $video ) )->group ( $db->escape ( 'a.id' ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    	}
    	else {
    		$query->clear ()->select ( array ( $db->quoteName ( 'a.id' ), $db->quoteName ( 'a.filepath' ),
    				$db->quoteName ( 'a.thumburl' ), $db->quoteName ( 'a.title' ), $db->quoteName ( 'a.description' ),
    				$db->quoteName ( TIMESVIEWED ), $db->quoteName ( 'a.ratecount' ), $db->quoteName ( 'a.rate' ),
    				$db->quoteName ( 'a.amazons3' ), $db->quoteName ( 'a.seotitle' ),
    				$db->quoteName ( 'b.id' ) . ' AS catid', $db->quoteName ( 'b.category' ),
    				$db->quoteName ( 'b.seo_category' ), $db->quoteName ( 'e.catid' ), $db->quoteName ( 'e.vid' )
    		) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( PLAYLISTID ) . ' = ' . $db->quote ( $video ) )->group ( $db->escape ( 'a.id' ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    	}
      $db->setQuery ( $query, $start, $length );
      $rows = $db->loadObjectList ();
    }    
    /** Check data is exists in row array */
    if (count ( $rows ) > 0) {
      /** Assign pagenum to row array */
      $rows ['pageno'] = $pageno;
      /** Assign pages to row array */
      $rows ['pages'] = $pages;
      /** Assign start to row array */
      $rows ['start'] = $start;
      /** Assign length to row array */
      $rows ['length'] = $length;
    }
    /** Return realted video results */
    return $rows;
  }
  
  /**
   * Function to get video id based on the SEO settings 
   * 
   * @return multitype:number Ambigous <number, mixed, unknown, string, string, NULL>
   */
  public function getVideoCatId () {
    /** Get db connection to get video id */
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    /** Code for seo option or not - start 
     * Get video param from request */
    $video = JRequest::getVar ( 'video' );
    /** Get video id from request */
    $id = JRequest::getInt ( 'id' );
    /** Get flagvideo value to check seo option */
    $flagVideo = is_numeric ( $video );
    
    /** Check video value is exists */
    if (isset ( $video ) && $video != "") {
      if ($flagVideo != 1) {
        /** Joomla router replaced to : from - in query string */
        $videoTitle = JRequest::getString ( 'video' );
        $videoid = str_replace ( ':', '-', $videoTitle );
    
        if ($videoid != "" && ! version_compare ( JVERSION, '3.0.0', 'ge' )) {
          $videoid = $db->getEscaped ( $videoid );
        }
    
        /** Get playlist id from db */
        $query->select ( 'playlistid' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'seotitle' ) . ' = ' . $db->quote ( $videoid ) );
        $db->setQuery ( $query );
        $video = $db->loadResult ();
      } else {
        /** Get video param from request to fetch playlist id */
        $videoid = JRequest::getInt ( 'video' );
        /** Get playlist id based on the video param */
        $query->select ( 'playlistid' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $videoid ) );
        $db->setQuery ( $query );
        $video = $db->loadResult ();
      }
    } elseif (isset ( $id ) && $id != '') {
      /** Get video id param from request */
      $videoid = JRequest::getInt ( 'id' ); 
      /** Get playlist id from db based on the video id */ 
      $query->select ( 'playlistid' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $videoid ) );
      $db->setQuery ( $query );
      $video = $db->loadResult ();
    } else {
      /** Check video is empty then set 0 */
      $video = $this->checkCatValueExists ( $video );
      /** Check video id is empty then set 0 */
      $videoid = $this->checkCatValueExists ( $videoid );
    }
    /** Return video id and video as an array */ 
    return array ( $video, $videoid );
  }
  
  /**
   * Function is used to return 0 if value is not exists
   * 
   * @param unknown $value
   * @return number
   */
  public function checkCatValueExists ( $value ) {
    if (! isset ( $value ) && $value == '') {
      return 0;
    }
  }
 /** Related videos model class ends */ 
}
