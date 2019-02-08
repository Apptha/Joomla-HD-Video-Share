<?php
/**
 * Videoshare module custom fileds file for HD Video Share
 *
 * This file is to customize the fileds for hdvideoshare modules
 *
 * @category   Apptha
 * @package    mod_hdvideosharemodules
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla libraries */
jimport ( 'joomla.html.html' );
jimport ( 'joomla.form.formfield' );
jimport ( 'joomla.html.html.select' );

/** 
 * JForm Class for Video Category list field 
 * 
 * @author user 
 */
class JFormFieldVsmodtype extends JFormField {
  protected $type = 'Vsmodtype';
  
  /**
   * Function to get form values
   * 
   * @see JFormField::getInput()
   */
  function getInput() {
    return $this->fetchElement ( $this->element ['name'], $this->value, $this->name );
  }
  
  /** 
   * Function to fetch videos from table and display in module parameter 
   * 
   * @param unknown $name
   * @param unknown $value
   * @param unknown $node
   * @param unknown $control_name
   * @return mixed
   */
  function fetchElement($catname, $catvalue, $controlName) {
    /** Define arrays for rss type */
    $modoptions = array ( );
    $modoptions [0] = 'recent';
    $modoptions [0] = 'Recent Videos';
    $modoptions [1] = 'featured';
    $modoptions [1] = 'Featured Videos';
    $modoptions [2] = 'popular';
    $modoptions [2] = 'Popular Videos';
    $modoptions [3] = 'random';
    $modoptions [3] = 'Random Videos';
    $modoptions [4] = 'related';
    $modoptions [4] = 'Related Videos';
    $modoptions [5] = 'category';
    $modoptions [5] = 'Category Videos';
    $modoptions [6] = 'watchlater';
    $modoptions [6] = 'Watch later';
    $modoptions [7] = 'History Videos';
    $modoptions [7] = 'History Videos';
    
    /** Display select box to select the rss type in module params */ 
    return JHTML::_ ( 'select.genericlist', $modoptions, '' . $controlName . '[' . $catname . ']', 'class="inputbox"
            onchange= "javascript:if(document.getElementById(\'jformparamsvsmodtypevsmodtype\').value == 5) {
              document.getElementById(\'jformparamscatidcatid_chzn\').style.display=\'block\';
              document.getElementById(\'jform_params_catid-lbl\').style.display=\'block\';
            } else {
              document.getElementById(\'jformparamscatidcatid_chzn\').style.display=\'none\';
              document.getElementById(\'jform_params_catid-lbl\').style.display=\'none\';
            };"', 'id', 'title', $catvalue, $controlName . $catname );
  }
}
