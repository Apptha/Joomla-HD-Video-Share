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
jimport ( 'joomla.application.component.view' );

/**
 * Myplaylists view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewmyplaylists extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewmyplaylists object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Getting the delete video id */
    $request_delete = JRequest::getVar ( 'delete_id' ); 
    $model = $this->getModel ();
    /** Assigning reference for model */
    $this->assignRef ( 'model', $model );
    
    $itemId = getmenuitemid_thumb ('player', '');
    /** Assigning reference for video id */
    $this->assignRef ( 'itemId', $itemId );
    
    /** Perform delete video from playlist */
    if ($request_delete) {
      $model->delete_userplayList ( $request_delete );
    }
    
    /** Function call for fetching member videos */
    $siteSetting = getSiteSettings();

    /** Assigning reference for the site settings */
    $this->assignRef ( 'siteSetting', $siteSetting );
    
    $playlists = $model->getmemberplaylists ();    
    /** Assigning reference for the user playlists */
    $this->assignRef ( 'myplaylists', $playlists );
        
    /** Assign Referense varible for parent category select */
    parent::display ();
  }
}
