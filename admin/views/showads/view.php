<?php
/**
 * Show ads view file
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

/** Import joomla view library */
jimport ( 'joomla.application.component.view' );

/** Import Joomla pagination */
jimport ( 'joomla.html.pagination' );

/**
 * Show ads view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewshowads extends ContushdvideoshareView {
  protected $canDo;
  
  /**
   * Function to manage ads
   *
   * @return showads
   */
  public function showads() {
    /** Add dtyle sheet for ads page */
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    $this->addToolbar ();
    
    /** Get ads model object and display the layout */
    $model    = $this->getModel ();
    $showads  = $model->showadsmodel ();
    $this->assignRef ( 'showads', $showads );
    parent::display ();
  }
  
  /**
   * Function to set the toolbar
   *
   * @return addToolBar
   */
  protected function addToolBar() {
    JToolBarHelper::title ( JText::_ ( 'Video Ads' ), 'ads' );
    
    /** Check joomla version */
    if (version_compare ( JVERSION, '2.5.0', 'ge' ) || version_compare ( JVERSION, '1.6', 'ge' ) || version_compare ( JVERSION, '1.7', 'ge' ) || version_compare ( JVERSION, '3.0', 'ge' )) {
      
      /** Include helper file */
      require_once JPATH_COMPONENT . '/helpers/contushdvideoshare.php';
      
      /** What Access Permissions does this user have? What can (s)he do? */
      $this->canDo = ContushdvideoshareHelper::getActions ();
      
      if ($this->canDo->get ( 'core.create' )) {
        JToolBarHelper::addNew ( 'addads', 'New Ad' );
      }
      
      if ($this->canDo->get ( 'core.edit' )) {
        JToolBarHelper::editList ( 'editads', 'Edit' );
      }
      
      if ($this->canDo->get ( 'core.edit.state' )) {
        JToolBarHelper::publishList ();
        JToolBarHelper::unpublishList ();
      }
      
      if ($this->canDo->get ( 'core.admin' )) {
        JToolBarHelper::divider ();
        JToolBarHelper::preferences ( COMPONENT );
      }
      
      if ($this->canDo->get ( 'core.delete' ) && JRequest::getVar ( 'ads_status' ) == 3) {
          JToolBarHelper::deleteList ( '', 'removeads', 'JTOOLBAR_EMPTY_TRASH' );
      } else {
        if( $this->canDo->get ( 'core.delete' )) {
          JToolBarHelper::trash ( 'trash' );
        }
      }
      
    } else {
      /** Display add , edit and trash buttons in ads page */
      JToolBarHelper::addNew ( 'addads', 'New Ad' );
      JToolBarHelper::editList ( 'editads', 'Edit' );
      
      if (JRequest::getVar ( 'ads_status' ) == 3) {
        JToolBarHelper::deleteList ( '', 'removeads', 'JTOOLBAR_EMPTY_TRASH' );
      } else {
        JToolBarHelper::trash ( 'trash' );
      }
      
      /** Call function to publish and unpublish video ads */
      JToolBarHelper::publishList ();
      JToolBarHelper::unpublishList ();
    }
  }
}
