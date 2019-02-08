<?php
/**
 * Player settings controller
 * 
 * This file is used for player settings controller
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

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla controller library */
jimport ( 'joomla.application.component.controller' );

define ('SETTINGS', 'settings');
/**
 * Admin settings controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllersettings extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllersettings object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {
  /** Get viewname for settings */
  $viewName = JRequest::getVar ( 'view', SETTINGS );
  /** Get layout for settings page */
  $viewLayout = JRequest::getVar ( 'layout', SETTINGS );
  /** Get view for settings page */
  $view = $this->getView ( $viewName );
  /** Get model for settings page */
  if ($model = $this->getModel ( SETTINGS )) {
   $view->setModel ( $model, true );
  }
  /** Set layout for settings page */
  $view->setLayout ( $viewLayout );
  $view->display ();
 }
 
 /**
  * Function to save settings
  *
  * @return apply
  */
 public function apply() {
  /** Get settings model to perform apply action */ 
  $model = $this->getModel ( SETTINGS );
  /** Call funtion to save settings detail */
  $model->saveplayersettings ();
 }
}
