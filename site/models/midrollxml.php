<?php
/**
 * Midroll ad XML model file
 *
 * This file is to fetch Midroll ad details from database
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
 * Mid roll xml model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharemidrollxml extends ContushdvideoshareModel {
  /**
   * Function to midroll ads
   *
   * @return array
   */
  public function getads() {
    /** Set db connection for midroll xml */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query to get midroll xml details */
    $query->select ( '*' )->from ( '#__hdflv_ads' )->where ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'typeofadd' ) . ' = ' . $db->quote ( 'mid' ) );
    $db->setQuery ( $query );
    $rs_modulesettings = $db->loadObjectList ();
    
    /** Get player settings for midroll xml */
    $rs_random = getPlayerIconSettings('both');
    
    /** Unserialize player settings value */
    $player_icons = unserialize ( $rs_random->player_icons );
    $player_values = unserialize ( $rs_random->player_values );
    
    /** Assign midroll begin and interval time */
    $interval = $player_values ['midinterval'];
    $begin    = $player_values ['midbegin'];
    
    /** Check random value and assign the value based on that */
    $random = 'false';
    if ($player_icons ['midrandom'] == 1) {
      $random = 'true';
    }
    
    /** Check adrotate value and assign the value based on that */
    $adrotate = 'false';
    if ($player_icons ['midadrotate'] == 1) {
      $adrotate = 'true';
    }
    
    /** Check midroll data are exists */
    if ($rs_modulesettings) {
      /** Call function to display midroll xml content */
      $this->showadsxml ( $rs_modulesettings, $random, $begin, $interval, $adrotate );
    }
  }
  
  /**
   * Function to show midroll ads
   *
   * @param array $midroll_video
   *          Midroll ads detail in array format
   * @param boolean $random
   *          random display enabled or not
   * @param int $begin
   *          Mid roll ads starting time
   * @param int $interval
   *          Mid roll ad interval period to display the next ad
   * @param boolean $adrotate
   *          Rotation of displaying mid roll ad enabled or not
   *          
   * @return string
   */
  public function showadsxml($midroll_video, $random, $begin, $interval, $adrotate) {
    ob_clean ();
    /** Midroll xml starts here */
    header ( "content-type: text/xml" );
    
    /** Set xml version and encoding for midroll xml */
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<midrollad begin="' . $begin . '" adinterval="' . $interval . '" random="' . $random . '" adrotate="' . $adrotate . '">';
    
    if (count ( $midroll_video ) > 0) {
      foreach ( $midroll_video as $rows ) {
                
        /** Get impression hits URL from helper */
        $impressionpath = getImpressionURL ($rows->impressionurl);
        
        /** Get click URL */
        $clickpath = getClickURL ($rows->clickurl );
                
        /** Get target url */
        $targeturl = $rows->targeturl;
        if (! empty ( $rows->targeturl ) && ! preg_match ( "~^(?:f|ht)tps?://~i", $rows->targeturl )) {
          $targeturl = "http://" . $rows->targeturl;
        }
        
        /** Display midroll xml content */
        echo '<midroll targeturl="' . $targeturl . '" clickurl="' . $clickpath . '" impressionurl="' . $impressionpath . '" >';
        echo '<![CDATA[';
        echo '<span class="heading">' . $rows->adsname;
        echo '</span><br><span class="midroll">' . $rows->adsdesc;
        echo '</span><br><span class="webaddress">' . $targeturl;
        echo '</span>]]>';
        echo '</midroll>';
      }
    }
    /** Midroll xml ends here */
    echo '</midrollad>';
    exitAction ( '' );
  }
  /** Mid roll xml model class ends */ 
}
