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

/** No direct access to this file */
defined('_JEXEC') or die('Restricted access');

/** Import Joomla installer library */
jimport('joomla.installer.installer');

/** Import Joomla environment library */
jimport('joomla.environment.uri');

if (!defined('DS')){
  define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Component Contus HD Video Share installer file
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Com_ContushdvideoshareInstallerScript {
	/**
	 * Joomla installation hook for component
	 * 
	 * @param   string  $parent  parent value
	 * 
	 * @return  install
	 */
	public function install($parent) {
	}

	/**
	 * Joomla uninstallation hook for component
	 * 
	 * @param   string  $parent  parent value
	 * 
	 * @return  uninstall
	 */
	public function uninstall($parent) {
	  /** Get channel directory path
	   * Remvoe the folder while uninstalling */
	  $path = JPATH_ROOT . DS . 'images' . DS . 'channel';
	  JFolder::delete( $path );
	}

	/**
	 * Joomla before installation hook for component
	 * 
	 * @param   string  $type    type
	 * @param   string  $parent  parent value
	 * 
	 * @return  preflight
	 */
	public function preflight($type, $parent) {
	}

	/**
	 * Function to create index.html file within the channel and banner directory
	 *
	 * @param string $folder
	 *
	 * @return void
	 */
	public function createIndexFile ( $folder ) {
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
	public function createChannelDir () {
	  /** Set channel images directory path */
	  $channelDir = JPATH_ROOT . DS . 'images' . DS . 'channel';
      $bannerDir  = $channelDir . DS . 'banner';
      $coverDir   = $bannerDir . DS . 'cover';
      $profileDir = $bannerDir . DS . 'profile';
	
	  /** Check channel flder is exists
	   * If not then create banner, profile and cover directories */
	  if( ! file_exists( $channelDir ) ) {
	    mkdir( $channelDir );
	    $this->createIndexFile ( $channelDir );
	    mkdir( $bannerDir );
	    $this->createIndexFile ( $bannerDir );
	    mkdir( $coverDir );
	    $this->createIndexFile ( $coverDir );
	    mkdir( $profileDir );
	    $this->createIndexFile ( $profileDir );
	  }
	}	

	
	/**
	 * Joomla after installation hook for component
	 * 
	 * @param   string  $type    type
	 * @param   string  $parent  parent value
	 * 
	 * @return  postflight
	 */
	public function postflight($type, $parent) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$player_colorscolumnExists = $player_valuescolumnExists = $imaadscolumnExists = $embedcodecolumnExists = $historycolumnExists = 
		$subtitle1codecolumnExists = $subtitle2codecolumnExists = $subtile_lang2codecolumnExists = $subtile_lang1codecolumnExists = 
		$amazons3columnExists = $imaaddetcolumnExists = $dispenablecolumnExists = $sidethumbviewcolumnExists = $ratedusercolumnExists = 'false';
		$homethumbviewcolumnExists = $player_iconscolumnExists = $thumbviewcolumnExists = 'false';

		$conf = JFactory::getConfig();
		$prefix = $conf->get('dbprefix');
		
		$expected_tablename = $prefix . 'hdflv_player_settings';
		$showtablequery = 'SHOW TABLES LIKE "' . $expected_tablename . '";';
		$db->setQuery($showtablequery);
		$db->query();
		$tablecolumnData = $db->loadResult();

		if (!empty($tablecolumnData))
		{
			$playersettingsquery = 'SHOW COLUMNS FROM `#__hdflv_player_settings`';
			$db->setQuery($playersettingsquery);
			$db->query();
			$columnData = $db->loadObjectList();

			foreach ($columnData as $valueColumn) {
				if ($valueColumn->Field == 'player_colors') {
					$player_colorscolumnExists = 'true';
				}

				if ($valueColumn->Field == 'player_icons') {
					$player_iconscolumnExists = 'true';
				}

				if ($valueColumn->Field == 'player_values') {
					$player_valuescolumnExists = 'true';
				}
			}

			$query->clear() ->select('*') ->from($db->quoteName('#__hdflv_player_settings'));
			$db->setQuery($query);
			$playersettingstabeResult = $db->loadObject();

			if ($player_colorscolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_player_settings` ADD  `player_colors` longtext NOT NULL");
				$db->query();
			}

			/** If player icons column not exist then add this column and its values */
			if ($player_iconscolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_player_settings` ADD  `player_icons` longtext NOT NULL");
				$db->query();

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
					'shareurl' => $db->quote($playersettingstabeResult->shareurl),
					'emailenable' => 1,
					'login_page_url' => $db->quote($playersettingstabeResult->login_page_url),
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
				$query->clear() ->update($db->quoteName('#__hdflv_player_settings')) ->set($db->quoteName('player_icons') . ' = ' . $db->quote($arrplayer_icons));
				$db->setQuery($query);
				$db->query();
			} else {
			  /** If player icon column is exist then upgrade with new data */
			  $query->clear()->select('player_icons') ->from($db->quoteName('#__hdflv_player_settings'));
			  $db->setQuery($query);
			  $playerIconsResult = $db->loadResult();
			  $upgradePlayerIcons = unserialize($playerIconsResult);
			  
			  if(!empty ($upgradePlayerIcons)) {			  
    			  if (!isset($upgradePlayerIcons['iframeVisible'])) { 
    			    $upgradePlayerIcons['iframeVisible'] = 1;
    			    $data = serialize($upgradePlayerIcons);
    			    $query->clear() ->update($db->quoteName('#__hdflv_player_settings')) ->set($db->quoteName('player_icons') . ' = ' . $db->quote($data));
    			    $db->setQuery($query);
    			    $db->query();
    			  }
			  }
			}

			if ($player_valuescolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_player_settings` ADD  `player_values` longtext NOT NULL");
				$db->query();

				// Get Player values and serialize data
				$updateplayer_values = array( 'buffer' => $playersettingstabeResult->buffer,
					'width' => $playersettingstabeResult->width,
					'height' => $playersettingstabeResult->height,
					'normalscale' => $playersettingstabeResult->normalscale,
					'fullscreenscale' => $playersettingstabeResult->fullscreenscale,
					'volume' => $playersettingstabeResult->volume,
					'nrelated' => $playersettingstabeResult->nrelated,
					'ffmpegpath' => $db->quote($playersettingstabeResult->ffmpegpath),
					'stagecolor' => $playersettingstabeResult->stagecolor,
					'licensekey' => $db->quote($playersettingstabeResult->licensekey),
					'logourl' => $db->quote($playersettingstabeResult->logourl),
					'logoalpha' => $playersettingstabeResult->logoalpha,
					'logoalign' => $db->quote($playersettingstabeResult->logoalign),
					'adsSkipDuration' => 5,
					'imaadbegin' => 10,
					'skin_opacity' => 1,
					'subTitleColor' => '',
					'subTitleBgColor' => '',
					'subTitleFontFamily' => '',
					'subTitleFontSize' => '',
					'googleanalyticsID' => $db->quote($playersettingstabeResult->googleanalyticsID),
					'midbegin' => $playersettingstabeResult->midbegin,
					'midinterval' => $playersettingstabeResult->midinterval,
					'related_videos' => $playersettingstabeResult->related_videos,
					'relatedVideoView' => $db->quote('side'),
					'login_page_url' => $db->quote($playersettingstabeResult->login_page_url)
				);
				$arrplayer_values = serialize($updateplayer_values);
				$query->clear() ->update($db->quoteName('#__hdflv_player_settings')) ->set($db->quoteName('player_values') . ' = ' . $db->quote($arrplayer_values));
				$db->setQuery($query);
				$db->query();
			}

			$sitesettingsquery = 'SHOW COLUMNS FROM `#__hdflv_site_settings`';
			$db->setQuery($sitesettingsquery);
			$db->query();
			$sitesettingscolumnData = $db->loadObjectList();
			foreach ($sitesettingscolumnData as $valueColumn) {
				if ($valueColumn->Field == 'thumbview') {
					$thumbviewcolumnExists = 'true';
				}
				if ($valueColumn->Field == 'homethumbview') {
					$homethumbviewcolumnExists = 'true';
				}
				if ($valueColumn->Field == 'sidethumbview') {
					$sidethumbviewcolumnExists = 'true';
				}
				if ($valueColumn->Field == 'dispenable') {
					$dispenablecolumnExists = 'true';
				}
			}
			
			$query->clear()->select('*') ->from($db->quoteName('#__hdflv_site_settings'));
			$db->setQuery($query);
			$settingstabeResult = $db->loadObject();

			if ($thumbviewcolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_site_settings` ADD  `thumbview` longtext NOT NULL");
				$db->query();
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
				$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($arrthumbview));
				$db->setQuery($query);
				$db->query();
			} else {
			  $query->clear() ->select('thumbview') ->from($db->quoteName('#__hdflv_site_settings'));
			  $db->setQuery($query);
			  $thumbviewResult = $db->loadResult();
			  $upgradethumbview = unserialize($thumbviewResult);
			  if (!empty($thumbviewResult)) {
			    if (!isset($upgradethumbview['watchlaterrow'])) {
			      $upgradethumbview['watchlaterrow'] = 3;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			    if (!isset($upgradethumbview['watchlatercol'])) {
			      $upgradethumbview['watchlatercol'] = 4;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			    if (!isset($upgradethumbview['playlistrow'])) {
			      $upgradethumbview['playlistrow'] = 3;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			    if (!isset($upgradethumbview['playlistcol'])) {
			      $upgradethumbview['playlistcol'] = 4;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			    if (!isset($upgradethumbview['historyrow'])) {
			      $upgradethumbview['historyrow'] = 3;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			    if (!isset($upgradethumbview['historycol'])) {
			      $upgradethumbview['historycol'] = 4;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			    if (!isset($upgradethumbview['watchlaterwidth'])) {
			      $upgradethumbview['watchlaterwidth'] = 10;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			    if (!isset($upgradethumbview['historywidth'])) {
			      $upgradethumbview['historywidth'] = 10;
			      $data = serialize($upgradethumbview);
			      $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('thumbview') . ' = ' . $db->quote($data));
			      $db->setQuery($query);
			      $db->query();
			    }
			  }
			}

			if ($homethumbviewcolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_site_settings` ADD  `homethumbview` longtext NOT NULL");
				$db->query();
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
				$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('homethumbview') . ' = ' . $db->quote($arrhomethumbview));
				$db->setQuery($query);
				$db->query();
			}

			if ($sidethumbviewcolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_site_settings` ADD  `sidethumbview` longtext NOT NULL");
				$db->query();
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
				    'sidehistoryvideocol' => 1
				);
				$arrsidethumbview = serialize($sitesidethumbview);
				$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrsidethumbview));
				$db->setQuery($query);
				$db->query();
			} else {
				$query->clear() ->select('sidethumbview') ->from($db->quoteName('#__hdflv_site_settings'));
				$db->setQuery($query);
				$sidethumbviewResult = $db->loadResult();
				$upgradesidethumbview = unserialize($sidethumbviewResult);

				if (!empty($sidethumbviewResult)) {
					if (!isset($upgradesidethumbview['siderandomvideorow'])) {
						$upgradesidethumbview['siderandomvideorow'] = 3;
						$arrupgradesiderandomvideorow = serialize($upgradesidethumbview);
						$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrupgradesiderandomvideorow));
						$db->setQuery($query);
						$db->query();
					} 
					if (!isset($upgradesidethumbview['siderandomvideocol'])) {
						$upgradesidethumbview['siderandomvideocol'] = 1;
						$arrupgradesiderandomvideocol = serialize($upgradesidethumbview);
						$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('sidethumbview') . ' = ' . $db->quote($arrupgradesiderandomvideocol));
						$db->setQuery($query);
						$db->query();
					}					
					if (!isset($upgradesidethumbview['sidecategoryvideorow'])) {
						$upgradesidethumbview['sidecategoryvideorow'] = 3;
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
					  $upgradesidethumbview['sidewatchlaterrow'] = 3;
					  $data = serialize($upgradesidethumbview);
					  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($data)));
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
					  $upgradesidethumbview['sidehistoryvideorow'] = 3;
					  $data = serialize($upgradesidethumbview);
					  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set(array($db->quoteName('sidethumbview') . ' = ' . $db->quote($data)));
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
			}

			if ($dispenablecolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_site_settings` ADD  `dispenable` longtext NOT NULL");
				$db->query();

				// Get thumbview details and serialize data
				$sitedispenable = array( 'allowupload' => $settingstabeResult->allowupload,
					'user_login' => $settingstabeResult->user_login,
					'ratingscontrol' => $settingstabeResult->ratingscontrol,
					'viewedconrtol' => $settingstabeResult->viewedconrtol,
					'reportvideo' => 0,
					'seo_option' => $settingstabeResult->seo_option,
					'adminapprove' => 0,
					'categoryplayer' => 0,
					'homeplayer' => 1,
					'upload_methods' => $db->quote('Upload,Youtube,URL,RTMP'),
					'language_settings' => $db->quote('English.php'),
					'disqusapi' => '""',
					'amazons3' => 0,
					'amazons3name' => '""',
					'amazons3link' => '""',
					'amazons3accesskey' => '""',
					'amazons3accesssecretkey_area' => '""',
					'facebookapi' => $db->quote($settingstabeResult->facebookapi),
					'comment' => $db->quote($settingstabeResult->comment),
					'facebooklike' => $db->quote($settingstabeResult->facebooklike),
					'rssfeedicon' => 1,
					'playlist_limit' => 10
				);
				$arrdispenable = serialize($sitedispenable);
				$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('dispenable') . ' = ' . $db->quote($arrdispenable));
				$db->setQuery($query);
				$db->query();
			} else {
				$query->clear() ->select('dispenable') ->from($db->quoteName('#__hdflv_site_settings'));
				$db->setQuery($query);
				$dispenableResult = $db->loadResult();
				$upgradedisp = unserialize($dispenableResult);

				if (!isset($upgradedisp['upload_methods'])) {
					$upgradedisp['upload_methods'] = 'Upload,Youtube,URL,RTMP';
					$arrupgradedisp = serialize($upgradedisp);
					$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp));
					$db->setQuery($query);
					$db->query();
				}
				if (!isset($upgradedisp['adminapprove'])) {
					$upgradedisp['adminapprove'] = 0;
					$arrupgradedisp = serialize($upgradedisp);
					$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp));
					$db->setQuery($query);
					$db->query();
				}
				if (!isset($upgradedisp['reportvideo'])) {
					$upgradedisp['reportvideo'] = 0;
					$arrupgradedisp = serialize($upgradedisp);
					$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp));
					$db->setQuery($query);
					$db->query();
				}
				if (!isset($upgradedisp['categoryplayer'])) {
					$upgradedisp['categoryplayer'] = 0;
					$arrupgradedisp = serialize($upgradedisp);
					$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp));
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
					$query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp));
					$db->setQuery($query);
					$db->query();
				}
				if (!isset($upgradedisp['playlist_limit'])) {
				  $upgradedisp['playlist_limit'] = 10;
				  $arrupgradedisp = serialize($upgradedisp);
				  $query->clear() ->update($db->quoteName('#__hdflv_site_settings')) ->set($db->quoteName('dispenable') . ' = ' . $db->quote($arrupgradedisp));
				  $db->setQuery($query);
				  $db->query();
				}
			}

			$googleadquery = 'SHOW COLUMNS FROM `#__hdflv_googlead`';
			$db->setQuery($googleadquery);
			$db->query();
			$googleadcolumnData = $db->loadObjectList();
			foreach ($googleadcolumnData as $valueColumn) {
				if ($valueColumn->Field == 'imaaddet') {
					$imaaddetcolumnExists = 'true';
					break;
				}
			}
			if ($imaaddetcolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_googlead` ADD  `imaaddet` longtext NOT NULL");
				$db->query();
			}
			
			$userquery = 'SHOW COLUMNS FROM `#__hdflv_user`';
			$db->setQuery($userquery);
			$db->query();
			$usercolumnData = $db->loadObjectList();			
			foreach ($usercolumnData as $valueColumn) {
			  if ($valueColumn->Field == 'Pause_History_State') {
			    $historycolumnExists = 'true';			
			    break;
			  }
			}			
			if ($historycolumnExists == 'false') {
			  $db->setQuery("ALTER TABLE `#__hdflv_user` ADD  `Pause_History_State` int(50) NOT NULL");
			  $db->query();
			}

			$uploadquery = 'SHOW COLUMNS FROM `#__hdflv_upload`';
			$db->setQuery($uploadquery);
			$db->query();
			$uploadcolumnData = $db->loadObjectList();
			foreach ($uploadcolumnData as $valueColumn) {
				if ($valueColumn->Field == 'imaads') {
					$imaadscolumnExists = 'true';
				}

				if ($valueColumn->Field == 'embedcode') {
					$embedcodecolumnExists = 'true';
				}

				if ($valueColumn->Field == 'rateduser') {
					$ratedusercolumnExists = 'true';
				}

				if ($valueColumn->Field == 'subtitle1') {
					$subtitle1codecolumnExists = 'true';
				}

				if ($valueColumn->Field == 'subtitle2') {
					$subtitle2codecolumnExists = 'true';
				}

				if ($valueColumn->Field == 'subtile_lang2') {
					$subtile_lang2codecolumnExists = 'true';
				}

				if ($valueColumn->Field == 'subtile_lang1') {
					$subtile_lang1codecolumnExists = 'true';
				}

				if ($valueColumn->Field == 'amazons3') {
					$amazons3columnExists = 'true';
				}
			}
			if ($imaadscolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `imaads` TINYINT( 1 ) NOT NULL DEFAULT '0'");
				$db->query();
			}
			if ($embedcodecolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `embedcode` longtext NOT NULL ");
				$db->query();
			}
			if ($ratedusercolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `rateduser` longtext NOT NULL ");
				$db->query();
			}
			if ($subtitle1codecolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `subtitle1` varchar(255) CHARACTER SET utf8 NOT NULL ");
				$db->query();
			}
			if ($subtitle2codecolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `subtitle2` varchar(255) CHARACTER SET utf8 NOT NULL ");
				$db->query();
			}
			if ($subtile_lang2codecolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `subtile_lang2` text CHARACTER SET utf8 NOT NULL");
				$db->query();
			} 
			if ($subtile_lang1codecolumnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `subtile_lang1` text CHARACTER SET utf8 NOT NULL");
				$db->query();
			}
			if ($amazons3columnExists == 'false') {
				$db->setQuery("ALTER TABLE  `#__hdflv_upload` ADD  `amazons3` tinyint(3) NOT NULL DEFAULT '0'");
				$db->query();
			}
			
			$playlisttablequery = 'SHOW TABLES LIKE "#__hdflv_playlist";';
			$db->setQuery($playlisttablequery);
			$db->query();
			$playlistcolumnData = $db->loadResult();			
			if (empty($playlistcolumnData)) {
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
				$db->query();
			}
			
			$videoplaylisttablequery = 'SHOW TABLES LIKE "#__hdflv_video_playlist";';
			$db->setQuery($videoplaylisttablequery);
			$db->query();
			$videoplaylistcolumnData = $db->loadResult();			
			if (empty($videoplaylistcolumnData)) {
				// Create Video playlist table
				$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_video_playlist` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
			  `vid` int(11) NOT NULL,
			  `catid` varchar(100) NOT NULL,
			  PRIMARY KEY (`id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
				$db->query();
			}	

			$watchhistorytablequery = 'SHOW TABLES LIKE "#__hdflv_watchhistory";';
			$db->setQuery($watchhistorytablequery);
			$db->query();
			$watchhistorycolumnData = $db->loadResult();
			if (empty($watchhistorycolumnData)) {
			// Create Watch History Table
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_watchhistory` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userId` int(50) NOT NULL,
  `VideoId` int(50) NOT NULL,
  `watchedOn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
			$db->query();
			}
				
			$watchlatertablequery = 'SHOW TABLES LIKE "#__hdflv_watchlater";';
			$db->setQuery($watchlatertablequery);
			$db->query();
			$watchlatercolumnData = $db->loadResult();
			if (empty($watchlatercolumnData)) {
			// Create Watch Later Table
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_watchlater` (
  `user_id` int(5) NOT NULL,
  `video_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			$db->query();
			}
				
			$channeltablequery = 'SHOW TABLES LIKE "#__hdflv_channel";';
			$db->setQuery($channeltablequery);
			$db->query();
			$channelcolumnData = $db->loadResult();
			if (empty($channelcolumnData)) {
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
			}
				
			$channelnotificationtablequery = 'SHOW TABLES LIKE "#__hdflv_channel";';
			$db->setQuery($channelnotificationtablequery);
			$db->query();
			$channelnotificationcolumnData = $db->loadResult();
			if (empty($channelnotificationcolumnData)) {
			// Create Channel Notification Table
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_channel_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sub_id` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
			$db->query();
			}
				
			$channelsubscribetablequery = 'SHOW TABLES LIKE "#__hdflv_channel";';
			$db->setQuery($channelsubscribetablequery);
			$db->query();
			$channelsubscribecolumnData = $db->loadResult();
			if (empty($channelsubscribecolumnData)) {
			// Create Channel Subscribe Table
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_channel_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sub_id` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
			$db->query();
			}
				
			$channelvideostablequery = 'SHOW TABLES LIKE "#__hdflv_channel";';
			$db->setQuery($channelvideostablequery);
			$db->query();
			$channelvideoscolumnData = $db->loadResult();
			if (empty($channelvideoscolumnData)) {
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
			}
		}
		else {
			// Create ads table
			$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__hdflv_ads`(
			        `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
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
        		    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;" );
			$db->query();
			
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
			$db->query();
				
			// Create Video playlist table
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_video_playlist` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
			  `vid` int(11) NOT NULL,
			  `catid` varchar(100) NOT NULL,
			  PRIMARY KEY (`id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
			$db->query();

			// Create category table
			$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__hdflv_category` (
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
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;" );
			$db->query();
			// Create sample data fo category table
			$column_category = array( 'id', 'member_id', 'category', 'seo_category', 'parent_id', 'ordering', 'lft', 'rgt', 'published' );
			$query->clear() ->insert($db->quoteName('#__hdflv_category')) ->columns($column_category)
					->values( implode( ',', array( 1, 0, $db->quote('Speeches'), $db->quote('speeches'), 0, 1, 19, 20, 1 ) ) )
					->values( implode( ',', array( 2, 0, $db->quote('Interviews'), $db->quote('interviews'), 0, 2, 11, 12, 1 ) ) )
					->values( implode( ',', array( 3, 0, $db->quote('Talk Shows'), $db->quote('talk-shows'), 0, 3, 21, 22, 1 ) ) )
					->values( implode( ',', array( 4, 0, $db->quote('News & Info'), $db->quote('news-info'), 0, 4, 15, 16, 1 ) ) )
					->values( implode( ',', array( 5, 0, $db->quote('Documentary'), $db->quote('documentary'), 0, 5, 7, 8, 1 ) ) )
					->values( implode( ',', array( 6, 0, $db->quote('Travel'), $db->quote('travel'), 0, 6, 25, 26, 1 ) ) )
					->values( implode( ',', array( 7, 0, $db->quote('Cooking'), $db->quote('cooking'), 0, 7, 5, 6, 1 ) ) )
					->values( implode( ',', array( 8, 0, $db->quote('Music'), $db->quote('music'), 0, 8, 13, 14, 1 ) ) )
					->values( implode( ',', array( 9, 0, $db->quote('Trailers'), $db->quote('trailers'), 0, 9, 23, 24, 1 ) ) )
					->values( implode( ',', array( 10, 0, $db->quote('Religious'), $db->quote('religious'), 0, 10, 17, 18, 1 ) ) )
					->values( implode( ',', array( 11, 0, $db->quote('TV Serials & Shows'), $db->quote('tv-serials-shows'), 0, 11, 27, 28, 1 ) ) )
					->values( implode( ',', array( 12, 0, $db->quote('Greetings'), $db->quote('greetings'), 0, 12, 9, 10, 1 ) ) )
					->values( implode( ',', array( 13, 0, $db->quote('Comedy'), $db->quote('comedy'), 0, 13, 3, 4, 1 ) ) )
					->values( implode( ',', array( 14, 0, $db->quote('Actors'), $db->quote('actors'), 0, 14, 1, 2, 1 ) ) );
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
			
			/** Get all category details */
			$query->clear()->select('*')->from('#__hdflv_category');
			$db->setQuery($query);
			$defaultCategoryDetails = $db->loadObjectList();

			/** Loop through category details */
			foreach ($defaultCategoryDetails as $catDetails) {
			  /** Set category title and seo title */
			  $catTitle = $catDetails->category;
			  $seoCategory = strtolower(stripslashes ( $catTitle ));
        	  $seoCategory = preg_replace ( '/[&:\s]+/i', '-', $seoCategory );
        	  $alias = preg_replace ( '/[#!@$%^.,:;\/&*(){}\"\'\[\]<>|?]+/i', '', $seoCategory );
              $alias = preg_replace ( '/---|--+/i', '-', $alias ); 
			  
			  /** Set url for hidden category menu */
			  $url = 'index.php?option=com_contushdvideoshare&view=category&catid='.$catDetails->id;
			   
			  /** Create category hidden menu */
			  $values = array($db->quote('hiddencategorymenu'), $db->quote( $catTitle ),$db->quote($alias),$db->quote($alias),$db->quote("$url"),$db->quote("component"),1,1,1,$extension_id,0,1,$db->quote('{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1}'));
			  $query->clear() ->insert($db->quoteName('#__menu')) ->columns($db->quoteName($columns)) ->values(implode(',', $values));
			  $db->setQuery($query);
			  $db->query();
			}			
			/** Code to create category hidden menu ends */
			
			// Create comments table
			$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__hdflv_comments` (
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
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;" );
			$db->query();

			// Create google ad table
			$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__hdflv_googlead` (
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
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;" );
			$db->query();
			// Create sample data for google ad table
			$column_googlead = array( 'id', 'code', 'showoption', 'closeadd', 'reopenadd', 'publish', 'ropen', 'showaddc', 'showaddm', 'showaddp' );
			$query->clear() ->insert($db->quoteName('#__hdflv_googlead')) ->columns($column_googlead) ->values(implode(',', array(1, '""', 1, 10, '0', 0, 10, 0, '0', '0')));
			$db->setQuery($query);
			$db->query();

			// Create player settings table
			$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__hdflv_player_settings` (
					`id` int(20) NOT NULL AUTO_INCREMENT,
					`published` tinyint(4) NOT NULL,
					`player_colors` longtext NOT NULL,
					`player_icons` longtext NOT NULL,
					`player_values` longtext NOT NULL,
					`uploadmaxsize` int(10) NOT NULL,
					`logopath` varchar(255) NOT NULL,
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;" );
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
			$query->clear() ->insert($db->quoteName('#__hdflv_player_settings')) ->columns($column_settings) 
			->values( implode( ',', array( $db->quote('1'), $db->quote('1'), $db->quote('100'), $db->quote(''), 
			    $db->quote($player_colors), $db->quote($player_icons), $db->quote($player_values) ) ) );
			$db->setQuery($query);
			$db->query();

			// Create site settings table
			$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__hdflv_site_settings` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`published` tinyint(4) NOT NULL,
					`thumbview` longtext NOT NULL,
					`homethumbview` longtext NOT NULL,
					`sidethumbview` longtext NOT NULL,
					`dispenable` longtext NOT NULL,
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;" );
			$db->query();
			// Create sample data for site settings table
			$homethumbview = 'a:15:{s:16:"homepopularvideo";s:1:"1";s:19:"homepopularvideorow";s:1:"1";'
					. 's:19:"homepopularvideocol";s:1:"4";s:17:"homefeaturedvideo";s:1:"1";s:20:"homefeaturedvideorow";'
					. 's:1:"1";s:20:"homefeaturedvideocol";s:1:"4";s:15:"homerecentvideo";s:1:"1";s:18:"homerecentvideorow";'
					. 's:1:"1";s:18:"homerecentvideocol";s:1:"4";s:21:"homepopularvideoorder";s:1:"1";'
					. 's:22:"homefeaturedvideoorder";s:1:"2";s:20:"homerecentvideoorder";s:1:"3";s:21:"homepopularvideowidth";'
					. 's:2:"20";s:22:"homefeaturedvideowidth";s:2:"20";s:20:"homerecentvideowidth";s:2:"20";}';
			$disenable = 'a:24:{s:11:"allowupload";s:1:"1";s:12:"adminapprove";s:1:"1";s:11:"rssfeedicon";s:1:"1";s:10:"user_login";s:1:"1";s:14:"ratingscontrol";s:1:"1";s:13:"viewedconrtol";s:1:"1";s:11:"reportvideo";s:1:"0";s:14:"categoryplayer";s:1:"1";s:10:"homeplayer";s:1:"1";s:10:"limitvideo";s:3:"100";s:10:"youtubeapi";s:0:"";s:10:"seo_option";s:1:"0";s:14:"upload_methods";s:23:"Upload,Youtube,URL,RTMP";s:17:"language_settings";s:11:"English.php";s:9:"disqusapi";s:1:" ";s:11:"facebookapi";s:1:" ";s:7:"comment";s:1:"0";s:8:"amazons3";s:1:"0";s:12:"amazons3name";s:0:"";s:12:"amazons3link";s:0:"";s:17:"amazons3accesskey";s:0:"";s:28:"amazons3accesssecretkey_area";s:0:"";s:12:"facebooklike";s:1:"1";s:14:"playlist_limit";s:2:"10";}';
			$thumbview = 'a:33:{s:9:"featurrow";s:1:"3";s:9:"featurcol";s:1:"4";s:13:"watchlaterrow";s:1:"3";s:13:"watchlatercol";s:1:"4";s:9:"recentrow";s:1:"3";s:9:"recentcol";s:1:"4";s:11:"categoryrow";s:1:"3";s:11:"categorycol";s:1:"4";s:11:"playlistrow";s:1:"3";s:11:"playlistcol";s:1:"4";s:10:"popularrow";s:1:"3";s:10:"popularcol";s:1:"4";s:9:"searchrow";s:1:"3";s:9:"searchcol";s:1:"4";s:10:"relatedrow";s:1:"3";s:10:"relatedcol";s:1:"4";s:11:"featurwidth";s:2:"20";s:11:"recentwidth";s:2:"20";s:13:"categorywidth";s:2:"20";s:13:"playlistwidth";s:2:"20";s:12:"popularwidth";s:2:"20";s:15:"watchlaterwidth";s:2:"20";s:11:"searchwidth";s:2:"20";s:12:"relatedwidth";s:2:"20";s:15:"memberpagewidth";s:2:"20";s:12:"myvideowidth";s:2:"20";s:13:"memberpagerow";s:1:"3";s:13:"memberpagecol";s:1:"4";s:10:"myvideorow";s:1:"3";s:10:"myvideocol";s:1:"4";s:10:"historyrow";s:1:"3";s:10:"historycol";s:1:"4";s:12:"historywidth";s:2:"20";}';
			$sidethumbview = 'a:16:{s:19:"sidepopularvideorow";s:1:"2";s:19:"sidepopularvideocol";s:1:"1";s:20:"sidefeaturedvideorow";s:1:"2";s:20:"sidefeaturedvideocol";s:1:"1";s:19:"siderelatedvideorow";s:1:"2";s:19:"siderelatedvideocol";s:1:"1";s:18:"siderecentvideorow";s:1:"2";s:18:"siderecentvideocol";s:1:"1";s:17:"sidewatchlaterrow";s:1:"2";s:17:"sidewatchlatercol";s:1:"1";s:18:"siderandomvideorow";s:1:"2";s:18:"siderandomvideocol";s:1:"1";s:20:"sidecategoryvideorow";s:1:"2";s:20:"sidecategoryvideocol";s:1:"1";s:19:"sidehistoryvideorow";s:1:"2";s:19:"sidehistoryvideocol";s:1:"1";}';			
			$column_site_settings = array( 'id', 'published', 'homethumbview', 'dispenable', 'thumbview', 'sidethumbview');
			$query->clear() ->insert($db->quoteName('#__hdflv_site_settings')) ->columns($column_site_settings)
					->values( implode( ',', array( 1, 1, $db->quote($homethumbview), $db->quote($disenable), $db->quote($thumbview),
										$db->quote($sidethumbview) ) ) );
			$db->setQuery($query);
			$db->query();

			// Create video upload table
			$db->setQuery( "CREATE TABLE IF NOT EXISTS `#__hdflv_upload` (
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
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
			$db->query();

			$user = JFactory::getUser();
			$userid = $user->get('id');
			$db = JFactory::getDBO();
			$groupname = 8;
			$uploadcolumnsArray = array( 'id', 'memberid', 'published', 'title', 'seotitle', 'featured', 'type', 'rate',
				'ratecount', 'times_viewed', 'videos', 'filepath', 'videourl', 'thumburl', 'previewurl',
				'hdurl', 'home', 'playlistid', 'duration', 'ordering', 'streamerpath', 'streameroption',
				'postrollads', 'prerollads', 'description', 'targeturl', 'download', 'prerollid',
				'postrollid', 'created_date', 'addedon', 'usergroupid', 'useraccess', 'islive', 'imaads',
				'embedcode', 'rateduser' );
			$query->clear() ->insert($db->quoteName('#__hdflv_upload')) ->columns($uploadcolumnsArray)
					->values(
							implode(
									',',
									array(
										1, $db->quote($userid), 1,
										$db->quote('The Hobbit: The Desolation of Smaug International Trailer'),
										$db->quote('The-Hobbit-The-Desolation-of-Smaug-International-Trailer'), 1, 0, 9,
										2, 3, $db->quote(''), $db->quote('Youtube'),
										$db->quote('http://www.youtube.com/watch?v=TeGb5XGk2U0'),
										$db->quote('http://img.youtube.com/vi/TeGb5XGk2U0/mqdefault.jpg'),
										$db->quote('http://img.youtube.com/vi/TeGb5XGk2U0/maxresdefault.jpg'),
										$db->quote(''), 0, 9, $db->quote(''), 0, $db->quote(''), $db->quote(''), 0, 0,
										$db->quote(''), $db->quote(''), 0, 0, 0, $db->quote('2010-06-05 01:06:06'),
										$db->quote('2010-06-28 16:26:39'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
										)
									)
							)
					->values(
							implode(
									',',
									array(
										2, $db->quote($userid), 1,
										$db->quote('Iron Man 3'),
										$db->quote('Iron-Man-3'), 1, 0, 0,
										0, 95, $db->quote(''), $db->quote('Youtube'),
										$db->quote('http://www.youtube.com/watch?v=Ke1Y3P9D0Bc'),
										$db->quote('http://img.youtube.com/vi/Ke1Y3P9D0Bc/mqdefault.jpg'),
										$db->quote('http://img.youtube.com/vi/Ke1Y3P9D0Bc/maxresdefault.jpg'),
										$db->quote(''), 0, 14, $db->quote(''), 1, $db->quote(''), $db->quote(''), 0, 0,
										$db->quote(''), $db->quote(''), 0, 0, 0, $db->quote('2010-06-05 01:06:28'),
										$db->quote('2010-06-28 16:45:59'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
										)
									)
							)
					->values(
							implode(
									',',
									array(
										3, $db->quote($userid), 1,
										$db->quote('GI JOE 2 Retaliation Trailer 2'),
										$db->quote('GI-JOE-2-Retaliation-Trailer-2'), 1, 0, 5,
										1, 9, $db->quote(''), $db->quote('Youtube'),
										$db->quote('http://www.youtube.com/watch?v=mKNpy-tGwxE'),
										$db->quote('http://img.youtube.com/vi/mKNpy-tGwxE/mqdefault.jpg'),
										$db->quote('http://img.youtube.com/vi/mKNpy-tGwxE/maxresdefault.jpg'),
										$db->quote(''), 0, 5, $db->quote(''), 2, $db->quote(''), $db->quote(''), 0, 0,
										$db->quote(''), $db->quote(''), 0, 0, 0, $db->quote('2010-06-05 01:06:25'),
										$db->quote('2010-06-28 16:29:39'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
										)
									)
							)
					->values(
							implode(
									',',
									array(
										4, $db->quote($userid), 1,
										$db->quote('UP HD 1080p Trailer'),
										$db->quote('UP-HD-1080p-Trailer'), 1, 0, 0,
										0, 29, $db->quote(''), $db->quote('Youtube'),
										$db->quote('http://www.youtube.com/watch?v=1cRuA64m_lY'),
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
										5, $db->quote($userid), 1,
										$db->quote('Chipwrecked: Survival Tips'),
										$db->quote('Chipwrecked-Survival-Tips'), 1, 0, 0,
										0, 8, $db->quote(''), $db->quote('Youtube'),
										$db->quote('http://www.youtube.com/watch?v=dLIEKGNYbVU'),
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
										6, $db->quote($userid), 1,
										$db->quote('THE TWILIGHT SAGA: BREAKING DAWN PART 2'),
										$db->quote('THE-TWILIGHT-SAGA-BREAKING-DAWN-PART-2'), 1, 0, 0,
										0, 8, $db->quote(''), $db->quote('Youtube'),
										$db->quote('http://www.youtube.com/watch?v=ey0aA3YY0Mo'),
										$db->quote('http://img.youtube.com/vi/ey0aA3YY0Mo/mqdefault.jpg'),
										$db->quote('http://img.youtube.com/vi/ey0aA3YY0Mo/maxresdefault.jpg'),
										$db->quote(''), 0, 11, $db->quote(''), 5, $db->quote(''), $db->quote(''), 0, 0,
										$db->quote(''), $db->quote(''), 0, 0, 0, $db->quote('2011-01-24 06:01:26'),
										$db->quote('2011-01-24 11:31:26'), $groupname, 0, 0, 0, $db->quote(''), $db->quote('')
										)
									)
							);
			$db->setQuery($query);
			$db->query();

			// Create video share user table
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_user` (
`member_id` int(11) NOT NULL,
`allowupload` tinyint(4) NOT NULL,
`Pause_History_State` int(50) NOT NULL,
PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
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
			
			// Create video category table
			$db->setQuery("CREATE TABLE IF NOT EXISTS `#__hdflv_video_category` (
`vid` int(11) NOT NULL,
`catid` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
			$db->query();
			// Create sample data for video category table
			$query->clear()->insert($db->quoteName('#__hdflv_video_category'))->columns($db->quoteName(array('vid', 'catid')))
					->values(implode(',', array(1, 9)))->values(implode(',', array(2, 14)))
					->values(implode(',', array(3, 5)))->values(implode(',', array(4, 5)))
					->values(implode(',', array(5, 5)))->values(implode(',', array(6, 11)));
			$db->setQuery($query);
			$db->query();
		}

		$status = new stdClass;
		$status->modules = array();
		$src = $parent->getParent()->getPath('source');
		$manifest = $parent->getParent()->manifest;

		$modules = $manifest->xpath('modules/module');
		$root = JPATH_SITE;

		if (!defined('DS')) {
		  define('DS', DIRECTORY_SEPARATOR);
		}
		
		foreach ($modules as $module) {
			$name = (string) $module->attributes()->module;
			$client = (string) $module->attributes()->client;
			$path = $src . '/extensions/' . $name;
			$installer = new JInstaller;
			$result = $installer->install($path);

			if ($result) {
			    if (JFile::exists($root . DS . 'modules' . DS . $name . DS . $name . '.xml')) {
					JFile::delete($root . DS . 'modules' . DS . $name . DS . $name . '.xml');
				}
				JFile::move( $root . DS . 'modules' . DS . $name . DS . $name . '.j3.xml', $root . DS . 'modules' . DS . $name . DS . $name . '.xml' );
			}
			$status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
		}

                if (JFile::exists($root . '/components/com_contushdvideoshare/views/category/tmpl/default.xml')) {
			JFile::delete($root . '/components/com_contushdvideoshare/views/category/tmpl/default.xml');
		}

		JFile::move( $root . '/components/com_contushdvideoshare/views/category/tmpl/default.j3.xml', $root . '/components/com_contushdvideoshare/views/category/tmpl/default.xml');
                
        $plugins = $manifest->xpath('plugins/plugin');
		foreach ($plugins as $plugin) {
			$name = (string) $plugin->attributes()->plugin;
			$group = (string) $plugin->attributes()->group;
			$path = $src . '/extensions/' . $name;
			$installer = new JInstaller;
			$result = $installer->install($path);
			$query->clear() ->update($db->quoteName('#__extensions')) ->set($db->quoteName('enabled') . ' = 1')
					->where($db->quoteName('type') . ' = ' . $db->quote('plugin')) ->where($db->quoteName('element') . ' = ' . $db->quote($name))
					->where($db->quoteName('folder') . ' = ' . $db->quote($group));
			$db->setQuery($query);
			$db->query();
			$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
		}

		JFile::move( $root . DS . 'plugins' . DS . 'content' . DS . 'hvsarticle' . DS . 'hvsarticle.j3.xml',
				$root . DS . 'plugins' . DS . 'content' . DS . 'hvsarticle' . DS . 'hvsarticle.xml' );
		?>
		<style  type="text/css">
			.row-fluid .span10{width: 84%;}
			table{width: 100%;}
			table.adminlist {
				width: 100%;
				border-spacing: 1px;
				background-color: #f3f3f3;
				color: #666;
			}

			table.adminlist td,
			table.adminlist th {
				padding: 4px;
			}

			table.adminlist td {padding-left: 8px;}

			table.adminlist thead th {
				text-align: center;
				background: #f7f7f7;
				color: #666;
				border-bottom: 1px solid #CCC;
				border-left: 1px solid #fff;
			}

			table.adminlist thead th.left {
				text-align: left;
			}

			table.adminlist thead a:hover {
				text-decoration: none;
			}

			table.adminlist thead th img {
				vertical-align: middle;
				padding-left: 3px;
			}

			table.adminlist tbody th {
				font-weight: bold;
			}

			table.adminlist tbody tr {
				background-color: #fff;
				text-align: left;
			}

			table.adminlist tbody tr.row0:hover td,
			table.adminlist tbody tr.row1:hover td	{
				background-color: #e8f6fe;
			}

			table.adminlist tbody tr td {
				background: #fff;
				border: 1px solid #fff;
			}

			table.adminlist tbody tr.row1 td {
				background: #f0f0f0;
				border-top: 1px solid #FFF;
			}

			table.adminlist tfoot tr {
				text-align: center;
				color: #333;
			}

			table.adminlist tfoot td,table.adminlist tfoot th {
				background-color: #f7f7f7;
				border-top: 1px solid #999;
				text-align: center;
			}

			table.adminlist td.order {
				text-align: center;
				white-space: nowrap;
				width: 200px;
			}

			table.adminlist td.order span {
				float: left;
				width: 20px;
				text-align: center;
				background-repeat: no-repeat;
				height: 13px;
			}

			table.adminlist .pagination {
				display: inline-block;
				padding: 0;
				margin: 0 auto;
			}
		</style>
		<div style="float: left;">
			<a href="http://www.apptha.com/category/extension/Joomla/HD-Video-Share" target="_blank">
				<img src="components/com_contushdvideoshare/assets/contushdvideoshare-logo.png"
					 alt="Joomla! HDVideoShare" align="left" />
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
					</td>
				</tr>
				<tr class="row1">
					<td class="key" colspan="2"><?php echo 'HD Video Share Categories - ' . JText::_('Module'); ?></td>
					<td style="text-align: center;">
						<?php
						// Check installed modules
						$query->clear()
								->select('extension_id')
								->from($db->quoteName('#__extensions'))
								->where($db->quoteName('type') . ' = ' . $db->quote('module'))
								->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharecategories'));

						$db->setQuery($query);
						$category_id = $db->loadResult();

						if ($category_id)
						{
							echo "<strong>" . JText::_('Installed successfully') . "</strong>";
						}
						else
						{
							echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
						}
						?>
					</td>
				</tr>

				<tr class="row0">
					<td class="key" colspan="2"><?php echo 'HD Video Share Modules - ' . JText::_('Module'); ?></td>
					<td style="text-align: center;">
						<?php
						// Check installed modules
						$query->clear()
								->select('extension_id')
								->from($db->quoteName('#__extensions'))
								->where($db->quoteName('type') . ' = ' . $db->quote('module'))
								->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharemodules'));

						$db->setQuery($query);
						$featured_id = $db->loadResult();

						if ($featured_id)
						{
							echo "<strong>" . JText::_('Installed successfully') . "</strong>";
						}
						else
						{
							echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
						}
						?>
					</td>
				</tr>
								
				<tr class="row0">
					<td class="key" colspan="2"><?php echo 'HD Video Share Search - ' . JText::_('Module'); ?></td>
					<td style="text-align: center;">
						<?php
						// Check installed modules
						$query->clear()
								->select('extension_id')
								->from($db->quoteName('#__extensions'))
								->where($db->quoteName('type') . ' = ' . $db->quote('module'))
								->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharesearch'));

						$db->setQuery($query);
						$search_id = $db->loadResult();

						if ($search_id)
						{
							echo "<strong>" . JText::_('Installed successfully') . "</strong>";
						}
						else
						{
							echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
						}
						?>
					</td>
				</tr>
				<tr class="row0">
					<td class="key" colspan="2"><?php echo 'HD Video Share Player - ' . JText::_('Module'); ?></td>
					<td style="text-align: center;">
						<?php
						// Check installed modules
						$query->clear()
								->select('extension_id')
								->from($db->quoteName('#__extensions'))
								->where($db->quoteName('type') . ' = ' . $db->quote('module'))
								->where($db->quoteName('element') . ' = ' . $db->quote('mod_videoshare'));

						$db->setQuery($query);
						$search_id = $db->loadResult();

						if ($search_id)
						{
							echo "<strong>" . JText::_('Installed successfully') . "</strong>";
						}
						else
						{
							echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
						}
						?>
					</td>
				</tr>
				<tr class="row0">
					<td class="key" colspan="2"><?php echo 'HD Video Share RSS - ' . JText::_('Module'); ?></td>
					<td style="text-align: center;">
						<?php
						// Check installed modules
						$query->clear()
								->select('extension_id')
								->from($db->quoteName('#__extensions'))
								->where($db->quoteName('type') . ' = ' . $db->quote('module'))
								->where($db->quoteName('element') . ' = ' . $db->quote('mod_hdvideosharerss'));

						$db->setQuery($query);
						$search_id = $db->loadResult();

						if ($search_id)
						{
							echo "<strong>" . JText::_('Installed successfully') . "</strong>";
						}
						else
						{
							echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
						}
						?>
					</td>
				</tr>
				<tr class="row0">
					<td class="key" colspan="2"><?php echo 'HVS Article Plugin - ' . JText::_('Plugin'); ?></td>
					<td style="text-align: center;">
						<?php
						// Check installed modules
						$query->clear()
								->select('extension_id')
								->from($db->quoteName('#__extensions'))
								->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
								->where($db->quoteName('element') . ' = ' . $db->quote('hvsarticle'))
								->where($db->quoteName('folder') . ' = ' . $db->quote('content'));

						$db->setQuery($query);
						$article_id = $db->loadResult();

						if ($article_id) {
							echo "<strong>" . JText::_('Installed successfully') . "</strong>";
						} else {
							echo "<strong>" . JText::_('Not Installed successfully') . "</strong>";
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php 
		/**
		 * Creating channel directory within joomla images directory
		 */
	$this->createChannelDir();
	}
}
