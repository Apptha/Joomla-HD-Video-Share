<?php
/**
 * @name       Joomla HD Video Share
 * @SVN        3.7
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2014 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 * @Creation Date   March 2010
 * @Modified Date   September 2015
 */
/** Include component helper */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR .  'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
/**
 * Our main element class, creating a multi-select list out of an SQL statement
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class JFormFieldModal_Category extends JFormField {
 protected $type = 'Modal_Category';
 
 /**
  * Function to get category details
  *
  * @return getInput
  */
 public function getInput() {
  /** Load the modal behavior script */
  JHtml::_ ( 'behavior.modal', 'a.modal' );
  
  /** Build the script */
  $script = array ();
  $script [] = 'function jSelectArticle_' . $this->id . '(id, title, catid, object) {';
  $script [] = 'document.id("' . $this->id . '_id").value = id;';
  $script [] = 'document.id("' . $this->id . '_name").value = title;';
  $script [] = 'SqueezeBox.close();';
  $script [] = '}';
  
  /** Add the script to the document head */
  JFactory::getDocument ()->addScriptDeclaration ( implode ( "\n", $script ) );
  
  /** Setup variables for display */
  $html = array ();
  $link = 'index.php?layout=categorylist&option=com_contushdvideoshare&categorylist=1&amp;tmpl=component&amp;function=jSelectArticle_' . $this->id;
  $db = JFactory::getDBO ();
  $query = $db->getQuery ( true );
  $query->clear ()->select ( array (
    'category' 
  ) )->from ( '#__hdflv_category' )->where ( $db->quoteName ( 'id' ) . ' = ' . ( int ) $this->value );
  $db->setQuery ( $query );
  
  try {
   $title = $db->loadResult ();
  } catch ( RuntimeException $e ) {
   JError::raiseWarning ( 500, $e->getMessage () );
  }
  
  if (empty ( $title )) {
   $title = JText::_ ( 'Select a category' );
  }
  
  $title = htmlspecialchars ( $title, ENT_QUOTES, 'UTF-8' );
  
  /** The current user display field */
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
   $html [] = '<span class="input-append">';
   $html [] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />
       <a class="modal btn" title="Select Category" href="' . $link . '&amp;' . JSession::getFormToken () . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> ' . JText::_ ( 'JSELECT' ) . '</a>';
   $html [] = '</span>';
  } else {
   $html [] = '\n' . '<div style="float: left;"><input style="background: #ffffff;" type="text" id="' . $this->id . '_name" value="' . htmlspecialchars ( $title, ENT_QUOTES, 'UTF-8' ) . '" disabled="disabled" /></div>';
   $html [] = '<div class="button2-left"><div class="blank"><a class="modal" title="Select Category"  href="' . $link . '&amp;' . JSession::getFormToken () . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">' . JText::_ ( 'JSELECT' ) . '</a></div></div>' . "\n";
  }
  
  /** The active article id field */
  if (0 == ( int ) $this->value) {
   $value = '';
  } else {
   $value = ( int ) $this->value;
  }
  
  /** Class='required' for client side validation */
  $class = '';
  
  if ($this->required) {
   $class = ' class="required modal-value"';
  }
  
  $html [] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';
  
  return implode ( "\n", $html );
 }
}
