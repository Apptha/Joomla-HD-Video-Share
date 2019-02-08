<?php
/**
 * Component helper
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2014 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */
/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
/**
 * Admin ContushdvideoshareHelper class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareHelper {
 /**
  * Function to getActions
  *
  * @param int $messageId
  *         message id
  *         
  * @return getActions
  */
 public $canDo;
 public static function getActions($messageId = 0) {
  /** Include access joomla library */
  jimport ( 'joomla.access.access' );
  
  /** Get user object */ 
  $user = JFactory::getUser ();
  /** Create result object */
  $result = new JObject ();
  
  if (empty ( $messageId )) {
   $assetName = COMPONENT;
  } else {
   $assetName = 'com_contushdvideoshare.message.' . ( int ) $messageId;
  }
  /** GEt actions */
  $actions = JAccess::getActions ( COMPONENT, 'component' );
  
  /** Set user name and access level for the actions */
  foreach ( $actions as $action ) {
   $result->set ( $action->name, $user->authorise ( $action->name, $assetName ) );
  }
  
  return $result;
 }
 
 /**
  * Function to Setting the toolbar
  *
  * @return addToolBar
  */
 public static function addToolBar($title , $text) {
   /** Include toolbars */
   JToolBarHelper::title ( JText::_ ( $title ), $text );
 
   /** Check joomla versions to add toolbars */
   if (version_compare ( JVERSION, '2.5.0', 'ge' ) || version_compare ( JVERSION, '1.6', 'ge' ) || version_compare ( JVERSION, '1.7', 'ge' ) || version_compare ( JVERSION, '3.0', 'ge' )) {
 
     /** What Access Permissions does this user have? What can (s)he do? */
     $canDo = ContushdvideoshareHelper::getActions ();
 
     /** Display admin toolbars */
     if ($canDo->get ( 'core.admin' )) {
       JToolBarHelper::apply ();
       JToolBarHelper::divider ();
       JToolBarHelper::preferences ( COMPONENT );
     }
   } else {
     JToolBarHelper::apply ();
   }
 }
}
