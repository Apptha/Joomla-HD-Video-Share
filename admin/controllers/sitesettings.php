<?php
/**
 * Site settings controller  file
 * 
 * This file is the site settings controller file 
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

/** Import Joomla controller library */
jimport ( 'joomla.application.component.controller' );

/** Define constant for site settings controller */
define ('SITESETTINGS', 'sitesettings');
/**
 * Admin site settings controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllersitesettings extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllersitesettings object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {
  /** Get viewname for sitesettings page */
  $viewNam = JRequest::getVar ( 'view', SITESETTINGS );
  /** Get view layout for sitesettings page */
  $viewLayout = JRequest::getVar ( 'layout', SITESETTINGS );
  /** Get viewname for sitesettings page */
  $view = $this->getView ( $viewNam );
  /** Get model for sitesettings page */
  if ($model = $this->getModel ( SITESETTINGS )) {
   $view->setModel ( $model, true );
  }
  /** set view layout for sitesettings page */
  $view->setLayout ( $viewLayout );
  $view->display ();
 }
 
 /**
  * Function to edit site settings
  *
  * @return edit
  */
 public function edit() {
  /** Call function to edit detils */ 
  $this->display ();
 }
 
 /**
  * Function to save site settings
  *
  * @return apply
  */
 public function apply() {
  /** Get site settings detail for apply action */ 
  $arrFormData = JRequest::get ( 'POST' );
  /** Get site settings model for apply action */
  $model = $this->getModel ( SITESETTINGS );
  /** Call funtion to save site settings detail*/
  $model->savesitesettings ( $arrFormData );
 }
}
