<?php
/**
 * Ads view file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */

/** No direct access to this file */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** Restricted Access */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * View class for the hdvideoshare component (Ads tab)
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewads extends ContushdvideoshareView {
  /**
   * Function to add ads
   *
   * @return ads
   */
  public function ads() {
    /** Add stylesheet for admin view */
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    /** Add toolbars for ads page */
    JToolBarHelper::title ( JText::_ ( 'Video Ads' ), 'ads' );
    JToolBarHelper::save ( 'saveads', 'Save & Close' );
    JToolBarHelper::apply ( 'applyads', 'Apply' );
    JToolBarHelper::cancel ( 'CANCEL6', 'Cancel' );
    /** Get ads model */
    $model = $this->getModel ();
    /** Call function to get ads detail */
    $adslist = $model->addadsmodel ();
    /** Assign refrence to ads detail */
    $this->assignRef ( 'adslist', $adslist );
    parent::display ();
  }
  
  /**
   * Function to edit ads
   *
   * @return editads
   */
  public function editads() {
    /** Add title for ads edit page */
    JToolBarHelper::title ( JText::_ ( 'Ads' ) . ': [<small>Edit</small>]' );
    /** Add toolbars for ads edit page */
    JToolBarHelper::save ( 'saveads', 'Save & Close' );
    JToolBarHelper::apply ( 'applyads', 'Apply' );
    JToolBarHelper::cancel ( 'CANCEL6', 'Cancel' );
    /** Get model to edit ads detail */
    $model = $this->getModel ();
    /** Call function to edit ads detail */
    $editlist = $model->editadsmodel ();
    /** Assign refrence to edit ads detail */
    $this->assignRef ( 'adslist', $editlist );
    parent::display ();
  }
}
