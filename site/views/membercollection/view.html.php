<?php
/**
 * View file to display member videos
 *
 * This file is to display member videos
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
 * Membercollection view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewmembercollection extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewmembercollection object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get model for member collection view */
    $model = $this->getModel ();
    
    /** Function call for fetching member videos */
    $membercollection = $model->getmembercollection ();
    /** Assgin reference for member collection data */ 
    $this->assignRef ( 'membercollection', $membercollection );
    
    /** Function call for fetching membercollection settings */
    $memberpagerowcol = getpagerowcol ();
    
    /** Assign reference for member collection settings data */ 
    $this->assignRef ( 'memberpagerowcol', $memberpagerowcol );
    
    /** Function call for fetching Itemid for member collection*/
    $Itemid = getmenuitemid_thumb ( 'player', '');    
    /** Assign reference to itemid for member collection view */
    $this->assignRef ( 'Itemid', $Itemid );
    parent::display ();
  }
}
