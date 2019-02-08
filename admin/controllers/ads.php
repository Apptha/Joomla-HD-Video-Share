<?php
/**
 * Ads controller
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

/** Define constants for ads controller */
define('SHOWADS','showads');

/**
 * Admin ads controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllerads extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllerads object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {
  /** Get view for showads */
  $view = $this->getView ( SHOWADS );
  
  /** Get model for showads */
  if ($model = $this->getModel ( SHOWADS )) {
   $view->setModel ( $model, true );
  }
  
  /** Set view for showads */
  $view->setLayout ( 'showadslayout' );
  $view->showads ();
 }
 
 /**
  * Function to add ads
  *
  * @return addads
  */
 public function addads() {
  /** Get adsview for add action */
  $view = $this->getView ( 'ads' );
  
  /** Get/Create the model */
  if ($model = $this->getModel ( 'addads' )) {
   /** Push the model into the view (as default)
    * Second parameter indicates that it is the default model for the view */
   $view->setModel ( $model, true );
  }
  /** Set ads layout */
  $view->setLayout ( 'adslayout' );
  $view->ads ();
 }
 
 /**
  * Function to edit ads
  *
  * @return editads
  */
 public function editads() {
  /** Get adsview for edit action */
  $view = $this->getView ( 'ads' );
  
  /** Get/Create the model to edit ads */
  if ($model = $this->getModel ( 'editads' )) {
   /** Push the model into the view (as default)
    * Second parameter indicates that it is the default model for the view */
   $view->setModel ( $model, true );
  }
  /** Set adslayout for edit action */
  $view->setLayout ( 'adslayout' );
  $view->editads ();
 }
 
 /**
  * Function to save ads
  *
  * @return saveads
  */
 public function saveads() {
  /** Get/Create the model to save ads data */
  if ($model = $this->getModel ( SHOWADS )) {
   /** Push the model into the view (as default)
    * Second parameter indicates that it is the default model for the view */
   $model->saveads ( JRequest::getVar ( 'task' ) );
  }
 }
 
 /**
  * Function to apply ads
  *
  * @return applyads
  */
 public function applyads() {
  /** Get/Create the model to apply the ads data */
  if ($model = $this->getModel ( SHOWADS )) {
   /** Push the model into the view (as default)
    * Second parameter indicates that it is the default model for the view */
   $model->saveads ( JRequest::getVar ( 'task' ) );
  }
 }
 
 /**
  * Function to remove ads
  *
  * @return removeads
  */
 public function removeads() {
  if ($model = $this->getModel ( 'editads' )) {
   /** Push the model into the view (as default)
    * Second parameter indicates that it is the default model for the view */
   $model->removeads ();
  }
 }
 
 /**
  * Function to cancel ads
  *
  * @return CANCEL6
  */
 public function CANCEL6() {
  /** Get showads view for cancel action */
  $view = $this->getView ( SHOWADS );
  
  /** Get/Create the model to perform cancel */
  if ($model = $this->getModel ( SHOWADS )) {
   $view->setModel ( $model, true );
  }
  
  /** Set showads view for cancel action */
  $view->setLayout ( 'showadslayout' );
  $view->showads ();
 }
 
 /**
  * Function to publish ads
  *
  * @return publish
  */
 public function publish() {
  /** Get ads details from post method for publish action*/ 
  $adsdetail = JRequest::get ( 'POST' );
  /** Get showads model for publish action */
  $model = $this->getModel ( SHOWADS );
  /** Call function to perform ads publish action*/
  $model->statusChange ( $adsdetail );
 }
 
 /**
  * Function to unpublish ads
  *
  * @return unpublish
  */
 public function unpublish() {
  /** Get ads details from post method for unpublish action */
  $adsdetail = JRequest::get ( 'POST' );
  /** Get showads model for unpublish action */
  $model = $this->getModel ( SHOWADS );
  /** Call function to perform ads unpublish action */
  $model->statusChange ( $adsdetail );
 }
 
 /**
  * Function to trash ads
  *
  * @return trash
  */
 public function trash() {
  /** Get ads details from post method for trash action */
  $adsdetail = JRequest::get ( 'POST' );
  /** Get showads model for trash action */
  $model = $this->getModel ( SHOWADS );
  /** Call function to perform ads trash action */
  $model->statusChange ( $adsdetail );
 }
}
