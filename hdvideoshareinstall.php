<?php
/**
 * Installation file of HD Video Share
 *
 * This file is to install component, modules and plugin
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access */
defined('_JEXEC') or die('Restricted access');

/** Import joomla installer */
jimport('joomla.installer.installer');

$installer = new JInstaller;
$upgra = $errorMsg = '';

/**
 * Function to alter table if column not exist
 * 
 * @param   string  &$errorMsg   error message
 * @param   string  $table       Table name
 * @param   string  $column      Column name
 * @param   string  $attributes  Column attribute
 * @param   string  $after       Column position
 * 
 * @return  addColumnIfNotExists
 */
function addColumnIfNotExists(&$errorMsg, $table, $column, $attributes = "INT( 11 ) NOT NULL DEFAULT '0'", $after = '')
{
	$db = JFactory::getDBO();
	$columnExists = false;
	$query = 'SHOW COLUMNS FROM ' . $table;

	$db->setQuery($query);

	if (!$result = $db->query()) {
		return false;
	}

	$columnData = $db->loadObjectList();

	foreach ($columnData as $valueColumn) {
		if ($valueColumn->Field == $column) {
			$columnExists = true;
			break;
		}
	}

	if (!$columnExists) {
		if ($after != '') {
			$query = 'ALTER TABLE ' . $db->nameQuote($table) . ' ADD ' . $db->nameQuote($column)
					. ' ' . $attributes . ' AFTER ' . $db->nameQuote($after) . ';';
		} else {
			$query = 'ALTER TABLE ' . $db->nameQuote($table) . ' ADD ' . $db->nameQuote($column) . ' ' . $attributes . ';';
		}

		$db->setQuery($query);

		if (!$result = $db->query()) {
			return false;
		}
		$errorMsg = 'notexistcreated';
	}
	return true;
}

/**
 * Function to create index.html file within the channel and banner directory
 *
 * @param string $folder
 *
 * @return void
 */
function createIndexFile ( $folder ) {
  /** Check index.html file is exists in the given folder */
  if(!file_exists($folder . DS . "index.html" )) {
    /** If not exists, then create new index.html file with the sample html code */
    $fp = fopen($folder . DS . "index.html","w");
    $content = "<html><head><title></title></head><body></body></html>";
    fwrite($fp, $content, strlen($content));
    fclose($fp);
  }
}

/**
 * Function to create channel and banner directory within joomla's images directory
 *
 * @return void
 */
function createChannelDir () {
  /** Set channel images directory path */
  $channelDir = JPATH_ROOT . DS . 'images' . DS . 'channel';
  $bannerDir  = $channelDir . DS . 'banner';
  $coverDir   = $bannerDir . DS . 'cover';
  $profileDir = $bannerDir . DS . 'profile';

  /** Check channel flder is exists
   * If not then create banner, profile and cover directories */
  if( ! file_exists( $channelDir ) ) {
    mkdir( $channelDir );
    createIndexFile ( $channelDir );
    mkdir( $bannerDir );
    createIndexFile ( $bannerDir );
    mkdir( $coverDir );
    createIndexFile ( $coverDir );
    mkdir( $profileDir );
    createIndexFile ( $profileDir );
  }
}

/**
 * Function to alter existing field in database
 * 
 * @return  addMebercolumn
 */
function addMebercolumn()  {
	$db = JFactory::getDBO();
	$query = 'ALTER TABLE `#__hdflv_upload` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL';
	$db->setQuery($query);
	$dropquery = 'ALTER TABLE `#__hdflv_user` DROP `id`';
	$db->setQuery($dropquery);

	if (!$result = $db->query()) {
		return false;
	}

	$userquery = 'ALTER TABLE `#__hdflv_user` ADD PRIMARY KEY ( `member_id` )';
	$db->setQuery($userquery);

	if (!$result = $db->query()) {
		return false;
	}
}

//  Update component, modules and plugin in joomla table
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$result = '';

if (version_compare(JVERSION, '1.6.0', 'ge')) {
	$query->clear() ->select('*') ->from($db->nameQuote('#__extensions')) ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
			->where($db->quoteName('element') . ' = ' . $db->quote('com_contushdvideoshare'));
	$db->setQuery($query, 0, 1);
	$result = $db->loadResult();
} else {
	$query->clear() ->select('id') ->from($db->nameQuote('#__hdflv_player_settings'));
	$db->setQuery($query);
	$result = $db->loadResult();

	/** Add admin menus for videoshare component */
	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Contus HD Video Share'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Member Videos'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_MEMBER_VIDEOS'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Member Details'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_MEMBER_DETAILS'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Admin Videos'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_ADMIN_VIDEOS'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Category'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_CATEGORY'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Player Settings'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_PLAYER_SETTINGS'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Site Settings'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_SITE_SETTINGS'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Google Adsense'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_GOOGLE_ADSENSE'));
	$db->setQuery($query);
	$db->query();

	$query->clear() ->update($db->quoteName('#__components')) ->set($db->quoteName('name') . ' = ' . $db->quote('Video Ads'))
			->where($db->quoteName('name') . ' = ' . $db->quote('COM_HDVIDEOSHARE_ADS'));
	$db->setQuery($query);
	$db->query();
}

