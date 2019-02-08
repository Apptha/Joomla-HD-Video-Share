<?php
/**
 * Player view file
 *
 * This file is to display the player and video thumb images on video home and detail page. 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include copmonent helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

define ('PLAYER_VIDEO', 'video');

/**
 * Player view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewplayer extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewplayer object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get model for player view */
    $model = $this->getModel ();    
    /** Get video param from request */
    $video = JRequest::getVar ( PLAYER_VIDEO );
    /** Get category param from request */
    $categoryname = JRequest::getVar ( CATEGORY );
    /** Get Playlist  */
    $playname = JRequest::getVar('playlist');
    /** Get id param from request */
    $id = JRequest::getInt ( 'id' );
    /** Convert video param into int type */
    $flagVideo = is_numeric ( $video );
    /** Convert category param into int type */
    $flagcat = is_numeric ( $categoryname );    
    /** Check Playlist id is numeric */
    $flagplay     = is_numeric($playname);
    $addedplaylists = $model->getvideoplaylists($id);
    $this->assignRef('uservideoplaylists',$addedplaylists);
    if($id==""){    
    	$addedplaylists = $model->getvideoplaylists(1);
    	$this->assignRef('uservideoplaylists',$addedplaylists);
    }
    /** Check video id is exist */
    if (isset ( $video ) && $video != "") {
      /** Check video id is int or text */
      if (($flagVideo != 1 || $flagcat != 1) && $playname ==  '') {
          /** Get video title from param */ 
          $videoTitle = JRequest::getString ( PLAYER_VIDEO );
          $video = str_replace ( ':', '-', $videoTitle );
          /** Get category title from param */
          $category_name = JRequest::getString ( CATEGORY );
          $category = str_replace ( ':', '-', $category_name );       
          if (! empty ( $category ) && ! empty ( $video )) {
            /** Get video details based on the video, category params */
            $videodetails = $model->getVideoCatId ( $video, $category );
            /** Get video id, playlist id */ 
            $videoid = $videodetails->id;
            $categoryid = $videodetails->playlistid;
          } else {  
            /** Get video details based on the video param */
            $videodetails = $model->getVideoId ( $video, 'seo' );
            /** Get video id, playlist id and video url */
            $videoid = $videodetails->id;
            $categoryid = $videodetails->playlistid;
            $videodetails->videourl = $videodetails->videourl;
          }
      } else if (!empty($playname) && ($flagVideo != 1 || $flagplay != 1)) {
        	$videoTitle = JRequest::getString('video');
        	$video = str_replace(':', '-', $videoTitle);
        	$category_name = JRequest::getString('category');
        	$category = str_replace(':', '-', $category_name);
        	$play_name = JRequest::getString('playlist');
        	$play = str_replace(':', '-', $play_name);
        		
        	if (!empty($category) && !empty($video)) {
        		$videodetails = $model->getVideoCatId($video, $category);
        		$videoid = $videodetails->id;
        		$categoryid = $videodetails->playlistid;
        	}
        	if (!empty($play) && !empty($video)) {
        		$videodetails = $model->getVideoPlayId($video, $play);
        		$videoid = $videodetails->id;
        		$playid = $videodetails->playid;
        	} else{
        		$videodetails = $model->getVideoId($video);
        		$videoid = $videodetails->id;
        		$categoryid = $videodetails->playlistid;
        		$videodetails->videourl = $videodetails->videourl;
        	}
      } else { 
       /** Get video detail based on the video id, category id */
        $videoid = JRequest::getInt ( PLAYER_VIDEO );
        $videodetails = $model->getVideoId ( $videoid, '' );
        $categoryid = JRequest::getInt ( CATEGORY );
        $playid = JRequest::getInt('playlist');
        $videodetails->playid = $playid;
        $videodetails->id = $videoid;
        $videodetails->playlistid = $categoryid;
        $videodetails->videourl = $videodetails->videourl;
      }
      /** Assign my videos data into reference */
      $this->assignRef ( 'videodetails', $videodetails );
    } elseif (isset ( $id ) && $id != '') {
      /** Get video details absed on the non seo URL */ 
      $videoid = JRequest::getInt ( 'id' );
      $videodetails = $model->getVideoId ( $videoid, '' );
      $categoryid = JRequest::getInt ( 'catid' );
      $playid = JRequest::getInt('playid');
      $videodetails->playid = $playid;
      $videodetails->id = $videoid;
      $videodetails->playlistid = $categoryid;
      $videodetails->videourl = $videodetails->videourl;
      $this->assignRef ( 'videodetails', $videodetails );
    } else {
      $videoid = $categoryid = '';
      $videodetails = array ();
    }
    /** Code for html5 player */
    $htmlVideoDetails = $model->getHTMLVideoDetails ( $videoid );
    $this->assignRef ( 'htmlVideoDetails', $htmlVideoDetails );    
    /** Get featured video details for home page */
    $getfeatured = $model->getfeatured ();
    $this->assignRef ( 'getfeatured', $getfeatured );    
    /** Get player details */
    $detail = $model->showhdplayer ( $videoid );
    $this->assignRef ( 'detail', $detail );    
    /** Get ratting details for home page */
    $commentsview = $model->ratting ( $videoid );
    $this->assignRef ( 'commentview', $commentsview );
    /** Calling the function in models comment.php */
    $comments = $model->displaycomments ( $videoid );    
    /** Assigning the reference for the results */
    $this->assignRef ( 'commenttitle', $comments [0] );
    /** Assigning the reference for the results */
    $this->assignRef ( 'commenttitle1', $comments [1] );    
    /** Function call for fetching Itemid */
    $Itemid = getmenuitemid_thumb ('player', '');
    $this->assignRef ( 'Itemid', $Itemid );
    /**  Assigning the reference for the playlists */
    $playlists =  getuserplaylists();
    $this->assignRef('playlists', $playlists);    
    /** Calling the function in models homepagebottom.php */
    $homepagebottom = $model->gethomepagebottom ();
    /** Assigning the reference for the results */
    $this->assignRef ( 'rs_playlist1', $homepagebottom );    
    /** Calling the function in models homepagebottom.php */
    $homepagebottomsettings = gethomepagebottomsettings ();    
    /** Assigning the reference for the results ) */
    $this->assignRef ( 'homepagebottomsettings', $homepagebottomsettings );    
    /** Get access level details for home page */
    $homeAccessLevel = $model->getHTMLVideoAccessLevel ();
    $this->assignRef ( 'homepageaccess', $homeAccessLevel );    
    /** Get home page player data */
    $homePageFirst = $model->initialPlayer ();    
    $this->assignRef ( 'homePageFirst', $homePageFirst );
    parent::display ();
  }
}
