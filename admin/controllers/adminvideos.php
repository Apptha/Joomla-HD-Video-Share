<?php
/**
 * Admin videos controller
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 * @Creation Date   March 2010
 * @Modified Date   September 2015
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla controller library */
jimport ( 'joomla.application.component.controller' );

/** Define constants for adminvideos controller */
define('SHOWVIDEOS','showvideos');

/**
 * Admin videos controller class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareControlleradminvideos extends ContusvideoshareController {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareControlleradminvideos object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get view for showvideos */
    $view = $this->getView ( SHOWVIDEOS );
    
    /** Get model for showvideos */
    if ($model = $this->getModel ( SHOWVIDEOS )) {
      $view->setModel ( $model, true );
    }
    
    /** Set view for showvideos */
    $view->setLayout ( 'showvideoslayout' );
    $view->showvideos ();
  }
  
  /**
   * Function to set layout and model for add action
   *
   * @return addvideos
   */
  public function addvideos() {
    /** Get view for adminvideos */
    $view = $this->getView ( 'adminvideos' );
    
    /** Get model for addvideos */
    if ($model = $this->getModel ( 'addvideos' )) {
      $view->setModel ( $model, true );
    }
    
    /** Set layout for adminvideos */
    $view->setLayout ( 'adminvideoslayout' );
    $view->adminvideos ();
  }
  
  /**
   * Function to set layout and model for edit action
   *
   * @return editvideos
   */
  public function editvideos() {
    /** Get view for adminvideos in edit section */
    $view = $this->getView ( 'adminvideos' );
    
    /** Get model for adminvideos in edit mode */
    if ($model = $this->getModel ( 'editvideos' )) {
      $view->setModel ( $model, true );
    }
    
    /** Set view for adminvideos edit section*/
    $view->setLayout ( 'adminvideoslayout' );
    $view->editvideos ();
  }
  
  /**
   * Function to set model for save action
   *
   * @return savevideos
   */
  public function savevideos() {
    /** Get showvideos model to save videos */
    if ($model = $this->getModel ( SHOWVIDEOS )) {
      $model->savevideos ( JRequest::getVar ( 'task' ) );
    }
  }
  
  /**
   * Function to set model for apply action
   *
   * @return applyvideos
   */
  public function applyvideos() {
    /** Get showvideos model to save videos for apply button */
    if ($model = $this->getModel ( SHOWVIDEOS )) {
      $model->savevideos ( JRequest::getVar ( 'task' ) );
    }
  }
  
  /**
   * Function to set model for remove action
   *
   * @return removevideos
   */
  public function removevideos() {
    /** Get editvideos model for delete videos */
    if ($model = $this->getModel ( 'editvideos' )) {
      $model->removevideos ();
    }
  }
  
  /**
   * Function to set layout for cancel action
   *
   * @return CANCEL7
   */
  public function CANCEL7() {
    /** Get showvideos view for cancel action */
    $view = $this->getView ( SHOWVIDEOS );
    
    /** Get showvideos model for cancel action */
    if ($model = $this->getModel ( SHOWVIDEOS )) {
      $view->setModel ( $model, true );
    }
    
    /** Set showvideos view for cancel action */
    $view->setLayout ( 'showvideoslayout' );
    $view->showvideos ();
  }
  
  /**
   * Function to set redirect for comment page cancel action
   *
   * @return Commentcancel
   */
  public function Commentcancel() {
    /** Get option and user for comment section */
    $option = JRequest::getCmd ( 'option' );
    $user = JRequest::getCmd ( 'user' );
    /** Get user page url to redirect page */
    $userUrl = ($user == 'admin') ? "&user=$user" : "";
    $redirectUrl = 'index.php?option=' . $option . '&layout=adminvideos' . $userUrl;
    $this->setRedirect ( $redirectUrl );
  }
  
  /**
   * Function to make videos as featured
   *
   * @return featured
   */
  public function featured() {
    /** Get details from post method */
    $detail = JRequest::get ( 'POST' );
    /** Get model for showvideos to perform feature action */
    $model = $this->getModel ( SHOWVIDEOS );
    /** Call function to make video as a featured */ 
    $model->featuredvideo ( $detail );
  }
  
  /**
   * Function to make videos as unfeatured
   *
   * @return unfeatured
   */
  public function unfeatured() {
    /** Call function to make video as a unfeatured */
    $this->featured ();
  }
  
  /**
   * Function to publish videos
   *
   * @return publish
   */
  public function publish() {
    /** Get details from post method for publish action*/
    $detail = JRequest::get ( 'POST' );
    /** Get showvideos model for publish action */ 
    $model = $this->getModel ( SHOWVIDEOS );
    /** Call function to publish videos */
    $model->changevideostatus ( $detail );
  }
  
  /**
   * Function to unpublish videos
   *
   * @return unpublish
   */
  public function unpublish() {
    /** Get details from post method for unpublish action*/
    $detail = JRequest::get ( 'POST' );
    /** Get showvideos model for unpublish action */
    $model = $this->getModel ( SHOWVIDEOS );
    /** Call function to unpublish videos */
    $model->changevideostatus ( $detail );
  }
  
  /**
   * Function to upload file processing
   *
   * @return uploadfile
   */
  public function uploadfile() {
    /** Get model for uploadvideo */
    $model = $this->getModel ( 'uploadvideo' );
    /** Call function to upload new file */
    $model->fileupload ();
  }
  
  /**
   * Function to trash videos
   *
   * @return trash
   */
  public function trash() {
    /** Get details from post method for trash action*/
    $detail = JRequest::get ( 'POST' );
    /** Get showvideos model for trash action */
    $model = $this->getModel ( SHOWVIDEOS );
    /** Call function to trash videos */
    $model->changevideostatus ( $detail );
  }
  
  /**
   * Function to get youtube videos detail
   *
   * @return youtubeurl
   */
  public function youtubeurl() {
    /** Call function to fetch youtube details */
    fetchYouTubeDetails ();
  }  
}
