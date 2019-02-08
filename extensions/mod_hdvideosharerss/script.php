<?php
/**
 * RSS module for HD Video Share
 *
 * This file is to display Video Share RSS module 
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
 * @version    3.7
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
/** Include component helper file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/**
 * Class mod_videoshareRSSInstallerScript is used to install the rss module 
 * 
 * @author user
 */
class mod_videoshareRSSInstallerScript {
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
    $query = $db->getQuery ( true );
    /** Query to publish rss module */
    $query->update ( $db->quoteName ( '#__modules' ) )->set ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'module' ) . ' = ' . $db->quote ( 'mod_hdvideosharerss' ) );
    $db->setQuery ( $query );
    $db->query ();
    
    /** Query to select rss module id */
    $query  = "SELECT id FROM #__modules WHERE module = 'mod_hdvideosharerss' ";
    $db->setQuery ( $query );
    $db->query ();
    $mid4   = $db->loadResult ();
    
    /** Query to insert rss module id to the menu table */
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
