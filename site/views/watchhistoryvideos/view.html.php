<?php
/**
 * WatchHistory videos view for HD Video Share
 *
 * This file is to display history of videos viewed by the users 
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
 * Featuredvideos view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewwatchhistoryvideos extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewfeaturedvideos object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    global $appObj;
    $userId = (int) getUserID();
    if (!empty($userId)) {
    /** Get featured video model */
    $model = $this->getModel ();
    /** Function call for fetching featured videos */
    $HistoryVideos = $model->getvideoHistory();
    $this->assignRef ( 'HistoryVideos', $HistoryVideos );
    /** Function call for fetching featured videos settings */
    $historyvideosrowcol = getpagerowcol();    
    $this->assignRef ( 'historyvideosrowcol', $historyvideosrowcol );
    /** fucntion call to get the history state of the user */
    $HistoryState = $model->HistoryState();
    $this->assignRef ('HistoryState', $HistoryState );
    
    /** Function call to fetch Itemid for watch history videos view */
    $Itemid = getmenuitemid_thumb ( 'watchhistoryvideos', '' );
    /** Assign reference to itemid for watch history video */
    $this->assignRef ( 'Itemid', $Itemid );
    
    parent::display ();
    } else {
      $currentURL = JUri::getInstance();
      $loginURL    = JURI::base () . "index.php?option=com_users&amp;view=login&return=" . base64_encode ( $currentURL );
      $appObj->redirect($loginURL, JText::_('HDVS_LOGIN_FIRST'),MESSAGE);
    }
  }
}
