<?php
/**
 * RSS Feed model file
 *
 * This file is to fetch videos detail from database and generate RSS Feed
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component herlper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file  */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );

/**
 * RSS model class. 
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharerss extends ContushdvideoshareModel {
  /**
   * Function to get play records
   *
   * @return array
   */
  public function playgetrecords() {
    /** Get db connection for rss model */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** Get rss type from request */
    $type = JRequest::getvar ( 'type' );
    $orderby = '';
    /** Check rss type and set order by clause */
    switch ($type) {
      case 'popular' :
        $orderby = " a.times_viewed";        
        break;      
      case 'recent' :
        $orderby = "a.id";        
        break;      
      case 'featured' :
        $where = "a.featured='1'";
        $orderby = "";        
        break;      
      case 'category' :
        $playid = JRequest::getvar ( 'catid' );
        $where = 'a.playlistid=' . $playid;
        $orderby = '';        
        break;
      default :
    }
    
    /** Query to select video details based on the rss type */
    $query->select ( array ( 'DISTINCT a.*', 'b.seo_category', 'b.category', 'd.username' 
    ) )->from ( '#__hdflv_upload AS a' )->leftJoin ( '#__users AS d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_category AS b ON a.playlistid=b.id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) );
    
    /** Check where is exist then add it to the query */
    if ($where != '') {
      $query->where ( $where );
    }
    
    /** Check orderby is exist then add it to the query */
    if ($orderby != '') {
      $query->order ( $db->escape ( $orderby . ' ' . 'DESC' ) );
    }
    
    $db->setQuery ( $query );
    /** Execute query to get rss results */
    $rssData = $db->loadObjectList ();
    
    /** Call function to show rss feed data */
    $this->showxml ( $rssData );
  }
  
  /**
   * Function to show RSS
   *
   * @param array $rssData
   *          Video detail array
   * @param array $dispenable
   *          settings array
   *          
   * @return string
   */
  public function showxml( $rssData ) {
    $siteName = $language = $metaDesc = '';
    $fbCategoryVal = $fbVideoVal = 0 ;
    /** Clear page content and set header for rss feed */
    ob_clean ();
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
    header ( "content-type: text/xml" );
    /** Rss xml feed starts here */
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:gd="http://schemas.google.com/g/2005" 
        xmlns:yt="http://gdata.youtube.com/schemas/2007" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" 
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:content="http://purl.org/rss/1.0/modules/content/">';    
    $result = $this->getSiteLangName ();
    if( isset ($result [0]) ) {
      $siteName = $result[0];
    }
    if( isset ($result [1]) ) {
      $language = $result[1];
    }
    if( isset ($result [2]) ) {
      $metaDesc = $result[2];
    }    
    echo '<channel> <title>' . $siteName . '</title> <description>' . $metaDesc . '</description>';
    echo '<link>' . JURI::base () . '</link> <language>' . $language . '</language>';
    
    $dispenable = getSiteSettings ();
    /** Get player settings */
    $player_icons = getPlayerIconSettings('icon');
    $rsshddefault = $player_icons ['hddefault'];
    $current_path = "components/com_contushdvideoshare/videos/";    
    if (count ( $rssData ) > 0) {
      foreach ( $rssData as $rows ) {        
        $preview_image = 'default_preview.jpg';
        if (! empty ( $rows->previewurl )) {
          $preview_image = $rows->previewurl;
        } 
        $previewimage = JURI::base () . $current_path . $preview_image;        
        $timage = JURI::base () . $current_path . $rows->thumburl;
        switch ($rows->filepath ) {
          case 'File':
          case 'FFmpeg':
            if ($rsshddefault == 0 && $rows->hdurl != '') {
              $rssvideo = '';
            } else {
              $rssvideo = JURI::base () . $current_path . $rows->videourl;
              if (isset ( $rows->amazons3 ) && $rows->amazons3 == 1) {
                $rssvideo = $dispenable ['amazons3link'] . $rows->videourl;
              }
            }  
          break;
          case 'Url':
            $rssvideo = $rows->videourl;            
            if ( !empty ( $rows->previewurl )) {
              $preview_image = $rows->previewurl;
            }            
            $timage = $rows->thumburl;
            break;
          case 'Youtube':
            $rssvideo = $rows->videourl;
            $str2 = strstr ( $rows->previewurl, 'components' );    
            $previewimage = $rows->previewurl;
            $timage = $rows->thumburl;
            if ($str2 != "") {
              $previewimage = JURI::base () . $rows->previewurl;
              $timage = JURI::base () . $rows->thumburl;
            } 
            break;
          case 'Embed':
            $rssvideo = $previewimage = '';
            break;
          default: break;
        }
        $baseUrl1 = parse_url ( JURI::base () );
        $baseUrl1 = $baseUrl1 ['scheme'] . '://' . $baseUrl1 ['host'];        
        /** Call function to select the featured videos module item id */
        $Itemid = getmenuitemid_thumb ( 'player', '' );
        /** Call function to generate fb path based on the seo title */ 
        $fbURLResults = $this->getFBPath ($rows->playlistid, $rows->seotitle, $rows->id);
        if(!empty ($fbURLResults)) {
          $fbCategoryVal = $fbURLResults [0];
          $fbVideoVal = $fbURLResults [1];
        }
        $fbPath = $baseUrl1 . JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=player&' . $fbCategoryVal . '&' . $fbVideoVal );
        echo '<item> <title>' . $rows->title . '</title> <link>' . $fbPath . '</link>';
        echo '<media:group> <media:category/>
                <media:content url="' . $rssvideo . '" type="application/x-shockwave-flash" medium="video" isDefault="true" expression="full" yt:format="5"/>
                <media:description type="plain" /> 
                <media:keywords/> <media:thumbnail url="' . $timage . '"/> </media:group>';
        echo '<guid>' . $fbPath . '</guid>';
        echo '<description>  <![CDATA[<p><img src ="' . $timage . '"/>' . strip_html_tags($rows->description) . '</p>]]> </description>';
        echo '<pubDate>' . date ( "D, d M Y H:i:s T", strtotime ( $rows->created_date ) ) . '</pubDate> <author>' . strip_tags($rows->username) . '</author>';
        echo '<category>' . htmlentities  ($rows->category) . '</category> </item>';
      }
    }
    /** rss xml feed ends here */
    echo '</channel></rss>';
    exitAction ( '' );
  }
  
  /**
   * Function is used to get site name and language
   * 
   * @return multitype:unknown Ambigous <mixed, \Joomla\Application\mixed, \Joomla\Registry\mixed, unknown>
   */
  public function getSiteLangName () {
    /** Get global configuration and application object */
    $config = JFactory::getConfig ();
    $mainframe = JFactory::getApplication ();
    
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      $siteName = $mainframe->getCfg ( 'sitename' );
      $language = $mainframe->getCfg ( 'language' );
      $metaDesc = $mainframe->getCfg ( 'MetaDesc' );
    } else {
      $siteName = $config->getValue ( 'config.sitename' );
      $language = $config->getValue ( 'config.language' );
      $metaDesc = $mainframe->getCfg ( 'config.MetaDesc' );
    }
    return array ( $siteName, $language );
  }
  
  /** 
   * Fucntion is used to get fbpath passed in playlist xml
   * 
   * @param unknown $catid
   * @param unknown $seotitle
   * @param unknown $id
   * @return multitype:string
   */
  public function getFBPath ($catid, $seotitle, $id) {
    /** Get site settings to check seo option*/
    $dispenable = getSiteSettings ();
    /** Get db connection to get seo category from db */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** Get seo category from table for the given cat id */
    $query->clear ()->select ( 'seo_category' )->from ( '#__hdflv_category' )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $catid ) );
    $db->setQuery ( $query );
    $categorySeo = $db->loadObjectList ();
    /** Get video and category id */
    if ($dispenable ['seo_option'] == 1) {
      $fbCategoryVal = "category=" . $categorySeo [0]->seo_category;
      $fbVideoVal = "video=" . $seotitle;
    } else {
      $fbCategoryVal = "catid=" . $catid;
      $fbVideoVal = "id=" . $id;
    }
    /** Store video adn category id in an array and return */ 
    return array ( $fbCategoryVal, $fbVideoVal );
  }
  /** RSS Feed model file ends */
}