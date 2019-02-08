<?php
/**
 * HVS Article plugin for HD Video Share
 *
 * This file is to install HVS Article plugin 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/**
 * HVS Article Plugin installation class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class PlgContenthvsarticleInstallerScript {
  /**
   * Joomla installation hook for plugin
   *
   * @param string $parent
   *          parent value
   *          
   * @return install
   */
  public function install($parent) {
  }
  
  /**
   * Joomla uninstallation hook for plugin
   *
   * @param string $parent
   *          parent value
   *          
   * @return uninstall
   */
  public function uninstall($parent) {
  }
  
  /**
   * Joomla update hook for plugin
   *
   * @param string $parent
   *          parent value
   *          
   * @return update
   */
  public function update($parent) {
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
  public function preflight($type, $parent) {
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
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Check joomla version and update status in extensions table for hvsarticle plugin */
    if (version_compare ( JVERSION, '1.7.0', 'ge' )) {
      $query->update ( $db->quoteName ( '#__extensions' ) );
    } elseif (version_compare ( JVERSION, '1.6.0', 'ge' )) {
      $query->update ( $db->quoteName ( '#__extensions' ) );
    } else {
      $query->update ( $db->quoteName ( '#__plugins' ) );      
    }
    $query->set ( $db->quoteName ( 'enabled' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'element' ) . ' = ' . $db->quote ( 'hvsarticle' ) );
    $db->setQuery ( $query );
    $db->query ();
    
    /** Rename xml file for joomla3 version */ 
    $root = JPATH_SITE;
    JFile::move ( $root . '/plugins/content/hvsarticle/hvsarticle.j3.xml', $root . '/plugins/content/hvsarticle/hvsarticle.xml' );
  }
}
