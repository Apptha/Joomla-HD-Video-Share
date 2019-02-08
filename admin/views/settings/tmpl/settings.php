<?php
/**
 * Player settings template file
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

/** Get player settings from db */
$rs_editsettings = $rs_showsettings = $this->playersettings;
/** Unserialize player settings value*/
$player_colors = unserialize ( $rs_editsettings [0]->player_colors );
$player_icons = unserialize ( $rs_editsettings [0]->player_icons );
$player_values = unserialize ( $rs_editsettings [0]->player_values );
JHTML::_ ( 'behavior.tooltip' );

/** Include style for joomla other than 3.0 version */
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
<style>
fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
table.admintable td.key { background-color: #F6F6F6; text-align: left; width: auto; color: #666; font-weight: bold;
border-bottom: 1px solid #E9E9E9; border-right: 1px solid #E9E9E9; }
fieldset label,fieldset span.faux-label { float: none; clear: left; display: block; margin: 5px 0; }
</style>
<?php } else {
/** Include style for joomla 3.0 version */ ?>
<style type="text/css">
fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
table.admintable td.key {  }
table.adminlist .radio_algin input[type="radio"] { margin: 0 5px 0 0; }
fieldset label,fieldset span.faux-label { float: none; clear: left; display: block; margin: 5px 0; }
</style>
<?php } ?>
<script type="text/javascript">
	// Function to hide and show Google Analytics ID
	function Toggle(theDiv) {
		if (theDiv == "shows") {
			document.getElementById("show").style.display = '';
			document.getElementById("show1").style.display = '';
		} else {
			document.getElementById("show").style.display = "none";
			document.getElementById("show1").style.display = "none";
		}
	}
	// Function to hide and show Intermediate Ad
	function Toggle1(theDiv) {
		if (theDiv == "showss") {
			document.getElementById("imashow").style.display = '';
			document.getElementById("imashow1").style.display = '';
		} else {
			document.getElementById("imashow").style.display = "none";
			document.getElementById("imashow1").style.display = "none";
		}
	}
