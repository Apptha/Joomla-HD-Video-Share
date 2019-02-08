<?php
/**
 * Router file for Contus HD Video Share
 *
 * This file will be called when the admine enables URL rewrite option
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
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
 * Function to assign router values
 *
 * @param
 *          object &$query query string
 *          
 * @return contushdvideoshareBuildRoute
 */
function contushdvideoshareBuildRoute(&$rquery) {
  $segments = array ();
  
  /** Code for get itemid if itemid is empty. 
   * It's used to add alias name in URL link */
  if (isset ( $rquery ['view'] )) {
    $segments [] = $rquery ['view'];
    unset ( $rquery ['view'] );
  }
  /** Check category id is exist in the URL */ 
  if (isset ( $rquery [CATID] ) || isset ( $rquery [CATEGORY] )) {
    if(isset ( $rquery [CATID] )) {
      $segments [] = $rquery [CATID];
      unset ( $rquery [CATID] );
    } else {
      $segments [] = $rquery [CATEGORY];
      unset ( $rquery [CATEGORY] );
    }
  }
  
  if (isset($rquery['playid'])) {
  	$segments[] = $rquery['playid'];
  	unset($rquery['playid']);
  } elseif (isset($rquery['playlist'])) {
  	$segments[] = $rquery['playlist'];
  	unset($rquery['playlist']);
  }
  
  /** Check video id is exist in the URL */
  if (isset ( $rquery ['id'] ) || isset ( $rquery [VIDEO] )) {
    if(isset ( $rquery ['id'] )) {
      $segments [] = $rquery ['id'];
      unset ( $rquery ['id'] );
    } else {
      $segments [] = $rquery [VIDEO];
      unset ( $rquery [VIDEO] );
    }
  }
  
  /** Check type is exist in the URL */
  if (isset ( $rquery ['type'] )) {
    $segments [] = $rquery ['type'];
    unset ( $rquery ['type'] );
  }
  
  if( isset($rquery['playlist_id'])) {
  	$segments[] =  $rquery['playlist_id'];
  	unset( $rquery['playlist_id']);
  }
  if (isset($rquery['play'])) {
  	$segments[] = $rquery['play'];
  	unset($rquery['play']);
  }
  
  if (isset($rquery['channel'])) {
  	$segments[] = $rquery['channel'];
  	unset($rquery['channel']);
  }
  
  if (isset($rquery['subscripe'])) {
  	$segments[] = $rquery['subscripe'];
  	unset($rquery['subscripe']);
  }
  
  if (isset($rquery['task'])) {
  	$segments[] = $rquery['task'];
  	unset($rquery['task']);
  }
  if (isset($rquery['ukey'])) {
  	$segments[] = $rquery['ukey'];
  	unset($rquery['ukey']);
  }
  
  return $segments;
}

/**
 * Function to assign view for the corresponding router value
 *
 * @param array $segments
 *          segments
 *          
 * @return contushdvideoshareParseRoute
 */
function contushdvideoshareParseRoute( $segments ) {
  $vars = array ();  
  /** View is always the first element of the array */
  $count = count ( $segments );  
  $appObj = JFactory::getApplication();
  $prefix = $appObj->getCfg('dbprefix');
  if(isset($segments[1])) {
  	if(strlen($segments[1])) {
  
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$query->select($db->quoteName('id'));
  		$query->from($db->quoteName($prefix.'hdflv_channel'));
  		$query->where($db->quoteName('user_key').' = '.$db->quote($segments[1]));
  		$db->setQuery($query);
  		$userId = $db->loadResult();
  	}
  	if(!empty($userId)) {
  		$vars['ukey'] = $segments[1];
  	}
  }
  if ($count) {
    switch ($segments [0]) {
      case 'channel':
      case 'addnewchannel':
      case 'subscripe':
    	 $vars['task'] = $segments[0];
    	 break;
      case 'category' :
        /** Set view as category */
        $vars ['view'] = CATEGORY;
        $vars [CATEGORY] = $segments [1];
        break;      
      case 'player' :
        /** Set view as player */
        $vars ['view'] = 'player';        
        if (isset($segments[3])) {
        	$vars['playlist'] = $segments[1];
        	$vars['video'] = $segments[2];
        }
        else if (isset($segments[2])) {
        	$vars['category'] = $segments[1];
        	$vars['video'] = $segments[2];
        }
        
        break;      
      case 'rss' :
        /** Set view as rss */
        $vars ['view'] = 'rss';        
        if (isset ( $segments [2] )) {
          $vars ['type'] = $segments [2];
          $vars [CATID] = $segments [1];
        } else {
          $vars ['type'] = $segments [1];
        }
        break;      
      case 'configxml' :
        $vars ['view'] = 'configxml';
        $vars ['id'] = $segments [1];        
        if (isset ( $segments [2] )) {
          $vars [CATID] = $segments [2];
        }
        break; 
      default:
        $vars = parseViews ( $segments );
        break;
    }
  }  
  return $vars;
}

