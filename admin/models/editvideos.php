<?php
/**
 * Edit videos model file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5 */
/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );
jimport ( 'joomla.filesystem.file' );

/**
 * Admin edit videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModeleditvideos extends ContushdvideoshareModel {
  /**
   * Function to edit videos
   *
   * @return editvideosmodel
   */
  public function editvideosmodel() {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query to fetch category list */
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'category' ) ) )->from ( $db->quoteName ( '#__hdflv_category' ) )->where ( 'published = 1' )->order ( 'id DESC' );
    $db->setQuery ( $query );
    $rs_play = $db->loadObjectList ();
    
    /** Query to fetch pre/post roll ads */
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'adsname' ) ) )->from ( $db->quoteName ( '#__hdflv_ads' ) )
    ->where ( 'published = 1' )->where ( 'typeofadd <> \'mid\'' )->where ( 'typeofadd <> \'ima\'' )->order ( 'adsname ASC' );
    $db->setQuery ( $query );
    $rs_ads = $db->loadObjectList ();
    
    /** Get adminvideos table object */
    $rs_editupload = JTable::getInstance ( 'adminvideos', 'Table' );
    $cid = JRequest::getVar ( 'cid', array ( 0 ), '', 'array' );
    
    /** To get the id no to be edited... */
    $id = $cid [0];
    $rs_editupload->load ( $id );    
    
    if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
      $strTable = '#__viewlevels';
      $strName = 'title';
    } else {
      $strTable = '#__groups';
      $strName = 'name';
    }
    
    /** Query to fetch user groups */
    $query->clear ()->select ( array ( 'id AS id', $strName . ' AS title' ) )->from ( $db->quoteName ( $strTable ) )->order ( 'id ASC' );
    $db->setQuery ( $query );
    $usergroups = $db->loadObjectList ();
    return array ( 'rs_editupload' => $rs_editupload, 'rs_play' => $rs_play,
        'rs_ads' => $rs_ads, 'user_groups' => $usergroups );
  }
  
  /**
   * Function to remove videos
   *
   * @return removevideos
   */
  public function removevideos() {
    $option = COMPONENT;
    global $mainframe;
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Get video id's from request */
    $cid = JRequest::getVar ( 'cid', array (), '', 'array' );
    $cids = implode ( ',', $cid );
    
    /** Check count of id's */
    if (count ( $cid )) {
      
      /** Query to fetch video details for selected videos */
      $query->clear ()->select ( $db->quoteName ( array ( 'videourl', 'thumburl', 'previewurl', 'hdurl' ) ) )->from ( $db->quoteName ( '#__hdflv_upload' ) )
      ->where ( 'filepath = \'File\' OR filepath <> \'FFmpeg\' AND id IN ( ' . $cids . ' )' );
      $db->setQuery ( $query );
      $arrVideoIdList = $db->loadObjectList ();
      
      /** Call function to delete videos */
      $this->deleteVideosCaller ( $arrVideoIdList );
      
      /** Query to delete selected videos */
      $conditions = array ( $db->quoteName ( 'id' ) . 'IN ( ' . $cids . ' )' );      
      $query->clear ()->delete ( $db->quoteName ( '#__hdflv_upload' ) )->where ( $conditions );
      $db->setQuery ( $query );
      
      if (! $db->query ()) {
        JError::raiseWarning ( $db->getErrorNum (), JText::_ ( $db->getErrorMsg () ) );
      } else {
        /** Query to delete video id in #__hdflv_video_category table */
        $conditions = array ( $db->quoteName ( 'vid' ) . 'IN ( ' . $cids . ' )' );        
        $query->clear ()->delete ( $db->quoteName ( '#__hdflv_video_category' ) )->where ( $conditions );
        $db->setQuery ( $query );
      }
    }
    
    if (count ( $cid ) > 1) {
      $msg = JText::_ ( 'Videos Deleted Successfully' );
    } else {
      $msg = JText::_ ( 'Video Deleted Successfully' );
    }
    
    /** Page redirect */
    $mainframe = JFactory::getApplication ();
    /** Set redirect to videos list page */
    $mainframe->redirect ( 'index.php?option=' . $option . '&layout=adminvideos&user=' . JRequest::getVar ( 'user' ), $msg, MESSAGE );
  }
  
  /**
   * Function to call delete videos function for the selected videos
   * 
   * @param unknown $cids
   */
  public function deleteVideosCaller ( $arrVideoIdList ) {
    /** Removed the video and image files for selected videos */
    if (count ( $arrVideoIdList )) {
      for($i = 0; $i < count ( $arrVideoIdList ); $i ++) {
        /** Call function to delete selected videos */
        $this->deleteVideos ($arrVideoIdList [$i]->videourl) ;
        $this->deleteVideos ($arrVideoIdList [$i]->thumburl, IMAGE ) ;
        $this->deleteVideos ($arrVideoIdList [$i]->previewurl, IMAGE ) ;
        $this->deleteVideos ($arrVideoIdList [$i]->hdurl) ; 
      }
    }
  }
  
  /**
   * Function to delete selected videos
   * 
   * @param unknown $videoList
   * @param unknown $type
   */
  public function deleteVideos ( $videoList, $type ) {
    /** VPATH - target path /components/com_contushdvideoshare/videos */
    $strVideoPath = VPATH . "/";
    
    /** Check type is image 
     * If yes then check thumb or preview is not a default image */
    if ($type == IMAGE ) {
      if ( $videoList != 'default_thumb.jpg' && JFile::exists ( $strVideoPath . $videoList )) {
        JFile::delete ( $strVideoPath . $videoList );
      }
    } else {
      if ( $videoList && JFile::exists ( $strVideoPath . $videoList )) {
        JFile::delete ( $strVideoPath . $videoList );
      }
    }
  }
  
}