<?php /** Validation for player width and height */
if (version_compare ( JVERSION, '1.6.0', 'ge' )) { 
/** For Joomla versions 1.6, 1.7, 2.5 */ ?>
   Joomla.submitbutton = function(pressbutton) { 
<?php } else { 
/** For Joomla versions 1.5 */ ?>
function submitbutton(pressbutton){ 
<?php } ?>
	if (pressbutton){
	var playerWidth = document.getElementById('player_width').value;
			playerWidth = parseInt(playerWidth);
			var playerHeight = document.getElementById('player_height').value;
			playerHeight = parseInt(playerHeight);
			var googleana_visible = document.getElementById('googleana_visible').checked;
			var googleanalyticsID = document.getElementById('googleanalyticsID').value;
			var volume = document.getElementById('volume').value;
			if (!playerWidth || !playerHeight) {
	alert('Please enter minimum width and height value for player');
			return false;
			}
	if (googleana_visible == 1 && googleanalyticsID == '') {
	alert('Please Enter Google Analytics ID');
			return false;
			}
	if (!volume.match(/^[0-9]+$/)) {
	alert('Enter only numbers');
			return false;
	}
			else if(volume>100 || volume<0){
				alert('Enter volume value between 1 to 100%');
			return false;
	}
	}
	submitform(pressbutton);
			return;
}
</script>
<?php /** Form For Edit Player Settings Start Here */ ?>
<form action="index.php?option=com_contushdvideoshare&layout=settings" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div style="position: relative;">
		<fieldset class="adminform">
<?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
				<legend>Player Settings</legend>
<?php } else { ?>
				<h2>Player Settings</h2>
<?php } ?>
			<table
				<?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="adminlist table table-striped"';
    } else {
      echo 'class="admintable adminlist" ';
    } ?>>
				<tr> <?php /** Display field to enter buffer time */ ?>
					<td class="key"><?php echo JHTML::tooltip('Recommended value is 3', '', '', 'Buffer Time'); ?></td>
					<td><input type="text" name="buffer"
						value="<?php
      if (isset ( $player_values ['buffer'] )) {
        echo $player_values ['buffer'];
      }
      ?>" /> secs</td> <td class="key"> <?php /** Display option to select skin auto hide */ ?>
      <?php echo JHTML::tooltip('Select Enable to auto hide skin', '', '', 'Skin Auto Hide'); ?></td>
					<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="skin_autohide" <?php echo radioButtonCheck ($player_icons ['skin_autohide']); 
       ?> value="1" />Enable <input type="radio" name="skin_autohide"
						<?php echo radioButtonUnCheck ($player_icons ['skin_autohide']); ?> value="0" />Disable</td> </tr>
				<tr> <td class="key"> <?php echo JHTML::tooltip ( 'Minimum width of the player is 300px.To have smaller than 300px then have to disable couple of controls (eg: Timer, Zoom).', '', '', 'Width' );
      ?></td> <?php /** Display field to enter player width */ ?> 
      <td><input type="text" id="player_width" name="width"
						value="<?php if (isset ( $player_values ['width'] )) {
        echo $player_values ['width'];
      } ?>" /> px</td> <td class="key">
						<?php echo JHTML::tooltip('Recommended value is 400', '', '', 'Height'); ?></td>
						<?php /** Display field to enter player height */ ?>
					<td><input type="text" name="height"
						value="<?php if (isset ( $player_values ['height'] )) {
        echo $player_values ['height'];
      } ?>" id="player_height" /> px</td>
      <?php /** Display field to enter stage color */ ?>
				</tr> <tr> <td class="key"> <?php echo JHTML::tooltip ( 'Set the background color for the player in the format ffffff', '', '', 'Stage Color' );
      ?></td> <td>#<input type="text" name="stagecolor"
						value="<?php if (isset ( $player_values ['stagecolor'] )) {
        echo $player_values ['stagecolor'];
      } ?>" /> </td> 
      <?php /** Display field to enter ffmpeg path */ ?>
					<td class="key"> <?php echo JHTML::tooltip ( 'Enter FFMpeg Binary Path', '', '', 'FFMpeg Binary Path' );
      ?></td> <td><input style="width: 150px;" type="text" name="ffmpegpath"
						value="<?php if (isset ( $player_values ['ffmpegpath'] )) {
        echo $player_values ['ffmpegpath'];
      } ?>" /></td> </tr> <tr>
      <?php /** Display field to select screen scale */ ?>
					<td class="key"> <?php echo JHTML::tooltip ( 'Select Normal Screen Scale', '', '', 'Normal Screen Scale' );
      ?></td> <td><select name="normalscale"> <option value="0" id="20">Aspect Ratio</option>
							<option value="1" id="21">Original Size</option>
							<option value="2" id="22">Fit to Screen</option>
					</select>  <?php if (isset ( $player_values ['normalscale'] ) && $player_values ['normalscale']) {
        echo '<script>document.getElementById("2' . $player_values ['normalscale'] . '").selected="selected"; </script>';
      } ?> </td> <td class="key">
						<?php echo JHTML::tooltip('Select Full Screen Scale', '', '', 'Full Screen Scale'); ?></td>
					<td><select name="fullscreenscale">
							<option value="0" id="10" name=0>Aspect Ratio</option>
							<option value="1" id="11" name=1>Original Size</option>
							<option value="2" id="12" name=2>Fit to Screen</option>
					</select> <?php if (isset ( $player_values ['fullscreenscale'] ) && $player_values ['fullscreenscale']) {
  echo '<script>document.getElementById("1' . $player_values ['fullscreenscale'] . '").selected="selected"; </script>';
} ?> </td> </tr>
				<tr> <td class="key"> <?php echo JHTML::tooltip ( 'Fullscreen button can be enable/disabled from here', '', '', 'Full Screen' );
      ?></td> <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="fullscreen" <?php echo radioButtonCheck ($player_icons ['fullscreen']);
      ?> value="1" />Enable <input type="radio" name="fullscreen" <?php echo radioButtonUnCheck ($player_icons ['fullscreen']);
      ?> value="0" />Disable</td> <td class="key"> <?php
        echo JHTML::tooltip ( 'Option to play the videos one by one continuously without clicking on next video', '', '', 'Autoplay' );
      ?></td> <td <?php /** Display field to select autoplay option */
      if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="autoplay" 
      <?php echo radioButtonCheck ($player_icons ['autoplay']); ?> value="1" />Enable <input type="radio" name="autoplay"
						<?php echo radioButtonUnCheck ($player_icons ['autoplay']); ?> value="0" />Disable</td> </tr>
				<tr> <td class="key"><?php echo JHTML::tooltip('Zoom button on the player control can be disable / enable here', '', '', 'Zoom'); ?></td>
					<td <?php /** Display field to select zoom option */
      if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="zoom" <?php echo radioButtonCheck ($player_icons ['zoom']); ?>  value="1" />Enable 
      <input name="zoom" type="radio" <?php echo radioButtonUnCheck ($player_icons ['zoom']); ?> value="0" />Disable</td>
      <?php /** Display field to select player timer option */ ?>
					<td class="key">
						<?php echo JHTML::tooltip ( 'Option to set enable / disable timer control on player', '', '', 'Timer' );
      ?></td> 
      <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="timer" value="1" <?php echo radioButtonCheck ($player_icons ['timer']); ?> />Enable  <input name="timer" value="0" type="radio" 
      <?php echo radioButtonUnCheck ($player_icons ['timer']); ?>  />Disable</td>
				</tr> <tr> <td class="key"> <?php echo JHTML::tooltip('Recommended value is 50', '', '', 'Volume'); ?></td>
					<?php /** Display field to enable volume option */ ?>
					<td><input type="text" name="volume" id="volume"
						value="<?php if (isset ( $player_values ['volume'] )) {
        echo $player_values ['volume'];
      } ?>" /> %</td> <td class="key">
      <?php /** Display field to enter login page url */ ?>
						<?php echo JHTML::tooltip('Enter Login Page URL', '', '', 'Login Page URL'); ?></td>
					<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="text" name="login_page_url" 
      value="<?php if (isset ( $player_icons ['login_page_url'] )) {
        echo $player_icons ['login_page_url'];
      } ?>" /></td> </tr>
				<tr>
				<?php /** Display field to enable share icons within the player */ ?>
					<td class="key">
						<?php echo JHTML::tooltip('Share button on the player can be enabled/disabled from here', '', '', 'Share Button'); ?></td>
					<td  <?php
      if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="shareurl"
						<?php echo radioButtonCheck ($player_icons ['shareurl']); ?>  value="1" />Enable <input type="radio" name="shareurl"
						<?php echo radioButtonUnCheck ($player_icons ['shareurl']); ?>  value="0" />Disable</td> <td class="key">
						<?php echo JHTML::tooltip('Option to select related videos view', '', '', 'Related Videos View'); ?></td>
					<td><select name="relatedVideoView"> <option value="side" id="side">Side</option>
							<option value="center" id="center">Center</option>
					</select>  <?php if (isset ( $player_values ['relatedVideoView'] ) && $player_values ['relatedVideoView']) {
        echo '<script>document.getElementById("' . $player_values ['relatedVideoView'] . '").selected="selected"; </script>';
      } ?> </td> </tr>
				<tr>
					<td class="key">
						<?php /** Display field to enable playlist autoplay */      
      echo JHTML::tooltip ( 'Option to play all the videos from playlist continuously', '', '', 'Playlist Autoplay' );
      ?></td> <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="playlist_autoplay"
						<?php echo radioButtonCheck ($player_icons ['playlist_autoplay']); ?>  value="1" />Enable <input type="radio" name="playlist_autoplay"
						<?php echo radioButtonUnCheck ($player_icons ['playlist_autoplay']); ?> value="0" />Disable</td>
					<td class="key">
					<?php /** Display field to enable hd default option */ ?>
						<?php echo JHTML::tooltip('Option to set the HD videos to play by default', '', '', 'HD Default'); ?>
					</td> <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="hddefault"
						<?php echo radioButtonCheck($player_icons ['hddefault']); ?>  value="1" />Enable <input type="radio" name="hddefault"
						<?php echo radioButtonUnCheck ($player_icons ['hddefault']); ?> value="0" />Disable</td> </tr>
						<?php /** Display field to open playlist */ ?>
				<tr> <td class="key"> <?php echo JHTML::tooltip('Set playlist to open / close always by enable / disable this option', '', '', 'Playlist Open'); ?></td>
					<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      }
      ?>><input type="radio" name="playlist_open"
						<?php echo radioButtonCheck ($player_icons ['playlist_open']);
      ?> value="1" />Enable <input type="radio" name="playlist_open"
						<?php echo radioButtonUnCheck ($player_icons ['playlist_open']);
      ?> value="0" />Disable</td>
      <?php /** Display field to enable related videos within the player */ ?>
					<td class="key"><?php echo JHTML::tooltip ( 'Option to set enable/disable related videos display within player', '', '', 'Related Videos' );
    ?></td> <td><select name="related_videos">
							<option value="1" id="1">Enable</option>
							<option value="2" id="2">Disable</option>
					</select> <?php if (isset ( $player_values ['related_videos'] ) && $player_values ['related_videos']) {
  echo '<script>document.getElementById("' . $player_values ['related_videos'] . '").selected="selected"; </script>';
} ?> </td> </tr> <tr> <td class="key">
<?php /** Display field to select default preview image to be shown */ ?>
						<?php echo JHTML::tooltip ( 'Option to enable/disable default preview image when it is not available', '', '', 'Display Default Image' );
      ?></td> <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="imageDefault"
						<?php echo radioButtonCheck ($player_icons ['imageDefault']);?> value="1" />Enable <input type="radio" 
						name="imageDefault" <?php  echo radioButtonUnCheck ($player_icons ['imageDefault']); ?> value="0" />Disable</td> <td class="key">
						<?php echo JHTML::tooltip ( 'Option to enable/disable Embed option on player', '', '', 'Embed visible' );
      ?></td> 		<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>> <?php /** Display field to select embed visible option */ ?>
      <input type="radio" name="embedVisible" <?php echo radioButtonCheck ($player_icons ['embedVisible']); ?> value="1" />Enable <input type="radio" name="embedVisible"
						<?php  echo radioButtonUnCheck ($player_icons ['embedVisible']); ?> value="0" />Disable</td> </tr>
				<tr> <td class="key">
						<?php echo JHTML::tooltip ( 'Option to enable/disable Iframe option on player page', '', '', 'Iframe visible' );
      ?></td> 		<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>> <?php /** Display field to select iframe visible option */ ?>
      <input type="radio" name="iframeVisible" <?php echo radioButtonCheck ($player_icons ['iframeVisible']); ?> value="1" />Enable <input type="radio" name="iframeVisible"
						<?php  echo radioButtonUnCheck ($player_icons ['iframeVisible']); ?> value="0" />Disable</td> <td class="key"> <?php echo JHTML::tooltip('Option to enable/disable Download option on player', '', '', 'Enable Download'); ?></td>
					<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="enabledownload" <?php echo radioButtonCheck ($player_icons ['enabledownload']); ?> value="1" />Enable <input type="radio" name="enabledownload" 
      <?php echo radioButtonUnCheck ($player_icons ['enabledownload']); ?> value="0" />Disable</td> </tr> <tr> <td class="key">
						<?php /** Display field to select email option within the player */ 
						echo JHTML::tooltip ( 'Option to enable/disable email option to be displayed on player', '', '', 'Display Email' );
      ?></td> <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="emailenable" 
      <?php echo radioButtonCheck ($player_icons ['emailenable']); ?> value="1" />Enable <input type="radio" name="emailenable"
						<?php echo radioButtonUnCheck ($player_icons ['emailenable']); ?> value="0" />Disable</td>  <td class="key">
						<?php /** Display field to enable description on player */ 
						echo JHTML::tooltip ( 'Option to enable/disable Description to be displayed on player', '', '', 'Description visible' );
      ?></td>  <td 
      <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="showTag" <?php echo radioButtonCheck ($player_icons ['showTag']); ?> value="1" />Enable <input type="radio" name="showTag"
						<?php /** Display field to enable tags */  
						echo radioButtonUnCheck ($player_icons ['showTag']); ?> value="0" />Disable</td> </tr> <tr> <td class="key">
						<?php echo JHTML::tooltip ( 'Option to enable/disable Volume control to be displayed on player', '', '', 'Volume visible' );
      ?></td><td <?php
      if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="volumecontrol"
						<?php echo radioButtonCheck ($player_icons ['volumecontrol']);  ?> value="1" />Enable <input type="radio" name="volumecontrol"
						<?php echo radioButtonUnCheck ($player_icons ['volumecontrol']); 
      ?> value="0" />Disable</td> <?php /** Display field to enable progress bar */ ?>
					<td class="key"> <?php echo JHTML::tooltip ( 'Option to enable/disable Progress bar to be displayed on player', '', '', 'Display Progress bar' );
      ?></td>  <td <?php
      if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="progressControl" <?php echo radioButtonCheck ($player_icons ['progressControl']); ?> 
      value="1" />Enable <input type="radio" name="progressControl" <?php echo radioButtonUnCheck ($player_icons ['progressControl']); ?> value="0" />Disable</td>
				</tr> <tr>	<td class="key"> <?php /** Display field to enable skin */ ?>
						<?php echo JHTML::tooltip ( 'Option to enable/disable Skin to be displayed on player', '', '', 'Display Skin' );
      ?></td> <td <?php
      if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="skinvisible" <?php echo radioButtonUnCheck ($player_icons ['skinvisible']); ?> value="0" />Enable <input type="radio" name="skinvisible"
						<?php echo radioButtonCheck ($player_icons ['skinvisible']); ?> value="1" />Disable</td> 
					<td class="key"> <?php /** Display field to set skin opacity */ 
					echo JHTML::tooltip('Option to set Skin Opacity', '', '', 'Skin Opacity'); ?></td>
					<td><input type="text" id="skin_opacity" name="skin_opacity"
						value="<?php if (isset ( $player_values ['skin_opacity'] )) {
        echo $player_values ['skin_opacity'];
      } ?>" /> ( Range from 0 to 1 )</td> </tr> <tr><td class="key">
