<?php
/**
 * Player model file
 *
 * This file is to fetch all videos details from database for video home page view
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

/** Define constants */
define ('VIDEOPAGEID', JRequest::getInt ( 'video_pageid' ));
define ('VIDEOCATCLEFTJOIN', ' AS c ON c.vid=a.id');
define ('CATBLEFTJOIN', ' AS b ON e.catid=b.id');
define ('CATBLEFTJOINC', ' AS b ON c.catid=b.id');
define ('UPLOADTABLEASA', VIDEOTABLECONSTANT);
define ('ADMINVIEW', JRequest::getString ( 'adminview' ));

/**
 * Player model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideoshareplayer extends ContushdvideoshareModel {
  /**
   * Function to get video id
   *
   * @param string $video
   *          Video name
   *          
   * @return object
   */
  public function getVideoId($video, $type) { 
    /** Get db connection got player model */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Check type is seo */
    if($type == 'seo') {
      /** Get video seo title */
      if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
        $video = $db->getEscaped ( $video );
      }  
      /** Set where condition based on seo title */
      $where = $db->quoteName ( 'seotitle' ) . ' = ' . $db->quote ( $video ) ;
    } else{
      /** Set where condition based on video id */
      $where = $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $video ) ;
    }
    
    /** Query to get video and playlist id, video url */
    $query->clear()->select ( array ( 'id', 'playlistid', 'videourl' ) )->from ( PLAYERTABLE );
    $query->where ($where);
    $db->setQuery ( $query ); 
    /** Return video details */ 
    return $db->loadObject ();
  }
  
  /**
   * Function to get video details
   *
   * @param string $video
   *          Video name
   * @param string $category
   *          Category name
   *          
   * @return object
   */
  public function getVideoCatId($video, $category) {
    /** Get db connection to fetch video and catid */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Check admin view and set condition */
    if (ADMINVIEW) {
      $publish = '';
    } else {
      $publish = $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' );
    }
    
    /** Query to select video id and cat id from db */
    $query->clear()->select ( array ( 'a.id', 'a.playlistid', 'a.videourl' ) )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOIN );
    
    if ($publish != '') {
      $query->where ( $publish );
    }
    
    $query->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( SEOTITLE ) . ' = ' . $db->quote ( $video ) )->where ( $db->quoteName ( SEO_CATEGORY ) . ' = ' . $db->quote ( $category ) );
    $db->setQuery ( $query );
    /** Return video and cat id details */ 
    return $db->loadObject ();
  }
  
  public function getVideoPlayId($video, $play) {
  	$db = JFactory::getDBO();
  	$query = $db->getQuery(true);
  	$adminview = JRequest::getString('adminview');
  
  	if ($adminview) {
  		$publish = '';
  	}
  	else {
  		$publish = $db->quoteName( VIDEOPUBLISH ) . ' = ' . $db->quote('1');
  	}
  
  	$query->clear()->select(array('a.id', 'a.playlistid', 'a.videourl','b.id as playid'))
  	->from( PLAYERTABLE . VIDEOTABLECONSTANT)
  	->leftJoin( VIDEOPLAYLISTTABLE . ' AS e ON e.vid=a.id')
  	->leftJoin( PLAYLISTTABLE . ' AS b ON e.catid=b.id');
  
  	if ($publish != '') {
  		$query->where($publish);
  	}
  
  	$query->where($db->quoteName( CATPUBLISH ) . ' = ' . $db->quote('1'))
  	->where($db->quoteName( SEOTITLE ) . ' = ' . $db->quote($video))
  	->where($db->quoteName(SEO_CATEGORY) . ' = ' . $db->quote($play));
  	$db->setQuery($query);
  	
  	return $db->loadObject();
  }
  
  /**
   * Function to get featured videos
   *
   * @return object
   */
  public function getfeatured() {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    if (ADMINVIEW) {
      $publish = '';
    } else {
      $publish = $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' );
    }
    
    $query->clear()->select ( 'id' )->from ( PLAYERTABLE );
    
    if ($publish != '') {
      $query->where ( $publish );
    }
    
    $query->where ( $db->quoteName ( 'featured' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'type' ) . ' = ' . $db->quote ( '0' ) )->order ( $db->escape ( 'ordering' . ' ' . 'ASC' ) );
    $db->setQuery ( $query );
    return $db->loadObject ();
  } 

  /**
   * Function to get Player setting values
   * 
   * @return array
   */
  public function playersettings(){
    /** Get db connection to get player settings for player view */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** Query to get player settings from db */
    $query->clear()->select ( array ( 'player_values', 'logopath', 'player_icons' ) )->from ( PLAYERSETTINGSTABLE );
    $db->setQuery ( $query );
    /** Return player setting detials ?*/
    $videoCount = $db->loadObjectList ();
    
    if ( $videoCount > 0 ) {
      return $videoCount;
    } else {
      return 0;
    } 
  }
  
  /**
   * Function to get total videos count
   * 
   * @return Ambigous <mixed, NULL>
   */
  public function getVideosCount () {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    $query->clear ()->select ( 'count(id)' )->from ( PLAYERTABLE )->where ( $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' ) )->order ( $db->escape ( 'id' . ' ' . 'DESC' ) );
    $db->setQuery ( $query );
    return $db->loadResult ();    
  }
  
  /**
   * Function to get all video details 
   * 
   * @return Ambigous <mixed, NULL, multitype:unknown mixed >
   */
  public function getAllVideoDetails () {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    $query->clear ()->select ( '*' )->from ( PLAYERTABLE )->where ( $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' ) );
    $db->setQuery ( $query );
    return $db->loadObjectList ();
  }
  
  /**
   * Fucntion to get google adsense detials 
   * 
   * @return Ambigous <mixed, NULL, multitype:unknown mixed >
   */
  public function getGoogleAdsenseDetails () {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /**
     * Get google adsense detail from table
     * Set them in array and pass it to player view
     * The passed array value will be processed and display the ad on the player
     */
    $query->clear ()->select ( '*' )->from ( GOOGLEADTABLE )->where ( $db->quoteName ( 'publish' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( '1' ) );
    $db->setQuery ( $query );
    return $db->loadObjectList ();
  }

  /**
   * Function to get video details for home page
   *
   * @param int $videoid
   *          video id
   *          
   * @return array
   */
  public function showhdplayer($videoid) {
    $playid = $thumbid = 0;
    $pageno = $start = 1;
    $hd_bol = "false";
    $hdvideo = false;
    $current_path = "components/com_contushdvideoshare/images/";
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    $settingsrows = $this->playersettings();
    $player_values = unserialize ( $settingsrows [0]->player_values );
    
    /** Check playlist id is exists and assign to variabe */
    if ($videoid) {
      $playid = $videoid;
    }
    
    /** Call function to get total videos count from db */
    $total = $this->getVideosCount();
    
    /** Check page id is exists and set into session */
    if (VIDEOPAGEID) {
      $pageno = VIDEOPAGEID;
      $_SESSION ['commentappendpageno'] = $pageno;
    }
    
    /** Assign default limit 4 for related videos */
    $length = 4;
    /** Check related videos limit is exists and assign */ 
    if ($player_values ['nrelated'] != "") {
      $length = $player_values ['nrelated'];
    }
    
    if ($pageno > 1) {
      $start = ($pageno - 1) * $length;
    }
    
    /** Get all video details from db */
    $rows = $this->getAllVideoDetails ();

    if (isset ( $rows [0]->id )) {
      $thumbid = $rows [0]->id;
    }
    
    if (count ( $rows ) > 0) {
      $video = $rows [0]->videourl;
      if ($rows [0]->hdurl) {
        $hd_bol = "true";
      } 
      /** Swtich case to check the video type
       * It will return normal video url, hd video url, preview image url */
      switch ($rows [0]->filepath){
        case "File":
        case "FFmpeg":
          $video = JURI::base () . $current_path . $rows [0]->videourl;
          ($rows [0]->hdurl != "") ? $hdvideo = JURI::base () . $current_path . $rows [0]->hdurl : $hdvideo = "";
          $previewimage = JURI::base () . $current_path . $rows [0]->previewurl;
          break;
        case "Url":          
          $previewimage = $rows [0]->previewurl; 
          $hdvideo = $rows [0]->hdurl;
          break;
        case "Youtube":          
          $previewimage = $rows [0]->previewurl;  
          $hdvideo = $rows [0]->videourl;
          break;
        default:
          break;
      }
      $playid = $rows [0]->id;
    }
    
    $query->clear ()->select ( '*' )->from ( PLAYERTABLE )->where ( $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'id' ) . ' NOT IN (' . $db->quote ( $playid ) . ')' )->order ( $db->escape ( 'ordering' . ' ' . 'ASC' ) );
    $db->setQuery ( $query, $start, $length );
    $rs_playlist = $db->loadobjectList ();
    
    $url_base = substr_replace ( str_replace ( ':', '%3A', JURI::base () ), "", - 1 );
    $baseurl = str_replace ( '/', '%2F', $url_base );
    
    /**  Assign video details into an array */
    $insert_data_array = array ( 'playerpath' => PLAYERPATH, 'baseurl' => $baseurl, 'thumbid' => $thumbid, 'rs_playlist' => $rs_playlist, 'length' => $length, 'total' => $total );
    
    /** Call function to get google adsense details */
    $fields = $this->getGoogleAdsenseDetails ();
    /** Check gi=oogle adsense data is published */ 
    if (isset ( $fields [0]->publish )) {
      /** Merge adsense details with video detials */
      $insert_data_array = array_merge ( $insert_data_array, array ( 'closeadd' => $fields [0]->closeadd, 'showoption' => $fields [0]->showoption, 'reopenadd' => $fields [0]->reopenadd, 'ropen' => $fields [0]->ropen, 'publish' => $fields [0]->publish, 'showaddc' => $fields [0]->showaddc ));
    }
    /** Merge settings data and video detials and return */
    return array_merge ( $settingsrows, $insert_data_array );
  }
  
  /**
   * Function to get video id for ratting
   * 
   * @param int $videoid
   * @return Ambigous <unknown, string>
   */
  public function getRatingVideoId ($videoid) {
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    if ($videoid) {
      $id = $videoid;
    } else { 
      $query->select ( array ( 'a.*', 'b.category', 'd.username', 'e.*' ) )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOIN )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( FEATURED ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    
      /** Query is to display recent videos in home page */
      $db->setQuery ( $query );
      $rs_video = $db->loadObjectList ();
    
      if (empty ( $rs_video )) {
        $query->clear ( WHERE )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) );
        $db->setQuery ( $query );
        $rs_video = $db->loadObjectList ();
      }
    
      if (isset ( $rs_video [0] ) && $rs_video [0] != '') {
        $id = $rs_video [0]->id;
      } else {
        $id = '';
      }
    }
    return $id;
  }
  
  /**
   * Function to rating calculation
   *
   * @param int $videoid
   *          video id
   *          
   * @return array
   */
  public function ratting($videoid) {
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    /** Call function to get video id for ratting */
    $id = $this->getRatingVideoId ($videoid);
    
    /** Get rate param from request using helper function */ 
    $get_rate = JRequest::getVar ( 'rate' );
    
    if ($get_rate) {
      $rated_user = '';
      $userid = $_SERVER ['REMOTE_ADDR'];
      
      $query->clear ()->select ( 'rateduser' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $id ) );
      $db->setQuery ( $query );
      $rateduser = $db->loadResult ();
      /**
       * Restrict user to rate one time based on IP address 
       */ 
      $rate_user = explode ( ',', $rateduser );
      if (in_array ( $userid, $rate_user )) {
        $rateuser = 1;
      } else {
        $rateuser = 0;
      }
      
      if ($rateuser == 0) {
        if (empty ( $rateduser )) {
          $rated_user = $userid;
        } else {
          $rated_user = $rateduser . ',' . $userid;
        }
        
        $fields = array ( $db->quoteName ( 'rate' ) . ' = ' . $get_rate . '+rate', $db->quoteName ( 'ratecount' ) . '= 1+ratecount', $db->quoteName ( 'rateduser' ) . '= "' . $rated_user . '"' 
        );
        
        $query->clear ()->update ( $db->quoteName ( PLAYERTABLE ) )->set ( $fields )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $id ) );
        $db->setQuery ( $query );
        $db->query ();
        
        $query->clear ()->select ( 'ratecount' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $id ) );
        $db->setQuery ( $query );
        $ratings = $db->loadResult ();
        
        echo $ratings;
      } else {
        echo "You have already voted";
      }
      exitAction ( '' );
    }
    
    if ($id != '') {
      /** Get Views counting */
      $query->clear ()->select ( array ( TIMESVIEWED, 'a.rate', 'a.rateduser', 'a.ratecount',
          'a.memberid', 'b.username' ) )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . ' AS b ON a.memberid=b.id' )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $id ) );
      
      /** This query is to display the title and times of views in the video page */
      $db->setQuery ( $query );
      return $db->loadObjectList ();
    }
  }
  
  /**
   * Function to display comments
   *
   * @param int $videoid
   *          video id
   *          
   * @return array
   */
  public function displaycomments($videoid) {
    if ($videoid) {
      $db = $this->getDBO ();
      $query = $db->getQuery ( true );
      $sub = $db->getQuery ( true );
      $id = $videoid;
      $pageno = 1;
      $length = 10;
      
      if (JRequest::getString ( 'name' ) && JRequest::getString ( MESSAGE )) {
        /** Getting the parent id value */
        $parentid = JRequest::getInt ( 'pid' );
        
        /** Getting the name who is posting the comments */
        $name = JRequest::getString ( 'name' );
        
        /** Getting the message */
        $message = JRequest::getString ( MESSAGE );
        
        if (strlen ( $message ) > 500) {
          $message = JHTML::_ ( 'string.truncate', ($message), 500 );
        }
        
        if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
          $name = $db->getEscaped ( $name );
          $message = $db->getEscaped ( $message );
        }
        
        /** Insert query to post a new comment for a particular video */
        $values = array ( $parentid, $id, $db->quote ( $name ), $db->quote ( $message ), 1 );
        $query->clear()->insert ( $db->quoteName ( COMMENTSTABLE ) )->columns ( $db->quoteName ( array (
            PARENTID, VIDEOID, 'name', MESSAGE, PUBLISH ) ) )->values ( implode ( ',', $values ) );
        $db->setQuery ( $query ); 
        $db->query ();
      }
      
      /** Following code is to display the title and times of views for a particular video */
      $query->clear ()->select ( array ( 'a.title', 'a.description', TIMESVIEWED, 'a.memberid', 'b.username' 
      ) )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . ' AS b ON a.memberid=b.id' )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $id ) );
      
      /** This query is to display the title and times of views in the video page */
      $db->setQuery ( $query );
      $commenttitle = $db->loadObjectList ();
      
      /** Query is to get the pagination value for comments display */
      $query->clear ()->select ( 'count(id)' )->from ( COMMENTSTABLE )->where ( $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( VIDEOID ) . ' = ' . $db->quote ( $id ) );
      $db->setQuery ( $query );
      $total = $db->loadResult ();
      
      if (VIDEOPAGEID) {
        $pageno = VIDEOPAGEID;
      }      
      $pages = ceil ( $total / $length );      
      if ($pageno == 1) {
        $start = 0;
      } else {
        $start = ($pageno - 1) * $length;
      }
      $options = array ( 'id', PARENTID, VIDEOID, 'subject', 'name', 'created', 'message' );
      $sub->clear()->select ( array_merge ( array( 'parentid as number' ), $options) )->from ( COMMENTSTABLE )->where ( $db->quoteName ( PARENTID ) . ' != ' . $db->quote ( '0' ) )->where ( $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( VIDEOID ) . ' = ' . $db->quote ( $id ) );
      
      $query->clear ()->select ( array_merge ( array( 'id as number' ), $options) )->from ( COMMENTSTABLE )->where ( $db->quoteName ( PARENTID ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( VIDEOID ) . ' = ' . $db->quote ( $id ) . ' UNION ' . $sub )->order ( $db->escape ( 'number' . ' ' . 'DESC' ) . ',' . $db->escape ( PARENTID ) );
      
      /** Query is to display the comments posted for particular video */
      $db->setQuery ( $query );
      $rowscount = $db->loadObjectList ();
      $totalcomment = count ( $rowscount );
      
      /** Query is to display the comments posted for particular video */
      $db->setQuery ( $query, $start, $length );
      $rows = $db->loadObjectList ();
      
      /** Below code is to merge the pagination values like pageno,pages,start value,length value */
      $merge_page_no = array_merge ( $commenttitle, array ( 'pageno' => $pageno ) );
      $mergepages = array_merge ( $merge_page_no, array ( 'pages' => $pages ) );
      $merge_start_value = array_merge ( $mergepages, array ('start' => $start ) );
      $merge_length = array_merge ( $merge_start_value, array ( 'length' => $length ) );
      $merge_totalcomment = array_merge ( $merge_length, array ( 'totalcomment' => $totalcomment ) );      
      return array ( $merge_totalcomment, $rows );
    }
  }
  
  /**
   * Function to get home page videos
   *
   * @return array
   */
  public function gethomepagebottom() {
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    $user = JFactory::getUser();
    $userId = $user->get('id');
    /** Function to get homa page video settings */
    $viewrow = gethomepagebottomsettings ();
    $thumbview = unserialize ( $viewrow [0]->homethumbview );
    $featurelimit = $thumbview ['homefeaturedvideorow'] * $thumbview ['homefeaturedvideocol'];
    
    $columns = array ('a.*', 'b.category', SEO_CATEGORY, 'd.username', 'c.*');
    $wlcolumns = array_merge( $columns, array ('wl.video_id', 'wh.VideoId' ));
    
    /** Query to get featured videos */
    if(!empty($userId)) {
    	$query->clear()->select ( $wlcolumns )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATCLEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOINC )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( FEATURED ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'c.vid' ) )->order ( $db->escape ( 'a.ordering' . ' ' . 'ASC' ) );
    } else {
    	$query->clear()->select ( $columns )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATCLEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOINC )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( FEATURED ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'c.vid' ) )->order ( $db->escape ( 'a.ordering' . ' ' . 'ASC' ) );
    }    
    /** Query is to display featured videos in home page randomly */
    $db->setQuery ( $query, 0, $featurelimit );    
    /** $featuredvideos contains the results */
    $featuredvideos = $db->loadobjectList ();
    $recentlimit = $thumbview ['homerecentvideorow'] * $thumbview ['homerecentvideocol'];
    
    /** Query to get recent videos */
    if(!empty($userId)) {
      $query->clear ()->select ( $wlcolumns )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATCLEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOINC )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'c.vid' ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    }
    else {
    	$query->clear ()->select ( $columns )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATCLEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOINC )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'c.vid' ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
    }    
    /** Query is to display recent videos in home page */
    $db->setQuery ( $query, 0, $recentlimit );
    /** $recentvideos contains the results */
    $recentvideos = $db->loadobjectList ();
    
    $popularlimit = $thumbview ['homepopularvideorow'] * $thumbview ['homepopularvideocol'];
    
    /** Query to get popular videos */
    if(!empty($userId)) {    
      $query->clear ()->select ( $wlcolumns )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATCLEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOINC )->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'c.vid' ) )->order ( $db->escape ( TIMESVIEWED . ' ' . 'DESC' ) );
    }
    else {
    	$query->clear ()->select ( $columns )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATCLEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOINC )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( 'c.vid' ) )->order ( $db->escape ( TIMESVIEWED . ' ' . 'DESC' ) );
    }
    
    /** Query is to display popular videos in home page */
    $db->setQuery ( $query, 0, $popularlimit );
    
    /** $popularvideos contains the results */
    $popularvideos = $db->loadobjectList ();
    
    /** Merging the featured,recent,popular videos results */    
    return array ( $featuredvideos, $recentvideos, $popularvideos );
  }  
  
  /**
   * Function to get video detail for HTML5 Player
   *
   * @param int $videoId
   *          video id
   *          
   * @return object
   */
  public function getHTMLVideoDetails($videoId) {
   global $tableField;
   $fields = array_merge($tableField, array(SEO_CATEGORY));
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    if (ADMINVIEW) {
      $publish = '';
    } else {
      $publish = $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' );
    }
    
    if ($publish != '') {
      $query->where ( $publish );
    }
    
    if (isset ( $videoId ) && $videoId != '') {
      $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $videoId ) )->order ( $db->escape ( ORDERING . ' ' . 'ASC' ) );
    } else {
      $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( FEATURED ) . ' = ' . $db->quote ( '1' ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( ORDERING . ' ' . 'ASC' ) );
    }
   
    $query->select ( array ( 'a.*', SEO_CATEGORY ) )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOIN );
    
    /** Query is to select the popular videos row */
    $db->setQuery ( $query );
    $rows = $db->LoadObject ();
    
    if (empty ( $videoId ) && count ( $rows ) == 0) {
      $query->clear ()->select ( $fields )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . ' d ON a.memberid=d.id' )->leftJoin ( VIDEOCATEGORYTABLE . ' e ON e.vid=a.id' )->leftJoin ( CATEGORYTABLE . ' b ON e.catid=b.id' );
      
      if ($publish != '') {
        $query->where ( $publish );
      }
      
      $query->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( 'a.id' . ' ' . 'DESC' ) );
      
      /** Query is to display recent videos in home page */
      $db->setQuery ( $query, 0, 1 );
      $rows = $db->LoadObject ();
    }
    
    return $rows;
  }
  
  /**
   * Function to get video access level
   *
   * @return string
   */
  public function getHTMLVideoAccessLevel() {
   global $tableField;
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );    
    $videoid = 0;
    
    /** Code for seo option or not - start */
    $video = JRequest::getVar ( 'video' );
    $id = JRequest::getInt ( 'id' );
    $flagVideo = is_numeric ( $video );
    
    $fields =  array ( 'DISTINCT a.*', 'b.category' );
    if (isset ( $video ) && $video != "") {
      if ($flagVideo != 1) {
        /** Joomla router replaced to : from - in query string */
        $videoTitle = JRequest::getString ( 'video' );
        $videoSEOVal = str_replace ( ':', '-', $videoTitle );
        
        if ($videoSEOVal != "") {
          $videoid = $videoSEOVal;
        }
        $query->clear ()->select ( $fields )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( CATEGORYTABLE . ' AS b ON a.playlistid=b.id  OR a.playlistid=b.parent_id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( SEOTITLE ) . ' = ' . $db->quote ( $videoid ) );
        $db->setQuery ( $query );
        $rowsVal = $db->loadAssoc ();
      } else {
        $videoid = JRequest::getInt ( 'video' );
        $query->clear ()->select ( $fields )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( CATEGORYTABLE . ' AS b ON a.playlistid=b.id  OR a.playlistid=b.parent_id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $videoid ) );
        $db->setQuery ( $query );
        $rowsVal = $db->loadAssoc ();
      }
    } elseif (isset ( $id ) && $id != '') {
      $videoid = JRequest::getInt ( 'id' );
      $query->clear ()->select ( $fields )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( CATEGORYTABLE . ' AS b ON a.playlistid=b.id  OR a.playlistid=b.parent_id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $videoid ) );
      $db->setQuery ( $query );
      $rowsVal = $db->loadAssoc ();
    }     

    /** Code for seo option or not - end */
    else {
      $query->clear ()->select ( $tableField )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOIN )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( FEATURED ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( ORDERING . ' ' . 'ASC' ) );
      
      /** Query is to display recent videos in home page */
      $db->setQuery ( $query );
      $rowsVal = $db->loadAssoc ();
      
      if (count ( $rowsVal ) == 0) {
        $query->clear ( WHERE )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) );
        
        /** Query is to display recent videos in home page */
        $db->setQuery ( $query, 0, 1 );
        $rowsVal = $db->loadAssoc ();
      }
    }    
    return getUserAccessLevel( $rowsVal['useraccess'], ADMINVIEW ); 
  }
  
  /**
   * Function to get initial video details
   *
   * @return array
   */
  public function initialPlayer() {
   global $tableField;
    $videoid = 0;
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    $videoparam = JRequest::getInt ( 'video' );
    $idparam = JRequest::getInt ( 'id' );
    $fields =  array ( 'DISTINCT a.*', 'b.category' );
    if ($idparam || $videoparam) {
      if ($videoparam) {
        $videoid = $videoparam;
      } else {
        $videoid = JRequest::getInt ( 'id' );
      }
      
      if ($videoid != "") {
        $query->clear()->select ( $fields )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( CATEGORYTABLE . ' AS b ON a.playlistid=b.id  OR a.playlistid=b.parent_id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $videoid ) );
        $db->setQuery ( $query );
        $rowsVal = $db->loadAssoc ();
      }
    } elseif (JRequest::getString ( 'video' )) {
      $video_string = JRequest::getString ( 'video' );
      $video = str_replace ( ':', '-', $video_string );
      $query->clear ()->select ( $fields )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( CATEGORYTABLE . ' AS b ON a.playlistid=b.id  OR a.playlistid=b.parent_id' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( SEOTITLE ) . ' = ' . $db->quote ( $video ) );
      $db->setQuery ( $query );
      $rowsVal = $db->loadAssoc ();
    } else {
      $query->clear ()->select ( $tableField )->from ( PLAYERTABLE . UPLOADTABLEASA )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )->leftJoin ( VIDEOCATEGORYTABLE . VIDEOCATELEFTJOIN )->leftJoin ( CATEGORYTABLE . CATBLEFTJOIN )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( FEATURED ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) )->group ( $db->escape ( CATVIDEOID ) )->order ( $db->escape ( ORDERING . ' ' . 'ASC' ) );
      
      /** Query is to display recent videos in home page */
      $db->setQuery ( $query );
      $rowsVal = $db->loadAssoc ();
      
      if (count ( $rowsVal ) == 0) {
        $query->clear ( WHERE )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( TYPE ) . ' = ' . $db->quote ( '0' ) );
        
        /** Query is to display recent videos in home page */
        $db->setQuery ( $query, 0, 1 );
        $rowsVal = $db->loadAssoc ();
      }
    }
    
    return $rowsVal;
  }
 
 /**
  * Get playlist already added check function query datas
  * 
  * @return <mixed>
  */
 public function getvideoplaylists($vID) {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    $query->clear()->select($db->quoteName('catid'))->from( VIDEOPLAYLISTTABLE)->where ( $db->quoteName ( 'vid' ) . ' = ' . $db->quote ( $vID ) );
    $db->setQuery ( $query );
    return $db->loadColumn ();
 }
}