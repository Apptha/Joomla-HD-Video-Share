<?php
/**
 * Helper file for Contus HD Video Share
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Include common helper file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'chanHelper.php');

/**
 * Get site settings from database
 *
 * @return array
 */
function getSiteSettings () {
  /** Get database connection */
  global $contusDB,$contusQuery;

  /** Get site settings from database */
  $contusQuery->clear ()->select ( $contusDB->quoteName ( array ( DISPENABLE ) ) )->from ( $contusDB->quoteName ( SITESETTINGSTABLE ) )->where ( $contusDB->quoteName ( 'id' ) . ' = ' . $contusDB->quote ( '1' ) );
  /** Execute query */
  $contusDB->setQuery ( $contusQuery );
  /** Store db values */
  $resultSetting = $contusDB->loadResult ();
  /** return serialized site settings */
  return unserialize ( $resultSetting );
}

/**
 * Get player settings from database
 *
 * @param string $type
 *
 * @return mixed
 */
function getPlayerIconSettings ( $type ) {
  /** Get database connection */
  global $contusDB, $contusQuery;

  if($type == 'both') {
    $contusQuery->clear ()->select ( array ( 'player_values', 'player_icons' ) )->from ( PLAYERSETTINGSTABLE );
    $contusDB->setQuery ( $contusQuery );
    return $contusDB->loadObject ();
  } else {
    /** Get player settings */
    if($type == 'icon') {
      $contusQuery->clear ()->select ( 'player_icons' )->from ( PLAYERSETTINGSTABLE );
    } else {
      $contusQuery->clear ()->select ( 'player_values' )->from ( PLAYERSETTINGSTABLE );
    }
    /** Execute query */
    $contusDB->setQuery ( $contusQuery );
    /** Store db values */
    $rs_settings = $contusDB->loadResult ();
    /** return serialized player settings */
    return unserialize ( $rs_settings );
  }
}

/**
 * Function to get thumb settings
 *
 * @return array
 */
function getpagerowcol () {
  /** Query to get thumb ettings */
  global $contusDB, $contusQuery;

  /** Query is to fetch row, col settings */
  $contusQuery->clear()->select ( array ( 'thumbview', DISPENABLE ) )->from( SITESETTINGSTABLE )->where ( $contusDB->quoteName ( 'id' ) . ' = ' . $contusDB->quote ( '1' ) );
  $contusDB->setQuery ( $contusQuery );
  /** Return thumb view and dispenable settings */
  return $contusDB->LoadObjectList ();
}


/**
 * Function to get home page bottom settings
 *
 * @return array
 */
function gethomepagebottomsettings () {
  global $contusDB, $contusQuery;

  /** Query is to select the home page botom videos settings */
  $contusQuery->clear()->select ( array ( 'homethumbview', DISPENABLE ) )->from ( SITESETTINGSTABLE );
  $contusDB->setQuery ( $contusQuery );
  return $contusDB->LoadObjectList ();
}

/**
 * Fucntion to get user details
 * 
 * @return Ambigous <mixed, NULL>
 */
function getUserDetails () {
  /** Get db connection to detch user details */
  global $contusDB, $contusQuery;

  /** Query to get user details */
  $contusQuery->clear ()->select ( array ( 'd.email', 'd.username'  ) )->from ( USERSTABLE . ' AS d' )->where ( $contusDB->quoteName ( 'd.id' ) . ' = ' . $contusDB->quote ( (int) getUserID () ) );
  $contusDB->setQuery ( $contusQuery );
  
  /** Return user details */ 
  return $contusDB->loadObject ();
}

/**
 * Function to get cureent user group
 * 
 * @return Ambigous <mixed, NULL>
 */
function getUserGroup () {
  /** Get db connection to get user group */
  global $tablePrefix, $contusDB, $contusQuery;
  
  /** Get user groups from joomla version above 1.6.0 */
  if (version_compare ( JVERSION, VERSIONCOMPARE, 'ge' )) {
    /** Query items are returned as an associative array */
    $contusQuery->clear ()->select ( 'g.id AS group_id' )->from ( $tablePrefix . 'usergroups AS g' )->leftJoin ( '#__user_usergroup_map AS map ON map.group_id = g.id' )->where ( 'map.user_id = ' . ( int ) getUserID () );
  }  else {    
    /** Get user groups from joomla version below 1.6.0 */
    $contusQuery->clear ()->select ( 'gid' )->from ( USERSTABLE )->where ( 'id = ' . ( int ) getUserID () );
  }
  $contusDB->setQuery ( $contusQuery );
  
  /** Return user group detials */
  return $contusDB->loadObject ();
}


