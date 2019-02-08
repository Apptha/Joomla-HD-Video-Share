<?php
/**
 * View helper
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

/** Import view libraries */
jimport ( 'joomla.application.component.view' );

if (version_compare ( JVERSION, '3.0', 'ge' )) {
 /**
  * Admin ContushdvideoshareView class.
  *
  * @package Joomla.Contus_HD_Video_Share
  * @subpackage Com_Contushdvideoshare
  * @since 1.5
  */
 class ContushdvideoshareView extends JViewLegacy {
 }
} else {
 /**
  * Admin ContushdvideoshareView class.
  *
  * @package Joomla.Contus_HD_Video_Share
  * @subpackage Com_Contushdvideoshare
  * @since 1.5
  */
 class ContushdvideoshareView extends JView {
 }
}
