<?php
/**
 * Category model for HD Video Share
 *
 * This file is to fetch category details from database for category view 
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
 * Category videos model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharecategory extends ContushdvideoshareModel {
  /**
   * Function to display the video results of related category
   *
   * @return array
   */
  public function getcategory() {
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    $user = JFactory::getUser();
    $userId = $user->get('id');
    /** Get category id */
    $catid = getcategoryid( CATEGORYPARAM, CATEGORYTABLE, SEOCATEGORY, CATIDPARAM );
    if (! version_compare ( JVERSION, JOOM3, 'ge' )) {
      $catid = $db->getEscaped ( $catid );
    }
    $where = '(' . $db->quoteName ( 'e.catid' ) . ' = ' . $db->quote ( $catid ) . ' OR ' . $db->quoteName ( 'b.parent_id' ) . ' = ' . $db->quote ( $catid ) . ' OR ' . $db->quoteName ( 'a.playlistid' ) . ' = ' . $db->quote ( $catid ) . ')' ;
    $whrQuery = $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) . ' AND ' . $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) . ' AND '. $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) ;
    /** Query to calculate total number of videos in paricular category */
    if(!empty($userId)) {
	    $query->clear ()->select ( 'a.id' )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )
	    ->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN ) ->leftJoin ( CATEGORYTABLE . ' AS b ON a.playlistid=b.id' )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )
	    ->where ( $where )->where ( $whrQuery ) ->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( 'b.ordering' . ' ' . 'ASC' ) );
    }
    else {
    	$query->clear ()->select ( 'a.id' )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )
    	->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN ) ->leftJoin ( CATEGORYTABLE . ' AS b ON a.playlistid=b.id' )
    	->where ( $where )->where ( $whrQuery ) ->group ( $db->escape ( 'e.vid' ) )->order ( $db->escape ( 'b.ordering' . ' ' . 'ASC' ) );
    }
    $db->setQuery ( $query );
    $searchtotal = $db->loadObjectList ();
    $total = count ( $searchtotal );
    /** Set page number as 1 
     * Also get page id from request */
    $catPageNo = 1;
    if (JRequest::getInt ( 'video_pageid')) {
      $catPageNo = JRequest::getInt ( 'video_pageid' );
    }
    /** Get category row, column settings from helper */
    $catlimitrow = getpagerowcol ();
    $catthumbview = unserialize ( $catlimitrow [0]->thumbview );
    /** Calculate category pages and total thumb count */
    $catLength = $catthumbview ['categoryrow'] * $catthumbview ['categorycol'];
    $catPages = ceil ( $total / $catLength );
    /** Set start and offset fro category query */
    if ($catPageNo == 1) {
      $catstart = 0;
    } else {
      $catstart = ($catPageNo - 1) * $catLength;
    }
    /** This query for displaying category's full view display */
    if(!empty($userId)) {
	    $query->clear ('select') ->clear ('order')
	    ->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
	        'a.ratecount', 'a.rate', 'a.streameroption', 'a.streamerpath', 'a.created_date', 'a.videourl', 'a.playlistid', 'a.amazons3',
	        'a.seotitle', 'a.embedcode', 'b.category', 'b.seo_category', 'b.parent_id', 'd.username', 'e.catid', 'e.vid', 'wl.video_id', 'wh.VideoId' 
	    ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    }
    else {
    	$query->clear ('select') ->clear ('order')
    	->select ( array ( 'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED,
    			'a.ratecount', 'a.rate', 'a.streameroption', 'a.streamerpath', 'a.created_date', 'a.videourl', 'a.playlistid', 'a.amazons3',
    			'a.seotitle', 'a.embedcode', 'b.category', 'b.seo_category', 'b.parent_id', 'd.username', 'e.catid', 'e.vid'
    	) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    }
    $db->setQuery ( $query, $catstart, $catLength );
    $resultrows = $db->LoadObjectList ();
    /** This query is to get videos for player in category page */
    $where = '(' . $db->quoteName ( 'a.playlistid' ) . ' = ' . $db->quote ( $catid ) . ')';    
    $query->clear ( 'where' ) ->where ( $where )->where ( $whrQuery );   
    $db->setQuery ( $query, $catstart, $catLength );
    $videoForPlayer = $db->LoadObjectList ();
    if (empty ( $videoForPlayer )) {
      $where = '(' . $db->quoteName ( 'b.parent_id' ) . ' = ' . $db->quote ( $catid ) . ')';
      $query->clear ('where')-> where ( $where )-> where ($whrQuery);
      $db->setQuery ( $query, $catstart, $catLength );
      $videoForPlayer = $db->LoadObjectList ();
    }
    /** This query for displaying category's full view display */
    $query->clear ()->select ( CATEGORY )->from ( CATEGORYTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $catid ) );
    $db->setQuery ( $query );
    $category = $db->LoadObjectList ();
    /** Below code is to merge the pagination values like pageno,pages,start value,length value */
    if (count ( $resultrows ) > 0) {
      $categoryname_array = array ( 'categoryname' => $category );
      $merge_rows = array_merge ( $resultrows, $categoryname_array );
      $pageno_array = array ( 'pageno' => $catPageNo );
      $merge_pageno = array_merge ( $merge_rows, $pageno_array );
      $pages_array = array ( 'pages' => $catPages );
      $merge_pages = array_merge ( $merge_pageno, $pages_array );
      $start_array = array ( 'start' => $catstart );
      $merge_start = array_merge ( $merge_pages, $start_array );
      $length_array = array ( 'length' => $catLength );
      $mergeLength = array_merge ( $merge_start, $length_array );
      $videoForPlayerArray = array ( 'videoForPlayer' => $videoForPlayer );
      $rows = array_merge ( $mergeLength, $videoForPlayerArray );
    } else {
      /** This query for displaying category's full view display */
      $query->clear ()->select ( '*' )->from ( CATEGORYTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $catid ) );
      $db->setQuery ( $query );
      $rows = $db->LoadObjectList ();
    }
    /** Return category details */
    return $rows;
  }
}
