<?php
/**
 * Player settings model
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

/** Import filesystem libraries */
jimport ( 'joomla.filesystem.file' );

/**
 * Admin settings model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModelsettings extends ContushdvideoshareModel {
  /**
   * Get player settings
   *
   * @return showplayersettings
   */
  public function showplayersettings() {
    /** Get db connection to fetch player settings value */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query to fetch player settings */
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'player_colors', 'player_icons', 'player_values', 'logopath' ) ) )->from ( $db->quoteName ( '#__hdflv_player_settings' ) );
    $db->setQuery ( $query );
    return $db->loadObjectList ();
  }
  
  /**
   * Function to save player settings
   *
   * @return saveplayersettings
   */
  public function saveplayersettings() {
    $option = JRequest::getCmd ( 'option' );
    $arrFormData = JRequest::get ( 'post' );
    $mainframe = JFactory::getApplication ();
    
    /** Get the object for settings */
    $objPlayerSettingsTable = JTable::getInstance ( 'settings', 'Table' );
    $id = 1;
    $objPlayerSettingsTable->load ( $id );
    
    /** Get Player colors and serialize data */
    $player_color = array ( 'sharepanel_up_BgColor' => $arrFormData ['sharepanel_up_BgColor'], 'sharepanel_down_BgColor' => $arrFormData ['sharepanel_down_BgColor'],
        'sharepaneltextColor' => $arrFormData ['sharepaneltextColor'], 'sendButtonColor' => $arrFormData ['sendButtonColor'],
        'sendButtonTextColor' => $arrFormData ['sendButtonTextColor'], 'textColor' => $arrFormData ['textColor'], 'skinBgColor' => $arrFormData ['skinBgColor'],
        'seek_barColor' => $arrFormData ['seek_barColor'], 'buffer_barColor' => $arrFormData ['buffer_barColor'], 'skinIconColor' => $arrFormData ['skinIconColor'],
        'pro_BgColor' => $arrFormData ['pro_BgColor'], 'playButtonColor' => $arrFormData ['playButtonColor'], 'playButtonBgColor' => $arrFormData ['playButtonBgColor'],
        'playerButtonColor' => $arrFormData ['playerButtonColor'], 'playerButtonBgColor' => $arrFormData ['playerButtonBgColor'],
        'relatedVideoBgColor' => $arrFormData ['relatedVideoBgColor'], 'scroll_barColor' => $arrFormData ['scroll_barColor'], 'scroll_BgColor' => $arrFormData ['scroll_BgColor'] );
    $arrFormData ['player_colors'] = serialize ( $player_color );
    
    /** Get Player values and serialize data */
    $player_values = array ( 'buffer' => $arrFormData ['buffer'], 'width' => $arrFormData ['width'], 'height' => $arrFormData ['height'],
        'normalscale' => $arrFormData ['normalscale'], 'fullscreenscale' => $arrFormData ['fullscreenscale'], 'nrelated' => 8,
        'volume' => $arrFormData ['volume'], 'ffmpegpath' => $arrFormData ['ffmpegpath'], 'skin_opacity' => $arrFormData ['skin_opacity'],
        'subTitleColor' => $arrFormData ['subTitleColor'], 'subTitleBgColor' => $arrFormData ['subTitleBgColor'], 'stagecolor' => $arrFormData ['stagecolor'], 
        'subTitleFontFamily' => $arrFormData ['subTitleFontFamily'], 'subTitleFontSize' => $arrFormData ['subTitleFontSize'],
        'licensekey' => $arrFormData ['licensekey'], 'logourl' => $arrFormData ['logourl'], 'logoalpha' => $arrFormData ['logoalpha'],
        'logoalign' => $arrFormData ['logoalign'], 'adsSkipDuration' => $arrFormData ['adsSkipDuration'], 'imaadbegin' => $arrFormData ['imaadbegin'],
        'googleanalyticsID' => $arrFormData ['googleanalyticsID'], 'midbegin' => $arrFormData ['midbegin'], 'midinterval' => $arrFormData ['midinterval'],
        'related_videos' => $arrFormData ['related_videos'], 'relatedVideoView' => $arrFormData ['relatedVideoView'], 'login_page_url' => $arrFormData ['login_page_url'] );
    $arrFormData ['player_values'] = serialize ( $player_values );
    
    /** Get player icon options and serialize data */
    $googleana_visible = 0;
    if (isset ( $arrFormData ['googleana_visible'] )) {
      $googleana_visible = $arrFormData ['googleana_visible'];
    } 
    
    $adsSkip = 0;
    if (isset ( $arrFormData ['adsSkip'] )) {
      $adsSkip = $arrFormData ['adsSkip'];
    }
    
    $imaads = 0;
    if (isset ( $arrFormData ['imaads'] )) {
      $imaads = $arrFormData ['imaads'];
    } 
    
    $postrollads = 0;
    if (isset ( $arrFormData ['postrollads'] )) {
      $postrollads = $arrFormData ['postrollads'];
    } 
    
    $prerollads = 0;
    if (isset ( $arrFormData ['prerollads'] )) {
      $prerollads = $arrFormData ['prerollads'];
    } 
    
    $player_icons = array ( 'autoplay' => $arrFormData ['autoplay'], 'playlist_autoplay' => $arrFormData ['playlist_autoplay'],
        'playlist_open' => $arrFormData ['playlist_open'], 'skin_autohide' => $arrFormData ['skin_autohide'], 'fullscreen' => $arrFormData ['fullscreen'],
        'zoom' => $arrFormData ['zoom'], 'timer' => $arrFormData ['timer'], 'showTag' => $arrFormData ['showTag'], 'shareurl' => $arrFormData ['shareurl'],
        'emailenable' => $arrFormData ['emailenable'], 'login_page_url' => $arrFormData ['login_page_url'], 'embedVisible' => $arrFormData ['embedVisible'],
        'progressControl' => $arrFormData ['progressControl'], 'skinvisible' => $arrFormData ['skinvisible'], 'hddefault' => $arrFormData ['hddefault'],
        'imageDefault' => $arrFormData ['imageDefault'], 'enabledownload' => $arrFormData ['enabledownload'], 'prerollads' => $prerollads,
        'postrollads' => $postrollads, 'imaads' => $imaads, 'volumecontrol' => $arrFormData ['volumecontrol'], 'adsSkip' => $adsSkip,
        'midrollads' => $arrFormData ['midrollads'], 'midbegin' => $arrFormData ['midbegin'], 'midrandom' => $arrFormData ['midrandom'],
        'midadrotate' => $arrFormData ['midadrotate'], 'googleana_visible' => $googleana_visible ,'iframeVisible' => $arrFormData ['iframeVisible'],);
    $arrFormData ['player_icons'] = serialize ( $player_icons );
    
    /** For logo image */
    $logo = JRequest::getVar ( 'logopath', null, 'files', 'array' );
    $strRes = $this->logoImageValidation ( $logo ['name'] );
    
    if ($logo ['name'] && $strRes) {
      $strTargetPath = VPATH . DS;
      
      /** Clean up filename to get rid of strange characters like spaces etc */
      $strLogoName = JFile::makeSafe ( $logo ['name'] );
      $strTargetLogoPath = $strTargetPath . $logo ['name'];      
      
      /** To store images to a directory called components/com_contushdvideoshare/videos */
      JFile::upload ( $logo ['tmp_name'], $strTargetLogoPath );
      $arrFormData ['logopath'] = $strLogoName;
    }
    
    /** Bind data to the databse table object */
    if (! $objPlayerSettingsTable->bind ( $arrFormData )) {
      JError::raiseWarning ( 500, JText::_ ( $objPlayerSettingsTable->getError () ) );
    }    
    /** Store the data into the settings table */
    if (! $objPlayerSettingsTable->store ()) {
      JError::raiseWarning ( 500, JText::_ ( $objPlayerSettingsTable->getError () ) );
    }
    
    /** Set to page redirect */
    $link = 'index.php?option=' . $option . '&layout=settings';
    $mainframe->redirect ( $link, SAVE_SUCCESS, MESSAGE );
  }
  
  /**
   * Function to check image type
   *
   * @param string $logoname
   *          logoname
   *          
   * @return logoImageValidation
   */
  public function logoImageValidation($logoname) {
    /** Get file extension */
    $ext = $this->getFileExt ( $logoname );
    
    if ($ext) {
      /** To check file type */
      if (($ext != "png") && ($ext != "gif") && ($ext != "jpeg") && ($ext != "jpg")) {
        JError::raiseWarning ( 500, JText::_ ( 'File Extensions : Allowed Extensions for image file [ jpg , jpeg , png ] only' ) );
        
        return false;
      } else {
        return true;
      }
    }
  }
  
  /**
   * Function to get file extension
   *
   * @param string $filename
   *          filename
   *          
   * @return getFileExt
   */
  public function getFileExt($filename) {
    /** Convert file name into lower case */
    $filename = strtolower ( $filename );
    /** REturn file extension */
    return JFile::getExt ( $filename );
  }
}
