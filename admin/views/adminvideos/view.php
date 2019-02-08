<?php
/**
 * Admin videos view file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */
/** No direct access to this file  */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** Restricted Access */ 
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** User access levels */
jimport ( 'joomla.access.access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Admin videos view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewadminvideos extends ContushdvideoshareView {
  /**
   * Function to set icons and values for admin grid view
   *
   * @return adminvideos
   */
  public function adminvideos() {
    /** Call function to add tool bars in add video section*/
    $this->addToolBars();
    /** Get model object and call addvideos function */
    $model      = $this->getModel ();
    $videoslist = $model->addvideosmodel ();    
    $this->assignRef ( 'editvideo', $videoslist );
    /** Get player settings */
    $playerValues = getPlayerIconSettings('');
    $this->assignRef ( 'player_values', $playerValues );
    parent::display ();
  }
  
  /**
   * Function to set icons and values for admin edit view
   *
   * @return adminvideos
   */
  public function editvideos() {
    /** Call function to add tool bars in edit section */
    $this->addToolBars();
    /** Get model object and call addvideos function */
    $model          = $this->getModel ();
    $editvideoslist = $model->editvideosmodel ();
    $this->assignRef ( 'editvideo', $editvideoslist );
    /** Get player settings */
    $player_values = getPlayerIconSettings('');
    $this->assignRef ( 'player_values', $player_values );
    parent::display ();
  }
  
  /**
   * Fucntion to add toolbars in add and edit videos page 
   */
  public function addToolBars() {
    
    /** Include admin css file */
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    
    /** Check user type is admin and display p[age heading based on that */
    if (JRequest::getVar ( 'user', '', 'get' ) && JRequest::getVar ( 'user', '', 'get' ) == 'admin') {
      JToolBarHelper::title ( JText::_ ( 'Admin Videos' ), 'adminvideos' );
    } else {
      JToolBarHelper::title ( JText::_ ( 'Member Videos' ), 'membervideos' );
    }
    
    /** Add save button in toolbar */
    JToolBarHelper::save ( 'savevideos', 'Save' );
    /** Add apply button in toolbar */
    JToolBarHelper::apply ( 'applyvideos', 'Apply' );
    /** Add cancel button in toolbar */
    JToolBarHelper::cancel ( 'CANCEL7', 'Cancel' );
  }
}