/**
 * Function to get current user access id
 *
 * @return mixed
 */
function getUserAccessID (){
  /** Get database and user object */
  global $tablePrefix, $contusDB, $contusQuery, $loggedUser;

  /** Check joomla version to fetch access id */
  if (version_compare ( JVERSION, VERSIONCOMPARE, 'ge' )) {
    $uid  = (int) getUserID ();
    if ($uid) {
      /** Get access id for the current user */
      $contusQuery->clear()->select ( 'g.id AS group_id' )->from ( $tablePrefix . 'usergroups AS g' )->leftJoin ( $tablePrefix .'user_usergroup_map AS map ON map.group_id = g.id' )->where ( 'map.user_id = ' . ( int ) $uid );
      $contusDB->setQuery ( $contusQuery );
      $message = $contusDB->loadObjectList ();

      foreach ( $message as $mess ) {
        $accessid [] = $mess->group_id;
      }
    } else {
      $accessid [] = 1;
    }
  } else {
    $accessid = $loggedUser->get ( 'aid' );
  }
  /** Return access id */
  return $accessid;
}


/**
 * Function to get user access level
 *
 * @param int $useraccess
 * @param int $adminview
 *
 * @return string
 */
function getUserAccessLevel ( $useraccess, $adminview ) {
  /** Get db connection to get user access level */
  global $tablePrefix, $contusDB, $contusQuery;
  $member = VS_TRUE;

  /** Get user access id */
  $accessid = getUserAccessID ();

  if (version_compare ( JVERSION, VERSIONCOMPARE, 'ge' )) {
    $member = VS_FALSE;
    if ($useraccess == 0) {
      $useraccess = 1;
    }

    $contusQuery->clear()->select ( 'rules as rule' )->from ( $tablePrefix . 'viewlevels AS view' )->where ( 'id = ' . ( int ) $useraccess );
    $contusDB->setQuery ( $contusQuery );
    $message = $contusDB->loadResult ();
    $accessLevel = json_decode ( $message );

    foreach ( $accessLevel as $useracess ) {
      if (in_array ( "$useracess", $accessid ) || $useracess == 1) {
        $member = VS_TRUE;
        break;
      }
    }
  } else {
    if ($useraccess != 0 && $accessid != $useraccess && $accessid != 2) {
      $member = VS_FALSE;
    }
  }

  /** Check admin is a user */
  if (! empty ( $adminview )) {
    $member = VS_TRUE;
  }
  /** Return member value */
  return $member;
}

/**
 * Function to get html video access level
 * 
 * @param string $tableName
 *
 * @return string
 */
