<?php
/**
 * Featured videos model for HD Video Share
 *
 * This file is to fetch featured videos details from database for featured videos view 
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
class Modelcontushdvideosharefeaturedvideos extends ContushdvideoshareModel {
  /**
   * Function to display a featured videos
   *
   * @return array
   */
  public function getfeaturedvideos() {
    /** declare database query */
    $db = $this->getDBO ();
    /** set query */
    $query = $db->getQuery ( true );
    /** method to get user details */
    $user = JFactory::getUser();
    /** Get current user id */
    $userId = $user->get('id');
    /** Query is to get the pagination for featured values */
    $query->select ( 'count(a.id)' )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__hdflv_category AS b ON a.playlistid=b.id' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) );
    /** execute query */
    $db->setQuery ( $query );
    /** get the count of total featured videos. */
    $total = $db->loadResult ();
    /** set initial page number for featured videos to 1 */
    $feaPageno = 1;
    
    /** Get page number from request for featured videos page */
    if (JRequest::getInt ( 'video_pageid' )) {
      /** Variable declaration for featured videos */
      $feaPageno = JRequest::getInt ( 'video_pageid' );
    }
    /** Get featured videos row, col values */
    /** Get row and column for the featured videos */
    $feaLimitrow = getpagerowcol ();
    /** unserialize the thumbview data */
    $feaThumbView = unserialize ( $feaLimitrow [0]->thumbview );
    /** varaible declaration for featured video length based on the row and column */
    $feaLength = $feaThumbView ['featurrow'] * $feaThumbView ['featurcol'];
    /** Calculate total number of pages for featured videos */
    $feaPages = ceil ( $total / $feaLength );
    
    /** Calculate start and total count for featured videos */
    if ($feaPageno == 1) {
      /** set featured videos page limit */
      $feaStart = 0;
    } else {
      /** set featured videos page limit */
      $feaStart = ($feaPageno - 1) * $feaLength;
    }
    
    /** Query is to display the featured videos */
    if(!empty($userId)) {
      /** Query to select the featured videos for the logged in users */
    $query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
        'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category', 'b.seo_category', 'd.username', 'e.catid', 'e.vid', 'wl.video_id', 'wh.VideoId' 
    ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( 'a.ordering' . ' ' . 'ASC' ) );
    }
    else {
      /** Query to fetch the featured videos for guest users */
    	$query->clear ()->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
    			'a.ratecount', 'a.rate', 'a.amazons3', 'a.seotitle', 'b.category', 'b.seo_category', 'd.username', 'e.catid', 'e.vid'
    	) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category AS e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category AS b ON e.catid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( 'a.ordering' . ' ' . 'ASC' ) );
    }
    /** set query */
    $db->setQuery ( $query, $feaStart, $feaLength );
    /** store array of featured video details */
    $rows = $db->LoadObjectList ();
    
    /** Store pagenumber, pages, start and total count in array */
    if (count ( $rows ) > 0) {
      /** store featured videos page number */
      $rows ['pageno'] = $feaPageno;
      /** stored featured videos total page number */
      $rows ['pages'] = $feaPages;
      /** set featured videos starting page */
      $rows ['start'] = $feaStart;
      /** set featured videos row and column count */
      $rows ['length'] = $feaLength;
    }
    /** Return the result array */
    return $rows;
  }
}
