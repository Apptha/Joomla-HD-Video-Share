<?php
/**
 * IMA ad model for HD Video Share
 *
 * This file is to fetch IMA Ad details from database for the player
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
 * IMA ad xml model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideoshareimaadxml extends ContushdvideoshareModel {
  /**
   * Function to get ads
   *
   * @return array
   */
  public function getads() {
    $rows = array ();
    /** Get db connection for ima ads model */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query to get imaads details */
    $query->select ( array ( 'id', 'published', 'adsname', 'imaaddet', 'typeofadd' ) )
    ->from ( '#__hdflv_ads' )->where ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'typeofadd' ) . ' = ' . $db->quote ( 'ima' ) )->order ( $db->escape ( 'id' . ' ' . 'DESC' ) );
    
    $db->setQuery ( $query );
    $rs_ads = $db->loadObject ();
    /** Check im ad details exists */
    if (! empty ( $rs_ads )) {
      $rows = unserialize ( $rs_ads->imaaddet );
    }
    /** Query to get player settings for ima ads */
    $settings = getPlayerIconSettings('');
    /** Call function to display ima ads data */
    $this->showadsxml ( $rows, $settings );
  }
  
  /**
   * Function to show ads
   *
   * @param array $rows
   *          IMA ad detail in array format
   * @param array $settings
   *          Player width and height
   *          
   * @return string
   */
  public function showadsxml($rows, $settings) {
    ob_clean ();
    /** Ima ads xml starts here */
    header ( "content-type: text/xml" );
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<ima>';
    /** Check ima ad details are exist */
    if (count ( $rows ) > 0) {
      /** Fetch ima ad details and assign */
      $imaadwidth   = $settings ['width'] - 30;
      $imaadheight  = $settings ['height'] - 60;
      $imaadpath    = $rows ['imaadpath'];
      $publisherId  = $rows ['publisherId'];
      $contentId    = $rows ['contentId'];
      $imaadType    = $rows ['imaadtype'];
      
      /** Set ima ad type and channel empty for video method */
      $channels = '';
      if ($imaadType != 'videoad') {
        $imaadType  = 'Text';
        $channels   = $rows ['channels'];
      }      
      /** Video ads */
      echo '<adSlotWidth>' . $imaadwidth . '</adSlotWidth> <adSlotHeight>' . $imaadheight . '</adSlotHeight> <adTagUrl>' . $imaadpath . '</adTagUrl>';
      
      /** Text ads size(468,60) */
      echo '<publisherId>' . $publisherId . '</publisherId> <contentId>' . $contentId . '</contentId>';
      
      /** Text or Overlay */
      echo '<adType>' . $imaadType . '</adType> <channels>' . $channels . '</channels>';
    } else {
      /** Video ads */
      echo '<adSlotWidth>400</adSlotWidth> <adSlotHeight>250</adSlotHeight> <adTagUrl> http://ad.doubleclick.net/pfadx/N270.126913.6102203221521/B3876671.22;dcadv=2215309;sz=0x0;ord=%5btimestamp%5d;dcmt=text/xml </adTagUrl>';
      
      /** Text ads size(468,60) */
      echo '<publisherId></publisherId> <contentId>1</contentId>';
      
      /** Text or Overlay */
      echo ' <adType>Text</adType> <channels>poker</channels>';
    }
    /** Ima ad xml ends */
    echo '</ima>';
    exitAction ( '' );
  }
  /** IMA ad xml model class ends */
}
