<?php
/**
 * Categories module for HD Video Share
 *
 * This file is to fetch all the categories name in the module 
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

/**
 * Category Module Helper class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modcategorylist {
  /**
   * Function to get category list
   *
   * @return getcategorylist
   */
  public static function getcategorylist() {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Define fields to fetch from db */
    $fields = array ( $db->quoteName ( 'a.id' ), $db->quoteName ( 'a.category' ), 
        $db->quoteName ( 'a.seo_category' ), 'COUNT(DISTINCT b.id) AS level' );
    
    /** Select all category detials from db */
    $query->clear ()->select ( $fields )->from ( $db->quoteName ( '#__hdflv_category' ) . ' AS a' )->leftJoin ( '#__hdflv_category AS b ON a.lft > b.lft AND a.rgt < b.rgt' )->where ( $db->quoteName ( 'a.published' ) . ' = ' . $db->quote ( '1' ) )->group ( $db->escape ( 'a.id' . ' ,' . 'a.category' . ' , ' . 'a.lft' . ' , ' . 'a.rgt' ) )->order ( $db->quoteName ( 'a.lft' ) );
    $db->setQuery ( $query );
    return $db->loadObjectList ();
  }
  
  /**
   * Function to get parent category list
   *
   * @param int $id
   *          parent category id
   *          
   * @return getcategorylist
   */
  public static function getparentcategory($id) {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Select category details for the given category id */
    $query->select ( '*' )->from ( '#__hdflv_category' )->where ( $db->quoteName ( 'parent_id' ) . ' IN ( ' . $db->quote ( $id ) . ' ) AND ' . $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->order ( $db->quoteName ( 'category' ) );
    $db->setQuery ( $query );
    
    /** Return particular category details */
    return $db->loadObjectList ();
  }
}
