<?php
/**
 * Comments table
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

/**
 * Admin comment table class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Tablecomment extends JTable {
  public $id = null;
  public $parentid = null;
  public $videoid = null;
  public $name = null;
  public $email = null;
  public $subject = null;
  public $message = null;
  public $created = null;
  public $published = null;
  
  /**
   * Function to save comment
   *
   * @param
   *          object &$db Database detail
   *          
   * @return Tablecomment
   */
  public function Tablecomment(&$db) {
    parent::__construct ( '#__hdflv_comments', 'id', $db );
  }
}
