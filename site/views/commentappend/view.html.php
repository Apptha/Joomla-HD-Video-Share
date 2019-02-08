<?php
/**
 * View file for Append default comment on the player page
 *
 * This file is to Append default comment on the player page
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Commentappend view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewcommentappend extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewcommentappend object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get model for comment section */
    $model = $this->getModel ();
    
    /** Get comment details */
    $getcomments = $model->getcomment ();
    
    /** Assigning the reference for the comment results */
    $this->assignRef ( 'commenttitle', $getcomments [0] );
    
    /** Assigning the reference for the comment title results */
    $this->assignRef ( 'commenttitle1', $getcomments [1] );
    
    /** Assigning the reference for the player setting results */
    $this->assignRef ( 'playersettings', $getcomments [2] );
    
    /** Assigning the reference for the comment setting results */
    $this->assignRef ( 'dispEnable', $getcomments [3] );
    
    /** Assigning the reference for the rating results */
    $commentsview = $model->ratting ();
    $this->assignRef ( 'commentview', $commentsview );
    parent::display ();
  }
}
