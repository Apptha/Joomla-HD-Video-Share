<?php
/**
 * View file to display Impression and Click count of the ads
 *
 * This file is to get impression and click count of the ad
 * Impression count is based on how many times the ad is played on the player
 * It will be stored in database for get stats about the ad.
 * Click count is based on how many time user clicked the ad and redirected to the target URL
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

/** Import Joomla view library */
jimport ( 'joomla.application.component.view' );

/**
 * Impressionclicks view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewimpressionclicks extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewimpressionclicks object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    /** Get model for impression clicks view */
    $model = $this->getModel ();
    /** Get data for impression clicks */
    $model->impressionclicks ();
  }
}