<?php /** Display field to enter subtitle text color */    
echo JHTML::tooltip ( 'Option to set Subtitle Text Color', '', '', 'Subtitle Text Color' );
      ?></td> <td>#<input type="text" name="subTitleColor" value="<?php
      if (isset ( $player_values ['subTitleColor'] )) {
        echo $player_values ['subTitleColor'];
      } ?>" id="subTitleColor" /></td>
				
					<td class="key">
						<?php /** Display field to enter subtitle background color */
      echo JHTML::tooltip ( 'Option to set Subtitle Background Color', '', '', 'Subtitle Background Color' );
      ?></td>
					<td>#<input type="text" id="subTitleBgColor" name="subTitleBgColor"
						value="<?php
      if (isset ( $player_values ['subTitleBgColor'] )) {
        echo $player_values ['subTitleBgColor'];
      }
      ?>" /></td></tr> <tr>
					<td class="key">
						<?php /** Display field to enter subtitle font family */      
      echo JHTML::tooltip ( 'Option to set Subtitle Font Family', '', '', 'Subtitle Font Family' );
      ?></td>
					<td><input type="text" name="subTitleFontFamily"
						value="<?php
      if (isset ( $player_values ['subTitleFontFamily'] )) {
        echo $player_values ['subTitleFontFamily'];
      }
      ?>"
						id="subTitleFontFamily" /></td>
				
					<td class="key">
						<?php /** Display field to enter subtitle font size */      
      echo JHTML::tooltip ( 'Option to set Subtitle Font Size', '', '', 'Subtitle Font Size' );
      ?></td>
					<td><input type="text" id="subTitleFontSize"
						name="subTitleFontSize"
						value="<?php
      if (isset ( $player_values ['subTitleFontSize'] )) {
        echo $player_values ['subTitleFontSize'];
      }
      ?>" /></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php /** Player Settings Fields End */ ?>

	<?php /** Player color Settings Start Here */ ?> 
	<div style="position: relative;">
		<fieldset class="adminform">
