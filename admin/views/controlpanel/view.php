<?php
/**
 * Controlpanel view file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */

/** No direct access to this file */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** Restricted Access */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Admin control panel view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewcontrolpanel extends ContushdvideoshareView {
  /**
   * Function to view for manage categories
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return contushdvideoshareViewcategory object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Check task is empty or edit */
    if (JRequest::getVar ( 'task' ) == 'edit' || JRequest::getVar ( 'task' ) == '') {
      /** Display title for control panel page */
      JToolBarHelper::title ( 'HD Video Share Control Panel', 'manege-pins.png' );
      /** Get controlpanel model */
      $model = $this->getModel ();
      /** Call function to display details in controlpanel */
      $controlpaneldetails = $model->controlpaneldetails ();
      /** Assign refernce to control panel details */
      $this->assignRef ( 'controlpaneldetails', $controlpaneldetails );
      parent::display ();
    }
  }
}
