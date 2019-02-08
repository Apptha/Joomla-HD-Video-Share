<?php
/**
 * Search module for HD Video Share
 *
 * This file is to display Search module 
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

/** Define DS */
if (! defined ( 'DS' )) {
  define ( 'DS', DIRECTORY_SEPARATOR );
}
define ('MOD_VIDEOSHARESEARCH', 'mod_hdvideosharesearch');

/** Include helper file for this module */
require_once dirname ( __FILE__ ) . DS . 'helper.php';

/** Check and get the joomla version for search module*/
$version = getJoomlaVersion ();

/** Check joomla version for search module */
if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  /** Load language files based on the joomla version */
  $jlang = JFactory::getLanguage ();
  $jlang->load ( MOD_VIDEOSHARESEARCH, JPATH_SITE, $jlang->get ( 'tag' ), true );
  $jlang->load ( MOD_VIDEOSHARESEARCH, JPATH_SITE, null, true );
}

/** Get module calss suffix value for search module */
$class        = $params->get ( 'moduleclass_sfx' );

/** Load template of this module */
require JModuleHelper::getLayoutPath ( MOD_VIDEOSHARESEARCH );
