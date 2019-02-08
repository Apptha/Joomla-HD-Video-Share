<?php
/**
 * Controlpanel controller
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
define ('CONTROLPANEL','controlpanel');

/**
 * Admin controlpanel controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllercontrolpanel extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllercontrolpanel object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {
  /** Get viewname for controlpanel */
  $viewTitle = JRequest::getVar ( 'view', CONTROLPANEL );
  /** Get view layout for controlpanel */
  $viewLayout = JRequest::getVar ( 'layout', CONTROLPANEL );
  /** Get view for controlpanel */
  $view = $this->getView ( $viewTitle );
  /** Get model for controlpanel */
  if ($model = $this->getModel ( CONTROLPANEL )) {
   $view->setModel ( $model, true );
  }
  /** Set view layout for controlpanel */
  $view->setLayout ( $viewLayout );
  $view->display ();
 }
}
