<?php
/**
 * Filter helper
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

/**
 * Admin HdvideoshareFilterHelper class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class HdvideoshareFilterHelper {
 /**
  * Function to get list of option for status
  *
  * @return getStatusOptions
  */
 public function getStatusOptions() {
  return array ( '1' => 'Enable', '2' => 'Disable' );
 }
 
 /**
  * Function to get list of option for Featured
  *
  * @return getFeaturedOptions
  */
 public function getFeaturedOptions() {
  return array ( '1' => 'Featured', '2' => 'Unfeatured' );
 }
 
 /**
  * Get a list of filter options for ad types
  *
  * @return getAdTypes
  */
 public function getAdTypes() {
  return array ( 'mid' => 'Mid Roll Ad', 'prepost' => 'Pre/Post Roll Ad' );
 }
}
