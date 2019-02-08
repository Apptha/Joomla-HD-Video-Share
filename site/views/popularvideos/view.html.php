<?php
/**
 * Popular videos view file
 *
 * This file is to dispaly Popular videos
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
 * Popularvideos view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewpopularvideos extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewpopularvideos object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get model for popular videos */  
    $model = $this->getModel ();
    
    /** Get popular video details */
    $popularvideos = $model->getpopularvideos ();
    /** Assign reference for popular video details */
    $this->assignRef ( 'popularvideos', $popularvideos );
    
    /** Function call to fetch Itemid for popular videos view */
    $Itemid = getmenuitemid_thumb ('popularvideos', '');
    /** Assign reference to itemid for popular video */
    $this->assignRef ( 'Itemid', $Itemid );
    
    /** Function call for fetching popular videos settings */
    $popularvideosrowcol = getpagerowcol ();
    /** Assign reference to popular video settings */
    $this->assignRef ( 'popularvideosrowcol', $popularvideosrowcol );
    parent::display ();
  }
}
