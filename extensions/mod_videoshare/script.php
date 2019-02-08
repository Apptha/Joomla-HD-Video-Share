<?php
/**
 * Video Share Player module for HD Video Share
 *
 * This file is to install Video Share player as a module 
 *
 * @category   Apptha
 * @package    mod_videoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');
/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/**
 * Class mod_videoshareInstallerScript is used to install videoshare player module
 * 
 * @author user
 */
class mod_videoshareInstallerScript {
  /**
   * Joomla installation hook for plugin
   *
   * @param string $parent
   *          parent value
   *          
   * @return install
   */
  function install($parent) {
    $db     = JFactory::getDBO ();
    /** */
    $query  = "UPDATE #__modules SET published='1' WHERE module='mod_videoshare' ";
    $db->setQuery ( $query );
    $db->query ();
    
    /** Query to get module id */
    $query  = "SELECT id FROM #__modules WHERE module = 'mod_videoshare' ";
    $db->setQuery ( $query );
    $db->query ();
    $mid4   = $db->loadResult ();
    
    /** Query to insert module id into menu's table */
    $query  = "INSERT INTO #__modules_menu (moduleid) VALUES ('$mid4')";
    $db->setQuery ( $query );
    $db->query ();
  }
  
  /**
   * Joomla uninstallation hook for plugin
   *
   * @return uninstall
   */
  function uninstall($parent) {
  }
  
  /**
   * Joomla before installation hook for plugin
   *
   * @param string $type
   *          type
   * @param string $parent
   *          parent value
   *          
   * @return preflight
   */
  function preflight($type, $parent) {
  }
  
  /**
   * Joomla after installation hook for plugin
   *
   * @param string $type
   *          type
   * @param string $parent
   *          parent value
   *          
   * @return postflight
   */
  function postflight($type, $parent) {
  }
}