function getHTMLVideoAccessLevel ( $tableName ) {
 $db = JFactory::getDBO ();
 $query = $db->getQuery ( true );

 $category = JRequest::getString ( CATEGORY );
 /** Code for seo option or not - start */
 $flatCatid = is_numeric ( $category );

 if ($category && $flatCatid != 1) {
  $catvalue = str_replace ( ':', '-', $category );
  $query->clear ()->select ( 'id' )->from ( CATEGORYTABLE )->where ( 'seo_category = ' . $db->quote ( $catvalue ) );
  $db->setQuery ( $query );
  $catid = $db->loadResult ();
 } elseif ($flatCatid == 1) {
  $catid = $category ;
 } elseif ( JRequest::getInt ( CATID ) ) {
  $catid = JRequest::getInt ( CATID );
 } else {
  /** This query is for category view pagination */
  $query->clear ()->select ( 'id' )->from ( CATEGORYTABLE )->where ( $db->quoteName ( PUBLISH ) . ' = ' . $db->quote ( '1' ) )->order ( $db->escape ( 'category ASC' ) );
  $db->setQuery ( $query );
  $searchtotal1 = $db->loadObjectList ();

  /** Category id is stored in this catid variable */
  $catid = $searchtotal1 [0]->id;
 }

 if (! version_compare ( JVERSION, JOOM3, 'ge' )) {
  $catid = $db->getEscaped ( $catid );
 }

 /** Query to calculate total number of videos in paricular category */
 $query->clear ()->select ( array ( 'a.*', 'b.id as cid', 'b.category','b.seo_category', 'b.parent_id', 'c.*'
 ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( USERSTABLE . VIDEOMEMBERLEFTJOIN )
 ->leftJoin ( $tableName . ' AS c ON c.vid=a.id' )->leftJoin ( CATEGORYTABLE . ' AS b ON c.catid=b.id' )
 ->where ( '(' . $db->quoteName ( 'c.catid' ) . ' = ' . $db->quote ( $catid ) . ' OR ' . $db->quoteName ( 'b.parent_id' ) . ' = ' . $db->quote ( $catid ) . ' OR ' . $db->quoteName ( 'a.playlistid' ) . ' = ' . $db->quote ( $catid ) . ')' )
 ->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) . ' AND ' . $db->quoteName ( CATPUBLISH ) . ' = ' . $db->quote ( '1' ) )
 ->where ( $db->quoteName ( USERBLOCK ) . ' = ' . $db->quote ( '0' ) )
 ->order ( $db->escape ( 'b.id ASC' ) );
 $db->setQuery ( $query );
 $rowsVal = $db->loadAssoc ();

 /** Get category access level from helper */
 return getUserAccessLevel ($rowsVal['useraccess'], '' );
}

/**
 * Function to get category id
 * 
 * @param string
 * @param string $table
 * @param string $field
 * @param string $param
 * 
 * @return int
 */
