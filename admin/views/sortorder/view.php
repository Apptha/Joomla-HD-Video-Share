<?php
/**
 * Sort order view file
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

/**
 * Sortorder view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewsortorder extends ContushdvideoshareView {
  /**
   * Function for category sorting
   *
   * @return categorysortorder
   */
  public function categorysortorder() {
    /** Get sortorder model to sort categories */ 
    $model = $this->getModel ();
    /** Call function to sort categories */
    $model->sortorder_function ('cat');
  }
  
  /**
   * Function for video sorting
   *
   * @return videosortorder
   */
  public function videosortorder() {
    /** Get sortorder model to sort videos */
    $model = $this->getModel ();
    /** Call function to sort videos */
    $model->sortorder_function ('video');
  }
}
