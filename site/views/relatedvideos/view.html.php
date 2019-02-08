<?php
/**
 * Related videos view file
 *
 * This file is to display Related videos detail
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
 * Relatedvideos view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewrelatedvideos extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewrelatedvideos object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get model for related videos */
    $model = $this->getModel ();
    /** Get related video details */
    $relatedvideos = $model->getrelatedvideos ();
    /** Assign reference for related video details */
    $this->assignRef ( 'relatedvideos', $relatedvideos );
    /** Function call for fetching related videos settings */
    $relatedvideosrowcol = getpagerowcol ();
    
    /** Assign reference to popular video settings */
    $this->assignRef ( 'relatedvideosrowcol', $relatedvideosrowcol );
    
    /** Function call to fetch Itemid for related videos view */
    $Itemid = getmenuitemid_thumb ( 'player', '' );
    /** Assign reference to itemid for popular video */
    $this->assignRef ( 'Itemid', $Itemid );
    parent::display ();
  }
}