function getcategoryid( $paramName, $table, $field, $param ) { 
	global $contusDB, $contusQuery;
	/** Get category param from request */
	$flatCatid = is_numeric ( $paramName );

	if ($paramName && $flatCatid != 1) {
		$catvalue = str_replace ( ':', '-', $paramName );

		if (! version_compare ( JVERSION, JOOM3, 'ge' )) {
			$catvalue = $contusDB->getEscaped ( $catvalue );
		}
		
		/** Get category id from db based on the title */
		$contusQuery->clear()->select ( 'id' )->from ( $table )->where ( $contusDB->quoteName ( $field ) . ' = ' . $contusDB->quote ( $catvalue ) );
		$contusDB->setQuery ( $contusQuery );
		$catid = $contusDB->loadResult ();
	} elseif ($flatCatid == 1) {
		$catid = $paramName;
	} elseif ( $param ) {
		$catid = $param;
	} else {
		/** This query is for category view pagination */
		$contusQuery->clear()->select ( 'id' )->from ( $table )->where ( $contusDB->quoteName ( PUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )->order ( $contusDB->escape ( 'category ASC' ) );
		$contusDB->setQuery ( $contusQuery );
		$searchtotal1 = $contusDB->loadObjectList ();
		$catid = $searchtotal1 [0]->id;
	}
	
	/** Return category id */
	return $catid;
}

/**
 * Function to get category data
 * 
 * @param string $table1
 * @param string $table2
 * @param string $field
 * 
 * @return mixedarray
 */
function getcategoryList( $table1, $table2, $field ) {
	global $contusDB, $contusQuery;
	
	/** Get category id from category model */
	if($field == 'playlistid') {	
		$catid = getcategoryid( JRequest::getString ( CATEGORY ), CATEGORYTABLE, SEOCATEGORY, JRequest::getInt ( CATID ));
	}
	if($field == 'catid') {
		$catid = getcategoryid( JRequest::getString( PLAYLIST ), PLAYLISTTABLE, CATEGORY, JRequest::getInt ( PLAYID ) );
	}
	
	if (! version_compare ( JVERSION, JOOM3, 'ge' )) {
		$catid = $contusDB->getEscaped ( $catid );
	}

	/** Get exact category details
	 * Query is to select the popular videos row */
	$contusQuery->clear()->select ( array ( 'DISTINCT(a.id)', 'a.*', 'b.id AS vid') )->from ( $table1 . VIDEOTABLECONSTANT )
	->leftJoin ( $table2 . ' AS b ON b.'. $field . ' = a.id' )
	->where ( $contusDB->quoteName ( 'a.id' ) . ' = ' . $contusDB->quote ( $catid ) )->group ( $contusDB->escape ( 'a.id' ) );
	$contusDB->setQuery ( $contusQuery );
	$catgoryrows = $contusDB->LoadObjectList ();

	/** Get parent category details
	 * Query is to select the popular videos row */
	$contusQuery->clear()->select ( array ( 'DISTINCT(a.id)', 'a.*', 'b.id AS vid') )->from ( $table1 . VIDEOTABLECONSTANT )
	->leftJoin ( $table2 . ' AS b ON b.'. $field . ' = a.id' )
	->where ( $contusDB->quoteName ( 'a.parent_id' ) . ' = ' . $contusDB->quote ( $catid ) )->group ( $contusDB->escape ( 'a.id' ) )->order ( $contusDB->escape ( 'ordering' ) );
	$contusDB->setQuery ( $contusQuery );
	$parentrows = $contusDB->LoadObjectList ();
	return array_merge ( $catgoryrows, $parentrows );
}

/**
 * Function to update view count
 *
 * @param int $vid
 * 
 * @return void
 */
function updateViewCount ( $vid ) {
  /** Get database connection to update view count */
  global $contusDB, $contusQuery;

  /** Query to update view count */
  $contusQuery->clear()->update ( $contusDB->quoteName ( PLAYERTABLE ) )->set ( array ( $contusDB->quoteName ( 'times_viewed' ) . '= 1+times_viewed' ) )->where ( $contusDB->quoteName ( 'id' ) . ' = ' .  ( int ) $vid  );
  /** Execute query to update views count in db */
  $contusDB->setQuery ( $contusQuery );
  $contusDB->query ();
}

/**
 * Function to get itemid of the video share menu
 *
 * @return int
 */
function getmenuitemid_thumb( $type, $catid ) {
  /** Get db connection to fetch item id */
  global $tablePrefix, $contusDB, $contusQuery;
  
  if($type == CATEGORY && !empty($catid)){
    $type = 'category&catid=' . $catid;
  }

  /** Query is to get itemid of the video share menu */
  $contusQuery->clear()->select ( 'id' )->from ( $tablePrefix . 'menu' )->where ( $contusDB->quoteName ( 'link' ) . ' = ' . $contusDB->quote ( 'index.php?option=com_contushdvideoshare&view=' . $type ) . ' AND ' . $contusDB->quoteName (PUBLISH ) . ' = ' . $contusDB->quote ( '1' ) )->order ( 'id DESC' );
  $contusDB->setQuery ( $contusQuery );
  /** Return menu item id */
  return $contusDB->loadResult ();
}

/**
 * Function to get video ad details
 *
 * @param int $adID
 * @param string $adType
 * 
 * @return mixed
 */
function getPrePostAdDetails ( $adID, $adType ) {
  /** Get database connection to fetch pre / post ad details */
  global $contusDB, $contusQuery;

  /** Query to fetch ad details */
  $contusQuery->clear ()->select ( '*' )->from ( ADSTABLE )->where ( $contusDB->quoteName ( PUBLISH ) . ' = ' . $contusDB->quote ( '1' ) );
  /** Check ad type is empty */
  if ($adType == '') {
    /** Fetch pre or post roll ad details */
    $contusQuery->where ( $contusDB->quoteName ( 'id' ) . ' = ' . $contusDB->quote ( $adID ) );
  } else {
    /** Fetch mid roll or ima ad details */
    $contusQuery->where ( $contusDB->quoteName ( 'typeofadd' ) . ' = ' . $contusDB->quote ( $adType ) );
  }
  $contusDB->setQuery ( $contusQuery );
  /** Return ads detail */
  return $contusDB->loadObjectList ();
}

/**
 * Fucntion to get click hits URL
 *
 * @param string $clickurl
 *
 * @return string
 */
function getClickURL ( $clickurl ) {
  /** Get click hits URL */
  $defined_clickpath = JURI::base () . '?option=com_contushdvideoshare&view=impressionclicks&click=click';

  $clickpath = $defined_clickpath;
  if (! empty ( $clickurl )) {
    $clickpath = $clickurl;

    if (! preg_match ( "~^(?:f|ht)tps?://~i", $clickpath )) {
      $clickpath = "http://" . $clickpath;
    }
  }
  /** Return click url  */
  return $clickpath;
}

/**
 * Fucntion to get impression hits URL
 *
 * @param string $impressionurl
 *
 * @return string
 */
function getImpressionURL ( $impressionurl ) {
  /** Get impression hits URL */
  $defined_impressionpath = JURI::base () . '?option=com_contushdvideoshare&view=impressionclicks&click=impression';
  $impressionpath = $defined_impressionpath;
  if (! empty ( $impressionurl )) {
    $impressionpath = $impressionurl;

    if (! preg_match ( "~^(?:f|ht)tps?://~i", $impressionpath )) {
      $impressionpath = "http://" . $impressionpath;
    }
  }
  /** Return impression path */
  return $impressionpath;
}

/**
 * Fucntion is used to display playlists
 * 
 * @param <mix array> $playlists
 * @param array $uservideoplaylist
 * @param string $containerType
 * @param int $vID
 */
function displayPlaylists ( $playlists, $uservideoplaylist, $containerType, $vID ) { 
  if( $playlists ) {
      foreach ( $playlists as $playlist ) {
        $append = '';
        $title  = 'title="' . ucfirst ( $playlist->category ) . '"';
        if ($playlist->published == 0) {
          $append = 'disabled';
          $title  = 'title="' . ucfirst ( $playlist->category ) . ' ' . JText::_ ( 'HDVS_PLAYLIST_DISABLED' ) . '"';
        }
        ?>
<li style="display: block"><input type="checkbox" <?php echo $append; ?>
	<?php echo $title?> value="<?php echo  $playlist->id; ?>"
	onChange="return addVideoToplaylist(this,'<?php echo $containerType;?>', 
    '<?php echo  $vID; ?>',
    '<?php echo $playlist->id; ?>'); "
	id="checkbox-video<?php echo  $vID; ?>"
	<?php if( in_array( $playlist->id , $uservideoplaylist ) ) { 
      echo 'checked="checked"';
    } ?>> <span class="playlistlabel"><?php echo ucfirst( $playlist->category ) ; ?></span></li>
<?php /** End Foreach */ 
    } 
  }
}

