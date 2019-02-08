<?php
/**
 * Search model for HD Video Share
 *
 * This file is to fetch video details from database based on search keyword 
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
 * HD Video Share Search model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharehdvideosharesearch extends ContushdvideoshareModel {
  
  /**
   * Function to get search result
   *
   * @return array
   */
  public function getsearch() {
    /** Get search value from request */
    $btn = JRequest::getVar ( 'search_btn' );
      
    /** Call function to get total search results */
    $subtotal     = count ( $this->searchQuery ($btn, '', '' ));
    $total        = $subtotal;
    $searchPageNo = 1;
    
    /** Get page number for search results */
    if (JRequest::getInt ( 'video_pageid' )) {
      $searchPageNo = JRequest::getInt ( 'video_pageid' );
    }
    
    $searchLimit      = getpagerowcol ();
    $searchThumbView  = unserialize ( $searchLimit [0]->thumbview );
    $searchLength     = $searchThumbView ['searchrow'] * $searchThumbView ['searchcol'];
    $searchPages      = ceil ( $total / $searchLength );
    
    /** Set search page number starting limit */
    if ($searchPageNo == 1) {
      $searchStart = 0;
    } else {
      $searchStart = ($searchPageNo - 1) * $searchLength;
    }

    /** Get search results with pagination */
    $rows = $this->searchQuery ($btn, $searchStart, $searchLength);
    
    /** Check result is exists */
    if (count ( $rows ) > 0) {
      /** Store page num, pages, start and total count in result array */
      $rows ['pageno'] = $searchPageNo;
      $rows ['pages'] = $searchPages;
      $rows ['start'] = $searchStart;
      $rows ['length'] = $searchLength;
    }
    
    /** Return search results array */
    return $rows;
  }
  
  public function searchQuery ($btn, $searchStart, $searchLength ) {
    /** Get db connection for search model */
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    $user = JFactory::getUser();
    $userId = $user->get('id');
    /** Get session for search results */
    $session = JFactory::getSession ();
    
    if (isset ( $btn )) {
      /** Getting the search text box value */
      $search = JRequest::getString ( 'searchtxtbox' );
      $session->set ( 'search', $search );
    } else {
      $search = $session->get ( 'search' );
    }
    
    /** Call component helper for search page */
    $search       = phpSlashes ( $search );
    
    /** Query to get search results */
    if(!empty($userId)) {
    $query->select ( array ( 'a.id AS vid', 'a.category', 'a.seo_category', 'b.catid', 'b.vid', 'c.id',
        'c.amazons3', 'c.filepath', 'c.thumburl', 'c.title', 'c.description', 'c.times_viewed', 'c.ratecount',
        'c.rate', 'c.seotitle', 'd.id', 'd.username', 'wl.video_id', 'wh.VideoId'  ) )
    ->from ( '#__hdflv_category AS a' )
    ->leftJoin ( '#__hdflv_video_category AS b ON b.catid=a.id' )
    ->leftJoin ( '#__hdflv_upload AS c ON c.id=b.vid' )
    ->leftJoin ( '#__users AS d ON c.memberid=d.id' ) 
    ->leftJoin ( WATCHLATERTABLE.' AS wl ON c.id=wl.video_id AND wl.user_id='.$userId )
    ->leftJoin ( WATCHHISTORYTABLE.' AS wh ON c.id=wh.VideoId AND wh.userId='.$userId )
    ->where ( $db->quoteName ( 'c.type' ) . ' = ' . $db->quote ( '0' ) )
    ->where ( $db->quoteName ( 'c.published' ) . ' = ' . $db->quote ( '1' ) )
    ->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )
    ->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) );
    }
    else {
    	$query->select ( array ( 'a.id AS vid', 'a.category', 'a.seo_category', 'b.catid', 'b.vid', 'c.id',
    		'c.amazons3', 'c.filepath', 'c.thumburl', 'c.title', 'c.description', 'c.times_viewed', 'c.ratecount',
    		'c.rate', 'c.seotitle', 'd.id', 'd.username'  ) )
    	->from ( '#__hdflv_category AS a' )
    	->leftJoin ( '#__hdflv_video_category AS b ON b.catid=a.id' )
    	->leftJoin ( '#__hdflv_upload AS c ON c.id=b.vid' )
    	->leftJoin ( '#__users AS d ON c.memberid=d.id' )
    	->where ( $db->quoteName ( 'c.type' ) . ' = ' . $db->quote ( '0' ) )
    	->where ( $db->quoteName ( 'c.published' ) . ' = ' . $db->quote ( '1' ) )
    	->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )
    	->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) );
    }
    
    $whereQuery =  '(' . $db->quoteName ( 'c.title' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ' || ' . $db->quoteName ( 'c.description' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ' || ' . $db->quoteName ( 'c.tags' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ')'  ;
    
    /** Breaking the string to array of words */
    $kt = preg_split ( "/[\s,]+/", $search );
    /** Now let us generate the sql */
    while ( list ( $key, $search ) = each ( $kt ) ) {
      if ($search != " " && strlen ( $search ) > 0) {
        $whereQuery = '(' . $db->quoteName ( 'c.title' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ' || ' . $db->quoteName ( 'c.description' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ' || ' . $db->quoteName ( 'c.tags' ) . ' LIKE ' . $db->quote ( '%' . $search . '%', false ) . ')'  ;
      }
    }
    
    $query->where ($whereQuery) ->group ( $db->escape ( 'c.id' ));
    
    /** Check start and offset is exists */
    if(empty ($searchStart) && empty ($searchLength)) {
      $db->setQuery ( $query );
    } else {
      $db->setQuery ( $query, $searchStart, $searchLength);
    }
    /** Return search results */ 
    return $db->loadObjectList ();
  }
}
