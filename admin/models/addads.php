<?php
/**
 * Add ads model file
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

/** Import joomla model librar */ 
jimport ( 'joomla.application.component.model' );

/**
 * Admin ads model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModeladdads extends ContushdvideoshareModel {
 /**
  * Function to add ads
  *
  * @return addvideos
  */
 public function addadsmodel() {
   /** Get ads details and assign to reference */
  $rs_ads = JTable::getInstance ( 'ads', 'Table' );
  return array ( 'rs_ads' => $rs_ads );
 }
}
