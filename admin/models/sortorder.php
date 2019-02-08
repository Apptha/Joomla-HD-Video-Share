<?php
/**
 * sort order model file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */
/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );

/**
 * Admin sortorder model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModelsortorder extends ContushdvideoshareModel {
  /**
   * Function to save sort order
   *
   * @param string $type
   * @return videosortordermodel
   */
  public function sortorder_function($type) {
     
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** Varaibale initialization for sortorder action */
    $sql = '';
    $listitemArray = array ();
    $listitem = JRequest::getvar ( 'listItem' );
    $listitemArray    = array_filter($listitem, 'isNumber');
    /** Get page num and type */
    $pagenum = JRequest::getvar ( 'pagenum' );
        
    if(!empty ($listitemArray)) {
      /** Implode list item array into string */
      $ids      = implode ( ',', $listitemArray );
  
      /** Check type is video or playlist */
      switch( $type ) {
        case 'video':
          /** Update video ordering in database tables */
          $query->clear ()->update ( $db->quoteName ( '#__hdflv_upload' ) );
          break;
        case 'cat':
          /** Update playlist ordering in database tables */
          $query->clear ()->update ( $db->quoteName ( '#__hdflv_category' ) );
          break;
        default:
          break;
      }
      /** Calculate page values */
      if (isset ( $pagenum )) {
        $page = (20 * ($pagenum - 1));
      }
      
      foreach ( $listitem as $key => $value ) {
        $listitems [$key + $page] = $value;
      }
      foreach ( $listitems as $position => $item ) {
        $sql .= sprintf ( "WHEN %d THEN %d ", $item, $position );
      }
      
      $query->set ( $db->quoteName ( 'ordering' ) . ' = CASE id ' . $sql . ' END' )->where ( $db->quoteName ( 'id' ) . ' IN (' . $ids . ')' );
      $db->setQuery ( $query );
      $db->query ();
    }
    exitAction ( '' );
  }
}
