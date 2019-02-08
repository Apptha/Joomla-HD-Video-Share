<?php
/**
 * Recent videos view file
 *
 * This file is to display Recent videos detail
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

/** No direct access to this file  */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Recent Videos Module View file
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewrecentvideos extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewrecentvideos object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    $model = $this->getModel ();
    
    /** Get recent video details */
    $recentvideos = $model->getrecentvideos ();
    /** Assign reference for recent video details */
    $this->assignRef ( 'recentvideos', $recentvideos );
    
    /** Function call to fetch Itemid for recent videos view */
    $Itemid = getmenuitemid_thumb ( 'recentvideos', '' );
    /** Assign reference to itemid for recent video */
    $this->assignRef ( 'Itemid', $Itemid );
    
    /** Function call for fetching recent videos settings */
    $recentvideosrowcol = getpagerowcol ();
    /** Assign reference to recent video settings */
    $this->assignRef ( 'recentvideosrowcol', $recentvideosrowcol );
    parent::display ();
  }
}
