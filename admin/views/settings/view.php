<?php
/**
 * Player settings view file
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

/** Include admin helper files for settings page */
require_once JPATH_COMPONENT . '/helpers/contushdvideoshare.php';

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Admin settings view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewsettings extends ContushdvideoshareView {
  /**
   * Function to view for manage categories
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewsettings object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Add css for settings page */ 
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    /** Add toolbar for player settings page */
    ContushdvideoshareHelper::addToolbar ('Player Settings', 'settings' );
    $model = $this->getModel ();
    
    /** Function to get player settings */
    $playersettings = $model->showplayersettings ();
    /** Assign refernce to player settings */
    $this->assignRef ( 'playersettings', $playersettings );
    parent::display ();
  }
}