<?php
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
  ?>
				<legend>Player Color Settings</legend>
<?php
} else {
  ?>
				<h2>Player Color Settings</h2>
<?php
}
?>
			<table
				<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="adminlist table table-striped"';
    } else {
      echo 'class="admintable adminlist"';
    }
    ?>>
				<tr>
					<td>
						<?php /** Display field to enter the color for Share Popup Header Color */       
      echo JHTML::tooltip ( 'Enter the color for Share Popup Header Color', '', '', 'Share Popup Header Color' );
      ?></td>
					<td>#<input name="sharepanel_up_BgColor" id="sharepanel_up_BgColor"
						maxlength="100"
						value="<?php
      echo $player_colors ['sharepanel_up_BgColor'];
      ?>">
					</td>
				</tr>
				<tr>
					<td>
						<?php /** Display field to Enter the color for Share Popup Background Color */       
      echo JHTML::tooltip ( 'Enter the color for Share Popup Background Color', '', '', 'Share Popup Background Color' );
      ?></td>
					<td>#<input name="sharepanel_down_BgColor"
						id="sharepanel_down_BgColor" maxlength="100"
						value="<?php
      echo $player_colors ['sharepanel_down_BgColor'];
      ?>">
					</td>
				</tr>
				<tr>
					<td>
						<?php  /** Display field to Enter the color for Share Popup Text Color */
      echo JHTML::tooltip ( 'Enter the color for Share Popup Text Color', '', '', 'Share Popup Text Color' );
      ?></td>
					<td>#<input name="sharepaneltextColor" id="sharepaneltextColor"
						maxlength="100"
						value="<?php
      echo $player_colors ['sharepaneltextColor'];
      ?>">
					</td>
				</tr>
				<tr>
					<td> <?php /** Display field to Enter the color for Send Button Color */ ?>
						<?php echo JHTML::tooltip('Enter the color for Send Button Color', '', '', 'Send Button Color'); ?></td>
					<td>#<input name="sendButtonColor" id="sendButtonColor" maxlength="100" value="<?php
      echo $player_colors ['sendButtonColor'];
      ?>"> </td> </tr> <tr> <td> <?php /** Display field to Enter the color for Send Button Text Color */
      echo JHTML::tooltip ( 'Enter the color for Send Button Text Color', '', '', 'Send Button Text Color' );
      ?></td> <td>#<input name="sendButtonTextColor" id="sendButtonTextColor"
						maxlength="100" value="<?php echo $player_colors ['sendButtonTextColor']; ?>">
					</td> </tr> <tr> <td> <?php  /** Display field to Enter the color for player text Color */     
      echo JHTML::tooltip ( 'Enter the color for Player Text Color', '', '', 'Player Text Color' );
      ?></td> <td>#<input name="textColor" id="textColor" maxlength="100"
						value="<?php echo $player_colors ['textColor']; ?>"> </td>
						<?php /** Display field to Enter the color for skin background Color */ ?>
				</tr> <tr> <td> <?php echo JHTML::tooltip ( 'Enter the color for Skin Background Color', '', '', 'Skin Background Color' );
      ?></td> <td>#<input name="skinBgColor" id="skinBgColor" maxlength="100"
						value="<?php echo $player_colors ['skinBgColor']; ?>"> </td> </tr> <tr>
						<?php /** Display field to Enter the color for Seek bar Color */ ?>
					<td> <?php echo JHTML::tooltip ( 'Enter the color for Seek Bar Color', '', '', 'Seek Bar Color' );
      ?></td> <td>#<input name="seek_barColor" id="seek_barColor" maxlength="100"
						value="<?php echo $player_colors ['seek_barColor']; ?>"> </td>
						<?php /** Display field to Enter the color for Buffer bar Color */ ?>
				</tr> <tr> <td> <?php echo JHTML::tooltip ( 'Enter the color for Buffer Bar Color', '', '', 'Buffer Bar Color' );
      ?></td> <td>#<input name="buffer_barColor" id="buffer_barColor" maxlength="100"
						value="<?php echo $player_colors ['buffer_barColor']; ?>">
					</td> </tr> <tr> <td>
					<?php /** Display field to Enter the color for Skin icons Color */ ?>
						<?php echo JHTML::tooltip ( 'Enter the color for Skin Icons Color', '', '', 'Skin Icons Color' ); ?></td> <td>#<input name="skinIconColor" id="skinIconColor" maxlength="100"
						value="<?php echo $player_colors ['skinIconColor']; ?>">
					</td> </tr> <?php /** Display field to Enter the color for progress bar bg Color */ ?>
					 <tr> <td> <?php echo JHTML::tooltip ( 'Enter the color for Progress Bar Background Color', '', '', 'Progress Bar Background Color' );
      ?></td> <td>#<input name="pro_BgColor" id="pro_BgColor" maxlength="100" value="<?php
      echo $player_colors ['pro_BgColor'];
      ?>"> </td> </tr> <tr> <td> <?php /** Display field to Enter the color for play Button Color */  
      echo JHTML::tooltip ( 'Enter the color for Play Button Color', '', '', 'Play Button Color' );
      ?></td> <td>#<input name="playButtonColor" id="playButtonColor"
						maxlength="100" value="<?php echo $player_colors ['playButtonColor']; ?>"> </td>
				</tr> <tr> <td> <?php /** Display field to Enter the color for play Button bg Color */ 
				echo JHTML::tooltip ( 'Enter the color for Play Button Background Color', '', '', 'Play Button Background Color' ); ?></td>
					<td>#<input name="playButtonBgColor" id="playButtonBgColor"
						maxlength="100" value="<?php
      echo $player_colors ['playButtonBgColor'];
      ?>"> </td> </tr> <tr>
      <?php /** Display field to Enter the color for player Buttons Color */ ?>
					<td> <?php echo JHTML::tooltip ( 'Enter the color for Player Buttons Color', '', '', 'Player Buttons Color' ); ?></td>
					<td>#<input name="playerButtonColor" id="playerButtonColor"
						maxlength="100"
						value="<?php echo $player_colors ['playerButtonColor']; ?>">
					</td> </tr>
				<?php /** Player Buttons Background Color */ ?>
				<tr>
					<td>
						<?php /** Display field to Enter the color for player Buttons bg Color */      
      echo JHTML::tooltip ( 'Enter the color for Player Buttons Background Color', '', '', 'Player Buttons Background Color' );
      ?></td>
					<td>#<input name="playerButtonBgColor" id="playerButtonBgColor"
						maxlength="100"
						value="<?php
      echo $player_colors ['playerButtonBgColor'];
      ?>">
					</td>
				</tr>
				<?php /** Related Videos Background Color */ ?>
				<tr>
					<td>
						<?php
      
      echo JHTML::tooltip ( 'Enter the color for Related Videos Background Color', '', '', 'Related Videos Background Color' );
      ?></td>
					<td>#<input name="relatedVideoBgColor" id="relatedVideoBgColor"
						maxlength="100"
						value="<?php
      echo $player_colors ['relatedVideoBgColor'];
      ?>">
					</td>
				</tr>
				<?php /** Related Videos Scroll Bar Color */ ?>
				<tr>
					<td>
						<?php       
      echo JHTML::tooltip ( 'Enter the color for Related Videos Scroll Bar Color', '', '', 'Related Videos Scroll Bar' );
      ?></td>
					<td>#<input name="scroll_barColor" id="scroll_barColor"
						maxlength="100"
						value="<?php
      echo $player_colors ['scroll_barColor'];
      ?>">
					</td>
				</tr>
				<?php /** Related Videos Scroll Bar Background Color */ ?>
				<tr>
					<td>
						<?php       
      echo JHTML::tooltip ( 'Enter the color for Related Videos Scroll Bar Background Color', '', '', 'Related Videos Scroll Bar Background' );
      ?></td>
					<td>#<input name="scroll_BgColor" id="scroll_BgColor"
						maxlength="100"
						value="<?php
      echo $player_colors ['scroll_BgColor'];
      ?>">
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php /** Player Settings Fields End */ ?>

	<?php /** Pre/Post-Roll Ads Settings Fields Start Here */ ?>
	<div style="position: relative;">
		<fieldset class="adminform">
