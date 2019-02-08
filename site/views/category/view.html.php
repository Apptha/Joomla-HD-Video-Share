<?php
/**
 * Category videos view file
 *
 * This file is to display Category videos
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include ecomponent helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );
define ('CATEGORYPARAM', JRequest::getString ( CATEGORY ));
define ('CATIDPARAM', JRequest::getInt ( CATID ));

/**
 * Category view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewcategory extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewcategory object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    $model = $this->getModel ();
    
    /** Calling the function in models category.php */
    $getcategoryview = $model->getcategory ();
    /** Assigning reference to category view */
    $this->assignRef ( 'categoryview', $getcategoryview );
    
    /** Get category row/col settings */
    $categorrowcol = getpagerowcol ();
    /** Assigning reference for the category row/col settings */
    $this->assignRef ( 'categoryrowcol', $categorrowcol );
    
    /** Get category list */
    $getcategoryListVal = getcategoryList(CATEGORYTABLE, PLAYERTABLE, 'playlistid');
    /** Assigning reference for the category list */
    $this->assignRef ( 'categoryList', $getcategoryListVal );
    
    /** Get player settings for the category view */
    $getplayersettings = getPlayerIconSettings ('both');
    /** Assigning reference for the player settings */
    $this->assignRef ( 'player_values', $getplayersettings );
    
    /** Get category id for category view */
    $getcategoryid = getcategoryid( CATEGORYPARAM, CATEGORYTABLE, SEOCATEGORY, CATIDPARAM );
    /** Assigning reference for the category id for category view  */
    $this->assignRef ( 'getcategoryid', $getcategoryid );
    
    /** Get access level for category view */
    $homeAccessLevel = getHTMLVideoAccessLevel ( VIDEOCATEGORYTABLE );
    /** Assigning reference for the category videos access level result */
    $this->assignRef ( 'homepageaccess', $homeAccessLevel );

    $catID = '';
    if(isset($getcategoryview[0])){
      $catID = $getcategoryview[0]->id;
    }
    /** Get Item id for category view */
    $Itemid = getmenuitemid_thumb ('category' , $catID );   
    /** Assigning reference for the category Item id */
    $this->assignRef ( 'Itemid', $Itemid );
    
    parent::display ();
  }
}
