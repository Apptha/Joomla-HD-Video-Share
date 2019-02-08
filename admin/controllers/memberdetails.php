<?php
/**
 * Member Details controller
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
define('MEMBERDETAILS', 'memberdetails');
$memberPage = 'index.php?layout=memberdetails&option=' . JRequest::getVar ( 'option' );
define ('MEMBER_PAGE', $memberPage);

/**
 * Admin memberdetails controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControllermemberdetails extends ContusvideoshareController {
 /**
  * Function to set layout and model for view page.
  *
  * @param boolean $cachable
  *         If true, the view output will be cached
  * @param boolean $urlparams
  *         An array of safe url parameters and their variable types
  *         
  * @return ContushdvideoshareControllermemberdetails object to support chaining.
  *        
  * @since 1.5
  */
 public function display($cachable = false, $urlparams = false) {  
  /** Get layout for member details page */
  $viewLayout = JRequest::getVar ( 'layout', MEMBERDETAILS );
  /** Get viewname for member details page */
  $viewName = JRequest::getVar ( 'view', MEMBERDETAILS );
  /** Get view for member details page */
  $view = $this->getView ( $viewName );
  /** Get model for member details page */
  if ($model = $this->getModel ( MEMBERDETAILS )) {
   $view->setModel ( $model, true );
  }
  /** Set layout for member details page */
  $view->setLayout ( $viewLayout );
  $view->display ();
 }
 
 /**
  * Function to publish memberdetails
  *
  * @return publish
  */
 public function publish() {
  /** Get details from post method to perform publish action */
  $detail = JRequest::get ( 'POST' );
  /** Get member details model for publish */
  $model = $this->getModel ( MEMBERDETAILS );
  /** Call function to publish for members */
  $model->memberActivation ( $detail );
  $this->setRedirect ( MEMBER_PAGE );
 }
 
 /**
  * Function to unpublish memberdetails
  *
  * @return unpublish
  */
 public function unpublish() {
  /** Get details from post method to perform unpublish action */
  $detail = JRequest::get ( 'POST' );
  /** Get member details model for unpublish */
  $model = $this->getModel ( MEMBERDETAILS );
  /** Call function to unpublish for members */
  $model->memberActivation ( $detail );
  $this->setRedirect ( MEMBER_PAGE );
 }
 
 /**
  * Function to allowupload memberdetails
  *
  * @return allowupload
  */
 public function allowupload() {
  /** Get details from post method to perform allow upload action */
  $detail = JRequest::get ( 'POST' );
  /** Get member details model for allow upload */
  $model = $this->getModel ( MEMBERDETAILS );
  /** Call function to allow upload option for members */
  $model->allowUpload ( $detail );
  $this->setRedirect ( MEMBER_PAGE );
 }
 
 /**
  * Function to unallowupload memberdetails
  *
  * @return unallowupload
  */
 public function unallowupload() {
  /** Get details from post method to perform restrict upload action */
  $detail = JRequest::get ( 'POST' );
  /** Get member details model for restricting upload option */
  $model = $this->getModel ( MEMBERDETAILS );
  /** Call function to restrict upload option for members */
  $model->allowUpload ( $detail );
  $this->setRedirect ( MEMBER_PAGE );
 }
}
