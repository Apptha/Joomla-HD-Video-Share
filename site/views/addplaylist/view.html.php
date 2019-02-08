<?php
/**
 * User video view file
 *
 * This file is to display logged in user videos
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
/** Import Joomla view library */
jimport('joomla.application.component.view');

/**
 * Myvideos view class.
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class ContushdvideoshareViewaddplaylist extends ContushdvideoshareView {
	/**
	 * Function to set layout and model for view page.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   boolean  $urlparams  An array of safe url parameters and their variable types
	 *
	 * @return  ContushdvideoshareViewmyplaylists		This object to support chaining.
	 * 
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false) {
			$model = $this->getModel();
			/** Function call for fetching member videos */
			$playlistID =  JRequest::getVar('playlist_id');
			$app = JFactory::getApplication();
			/** Function call for add button*/
			$playlistAddButton  = JRequest::getVar('playlist_addbutton');
			/** Get all category list */
			$get_all_category =  $model->get_all_parentcategory();
			/** Assigning reference variable for category list */
			$this->assignRef('parentCategory',$get_all_category);
			if( $playlistID) {
				/** Get detail for each video category */
				$playlistDetail = $model->getPlaylistDetail($playlistID);
				/** Assigning reference variable for particular category */
				$this->assignRef('playlistdetails', $playlistDetail );
			}
			/** Get site settings */
			$siteSetting  =  getSiteSettings ();
			/** Get detail of all playlists */
			$playlistCount =  $model->get_total_playlist();
			/**  login user total  playlist  value for  template file */
			$this->assignRef('totalPlaylist' ,$playlistCount);  
			/** function  assign  varible to  site setting */
			$this->assignRef('siteSetting',$siteSetting);
			if( $playlistAddButton ) {
				
				if( $model->updateDetails() ) {
                    if( $playlistID ) { 
					    $app->redirect(  JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists" ) ,'Updated Successfully');
                    } else {
                    	$app->redirect(  JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists" ) ,'Added Successfully');
                    }
				} else {
					if( $playlistID ) {
					    $app->redirect(  JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists" ) ,'Not Updated Successfully');
					} else {
						$app->redirect(  JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists" ) ,'Not Added Successfully');
					}
				}
			}
			$itemId =  getmenuitemid_thumb ('player', '');
			/** Assigning id to video for details */ 
			$this->assignRef('itemId' , $itemId);
			parent::display();
	}
}