/**
 * Function to get all published playlist for current users
 * 
 * @return  <mix array>
 */
function getuserplaylists () {
  global $contusDB, $contusQuery;
  $playLists  = '';
  
  /** Get current user id using helper function */
  $userId     =  (int) getUserID ();
  
  /** Check user id is exists or not */ 
  if( $userId ) {
    /** Get home page settings from site settings */
    $setting        = gethomepagebottomsettings ();
    /** Unserialize settings data and and get playlist limit */
    $dispenable     = unserialize ( $setting [0]->dispenable );
    $playlistlimit  = $dispenable ['playlist_limit'];
    
    /** Get playlist details for the given user */
    $contusQuery->clear()->select ( array ( 'id', 'category', PUBLISH ) )->from ( PLAYLISTTABLE )->where ( $contusDB->quoteName ( 'member_id' ) . ' = ' . $contusDB->quote ( $userId ) . ' AND published!=-2' );
    $contusDB->setQuery ( $contusQuery, 0, $playlistlimit );
    $playLists = $contusDB->loadObjectList();
  }
  return $playLists;
}

/**
 * Function to generate particular video page URL
 * 
 * @param int $Itemid
 * @param object $videoData
 * 
 * @return string
 */
function generateVideoTitleURL ( $Itemid, $videoData, $type ) {
  $categoryVal = $videoVal = $catgoryVideo = '';
  $siteSettings = getpagerowcol ();
  $dispenable   = unserialize ( $siteSettings[0]->dispenable );
  $seoOption    = $dispenable ['seo_option'];

  if( isset($videoData)) {
    if( $type != 'playlist' ) {
      $catgoryVideo = '';
      /** Get video title based on the seo option
       * Get cat id and video id */
      if ($seoOption == 1) {
        $categoryVal  = "category=" . $videoData->seo_category;
        $videoVal     = "video=" . $videoData->seotitle;
      } else {
        $categoryVal  = "catid=" . $videoData->catid;
        $videoVal     = "id=" . $videoData->vid;
      } 
    } else {
      /** Check SEO option enabled or not */
      if ($seoOption == 1){
        /** Set category URL when SEO option enabled */
        $categoryVal = "playlist=" . $videoData->seo_category;
        /** Set video values when SEO option enabled */
        $videoVal = "video=" . $videoData->seotitle;
        /** Set play value when SEO option enabled */
        $catgoryVideo = "play=1";
      }
      else{
        /** Set category URL when SEO option disabled */
        $categoryVal = "playid=" . $videoData->catid;
        /** Set video values when SEO option disabled */
        $videoVal = "id=" . $videoData->id;
        /** Set play value when SEO option disabled */
        $catgoryVideo = "play=1";
      }
    }
  }
  $pageURL = "index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&amp;view=player&amp;" . $categoryVal . "&amp;" . $videoVal;  
  return JRoute::_($pageURL . "&amp;" . $catgoryVideo);
}

