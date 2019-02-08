<?php
/**
 * User video view file
 *
 * This file is to display logged in user videos
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
 * Myvideos view class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareViewmyvideos extends ContushdvideoshareView {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types
   *          
   * @return ContushdvideoshareViewmyvideos object to support chaining.
   *        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {
    global $appObj;
    
    /** Get user object */
    $userID = getUserID ();
    
    /** Check user id is empty */
    if ($userID == '') {
      /** Set redirect Url to player page */
      $currentURL = JUri::getInstance();
      $loginURL    = JURI::base () . "index.php?option=com_users&amp;view=login&return=" . base64_encode ( $currentURL );
      $appObj->redirect($loginURL, JText::_('HDVS_LOGIN_FIRST'),MESSAGE);
    } else {
      /** Get model for myvideos */
      $model = $this->getModel ();
      
      /** Function call for fetching member videos */
      $deletevideos = $model->getmembervideo ();
      /** Assign reference for delete videos */
      $this->assignRef ( 'deletevideos', $deletevideos ['rows'] );
      /** Assign reference for allow upload settings */
      $this->assignRef ( 'allowupload', $deletevideos ['row1'] );
      
      /** Function call to fetch Itemid for myvideos page */
      $Itemid = getmenuitemid_thumb ( 'player', '' );
      /** Assign referene to itemid for myvideos page */
      $this->assignRef ( 'Itemid', $Itemid );
      
      /** Function call for fetching my videos settings */
      $myvideorowcol = getpagerowcol ();
      /** Assign reference for myvideos setting */
      $this->assignRef ( 'myvideorowcol', $myvideorowcol );
      parent::display ();
    }
  }
}
