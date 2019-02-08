<?php
/**
 * Google ad model file
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
 * Admin google adsense model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModelgooglead extends ContushdvideoshareModel {
  /**
   * Function to get google adsense
   *
   * @return getgooglead
   */
  public function getgooglead() {
    $rs_googlead = JTable::getInstance ( 'googlead', 'Table' );
    
    /** To get the id no to be edited... */
    $id = 1;
    $rs_googlead->load ( $id );
    
    return $rs_googlead;
  }
  
  /**
   * Function to save google adsense
   *
   * @return savegooglead
   */
  public function savegooglead() {
    $option = JRequest::getCmd ( 'option' );
    $arrFormData = JRequest::get ( 'POST' );
    $mainframe = JFactory::getApplication ();

    $objGoogleAdTable = & JTable::getInstance ( 'googlead', 'Table' );
    $id = 1;
    
    if (JRequest::getVar ( 'reopenadd' ) == '') {
      $arrFormData ['reopenadd'] = '1';
      $arrFormData ['ropen'] = '';
    }
    
    $code = JRequest::getVar ( 'code', '', 'post', 'string', JREQUEST_ALLOWRAW );
    
    /** Convert all applicable characters to HTML entities */
    $arrFormData ['code'] = htmlentities ( stripslashes ( $code ) );
    
    /** Get the node from the table */
    $objGoogleAdTable->load ( $id );
    
    /** Bind data to the table object */
    if (! $objGoogleAdTable->bind ( $arrFormData )) {
      JError::raiseWarning ( 500, $objGoogleAdTable->getError () );
    }
    
    /** Store the node in the database table */
    if (! $objGoogleAdTable->store ()) {
      JError::raiseWarning ( 500, $objGoogleAdTable->getError () );
    }
    
    /** Page redirect */
    $link = 'index.php?option=' . $option . '&layout=googlead';
    $mainframe->redirect ( $link, SAVE_SUCCESS, MESSAGE );
  }
}
