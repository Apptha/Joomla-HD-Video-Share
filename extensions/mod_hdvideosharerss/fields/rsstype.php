<?php
/**
 * RSS module custom fileds file for HD Video Share
 *
 * This file is to customize the fileds for RSS module
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
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
class JFormFieldRsstype extends JFormField {
  protected $type = 'Rsstype';
  
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
  function fetchElement($name, $value, $control_name) {
    /** Define arrays for rss type */
    $options = array ( 'id', 'title' );
    $options [0] = 'recent';
    $options [0] = 'Recent Videos';
    $options [1] = 'featured';
    $options [1] = 'Featured Videos';
    $options [2] = 'popular';
    $options [2] = 'Popular Videos';
    $options [3] = 'category';
    $options [3] = 'Category Videos';
    
    /** Display select box to select the rss type in module params */ 
    return JHTML::_ ( 'select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"
            onchange=
            "javascript:if(document.getElementById(\'jformparamsrsstypersstype\').value == 3) {
              document.getElementById(\'jformparamscatidcatid_chzn\').style.display=\'block\';
              document.getElementById(\'jform_params_catid-lbl\').style.display=\'block\';
            } else {
              document.getElementById(\'jformparamscatidcatid_chzn\').style.display=\'none\';
              document.getElementById(\'jform_params_catid-lbl\').style.display=\'none\';
            };"', 'id', 'title', $value, $control_name . $name );
  }
}
