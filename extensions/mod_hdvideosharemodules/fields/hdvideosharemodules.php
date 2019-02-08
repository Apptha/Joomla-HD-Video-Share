<?php
/**
 * Module custom fileds file for HD Video Share
 *
 * This file is to customize the fileds for videoshare module
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

/** Imports for html */
jimport ( 'joomla.html.html' );

/** Imports for param fields */
jimport ( 'joomla.form.formfield' );


/**
 * Class for playlist, videos form fields
 *
 * @author user
 */
class JFormFieldHdvideoshareModules extends JFormField {
  protected $type = 'hdvideosharemodules';
  
  /**
   * Function for input to playlist parameter
   * 
   * @see JFormField::getInput()
   */
  function getInput() {
    return $this->fetchElement ( $this->element ['name'], $this->value, $this->name );
  }
  
  /**
   * Function to fetch playlist info from database
   *  
   * @param unknown $name
   * @param unknown $value
   * @param unknown $node
   * @param unknown $control_name
   * @return mixed
   */
  function fetchElement($name, $value, $control_name) {
    $db = JFactory::getDBO ();
    $query    = $db->getQuery ( true );
    $modtype = '';
    
    /** Query to fetch the playlist records */
    $query = 'SELECT id, category AS name' . ' FROM #__hdflv_category' . ' WHERE published = 1' . ' ORDER BY category ASC';    
    $db->setQuery ( $query );
    $modueOptions = $db->loadObjectList ();
    
    /** Fetch module id */
    $moduleId = $getID = "";
    $getID = JRequest::getVar ( 'id' );
    if (isset ( $getID )) {
      $moduleId = $getID;
    }
      
    /** Check If module id available */
    if ($moduleId != '') {
      /** Fetch params from module table */
      $query          = 'SELECT params from #__modules WHERE id=' . $moduleId;
      $db->setQuery ( $query );
      $vsmodule_params    = $db->loadObject ();
      $paramdecode  = json_decode ( $vsmodule_params->params, true );
      $modtype      = $paramdecode ['vsmodtype'] ['vsmodtype'];
          
      /** If Video category 1, show playlists */
      if ($modtype == 5) {
        echo "<style> #jformparamscatidcatid_chzn, #jform_params_catid-lbl { display:block;  }</style>";
      } else {
        echo "<style> #jformparamscatidcatid_chzn, #jform_params_catid-lbl { display:none;  }</style>";
      }
    }
    return JHTML::_ ( 'select.genericlist', $modueOptions, '' . $control_name . '[' . $name . ']', 'class="inputbox" ', 'id', 'name', $value, $control_name . $name );
  }
}
