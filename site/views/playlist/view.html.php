<?php
/**
 * Playlist videos view file
 *
 * This file is to display playlist videos
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
jimport ( 'joomla.application.component.view' );

/**
 * Playlist view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewplaylist extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewcategory object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    $model = $this->getModel ();
    
    /** Calling the function in models playlist.php */
    $getcategoryview = $model->getcategory ();
    $this->assignRef ( 'categoryview', $getcategoryview );
    
    /** Assigning reference for the playlist row/col settings */
    $categorrowcol = getpagerowcol ();
    $this->assignRef ( 'playlistrowcol', $categorrowcol );
    
    /** Assigning reference for the playlist settings */
    $getcategoryListVal = getcategoryList(PLAYLISTTABLE, VIDEOPLAYLISTTABLE, 'catid');
    $this->assignRef ( 'categoryList', $getcategoryListVal );
    
    /** Assigning reference for the playlist settings */
    $getplayersettings = getPlayerIconSettings ('both');
    $this->assignRef ( 'player_values', $getplayersettings );
    
    /** Assigning reference for the playlist video for the player */
    $getcategoryid = getcategoryid( JRequest::getString( PLAYLIST ), PLAYLISTTABLE, CATEGORY, JRequest::getInt ( PLAYID ) );
    $this->assignRef ( 'getcategoryid', $getcategoryid );
    
    /** Assigning reference for the playlist videos access level result */
    $homeAccessLevel = getHTMLVideoAccessLevel ( VIDEOPLAYLISTTABLE );
    $this->assignRef ( 'homepageaccess', $homeAccessLevel );
    
    /** Assigning reference for the Item id */
    $Itemid = getmenuitemid_thumb ('player', '');
    $this->assignRef ( 'Itemid', $Itemid );
    
    parent::display ();
  }
}
