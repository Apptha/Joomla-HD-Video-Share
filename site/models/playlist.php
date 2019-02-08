<?php
/**
 * Playlist model for HD Video Share
 *
 * This file is to fetch playlist details from database for playlist view 
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
jimport('joomla.application.component.model');

/**
 * Category videos model class
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Modelcontushdvideoshareplaylist extends ContushdvideoshareModel {
	/**
	 * Function to display the video results of related category
	 * 
	 * @return  array
	 */
	public function getcategory() {
		global $contusDB, $contusQuery, $appObj;
		$userId = (int) getUserID(); 
		/** Get the playlist id, check is that numeric */
		$flatCatid = is_numeric(JRequest::getString(PLAYLIST)); 
		/** Check delete id is in post */
		if ( JRequest::getInt ('deletevideo') && JRequest::getInt ('deletecat')) {
			/** Getting the video id, category id which is going to be deleted */
			$vid = JRequest::getInt ('deletevideo'); 
			$cid = JRequest::getInt ('deletecat'); 
			/**  Query for deleting a selected video */
		    $contusQuery->clear()->delete( $contusDB->quoteName( VIDEOPLAYLISTTABLE ))->where( $contusDB->quoteName('vid').'='.$vid.' AND '.$contusDB->quoteName('catid').'='.$cid );
			$contusDB->setQuery($contusQuery);
			$contusDB->query();
			/**  Function to get site settings, unserialize settings data */
			$dispenable =  getSiteSettings() ;
			/**  Check SEO options settings enabled */
			if ($dispenable['seo_option'] == 1) {
				/** Create playlist id */
				$playlistValue = "playlist=" . $cid;
			}else {
				/**  Check SEO options settings disabled */
				/**  Assign playlist category id as playlist id */
				$playlistValue = "playid=" . $cid;
			}
			/**  Ger redirect URI */
			$url =  JRoute::_("index.php?option=com_contushdvideoshare&view=playlist&" .$playlistValue );
			/**  Check SEO options settings disabled */
			$appObj->redirect($url, JText::_('HDVS_DELETE_SUCCESS'), 'message');
		}
		/**  Playlist details available in query string */
		if (JRequest::getString(PLAYLIST) && $flatCatid != 1) {
			/** Replacing the format of querystring */
			$catvalue = str_replace(':', '-', JRequest::getString(PLAYLIST));
			/** Select category values */
			$contusQuery->clear()->select('id')->from( PLAYLISTTABLE )->where($contusDB->quoteName('seo_category') . ' = ' . $contusDB->quote($catvalue));
			$contusDB->setQuery($contusQuery);
			$categoryid = $contusDB->loadResult();
		} elseif ($flatCatid == 1) {
			/**  If playlist id is not an numeric value 1 */
			/** get playlist id as category id  */
			$categoryid = JRequest::getString(PLAYLIST);
		} elseif (JRequest::getInt('playid')) {
			/**  If playlist id is not available then take playid */
			/** get video id as category id  */
			$categoryid = JRequest::getInt('playid');
		} else {
			/**  This query is for category view pagination */
			$contusQuery->clear()->select('id')->from( PLAYLISTTABLE )->where($contusDB->quoteName('published') . ' = ' . $contusDB->quote('1'))->order($contusDB->escape('category ASC'));
			$contusDB->setQuery($contusQuery);
			$searchtotal1 = $contusDB->loadObjectList();
			/** Category id is stored in this catid variable */
			$categoryid = $searchtotal1[0]->id;
		}
		/** Check joomla version */
		if (!version_compare(JVERSION, '3.0.0', 'ge')) {
			/** For version 3.0 */
			$categoryid = $contusDB->getEscaped($categoryid);
		}
		$options=array('a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', TIMESVIEWED, 'a.ratecount', 
		  'a.rate', 'a.streameroption', 'a.streamerpath', 'a.videourl', PLAYLISTID,'a.amazons3', 'a.seotitle', 'a.embedcode');
		$options1 = array_merge( $options , array('e.*'));
		/** call to method to get row and column for the playlist  */
		$limitrow = getpagerowcol();
		/**  This query for displaying category's full view display */
		 $contusQuery->clear()->select( array_merge($options, array('b.category', 'b.seo_category', 'b.parent_id', 'd.username', 'e.catid', CATVIDEOID, 'wl.video_id', 'wh.VideoId')) ) ->from(PLAYERTABLE . VIDEOTABLECONSTANT)
		->leftJoin(USERSTABLE .  VIDEOMEMBERLEFTJOIN)->leftJoin(VIDEOPLAYLISTTABLE . VIDEOCATELEFTJOIN)->leftJoin(PLAYLISTTABLE . ' AS b ON e.catid=b.id')
		->leftJoin(CATEGORYTABLE . ' AS g ON g.id=a.playlistid')->leftJoin ( WATCHLATERTABLE.' AS wl ON a.id=wl.video_id AND wl.user_id='.$userId )
		->leftJoin ( WATCHHISTORYTABLE.' AS wh ON a.id=wh.VideoId AND wh.userId='.$userId )
		->where('(' . $contusDB->quoteName('e.catid') . ' = ' . $contusDB->quote($categoryid) . ' OR ' . $contusDB->quoteName('b.parent_id') . ' = ' . $contusDB->quote($categoryid)
		. ' OR ' . $contusDB->quoteName(PLAYLISTID) . ' = ' . $contusDB->quote($categoryid). ')')->where($contusDB->quoteName(VIDEOPUBLISH) . ' = ' . $contusDB->quote('1'))
		->where($contusDB->quoteName(CATPUBLISH) . ' = ' . $contusDB->quote('1'))->where($contusDB->quoteName(USERBLOCK) . ' = ' . $contusDB->quote('0'))
		->where($contusDB->quoteName('g.published') . ' = ' . $contusDB->quote('1'))->group($contusDB->escape(CATVIDEOID))->order($contusDB->escape('a.id' . ' ' . 'DESC'));
		$contusDB->setQuery($contusQuery);
		$searchtotal = $contusDB->loadObjectList();
			/** Get count of playlist  */
			$total = count($searchtotal);
			/** Set initial page no as 1  */
			$pageno = 1;
			if (JRequest::getInt ('video_pageid')) {
				/** Get page number for pagination  */
				$pageno = JRequest::getInt ('video_pageid');
			}
			/** Limit Play list in pagination  */
			$thumbview = unserialize($limitrow[0]->thumbview);
			/** get the length */
			$length = $thumbview['playlistrow'] * $thumbview['playlistcol'];
			/** Set page number */
			$pages = ceil($total / $length);
			/** If page no is 1 */
			if ($pageno == 1) {
				/** Set start value as 0 */
				$start = 0;
			}else {
				/** If page no is morethan 1 , Set next page no */
				$start = ( $pageno - 1) * $length;
			}
		$contusDB->setQuery($contusQuery, $start, $length);
		$resultrows = $contusDB->LoadObjectList();
		/** This query is to get videos for player in category page */
		 $contusQuery->clear()->select( $options1 )->from(PLAYERTABLE . VIDEOTABLECONSTANT)->leftJoin(USERSTABLE . VIDEOMEMBERLEFTJOIN)
		->leftJoin(VIDEOPLAYLISTTABLE . VIDEOCATELEFTJOIN)->leftJoin(PLAYLISTTABLE . ' AS b ON e.catid=b.id')
		->leftJoin(CATEGORYTABLE . ' AS g ON g.id=a.playlistid')->where('(' . $contusDB->quoteName(PLAYLISTID) . ' = ' . $contusDB->quote($categoryid) . ')')
		->where($contusDB->quoteName(VIDEOPUBLISH) . ' = ' . $contusDB->quote('1'))->where($contusDB->quoteName(CATPUBLISH) . ' = ' . $contusDB->quote('1'))
		->where($contusDB->quoteName(USERBLOCK) . ' = ' . $contusDB->quote('0'))->where($contusDB->quoteName('g.published') . ' = ' . $contusDB->quote('1'))
		->group($contusDB->escape(CATVIDEOID))->order($contusDB->escape('a.id' . ' ' . 'DESC'));
		$contusDB->setQuery($contusQuery, $start, $length);
		$videoForPlayer = $contusDB->LoadObjectList();
		/** If results is empty */
		if(empty($videoForPlayer)) {
			 $contusQuery->clear()->select($options1)->from(PLAYERTABLE . VIDEOTABLECONSTANT)->leftJoin(USERSTABLE . VIDEOMEMBERLEFTJOIN)->leftJoin(VIDEOPLAYLISTTABLE. VIDEOCATELEFTJOIN)
			->leftJoin(PLAYLISTTABLE . ' AS b ON e.catid=b.id')->leftJoin(CATEGORYTABLE . ' AS g ON g.id=a.playlistid')
			->where('('. $contusDB->quoteName('b.parent_id') . ' = ' . $contusDB->quote($categoryid). ')')->where($contusDB->quoteName(VIDEOPUBLISH) . ' = ' . $contusDB->quote('1'))
			->where($contusDB->quoteName(CATPUBLISH) . ' = ' . $contusDB->quote('1'))->where($contusDB->quoteName(USERBLOCK) . ' = ' . $contusDB->quote('0'))
			->where($contusDB->quoteName('g.published') . ' = ' . $contusDB->quote('1'))->group($contusDB->escape(CATVIDEOID))->order($contusDB->escape('a.id' . ' ' . 'DESC'));
			$contusDB->setQuery($contusQuery, $start, $length); 
			$videoForPlayer = $contusDB->LoadObjectList();
		}
		/** This query for displaying category's full view display */
		$contusQuery->clear()->select('category')->from(PLAYLISTTABLE)->where($contusDB->quoteName('id') . ' = ' . $contusDB->quote($categoryid));
		$contusDB->setQuery($contusQuery);
		$category = $contusDB->LoadObjectList();
		/** Below code is to merge the pagination values */		 
		if (count($resultrows) > 0) {		
			/** Merge results with category  */
			$merge_rows = array_merge($resultrows, array('categoryname' => $category));
			/** Merge results with page no  */
			$merge_pageno = array_merge($merge_rows, array('pageno' => $pageno));
			/** Merge results with pages  */
			$merge_pages = array_merge($merge_pageno, array('pages' => $pages));
			/** Merge results with start value  */
			$merge_start = array_merge($merge_pages, array('start' => $start));			
			/** Merge results with length array */
			$mergeLength = array_merge($merge_start, array('length' => $length));
			/** Merge results with player array  */
			$rows = array_merge($mergeLength, array('videoForPlayer' => $videoForPlayer));
		} else {
			/** This query for displaying category's full view display */
			$contusQuery->clear()->select('*')->from(PLAYLISTTABLE)->where($contusDB->quoteName('id') . ' = ' . $contusDB->quote($categoryid));
			$contusDB->setQuery($contusQuery);
			$rows = $contusDB->LoadObjectList();
		}
		/** Return results of category  */
		return $rows;
	}	
}
