<?php
/**
 * Admin main controller
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

/** Initialize to false and change to true according to current menu */
$category_active = $memberdetails_active = $adminvideos_active = $membervideos_active = false;
$sitesettings_active = $settings_active = $sortorder_active = $googlead_active = $ads_active = false;

/** Set default time zone */
date_default_timezone_set ( 'UTC' );

/** Access check */
if (! JFactory::getUser ()->authorise ( 'core.manage', COMPONENT )) {
  return JError::raiseWarning ( 404, JText::_ ( 'JERROR_ALERTNOAUTHOR' ) );
}

/** Get current component directory path */
$componentPath = JPATH_COMPONENT;

/** Get videos directory path */
$videoPath  = JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'videos';

/** Define videos directory path */
define ( 'VPATH', $videoPath );

/** Define current component directory path */
define ( 'FVPATH', $componentPath );

/** Load component controller , helper and model */
JLoader::register ( 'ContusvideoshareController', JPATH_COMPONENT . '/helpers/controller.php' );
JLoader::register ( 'ContushdvideoshareView', JPATH_COMPONENT . '/helpers/view.php' );
JLoader::register ( 'ContushdvideoshareModel', JPATH_COMPONENT . '/helpers/model.php' );

/** Check directory separator constant is defined */
if (! defined ( 'DS' )) {
  define ( 'DS', DIRECTORY_SEPARATOR );
}
/** Get controlpanel controller name and set as default*/
$controllerName = JRequest::getCmd ( 'layout', 'controlpanel' );

/** Check controller name is category list.
 *  If yes, then set controller name as category */ 
if ($controllerName == 'categorylist') {
  $controllerName = 'category';
}

/** Check which controller is executed */
switch ($controllerName) {
  case "category" :
    $category_active = true;
    break;
  case "memberdetails" :
    $memberdetails_active = true;
    break;
  case "adminvideos" :
    if (JRequest::getCmd ( 'user', '', 'get' ) == 'admin') {
      $adminvideos_active = true;
    } else {
      $membervideos_active = true;
    }
    break;
  case "sitesettings" :
    $sitesettings_active = true;
    break;
  case "settings" :
    $settings_active = true;
    break;
  case "sortorder" :
    $sortorder_active = true;
    break;
  case "googlead" :
    $googlead_active = true;
    break;
  case "ads" :
    $ads_active = true;
    break;
  default :
    $controllerName = 'controlpanel';
    break;
}

/** Adding menus */
JSubMenuHelper::addEntry ( JText::_ ( 'Member Videos' ), 'index.php?option=com_contushdvideoshare&layout=adminvideos', $membervideos_active );
JSubMenuHelper::addEntry ( JText::_ ( 'Member Details' ), 'index.php?option=com_contushdvideoshare&layout=memberdetails', $memberdetails_active );
JSubMenuHelper::addEntry ( JText::_ ( 'Admin Videos' ), 'index.php?option=com_contushdvideoshare&layout=adminvideos&user=admin', $adminvideos_active );
JSubMenuHelper::addEntry ( JText::_ ( 'Category' ), 'index.php?option=com_contushdvideoshare&layout=category', $category_active );
JSubMenuHelper::addEntry ( JText::_ ( 'Player Settings' ), 'index.php?option=com_contushdvideoshare&layout=settings', $settings_active );
JSubMenuHelper::addEntry ( JText::_ ( 'Site Settings' ), 'index.php?option=com_contushdvideoshare&layout=sitesettings', $sitesettings_active );
JSubMenuHelper::addEntry ( JText::_ ( 'Google AdSense' ), 'index.php?option=com_contushdvideoshare&layout=googlead', $googlead_active );
JSubMenuHelper::addEntry ( JText::_ ( 'Video Ads ' ), 'index.php?option=com_contushdvideoshare&layout=ads', $ads_active );

/** Load corresponding contrller file */
require_once JPATH_COMPONENT . DS . 'controllers' . DS . $controllerName . '.php';
$controllerName = 'ContushdvideoshareController' . $controllerName;

/** Create the controller object */
$controller = new $controllerName ();

/** Perform the Request task */
$controller->execute ( JRequest::getCmd ( 'task' ) );

/** Redirect if set by the controller */
$controller->redirect ();
