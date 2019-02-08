<?php
/**
 * Member details view file
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

/** Restricted access */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Admin videos view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewmemberdetails extends ContushdvideoshareView {
  protected $canDo;
  
  /**
   * Function to view for manage categories
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewmemberdetails object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Add css for member details page */
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    /** Add toolbars for member detials page */
    $this->addToolbar ();
    /** Get member details model */
    $model = $this->getModel ( 'memberdetails' );
    /** Get member details to display in grid view */
    $memberdetails = $model->getmemberdetails ();
    /** Assign reference to member details */
    $this->assignRef ( 'memberdetails', $memberdetails );
    parent::display ();
  }
  
  /**
   * Function to Setting the toolbar
   *
   * @return adminvideos
   */
  protected function addToolBar() {
    /** Add title for member details page */
    JToolBarHelper::title ( 'Member Details', 'memberdetails' );
    /** Check joomla version for member details page */
    if (version_compare ( JVERSION, '2.5.0', 'ge' ) || version_compare ( JVERSION, '1.6', 'ge' ) || version_compare ( JVERSION, '1.7', 'ge' ) || version_compare ( JVERSION, '3.0', 'ge' )) {
      /** Include admin helpers file */
      require_once JPATH_COMPONENT . '/helpers/contushdvideoshare.php';
      
      /** What Access Permissions does this user have? What can (s)he do? */
      $this->canDo = ContushdvideoshareHelper::getActions ();
      /** Check user is admin */
      if ($this->canDo->get ( 'core.admin' )) {
        /** Add tootlbars to make featured and unfeatured */
        JToolBarHelper::custom ( $task = 'allowupload', $icon = 'featured.png', $iconOver = 'featured.png', $alt = 'Enable User upload', $listSelect = true );
        JToolBarHelper::custom ( $task = 'unallowupload', $icon = 'unfeatured.png', $iconOver = 'unfeatured.png', $alt = 'Disable User upload', $listSelect = true );
        /** Add toolbars to publish and unpublish member */
        JToolBarHelper::publishList ( 'publish', 'Active' );
        JToolBarHelper::unpublishList ( 'unpublish', 'Deactive' );
        /** Add options toolbar in member details page */
        JToolBarHelper::divider ();
        JToolBarHelper::preferences ( COMPONENT );
      }
    } else {
      /** Add featured and unfeatured toolbars */
      JToolBarHelper::custom ( $task = 'allowupload', $icon = 'featured.png', $iconOver = 'featured.png', $alt = 'Enable User upload', $listSelect = true );
      JToolBarHelper::custom ( $task = 'unallowupload', $icon = 'unfeatured.png', $iconOver = 'unfeatured.png', $alt = 'Disable User upload', $listSelect = true );
      /** Add publish and unpublish toolbars */
      JToolBarHelper::publishList ( 'publish', 'Active' );
      JToolBarHelper::unpublishList ( 'unpublish', 'Deactive' );
    }
  }
}
