<?php
/**
 * Control panel template file
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

/** Import pane library */
jimport ( 'joomla.html.pane' );
$doc = JFactory::getDocument ();
$doc->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/styles.css' );

/** Style for hide Toolbar and menus */
?>
<style>
#toolbar-box,#submenu-box { display: none; }
</style>
<div class="contus-contropanel">
	<h2 class="heading">HD Video Share Control panel</h2>
</div>
<!-- Control panel first column view start here -->
<div class="cpanel-left">
	<div class="banner">
		<a href="http://www.apptha.com" target="_blank"><img
			src="components/com_contushdvideoshare/assets/apptha-banner.jpg"
			width="485" height="94" alt=""> </a>
	</div>
	<div id="cpanel">

		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=adminvideos"); ?>"
				title="membervideos"> <img
				src="components/com_contushdvideoshare/assets/member-videos.png"
				title="Member Videos" alt="Member Videos"> <span>Member Videos</span>
			</a>
		</div>

		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=memberdetails"); ?>"
				title="memberdetails"> <img
				src="components/com_contushdvideoshare/assets/member-details.png"
				title="Member Details" alt="Member Details"> <span>Member Details</span>
			</a>
		</div>

		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=adminvideos&user=admin"); ?>"
				title="adminvideos"> <img
				src="components/com_contushdvideoshare/assets/admin-video.png"
				title="Admin Videos" alt="Admin Videos"> <span>Admin Videos</span>
			</a>
		</div>


		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=category"); ?>"
				title="Category"> <img
				src="components/com_contushdvideoshare/assets/category-icon.png"
				title="Category" alt="Category"> <span>Category</span>
			</a>
		</div>


		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=settings"); ?>"
				title="Player Settings"> <img
				src="components/com_contushdvideoshare/assets/player-settings-icon.png"
				title="Player Settings" alt="Player Settings"> <span>Player Settings</span>
			</a>
		</div>

		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=sitesettings"); ?>"
				title="Site Settings"> <img
				src="components/com_contushdvideoshare/assets/site-settings-icon.png"
				title="Site Settings" alt="Site Settings"> <span>Site Settings</span>
			</a>
		</div>

		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=googlead"); ?>"
				title="Google Adsense"> <img
				src="components/com_contushdvideoshare/assets/google- adsense-icon.png"
				title="Google Adsense" alt="Google Adsense"> <span>Google Adsense</span>
			</a>
		</div>

		<div class="icon">
			<a
				href="<?php echo JRoute::_("index.php?option=com_contushdvideoshare&layout=ads"); ?>"
				title="Ads"> <img
				src="components/com_contushdvideoshare/assets/ads-icon.png"
				title="ads" alt="ads"> <span>Video Ads</span>
			</a>
		</div>


	</div>
</div>
<!-- Control panel first column end -->

<div style="width: 50%; float: right;">
<?php /** Control panel second column view start here */
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
  $membervideos = $this->controlpaneldetails ['membervideos'];
  $popularvideos = $this->controlpaneldetails ['popularvideos'];
  $latestvideos = $this->controlpaneldetails ['latestvideos'];
  $pane = JPane::getInstance ( 'sliders' );
  echo $pane->startPane ( 'pane' );
  echo $pane->startPanel ( 'Welcome to HD Video Share 3.8', 'panel1' );
  ?>
		<div class="main-text">
		<div class="text" style="text-align: justify; width: auto !important;">
			HD Video Share is an extension for Joomla, you can create your own
			video sharing site in matter of minutes. It offers a complete video
			sharing solutions with advanced features such as featured videos,
			popular videos, recent videos, related videos, video search, video
			categories, ratings, comments, social sharing and more. It supports
			more Joomla templates by default and few paid templates specially
			made for supporting HD Video Share which are available in apptha.com
			for purchase.</div>
		<div class="text">
			<a href="http://www.apptha.com/forum/viewforum.php?f=45"
				target="_blank"> Support</a> <a
				href="https://www.apptha.com/downloadable/download/sample/sample_id/9/">Documentation</a>
		</div>
	</div>
		<?php
  echo $pane->endPanel ();
  echo $pane->startPanel ( 'Member Details', 'panel2' );
  ?>
		<table class="adminlist">
		<thead>
			<tr>
				<th>Member Name</th>
				<th><strong>Videos count</strong></th>


			</tr>
		</thead>
		<tbody>
				<?php
  foreach ( $membervideos as $details ) {
    ?>
					<tr>
				<td><?php echo $details->username; ?>
						</td>
				<td class="center"><?php echo $details->count; ?>
						</td>
				<?php
  }
  ?>
				</tr>
		</tbody>
	</table>
		<?php
  echo $pane->endPanel ();
  echo $pane->startPanel ( 'Top 5 Popular Videos', 'panel3' );
  ?>
		<table class="adminlist">
		<thead>
			<tr>
				<th>Video Title</th>
				<th><strong>Times Viewed</strong></th>
			</tr>
		</thead>
		<tbody>
	<?php
  foreach ( $popularvideos as $details ) {
    ?>
		<tr>
				<td>
				<?php
    $route = JURI::Base () . "index.php?option=com_contushdvideoshare&layout=adminvideos&task=editvideos&user=admin&cid[]=" . $details->id;
    ?>
				<a href="
					<?php
    echo $route;
    ?>" target="_blank">
						<?php
    echo $details->title;
    ?>
				</a>
				</td>
				<td class="center"><?php echo $details->times_viewed; ?>
			</td>
	<?php
  }
  ?>
				</tr>
		</tbody>
	</table>
		<?php
  echo $pane->endPanel ();
  echo $pane->startPanel ( 'Last 5 Added Videos', 'panel4' );
  ?>
		<table class="adminlist">
		<thead>
			<tr>
				<th>Video Title</th>
				<th><strong>Created Date</strong></th>
			</tr>
		</thead>
		<tbody>
	<?php
  foreach ( $latestvideos as $details ) {
    ?>
		<tr>
				<td>
			<?php
    $route = JURI::Base () . "index.php?option=com_contushdvideoshare&layout=adminvideos&task=editvideos&user=admin&cid[]=" . $details->id;
    ?> 
			<a href="<?php echo $route; ?>" target="_blank">
				<?php
    echo $details->title;
    ?>
			</a>
				</td>
				<td class="center"><?php echo $details->created_date; ?>
		</td>
	<?php
  }
  ?>
	</tr>
		</tbody>
	</table>
		<?php
  echo $pane->endPanel ();
  echo $pane->endPane ();
} else {
  ?>
		<div class="well well-small">
		<div class="module-title nav-header">Welcome to HD Video Share</div>
		<div class="row-striped">
			<div class="row-fluid">
				<div class="span9"
					style="text-align: justify; width: auto !important;">

					<div class="row-title">HD Video Share is an extension for Joomla,
						you can create your own video sharing site in matter of minutes.
						It offers a complete video sharing solutions with advanced
						features such as featured videos, popular videos, recent videos,
						related videos, video search, video categories, ratings, comments,
						social sharing and more. It supports more Joomla templates by
						default and few paid templates specially made for supporting HD
						Video Share which are available in apptha.com for purchase.</div>
				</div>
				<div class="text" style="float: left;">
					<a href="http://www.apptha.com/forum/viewforum.php?f=45"
						target="_blank"> Support</a> <a
						href="https://www.apptha.com/downloadable/download/sample/sample_id/9/">Documentation</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}
/** ontrol panel second column view end */
?>
</div>