<?php
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
  ?>
				<legend>Pre/Post-Roll Ad Settings</legend>
<?php
} else {
  ?>
				<h2>Pre/Post-Roll Ad Settings</h2>
<?php
}
?>
			<table
				class="
				<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'adminlist table table-striped';
    } else {
      echo " admintable adminlist";
    }
    ?>"> <tr> <td class="key"> <?php 
    echo JHTML::tooltip ( 'Option to enable/disable post-roll ads', '', '', 'Post-roll Ad' ); ?> </td>
					<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'colspan="3" class="radio_algin"';
      } ?>><input type="radio" name="postrollads" <?php  echo radioButtonCheck($player_icons ['postrollads']); ?> value="1" />Enable <input type="radio" name="postrollads" <?php
       echo radioButtonUnCheck($player_icons ['postrollads']); ?> value="0" />Disable</td> <?php
      if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo "</tr> <tr>";
      } ?> <td class="key"> 
      <?php echo JHTML::tooltip ( 'Option to enable/disable pre-roll ads', '', '', 'Pre-roll Ad' ); ?></td> <td 
      <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'colspan="3" class="radio_algin" ';
      } ?>><input type="radio" name="prerollads" <?php echo radioButtonCheck($player_icons ['prerollads']); ?> value="1" />Enable <input name="prerollads" type="radio" value="0" <?php
      echo radioButtonUnCheck($player_icons ['prerollads']); ?> />Disable</td> 
      <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
  echo "</tr> <tr>";
} ?> <td class="key"> 
<?php echo JHTML::tooltip('Option to enable/disable IMA ads', '', '', 'IMA Ad'); ?> </td> <td
						<?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin" ';
      } ?>><input type="radio" name="imaads" <?php echo radioButtonCheck($player_icons ['imaads']); ?> value="1" />Enable <input type="radio" name="imaads" <?php
      echo radioButtonUnCheck($player_icons ['imaads']);?> value="0" />Disable</td> <td class="key"> <?php
      echo JHTML::tooltip ( 'Enter the time to start IMA Ad', '', '', 'IMA Ad beginning time' );
      ?></td> <td colspan="3"><input type="text" name="imaadbegin" value="<?php
      if (isset ( $player_values ['imaadbegin'] )) {
        echo $player_values ['imaadbegin'];
      } ?>" style="margin-bottom: 0;" /> sec</td> </tr> <tr> <td class="key">
						<?php echo JHTML::tooltip('Option to enable/disable Ad Skip', '', '', 'Ad Skip'); ?>
					</td> <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="adsSkip" <?php echo radioButtonCheck($player_icons ['adsSkip']); ?> value="1" />Enable <input type="radio" name="adsSkip"
						<?php echo radioButtonUnCheck($player_icons ['adsSkip']); ?> value="0" />Disable</td> <td class="key"> <?php
       echo JHTML::tooltip ( 'Enter the time interval for Ad Skip Duration', '', '', 'Ad Skip Duration' );
      ?></td> <td colspan="3"><input type="text" name="adsSkipDuration" value="<?php
      if (isset ( $player_values ['adsSkipDuration'] )) {
        echo $player_values ['adsSkipDuration'];
      } ?>" style="margin-bottom: 0;" /> sec</td> </tr>
				<tr> <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
						<td class="key"> <?php echo JHTML::tooltip ( 'Option to enable/disable Google Analytics', '', '', 'Google Analytics' ); ?></td>
					<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'colspan="3"';
        } if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'class="radio_algin"';
        } else {
          echo 'colspan="5"';
        } ?>> <?php
        if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo '<div style="float: left">';
        } ?> <input type="radio" style="float: none;" onclick="Toggle('shows')" name="googleana_visible" id="googleana_visible"
						<?php echo radioButtonCheck($player_icons ['googleana_visible']); ?> value="1" />Enable <input type="radio" style="float: none;"
						onclick="Toggle('unshow')" name="googleana_visible"
						id="googleana_visible"
						<?php echo radioButtonUnCheck($player_icons ['googleana_visible']);
        ?>
						value="0" />Disable
	<?php
        if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo '</div>';
        }
        ?>
							<div id="show" class="google_analytics" style="display: none;">
	<?php echo JHTML::tooltip('Enter Google Analytics ID', '', '', 'Google Analytics ID'); ?>
								<input style="margin: 0;" name="googleanalyticsID"
								id="googleanalyticsID" maxlength="100"
								value="<?php
        if (isset ( $player_values ['googleanalyticsID'] )) {
          echo $player_values ['googleanalyticsID'];
        }
        ?>">
						</div>
					</td>
				</tr>
