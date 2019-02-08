<?php
/**
 * Amazon s3 bucket config file
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
include_once (JPATH_COMPONENT_SITE . DS . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
$dispenable = getSiteSettings ();
$bucket = '';

if (isset($dispenable['amazons3']) && $dispenable['amazons3'] == 1) {
	if (isset($dispenable['amazons3name'])) {
		$bucket = $dispenable['amazons3name'];
	}

	if (!class_exists('S3')) {
		require_once 'S3.php';
	}

	## AWS access info
	if (!defined('awsAccessKey')) {
		if (isset($dispenable['amazons3accesskey'])) {
			define('awsAccessKey', $dispenable['amazons3accesskey']);
		}
	}

	if (!defined('awsSecretKey')) {
		if (isset($dispenable['amazons3accesssecretkey_area'])) {
			define('awsSecretKey', $dispenable['amazons3accesssecretkey_area']);
		}
	}

	// Instantiate the class
	$s3 = new S3(awsAccessKey, awsSecretKey);
	$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
}
