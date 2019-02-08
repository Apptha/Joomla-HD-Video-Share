<?php
/**
 * Category view file
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
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Admin category view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewcategory extends ContushdvideoshareView {
  protected $canDo;
  
  /**
   * Function to view for manage categories
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return contushdvideoshareViewcategory object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Add css for category admin view */ 
    JHTML::stylesheet ( 'styles.css', 'administrator/components/com_contushdvideoshare/css/' );
    /** Check task is edit */
    if (JRequest::getVar ( 'task' ) == 'edit') {
      /** Add category title for edit page */
      JToolBarHelper::title ( 'Category' . ': [<small>Edit</small>]', 'category' );
      /** Add toolbars for category edit section */
      JToolBarHelper::save ();
      JToolBarHelper::apply ();
      JToolBarHelper::cancel ();
      $model = $this->getModel ();
      /** Get category id */
      $id = JRequest::getVar ( 'cid' );
      /** Get category details to edit category */
      $category = $model->getcategorydetails ( $id [0] );
      /** Assign reference to edit category detail */
      $this->assignRef ( 'category', $category [0] );
      $this->assignRef ( 'categorylist', $category [1] );
      parent::display ();
    }
    
    /** Check task is add */
    if (JRequest::getVar ( 'task' ) == 'add') {
      /** Add category title to category add section */
      JToolBarHelper::title ( 'Category' . ': [<small>Add</small>]', 'category' );
      /** Add toolbars to cateogry add section */ 
      JToolBarHelper::save ();
      JToolBarHelper::cancel ();
      /** Get category model to add new data */
      $model = $this->getModel ();
      /** Call function to add new category */
      $category = $model->getNewcategory ();
      /** Assign reference to category details */ 
      $this->assignRef ( 'category', $category [0] );
      $this->assignRef ( 'categorylist', $category [1] );
      parent::display ();
    }
    
    /** Check task is empty */
    if (JRequest::getVar ( 'task' ) == '') {
      /** Add toolbars and get category model */
      $this->addToolbar ();
      $model = $this->getModel ( 'category' );
      /** Get category details to display in grid */
      $category = $model->getcategory ();
      /** Assign refernce to category details to display */
      $this->assignRef ( 'category', $category );
      parent::display ();
    }
  }
  
  /**
   * Function for Setting the toolbar
   *
   * @return addToolBar
   */
  protected function addToolBar() {
    /** Check joomla version */ 
    if (version_compare ( JVERSION, '2.5.0', 'ge' ) || version_compare ( JVERSION, '1.6', 'ge' ) || version_compare ( JVERSION, '1.7', 'ge' ) || version_compare ( JVERSION, '3.0', 'ge' )) {
      require_once JPATH_COMPONENT . '/helpers/contushdvideoshare.php';
      
      /** What Access Permissions does this user have? What can (s)he do? */
      $this->canDo = ContushdvideoshareHelper::getActions ();
      JToolBarHelper::title ( 'Category', 'category' );
      
      /** Check action is to create category */
      if ($this->canDo->get ( 'core.create' )) {
        JToolbarHelper::addNew ();
      }
      /** Check action is to edit category */
      if ($this->canDo->get ( 'core.edit' )) {
        JToolBarHelper::editList ();
      }
      /** Check action is to edit category state */ 
      if ($this->canDo->get ( 'core.edit.state' )) {
        JToolBarHelper::publishList ();
        JToolBarHelper::unpublishList ();
      }
      /** Check action is to delete or trash category */
      if ($this->canDo->get ( 'core.delete' ) && JRequest::getVar ( 'category_status' ) == 3) {
        JToolBarHelper::deleteList ( '', 'remove', 'JTOOLBAR_EMPTY_TRASH' );
      } else {
        if( $this->canDo->get ( 'core.delete' )) {
          JToolBarHelper::trash ( 'trash' );
        }
      }
      /** Check user is admin and add options toolbar for category page */
      if ($this->canDo->get ( 'core.admin' )) {
        JToolBarHelper::divider ();
        JToolBarHelper::preferences ( COMPONENT );
      }
    } else {
      /** Add toolbars for joomla 3.x versions */
      JToolBarHelper::addNew ();
      JToolBarHelper::editList ();
      
      /** Check category status and display empty trash button */ 
      if (JRequest::getVar ( 'category_status' ) == 3) {
        JToolBarHelper::deleteList ( '', 'remove', 'JTOOLBAR_EMPTY_TRASH' );
      } else {
        JToolBarHelper::trash ( 'trash' );
      }
      /** Add category publish and unpublish toolbars */
      JToolBarHelper::publishList ();
      JToolBarHelper::unpublishList ();
    }
  }
}
