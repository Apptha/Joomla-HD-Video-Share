<?php
use Joomla\Uri\Uri;
/**
 * User playlist model file
 *
 * This file is to fetch logged in user playlists detail from database for my videos view
 *
 * @category Apptha
 * @package Com_Contushdvideoshare
 * @version 3.8
 * @author Apptha Team <developers@contus.in>
 * @copyright Copyright (C) 2015 Apptha. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla model library */
jimport ( 'joomla.application.component.model' );

/**
 * Myplaylists model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 3.6
 */
class Modelcontushdvideosharemyplaylists extends ContushdvideoshareModel {
  /**
   * Function to get playlists of user who logged in
   *
   * @return array
   */
  public function getmemberplaylists() {
    /** Connect to db */
    global $contusDB, $contusQuery, $loggedUser;
    /** Get user detail */
    $userId = $loggedUser->get ( 'id' );
    /** Get player settiings */
    $dispenable = getSiteSettings ();
    /** Get the front end user playlist options */
    $limit = $dispenable ['playlist_limit'];
    /** Query to get user playlists */
    $contusQuery->clear ()->select ( array ( 'p.*', 'COUNT(DISTINCT(vp.vid)) AS count' ) )->from ( PLAYLISTTABLE . ' AS p' )->leftJoin ( VIDEOPLAYLISTTABLE . ' AS vp ON p.id=vp.catid' )->where ( $contusDB->quoteName ( 'member_id' ) . '=' . $userId . ' and published!=-2' )->group ( $contusDB->escape ( 'p.id' ) );
    /** Load query */
    $contusDB->setQuery ( $contusQuery, 0, $limit );
    /** Get the playlist of a user */
    return $contusDB->loadObjectList ();
  }
  
  /**
   * Function to get thumb image details 
   * 
   * @param int $id
   * 
   * @return mixed
   */
  public static function getthumbimage( $id ) {
    global $contusDB, $contusQuery;
    /** Get user details */
    /** Get playlist user id */
    $userId = ( int ) getUserID ();
    /** get the front end user playlist options */
    $limit = 1;
    /** Clear query */
    /** Query to get thumb images */
    $contusQuery->clear ()->select ( 'a.*,c.*' )->from ( '#__hdflv_playlist as a' )->leftJoin ( '#__hdflv_video_playlist AS b ON a.id=b.catid' )->leftJoin ( '#__hdflv_upload AS c ON c.id=b.vid' )->where ( $contusDB->quoteName ( 'a.member_id' ) . '=' . $userId . ' AND b.catid=' . $id )->order ( 'c.id DESC' );
    /** set the query for getting thumb images */
    $contusDB->setQuery ( $contusQuery, 0, $limit );
    /** Fetch details according to limit */
    return $contusDB->loadObjectList ();
  }
  
  /**
   * Function get limit playlist added for register user
   * 
   * @return Uri
   */
  public static function delete_userplayList($deleteID) {
    global $contusDB, $contusQuery; 
    $app = JFactory::getApplication ();
    
    /** Query to delete particular palylist */
    $contusQuery->clear ()->delete ( $contusDB->quoteName ( '#__hdflv_playlist' ) )->where ( $contusDB->quoteName ( 'id' ) . '=' . $contusDB->quote ( $deleteID ) );
    $contusDB->setQuery ( $contusQuery ); 
    $contusDB->query ();
    
    /** Perform deletion */
    $contusQuery->clear ()->delete ( $contusDB->quoteName ( '#__hdflv_video_playlist' ) )->where ( $contusDB->quoteName ( 'catid' ) . '=' . $contusDB->quote ( $deleteID ) );
    $contusDB->setQuery ( $contusQuery );
    
    /** response values */
    if ($contusDB->query ()) {
      /** If success response */
      $app->redirect ( JRoute::_ ( "index.php?option=com_contushdvideoshare&view=myplaylists" ), '<p>Deleted Successfully</p>' );
      /** Function call for exit */
      exitAction ( '' );
    } else {
      /** If failure response */
      $app->redirect ( JRoute::_ ( "index.php?option=com_contushdvideoshare&view=myplaylists" ), '<p class="error">Not Deleted Successfully</p>' );
      /** Function to exit action */
      exitAction ( '' );
    }
  }
}