/**
 * Function is used to display watch later, add to playlist icon on the thumb image 
 * 
 * @param int $Itemid
 * @param string $src_path
 * @param object $videoData
 * @param string $type
 * @param int $modType
 * 
 * @return mixed
 */
function displayVideoThumbImage ( $Itemid, $srcPath, $videoData, $type, $modType ) { 
  /** Get current user ID */
  $userId = (int) getUserID ();

  $class = 'info_hover';
  /** Check modtype as 100. If it is 100, the page is component page. 
   * Add class name if it is module
   */
  if($modType == 100) {
    $class = '';
  }
  
  $VideoPageURL = generateVideoTitleURL ( $Itemid, $videoData, $type );
  /** Display thumb images as hyperlink */
  ?>
<a class="<?php echo $class . ' '; ?>featured_vidimg" <?php if($type != 'myvideos') { ?>
	rel="htmltooltip" 
	<?php } ?> href="<?php echo $VideoPageURL; ?>"> 
	<img class="yt-uix-hovercard-target" src="<?php echo $srcPath; ?>" title="" alt="thumb_image" <?php if($type == 'myvideos') { ?>
	border="0" 
	<?php } ?>/>
	
    <?php if(!empty($userId) && !empty( $videoData->VideoId )) {
     if( $modType!=7 && $modType!=100) {  ?>
      <div class="watched_module_overlay"></div>
<?php } else { 
if($type != 'history') {?>
  <div class="watched_overlay"></div>
<?php }
}
}?> 
  </a>
<?php  
/** Display watch later icon except watch later pages */
if($type != 'later') {
/** Check user id is exist or not.
 * If user id is exist, then display watch later icon */ 
    if(!empty($userId) && $modType!=6) { 
        if(empty($videoData->video_id)){ ?>
<a class="watch_later_wrap" href="javascript:void(0)"
	onclick="addWatchLater(<?php echo $videoData->vid; ?>,'<?php echo JURI::base(); ?>', this)">
	<span class="watch_later default-watch-later"
	title="<?php echo JText::_ ( 'HDVS_ADD_TO_LATER_VIDEOS' );?>"></span>
</a>
<?php } else { ?>
<a class="watch_later_wrap" href="javascript:void(0)"> <span
	class="watch_later success-watch-later"
	title="<?php echo JText::_ ( 'HDVS_ADDED_TO_LATER_VIDEOS' );?>"></span>
</a>
<?php } 
    } 
}
    
    /** Display Add to playlist icon on the thumb image */ ?>
<a href="javascript:void(0)"
	onclick="return openplaylistpopup('<?php echo $type; ?>',<?php echo $videoData->vid; ?>)"
	class="add_to_playlist_wrap"> <span class="add_to_playlist"
	title="<?php echo JText::_ ( 'HDVS_ADD_TO_PLAYLIST' );?>"></span>
</a>
<?php 
}

/** 
 * Function is used to display playlist popup on the video thumbs
 * 
 * @param object $videoData
 * @param string $type
 * @param int    $Itemid
 * 
 * @return mixed
 */
