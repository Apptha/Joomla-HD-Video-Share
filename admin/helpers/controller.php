<?php
/**
 * Controller helper
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

/** Import Joomla controller library */
jimport ( 'joomla.application.component.controller' );
$flag = 0;
if (version_compare ( JVERSION, '3.0', 'ge' )) {
  $flag = 0;
} elseif (version_compare ( JVERSION, '2.5', 'ge' )) {
  $flag = 1;
} else {
 $flag = 2;
}

if ($flag == 1 || $flag == 2) {
 /**
  * Admin controller class.
  *
  * @package Joomla.Contus_HD_Video_Share
  * @subpackage Com_Contushdvideoshare
  * @since 1.5
  */
 class ContusvideoshareController extends JController {
 }
} else {
 /**
  * Admin controller class.
  *
  * @package Joomla.Contus_HD_Video_Share
  * @subpackage Com_Contushdvideoshare
  * @since 1.5
  */
 class ContusvideoshareController extends JControllerLegacy {
 }
}