/**
 * Function is used to parse views
 * 
 * @param unknown $segments
 * @return unknown
 */
function parseViews ( $segments ) {
  $vars = array ();
  switch ( $segments[0] ) {
    case 'playxml' :
      $vars ['view'] = 'playxml';
      $vars ['id'] = $segments [1];
      if (isset ( $segments [2] )) {
        $vars [CATID] = $segments [2];
      }
      break;
    case 'adsxml' :
      $adsxml = 'adsxml';
      $vars ['view'] = returnViewName ($adsxml);
      break;
    case 'midrollxml' :
      $midroll = 'midrollxml';
      $vars ['view'] = returnViewName ($midroll);
      break;
    case 'languagexml' :
      $langxml = 'languagexml';
      $vars ['view'] = returnViewName ($langxml);
      break;
    case 'playerbase' :
      $playerbase = 'playerbase';
      $vars ['view'] = returnViewName ($playerbase);
      break;
    case 'featuredvideos' :
      $feavideos = 'featuredvideos';
      $vars ['view'] = returnViewName ($feavideos);
      break;
    case 'watchlater':
   	  $watchlater = 'watchlater';
      $vars ['view'] = returnViewName ($watchlater);
      break;
    case 'watchhistoryvideos' :
      $hisvideos = 'watchhistoryvideos';
      $vars ['view'] = returnViewName ($hisvideos);
      break;
    case 'myvideos' :
      $myvideos = 'myvideos';
      $vars ['view'] = returnViewName ($myvideos);
      break;
    default:
      $vars = parseViewsRoute ( $segments );
      break;   
  }
  return $vars;
}

/**
 * Function is used to parse views route
 * 
 * @param unknown $segments
 * @return Ambigous <string, unknown>
 */
function parseViewsRoute ( $segments ) {
  $vars = array ();
  switch ( $segments[0] ) {
    case 'recentvideos' :
      $recvideos = 'recentvideos';
      $vars ['view'] = returnViewName ($recvideos);
      break;
    case 'addplaylist':
      $vars['view'] = 'addplaylist';
      if (isset($segments[1])) {
      	$vars['playlist_id'] = $segments[1];
      }
      break;
    case 'myplaylists':
      $vars['view'] = 'myplaylists';
      break;
    case 'playlist':
      $vars['view'] = 'playlist';
      $vars['playlist'] = $segments[1];
      break;
    case 'hdvideosharesearch' :
      $videosearch = 'hdvideosharesearch';
      $vars ['view'] = returnViewName ($videosearch);
      break;
    case 'membercollection' :
      $mbrcolectn = 'membercollection';
      $vars ['view'] = returnViewName ($mbrcolectn);
      break;
    case 'popularvideos' :
      $popvideos = 'popularvideos';
      $vars ['view'] = returnViewName ($popvideos);
      break;
    case 'relatedvideos' :
      /** Set view as relatedvideos */
      $vars ['view'] = 'relatedvideos';
      if (isset ( $segments [1] )) {
        $vars [VIDEO] = $segments [1];
      }
      break;
    case 'videoupload':
      /** Set view as videoupload */
      $vars ['view'] = 'videoupload';
      if (isset ( $segments [1] )) {
        $vars ['id'] = $segments [1];
      }
      if (isset ( $segments [2] )) {
        $vars ['type'] = $segments [2];
      }
      break;
    default:
      break;
  }
  return $vars;
}


/**
 * Function to return view name
 *  
 * @param unknown $viewText
 * @return unknown
 */
function returnViewName ($viewText) {
  /** Return view name */
  return $viewText;
}
