<?php
/**
 * Main Controller file for Contus HD Video Share
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper*/
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Load component controller, model and view files */
JLoader::register ( 'ContusvideoshareController', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/controller.php' );
JLoader::register ( 'ContushdvideoshareView', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/view.php' );
JLoader::register ( 'ContushdvideoshareModel', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/model.php' );

/** Inlcude component main controller */
require_once JPATH_COMPONENT . DS . 'controller.php';
$cache = JFactory::getCache ( 'com_contusvideoshare' );
$cache->clean ();
date_default_timezone_set ( 'UTC' );

/** check joomla version */
$version = getJoomlaVersion ();

/** Call component tales */
JTable::addIncludePath ( JPATH_ADMINISTRATOR . DS . 'components' . DS . COMPONENT . DS . 'tables' );
/** Create object for main controller */
$controller = new contushdvideoshareController ();
/** Get task from request */
$controller->execute ( JRequest::getVar ( 'task' ) );
/** Redirect to the requested task */ 
$controller->redirect ();
