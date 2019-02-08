<?php
/**
 * Site settings view file
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

/** Include admin helper for site settings page */
require_once JPATH_COMPONENT . '/helpers/contushdvideoshare.php';

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Site settings view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewsitesettings extends ContushdvideoshareView {
  /**
   * Function to prepare view for Site settings
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewsitesettings object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Check task is edit or empty */
    if (JRequest::getVar ( 'task' ) == 'edit' || JRequest::getVar ( 'task' ) == '') {
      /** Add css for site settings page */
      JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
      /** Add toolbars for site settings page */
      ContushdvideoshareHelper::addToolbar ('Site Settings', 'sitesettings');
      /** Get site settings model */
      $model = $this->getModel ();
      /** Call function to display site settings */
      $setting = $model->getsitesetting ();
      /** Assign sitesettings data to the view */
      $this->assignRef ( 'sitesettings', $setting [0] );
      /** Assign comment settings data to the view */
      $this->assignRef ( 'jomcomment', $setting [1] );
      $this->assignRef ( 'jcomment', $setting [2] );
      /** Display the view */
      parent::display ();
    }
  }
}
