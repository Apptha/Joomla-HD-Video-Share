<?php
/**
 * Add videos model file
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
 * Admin add videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModeladdvideos extends ContushdvideoshareModel {
  /**
   * Function to fetch categories,ads and adding new video
   *
   * @return addvideosmodel
   */
  public function addvideosmodel() {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query to get ffmpegpath & file max upload size from #__hdflv_player_settings */
    $query->clear ()->select ( $db->quoteName ( array ( 'uploadmaxsize' ) ) )->from ( $db->quoteName ( '#__hdflv_player_settings' ) );
    $db->setQuery ( $query );
    $rs_playersettings = $db->loadObjectList ();
    
    /** To get total no.of records */
    if (count ( $rs_playersettings ) > 0) {
      /** To set max file size in php.ini */
      ini_set ( 'upload_max_filesize', $rs_playersettings [0]->uploadmaxsize . "M" );
      
      /** To assign value to the php.ini file */                                     
      /** To set max execution_time in php.ini */
      ini_set ( 'max_execution_time', 3600 ); 
      
      /** max input time 5 Min */
      ini_set ( 'max_input_time', 3600 );
      $upload_maxsize = $rs_playersettings [0]->uploadmaxsize;
    }
    
    $rs_editupload = JTable::getInstance ( 'adminvideos', 'Table' );
    
    /** Query to fetch category list */
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'member_id', 'category', 'seo_category', 'parent_id', 'ordering' 
    ) ) )->from ( $db->quoteName ( '#__hdflv_category' ) )->where ( $db->quoteName ( PUBLISH ) . '= 1' )->order ( 'category ASC' );
    $db->setQuery ( $query );
    $rs_play = $db->loadObjectList ();
    
    /** Query to fetch pre/post roll ads */
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'adsname') ) )->from ( $db->quoteName ( '#__hdflv_ads' ) )->where ( $db->quoteName ( PUBLISH ) . '= 1' )->where ( 'typeofadd != \'mid\'' )->where ( 'typeofadd != \'ima\'' )->order ( 'adsname ASC' );
    $db->setQuery ( $query );
    $rs_ads = $db->loadObjectList ();
    
    /** Query to fetch mid roll ads */
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'adsname' ) ) )->from ( $db->quoteName ( '#__hdflv_ads' ) )->where ( $db->quoteName ( PUBLISH ) . '= 1' )->where ( 'typeofadd = \'mid\'' )->order ( 'adsname ASC' );
    $db->setQuery ( $query );
    $rs_midads = $db->loadObjectList ();
    
    if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
      $strTable = '#__viewlevels';
      $strName = 'title';
    } else {
      $strTable = '#__groups';
      $strName = 'name';
    }
    
    /** Query to fetch user groups */
    $query->clear ()->select ( array ( 'id', $strName . ' AS title') )->from ( $db->quoteName ( $strTable ) )->order ( 'id ASC' );
    $db->setQuery ( $query );
    $usergroups = $db->loadObjectList ();
    
    /** Get user group from helper */
    $ugp = getUserGroup ();
    return array ( 'upload_maxsize' => $upload_maxsize, 'rs_play' => $rs_play, 'rs_editupload' => $rs_editupload, 'rs_ads' => $rs_ads, 'rs_midads' => $rs_midads, 'user_groups' => $usergroups, 'user_group_id' => $ugp );
  }
}
