<?php
/**
 * Sortorder file
 * 
 * This file is used to sort order the videos and categories
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

/**
 * Admin sortorder controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllersortorder extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllersortorder object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {
  $view = $this->getView ( 'sortorder' );
  
  /** Get/Create the model to perform sortorder */
  if ($model = $this->getModel ( 'sortorder' )) {
   $view->setModel ( $model, true );
  }
  /** Set layout for sortorder */
  $view->setLayout ( 'sortorderlayout' );
  /** Get task from params */
  $task = JRequest::getVar ( 'task', 'get', '', 'string' );
  
  switch ($task) {
    case 'videos':
      /** Check whether the video sort order is performed */
      $view->videosortorder ();
      break;
    case 'category':
      /** Check whether the category sort order is performed */
      $view->categorysortorder ();
      break;
    default:
      break;
  }
 }
}