if (empty($result)) {
	// Create ads table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_ads` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`published` tinyint(4) NOT NULL,
`adsname` varchar(255) NOT NULL,
`filepath` varchar(255) NOT NULL,
`postvideopath` varchar(255) NOT NULL,
`home` int(11) NOT NULL,
`targeturl` varchar(255) NOT NULL,
`clickurl` varchar(255) NOT NULL,
`impressionurl` varchar(255) NOT NULL,
`impressioncounts` int(11) NOT NULL DEFAULT '0',
`clickcounts` int(11) NOT NULL DEFAULT '0',
`adsdesc` varchar(500) NOT NULL,
`typeofadd` varchar(50) NOT NULL,
`imaaddet` longtext NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	$db->query();

	// Create playlist table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `published` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `seo_category` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent_id` int(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20;"
	    );
	$db->query();
	
	// Create Video playlist table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_video_playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) NOT NULL,
  `catid` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;");
	$db->query();
	
	// Create Watch History Table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_watchhistory` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userId` int(50) NOT NULL,
  `VideoId` int(50) NOT NULL,
  `watchedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	$db->query();
	
	// Create Watch Later Table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_watchlater` (
  `user_id` int(5) NOT NULL,
  `video_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	$db->query();
	
	// Create Channel Table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_key` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_content` longtext CHARACTER SET utf8 NOT NULL,
  `channel_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	$db->query();
	
	// Create Channel Notification Table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_channel_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sub_id` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	$db->query();
	
	// Create Channel Subscribe Table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_channel_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sub_id` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	$db->query();
	
	// Create Channel Videos Table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_channel_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `path` varchar(255) CHARACTER SET utf8 NOT NULL,
  `thumb` varchar(255) CHARACTER SET utf8 NOT NULL,
  `prev` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  `hitcount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	$db->query();
	
	// Create category table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_category` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`category` varchar(255) NOT NULL,
`seo_category` varchar(255) NOT NULL,
`parent_id` int(11) NOT NULL,
`ordering` int(11) NOT NULL DEFAULT '0',
`lft` int(11) NOT NULL,
`rgt` int(11) NOT NULL,
`published` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;");
	$db->query();

	// Create sample data fo category table
	$query->clear()
			->insert($db->quoteName('#__hdflv_category'))
			->columns( $db->quoteName( array( 'id', 'member_id', 'category', 'seo_category', 'parent_id', 'ordering', 'lft', 'rgt', 'published' ) ))
			->values(implode(',', array(1, 0, $db->quote('Speeches'), $db->quote('speeches'), 0, 1, 19, 20, 1)))
			->values(implode(',', array(2, 0, $db->quote('Interviews'), $db->quote('interviews'), 0, 2, 11, 12, 1)))
			->values(implode(',', array(3, 0, $db->quote('Talk Shows'), $db->quote('talk-shows'), 0, 3, 21, 22, 1)))
			->values(implode(',', array(4, 0, $db->quote('News & Info'), $db->quote('news-info'), 0, 4, 15, 16, 1)))
			->values(implode(',', array(5, 0, $db->quote('Documentary'), $db->quote('documentary'), 0, 5, 7, 8, 1)))
			->values(implode(',', array(6, 0, $db->quote('Travel'), $db->quote('travel'), 0, 6, 25, 26, 1)))
			->values(implode(',', array(7, 0, $db->quote('Cooking'), $db->quote('cooking'), 0, 7, 5, 6, 1)))
			->values(implode(',', array(8, 0, $db->quote('Music'), $db->quote('music'), 0, 8, 13, 14, 1)))
			->values(implode(',', array(9, 0, $db->quote('Trailers'), $db->quote('trailers'), 0, 9, 23, 24, 1)))
			->values(implode(',', array(10, 0, $db->quote('Religious'), $db->quote('religious'), 0, 10, 17, 18, 1)))
			->values(implode(',', array(11, 0, $db->quote('TV Serials & Shows'), $db->quote('tv-serials-shows'), 0, 11, 27, 28, 1 ) ) )
			->values(implode(',', array(12, 0, $db->quote('Greetings'), $db->quote('greetings'), 0, 12, 9, 10, 1)))
			->values(implode(',', array(13, 0, $db->quote('Comedy'), $db->quote('comedy'), 0, 13, 3, 4, 1)))
			->values(implode(',', array(14, 0, $db->quote('Actors'), $db->quote('actors'), 0, 14, 1, 2, 1)));

	$db->setQuery($query);
	$db->query();
	
	/** Code to create category hidden menu starts */
	/** Set menu column values */
	$columns = array('menutype','title','alias','path','link','type','published','parent_id','level','component_id','browserNav','access','params');
	
	/** Get extension id for videoshare component */
	$query->clear() ->select('extension_id') ->from('#__extensions') ->where( $db->quoteName('type') . ' = ' . $db->quote('component') )
	->where($db->quoteName('element') . ' = ' . $db->quote('com_contushdvideoshare')) ->where($db->quoteName('enabled') . ' = ' . $db->quote('1'))
	->order('extension_id DESC');
	$db->setQuery($query,0,1);
	$extension_id = $db->loadResult();
	
	/** Get menu id for hidden category menu */
	$query->clear()->select('id') ->from('#__menu_types') ->where($db->quoteName('menutype') . ' = ' . $db->quote('hiddencategorymenu'));
	$db->setQuery($query,0,1);
	$menu_type_id = $db->loadResult();
	
	/** Check hidden category menu id is exists or not */
	if(empty($menu_type_id)) {
	  /** If menu id is not exist then insert new menu id */
	  $menu_type_values = array($db->quote('hiddencategorymenu'), $db->quote('Hidden HD Video Category Menu'), $db->quote('This is a hidden menu type for HD Video Share categories'));
	  $query->clear()->insert($db->quoteName('#__menu_types')) ->columns($db->quoteName(array('menutype', 'title', 'description'))) ->values(implode(',', $menu_type_values));
	  $db->setQuery($query);
	  $db->query();
	}

	$query->clear()->select('*')->from('#__hdflv_category');
	$db->setQuery($query);
	$defaultCategoryDetails = $db->loadObjectList();
	foreach ($defaultCategoryDetails as $catDetails) {
	  /** Set category title and seo title */
	  $catTitle = $catDetails->category;
	  $seoCategory = strtolower(stripslashes ( $catTitle ));
	  $seoCategory = preg_replace ( '/[&:\s]+/i', '-', $seoCategory );
	  $alias = preg_replace ( '/[#!@$%^.,:;\/&*(){}\"\'\[\]<>|?]+/i', '', $seoCategory );
      $alias = preg_replace ( '/---|--+/i', '-', $alias ); 
      
	  /** Set url for hidden category menu */
	  $url = 'index.php?option=com_contushdvideoshare&view=category&catid='.$catDetails->id;
	  
	  /** Check in the item */
	  $values = array($db->quote('hiddencategorymenu'), $db->quote( $catTitle ),$db->quote($alias),$db->quote($alias),$db->quote("$url"),$db->quote("component"),1,1,1,$extension_id,0,1,$db->quote('{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1}'));
	  $query->clear() ->insert($db->quoteName('#__menu')) ->columns($db->quoteName($columns)) ->values(implode(',', $values));
	  $db->setQuery($query);	  
	  $db->query();
	}
	/** Code to create category hidden menu ends */

	// Create commetns table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_comments` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`parentid` int(11) NOT NULL,
`videoid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`email` varchar(50) NOT NULL,
`subject` varchar(200) NOT NULL,
`message` varchar(500) NOT NULL,
`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
`published` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;");
	$db->query();

	// Create google ad table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_googlead` (
`id` int(2) NOT NULL,
`code` text NOT NULL,
`showoption` tinyint(1) NOT NULL,
`closeadd` int(6) NOT NULL,
`reopenadd` tinytext NOT NULL,
`publish` int(1) NOT NULL,
`ropen` int(6) NOT NULL,
`showaddc` tinyint(1) NOT NULL DEFAULT '0',
`showaddm` tinyint(4) NOT NULL DEFAULT '0',
`showaddp` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	$db->query();

	// Create sample data for google ad table
	$column_googlead = array( 'id', 'code', 'showoption', 'closeadd', 'reopenadd', 'publish', 'ropen', 'showaddc', 'showaddm', 'showaddp' );
	$query->clear() ->insert($db->quoteName('#__hdflv_googlead')) ->columns($column_googlead)
			->values(implode(',', array(1, '""', 1, 10, '0', 0, 10, 0, '0', '0')));
	$db->setQuery($query);
	$db->query();

	// Create player settings table
	$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_player_settings` (
`id` int(20) NOT NULL AUTO_INCREMENT,
`published` tinyint(4) NOT NULL,
`player_colors` longtext NOT NULL,
`player_icons` longtext NOT NULL,
`player_values` longtext NOT NULL,
`uploadmaxsize` int(10) NOT NULL,
`logopath` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;");
	$db->query();

	// Create sample data for player settings table
	$player_colors = 'a:18:{s:21:"sharepanel_up_BgColor";s:0:"";s:23:"sharepanel_down_BgColor";s:0:"";'
					. 's:19:"sharepaneltextColor";s:0:"";s:15:"sendButtonColor";s:0:"";s:19:"sendButtonTextColor";s:0:"";'
					. 's:9:"textColor";s:0:"";s:11:"skinBgColor";s:0:"";s:13:"seek_barColor";s:0:"";s:15:"buffer_barColor";'
					. 's:0:"";s:13:"skinIconColor";s:0:"";s:11:"pro_BgColor";s:0:"";s:15:"playButtonColor";s:0:"";'
					. 's:17:"playButtonBgColor";s:0:"";s:17:"playerButtonColor";s:0:"";s:19:"playerButtonBgColor";s:0:"";'
					. 's:19:"relatedVideoBgColor";s:0:"";s:15:"scroll_barColor";s:0:"";s:14:"scroll_BgColor";s:0:"";}';
	$player_icons = 'a:28:{s:8:"autoplay";s:1:"0";s:17:"playlist_autoplay";s:1:"0";s:13:"playlist_open";s:1:"1";s:13:"skin_autohide";s:1:"1";s:10:"fullscreen";s:1:"1";s:4:"zoom";s:1:"1";s:5:"timer";s:1:"1";s:7:"showTag";s:1:"1";s:8:"shareurl";s:1:"1";s:11:"emailenable";s:1:"1";s:14:"login_page_url";s:0:"";s:12:"embedVisible";s:1:"1";s:15:"progressControl";s:1:"1";s:11:"skinvisible";s:1:"0";s:9:"hddefault";s:1:"1";s:12:"imageDefault";s:1:"1";s:14:"enabledownload";s:1:"1";s:10:"prerollads";s:1:"0";s:11:"postrollads";s:1:"0";s:6:"imaads";s:1:"0";s:13:"volumecontrol";s:1:"1";s:7:"adsSkip";s:1:"0";s:10:"midrollads";s:1:"0";s:8:"midbegin";s:0:"";s:9:"midrandom";s:1:"0";s:11:"midadrotate";s:1:"0";s:17:"googleana_visible";s:1:"0";s:13:"iframeVisible";s:1:"1";}';
	$player_values = 'a:26:{s:6:"buffer";s:1:"3";s:5:"width";s:3:"700";s:6:"height";s:3:"500";'
			. 's:11:"normalscale";s:1:"1";s:15:"fullscreenscale";s:1:"1";s:6:"volume";s:2:"50";s:8:"nrelated";'
			. 'i:8;s:10:"ffmpegpath";s:15:"/usr/bin/ffmpeg";s:12:"skin_opacity";s:1:"1";s:13:"subTitleColor";'
			. 's:0:"";s:15:"subTitleBgColor";s:0:"";s:18:"subTitleFontFamily";s:0:"";s:16:"subTitleFontSize";'
			. 's:0:"";s:10:"stagecolor";s:6:"000000";s:10:"licensekey";s:0:"";s:7:"logourl";s:0:"";s:9:"logoalpha";'
			. 's:3:"100";s:9:"logoalign";s:2:"TR";s:15:"adsSkipDuration";s:0:"";s:10:"imaadbegin";s:2:"10";s:17:"googleanalyticsID";s:0:"";'
			. 's:8:"midbegin";s:0:"";s:11:"midinterval";s:0:"";s:14:"related_videos";s:1:"1";s:16:"relatedVideoView";'
			. 's:4:"side";s:14:"login_page_url";s:0:"";}';

	$column_settings = array( 'id', 'published', 'uploadmaxsize', 'logopath', 'player_colors', 'player_icons', 'player_values' );
	$query->clear() ->insert($db->quoteName('#__hdflv_player_settings'))
			->columns($column_settings)
			->values( implode( ',', array( 1, 1, 100, $db->quote(''), $db->quote($player_colors),
								$db->quote($player_icons), $db->quote($player_values) ) ) );
	$db->setQuery($query);
	$db->query();

	// Create site settings table
	$db->setQuery(
			"CREATE TABLE IF NOT EXISTS `#__hdflv_site_settings` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`published` tinyint(4) NOT NULL,
			`thumbview` longtext NOT NULL,
			`homethumbview` longtext NOT NULL,
			`sidethumbview` longtext NOT NULL,
			`dispenable` longtext NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;"
			);
	$db->query();

	// Create sample data for site settings tabele
	$homethumbview = 'a:15:{s:16:"homepopularvideo";s:1:"1";s:19:"homepopularvideorow";s:1:"1";'
					. 's:19:"homepopularvideocol";s:1:"4";s:17:"homefeaturedvideo";s:1:"1";s:20:"homefeaturedvideorow";'
					. 's:1:"1";s:20:"homefeaturedvideocol";s:1:"4";s:15:"homerecentvideo";s:1:"1";s:18:"homerecentvideorow";'
					. 's:1:"1";s:18:"homerecentvideocol";s:1:"4";s:21:"homepopularvideoorder";s:1:"1";'
					. 's:22:"homefeaturedvideoorder";s:1:"2";s:20:"homerecentvideoorder";s:1:"3";s:21:"homepopularvideowidth";'
					. 's:2:"20";s:22:"homefeaturedvideowidth";s:2:"20";s:20:"homerecentvideowidth";s:2:"20";}';
	$disenable = 'a:24:{s:11:"allowupload";s:1:"1";s:12:"adminapprove";s:1:"1";s:11:"rssfeedicon";s:1:"1";s:10:"user_login";s:1:"1";s:14:"ratingscontrol";s:1:"1";s:13:"viewedconrtol";s:1:"1";s:11:"reportvideo";s:1:"0";s:14:"categoryplayer";s:1:"1";s:10:"homeplayer";s:1:"1";s:10:"limitvideo";s:3:"100";s:10:"youtubeapi";s:0:"";s:10:"seo_option";s:1:"0";s:14:"upload_methods";s:23:"Upload,Youtube,URL,RTMP";s:17:"language_settings";s:11:"English.php";s:9:"disqusapi";s:1:" ";s:11:"facebookapi";s:1:" ";s:7:"comment";s:1:"0";s:8:"amazons3";s:1:"0";s:12:"amazons3name";s:0:"";s:12:"amazons3link";s:0:"";s:17:"amazons3accesskey";s:0:"";s:28:"amazons3accesssecretkey_area";s:0:"";s:12:"facebooklike";s:1:"1";s:14:"playlist_limit";s:2:"10";}';
	$thumbview = 'a:33:{s:9:"featurrow";s:1:"3";s:9:"featurcol";s:1:"4";s:13:"watchlaterrow";s:1:"3";s:13:"watchlatercol";s:1:"4";s:9:"recentrow";s:1:"3";s:9:"recentcol";s:1:"4";s:11:"categoryrow";s:1:"3";s:11:"categorycol";s:1:"4";s:11:"playlistrow";s:1:"3";s:11:"playlistcol";s:1:"4";s:10:"popularrow";s:1:"3";s:10:"popularcol";s:1:"4";s:9:"searchrow";s:1:"3";s:9:"searchcol";s:1:"4";s:10:"relatedrow";s:1:"3";s:10:"relatedcol";s:1:"4";s:11:"featurwidth";s:2:"20";s:11:"recentwidth";s:2:"20";s:13:"categorywidth";s:2:"20";s:13:"playlistwidth";s:2:"20";s:12:"popularwidth";s:2:"20";s:15:"watchlaterwidth";s:2:"20";s:11:"searchwidth";s:2:"20";s:12:"relatedwidth";s:2:"20";s:15:"memberpagewidth";s:2:"20";s:12:"myvideowidth";s:2:"20";s:13:"memberpagerow";s:1:"3";s:13:"memberpagecol";s:1:"4";s:10:"myvideorow";s:1:"3";s:10:"myvideocol";s:1:"4";s:10:"historyrow";s:1:"3";s:10:"historycol";s:1:"4";s:12:"historywidth";s:2:"20";}';
	$sidethumbview = 'a:16:{s:19:"sidepopularvideorow";s:1:"2";s:19:"sidepopularvideocol";s:1:"1";s:20:"sidefeaturedvideorow";s:1:"2";s:20:"sidefeaturedvideocol";s:1:"1";s:19:"siderelatedvideorow";s:1:"2";s:19:"siderelatedvideocol";s:1:"1";s:18:"siderecentvideorow";s:1:"2";s:18:"siderecentvideocol";s:1:"1";s:17:"sidewatchlaterrow";s:1:"2";s:17:"sidewatchlatercol";s:1:"1";s:18:"siderandomvideorow";s:1:"2";s:18:"siderandomvideocol";s:1:"1";s:20:"sidecategoryvideorow";s:1:"2";s:20:"sidecategoryvideocol";s:1:"1";s:19:"sidehistoryvideorow";s:1:"2";s:19:"sidehistoryvideocol";s:1:"1";}';
	$column_site_settings = array( 'id', 'published', 'homethumbview', 'dispenable', 'thumbview', 'sidethumbview' );
	$query->clear()
			->insert($db->quoteName('#__hdflv_site_settings'))
			->columns($column_site_settings)
			->values( implode( ',', array( 1, 1, $db->quote($homethumbview), $db->quote($disenable), $db->quote($thumbview), $db->quote($sidethumbview) ) ) );
	$db->setQuery($query);
	$db->query();

	// Create video upload table
	$db->setQuery(
			"CREATE TABLE IF NOT EXISTS `#__hdflv_upload` (
			`id` int(5) NOT NULL AUTO_INCREMENT,
			`memberid` int(11) NOT NULL,
			`published` tinyint(1) NOT NULL,
			`title` varchar(255) CHARACTER SET utf8 NOT NULL,
			`seotitle` varchar(255) CHARACTER SET utf8 NOT NULL,
			`featured` tinyint(4) NOT NULL,
			`type` tinyint(4) NOT NULL,
			`rate` int(11) NOT NULL,
			`rateduser` longtext NOT NULL,
			`ratecount` int(11) NOT NULL,
			`times_viewed` int(11) NOT NULL,
			`videos` varchar(255) CHARACTER SET utf8 NOT NULL,
			`filepath` varchar(10) CHARACTER SET utf8 NOT NULL,
			`videourl` varchar(255) CHARACTER SET utf8 NOT NULL,
			`thumburl` varchar(255) CHARACTER SET utf8 NOT NULL,
			`previewurl` varchar(255) CHARACTER SET utf8 NOT NULL,
			`hdurl` varchar(255) CHARACTER SET utf8 NOT NULL,
			`home` int(11) NOT NULL,
			`playlistid` int(11) NOT NULL,
			`duration` varchar(20) CHARACTER SET utf8 NOT NULL,
			`ordering` int(11) NOT NULL,
			`streamerpath` varchar(255) CHARACTER SET utf8 NOT NULL,
			`streameroption` varchar(255) CHARACTER SET utf8 NOT NULL,
			`postrollads` tinyint(4) NOT NULL,
			`prerollads` tinyint(4) NOT NULL,
			`midrollads` tinyint(4) NOT NULL,
			`description` text CHARACTER SET utf8 NOT NULL,
			`targeturl` varchar(255) CHARACTER SET utf8 NOT NULL,
			`download` tinyint(4) NOT NULL,
			`prerollid` int(11) NOT NULL,
			`postrollid` int(11) NOT NULL,
			`created_date` datetime NOT NULL,
			`addedon` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
			`usergroupid` varchar(250)CHARACTER SET utf8 NOT NULL,
			`tags` text CHARACTER SET utf8 NOT NULL,
			`useraccess` int(11) NOT NULL DEFAULT '0',
			`islive` tinyint(1) NOT NULL DEFAULT '0',
			`imaads` int(11) NOT NULL DEFAULT '0',
			`embedcode` longtext NOT NULL,
			`subtitle1` varchar(255) CHARACTER SET utf8 NOT NULL,
			`subtitle2` varchar(255) CHARACTER SET utf8 NOT NULL,
			`subtile_lang2` text CHARACTER SET utf8 NOT NULL,
			`subtile_lang1` text CHARACTER SET utf8 NOT NULL,
			`amazons3` tinyint(3) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
			);
	$db->query();

	if (version_compare(JVERSION, '1.6.0', 'ge'))
	{
		// Get user detail
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$query = $db->getQuery(true);
		$query->clear()
				->select('g.id AS group_id')
				->from('#__usergroups AS g')
				->leftJoin('#__user_usergroup_map AS map ON map.group_id = g.id')
				->where('map.user_id = ' . (int) $userid);
		$db->setQuery($query);
		$ugp = $db->loadObject();
		$groupname = $ugp->group_id;

		// Create sample data for video upload table
		$column_upload = array(
			'id', 'memberid', 'published', 'title', 'seotitle', 'featured',
			'type', 'rate', 'ratecount', 'times_viewed', 'videos', 'filepath', 'videourl',
			'thumburl', 'previewurl', 'hdurl', 'home', 'playlistid', 'duration', 'ordering',
			'streamerpath', 'streameroption', 'postrollads', 'prerollads', 'description', 'targeturl',
			'download', 'prerollid', 'postrollid', 'created_date', 'addedon', 'usergroupid',
			'useraccess', 'islive', 'imaads', 'embedcode', 'rateduser'
			);
		$query->clear()
				->insert($db->quoteName('#__hdflv_upload'))
				->columns($column_upload)
				->values( implode( ',', array(
									1, $db->quote($userid), 1,
									$db->quote('The Hobbit: The Desolation of Smaug International Trailer'),
									$db->quote('The-Hobbit-The-Desolation-of-Smaug-International-Trailer'), 1, 0, 9,
									2, 3, $db->quote(''), $db->quote('Youtube'),
									$db->quote('http://www.youtube.com/watch?v=TeGb5XGk2U0'),
									$db->quote('http://img.youtube.com/vi/TeGb5XGk2U0/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/TeGb5XGk2U0/maxresdefault.jpg'),
									$db->quote(''), 0, 9, $db->quote(''), 0, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2010-06-05 01:06:06'), $db->quote('2010-06-28 16:26:39'),
									$groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									) ) )
				->values( implode(
								',',
								array(
									2, $db->quote($userid), 1, $db->quote('Iron Man 3'),
									$db->quote('Iron-Man-3'), 1, 0, 0, 0, 95, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=Ke1Y3P9D0Bc'),
									$db->quote('http://img.youtube.com/vi/Ke1Y3P9D0Bc/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/Ke1Y3P9D0Bc/maxresdefault.jpg'),
									$db->quote(''), 0, 14, $db->quote(''), 1, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2010-06-05 01:06:28'), $db->quote('2010-06-28 16:45:59'),
									$groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									3, $db->quote($userid), 1, $db->quote('GI JOE 2 Retaliation Trailer 2'),
									$db->quote('GI-JOE-2-Retaliation-Trailer-2'), 1, 0, 5, 1, 9, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=mKNpy-tGwxE'),
									$db->quote('http://img.youtube.com/vi/mKNpy-tGwxE/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/mKNpy-tGwxE/maxresdefault.jpg'), $db->quote(''), 0, 5,
									$db->quote(''), 2, $db->quote(''), $db->quote(''), 0, 0, $db->quote(''),
									$db->quote(''), 0, 0, 0, $db->quote('2010-06-05 01:06:25'),
									$db->quote('2010-06-28 16:29:39'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									4, $db->quote($userid), 1, $db->quote('UP HD 1080p Trailer'),
									$db->quote('UP-HD-1080p-Trailer'), 1, 0, 0, 0, 29, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=1cRuA64m_lY'),
									$db->quote('http://img.youtube.com/vi/1cRuA64m_lY/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/1cRuA64m_lY/maxresdefault.jpg'),
									$db->quote(''), 0, 5, $db->quote(''), 3, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0, $db->quote('2010-06-05 01:06:57'),
									$db->quote('2010-06-28 17:09:46'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									5, $db->quote($userid), 1, $db->quote('Chipwrecked: Survival Tips'),
									$db->quote('Chipwrecked-Survival-Tips'), 1, 0, 0, 0, 8, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=dLIEKGNYbVU'),
									$db->quote('http://img.youtube.com/vi/dLIEKGNYbVU/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/dLIEKGNYbVU/maxresdefault.jpg'),
									$db->quote(''), 0, 5, $db->quote(''), 4, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0, $db->quote('2010-06-05 01:06:46'),
									$db->quote('2010-06-28 16:16:11'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									6, $db->quote($userid), 1, $db->quote('THE TWILIGHT SAGA: BREAKING DAWN PART 2'),
									$db->quote('THE-TWILIGHT-SAGA-BREAKING-DAWN-PART-2'), 1, 0, 0, 0, 8, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=ey0aA3YY0Mo'),
									$db->quote('http://img.youtube.com/vi/ey0aA3YY0Mo/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/ey0aA3YY0Mo/maxresdefault.jpg'),
									$db->quote(''), 0, 11, $db->quote(''), 5, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2011-01-24 06:01:26'), $db->quote('2011-01-24 11:31:26'),
									$groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						);
		$db->setQuery($query);
		$db->query();

	//  Joomla! 1.7 code here
	}
	else
	{
		$groupname = '25';
		$column_upload = array(
			'id', 'memberid', 'published', 'title', 'seotitle', 'featured',
			'type', 'rate', 'ratecount', 'times_viewed', 'videos', 'filepath', 'videourl', 'thumburl',
			'previewurl', 'hdurl', 'home', 'playlistid', 'duration', 'ordering', 'streamerpath',
			'streameroption', 'postrollads', 'prerollads', 'description', 'targeturl', 'download',
			'prerollid', 'postrollid', 'created_date', 'addedon', 'usergroupid', 'useraccess',
			'islive', 'imaads', 'embedcode', 'rateduser'
			);
		$query->clear()
				->insert($db->quoteName('#__hdflv_upload'))
				->columns($column_upload)
				->values(
						implode(
								',',
								array(
									1, 62, 1,
									$db->quote('The Hobbit: The Desolation of Smaug International Trailer'),
									$db->quote('The-Hobbit-The-Desolation-of-Smaug-International-Trailer'), 1, 0, 9,
									2, 3, $db->quote(''), $db->quote('Youtube'),
									$db->quote('http://www.youtube.com/watch?v=TeGb5XGk2U0'),
									$db->quote('http://img.youtube.com/vi/TeGb5XGk2U0/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/TeGb5XGk2U0/maxresdefault.jpg'),
									$db->quote(''), 0, 9, $db->quote(''), 0, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2010-06-05 01:06:06'), $db->quote('2010-06-28 16:26:39'),
									$groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									2, 62, 1, $db->quote('Iron Man 3'), $db->quote('Iron-Man-3'), 1, 0, 0,
									0, 95, $db->quote(''), $db->quote('Youtube'),
									$db->quote('http://www.youtube.com/watch?v=Ke1Y3P9D0Bc'),
									$db->quote('http://img.youtube.com/vi/Ke1Y3P9D0Bc/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/Ke1Y3P9D0Bc/maxresdefault.jpg'),
									$db->quote(''), 0, 14, $db->quote(''), 1, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2010-06-05 01:06:28'), $db->quote('2010-06-28 16:45:59'),
									$groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									3, 62, 1, $db->quote('GI JOE 2 Retaliation Trailer 2'),
									$db->quote('GI-JOE-2-Retaliation-Trailer-2'), 1, 0, 5, 1, 9, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=mKNpy-tGwxE'),
									$db->quote('http://img.youtube.com/vi/mKNpy-tGwxE/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/mKNpy-tGwxE/maxresdefault.jpg'),
									$db->quote(''), 0, 5, $db->quote(''), 2, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2010-06-05 01:06:25'), $db->quote('2010-06-28 16:29:39'),
									$groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									4, 62, 1, $db->quote('UP HD 1080p Trailer'),
									$db->quote('UP-HD-1080p-Trailer'), 1, 0, 0, 0, 29, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=1cRuA64m_lY'),
									$db->quote('http://img.youtube.com/vi/1cRuA64m_lY/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/1cRuA64m_lY/maxresdefault.jpg'),
									$db->quote(''), 0, 5, $db->quote(''), 3, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0, $db->quote('2010-06-05 01:06:57'),
									$db->quote('2010-06-28 17:09:46'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									5, 62, 1, $db->quote('Chipwrecked: Survival Tips'),
									$db->quote('Chipwrecked-Survival-Tips'), 1, 0, 0, 0, 8, $db->quote(''),
									$db->quote('Youtube'), $db->quote('http://www.youtube.com/watch?v=dLIEKGNYbVU'),
									$db->quote('http://img.youtube.com/vi/dLIEKGNYbVU/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/dLIEKGNYbVU/maxresdefault.jpg'),
									$db->quote(''), 0, 5, $db->quote(''), 4, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2010-06-05 01:06:46'), $db->quote('2010-06-28 16:16:11'),
									$groupname, 0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						)
				->values(
						implode(
								',',
								array(
									6, 62, 1, $db->quote('THE TWILIGHT SAGA: BREAKING DAWN PART 2'),
									$db->quote('THE-TWILIGHT-SAGA-BREAKING-DAWN-PART-2'), 1, 0, 0, 0, 8,
									$db->quote(''), $db->quote('Youtube'),
									$db->quote('http://www.youtube.com/watch?v=ey0aA3YY0Mo'),
									$db->quote('http://img.youtube.com/vi/ey0aA3YY0Mo/mqdefault.jpg'),
									$db->quote('http://img.youtube.com/vi/ey0aA3YY0Mo/maxresdefault.jpg'),
									$db->quote(''), 0, 11, $db->quote(''), 5, $db->quote(''), $db->quote(''), 0, 0,
									$db->quote(''), $db->quote(''), 0, 0, 0,
									$db->quote('2011-01-24 06:01:26'), $db->quote('2011-01-24 11:31:26'), $groupname,
									0, 0, 0, $db->quote(''), $db->quote('')
									)
								)
						);
		$db->setQuery($query);
		$db->query();
	}

	// Create video share user table
	$db->setQuery(
			"CREATE TABLE IF NOT EXISTS `#__hdflv_user` (
			`member_id` int(11) NOT NULL,
			`allowupload` tinyint(4) NOT NULL,
	    `Pause_History_State` int(50) NOT NULL,
			PRIMARY KEY (`member_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
			);
	$db->query();

	// Create video category table
	$db->setQuery(
			"CREATE TABLE IF NOT EXISTS `#__hdflv_video_category` (
			`vid` int(11) NOT NULL,
			`catid` varchar(100) CHARACTER SET utf8 NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
			);
	$db->query();

	// Create sample data for video category table
	$query->clear()
			->insert($db->quoteName('#__hdflv_video_category'))
			->columns($db->quoteName(array('vid', 'catid')))
			->values(implode(',', array(1, 9)))
			->values(implode(',', array(2, 14)))
			->values(implode(',', array(3, 5)))
			->values(implode(',', array(4, 5)))
			->values(implode(',', array(5, 5)))
			->values(implode(',', array(6, 11)));
	$db->setQuery($query);
	$db->query();
}
else
{
	// Upgrade section starts here
	$upgra = 'upgrade';

	// Alter upload table
	$updateDid = addColumnIfNotExists($errorMsg, "#__hdflv_upload", "useraccess");
	$isliveupdateLive = addColumnIfNotExists($errorMsg,"#__hdflv_upload", "islive", "TINYINT( 1 ) NOT NULL DEFAULT '0'");
	$imaadsupdateLive = addColumnIfNotExists($errorMsg,"#__hdflv_upload", "imaads", "TINYINT( 1 ) NOT NULL DEFAULT '0'");
	$embedcodeupdateLive = addColumnIfNotExists($errorMsg, "#__hdflv_upload", "embedcode", "longtext NOT NULL");
	$rateduserupdateLive = addColumnIfNotExists($errorMsg, "#__hdflv_upload", "rateduser", "longtext NOT NULL");
	$subtitle1updateLive = addColumnIfNotExists($errorMsg, "#__hdflv_upload", "subtitle1", "varchar(255) NOT NULL");
	$subtitle2updateLive = addColumnIfNotExists($errorMsg, "#__hdflv_upload", "subtitle2", "varchar(255) NOT NULL");
	$subtitlelang2updateLive = addColumnIfNotExists($errorMsg, "#__hdflv_upload", "subtile_lang2", "text NOT NULL");
	$subtitlelang1updateLive = addColumnIfNotExists($errorMsg, "#__hdflv_upload", "subtile_lang1", "text NOT NULL");
	$amazons3updateLive = addColumnIfNotExists($errorMsg,"#__hdflv_upload", "amazons3", "tinyint(3) NOT NULL DEFAULT '0'");

	// Alter site settings table
	$updatethumbview = addColumnIfNotExists($errorMsg, "#__hdflv_site_settings", "thumbview", "longtext NOT NULL");
	$updatehomethumbview = addColumnIfNotExists($errorMsg,"#__hdflv_site_settings", "homethumbview", "longtext NOT NULL");
	$updatesidethumbview = addColumnIfNotExists($errorMsg,"#__hdflv_site_settings", "sidethumbview", "longtext NOT NULL");
	$updatedispenable = addColumnIfNotExists($errorMsg,"#__hdflv_site_settings", "dispenable", "longtext NOT NULL");

	// Alter player settings table
	$updateplayer_colors = addColumnIfNotExists($errorMsg,"#__hdflv_player_settings", "player_colors", "longtext NOT NULL");
	$updateplayer_icons = addColumnIfNotExists($errorMsg,"#__hdflv_player_settings", "player_icons", "longtext NOT NULL");
	$updateplayer_values = addColumnIfNotExists($errorMsg,"#__hdflv_player_settings", "player_values", "longtext NOT NULL");

	// Alter category table
	$updateMid = addColumnIfNotExists($errorMsg, "#__hdflv_category", "member_id");
	$updateCategory = addColumnIfNotExists($errorMsg, "#__hdflv_category", "lft", "INT( 11 ) NOT NULL", "ordering");
	$updateCategory1 = addColumnIfNotExists($errorMsg, "#__hdflv_category", "rgt", "INT( 11 ) NOT NULL", "lft");

	// Alter google ad table
	$updateGoogleAd = addColumnIfNotExists($errorMsg,"#__hdflv_googlead", "showaddc", "TINYINT( 1 ) NOT NULL DEFAULT '0'");
	$updateGoogleAd1 = addColumnIfNotExists($errorMsg,"#__hdflv_googlead", "showaddm", "TINYINT NOT NULL DEFAULT '0'");
	$updateGoogleAd2 = addColumnIfNotExists($errorMsg,"#__hdflv_googlead", "showaddp", "TINYINT NOT NULL DEFAULT '0'");
	$updateGoogleAd3 = addColumnIfNotExists($errorMsg, "#__hdflv_googlead", "imaaddet", "longtext NOT NULL");
	
	// Alter hdflv user table
	$userHistory = addColumnIfNotExists($errorMsg, "#__hdflv_user", "Pause_History_State", "INT(50) NOT NULL");

	// Add fields to user table
	addMebercolumn();

	if (!$updateDid
		|| !$isliveupdateLive || !$imaadsupdateLive || !$subtitlelang1updateLive
		|| !$subtitlelang2updateLive || !$embedcodeupdateLive || !$subtitle2updateLive
		|| !$subtitle1updateLive || !$amazons3updateLive || !$rateduserupdateLive || !$userHistory)
	{
		$msgSQL .= "error adding columns to 'hdflvupload' table <br />";
	}

	$playlisttablequery = 'SHOW TABLES LIKE "#__hdflv_playlist";';
	$db->setQuery($playlisttablequery);
	$db->query();
	$playlistcolumnData = $db->loadResult();
	
	if (empty($playlistcolumnData))
	{
		// Create playlist table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_playlist` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
				`category` varchar(255) NOT NULL,
				`published` int(11) NOT NULL,
				`member_id` int(11) NOT NULL,
				`seo_category` varchar(255) NOT NULL,
				`ordering` int(11) NOT NULL,
				`description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
				`parent_id` int(25) NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	
		if(!$db->query()){
			$msgSQL .= "error adding 'hdflv_playlist' table <br />";
		}
	}
	
	$videoplaylisttablequery = 'SHOW TABLES LIKE "#__hdflv_video_playlist";';
	$db->setQuery($videoplaylisttablequery);
	$db->query();
	$videoplaylistcolumnData = $db->loadResult();
	
	if (empty($videoplaylistcolumnData))
	{
		// Create Video playlist table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_video_playlist` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
			  `vid` int(11) NOT NULL,
			  `catid` varchar(100) NOT NULL,
			  PRIMARY KEY (`id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		if(!$db->query()){
			$msgSQL .= "error adding 'hdflv_video_playlist' table <br />";
		}
	}
	
	$watchHistoryTableQuery = 'SHOW TABLES LIKE "#__hdflv_watchhistory";';
	$db->setQuery($watchHistoryTableQuery);
	$db->query();
	$watchHistoryColumnData = $db->loadResult();
	
	if (empty($watchHistoryColumnData))
	{
		// Create Watch History table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_watchhistory` (
		  `id` int(50) NOT NULL AUTO_INCREMENT,
		  `userId` int(50) NOT NULL,
		  `VideoId` int(50) NOT NULL,
		  `watchedOn` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		if(!$db->query()){
			$msgSQL .= "error adding 'hdflv_watchhistory' table <br />";
		}
	}
	
	$watchLaterTableQuery = 'SHOW TABLES LIKE "#__hdflv_watchlater";';
	$db->setQuery($watchLaterTableQuery);
	$db->query();
	$watchLaterColumnData = $db->loadResult();
	
	if (empty($watchLaterColumnData))
	{
		// Create Watch Later table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_watchlater` (
		  `user_id` int(5) NOT NULL,
		  `video_id` int(5) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		if(!$db->query()){
			$msgSQL .= "error adding 'hdflv_watchlater' table <br />";
		}
	}
	
	$channelTableQuery = 'SHOW TABLES LIKE "#__channel";';
	$db->setQuery($channelTableQuery);
	$db->query();
	$channelColumnData = $db->loadResult();
	
	if (empty($channelColumnData))
	{
		// Create Channel table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__channel` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `user_name` varchar(100) CHARACTER SET utf8 NOT NULL,
		  `user_key` varchar(100) CHARACTER SET utf8 NOT NULL,
		  `user_content` longtext CHARACTER SET utf8 NOT NULL,
		  `channel_name` varchar(100) CHARACTER SET utf8 NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		if(!$db->query()){
			$msgSQL .= "error adding 'channel' table <br />";
		}
	}
	
	$channelNotificationTableQuery = 'SHOW TABLES LIKE "#__channel_notification";';
	$db->setQuery($channelNotificationTableQuery);
	$db->query();
	$channelNotificationColumnData = $db->loadResult();
	
	if (empty($channelNotificationColumnData))
	{
		// Create Channel Notification table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__channel_notification` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `sub_id` longtext CHARACTER SET utf8 NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		if(!$db->query()){
			$msgSQL .= "error adding 'channel_notification' table <br />";
		}
	}
	
	$channelSubscribeTableQuery = 'SHOW TABLES LIKE "#__channel_subscribe";';
	$db->setQuery($channelSubscribeTableQuery);
	$db->query();
	$channelSubscribeColumnData = $db->loadResult();
	
	if (empty($channelSubscribeColumnData))
	{
		// Create Channel Subscribe table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__channel_subscribe` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `sub_id` longtext CHARACTER SET utf8 NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		if(!$db->query()){
			$msgSQL .= "error adding 'channel_subscribe' table <br />";
		}
	}
	
	$channelVideosTableQuery = 'SHOW TABLES LIKE "#__channel_videos";';
	$db->setQuery($channelVideosTableQuery);
	$db->query();
	$channelVideosColumnData = $db->loadResult();
	
	if (empty($channelVideosColumnData)) {
		// Create Channel Videos table
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__channel_videos` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `path` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `thumb` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `prev` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `date` datetime NOT NULL,
		  `hitcount` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		if(!$db->query()){
			$msgSQL .= "error adding 'channel_videos' table <br />";
		}
	}
	
	// Update site settings table
	$query->clear() ->select('*') ->from($db->quoteName('#__hdflv_site_settings'));
	$db->setQuery($query);
	$settingstabeResult = $db->loadObject();

	if (!$updatethumbview) {
		$msgSQL .= "error adding 'thumbview' column to 'hdflv_site_settings' table <br />";
	} else {
		$query->clear() ->select('thumbview') ->from($db->quoteName('#__hdflv_site_settings'));
		$db->setQuery($query);
		$thumbviewResult = $db->loadResult();
		$upgradethumbview = unserialize($thumbviewResult);
		if (empty($thumbviewResult)) {
			// Get thumbview details and serialize data
			$sitethumbview = array( 'featurrow' => $settingstabeResult->featurrow,
				'featurcol' => $settingstabeResult->featurcol,
				'recentrow' => $settingstabeResult->recentrow,
				'recentcol' => $settingstabeResult->recentcol,
				'categoryrow' => $settingstabeResult->categoryrow,
				'categorycol' => $settingstabeResult->categorycol,
				'popularrow' => $settingstabeResult->popularrow,
				'popularcol' => $settingstabeResult->popularcol,
				'searchrow' => $settingstabeResult->searchrow,
				'searchcol' => $settingstabeResult->searchcol,
				'relatedrow' => $settingstabeResult->relatedrow,
				'relatedcol' => $settingstabeResult->relatedcol,
				'featurwidth' => $settingstabeResult->featurwidth,
				'recentwidth' => $settingstabeResult->recentwidth,
				'categorywidth' => $settingstabeResult->categorywidth,
				'popularwidth' => $settingstabeResult->popularwidth,
				'searchwidth' => $settingstabeResult->searchwidth,
				'relatedwidth' => $settingstabeResult->relatedwidth,
				'memberpagewidth' => $settingstabeResult->memberpagewidth,
				'memberpagerow' => $settingstabeResult->memberpagerow,
				'memberpagecol' => $settingstabeResult->memberpagecol,
				'myvideorow' => $settingstabeResult->myvideorow,
				'myvideocol' => $settingstabeResult->myvideocol,
				'myvideowidth' => $settingstabeResult->myvideowidth,
				'watchlaterrow' =>  3,
				'watchlatercol' =>  4,
				'playlistrow' =>  3,
				'playlistcol' =>  4,				
				'historyrow' => 3,
				'historycol' => 4,
				'watchlaterwidth' => 10,
				'historywidth' => 10
			);			
			$arrthumbview = serialize($sitethumbview);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($arrthumbview)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradethumbview['watchlaterrow'])) {
		  $upgradethumbview['watchlaterrow'] = 3;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradethumbview['watchlatercol'])) {
		  $upgradethumbview['watchlatercol'] = 4;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradethumbview['playlistrow'])) {
		  $upgradethumbview['playlistrow'] = 3;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradethumbview['playlistcol'])) {
		  $upgradethumbview['playlistcol'] = 4;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradethumbview['historyrow'])) {
		  $upgradethumbview['historyrow'] = 3;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradethumbview['historycol'])) {
		  $upgradethumbview['historycol'] = 4;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradethumbview['watchlaterwidth'])) {
		  $upgradethumbview['watchlaterwidth'] = 10;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradethumbview['historywidth'])) {
		  $upgradethumbview['historywidth'] = 10;
		  $data = serialize($upgradethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('thumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
	}	
	
	if (!$updatehomethumbview) {
		$msgSQL .= "error adding 'homethumbview' column to 'hdflv_site_settings' table <br />";
	} else {
		$query->clear() ->select('homethumbview') ->from($db->quoteName('#__hdflv_site_settings'));
		$db->setQuery($query);
		$homethumbviewResult = $db->loadResult();
		if (empty($homethumbviewResult)) {
			// Get home page thumb details and serialize data
			$sitehomethumbview = array( 'homepopularvideo' => $settingstabeResult->homepopularvideo,
				'homepopularvideorow' => $settingstabeResult->homepopularvideorow,
				'homepopularvideocol' => $settingstabeResult->homepopularvideocol,
				'homefeaturedvideo' => $settingstabeResult->homefeaturedvideo,
				'homefeaturedvideorow' => $settingstabeResult->homefeaturedvideorow,
				'homefeaturedvideocol' => $settingstabeResult->homefeaturedvideocol,
				'homerecentvideo' => $settingstabeResult->homerecentvideo,
				'homerecentvideorow' => $settingstabeResult->homerecentvideorow,
				'homerecentvideocol' => $settingstabeResult->homerecentvideocol,
				'homepopularvideoorder' => $settingstabeResult->homepopularvideoorder,
				'homefeaturedvideoorder' => $settingstabeResult->homefeaturedvideoorder,
				'homerecentvideoorder' => $settingstabeResult->homerecentvideoorder,
				'homefeaturedvideoorder' => $settingstabeResult->homefeaturedvideoorder,
				'homepopularvideowidth' => $settingstabeResult->homepopularvideowidth,
				'homefeaturedvideowidth' => $settingstabeResult->homefeaturedvideowidth,
				'homerecentvideowidth' => $settingstabeResult->homerecentvideowidth
			);
			$arrhomethumbview = serialize($sitehomethumbview);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('homethumbview') . ' = ' . $db->quote($arrhomethumbview)));
			$db->setQuery($query);
			$db->query();
		}
	}

	if (!$updatesidethumbview) {
		$msgSQL .= "error adding 'sidethumbview' column to 'hdflv_site_settings' table <br />";
	} else {
		$query->clear() ->select('sidethumbview') ->from($db->quoteName('#__hdflv_site_settings'));
		$db->setQuery($query);
		$sidethumbviewResult = $db->loadResult();
		$upgradesidethumbview = unserialize($sidethumbviewResult);
		if (empty($sidethumbviewResult)) {
			// Get home page thumb details and serialize data
			$sitesidethumbview = array( 'sidepopularvideorow' => $settingstabeResult->sidepopularvideorow,
				'sidepopularvideocol' => $settingstabeResult->sidepopularvideocol,
				'sidefeaturedvideorow' => $settingstabeResult->sidefeaturedvideorow,
				'sidefeaturedvideocol' => $settingstabeResult->sidefeaturedvideocol,
				'siderelatedvideorow' => $settingstabeResult->siderelatedvideorow,
				'siderelatedvideocol' => $settingstabeResult->siderelatedvideocol,
				'siderecentvideorow' => $settingstabeResult->siderecentvideorow,
				'siderecentvideocol' => $settingstabeResult->siderecentvideocol,
				'siderandomvideorow' => 3,
				'siderandomvideocol' => 1,
				'sidecategoryvideorow' => 3,
				'sidecategoryvideocol' => 1,
			    'sidewatchlaterrow' => 3,
			    'sidewatchlatercol' => 1,
			    'sidehistoryvideorow' => 3,
			    'sidehistoryvideocol' => 1);			
			$arrsidethumbview = serialize($sitesidethumbview);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrsidethumbview)));
			$db->setQuery($query);
			$db->query();
		} 		
		if (!isset($upgradesidethumbview['siderandomvideorow'])) {
			$upgradesidethumbview['siderandomvideorow'] = 3;
			$arrupgradesiderandomvideorow = serialize($upgradesidethumbview);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrupgradesiderandomvideorow)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradesidethumbview['siderandomvideocol'])) {
			$upgradesidethumbview['siderandomvideocol'] = 1;
			$arrupgradesiderandomvideocol = serialize($upgradesidethumbview);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrupgradesiderandomvideocol)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradesidethumbview['sidecategoryvideorow'])) {
		  $upgradesidethumbview['sidecategoryvideorow'] = 1;
		  $arrupgradesidecategoryvideorow = serialize($upgradesidethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrupgradesidecategoryvideorow)));
		  $db->setQuery($query);
		  $db->query();
		}		
		if (!isset($upgradesidethumbview['sidecategoryvideocol'])) {
			$upgradesidethumbview['sidecategoryvideocol'] = 1;
			$arrupgradesidecategoryvideocol = serialize($upgradesidethumbview);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrupgradesidecategoryvideocol)));
			$db->setQuery($query);
			$db->query();
		}		
		if (!isset($upgradesidethumbview['sidewatchlaterrow'])) {
		  $upgradesidethumbview['sidewatchlaterrow'] = 1;
		  $data = serialize($upgradesidethumbview);
		  $query->clear()->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradesidethumbview['sidewatchlatercol'])) {
		  $upgradesidethumbview['sidewatchlatercol'] = 1;
		  $data = serialize($upgradesidethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradesidethumbview['sidehistoryvideorow'])) {
		  $upgradesidethumbview['sidehistoryvideorow'] = 1;
		  $data = serialize($upgradesidethumbview);
		  $query->clear()->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
		if (!isset($upgradesidethumbview['sidehistoryvideocol'])) {
		  $upgradesidethumbview['sidehistoryvideocol'] = 1;
		  $data = serialize($upgradesidethumbview);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
	}

	if (!$updatedispenable) {
		$msgSQL .= "error adding 'dispenable' column to 'hdflv_site_settings' table <br />";
	} else {
		$query->clear() ->select('dispenable') ->from($db->quoteName('#__hdflv_site_settings'));
		$db->setQuery($query);
		$dispenableResult = $db->loadResult();
		$upgradedisp = unserialize($dispenableResult);
		if (empty($dispenableResult)) {
			// Get thumbview details and serialize data
			$sitedispenable = array( 'allowupload' => $settingstabeResult->allowupload,
				'user_login' => $settingstabeResult->user_login,
				'ratingscontrol' => $settingstabeResult->ratingscontrol,
				'viewedconrtol' => $settingstabeResult->viewedconrtol,
				'reportvideo' => 0,
				'seo_option' => $settingstabeResult->seo_option,
				'adminapprove' => 1,
				'categoryplayer' => 0,
				'homeplayer' => 1,
				'upload_methods' => 'Upload,Youtube,URL,RTMP',
				'language_settings' => 'English.php',
				'disqusapi' => '""',
				'amazons3' => 0,
				'amazons3name' => '""',
				'amazons3link' => '""',
				'amazons3accesskey' => '""',
				'amazons3accesssecretkey_area' => '""',
				'facebookapi' => $settingstabeResult->facebookapi,
				'comment' => $settingstabeResult->comment,
				'facebooklike' => $settingstabeResult->facebooklike,
				'rssfeedicon' => 1, 
			    'playlist_limit' => 10
			);
			$arrdispenable = serialize($sitedispenable);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($arrdispenable)));
			$db->setQuery($query);
			$db->query();
		} 
		if (!isset($upgradedisp['upload_methods'])) {
			$upgradedisp['upload_methods'] = 'Upload,Youtube,URL,RTMP';
			$arrupgradedisp = serialize($upgradedisp);
			$query->clear()
					->update($db->quoteName('#__hdflv_site_settings'))
					->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradedisp['adminapprove'])) {
			$upgradedisp['adminapprove'] = 1;
			$arrupgradedisp = serialize($upgradedisp);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradedisp['reportvideo'])) {
			$upgradedisp['reportvideo'] = 0;
			$arrupgradedisp = serialize($upgradedisp);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradedisp['categoryplayer'])) {
			$upgradedisp['categoryplayer'] = 0;
			$arrupgradedisp = serialize($upgradedisp);
			$query->clear()
					->update($db->quoteName('#__hdflv_site_settings'))
					->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradedisp['homeplayer'])) {
			$upgradedisp['homeplayer'] = 1;
			$arrupgradedisp = serialize($upgradedisp);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradedisp['amazons3'])) {
			$upgradedisp['amazons3'] = 0;
			$arrupgradedisp = serialize($upgradedisp);
			$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradedisp['playlist_limit'])) {
		  $upgradedisp['playlist_limit'] = 0;
		  $data = serialize($upgradedisp);
		  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('dispenable') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
	}

	// Update player settings table
	$query->clear() ->select('*') ->from($db->quoteName('#__hdflv_player_settings'));
	$db->setQuery($query);
	$playersettingstabeResult = $db->loadObject();

	if (!$updateplayer_colors) {
		$msgSQL .= "error adding 'player_colors' column to 'hdflv_player_settings' table <br />";
	}
	if (!$updateplayer_icons) {
		$msgSQL .= "error adding 'player_icons' column to 'hdflv_player_settings' table <br />";
	} else {
		$query->clear() ->select('player_icons') ->from($db->quoteName('#__hdflv_player_settings'));
		$db->setQuery($query);
		$player_iconsResult = $db->loadResult();
		$upgradePlayerIcon = unserialize($player_iconsResult);
		if (empty($player_iconsResult)) {
			// Get player icon options and serialize data
			$updateplayer_icons = array(
				'autoplay' => $playersettingstabeResult->autoplay,
				'playlist_autoplay' => $playersettingstabeResult->playlist_autoplay,
				'playlist_open' => $playersettingstabeResult->playlist_open,
				'skin_autohide' => $playersettingstabeResult->skin_autohide,
				'fullscreen' => $playersettingstabeResult->fullscreen,
				'zoom' => $playersettingstabeResult->zoom,
				'timer' => $playersettingstabeResult->timer,
				'showTag' => 0,
				'shareurl' => $playersettingstabeResult->shareurl,
				'emailenable' => 1,
				'login_page_url' => $playersettingstabeResult->login_page_url,
				'embedVisible' => 1,
			    'iframeVisible' => 1,
				'progressControl' => 1,
				'skinvisible' => 0,
				'hddefault' => $playersettingstabeResult->hddefault,
				'imageDefault' => 1,
				'enabledownload' => 0,
				'prerollads' => $playersettingstabeResult->prerollads,
				'postrollads' => $playersettingstabeResult->postrollads,
				'imaads' => 0,
				'volumecontrol' => 1,
				'adsSkip' => 0,
				'midrollads' => $playersettingstabeResult->midrollads,
				'midbegin' => $playersettingstabeResult->midbegin,
				'midrandom' => $playersettingstabeResult->midrandom,
				'midadrotate' => $playersettingstabeResult->midadrotate,
				'googleana_visible' => $playersettingstabeResult->googleana_visible
			);
			$arrplayer_icons = serialize($updateplayer_icons);
			$query->clear() ->update($db->quoteName('#__hdflv_player_settings')) ->set(array($db->quoteName('player_icons') . ' = ' . $db->quote($arrplayer_icons)));
			$db->setQuery($query);
			$db->query();
		}
		if (!isset($upgradePlayerIcon['iframeVisible'])) {
		  $upgradePlayerIcon['iframeVisible'] = 1;
		  $data = serialize($upgradePlayerIcon);
		  $query->clear() ->update($db->quoteName('#__hdflv_player_settings')) ->set(array($db->quoteName('player_icons') . ' = ' . $db->quote($data)));
		  $db->setQuery($query);
		  $db->query();
		}
	}

	if (!$updateplayer_values) {
		$msgSQL .= "error adding 'player_values' column to 'hdflv_player_settings' table <br />";
	} else {
		$query->clear() ->select('player_values') ->from($db->quoteName('#__hdflv_player_settings'));
		$db->setQuery($query);
		$player_valuesResult = $db->loadResult();

		if (empty($player_valuesResult)) {
			// Get Player values and serialize data
			$updateplayer_values = array(
				'buffer' => $playersettingstabeResult->buffer,
				'width' => $playersettingstabeResult->width,
				'height' => $playersettingstabeResult->height,
				'normalscale' => $playersettingstabeResult->normalscale,
				'fullscreenscale' => $playersettingstabeResult->fullscreenscale,
				'volume' => $playersettingstabeResult->volume,
				'nrelated' => $playersettingstabeResult->nrelated,
				'ffmpegpath' => $playersettingstabeResult->ffmpegpath,
				'stagecolor' => $playersettingstabeResult->stagecolor,
				'licensekey' => $playersettingstabeResult->licensekey,
				'logourl' => $playersettingstabeResult->logourl,
				'logoalpha' => $playersettingstabeResult->logoalpha,
				'logoalign' => $playersettingstabeResult->logoalign,
				'skin_opacity' => 1,
				'subTitleColor' => '',
				'subTitleBgColor' => '',
				'subTitleFontFamily' => '',
				'subTitleFontSize' => '',
				'adsSkipDuration' => 5,
				'imaadbegin' => 10,
				'googleanalyticsID' => $playersettingstabeResult->googleanalyticsID,
				'midbegin' => $playersettingstabeResult->midbegin,
				'midinterval' => $playersettingstabeResult->midinterval,
				'related_videos' => $playersettingstabeResult->related_videos,
				'relatedVideoView' => 'side',
				'login_page_url' => $playersettingstabeResult->login_page_url
			);
			$arrplayer_values = serialize($updateplayer_values);
			$query->clear() ->update($db->quoteName('#__hdflv_player_settings')) ->set(array($db->quoteName('player_values') . ' = ' . $db->quote($arrplayer_values)));
			$db->setQuery($query);
			$db->query();
		}
	}

	if (!$updateMid) {
		$msgSQL .= "error adding 'member_id' column to 'category' table <br />";
	}

	if (!$updateGoogleAd || !$updateGoogleAd1 || !$updateGoogleAd2 || !$updateGoogleAd3) {
		$msgSQL .= "error updating columns in 'googlead' table <br />";
	}

	if (!$updateCategory || !$updateCategory1) {
		$msgSQL .= "error adding columns in 'hdflv_category' table <br />";
	}
}

/** Method to create channel profile, cover folder while installation */
createChannelDir();

// Install modules and plugin here
$installer->install($this->parent->getPath('source') . '/extensions/mod_hdvideosharecategories');
$installer->install($this->parent->getPath('source') . '/extensions/mod_hdvideosharemodules');
$installer->install($this->parent->getPath('source') . '/extensions/mod_hdvideosharesearch');
$installer->install($this->parent->getPath('source') . '/extensions/mod_hdvideosharerss');
$installer->install($this->parent->getPath('source') . '/extensions/mod_videoshare');
$installer->install($this->parent->getPath('source') . '/extensions/hvsarticle');

// Delete admin.contushdvideoshare.php file from the previous pack
if (version_compare(JVERSION, '1.5.0', 'ge')) {
	$componentPath = str_replace("com_installer", "com_contushdvideoshare", JPATH_COMPONENT_ADMINISTRATOR);

	if (file_exists($componentPath . '/admin.contushdvideoshare.php')) {
		unlink($componentPath . '/admin.contushdvideoshare.php');
	}
}

// Rename xml files for modules, plugin and component
if (version_compare(JVERSION, '2.5.0', 'ge')
	|| version_compare(JVERSION, '1.6.0', 'ge')
	|| version_compare(JVERSION, '1.7.0', 'ge')) {
	if (file_exists($componentPath . '/contushdvideoshare.xml')) {
		unlink($componentPath . '/contushdvideoshare.xml');
	}

	if (!defined('DS')){
		define('DS', DIRECTORY_SEPARATOR);
	}
	$rootPath = JPATH_SITE;

	$root = str_replace('administrator' . DS, '', $componentPath);
	if (JFile::exists($root . DS . 'views' . DS . 'category' . DS . 'tmpl' . DS . 'default.j3.xml')) {
		JFile::delete($root . DS . 'views' . DS . 'category' . DS . 'tmpl' . DS . 'default.j3.xml');
	}

	if (JFile::exists($rootPath . DS . 'modules' . DS . 'mod_hdvideosharecategories' . DS . 'mod_hdvideosharecategories.xml')) {
		JFile::delete($rootPath . DS . 'modules' . DS . 'mod_hdvideosharecategories' . DS . 'mod_hdvideosharecategories.xml');
	}
	JFile::move( $rootPath . DS . 'modules' . DS . 'mod_hdvideosharecategories' . DS . 'mod_hdvideosharecategories.j3.xml',
			$rootPath . DS . 'modules' . DS . 'mod_hdvideosharecategories' . DS . 'mod_hdvideosharecategories.xml');
	
	if (JFile::exists($rootPath . DS . 'modules' . DS . 'mod_hdvideosharemodules' . DS . 'mod_hdvideosharemodules.xml')) {
		JFile::delete($rootPath . DS . 'modules' . DS . 'mod_hdvideosharemodules' . DS . 'mod_hdvideosharemodules.xml');
	}
	JFile::move( $rootPath . DS . 'modules' . DS . 'mod_hdvideosharemodules' . DS . 'mod_hdvideosharemodules.j3.xml',
			$rootPath . DS . 'modules' . DS . 'mod_hdvideosharemodules' . DS . 'mod_hdvideosharemodules.xml');	

	if (JFile::exists($rootPath . DS . 'modules' . DS . 'mod_hdvideosharesearch' . DS . 'mod_hdvideosharesearch.xml')) {
	  JFile::delete($rootPath . DS . 'modules' . DS . 'mod_hdvideosharesearch' . DS . 'mod_hdvideosharesearch.xml');
	}
	JFile::move( $rootPath . DS . 'modules' . DS . 'mod_hdvideosharesearch' . DS . 'mod_hdvideosharesearch.j3.xml',
			$rootPath . DS . 'modules' . DS . 'mod_hdvideosharesearch' . DS . 'mod_hdvideosharesearch.xml');

	if (JFile::exists($rootPath . DS . 'modules' . DS . 'mod_videoshare' . DS . 'mod_videoshare.xml')) {
		JFile::delete($rootPath . DS . 'modules' . DS . 'mod_videoshare' . DS . 'mod_videoshare.xml');
	}
	JFile::move( $rootPath . DS . 'modules' . DS . 'mod_videoshare' . DS . 'mod_videoshare.j3.xml',
	$rootPath . DS . 'modules' . DS . 'mod_videoshare' . DS . 'mod_videoshare.xml');

	if (JFile::exists($rootPath . DS . 'modules' . DS . 'mod_hdvideosharerss' . DS . 'mod_hdvideosharerss.xml')) {
		JFile::delete($rootPath . DS . 'modules' . DS . 'mod_hdvideosharerss' . DS . 'mod_hdvideosharerss.xml');
	}	
	JFile::move(	$rootPath . DS . 'modules' . DS . 'mod_hdvideosharerss' . DS . 'mod_hdvideosharerss.j3.xml',
	$rootPath . DS . 'modules' . DS . 'mod_hdvideosharerss' . DS . 'mod_hdvideosharerss.xml');
	
	if (JFile::exists($rootPath . DS . 'plugins' . DS . 'content' . DS . 'hvsarticle' . DS . 'hvsarticle.xml')) {
	  JFile::delete($rootPath . DS . 'plugins' . DS . 'content' . DS . 'hvsarticle' . DS . 'hvsarticle.xml');
	}
	JFile::move($rootPath . DS . 'plugins' . DS . 'content' . DS . 'hvsarticle' . DS . 'hvsarticle.j3.xml',
			$rootPath . DS . 'plugins' . DS . 'content' . DS . 'hvsarticle' . DS . 'hvsarticle.xml');
}
?>
<!--Display installation status-->
<div style="float: left;">
	<a href="http://www.apptha.com/category/extension/Joomla/HD-Video-Share" target="_blank">
		<img src="components/com_contushdvideoshare/assets/contushdvideoshare-logo.png" alt="Joomla! HDVideoShare"
			 align="left" />
	</a>
</div>
<div style="float:right;">
	<a href="http://www.apptha.com/" target="_blank">
		<img src="components/com_contushdvideoshare/assets/contus.jpg" alt="contus products" align="right" />
	</a>
</div>
<br><br>

<h2 align="center">HD Video Share Installation Status</h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HD Video Share - Component'; ?></td>
			<td style="text-align: center;">
<?php
// Check installed components
$query->clear()->select('id')->from($db->quoteName('#__hdflv_player_settings'));
$db->setQuery($query);
$settings_id = $db->loadResult();
if ($settings_id) {
	if ($upgra == 'upgrade') {
		echo "<strong>" . JText::_('Upgrade successfully') . "</strong>";
	} else {
		echo "<strong>" . JText::_('Installed successfully') . "</strong>";
	}
}
else {
	echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
}
?>
			</td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'HD Video Share Categories - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
<?php  if (version_compare(JVERSION, '1.6.0', 'ge')) {
	$query->clear() ->select('extension_id') ->from($db->quoteName('#__extensions')) ->where($db->quoteName('type') . ' = ' . $db->quote('module'))
			->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharecategories'));
	$db->setQuery($query);
} else {
	$query->clear() ->select('id') ->from($db->quoteName('#__modules')) ->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharecategories'));
	$db->setQuery($query);
}
$category_id = $db->loadResult();
if ($category_id) {
	if ($upgra == 'upgrade') {
		echo "<strong>" . JText::_('Upgrade successfully') . "</strong>";
	} else {
		echo "<strong>" . JText::_('Installed successfully') . "</strong>";
	}
} else {
	echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
}
?>
			</td>
		</tr>

		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HD Video Share Modules - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
<?php
if (version_compare(JVERSION, '1.6.0', 'ge')) {
	$query->clear() ->select('extension_id') ->from($db->quoteName('#__extensions'))->where($db->quoteName('type') . ' = ' . $db->quote('module'))->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharemodules'));
	$db->setQuery($query);
} else {
	$query->clear()->select('id')->from($db->quoteName('#__modules'))->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharemodules'));
	$db->setQuery($query);
}
$popular_id = $db->loadResult();
if ($popular_id) {
	if ($upgra == 'upgrade') {
		echo "<strong>" . JText::_('Upgrade successfully') . "</strong>";
	} else {
		echo "<strong>" . JText::_('Installed successfully') . "</strong>";
	}
} else  {
	echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
}
?>
			</td>
		</tr>

		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HD Video Share Search - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
<?php  if (version_compare(JVERSION, '1.6.0', 'ge')) {
	$query->clear() ->select('extension_id') ->from($db->quoteName('#__extensions')) ->where($db->quoteName('type') . ' = ' . $db->quote('module'))
			->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharesearch'));

	$db->setQuery($query);
} else {
	$query->clear() ->select('id') ->from($db->quoteName('#__modules')) ->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharesearch'));
	$db->setQuery($query);
}
$search_id = $db->loadResult();
if ($search_id) {
	if ($upgra == 'upgrade') {
		echo "<strong>" . JText::_('Upgrade successfully') . "</strong>";
	}
	else {
		echo "<strong>" . JText::_('Installed successfully') . "</strong>";
	}
} else {
	echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
}
?>
			</td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HD Video Share Player - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
<?php if (version_compare(JVERSION, '1.6.0', 'ge')) {
	$query->clear() ->select('extension_id') ->from($db->quoteName('#__extensions')) ->where($db->quoteName('type') . ' = ' . $db->quote('module'))
			->where($db->quoteName('element') . ' = ' . $db->quote('mod_videoshare'));
	$db->setQuery($query);
} else {
	$query->clear() ->select('id') ->from($db->quoteName('#__modules'))
			->where($db->quoteName('module') . ' = ' . $db->quote('mod_videoshare'));
	$db->setQuery($query);
}
$search_id = $db->loadResult();
if ($search_id) {
	if ($upgra == 'upgrade') {
		echo "<strong>" . JText::_('Upgrade successfully') . "</strong>";
	} else {
		echo "<strong>" . JText::_('Installed successfully') . "</strong>";
	}
} else {
	echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
}
?>
			</td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HD Video Share RSS - ' . JText::_('Module'); ?></td>
			<td style="text-align: center;">
<?php
if (version_compare(JVERSION, '1.6.0', 'ge')) {
	$query->clear() ->select('extension_id') ->from($db->quoteName('#__extensions')) ->where($db->quoteName('type') . ' = ' . $db->quote('module'))
			->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharerss'));
	$db->setQuery($query);
} else {
	$query->clear() ->select('id') ->from($db->quoteName('#__modules')) ->where($db->quoteName('module') . ' = ' . $db->quote('mod_hdvideosharerss'));
	$db->setQuery($query);
}
$search_id = $db->loadResult();
if ($search_id) {
	if ($upgra == 'upgrade') {
		echo "<strong>" . JText::_('Upgrade successfully') . "</strong>";
	} else {
		echo "<strong>" . JText::_('Installed successfully') . "</strong>";
	}
} else {
	echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
}
?>
			</td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'HVS Article Plugin - ' . JText::_('Plugin'); ?></td>
			<td style="text-align: center;">
<?php if (version_compare(JVERSION, '1.6.0', 'ge')) {
	$query->clear() ->select('extension_id') ->from($db->quoteName('#__extensions')) ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
			->where($db->quoteName('element') . ' = ' . $db->quote('hvsarticle'));
	$db->setQuery($query);
} else {
	$query->clear() ->select('id') ->from($db->quoteName('#__plugins')) ->where($db->quoteName('element') . ' = ' . $db->quote('hvsarticle'));
	$db->setQuery($query);
}
$id = $db->loadResult();
if ($id) {
	if ($upgra == 'upgrade') {
		echo "<strong>" . JText::_('Upgrade successfully') . "</strong>";
	} else {
		echo "<strong>" . JText::_('Installed successfully') . "</strong>";
	}
} else {
	echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
}
?>
			</td>
		</tr>
	</tbody>
</table>
