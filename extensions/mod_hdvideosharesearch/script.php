<?php
/**
 * Search module for HD Video Share
 *
 * This file is to install Search module 
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

/** Import Joomla filesystem library */
jimport ( 'joomla.filesystem.folder' );

/** Import Joomla installer library */
jimport ( 'joomla.installer.installer' );

/** Import Joomla environment library */
jimport ( 'joomla.environment.uri' );

/**
 * Search Videos Module installer file
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class mod_hdvideosharesearchInstallerScript {
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
  public function preflight($type, $parent) {
  }
  
  /**
   * Joomla installation hook for plugin
   *
   * @param string $parent
   *          parent value
   *          
   * @return install
   */
  public function install($parent) {
    $db     = JFactory::getDBO ();
    $query  = $db->getQuery ( true );
    
    /** Query to publish the search module */
    $query->update ( $db->quoteName ( '#__modules' ) )->set ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'module' ) . ' = ' . $db->quote ( 'mod_hdvideosharesearch' ) );
    $db->setQuery ( $query );
    $db->query ();
    
    /** Query to select search module id */
    $query->clear ()->select ( 'id' )->from ( '#__modules' )->where ( $db->quoteName ( 'module' ) . ' = ' . $db->quote ( 'mod_hdvideosharesearch' ) );
    $db->setQuery ( $query );
    $db->query ();
    $mid4 = $db->loadResult ();
    
    /** Query to insert module id to menu's table  */
    $query->clear ()->insert ( $db->quoteName ( '#__modules_menu' ) )->columns ( $db->quoteName ( 'moduleid' ) )->values ( $db->quote ( $mid4 ) );
    $db->setQuery ( $query );
    $db->query ();
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
  public function postflight($type, $parent) {
  }
  
  /**
   * Joomla uninstallation hook for plugin
   *
   * @return uninstall
   */
  public function uninstall() {
  }
}