function displayPlaylistPopup ( $videoData, $type, $Itemid ) {
  $scrolClass = '';
  $count = 0;

  /** Set element's id attribute name */ 
  $containerAttr  = $type . '_playlistcontainer' . $videoData->vid; 
  $statusAttr     = $type . 'playliststatus' . $videoData->vid;
  $playlistsAttr  = $type . '_playlists' . $videoData->vid;
  $nolistAttr     = $type . '_no-playlists' . $videoData->vid; 
  $buttonAttr     = $type . '_playlistadd' . $videoData->vid;
  $addAttr        = $type . '_addplaylistform' . $videoData->vid;
  $plNameAttr     = $type . '_playlistname_input'. $videoData->vid;
  $responseAttr   = $type . '-playlistresponse-'. $videoData->vid;
  $saveBtnAttr    = $type . '_button-save-home'. $videoData->vid; 
  $loadingAttr    = $type . '_playlistname_loading-play'. $videoData->vid; 
  $restrictAttr   = $type . '_restrict'. $videoData->vid;

  /** Get user id */
  $userId = (int) getUserID();  
  $count = getPlaylistCount ();
  if ( $count > 5 ) {
    $scrolClass = ' popup_scroll';
  }
  
  $resultURL = generateLoginRegisterURL ();
  if( !empty( $resultURL)) {
    $loginURL    = $resultURL[0];
    $registerURL =  $resultURL[1];
  }
?>
<div id="<?php echo $containerAttr; ?>" class="addtocontentbox"
	style="display: none">
	<div id="<?php echo $statusAttr; ?>" class="playliststatus"
		style="display: none"></div>
	<p><?php echo JText::_('HDVS_PLAYLIST_ADD_NE'); ?></p>
	<ul id="<?php echo $playlistsAttr; ?>" class="playlists_ul<?php echo $scrolClass; ?>"></ul>
  
  <?php /** Check user id is exists */ 
  if( $userId ) { ?>
    <div id="<?php echo $nolistAttr; ?>" class="no-playlists"></div>

	<div class="create_playlist border-top:2px solid gray;">
		<button id="<?php echo $buttonAttr; ?>"
			onclick="opencrearesection('<?php echo $type; ?>',
      <?php echo $videoData->vid; ?>);"
			class="button playlistadd">
      <?php echo JText::_('HDVS_ADDPLAYLIST_LABEL'); ?></button>

		<div class="addplaylist" id="<?php echo $addAttr; ?>"
			style="display: none">
			<input type="text" value=""
				placeholder="<?php echo JText::_('HDVS_PLAYLIST_NAME_ERROR')?>"
				class="play_textarea" name="playlistname"
				id="<?php echo $plNameAttr; ?>" autocomplete="off" autofocus="on"
				onkeyup="if (event.keyCode != 13) return addplaylist('<?php echo $videoData->vid; ?>',
        '<?php echo $type; ?>');" 
        onkeydown="if (event.keyCode == 13) document.getElementById('<?php echo $saveBtnAttr;?>').click()"/> <span
				id="<?php echo $responseAttr; ?>" style="float: left; width: 100%;"></span>

			<input type="button" id="<?php echo $saveBtnAttr; ?>"
				class="playlistaddform-hide-btn"
				onclick="return ajaxaddplaylist('<?php echo $videoData->vid; ?>',
        '<?php echo $type; ?>');"
				value="<?php echo JText::_('HDVS_MY_ADDTO_SAVE_LABEL');?>">

			<div id="<?php echo $loadingAttr; ?>"></div>
		</div>
	</div>
	
	<div id="<?php echo $restrictAttr; ?>"
		name="<?php echo $restrictAttr; ?>" class="restrict" style="display: none">
		<p><?php echo  JText::_('HDVS_RESTRICTION_INFORMATION'); ?> 
        <a class="playlist_button" href="<?php	echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists"); ?>">
          <?php echo JText::_('HDVS_MY_PLAYLIST'); ?></a>
		</p>
	</div>
  <?php } else { ?>
      <div class="login-info">
		<p><?php echo JText::_('HDVS_PLAYLIST_ADD_LOGIN_INFORMATION2'); ?>&nbsp;&nbsp; 
          <a href="<?php echo JRoute::_( $loginURL );?>">
          <?php echo  JText::_('HDVS_LOGIN'); ?></a> | <a 
          href="<?php echo JRoute::_( $registerURL );?>">
          <?php echo  JText::_('HDVS_REGISTER'); ?></a>
		</p>
	</div>
  <?php } ?>
  </div>
<?php /** Function ends */ 
} 

