<?php
/**
 * Google adsense model for HD Video Share
 *
 * This file is to fetch google adsense details from database and
 * display the ad on the player
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
 * Googlead model class
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharegooglead extends ContushdvideoshareModel {
  /**
   * Function to get google adsense
   *
   * @return array
   */
  public function getgooglead() {
    /** Get db connection for adsense model */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** Query to get goole adsense details */
    $query->select ( '*' )->from ( '#__hdflv_googlead' )->where ( $db->quoteName ( 'publish' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( '1' ) );
    $db->setQuery ( $query );
    /** Get adsense details from db */
    $fields = $db->loadObjectList ();
    /** Return adsense details */
    return html_entity_decode ( stripcslashes ( $fields [0]->code ) );
  }
}
