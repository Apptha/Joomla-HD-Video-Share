<?php
/**
 * Ad xml file for HD Video Share
 *
 * This file is to display Ad XML for the player 
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
 * Adsxml model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideoshareadsxml extends ContushdvideoshareModel {
  /**
   * Function to get ads
   *
   * @return array
   */
  public function getads() {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** Query to get pre and post roll ad detials */
    $query->select ( array ( 'id', 'published', 'adsname', 'filepath', 'postvideopath', 'targeturl',
        'clickurl', 'impressionurl', 'adsdesc', 'typeofadd' 
    ) )->from ( '#__hdflv_ads' )->where ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) . ' AND ' . $db->quoteName ( 'typeofadd' ) . ' = ' . $db->quote ( 'prepost' ) );
    $db->setQuery ( $query );
    $rs_ads = $db->loadObjectList ();
    /** Call function to get ads xml data */
    $this->showadsxml ( $rs_ads );
  }
  
  /**
   * Function to show ads
   *
   * @param array $rs_ads
   *          ad detail in array format
   *          
   * @return string
   */
  public function showadsxml($rs_ads) {
    /** Clear data nad set content type for ads xml */
    ob_clean ();
    header ( "content-type: text/xml" );
    /** Set xml version and encoding for ads XML */
    echo '<?xml version="1.0" encoding="utf-8"?>';
    /** Ads XML starts */
    echo '<ads random="false">';
    $current_path = "components/com_contushdvideoshare/videos/";
    
    /** Check ads details are exist */
    if (count ( $rs_ads ) > 0) {
      /** Looping through ads details */
      foreach ( $rs_ads as $rows ) {
        /** Check file path is file or URL. 
         * Based on that get URL 
         */
        if ($rows->filepath == "File") {
          $postvideo = JURI::base () . $current_path . $rows->postvideopath;
        } elseif ($rows->filepath == "Url") {
          $postvideo = $rows->postvideopath;
        } else {
          $postvideo = '';
        }
        
        /** Get target URL */
        $targeturl = $rows->targeturl;
        if (! empty ( $rows->targeturl ) && ! preg_match ( "~^(?:f|ht)tps?://~i", $rows->targeturl )) {
          $targeturl = "http://" . $rows->targeturl;
        }
        
        /** Get click URL */
        $clickpath = getClickURL ($rows->clickurl );
        
        /** Get impression hits URL from helper */
        $impressionpath = getImpressionURL ($rows->impressionurl);
        
        /** Display ads xml content */
        echo '<ad id="' . $rows->id . '" url="' . $postvideo . '" targeturl="' . $targeturl . '" clickurl="' . $clickpath . '" impressionurl="' . $impressionpath . '">';
        echo '<![CDATA[' . $rows->adsdesc . ']]>';
        echo '</ad>';
      }
    }
    /** Ads XML ends */
    echo '</ads>';
    exitAction ( '' );
  }
}