/**
 * Function to display video title
 * 
 * @param int $Itemid
 * @param object $videoData
 * @param string $pageURL
 * 
 * @return mixed
 */
function displayVideoTitle ( $Itemid, $videoData, $type ) {
    /** Get row, column settings, seo option from site settings*/
    $pageURL = generateVideoTitleURL ( $Itemid, $videoData, $type );

  /** Display video titles in video results */
?>
<div class="show-title-container">
	<a href="<?php echo  $pageURL; ?>" class="show-title-gray info_hover">
  <?php /** Find substring for videos title */
    if (strlen ( $videoData->title ) > 50) {
      echo JHTML::_ ( 'string.truncate', ($videoData->title), 50 );
    } else {
      echo $videoData->title;
    }
    ?></a>
</div>
<?php }  

/**
 * Function to insert watch history of a user for a video in the database.
 *
 * @param integer $videoId The id of the video which the current user has watched.
 * @throws Exception
 * @return void
 */
function insertWatchHistory($videoId) {
  global $contusDB, $contusQuery, $loggedUser;
  /** Get current user id*/
  $userId = (int) getUserID();
  if($loggedUser->guest == 1) {
  		return;
  }
  
  /** Check whether the user is logged in or not */
  if(!empty($userId)) { 
    /** Check whether the cureent user record is exists in history table */
    $contusQuery->clear()->select (array ( 'userId', 'VideoId')) ->from( WATCHHISTORYTABLE ) ->where($contusDB->quoteName ('userId') . ' = ' . $contusDB->quote ( $userId ) )->where($contusDB->quoteName ('VideoId') . ' = ' . $contusDB->quote ( $videoId ) );
    $contusDB->setQuery( $contusQuery );
    $historyResult = $contusDB->loadobjectList();
    
    /** Get history view object */
    $HistoryModel = JModelLegacy::getInstance ('watchhistoryvideos', 'Modelcontushdvideoshare');
    $PauseResult = $HistoryModel->HistoryState();
    /** If user record in not available then insert new record */
    if(empty($historyResult) && $PauseResult != 1 ){
    		echo $videoId.' '. $userId;
    		$columns = array('userId', 'VideoId', 'watchedOn');
    		$values = array($contusDB->quote($userId), $contusDB->quote($videoId), $contusDB->quote(date('Y-m-d H:i:s')));
    		$contusQuery ->clear()->insert($contusDB->quoteName( WATCHHISTORYTABLE ))->columns($contusDB->quoteName($columns))->values(implode(',', $values));
    		$contusDB->setQuery($contusQuery);
    		if (!$contusDB->query()){
    		  throw new Exception($contusDB->getErrorMsg());
    		}
    } elseif(!empty($historyResult) && $PauseResult != 1) {
            /** Update new details with the existing user record */
    		$fields = array($contusDB->quoteName('watchedOn') . ' = '. $contusDB->quote(date('Y-m-d H:i:s')));
    		$conditions = array( $contusDB->quoteName('userId') . ' = ' . $userId, $contusDB->quoteName('VideoId') . ' = ' . $videoId );
    		$contusQuery->clear()->update($contusDB->quoteName( WATCHHISTORYTABLE ))->set($fields)->where($conditions);
    		$contusDB->setQuery($contusQuery);
    		if (!$contusDB->query()){
    		  throw new Exception($contusDB->getErrorMsg());
    		}
    }  
  }
  return;
}


/**
 * Function is used to get playlist details
 */
function getPlaylistDetails () {
  global $contusDB, $contusQuery;
  $userID =  (int) getUserID() ;
  $contusQuery->clear()->select(array('id', 'category','published', 'seo_category'))->from( PLAYLISTTABLE )->where($contusDB->quoteName('member_id') . ' = ' . $contusDB->quote( $userID ));
  $contusDB->setQuery($contusQuery);
  return $contusDB->loadObjectList();
}

/** 
 * Function is used to get playlist count
 */
function getPlaylistCount () {
  global $contusDB, $contusQuery;
  $userID =  (int) getUserID() ;
  $contusQuery->clear()->select('COUNT(*)')->from( $contusDB->quoteName( PLAYLISTTABLE ))->where( $contusDB->quoteName('member_id').'='.$userID );
  $contusDB->setQuery($contusQuery);
  return $contusDB->loadResult();
}
?>