<?php
      } else {
        ?>
					<td class="key">
						<?php echo JHTML::tooltip('Option to enable/disable Google Analytics', '', '', 'Google Analytics'); ?></td>
				<td class="radio_algin"
					<?php
        if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'colspan="3"';
        }
        ?>><input type="radio" style="float: none;"
					onclick="Toggle('shows')" name="googleana_visible"
					id="googleana_visible"
					<?php echo radioButtonCheck($player_icons ['googleana_visible']);
        ?>
					value="1" />Enable <input type="radio" style="float: none;"
					onclick="Toggle('unshow')" name="googleana_visible"
					id="googleana_visible"
					<?php echo radioButtonUnCheck($player_icons ['googleana_visible']);
        ?>
					value="0" />Disable</td>
				</tr>
				<tr>
					<td class="key">
						<div id="show" style="display: none;">
								<?php
        
        echo JHTML::tooltip ( 'Enter Google Analytics ID', '', '', 'Google Analytics ID' );
        ?></div>
					</td>
					<td>
						<div id="show1" style="display: none;">
							<input name="googleanalyticsID" id="googleanalyticsID"
								maxlength="100"
								value="<?php
        if (isset ( $player_values ['googleanalyticsID'] )) {
          echo $player_values ['googleanalyticsID'];
        }
        ?>">
						</div>
					</td>

				</tr>
