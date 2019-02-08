<?php
/**
 * Search view for HD Video Share
 *
 * This file is to display videos based on search keyword 
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
 * HD Video Share Search view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewhdvideosharesearch extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewhdvideosharesearch object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get model for video search view */
    $model = $this->getModel ();
    
    /** Function call for fetching search results */
    $search = $model->getsearch ();
    /** Assign reference for search view */
    $this->assignRef ( 'search', $search );
    
    /** Function call for fetching my videos settings */
    $searchrowcol = getpagerowcol ();
    /** Assign reference for search view settings */
    $this->assignRef ( 'searchrowcol', $searchrowcol );
    
    /** Function call for fetching Itemid for search */
    $Itemid = getmenuitemid_thumb ('player', '' );    
    /** Assign refernce for search view item id */
    $this->assignRef ( 'Itemid', $Itemid );
    /**  Assigning the reference for the playlists */
    $playlists =  getuserplaylists();
    $this->assignRef('playlists', $playlists);
    
    parent::display ();
  }
}
