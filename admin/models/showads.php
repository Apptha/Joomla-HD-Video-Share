<?php
/**
 * Show ads mdoel file
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

/** Import filesystem libraries */
jimport ( 'joomla.filesystem.file' );

/**
 * Admin show ads model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModelshowads extends ContushdvideoshareModel {
  /**
   * Constructor function to declare global value
   */
  public function __construct() {
    global $mainframe, $db, $option;
    parent::__construct ();
    $mainframe = JFactory::getApplication ();
    $db = JFactory::getDBO ();
    $option = JRequest::getCmd ( 'option' );
  }
  
  /**
   * Function to get ads details
   *
   * @return showadsmodel
   */
  public function showadsmodel() {
    global $db, $mainframe, $option;
    $query = $db->getQuery ( true );
    
    /** Filter variable for ads order */
    $strFilterAdsName = $mainframe->getUserStateFromRequest ( $option . 'filter_order_ads', 'filter_order', 'adsname', 'cmd' );
    
    /** Filter variable for ads order direction */
    $strFilterAdsDir = $mainframe->getUserStateFromRequest ( $option . 'filter_order_Dir_ads', 'filter_order_Dir', 'asc', 'word' );
    
    /** Filter variable for ads name search */
    $strSearch = $mainframe->getUserStateFromRequest ( $option . 'ads_search', 'ads_search', '', TYPE_STRING );
    
    /** Filter variable for ads status search */
    $strFilterAdsStatus = $mainframe->getUserStateFromRequest ( $option . 'ads_status', 'ads_status', '', 'int' );
    
    /** Filter variable for ads type search */
    $strFilterAdsType = $mainframe->getUserStateFromRequest ( $option . 'ads_type', 'ads_type', '', TYPE_STRING );
    $search1 = $strSearch;
    /** Call component helper for ads page */
    $strSearchAds = phpSlashes ( $strSearch );
    
    /** Pagination starts here */
    $limit = $mainframe->getUserStateFromRequest ( $option . 'limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
    $limitstart = $mainframe->getUserStateFromRequest ( $option . 'limitstart', 'limitstart', 0, 'int' );
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'published', 'adsname', 'filepath', 
        'postvideopath', 'targeturl', 'clickurl', 'impressionurl', 'impressioncounts', 'clickcounts',
        'adsdesc', 'typeofadd', 'imaaddet' ) ) )->from ( $db->quoteName ( ADSTABLE ) );
    
    /** Filtering based on search keyword */
    if ($strSearchAds) {
      $dbescape_search = $db->quote ( '%' . $db->escape ( $strSearchAds, true ) . '%' );
      $query->where ( 'adsname LIKE ' . $dbescape_search );
      $arrAdsFilter ['ads_search'] = $search1;
    }
    
    /** Filtering based on status */
    if ($strFilterAdsStatus) {
      if ($strFilterAdsStatus == 1) {
        $strFilterStatusval = 1;
      } elseif ($strFilterAdsStatus == 2) {
        $strFilterStatusval = 0;
      } else {
        $strFilterStatusval = - 2;
      }
      
      $query->where ( ' published = ' . $strFilterStatusval );
      $arrAdsFilter ['ads_status'] = $strFilterAdsStatus;
    } else {
      $query->where ( ' published != -2' );
    }
    
    /** Filtering based on ads type */
    if ($strFilterAdsType) {
      switch ($strFilterAdsType) {
        case 1:
          $strFilterTypeval = 'prepost';
          break;
        case 2:
          $strFilterTypeval = 'mid';
          break;
        case 3:
          $strFilterTypeval = 'ima';
          break;
        default: break;
      }
      
      $query->where ( $db->quoteName ( 'typeofadd' ) . ' = ' . $db->quote ( $strFilterTypeval ) );
      $arrAdsFilter ['ads_type'] = $strFilterAdsType;
    }
    
    /** Assign filter variables */
    $arrAdsFilter ['filter_order_Dir_ads'] = $strFilterAdsDir;
    $arrAdsFilter ['filter_order_ads'] = $strFilterAdsName;
    $query->order ( $db->escape ( $strFilterAdsName . ' ' . $strFilterAdsDir ) );
    $db->setQuery ( $query );
    $arrAdsCount = $db->loadObjectList ();
    
    /** Set count for pagination */
    $strAdsCount = count ( $arrAdsCount );
    
    /** Set pagination */
    $pageNav = new JPagination ( $strAdsCount, $limitstart, $limit );
    $db->setQuery ( $query, $pageNav->limitstart, $pageNav->limit );
    $arrAds = $db->loadObjectList ();
    
    /** Display the last database error message in a standard format */
    if ($db->getErrorNum ()) {
      JError::raiseWarning ( $db->getErrorNum (), $db->stderr () );
    }
    
    return array ( 'adsList' => $arrAds, 'adsFilter' => $arrAdsFilter, 'limitstart' => $limitstart, 'pageNav' => $pageNav );
  }
  
  /**
   * Function to save ads details
   *
   * @param string $task
   *          task for ad
   *          
   * @return saveads
   */
  public function saveads ( $task ) {
    global $db;
    $query = $db->getQuery ( true );
    $objAdTable = JTable::getInstance ( 'ads', 'Table' );
    
    /** Fetch the selected row id */
    $cid = JRequest::getVar ( 'cid', array ( 0  ), '', 'array' );
    $id = $cid [0];
    $objAdTable->load ( $id );
    $arrFormData = JRequest::get ( 'POST' );
    $typeofadd = JRequest::getVar ( 'typeofadd', 'post' );
    
    /** Get IMA ad details and serialize data */
    if ($typeofadd == "ima") {
      $imaaddetail = array ( 'imaadtype' => $arrFormData ['imaadtype'], 'publisherId' => $arrFormData ['publisherId'],
      'contentId' => $arrFormData ['contentId'], 'channels' => $arrFormData ['channels'], 'imaadpath' => $arrFormData ['imaadpath']  );
      $arrFormData ['imaaddet'] = serialize ( $imaaddetail );
    } else {
      $arrFormData ['imaaddet'] = '';
    }
    
    /** Binds given input with table columns */
    if (! $objAdTable->bind ( $arrFormData )) {
      JError::raiseWarning ( 500, $objAdTable->getError () );
    }
    
    /** Ad description and ad name to table */
    $objAdTable->adsdesc = JRequest::getVar ( 'adsdesc', '', 'post', TYPE_STRING, JREQUEST_ALLOWRAW );
    $objAdTable->adsname = JRequest::getVar ( 'adsname', '', 'post', TYPE_STRING, JREQUEST_ALLOWRAW );
    
    /** Stores the input into table into appropriate columns */
    $strFileOption = $arrFormData ['fileoption'];    
    if (! $objAdTable->store ()) {
      JError::raiseWarning ( 500, $objAdTable->getError () );
    }
    
    /** Checks whether the given column available in the table or not */
    $objAdTable->checkin ();    
    /** Fetch the last added id */
    $strAdId = $objAdTable->id;
    
    /** If File Path option Url means, the below code will work */
    if ($strFileOption == "Url") {
      $postvideopath = $arrFormData ['posturl-value'];
      $query->clear ()->update ( $db->quoteName ( ADSTABLE ) )->set ( $db->quoteName ( 'filepath' ) . ' = ' . $db->quote ( $strFileOption ) )->set ( $db->quoteName ( 'postvideopath' ) . ' = ' . $db->quote ( $postvideopath ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $strAdId ) );
      $db->setQuery ( $query );
      $db->query ();
    }    
    /** Upload method */
    if ($strFileOption == 'File') {
      $normal_video = $arrFormData ['normalvideoform-value'];
      $video_name = explode ( "uploads/", $normal_video );
      $vpath = VPATH . "/";
      $file_video = '';
      
      if (isset ( $video_name [1] )) {
        $file_video = $video_name [1];
      }      
      if ($file_video) {
        $ext = $this->getFileExt ( $file_video );
        $strTmpPath = FVPATH . "/images/uploads/" . $file_video;
        $strTargetPath = $vpath . $strAdId . "_ads" . "." . $ext;
        $file_name = $strAdId . "_ads" . "." . $ext;
        
        if (JFile::exists ( $strTargetPath )) {
          JFile::delete ( $strTargetPath );
        }        
        rename ( $strTmpPath, $strTargetPath );
        
        /** Query to update video path and fileoption */
        $strFileOption = "File";
        $query->clear ()->update ( $db->quoteName ( ADSTABLE ) )->set ( $db->quoteName ( 'filepath' ) . ' = ' . $db->quote ( $strFileOption ) )->set ( $db->quoteName ( 'postvideopath' ) . ' = ' . $db->quote ( $file_name ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $strAdId ) );
        $db->setQuery ( $query );
        $db->query ();
      }
    }    
    if ($strFileOption == '') {
      /** Query to update file path */
      $strFileOption = '';
      $query->clear ()->update ( $db->quoteName ( ADSTABLE ) )->set ( $db->quoteName ( 'filepath' ) . ' = ' . $db->quote ( $strFileOption ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $strAdId ) );
      $db->setQuery ( $query );
      $db->query ();
    }    
   
    $this->redirectMessage ( $task, $strAdId );
  }
  
  /**
   * Function to set redirect link and message and redirect to the link
   * 
   * @param unknown $task
   * @param unknown $strAdId
   */
  public function redirectMessage ( $task, $strAdId ) {
    global $mainframe, $option;
    
    /** Function to set redirect URL for SAVE and APPLY action */
    switch ($task) {
      case 'applyads' :
        $link = 'index.php?option=' . $option . '&layout=ads&task=editads&cid[]=' . $strAdId;
        break;
      case 'saveads' :
      default :
        $link = 'index.php?option=' . $option . '&layout=ads';
        break;
    }
    $msg = SAVE_SUCCESS;
    /** Set to redirect */
    $mainframe->redirect ( $link, $msg, MESSAGE );
  }
  
  /**
   * Function to get file extension
   *
   * @param string $strFileName
   *          filename
   *          
   * @return getFileExt
   */
  public function getFileExt($strFileName) {
    $strFileName = strtolower ( $strFileName );
    
    return JFile::getExt ( $strFileName );
  }
  
  /**
   * Function to publish and unpublish ads
   *
   * @param string $ads
   *          action type
   *          
   * @return statusChange
   */
  public function statusChange($ads) {
    global $mainframe;
    
    if ($ads ['task'] == 'publish') {
      $publish = 1;
      $msg = 'Published Successfully';
    } elseif ($ads ['task'] == 'trash') {
      $publish = - 2;
      $msg = 'Trashed Successfully';
    } else {
      $publish = 0;
      $msg = 'Unpublished Successfully';
    }
    
    $cids = $ads ['cid'];
    $adsTable = JTable::getInstance ( 'ads', 'Table' );
    $adsTable->publish ( $cids, $publish );
    $link = 'index.php?option=com_contushdvideoshare&layout=ads';
    $mainframe->redirect ( $link, $msg, MESSAGE );
  }
}
