<?php
/**
 * Playerbase model file
 *
 * This file is to display player home page
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla model library */
jimport ( 'joomla.application.component.model' );

/**
 * Playerbase model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideoshareplayerbase extends ContushdvideoshareModel {
  /**
   * Function to get player skin
   *
   * @return void
   */
  public function playerskin() {    
    /** Call function to show player */
    $this->showplayer ( PLAYERPATH );
  }
  
  /**
   * Function to show player
   *
   * @param string $playerpath
   *          player skin path
   *          
   * @return void
   */
  public function showplayer($playerpath) {
    /** Clear page content and set header */
    ob_clean ();
    header ( "content-type:application/x-shockwave-flash" );
    /** Execute player file to show */
    readfile ( $playerpath );
    exitAction ( '' );
  }
  /** Playerbase model class ends */
}
