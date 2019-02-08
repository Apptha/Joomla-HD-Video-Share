<?php
/**
 * google adsense template file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');
/** Include admin helper file */
require_once JPATH_COMPONENT . '/helpers/contushdvideoshare.php';

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Admin google ad view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewgooglead extends ContushdvideoshareView {    
  /**
   * Function to view for manage categories
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewgooglead object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Add css for google adsense  view */ 
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    /** Add toolbras for adsense page */
    ContushdvideoshareHelper::addToolbar ('Google Adsense','googlead');
    /** Get google adsense model */
    $model = $this->getModel ();
    /** Get google adsense details to display */
    $googlead = $model->getgooglead ();
    /** Assign reference to google adsense detail */
    $this->assignRef ( 'googlead', $googlead );
    parent::display ();
  }
}
