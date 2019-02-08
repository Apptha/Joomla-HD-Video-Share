<?php
/**
 * Edit ads model file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */
/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );

/**
 * Admin edit ads model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModeleditads extends ContushdvideoshareModel {
  /**
   * Function to edit ads
   *
   * @return editadsmodel
   */
  public function editadsmodel() {
    /** Method to call ads table */
    $objAdsTable = JTable::getInstance ( 'ads', 'Table' );
    /** get current selected id */
    $cid = JRequest::getVar ( 'cid', array ( 0 ), '', 'array' );
    /** varaible declaration for current ID */
    $id = $cid [0];
    /** load ads table */
    $objAdsTable->load ( $id );
    /** return array data */   
    return array ( 'rs_ads' => $objAdsTable );    
  }
  /**
   * Function to remove ad
   *
   * @return removeads
   */
  public function removeads() {
    $mainframe = JFactory::getApplication ();
    /** varaible declaration for current selected video id */
    $cid = JRequest::getVar ( 'cid', array (), '', 'array' );
    /** Declare database query */
    $db = JFactory::getDBO ();
    /** set database query */
    $query = $db->getQuery ( true );
    /** implode multiple ad ids selected to be removed */
    $cids = implode ( ',', $cid );
    if (count ( $cid )) {
      /** Query to fetch ad details for selected ads */
      $query->clear ()->select ( $db->quoteName ( array ( 'postvideopath' ) ) )->from ( $db->quoteName ( '#__hdflv_ads' ) )->where ( 'id  IN ( ' . $cids . ' )' );
      /** execute database query */
      $db->setQuery ( $query );
      /** store array of seelcted to be removed */
      $arrAdsIdList = $db->loadResultArray ();
      /** VPATH - target path /components/com_contushdvideoshare/videos */
      $strVideoPath = VPATH . "/";
      /** Removed the video and image files for selected videos */
      for($i = 0; $i < count ( $arrAdsIdList ); $i ++) {
        /** check if file exists */
          if ($arrAdsIdList [$i] && JFile::exists ( $strVideoPath . $arrAdsIdList [$i] )) {
            /** Method to delete the file */
            JFile::delete ( $strVideoPath . $arrAdsIdList [$i] );
          }
      }
      /** condition to delete the selected video */
      $conditions = array ( $db->quoteName ( 'id' ) . 'IN ( ' . $cids . ' )' );
      /** Query to delete ads */
      $query->clear ()->delete ( $db->quoteName ( '#__hdflv_ads' ) )->where ( $conditions );
      /** set query */
      $db->setQuery ( $query );
      if (! $db->query ()) {
        /** Raise a warning message */
        JError::raiseWarning ( $db->getErrorNum (), JText::_ ( $db->getErrorMsg () ) );
      }
    }
    if (count ( $cid ) > 1) {
      /** Message when multiple ads are deleted */
      $msg = 'Ads Deleted Successfully';
    } else {
      /** Message when a single video is deleted */
      $msg = 'Ad Deleted Successfully';
    }
    /** Set to redirect */
    $mainframe->redirect ( 'index.php?option=com_contushdvideoshare&layout=ads', $msg, MESSAGE );
  }
}