<?php
      }
      ?>
			</table>
		</fieldset>
	</div>
	<?php /** Pre/Post-Roll Ads Settings Fields End */ ?>

	<?php /** Mid Roll Ads Settings Fields Start Here */ ?>
	<div style="position: relative;">
		<fieldset class="adminform">
<?php
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
  ?>
				<legend>Mid Roll Ad Settings</legend>
<?php
} else {
  ?>
				<h2>Mid Roll Ad Settings</h2>
<?php
}
?>
			<table class=" <?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'adminlist table table-striped';
    } else {
      echo 'admintable adminlist';
    }
    ?> "> <tr> <td class="key"> 
    <?php echo JHTML::tooltip ( 'Option to enable/disable Mid-roll ads', '', '', 'Mid-roll Ad' ); ?></td> <td 
    <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="midrollads" 
      <?php echo radioButtonCheck($player_icons ['midrollads']); ?> value="1" />Enable <input type="radio" name="midrollads" 
      <?php echo radioButtonUnCheck($player_icons ['midrollads']); ?> value="0" />Disable</td> <td class="key"> 
      <?php echo JHTML::tooltip('Enter begin time for mid roll ad', '', '', 'Begin'); ?></td> <td><input type="text" name="midbegin"
						value="<?php if (isset ( $player_values ['midbegin'] )) {
        echo $player_values ['midbegin'];
      } ?>" /> sec</td> <td class="key">
						<?php  echo JHTML::tooltip ( 'Option to enable/disable rotation of ads', '', '', 'Ad Rotate' ); ?></td>
					<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="midadrotate"
						<?php echo radioButtonCheck($player_icons ['midadrotate']); ?> value="1" />Enable <input type="radio" name="midadrotate" 
      <?php echo radioButtonUnCheck($player_icons ['midadrotate']);?> value="0" />Disable</td> </tr> <tr> <td class="key">
						<?php echo JHTML::tooltip ( 'Option to enable/disable random display of ads', '', '', 'Mid-roll Ads Random' ); ?></td> 
						<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="radio_algin"';
      } ?>><input type="radio" name="midrandom" 
      <?php echo radioButtonCheck($player_icons ['midrandom']); ?> value="1" />Enable <input type="radio" name="midrandom" 
      <?php echo radioButtonUnCheck($player_icons ['midrandom']); ?> value="0" />Disable</td> <td class="key"> 
      <?php echo JHTML::tooltip ( 'Enter the time interval between ads', '', '', 'Ad Interval' ); ?></td>
					<td colspan="3"><input type="text" name="midinterval"
						value="<?php if (isset ( $player_values ['midinterval'] )) {
        echo $player_values ['midinterval'];
      } ?>" /> sec</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php /** Mid Roll Ads Settings Fields End */ ?>
	<input type="hidden" name="id" value="<?php if (isset ( $rs_editsettings [0]->id )) {
    echo $rs_editsettings [0]->id;
  } ?>" /> <input type="hidden" name="task" value="" /> <input type="hidden" name="submitted" value="true" id="submitted">
</form>
<?php /** Script for get file upload */ ?>
<script language="javascript">
			function getFileUpload(){
			var var_logo = '<input type="file" name="logopath" id="logopath" maxlength="100"  value="" />';
					document.getElementById('var_logo').innerHTML = var_logo;
					}
	window.onload = function(){
	var googleAnalyticsId = "<?php  if (isset ( $player_values ['googleanalyticsID'] )) {
  echo $player_values ['googleanalyticsID'];
} ?>";
			if (googleAnalyticsId){
	document.getElementById("show").style.display = '';
			}
	}
</script>
<?php /** Form For Edit Player Settings End */ ?>