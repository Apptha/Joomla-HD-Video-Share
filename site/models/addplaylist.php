<?php
/**
 * Add playlist model file
 *
 * This file is to update and add the playlist for front end users
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
jimport ( 'joomla.application.component.model' );

/**
 * add playlist model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ModelContushdvideoshareaddplaylist extends ContushdvideoshareModel {
  /**
   * Function to get individual playlist details for edit playlist details
   * 
   * @return array
   */
  public function getPlaylistDetail( $id ) {
    /** Database connection */
    global $contusDB, $contusQuery;
    
    /** Get playlist id */
    $id = JRequest::getVar ( 'playlist_id' );
    
    /** Query to get playlist details */
    $contusQuery->clear ()->select ( $contusDB->quoteName ( array ( 'category', 'parent_id', 'description' ) ) )->from ( PLAYLISTTABLE )->where ( $contusDB->quoteName ( 'id' ) . '=' . $id );
    $contusDB->setQuery ( $contusQuery );
    
    /** load playilst cetails */
    return $contusDB->loadRow ();
  }
  /**
   * Function update the category details for playlists
   * 
   * @return Boolean
   */
  public function updateDetails() {
    /** Database connectivity */
    global $contusDB, $contusQuery, $appObj;
    $parentPlaylist = $description = $playLists = NULL;
    
    /** Get playlist id from query string */
    $playlistID = JRequest::getVar ( 'playlist_id' );
    /** Get playlist name */
    $playlistName = JRequest::getVar ( 'playlistname' );
    /** Get playlist category name */
    $parentPlaylist = JRequest::getVar ( 'parentcategory_name' );
    /** Get playlist playlist description */
    $description = JRequest::getVar ( 'playlistdescription' );
    /** Get user details */
    /** Get playlist user id */
    $userID = (int) getUserID();
    /** Qupting Fetched details */
    $userID = $contusDB->quote ( $userID );
    /** Adding quote for parent category */
    $parent_category = $contusDB->quote ( $parentPlaylist );
    $category = $contusDB->quote ( $contusDB->escape ( $playlistName, true ), false );
    
    /** Function call for seo option */
    $playLists = getPlaylistDetails ();
    $seo_category =  makeSEOTitle( $category );
    foreach ($playLists AS $playList ) {
      if ( $seo_category == $playList->seo_category ) {
        /** Load admin videos table and get seo title */
        $seo_category = JString::increment ( $seo_category, 'dash' );
      }
    }
    /** Quote for seo category */
    $seo_category = $contusDB->quote ( $contusDB->escape ( $seo_category, true ), false ); 
    /** Quote for Description */
    $description = $contusDB->quote ( $contusDB->escape ( $description, true ), false );
    /** Function declaration for ordering */
    $ordering = $this->get_maximum_order ();
    /** Getting published playlist details */
    $published = $contusDB->quote ( '1' );
    
    /** To Perform operation when playlist id exist
     * Fetched column details */
    if ($playlistID) {
      /** From array for updating fields */
      $fields = array ( $contusDB->quoteName ( 'category' ) . ' = ' . $category, $contusDB->quoteName ( 'seo_category' ) . ' = ' . $seo_category,
          $contusDB->quoteName ( 'parent_id' ) . ' = ' . $parent_category, $contusDB->quoteName ( 'description' ) . '=' . $description,
          $contusDB->quoteName ( 'published' ) . ' = ' . $published  );
      
      /** Push values into table */
      $values = array ( $userID, $category, $seo_category, $parent_category, $published );
      $contusQuery->clear ()->update ( $contusDB->quoteName ( PLAYLISTTABLE ) )->set ( $fields )->values ( implode ( ',', $values ) )->where ( $contusDB->quoteName ( 'id' ) . '=' . $playlistID );
    } else {
      /** To Perform operation when playlist id not exist */
      /** Check the playlist details exist or not */
      $contusQuery->clear()->select ( '*' )->from ( $contusDB->quoteName ( PLAYLISTTABLE ) )->where ( $contusDB->quoteName ( 'category' ) . '=' . $category );
      /** Check wheather category name exists */
      $contusDB->setQuery ( $contusQuery );
      
      if ($contusDB->loadResult ()) {
        /** Redirect after update */
        $appObj->redirect ( JRoute::_ ( "index.php?option=com_contushdvideoshare&view=addplaylist" ), 'Already Exists This category' );
      }
      /** Inserting values into DB */
      $values = array ( $userID, $category, $seo_category, $parent_category, $ordering, $published );
      $contusQuery->clear ()->insert ( $contusDB->quoteName ( PLAYLISTTABLE ) )->columns ( $contusDB->quoteName ( array (
          'member_id', 'category', 'seo_category', 'parent_id', 'ordering', 'published' ) ) )->values ( implode ( ',', $values ) );
    }
    $contusDB->setQuery ( $contusQuery );
    $contusDB->query ();
    
    /** Get last inserted id */
    $resultInserid = $contusDB->insertid ();
    /** If last insert id exiet insert video in playlist details */
    if ($resultInserid) {
      /** Mail Functionlity start */
      /** Get last inserted playlist id */
      $lastinsertID = $resultInserid;
      sendMail ( $lastinsertID, $playlistName, 'category' );
    /** End mail Functionality */
    }
    /** @return val */
    return '1';
  }
  
  /**
   * Function for all category for parent category dropdown
   * 
   * @return <mixed variable>
   */
  public function get_all_parentcategory() {
    global $contusDB, $contusQuery;
    $contusQuery->clear()->select ( '*' )->from ( PLAYLISTTABLE )->where ( $contusDB->quoteName ( 'published' ) . ' = ' . $contusDB->quote ( '1' ) )->order ( 'ordering DESC' );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObjectList ();
  }
  
  /**
   * Function get order in playlist category
   * 
   * @return int
   */
  public function get_maximum_order() {
    global $contusDB, $contusQuery;
    $contusQuery->clear()->select ( 'max(ordering)' )->from ( PLAYLISTTABLE );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadResult ();
  }
  
  /**
   * Function get total playlist added by current user
   * 
   * @return integer
   */
  public function get_total_playlist() {
    global $contusDB, $contusQuery;
   
    /** Get loggedin user id */
    $memberID = (int) getUserID();

    $contusQuery->clear()->select ( $contusDB->quoteName ( 'id' ) )->from ( $contusDB->quoteName ( PLAYLISTTABLE ) )->where ( $contusDB->quoteName ( 'member_id' ) . '=' . $contusDB->quote ( $memberID ) );
    $contusDB->setQuery ( $contusQuery );
    $contusDB->query ();
    /** Fetch playlist for particular user */
    return $contusDB->getNumRows ();
  }
}