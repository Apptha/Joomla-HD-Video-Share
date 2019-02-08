<?php
/**
 * Modvideosharemodules module for HD Video Share
 *
 * This file is to fetch the video details based on the module type
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/**
 * class Modvideosharemodules 
 * to display popular, featured, recent, random, category and related modules
 * 
 * @author user
 */
class Modvideosharemodules {
  /**
   * Function to get category videos
   * 
   * @param unknown $type
   * @param unknown $orderField
   * @param unknown $catid
   * @return Ambigous <mixed, NULL, multitype:unknown mixed >
   */
  public static function getModuleVideos ( $type, $orderField, $catid ) {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    $userId = (int) getUserID();
    /** Set query for cateogires */
    $catQuery = '(' . $db->quoteName ( 'e.catid' ) . ' = ' . $db->quote ( $catid ) . ' OR ' . $db->quoteName ( 'b.parent_id' ) . ' = ' . $db->quote ( $catid ) . ' OR ' . $db->quoteName ( PLAYLISTID ) . ' = ' . $db->quote ( $catid ) . ')';
    /** Get limit from module helper */
    $length = Modvideosharemodules::getmoduleVideossettings ($type, '' );
    /** Query is to display category videos randomly */
    if(!empty($userId)) {
    $query->clear()->select ( array ( 'a.id', FILEPATH, VIDTHUMBURL, VIDTITLE, VIDDESCRIPTION, TIMESVIEWED, RATECOUNT, 
        RATE, AMAZONS3,TIMESVIEWED, SEOTITLE, CATEGORYTITLE, SEO_CATEGORY, USRNAME, 'e.catid', CATVIDEOID, 'wl.video_id', 'wh.VideoId' ) )
    ->from ( PLAYERTABLE . VIDEOTABLECONSTANT ) ->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )
    ->leftJoin ( VIDEOCATEGORYTABLE .VIDEOCATELEFTJOIN ) ->leftJoin ( CATEGORYTABLE . ' AS b ON e.catid=b.id' )
    ->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )
    ->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId );
    } else {
    $query->clear()->select ( array ( 'a.id', FILEPATH, VIDTHUMBURL, VIDTITLE, VIDDESCRIPTION, TIMESVIEWED, RATECOUNT, 
        RATE, AMAZONS3, TIMESVIEWED, SEOTITLE, CATEGORYTITLE, SEO_CATEGORY, USRNAME, 'e.catid', CATVIDEOID ) )
    ->from ( PLAYERTABLE . VIDEOTABLECONSTANT ) ->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )
    ->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . ' AS b ON e.catid=b.id' );
    }
    /** Check module type is category and catid is exists */
    if($catid && $type == 5) {
      $query->where ( $catQuery );
    } else{
      $query->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ) );
    }
    /** Check module type and set query based on that */
    switch ($type ){
     case 1:
       /** Set where condition oand order by clause */
      $query->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( '1' ) );
      $order = $query->order ( $db->escape (  $orderField . ' ' . 'ASC' ) );
      break;
     case 4:
      /** Get playlist id to fetch related videos */
      $videoData = Modvideosharemodules::getPlaylistID ();
      if(isset($videoData) && isset ( $videoData [0] ) && (isset ( $videoData [1] )) && ! empty ( $videoData [1] )) {
        $query->where ( $db->quoteName ( PLAYLISTID ) . ' = ' . $db->quote ( $videoData [1] ) );
      }
      $order = '';
      break;
     case 3:
      $order = $query->order ( 'rand()' );
      break;
      case 6:
      	if(!empty($userId)) {
      		$query->where($db->quoteName('wl.user_id') . ' = ' .$userId );
      	} else {
      		$query->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id' );
      		$query->where($db->quoteName('wl.user_id') . ' = 0' );
      	}
      	$order = $query->order($db->escape($orderField . ' ' . 'ASC'));
      	break;
      case 7:
      	if(!empty($userId)) {
      		$query->where ( $db->quoteName ( 'wh.userId' ) . ' = ' .  $userId );
      	}
      	else {
      		$query->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id = wh.VideoId' );
      		$query->where ( $db->quoteName ( 'wh.userId' ) . ' = 0' );
      	}
      	$order = $query->order ($db->escape ('wh.watchedOn' . ' ' . 'DESC' ));
      	break;
     default:
       /** Set order by fields and order direction */
       $order = $query->order ( $db->escape (  $orderField . ' ' . 'DESC' ) );
       break;
    }
    $query->where ( $db->quoteName ( 'a.published' ) . ' = ' . $db->quote ( '1' ) )
    ->where($db->quoteName ( 'b.published' ) . ' = ' . $db->quote ( '1' ) )   
    ->where ( $db->quoteName ( 'd.block' ) . ' = ' . $db->quote ( '0' ) ) ->group ( $db->escape ( CATVIDEOID ) ) . $order;
    /** Execute query to fetch module videos */
    $db->setQuery ( $query, 0, $length );
    return $db->loadobjectList ();
  }
  
  /**
   * Fucntion to get module settings 
   * 
   * @param string $type
   * @param string $tp
   * 
   * @return int/object $limitrow / $length
   */
  public static function getmoduleVideossettings($type, $tp) {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query is to select the category videos module settings */
    $query->clear()->select ( array ( 'dispenable', 'sidethumbview' ) )->from ( SITESETTINGSTABLE );
    $db->setQuery ( $query );
    $limitrow  = $db->loadObjectList ();
    $thumbView = unserialize ( $limitrow [0]->sidethumbview );
    
    /** Check module type is empty then return site settings */ 
    if($type == 8) {
      return $limitrow;
    } else {
      /** Check module type and fetch row, column value based on that */
      switch ($type) {
        case 2:
          $popCol = $thumbView ['sidepopularvideocol'];
          $popRow = $thumbView ['sidepopularvideorow'];
          /** Calculate total videos count for popular modules */
          $length = Modvideosharemodules::calcLength ($tp, $popCol, $popRow);
          break;
        case 0:
          $recCol = $thumbView ['siderecentvideocol'];
          $recRow = $thumbView ['siderecentvideorow'];
          /** Calculate total videos count for recent modules */
          $length = Modvideosharemodules::calcLength ($tp, $recCol, $recRow);
          break;
        case 1:
          $feaCol = $thumbView ['sidefeaturedvideocol'];
          $feaRow = $thumbView ['sidefeaturedvideorow'];
          /** Calculate total videos count for featured modules */
          $length = Modvideosharemodules::calcLength ($tp, $feaCol, $feaRow);
          break;
        case 3:
          $randCol = $thumbView ['siderandomvideocol'];
          $randRow = $thumbView ['siderandomvideorow'];
          /** Calculate total videos count for random modules */
          $length = Modvideosharemodules::calcLength ($tp, $randCol, $randRow);
          break;
        case 4 : 
          $relCol = $thumbView ['siderelatedvideocol'];
          $relRow = $thumbView ['siderelatedvideorow'];
          /** Calculate total videos count for related modules */
          $length = Modvideosharemodules::calcLength ($tp, $relCol, $relRow);
          break;
        case 5:
          $catCol = $thumbView ['sidecategoryvideocol'];
          $catRow = $thumbView ['sidecategoryvideorow'];
          /** Calculate total videos count for category modules */
          $length = Modvideosharemodules::calcLength ($tp, $catCol, $catRow);
          break;
          case 6:
          	$watchCol = $thumbView ['sidewatchlatercol'];
          	$watchRow = $thumbView ['sidewatchlaterrow'];
          	/** Calculate total videos count for category modules */
          	$length = Modvideosharemodules::calcLength ($tp, $watchCol, $watchRow);
          	break;
          case 7:
          	$catCol = $thumbView ['sidehistoryvideocol'];
          	$catRow = $thumbView ['sidehistoryvideorow'];
          	/** Calculate total videos count for category modules */
          	$length = Modvideosharemodules::calcLength ($tp, $catCol, $catRow);
          	break;
        default:
          break;
      }
      /** Return total count for videoshare modules */
      return $length;
    }
  }
  
  /**
   * Function is used to calculate total count for videoshare modules 
   * 
   * @param string $tp
   * @param int $col
   * @param int $row
   * 
   * @return number
   */
  public static function calcLength ($tp, $col, $row) {
    /** Check column is 0. 
     * If it is 0 then assign column value as 1 */
    if ($col == 0) {
      $col = 1;
    }
    /** Check view is eixsts
     * Then return column value alone */
    if($tp == 'view') {
      $length = $col;
    } else {
      /** Calculate total popular thumb count to be display */
      $length = $row * $col;
    }
    /** Return thumb counts */
    return $length;
  } 
  
  /**
   * Fucntion to get video id and playlist id for related videos 
   * 
   * @return multitype:Ambigous <mixed, number> Ambigous <mixed, NULL, unknown, string, string>
   */
  public static function getPlaylistID () {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
        
    /** Fetch related videos Information
     * Code for seo option or not - start
     * Get video id from request URL */
    $videoModule      = JRequest::getVar ( 'video' );
    $idModule         = JRequest::getInt ( 'id' );
    $flagVideoModule  = is_numeric ( $videoModule );
    
    /** Check video param is exists */
    if (isset ( $videoModule ) && $videoModule != "") {
      /** Check seo is enabled */
      if ($flagVideoModule != 1) {
        /** Joomla router replaced to : from - in query string */
        $videoTitle = JRequest::getString ( 'video' );
        $videoid    = str_replace ( ':', '-', $videoTitle );
    
        if ($videoid != "" && ! version_compare ( JVERSION, '3.0.0', 'ge' )) {
          $videoid = $db->getEscaped ( $videoid );
        }
    
        /** Query to select playlist id based on the video id */
        $query->clear ()->select ( 'playlistid' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'seotitle' ) . ' = ' . $db->quote ( $videoid ) );
        $db->setQuery ( $query );
        $videoModule = $db->loadResult ();
      } else {
        /** Get video id from request */
        $videoid = JRequest::getInt ( 'video' );
        $query->clear ()->select ( 'playlistid' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $videoid ) );
        $db->setQuery ( $query );
        $videoModule = $db->loadResult ();
      }
    } elseif (isset ( $idModule ) && $idModule != '') {
      $videoid = JRequest::getInt ( 'id' );
      /** Fetch palylist id from table */
      $query->clear ()->select ( 'playlistid' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $videoid ) );
      $db->setQuery ( $query );
      $videoModule = $db->loadResult ();
    } else {
      $videoid = $videoModule = '';
    }
    /** Return video id and category id */
    return array ( $videoid, $videoModule) ;
  }
  
  /**
   * Function is used to sort the related videos module 
   * based on the playlist order within the player
   * 
   * @param unknown $relatedResult
   * 
   * @return multitype:
   */
  public static function changeRelatedVideosOrder ($relatedResult){ 
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Initialize arrays */
    $arr1 = array ();
    $arr2 = array ();
    /** Get video id and playlist id */
    $videoData  = Modvideosharemodules::getPlaylistID ();
    $videoid    = $videoData[0];
    
    /** Check related videos results are exists */
    if (count($relatedResult) > 0) {
      /** Loop through related video details */
      foreach ($relatedResult as $r) {
        /** Set query to reorder the related vidoes */
        $query->clear()
        ->select(array ('a.id', FILEPATH, VIDTHUMBURL, VIDTITLE, VIDDESCRIPTION, TIMESVIEWED, RATECOUNT, RATE, AMAZONS3,
            SEOTITLE, CATEGORYTITLE, SEO_CATEGORY, USRNAME, 'e.catid', CATVIDEOID ))
            ->from ( PLAYERTABLE . VIDEOTABLECONSTANT) ->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )
            ->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN ) ->leftJoin ( CATEGORYTABLE . ' AS b ON e.catid=b.id' )
            ->where ( $db->quoteName ( 'a.type' ) . ' = ' . $db->quote ( '0' ))
            ->where ( $db->quoteName('a.id') . ' != ' . $db->quote($videoid))
            ->where ( $db->quoteName ( PLAYLISTID ) . ' = ' . $db->quote ( $videoData [1] ) )
            ->where ( $db->quoteName ( 'a.published' ) . ' = ' . $db->quote ( '1' ) . ' AND ' . $db->quoteName ( 'b.published' ) . ' = ' . $db->quote ( '1' ) )
            ->where ( $db->quoteName ( 'd.block' ) . ' = ' . $db->quote ( '0' ) )
            ->group ( $db->escape ( CATVIDEOID ) );
        
        /** Check related videos id is greater than the current video id */  
        if ($r->id > $videoid) {
          /** Storing greater values in an array */          
          $query->where ( $db->quoteName('a.id') . ' = ' . $db->quote($r->id));
          $db->setQuery($query);
          $arrGreat = $db->loadObject();
          /** Store greater video details in arr1 */
          $arr1[] = $arrGreat;     
        } else {
          /** Storing lesser values in an array */
          $query->where ( $db->quoteName('a.id') . ' = ' . $db->quote($r->id));
          $db->setQuery($query);
          $arrLess = $db->loadObject();
          /** Store lesser video details in arr1 */
          $arr2[] = $arrLess;
        }
      }
    }
    /** Merger greater and lesser values and return to display */
    return array_merge($arr1, $arr2);
  }
  
  /** class Modvideosharemodules ends */
}
