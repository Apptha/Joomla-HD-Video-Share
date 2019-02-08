<?php
/**
 * Admin videos table
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
 * Admin adminvideos table class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Tableadminvideos extends JTable {
  public $id = null;
  public $published = null;
  public $title = null;
  public $times_viewed = null;
  public $videos = null;
  public $filepath = null;
  public $videourl = null;
  public $thumburl = null;
  public $previewurl = null;
  public $hdurl = null;
  public $playlistid = null;
  public $duration = null;
  public $ordering = null;
  public $home = null;
  public $streameroption = null;
  public $streamerpath = null;
  public $postrollads = null;
  public $prerollads = null;
  public $midrollads = null;
  public $imaads = null;
  public $description = null;
  public $targeturl = null;
  public $download = null;
  public $prerollid = null;
  public $postrollid = null;
  public $memberid = null;
  public $type = null;
  public $featured = null;
  public $rate = null;
  public $ratecount = null;
  public $addedon = null;
  public $usergroupid = null;
  public $created_date = null;
  public $scaletologo = null;
  public $tags = null;
  public $seotitle = null;
  public $useraccess = null;
  public $islive = null;
  public $embedcode = null;
  public $subtitle1 = null;
  public $subtitle2 = null;
  public $subtile_lang1 = null;
  public $subtile_lang2 = null;
  
  /**
   * Function to save admin videos
   *
   * @param
   *          object &$db Database detail
   *          
   * @return Tableadminvideos
   */
  public function Tableadminvideos(&$db) {
    parent::__construct ( '#__hdflv_upload', 'id', $db );
  }
}
