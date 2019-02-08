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

/** Define DS */
if (! defined ( 'DS' )) {
  define ( 'DS', DIRECTORY_SEPARATOR );
}

/** Include categories module helper file */
require_once dirname ( __FILE__ ) . DS . 'helper.php';

/** Check joomla version for categories module */
$jversion = getJoomlaVersion ();

$class = $params->get ( 'moduleclass_sfx' );

/** Get language for categories module */
if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  $jlang = JFactory::getLanguage ();
  $jlang->load ( COMPONENT , JPATH_SITE, 'en-GB', true );
  $jlang->load ( COMPONENT , JPATH_SITE, null, true );
}

/** Get categories list */
$result           = Modcategorylist::getcategorylist ();
/** Get category settings from component helper*/
$result_settings  = getSiteSettings ();
/** Include layout for categories module */
require JModuleHelper::getLayoutPath ( 'mod_hdvideosharecategories' );
