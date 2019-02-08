<?php
/**
 * Watchlater view for HD Video Share
 *
 * This file is to display watchlater 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct acesss */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import Joomla view library */
jimport('joomla.application.component.view');

/**
 * Featuredvideos view class.
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class ContushdvideoshareViewwatchlater extends ContushdvideoshareView {
	/**
	 * Function to set layout and model for view page.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   boolean  $urlparams  An array of safe url parameters and their variable types
	 *
	 * @return  ContushdvideoshareViewwatchlater		This object to support chaining.
	 * 
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false) {
	  global $appObj;
	  $userId = (int) getUserID();
	  if(!empty($userId)) {
		$model = $this->getModel();

		/** Function call for fetching watch later videos */
		$watchlater = $model->getwatchlater();
		$this->assignRef('watchlater', $watchlater);

		/** Function call for fetching watch later videos settings */
		$watchlaterrowcol = getpagerowcol ();
		$this->assignRef('watchlaterrowcol', $watchlaterrowcol);
		
		/** Function call to fetch Itemid for watch later videos view */
		$Itemid = getmenuitemid_thumb ( 'watchlater', '' );
		/** Assign reference to itemid for watch later video */
		$this->assignRef ( 'Itemid', $Itemid );
		
		parent::display();
	  } else {
        $currentURL = JUri::getInstance();
        $loginURL    = JURI::base () . "index.php?option=com_users&amp;view=login&return=" . base64_encode ( $currentURL );
        $appObj->redirect($loginURL, JText::_('HDVS_LOGIN_FIRST'),MESSAGE);
      }
	}
}
