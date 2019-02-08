<?php
/**
 * Configxml model for HD Video Share
 *
 * This file is to fetch player configuration details from database
 * and pass values to player 
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
 * Configxml model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideoshareconfigxml extends ContushdvideoshareModel {
  /**
   * Function to get player settings
   *
   * @return array
   */
  public function configgetrecords() {    
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** Get player settings from db */
    $query->select ( array ( 'player_colors', 'player_icons', 'player_values', 'logopath' ) )->from ( '#__hdflv_player_settings' );
    $db->setQuery ( $query );
    $settingsrows = $db->loadObjectList ();
    /** Call function to generate config xml data */
    $this->configxml ( $settingsrows );
  }
  
  /**
   * Function to generate config xml
   *
   * @param array $settingsrows
   *          Setting in array format
   *          
   * @return string
   */
  public function configxml($settingsrows) {
    /** Variable initailzation for config xml */
    $googleanalyticsID = $IMAAds_path = $login_page_url = $player_colors = $downloadpath = $player_icons = $player_values = $subTitleBgColor = $subTitleColor = '';
    $skinVisible = VS_FALSE;
    
    /** Get player settings and unserialize data */
    $player_colors = unserialize ( $settingsrows [0]->player_colors );
    $player_icons = unserialize ( $settingsrows [0]->player_icons );
    $player_values = unserialize ( $settingsrows [0]->player_values );
    
    $base = JURI::base ();
    
    /** Get video buffer duration */
    $buffer = $player_values ['buffer'];
    
    /** Get normal screen scale ratio */
    $normalscale = $player_values ['normalscale'];
    
    /** Get full screen scale ratio */
    $fullscreenscale = $player_values ['fullscreenscale'];
    
    /** Get player volume */
    $volume = $player_values ['volume'];
    
    /** Get logo alpha */
    $logoalpha = $player_values ['logoalpha'];
        
    $skin_opacity = $this->checkPlayerValueExists ($player_values, 'skin_opacity');
    $subTitleFontFamily = $this->checkPlayerValueExists ($player_values, 'subTitleFontFamily');
    $subTitleFontSize = $this->checkPlayerValueExists ($player_values, 'subTitleFontSize');
    
    /** Enable/Disable related videos on the player */
    $playlist = $this->checkPlayerIconExists ($player_values, 'related_videos' );
    
    /** Enable/Disable skin */
    if ($player_icons ['skinvisible'] == 0) {
      $skinVisible = VS_TRUE;
    }
    
    /** Get login url for player login button */
    if (! empty ( $player_icons ['login_page_url'] )) {
      $login_page_url = $player_icons ['login_page_url'];
    }
    
    /** Enable/Disable video autoplay */
    $autoplay = $this->checkPlayerIconExists ($player_icons, 'autoplay' );
    
    /** Enable/Disable video autoplay */
    $emailenable = $this->checkPlayerIconExists ($player_icons, 'emailenable' );
    
    /** Enable/Disable zoom option */
    $zoom = $this->checkPlayerIconExists ($player_icons, 'zoom' );
    
    /** Enable/Disable fullscreen option */
    $fullscreen = $this->checkPlayerIconExists ($player_icons, 'fullscreen' );
    
    /** Enable/Disable embed option */
    $embedVisible = $this->checkPlayerIconExists ($player_icons, 'embedVisible' );
    
    /** Enable/Disable download option */
    $enabledownload =  $this->checkPlayerIconExists ($player_icons, 'enabledownload' );
    
    /** Enable/Disable volume control */
    $volumecontrol =  $this->checkPlayerIconExists ($player_icons, 'volumecontrol' );
    
    /** Enable/Disable Progress control */
    $progressControl = $this->checkPlayerIconExists ($player_icons, 'progressControl' );
    
    /** Enable/Disable default preview image option */
    $imageDefault = $this->checkPlayerIconExists ($player_icons, 'imageDefault' );
    
    /** Enable/Disable skin auto hide option */
    $skin_autohide =  $this->checkPlayerIconExists ($player_icons, 'skin_autohide' );
    
    /** Enable/Disable timer option */
    $timer =  $this->checkPlayerIconExists ($player_icons, 'timer' );
    
    /** Enable/Disable description on the player */
    $showTag = $this->checkPlayerIconExists ($player_icons, 'showTag' );
    
    /** Enable/Disable share option */
    $share = $this->checkPlayerIconExists ($player_icons, 'shareurl' );
    
    /** Enable/Disable playlist autoplay option */
    $playlist_autoplay = $this->checkPlayerIconExists ($player_icons, 'playlist_autoplay' );
    
    /** Enable/Disable hddefault option */
    $hddefault = $this->checkPlayerIconExists ($player_icons, 'hddefault' );
    
    /** Enable/Disable google tracker option */
    $googleana_visible = $this->checkPlayerIconExists ($player_icons, 'googleana_visible' );
    
    /** Get tracker code */
    if ($googleana_visible == VS_TRUE) {
      $googleanalyticsID = $player_values ['googleanalyticsID'];
    }
    
    /** Enable/Disable playlist open option */
    $playlist_open = $this->checkPlayerIconExists ($player_icons, 'playlist_open' );
    
    /** Enable/Disable postroll ad option */
    $postrollads = $this->checkPlayerIconExists ($player_icons, 'postrollads' );
    
    /** Enable/Disable preroll ad option */
    $prerollads = $this->checkPlayerIconExists ($player_icons, 'prerollads' );
    
    /** Enable/Disable ad skip option */
    $adsSkip = $this->checkPlayerIconExists ($player_icons, 'adsSkip' );
    
    /** Enable/Disable modroll ad option */
    $midrollads = $this->checkPlayerIconExists ($player_icons, 'midrollads' );
    
    /** Enable/Disable ima ad option */
    $IMAAds = $this->checkPlayerIconExists ($player_icons, 'imaads' );
        
    /** Get skin path */
    $skin = $base . "components/com_contushdvideoshare/hdflvplayer/skin/skin_hdflv_white.swf";
    
    /** Get IMA ad path */
    if ($IMAAds == VS_TRUE) {
      $IMAAds_path = JURI::base () . "index.php?option=com_contushdvideoshare&view=imaadxml";
    }
    
    /** Get Playlist xml path */
    $playlistxml = $this->generatePlaylistXMLPath ();
    
    /** Ad xml path */
    $adsxml = $base . "index.php?option=com_contushdvideoshare&view=adsxml";
    
    /** Logo path for purchased user */
    $logopath = $base . "components/com_contushdvideoshare/videos/" . $settingsrows [0]->logopath;
    
    /** Language xml path */
    $languagexml = $base . "index.php?option=com_contushdvideoshare&view=languagexml";
    
    /** Mid roll xml path */
    $midrollxml = $base . "index.php?option=com_contushdvideoshare&view=midrollxml";
    $baseUrl1 = parse_url ( $base );
    
    /** Generate base url */
    $baseUrl1 = $baseUrl1 ['scheme'] . '://' . $baseUrl1 ['host'];
    
    /** Send email in player */
    $emailpath = $base . 'index.php?option=com_contushdvideoshare&task=emailuser';
    
    /** Add http in URL if not exist */   
    $logotarget = $this->getLogoTarget( $player_values, $base );
    
    $imaadbegin = 5;
    if (! empty ( $player_values ['imaadbegin'] )) {
      $imaadbegin = $player_values ['imaadbegin'];
    }
    
    /** Get player and subtitle colors */
    $stagecolor = $this->setPlayerColor ( $player_values, 'stagecolor' ); 
    $subTitleColor = $this->setPlayerColor ( $player_values, 'subTitleColor' );
    $subTitleBgColor = $this->setPlayerColor ( $player_values, 'subTitleBgColor' );  
          
    /** Get player icon colors */
    $sharepanel_up_BgColor = $this->setPlayerColor ( $player_colors, 'sharepanel_up_BgColor' );    
    $sharepanel_down_BgColor = $this->setPlayerColor ( $player_colors, 'sharepanel_down_BgColor' );    
    $sharepaneltextColor = $this->setPlayerColor ( $player_colors, 'sharepaneltextColor' ); 
    $sendButtonColor = $this->setPlayerColor ( $player_colors, 'sendButtonColor' );     
    $sendButtonTextColor = $this->setPlayerColor ( $player_colors, 'sendButtonTextColor' );     
    $textColor = $this->setPlayerColor ( $player_colors, 'textColor' );    
    $skinBgColor = $this->setPlayerColor ( $player_colors, 'skinBgColor' );      
    $seek_barColor = $this->setPlayerColor ( $player_colors, 'seek_barColor' );     
    $buffer_barColor = $this->setPlayerColor ( $player_colors, 'buffer_barColor' );    
    $skinIconColor = $this->setPlayerColor ( $player_colors, 'skinIconColor' );
    $pro_BgColor = $this->setPlayerColor ( $player_colors, 'pro_BgColor' );    
    $playButtonColor = $this->setPlayerColor ( $player_colors, 'playButtonColor' );
    $playButtonBgColor = $this->setPlayerColor ( $player_colors, 'playButtonBgColor' );    
    $playerButtonColor = $this->setPlayerColor ( $player_colors, 'playerButtonColor' );    
    $playerButtonBgColor = $this->setPlayerColor ( $player_colors, 'playerButtonBgColor' );     
    $relatedVideoBgColor = $this->setPlayerColor ( $player_colors, 'relatedVideoBgColor' );     
    $scroll_barColor = $this->setPlayerColor ( $player_colors, 'scroll_barColor' );    
    $scroll_BgColor = $this->setPlayerColor ( $player_colors, 'scroll_BgColor' ); 
          
    /** Generate config xml here */
    ob_clean ();    
    header ( "content-type: text/xml" );
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<config> <stagecolor>' . $stagecolor . '</stagecolor> <autoplay>' . $autoplay . '</autoplay> <buffer>' . $buffer . '</buffer> 
        <volume>' . $volume . '</volume> <normalscale>' . $normalscale . '</normalscale> <fullscreenscale>' . $fullscreenscale . '</fullscreenscale> 
        <logoalpha>' . $logoalpha . '</logoalpha> 
        <logoalign>' . $player_values ['logoalign'] . '</logoalign> <logo_target>' . $logotarget . '</logo_target> 
        <sharepanel_up_BgColor>' . $sharepanel_up_BgColor . '</sharepanel_up_BgColor> <sharepanel_down_BgColor>' . $sharepanel_down_BgColor . '</sharepanel_down_BgColor> 
        <sharepaneltextColor>' . $sharepaneltextColor . '</sharepaneltextColor> <sendButtonColor>' . $sendButtonColor . '</sendButtonColor> 
        <sendButtonTextColor>' . $sendButtonTextColor . '</sendButtonTextColor> <textColor>' . $textColor . '</textColor> <skinBgColor>' . $skinBgColor . '</skinBgColor> 
        <seek_barColor>' . $seek_barColor . '</seek_barColor> <buffer_barColor>' . $buffer_barColor . '</buffer_barColor> <skinIconColor>' . $skinIconColor . '</skinIconColor> 
        <pro_BgColor>' . $pro_BgColor . '</pro_BgColor> <playButtonColor>' . $playButtonColor . '</playButtonColor> 
        <playButtonBgColor>' . $playButtonBgColor . '</playButtonBgColor> <playerButtonColor>' . $playerButtonColor . '</playerButtonColor>
        <playerButtonBgColor>' . $playerButtonBgColor . '</playerButtonBgColor> <relatedVideoBgColor>' . $relatedVideoBgColor . '</relatedVideoBgColor> 
        <scroll_barColor>' . $scroll_barColor . '</scroll_barColor> <scroll_BgColor>' . $scroll_BgColor . '</scroll_BgColor> 
        <skin>' . $skin . '</skin> <skin_autohide>' . $skin_autohide . '</skin_autohide> <languageXML>' . $languagexml . '</languageXML> 
        <registerpage>' . $login_page_url . '</registerpage> <playlistXML>' . $playlistxml . '</playlistXML> <adXML>' . $adsxml . '</adXML> 
        <preroll_ads>' . $prerollads . '</preroll_ads> <postroll_ads>' . $postrollads . '</postroll_ads> <midrollXML>' . $midrollxml . '</midrollXML> 
        <midroll_ads>' . $midrollads . '</midroll_ads> <playlist_open>' . $playlist_open . '</playlist_open> 
        <showPlaylist>' . $playlist . '</showPlaylist> <HD_default>' . $hddefault . '</HD_default> <shareURL>' . $emailpath . '</shareURL> <embed_visible>' . $embedVisible . '</embed_visible> 
        <Download>' . $enabledownload . '</Download> <downloadUrl>' . $downloadpath . '</downloadUrl> <adsSkip>' . $adsSkip . '</adsSkip> 
        <adsSkipDuration>' . $player_values ['adsSkipDuration'] . '</adsSkipDuration> <ads_start_time>' . $imaadbegin . '</ads_start_time> 
        <relatedVideoView>' . $player_values ['relatedVideoView'] . '</relatedVideoView> <imaAds>' . $IMAAds . '</imaAds> <imaAdsXML>' . $IMAAds_path . '</imaAdsXML> 
        <trackCode>' . $googleanalyticsID . '</trackCode> <showTag>' . $showTag . '</showTag> <timer>' . $timer . '</timer> <zoomIcon>' . $zoom . '</zoomIcon> 
        <email>' . $emailenable . '</email> <shareIcon>' . $share . '</shareIcon> <fullscreen>' . $fullscreen . '</fullscreen> <volumecontrol>' . $volumecontrol . '</volumecontrol> 
        <playlist_auto>' . $playlist_autoplay . '</playlist_auto> <progressControl>' . $progressControl . '</progressControl> <imageDefault>' . $imageDefault . '</imageDefault> 
        <skinVisible>' . $skinVisible . '</skinVisible> <skin_opacity>' . $skin_opacity . '</skin_opacity> <subTitleColor>' . $subTitleColor . '</subTitleColor> 
        <subTitleBgColor>' . $subTitleBgColor . '</subTitleBgColor> <subTitleFontFamily>' . $subTitleFontFamily . '</subTitleFontFamily> 
        <subTitleFontSize>' . $subTitleFontSize . '</subTitleFontSize> </config>';    
    exitAction ( '' );
    /** Config xml ends */
  }
  
  /**
   * Function to get logo target
   * 
   * @param unknown $player_values
   * @return Ambigous <unknown, string>
   */
  public function getLogoTarget ( $player_values, $base ) {
    /** Get logo target from settings */
    $logotarget =  $player_values ['logourl'] ;
    /** Check logo target is empty */
    if (empty($logotarget)) {
      /** Assign base url to logo target */
      $logotarget = $base;
    }
    /** Check logo target is found with http or https */
    if (! preg_match ( "~^(?:f|ht)tps?://~i", $player_values ['logourl'] )) {
      $logotarget = "http://" . $player_values ['logourl'];
    }
    /** Return logo target */
    return $logotarget;
  }
  /** 
   * Function to generate playlist xml path
   * 
   * @return string
   */
  public function generatePlaylistXMLPath () {
    $playlistxml = $adminviewbase = $adminview = '';
    $base = JUri::base();
    $adminview = JRequest::getString ( 'adminview' );    
    /** Check admin view is exists */
    if ($adminview) {
      $adminviewbase = '&adminview=true';
    } 
    
    /** Playlist xml path */
    if (JRequest::getString ( 'mid' ) == 'playerModule') {
      $playlistxml = $base . "index.php?option=com_contushdvideoshare&view=playxml&mid=playerModule&id=" . JRequest::getInt ( 'id' ) . "&catid=" . JRequest::getInt ( 'catid' ) . $adminviewbase;
    } elseif (JRequest::getInt ( 'catid' )) {
      $playlistxml = $base . "index.php?option=com_contushdvideoshare&view=playxml&id=" . JRequest::getInt ( 'id' ) . "&catid=" . JRequest::getInt ( 'catid' ) . $adminviewbase;
    } elseif(JRequest::getInt ( 'playid' ) && JRequest::getInt ( 'id' )) {
    	$playlistxml = $base . "index.php?option=com_contushdvideoshare&view=playxml&id=" . JRequest::getInt ( 'id' ) . "&playid=".JRequest::getInt ( 'playid' );
    } elseif (JRequest::getInt ( 'id' )) {
      $playlistxml = $base . "index.php?option=com_contushdvideoshare&view=playxml&id=" . JRequest::getInt ( 'id' ) . $adminviewbase;
    } else {
      $playlistxml = $base . "index.php?option=com_contushdvideoshare&view=playxml&featured=true";
    }
    return $playlistxml;
  }
  
  /**
   * Function is used to check player color and return
   * 
   * @param unknown $player_colors
   * @param unknown $attribute
   * @return string
   */
  public function setPlayerColor ( $player_colors, $attribute ) {
    $color = '';
    /** Check value is exists */
    if (isset ( $player_colors [ $attribute ] )) {
      $color = $player_colors [ $attribute ];
      /** Check value have 0x else add 0x to the color value */
      if (! empty ( $color ) && strpos ( $color, "0x" ) === false) {
        return "0x" . $color;
      }
    }
  }
  
  public function checkPlayerValueExists ( $player_values, $attribute) {
    $value = '';
    if (isset ( $player_values [ $attribute ] )) {
      $value = $player_values [ $attribute ];
    }
    return $value;
  }
  
  /**
   * Function is used to check player values is exists   
   * 
   * @param unknown $player_icons
   * @param unknown $attribute
   * @return string
   */
  public function checkPlayerIconExists ($player_icons, $attribute ) {
    /** Enable/Disable postroll ad option */
    if ($player_icons [ $attribute ] == 1) {
      return VS_TRUE;
    } else {
      return VS_FALSE;
    } 
  }
  /** Configxml model class ends */
}