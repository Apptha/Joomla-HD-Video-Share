<?php
/**
 * HD Video Share Player module
 *
 * This file is to fetch details for Player module 
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
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
 * Class Modvideoshare is used to fetch details from database 
 * 
 * @author user
 */
class Modvideoshare {
  /**
   * Function to featured video
   *
   * @return object
   */
  public static function getVideoListDetails() {
    /** Get db connection for videoshare player module */
    $db     = JFactory::getDBO ();
    $query  = $db->getQuery ( true );
    
    /** Query to get featured video details for module player */
    $query->select ( '*' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'featured' ) . ' = ' . $db->quote ( '1' ) )->order ( $db->quoteName ( 'id' ) . ' DESC' );
    $db->setQuery ( $query, 0, 1 );
    /** Get feature video for player module */
    $video  = $db->loadObject ();
    
    /** Check featured video is exists */
    if (empty ( $video )) {
      /** Query to get recent video details for module player */
      $query->clear ()->select ( '*' )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->order ( $db->quoteName ( 'id' ) . ' DESC' );
      $db->setQuery ( $query, 0, 1 );
      /** Get recent video for player module */
      $video = $db->loadObject ();
    }
    return $video;
  }
   
  /**
   * Function to get video share player module parameters
   *
   * @return object
   */
  public static function getvideoshareParam() {
    /** Get videoshare params file path */
    $filePath = dirname ( __FILE__ ) . DS . 'params.ini';
    /** Get videoshare params from params file */
    $content  = file_get_contents ( $filePath );
    /** Return videoshare module params */ 
    return new JRegistry ( $content );
  }
  /** Class Modvideoshare ends */
}
