<?php
/**
 * Player view file
 *
 * This file is to display the player and video thumb images on video home and detail page. 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** No direct access to this file */
include_once (JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'helper.php');
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
echo $this->loadTemplate('player');
echo $this->loadTemplate('videos');
