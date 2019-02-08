<?php
/**
 * Show videos view file
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
jimport ( 'joomla.access.access' );

/** Import joomla view library */
jimport ( 'joomla.application.component.view' );

/** Import Joomla pagination */
jimport ( 'joomla.html.pagination' );

/**
 * Show videos in grid view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewshowvideos extends ContushdvideoshareView {
  protected $canDo;
  
  /**
   * Function to prepare view for showvideos
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewshowvideos object to support chaining.
   *        
   * @since 1.5
   */
  public function showvideos($cachable = false, $urlparams = false) {
    /** Add css for show videos page */ 
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    /** Check page is not comment */
    if (JRequest::getVar ( 'page' ) != 'comment') {
      /** Get show videos model */
      $model = $this->getModel ();
      /** Call function to display videos detail */
      $showvideos = $model->showvideosmodel ();
      /** Assign reference for videos detail */
      $this->assignRef ( 'videolist', $showvideos );
      /** Get menu id from component helper and assign reference */
      $Itemid = getmenuitemid_thumb ('player', '');
      $this->assignRef ( 'Itemid', $Itemid );
    }
    
    /** Check page is comment */
    if (JRequest::getVar ( 'page' ) == 'comment') {
      /** Get show videos model to show comments */
      $model = $this->getModel ( 'showvideos' );
      /** Get comment details */
      $comment = $model->getcomment ();
      /** Assign reference to comment details */
      $this->assignRef ( 'comment', $comment );
      parent::display ();
    } else {
      parent::display ();
    }
    /** Call function to display toolbars */
    $this->addToolbar ();
  }
  
  /**
   * Function to set the toolbar
   *
   * @return showads
   */
  protected function addToolBar() {
    /** Check page is comment page */
    if (JRequest::getVar ( 'page' ) == 'comment') {
      /** Display title for comment page */
      JToolBarHelper::title ( 'Comments' );
    } elseif (JRequest::getVar ( 'user', '', 'get' )) {
      /** Display title for admin videos page */
      JToolBarHelper::title ( JText::_ ( 'Admin Videos' ), 'adminvideos' );
    } else {
      /** Display title for member videos page */
      JToolBarHelper::title ( JText::_ ( 'Member Videos' ), 'membervideos' );
    }
    
    /** Check joomla version for admin videos page */
    if (version_compare ( JVERSION, '2.5.0', 'ge' ) || version_compare ( JVERSION, '1.6', 'ge' ) || version_compare ( JVERSION, '1.7', 'ge' ) || version_compare ( JVERSION, '3.0', 'ge' )) {
      require_once JPATH_COMPONENT . '/helpers/contushdvideoshare.php';
      
      /** What Access Permissions does this user have? What can (s)he do? */
      $this->canDo = ContushdvideoshareHelper::getActions ();
      
      if (JRequest::getVar ( 'page' ) != 'comment') {
        if ($this->canDo->get ( 'core.create' ) &&  JRequest::getVar ( 'user', '', 'get' )) {
            JToolBarHelper::addNew ( 'addvideos', 'New Video' );
        }
        
        if ($this->canDo->get ( 'core.edit' )) {
          JToolBarHelper::editList ( 'editvideos', 'Edit' );
        }
        
        if ($this->canDo->get ( 'core.delete' ) && JRequest::getVar ( 'filter_state' ) == 3) {
            JToolBarHelper::deleteList ( '', 'Removevideos', 'JTOOLBAR_EMPTY_TRASH' );
        } else {
           if ($this->canDo->get ( 'core.delete' )) {
             JToolBarHelper::trash ( 'trash' );
           }
        }
        
        if ($this->canDo->get ( 'core.edit.state' )) {
          JToolBarHelper::publishList ();
          JToolBarHelper::unpublishList ();
          JToolBarHelper::custom ( $task = 'featured', $iconVideo = 'featured.png', $iconOver = 'featured.png', $alt = 'Enable Featured', $listSelectVideo = true );
          JToolBarHelper::custom ( $task = 'unfeatured', $iconVideo = 'unfeatured.png', $iconOver = 'unfeatured.png', $alt = 'Disable Featured', $listSelectVideo = true );
        }
        
        if ($this->canDo->get ( 'core.admin' )) {
          JToolBarHelper::divider ();
          JToolBarHelper::preferences ( COMPONENT );
        }
      } else {
        JToolBarHelper::cancel ( 'Commentcancel', 'Cancel' );
      }
    } else {
      /** Check page is not comment and display toolbar */
      if (JRequest::getVar ( 'page' ) != 'comment') {
        if (JRequest::getVar ( 'user', '', 'get' )) {
          JToolBarHelper::addNew ( 'addvideos', 'New Video' );
        }
        
        JToolBarHelper::editList ( 'editvideos', 'Edit' );
        
        if (JRequest::getVar ( 'filter_state' ) == 3) {
          JToolBarHelper::deleteList ( '', 'Removevideos', 'JTOOLBAR_EMPTY_TRASH' );
        } else {
          JToolBarHelper::trash ( 'trash' );
        }
        
        JToolBarHelper::publishList ();
        JToolBarHelper::unpublishList ();
        JToolBarHelper::custom ( $task = 'featured', $icon = 'featured.png', $iconOver = 'featured.png', $alt = 'Enable Featured', $listSelect = true );
        JToolBarHelper::custom ( $task = 'unfeatured', $icon = 'unfeatured.png', $iconOver = 'unfeatured.png', $alt = 'Disable Featured', $listSelect = true );
      } else {
        /** Display cancel toolbar for comment page */
        JToolBarHelper::cancel ( 'Commentcancel', 'Cancel' );
      }
    }
  }
}
