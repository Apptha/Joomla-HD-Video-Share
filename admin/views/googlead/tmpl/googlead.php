<?php
/**
 * Google adsense template file
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

$googleadDetails = $this->googlead;
?>
<style>
fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
</style>
<script language="JavaScript" type="text/javascript">
<?php
if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  ?>
	Joomla.submitbutton = function(pressbutton) {
<?php
} else {
  ?>
			function submitbutton(pressbutton) {
<?php
}
?>
			if (pressbutton) {
				if (document.getElementById('name').value == '') {
					alert('You must enter/paste the google adsense code!');
					return false;
				}
				submitform(pressbutton);
				return;
			}
	}
</script>

<?php /** Form for googlead begin */
if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
  ?>
<style type="text/css">
table.adminlist input[type="checkbox"],table.adminlist input[type="radio"] { vertical-align: top; }
table.adminlist input[type="radio"] { margin-right: 5px; }
table.adminlist textarea { width: 500px; }
</style>
<?php } ?>
<form action="index.php?option=com_contushdvideoshare&layout=googlead"
	method="post" name="adminForm" id="adminForm"
	enctype="multipart/form-data" style="position: relative;">
	<fieldset class="adminform">
		<h2>Google AdSense</h2>
		<table
			<?php  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'class="adminlist table table-striped"';
  } else {
    echo 'class="admintable"';
  }
  ?>>
			<tr>
				<td class="key">Enter the Code:</td>
				<td
					colspan="<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo '2';
    } else {
      echo '3';
    }
    ?>"><textarea rows="9" cols="40" name="code" id="name"><?php
    if (isset ( $googleadDetails->code )) {
      echo trim ( $googleadDetails->code );
    }
    ?></textarea><br /> Default size 468 x 60</td>
				<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo '<td>&nbsp;</td>';
    }
    ?>
			</tr>
			<tr>
				<td class="key" style="float: none;">Display Option</td>
				<td
					<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'colspan="2"';
    }
    ?>><input type="radio" name="showoption" value="0" checked />Always
					Show&nbsp;&nbsp;<input type="radio" name="showoption" value=1
					<?php
    if ($googleadDetails->showoption == '1') {
      echo 'checked';
    }
    ?> />Close After : <input type="text" name="closeadd"
					value="<?php echo $googleadDetails->closeadd; ?>" />&nbsp;Sec</td>
<?php
if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
  echo '<td>&nbsp;</td>';
}
?>
			</tr>
			<tr>
				<td class="key">Reopen</td>
				<td
					<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'colspan="2"';
    }
    ?>><input type="checkbox" name="reopenadd" value="0"
					<?php
    if ($googleadDetails->reopenadd == '0') {
      echo 'checked';
    }
    ?> />&nbsp;&nbsp;Re-open After : <input type="text" name="ropen"
					value="<?php
    echo $googleadDetails->ropen;
    ?>" />&nbsp;Sec</td>
<?php
if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
  echo '<td>&nbsp;</td>';
}
?>
			</tr>
			<tr>
				<td class="key">Publish</td>
				<td
					<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'colspan="2"';
    }
    ?>><input type="radio" name="publish" value=1
					<?php
    if ($googleadDetails->publish == '1' || $googleadDetails->publish == '') {
      echo 'checked';
    }
    ?> />Yes <input type="radio" name="publish" value="0"
					<?php
    if ($googleadDetails->publish == '0' && $googleadDetails->publish != '') {
      echo 'checked';
    }
    ?> />No</td>
<?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
  echo '<td>&nbsp;</td>';
}
?> </tr>
		</table>
	</fieldset>
	<input type="hidden" name="id"
		value="<?php
  echo $googleadDetails->id;
  ?>" /> <input type="hidden" name="task" value="" /> <input
		type="hidden" name="submitted" value="true" id="submitted">
</form>
<?php /** Form for googlead end */ ?>
