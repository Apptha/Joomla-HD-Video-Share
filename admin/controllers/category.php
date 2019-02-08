<?php
/**
 * Category controller
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

/** Define constants for category controller */ 
$catLayout = 'index.php?option=' . JRequest::getVar ( 'option' ) . '&layout=category';
define('CATEGORY_LAYOUT', $catLayout );

/**
 * Admin category controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllercategory extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllercategory object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {
  /** Get view name for category page */
  $viewName = JRequest::getVar ( 'view', CATEGORY );  
  /** Get view for category page */
  $view = $this->getView ( $viewName );
  /** Get view layout for category page */
  $viewLayout = JRequest::getVar ( 'layout', CATEGORY );
  /** Get model for category page */
  if ($model = $this->getModel ( CATEGORY )) {
   $view->setModel ( $model, true );
  }
  /** Set view layout for category page */
  $view->setLayout ( $viewLayout );
  $view->display ();
 }
 
 /**
  * Function to save category
  *
  * @return save
  */
 public function save() {
  /** Get category details from post method */ 
  $detail = JRequest::get ( 'POST' );
  /** Get category model to save categories */
  $model = $this->getModel ( CATEGORY );
  /** Call function to save categories */
  $model->savecategory ( $detail );
  /** SEt redirect link after category save action */
  $this->setRedirect ( CATEGORY_LAYOUT, SAVE_SUCCESS );
 }
 
 /**
  * Function to remove category
  *
  * @return remove
  */
 public function remove() {
  /** Get cat id's to perform delete action */
  $arrayIDs = JRequest::getVar ( 'cid', null, 'default', 'array' );
  
  /** Reads cid as an array */
  if ($arrayIDs [0] === null) {
   /** Make sure the cid parameter was in the request */
   JError::raiseWarning ( 500, 'Category missing from the request' );
  }
  /** Get cat model to perform delete action */
  $model = $this->getModel ( CATEGORY );
  /** Call function to delete categories */
  $model->deletecategary ( $arrayIDs );
  /** Set redirect link after delete action */ 
  $this->setRedirect ( CATEGORY_LAYOUT, SAVE_SUCCESS );
 }
 
 /**
  * Function to cancel category
  *
  * @return cancel
  */
 public function cancel() {
  /** Redirect to category grid section for cancel action */ 
  $this->setRedirect ( CATEGORY_LAYOUT );
 }
 
 /**
  * Function to publish category
  *
  * @return publish
  */
 public function publish() {
  /** Get category details to publish */
  $detail = JRequest::get ( 'POST' );
  /** Get category model to publish */
  $model = $this->getModel ( CATEGORY );
  /** Call function to publish categories */
  $model->changeStatus ( $detail );
  $this->setRedirect ( CATEGORY_LAYOUT );
 }
 
 /**
  * Function to unpublish category
  *
  * @return unpublish
  */
 public function unpublish() {
   /** Get category details to unpublish */
  $detail = JRequest::get ( 'POST' );
  /** Get category model to unpublish */
  $model = $this->getModel ( CATEGORY );
  /** Call function to unpublish categories */
  $model->changeStatus ( $detail );
  $this->setRedirect ( CATEGORY_LAYOUT );
 }
 
 /**
  * Function to save category
  *
  * @return apply
  */
 public function apply() {
  /** Get category details to perform apply action */
  $detail = JRequest::get ( 'POST' );
  /** Get model to perform apply action */
  $model = $this->getModel ( CATEGORY );
  /** Call function to save category while apply action */
  $model->savecategory ( $detail );
  $link = CATEGORY_LAYOUT . '&task=edit&cid[]=' . $detail ['id'];
  $this->setRedirect ( $link, SAVE_SUCCESS );
 }
 
 /**
  * Function to trash category
  *
  * @return trash
  */
 public function trash() {
   /** Get category details to perform trash action */
  $detail = JRequest::get ( 'POST' );
  /** Get model to perform trash action */
  $model = $this->getModel ( CATEGORY );
  /** Call function to trash category */
  $model->changeStatus ( $detail );
  $this->setRedirect ( CATEGORY_LAYOUT );
 }
}
