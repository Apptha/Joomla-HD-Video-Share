<?php
/**
 * Google adsense controller
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
define ('GOOGLEAD','googlead');
/**
 * Admin googlead controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllergooglead extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllergooglead object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {
  /** Get viewname for google ads */
  $viewName = JRequest::getVar ( 'view', GOOGLEAD );
  
  /** Get view for google ads page */
  $view = $this->getView ( $viewName );
  /** Get model for google adsense */
  if ($model = $this->getModel ( GOOGLEAD )) {
   $view->setModel ( $model, true );
  }
  /** Get layout for google ads */
  $viewLayout = JRequest::getVar ( 'layout', GOOGLEAD );
  /** Set layout for google ads */
  $view->setLayout ( $viewLayout );
  $view->display ();
 }
 
 /**
  * Function to apply googlead
  *
  * @return apply
  */
 public function apply() {
  /** Get google ads model to perform apply action */ 
  $model = $this->getModel ( GOOGLEAD );
  /** Call function to save google adsense details */
  $model->savegooglead ();
 }
}
