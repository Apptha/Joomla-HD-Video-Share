<?php
/**
 * Member details model
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

/** Import Joomla pagination library */
jimport ( 'joomla.html.pagination' );

define('MEMBER_STATUS', 'member_status');
define('MEMBER_SEARCH', 'member_search');
define('MEMBER_UPLOAD', 'member_upload');

/**
 * Admin google adsense model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModelmemberdetails extends ContushdvideoshareModel {
  /**
   * Constructor function to declare global value
   */
  public function __construct() {
    global $mainframe;
    parent::__construct ();
    $mainframe = JFactory::getApplication ();
  } 
  
  /**
   * Function to get member details
   *
   * @return getmemberdetails
   */
  public function getmemberdetails() {
    global $mainframe;
    $option = JRequest::getCmd ( 'option' );
    $mainframe = JFactory::getApplication ();
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    $query->clear ()->select ( $db->quoteName ( array ( 'a.id', 'a.name', 'a.username', 'a.email',
        'a.registerDate', 'a.block', 'b.allowupload' ) ) )->from ( $db->quoteName ( USERSTABLE ) . ' AS a' )->leftJoin ( $db->quoteName ( '#__hdflv_user' ) . ' AS b ON b.member_id = a.id' );
         
    /** Filter variable for member name search */
    $strMemberSearch = $mainframe->getUserStateFromRequest ( $option . MEMBER_SEARCH, MEMBER_SEARCH, '', 'string' );
    $search1 = $strMemberSearch;
    /** Call component helper function */
    $strMemberSearch = phpSlashes ( $strMemberSearch );  
       
    /** Filter variable for member order direction */
    $strMemberDir = $mainframe->getUserStateFromRequest ( $option . 'filter_order_Dir_member', 'filter_order_Dir', 'asc', 'word' );
    $arrMemberFilter ['filter_order_Dir'] = $strMemberDir;
    
    /** Filter variable for member order */
    $strMemberOrder = $mainframe->getUserStateFromRequest ( $option . 'filter_order_member', 'filter_order', 'name', 'cmd' );
    $arrMemberFilter ['filter_order'] = $strMemberOrder;
    
    /** Filtering based on search keyword */
    if ($strMemberSearch) {
      $dbescape_search = $db->quote ( '%' . $db->escape ( $strMemberSearch, true ) . '%' );
      $query->where ( '(a.name LIKE ' . $dbescape_search . ')' );
      $arrMemberFilter [MEMBER_SEARCH] = $search1;
    }
    
    /** Filter variable for member status */
    $strMemberStatus = $mainframe->getUserStateFromRequest ( $option . MEMBER_STATUS, MEMBER_STATUS, '', 'int' );
    if (($strMemberSearch && $strMemberStatus) || (! $strMemberSearch && $strMemberStatus)) {
      $query->where ( '' );
    }
    
    /** Filter variable for member upload */
    $strMemberUpload = $mainframe->getUserStateFromRequest ( $option . MEMBER_UPLOAD, MEMBER_UPLOAD, '', 'int' );
    /** Filtering based on status */
    if ($strMemberUpload) {
      $strMemberUploadVal = ($strMemberUpload == '1') ? '1' : '0';
      $query->where ( ' b.allowupload = ' . $strMemberUploadVal );
      $arrMemberFilter [MEMBER_UPLOAD] = $strMemberUpload;
    }
    
    /** Filtering based on status */
    if ($strMemberStatus) {
      $strMemberStatusVal = ($strMemberStatus == '1') ? '0' : '1';
      $query->where ( ' a.block = ' . $strMemberStatusVal );
      $arrMemberFilter [MEMBER_STATUS] = $strMemberStatus;
    }    
    $query->order ( $db->escape ( $strMemberOrder . ' ' . $strMemberDir ) );

    $resultArray = $this->getmemberData ( $query );
    return array_merge ( $resultArray, array ('memberFilter' => $arrMemberFilter ) );
  }
  
  public function getmemberData( $query ) {
    global $mainframe;
    $db = $this->getDBO ();
    $option = JRequest::getCmd ( 'option' );
    $mainframe = JFactory::getApplication ();
    
    $db->setQuery ( $query );
    $settingupload = $db->loadObjectList ();
    $strMemberCount = count ( $settingupload );
    
    $limit      = $mainframe->getUserStateFromRequest ( $option . 'limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
    $limitstart = $mainframe->getUserStateFromRequest ( $option . LIMITSTART, LIMITSTART, 0, 'int' );
    
    /** Set pagination */
    $pageNav = new JPagination ( $strMemberCount, $limitstart, $limit );
    
    $db->setQuery ( $query, $pageNav->limitstart, $pageNav->limit );
    $memberdetails = $db->loadObjectList ();
    
    $ser_disenable = getSiteSettings ();
    $disenable = $ser_disenable ['allowupload'];
    
    /** Display the last database error message in a standard format */
    if ($db->getErrorNum ()) {
      JError::raiseWarning ( $db->getErrorNum (), $db->stderr () );
    }
    
    return array ( 'pageNav' => $pageNav, 'limitstart' => $limitstart, 'memberdetails' => $memberdetails, 'settingupload' => $disenable );
  }
  
  /**
   * Function to activate or deactivate users
   *
   * @param array $arrayIDs
   *          task array
   *          
   * @return memberActivation
   */
  public function memberActivation($arrayIDs) {
    global $mainframe;
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    if ($arrayIDs ['task'] == "publish") {
      $publish = 0;
      $msg = 'Published Successfully';
    } else {
      $publish = 1;
      $msg = 'Unpublished Successfully';
    }
    
    $cids = implode ( ',', $arrayIDs ['cid'] );
    
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      $query->clear ()->update ( $db->quoteName ( USERSTABLE ) )->set ( $db->quoteName ( 'block' ) . ' = ' . $db->quote ( $publish ) )->where ( $db->quoteName ( 'id' ) . ' IN ( ' . $cids . ' )' );
    } else {
      $query->clear ()->update ( $db->quoteName ( USERSTABLE ) )->set ( $db->quoteName ( 'block' ) . ' = ' . $db->quote ( $publish ) )->where ( $db->quoteName ( 'usertype' ) . ' <> ' . $db->quote ( 'Super Administrator' ) )->where ( $db->quoteName ( 'id' ) . ' IN ( ' . $cids . ' )' );
    }
    
    $db->setQuery ( $query );
    $db->query ();
    $link = 'index.php?option=com_contushdvideoshare&layout=memberdetails';
    $mainframe->redirect ( $link, $msg, MESSAGE );
  }
  
  /**
   * Function to activate or deactivate user upload
   *
   * @param array $arrayIDs
   *          task array
   *          
   * @return allowUpload
   */
  public function allowUpload($arrayIDs) {
    global $mainframe;
    $db = $this->getDBO ();
    
    if ($arrayIDs ['task'] == "allowupload") {
      $publish = 1;
      $msg = 'Updated Successfully';
    } else {
      $publish = 0;
      $msg = 'Updated Successfully';
    }
    
    $strMemberCount = count ( $arrayIDs ['cid'] );
    
    for($i = 0; $i < $strMemberCount; $i ++) {
      $idval = $arrayIDs ['cid'] [$i];
      $db->setQuery ( "INSERT INTO #__hdflv_user (member_id,allowupload) VALUES ($idval,$publish) 
          ON DUPLICATE KEY UPDATE member_id=" . $idval . ", allowupload=" . $publish );
      $db->query ();
    }
    
    $link = 'index.php?option=com_contushdvideoshare&layout=memberdetails';
    $mainframe->redirect ( $link, $msg, MESSAGE );
  }
}
