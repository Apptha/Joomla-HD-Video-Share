<?php
/**
 * Category elements file 
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

/**  * No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

define ('SELECT_CATEGORY', 'Select a category' );
/** This trick allows us to extend the correct class, based on whether it's Joomla! 1.5 or 1.6 */
if (! class_exists ( 'JFakeElementBase' )) {
  if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
    /**
     * Admin JFakeElementBase class.
     *
     * @package Joomla.Contus_HD_Video_Share
     * @subpackage Com_Contushdvideoshare
     * @since 1.5
     */
    class JFakeElementBase extends JFormField {
      /**
       * Function getInput
       *
       * @return getInput
       */
      public function getInput() {
      }
    }
  } else {
    /**
     * Admin JFakeElementBase class.
     *
     * @package Joomla.Contus_HD_Video_Share
     * @subpackage Com_Contushdvideoshare
     * @since 1.5
     */
    class JFakeElementBase extends JElement {
    }
  }
}

/**
 * Admin JFakeElementSQL2 class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class JFakeElementSQL2 extends JFakeElementBase {
  /**
   * Function to get fetch catgory elements
   *
   * @param var $name
   *          input box name
   * @param var $value
   *          input box value
   * @param
   *          var &$node node value
   * @param var $control_name
   *          control name
   *          
   * @return fetchElement
   */
  public function fetchElement($name, $value, $control_name) {
    /** Get document and db object */
    $doc = & JFactory::getDocument ();
    $db = JFactory::getDBO ();
    
    /** Get category from category table for the given id */
    $db->setQuery ( 'SELECT category FROM #__hdflv_category WHERE id = "' . ( int ) $value . '" ' );
    $title = $db->loadResult ();
    if (empty ( $title )) {
      $title = JText::_ ( SELECT_CATEGORY );
    }
    
    /** Add the script to the document head. */
    $js = " public function jSelectArticle(id, title, object) { 
        document.getElementById(object + '_id').value = id; 
        document.getElementById(object + '_name').value = title; 
        document.getElementById('sbox-window').close(); 
    }";
    $doc->addScriptDeclaration ( $js );
    $link = 'index.php?layout=categorylist&option=com_contushdvideoshare&categorylist=1&amp;tmpl=component&amp;object=' . $name;
    
    JHTML::_ ( 'behavior.modal', 'a.modal' );
    $htmlData = "\n" . '<div style="float: left;">
              <input style="background: #ffffff;" type="text" id="' . $name . '_name" value="' . htmlspecialchars ( $title, ENT_QUOTES, 'UTF-8' ) . '" disabled="disabled" />
            </div>';
    $htmlData .= '<div class="button2-left"><div class="blank">
        <a class="modal" title="' . JText::_ ( 'Select an category' ) . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">' . JText::_ ( 'Select' ) . '</a></div></div>' . "\n"; 
    return $htmlData . "\n" . '<input type="hidden" id="' . $name . '_id" name="urlparams[' . $name . ']" value="' . ( int ) $value . '" />';

  }
  
  /**
   * Function getInput
   *
   * @return getInput
   */
  public function getInput() {
    /** Load the modal behavior script */
    JHtml::_ ( 'behavior.modal', 'a.modal' );
    
    /** Build the script */
    $script = array ();
    $script [] = 'function jSelectArticle(id, title, catid, object) {';
    $script [] = 'document.id("' . $this->id . '_id").value = id;';
    $script [] = 'document.id("' . $this->id . '_name").value = title;';
    $script [] = 'SqueezeBox.close();';
    $script [] = '}';
    
    /** Add the script to the document head */
    JFactory::getDocument ()->addScriptDeclaration ( implode ( "\n", $script ) );
    
    /** Setup variables for display */
    $htmlData = array ();
    $link = 'index.php?layout=categorylist&option=com_contushdvideoshare&categorylist=1&amp;tmpl=component&amp;function=jSelectArticle';
    
    $db = JFactory::getDBO ();
    $db->setQuery ( 'SELECT category FROM #__hdflv_category WHERE id = ' . ( int ) $this->value );
    $title = $db->loadResult ();
    
    if ($error = $db->getErrorMsg ()) {
      JError::raiseWarning ( 500, $error );
    }
    
    if (empty ( $title )) {
      $title = JText::_ ( SELECT_CATEGORY );
    }
    
    $title = htmlspecialchars ( $title, ENT_QUOTES, 'UTF-8' );
    
    /** The current user display field */
    $htmlData [] = '<div class="fltlft">';
    $htmlData [] = '  <input type="text" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
    $htmlData [] = '</div>';
    
    /** The user select button */
    $htmlData [] = '<div class="button2-left">';
    $htmlData [] = ' <div class="blank">';
    $htmlData [] = 'a class="modal" title="' . JText::_ ( SELECT_CATEGORY ) . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">' . JText::_ ( SELECT_CATEGORY ) . '</a>';
    $htmlData [] = '  </div>';
    $htmlData [] = '</div>';
    
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
    
    $htmlData [] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';
    
    return implode ( "\n", $htmlData );
  }
}

/**
 * Part two of our trick; we define the proper element name, depending on whether it's Joomla! 1.5 or 1.6
 */
if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  /**
   * Admin JFormFieldCategory class.
   *
   * @package Joomla.Contus_HD_Video_Share
   * @subpackage Com_Contushdvideoshare
   * @since 1.5
   */
  class JFormFieldCategory extends JFakeElementSQL2 {
  }
} else {
  /**
   * Admin JElementCategory class.
   *
   * @package Joomla.Contus_HD_Video_Share
   * @subpackage Com_Contushdvideoshare
   * @since 1.5
   */
  class JElementCategory extends JFakeElementSQL2 {
  }
}
