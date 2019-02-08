<?php
/**
 * Google adsense view
 *
 * This file is to display google adsense on the player
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
/** Clean page content for adsense page */ 
ob_clean();
/** Display adsense detials */
echo $this->details;
/** Include show ads js for adsense view */
?>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
<?php /** Exit google adsense page */  
exitAction (''); ?>
