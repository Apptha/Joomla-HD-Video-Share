<?php
/**
 * Ads template file
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

/** Get ads details */
$rs_roll = $this->adslist;
/** unserialize ima ads details */
$imaaddetail = unserialize ( $rs_roll ['rs_ads']->imaaddet );
/** Get doc object and include js files */
$document = JFactory::getDocument ();
$document->addScript ( JURI::base () . 'components/com_contushdvideoshare/js/upload_script.js' );
?>
<style>
fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
</style>
<script language="JavaScript" type="text/javascript">
<?php
if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  ?>Joomla.submitbutton = function(pressbutton) {
<?php
} else {
  ?>
	function submitbutton(pressbutton) {
<?php
}
?>
	if (pressbutton == "saveads" || pressbutton == "applyads") {
var prepost = document.getElementById('selectadd01').checked;
var filePath = document.getElementById('filepath01').checked;
var advideo = "<?php echo $rs_roll['rs_ads']->postvideopath ?>";

// For Post/Pre Roll Ad
if (prepost == true) {
if (filePath == true) {
			if (document.getElementById('normalvideoform-value').value == '' && advideo == '') {
alert("<?php echo JText::_('You must Upload a file', true); ?>");
	return false;
}

	document.getElementById('fileoption').value = "File"
if (uploadqueue.length != "") {
	alert("<?php echo JText::_('Upload in Progress', true); ?>");
return false;
}
	} else {
document.getElementById('fileoption').value = "Url"
			if (document.getElementById('posturl').value == '') {
alert("<?php echo JText::_('You must provide a Video Url', true); ?>")
return false;
			} else { var posturl = document.getElementById('posturl').value;
	var posturlregex = posturl.match("^(http:\/\/|https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
			if (posturlregex == null) {
	alert('Please Enter Valid URL');
	return;
}
}
	if (document.getElementById('posturl').value != "") {
			document.getElementById('posturl-value').value = document.getElementById('posturl').value;
}
}
}

// For IMA ad validation 
	if(document.getElementById('selectadd03').checked==true){
			if(document.getElementById('textad').checked==true && document.getElementById('publisherId').value == '') {
alert("<?php echo JText::_('Enter IMA Ad Publisher ID', true); ?>");
document.getElementById('publisherId').focus();
			return false;

} else if(document.getElementById('textad').checked==true && document.getElementById('contentId').value == '') {
			alert("<?php echo JText::_('Enter IMA Ad Content ID', true); ?>");
document.getElementById('contentId').focus();
return false;
			
	}else if(document.getElementById('textad').checked==true && document.getElementById('channels').value == '') {
	alert("<?php echo JText::_('Enter IMA Ad Channel', true); ?>"); document.getElementById('channels').focus();
return false;
}else {
if(document.getElementById('videoad').checked==true) {
	if(document.getElementById('imaadpath').value == ''){
	alert("<?php echo JText::_('Enter IMA Ad Path', true); ?>");
	document.getElementById('imaadpath').focus();
return false;
} else{
	var  thevideoadurl=document.getElementById("imaadpath").value ;
	var tomatch= /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
if (!tomatch.test(thevideoadurl)) {
alert("<?php echo JText::_('Enter Valid IMA Ad URL', true); ?>");
	document.getElementById("imaadpath").focus();
return false;
}
}
}
}
}

// For  Ad s name validation
	if (document.getElementById('adsname').value == ''){
			alert("<?php echo JText::_('You must provide a Ad name', true); ?>");
return false;
}
			if (document.getElementById('targeturl').value != "") {
	targeturl = document.getElementById('targeturl').value;
	var posturlregex = targeturl.match("^(http:\/\/|https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
if (posturlregex == null) {
			alert('Please Enter Valid URL');
return;
}
}
	if (document.getElementById('clickurl').value != "") {
	clickurl = document.getElementById('clickurl').value;
	var posturlregex = clickurl.match("^(http:\/\/|https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
if (posturlregex == null) {
			alert('Please Enter Valid URL');
return;
}
}
	if (document.getElementById('impressionurl').value != "") {
	impressionurl = document.getElementById('impressionurl').value;
			var posturlregex = impressionurl.match("^(http:\/\/| https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
if (posturlregex == null) {
			alert('Please Enter Valid URL');
return;
}
}
}
	submitform(pressbutton);
	return;
}
</script>
<?php
if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
  ?>
<style type="text/css">
table.adminlist input[type="checkbox"],table.adminlist input[type="radio"] { vertical-align: top; }
table.adminlist input[type="radio"] { margin-right: 5px; }
table.adminlist textarea { width: 300px; }
table #published { width: 315px; }
</style>
<?php } ?>
<div style="position: relative;">
	<fieldset class="adminform">
		<?php /** Display new video ads page title */
  if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
    ?>
			<legend>Ad Type</legend>
		<?php
  } else {
    ?>
			<h2>Ad Type</h2>
		<?php
  }
  ?>
		<table
			<?php
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'class="adminlist table table-striped"';
  } else {
    echo 'class="admintable"';
  }
  ?>>
			<tr> <?php /** Display field to select ads type */ ?>
				<td class="key" width="200px;">Select Ad Type</td>
				<td><input type="radio" name="selectadd" id="selectadd01"
					value="prepost" onclick="checkadd('prepost');"
					<?php
    if ($rs_roll ['rs_ads']->typeofadd == "prepost" || $rs_roll ['rs_ads']->typeofadd == '') {
      echo 'checked';
    }
    ?> />Pre/Post Roll Ad <input type="radio" name="selectadd"
					id="selectadd02" value="mid" onclick="checkadd('mid');"
					<?php
    if ($rs_roll ['rs_ads']->typeofadd == "mid") {
      echo 'checked';
    }
    ?> />Mid Roll Ad <input type="radio" name="selectadd" id="selectadd03"
					value="ima" onclick="checkadd('ima');"
					<?php
    if ($rs_roll ['rs_ads']->typeofadd == "ima") {
      echo 'checked';
    }
    ?> />IMA Ad</td>
			</tr>
		</table>
	</fieldset>
</div>

<?php
$var1 = "";

if (isset ( $rs_roll ['rs_ads']->typeofadd ) && $rs_roll ['rs_ads']->typeofadd == "mid") {
  $var1 = 'style="display: none;"';
}
?>
<div style="position: relative;">
	<!--Pre/Post roll ad starts here-->
	<fieldset class="adminform" id="videodet" <?php echo $var1; ?>>
		<?php
  if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
    ?>
			<legend>Video Details</legend>
<?php
  } else {
    ?>
			<h2>Video Details</h2>
<?php
  }
  ?>
		<table
			<?php
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'class="adminlist table table-striped"';
  } else {
    echo 'class="admintable"';
  }
  ?>>
			<tr> <?php /** display filed to selct video ad type as file or url */ ?>
				<td class="key" width="200px;">File Path</td>
				<td><input type="radio" name="filepath" id="filepath01"
					<?php
    if ($rs_roll ['rs_ads']->filepath == "File" || $rs_roll ['rs_ads']->filepath == '') {
      echo 'checked="checked" ';
    }
    ?>
					value="File" onclick="fileads('File');" />File <input type="radio"
					name="filepath" id="filepath02"
					<?php
    if ($rs_roll ['rs_ads']->filepath == "Url") {
      echo 'checked="checked" ';
    }
    ?>
					value="Url" onclick="fileads('Url');" />Url</td>
			</tr>
			<tr id="postrollnf" name="postrollnf">
			<?php /** Display upload button to upload ads file */ ?>
				<td class="key">Upload Preroll/Post Roll</td>
				<td>
					<div id="f1-upload-form">
						<form name="normalvideoform" method="post"
							enctype="multipart/form-data">
							<input type="file" name="myfile" style="float: left;" id="myfile"
								onchange="enableUpload(this.form.name);" /> <input type="button"
								style="float: left;" name="uploadBtn" value="Upload Video"
								<?php
        if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'class="modal btn"';
        }
        ?>
								disabled="disabled" onclick="addQueue(this.form.name);" /> <label
								id="advideo_path"><?php echo $rs_roll['rs_ads']->postvideopath; ?></label>
							<input type="hidden" name="mode" value="video" />
						</form>
					</div>
					<div id="f1-upload-progress" style="display: none">
						<table>
							<tr>
								<td><img id="f1-upload-image" style="float: left;"
									src="components/com_contushdvideoshare/images/empty.gif"
									alt="Uploading" /></td>
								<td><label style="float: left; clear: none; font-weight: bold;"
									id="f1-upload-filename">PostRoll.flv</label></td>
									<?php /** Display upload status */ ?>
								<td><span id="f1-upload-message" 
									style="font-size: 12px; padding: 5px 150px 5px 10px; color: green;">
										<b>Upload Failed:</b> User Cancelled the upload
								</span> <label id="f1-upload-status" style="float: left;">Uploading...</label>
								</td>
								<?php /** Display cancel link while uploading */ ?>
								<td><span id="f1-upload-cancel"> <a style="float: left;"
										href="javascript:cancelUpload('normalvideoform');"
										name="submitcancel">Cancel</a>
								</span></td>
							</tr>
						</table>
					</div>
					<div id="nor">
						<iframe id="uploadvideo_target" name="uploadvideo_target" src=""
							style="width: 0; height: 0; border: 0px solid #fff;"></iframe>
					</div>
				</td>
			</tr>
			<?php /** Display field to enter video ad url */ ?>
			<tr id="postrollurl">
				<td class="key">Preroll/Postroll Url</td>
				<td><input type="text" name="posturl" id="posturl"
					style="width: 300px" maxlength="250"
					value="<?php
    if ($rs_roll ['rs_ads']->postvideopath && $rs_roll ['rs_ads']->filepath == 'Url') {
      echo $rs_roll ['rs_ads']->postvideopath;
    }
    ?>" /></td>
			</tr>
		</table>
	</fieldset>
	<?php  /** Pre/Post roll ad ends here */ ?>
</div>
<?php
$styleVar = '';

if (isset ( $rs_roll ['rs_ads']->typeofadd ) && $rs_roll ['rs_ads']->typeofadd == "mid") {
  $styleVar = 'style="display: none;"';
}
/** Display ima ad details section starts */
?>
<form action="index.php?option=com_contushdvideoshare&layout=ads"
	method="post" name="adminForm" id="adminForm"
	enctype="multipart/form-data" style="position: relative;">
	<!--IMA ad starts here-->
	<fieldset class="adminform" id="videodetima" <?php echo $styleVar; ?>>
<?php /** Display ima ad title */
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
  ?>
			<legend>IMA Ad Details </legend>
<?php
} else {
  ?>
			<h2>IMA Ad Details</h2>
<?php
}
?>
		<table
			<?php
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'class="adminlist table table-striped"';
  } else {
    echo 'class="admintable"';
  }
  ?>>
			<tbody>
				<tr>
				<?php /** Display ima ad type */ ?>
					<td class="key" width="200px;">IMA Ad type</td>
					<td><input type="radio" style="float: none; margin-right: 3px;"
						name="imaadtype" id="textad" checked="checked" value="textad"
						onclick="imaads('textad');" />Text/Overlay <input type="radio"
						style="float: none; margin-right: 3px; margin-left: 10px;"
						name="imaadtype" id="videoad" value="videoad"
						onclick="imaads('videoad');" />Video</td>
				</tr>
				<?php /** Display field to enter publisher id */ ?>
				<tr id="adimapublisher" name="adimapublisher">
					<td class="key">Publisher Id</td>
					<td><input type="text" name="publisherId" id="publisherId"
						style="width: 300px" maxlength="250"
						value="<?php
      if (isset ( $imaaddetail ['publisherId'] )) {
        echo $imaaddetail ['publisherId'];
      }
      ?>" /></td>

				</tr>
				<?php /** Display field to enter content id */ ?>
				<tr id="adimacontentid" name="adimacontentid">
					<td class="key">Content Id</td>
					<td><input type="text" name="contentId" id="contentId"
						style="width: 300px" maxlength="250"
						value="<?php
      if (isset ( $imaaddetail ['contentId'] )) {
        echo $imaaddetail ['contentId'];
      }
      ?>" /></td>
				</tr>
				<?php /** Display field to enter channel */ ?>
				<tr id="adimachannels" name="adimachannels">
					<td class="key">Channel</td>
					<td><input type="text" name="channels" id="channels"
						style="width: 300px" maxlength="250"
						value="<?php
      if (isset ( $imaaddetail ['channels'] )) {
        echo $imaaddetail ['channels'];
      }
      ?>" /></td>

				</tr>
				<tr id="imavideoad">
				<?php /** Display field to enter ima ad path */ ?>
					<td class="key">IMA Ad Path</td>
					<td><input type="text" name="imaadpath" id="imaadpath"
						style="width: 300px" maxlength="250"
						value="<?php
      if (isset ( $imaaddetail ['imaadpath'] )) {
        echo $imaaddetail ['imaadpath'];
      }
      ?>" /></td>
				</tr>
			</tbody>
		</table>
	</fieldset>
	<?php /** IMA ad ends here */ ?>
	<fieldset class="adminform">
<?php /** Display other ads settings */
if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
  ?>
			<legend>Ad Settings </legend>
<?php
} else {
  ?>
			<h2>Ad Settings</h2>
<?php
}
?>
		<table
			<?php
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'class="adminlist table table-striped"';
  } else {
    echo 'class="admintable"';
  }
  ?>>
			<tr id="namead">
			<?php /** Display field to enter video ad title */ ?>
				<td class="key" width="200px">Ad Title</td>
				<td><input type="text" name="adsname" id="adsname"
					style="width: 300px" maxlength="250"
					value="<?php echo $rs_roll['rs_ads']->adsname; ?>" /></td>
			</tr>
			<?php /** Display field to enter video ad description */ ?>
			<tr id="descriptionad">
				<td class="key">Ad Description</td>
				<td><textarea rows="5" cols="40" name="adsdesc" id="adsdesc"><?php echo trim($rs_roll['rs_ads']->adsdesc); ?></textarea></td>
			</tr>
			<?php /** Display field to enter target url */ ?>
			<tr id="urltarget">
				<td class="key">Target URL</td>
				<td><input type="text" name="targeturl" id="targeturl"
					style="width: 300px" maxlength="250"
					value="<?php echo $rs_roll['rs_ads']->targeturl; ?>" /></td>
			</tr>
			<tr id="urlclick">
			<?php /** Display field to enter video click url */ ?>
				<td class="key">Click Hits URL</td>
				<td><input type="text" name="clickurl" id="clickurl"
					style="width: 300px" maxlength="250"
					value="<?php echo $rs_roll['rs_ads']->clickurl; ?>" /></td>
			</tr>
			<tr id="impress">
			<?php /** Display field to enter video impression url */ ?>
				<td class="key">Impression Hits URL</td>
				<td><input type="text" name="impressionurl" id="impressionurl"
					style="width: 300px" maxlength="250"
					value="<?php echo $rs_roll['rs_ads']->impressionurl; ?>" /></td>
			</tr>
			<tr> <?php /** Display field to enable video ad publish option */ ?>
				<td class="key">Published</td>
				<td><select name="published" id="published">
						<option value="1"
							<?php
      if (isset ( $rs_roll ['rs_ads']->published ) && $rs_roll ['rs_ads']->published == 1) {
        echo 'selected';
      }
      ?>>Published</option>
						<option value="0"
							<?php
      if (isset ( $rs_roll ['rs_ads']->published ) && $rs_roll ['rs_ads']->published == 0) {
        echo 'selected';
      }
      ?>>Unpublished</option>
						<option value="-2"
							<?php
      if (isset ( $rs_roll ['rs_ads']->published ) && $rs_roll ['rs_ads']->published == - 2) {
        echo 'selected';
      }
      ?>>Trashed</option>
				</select></td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="id" id="id"
		value="<?php
  if (isset ( $rs_roll ['rs_ads']->id )) {
    echo $rs_roll ['rs_ads']->id;
  }
  ?>" /> <input type="hidden" name="typeofadd" id="typeofadd"
		value="<?php
  if (isset ( $rs_roll ['rs_ads']->typeofadd )) {
    echo $rs_roll ['rs_ads']->typeofadd;
  }
  ?>" /> <input type="hidden" name="task" value="addads" /> <input
		type="hidden" name="boxchecked" value="1" /> <input type="hidden"
		name="submitted" value="true" id="submitted" /> <input type="hidden"
		name="fileoption" id="fileoption"
		value="<?php
  if (isset ( $rs_roll ['rs_ads']->filepath )) {
    echo $rs_roll ['rs_ads']->filepath;
  }
  ?>" /> <input type="hidden" name="imaadoption" id="imaadoption"
		value="<?php echo $rs_roll['rs_ads']->imaadoption; ?>" /> <input
		type="hidden" name="normalvideoform-value" id="normalvideoform-value"
		value="" /> <input type="hidden" name="posturl-value"
		id="posturl-value" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>
<script type="text/javascript"
	src="<?php echo JURI::base() . 'components/com_contushdvideoshare/js/adslayout.js'; ?>"></script>
<script type="text/javascript">
if ((document.getElementById('fileoption').value == 'File') || (document.getElementById('fileoption').value == ''))
{
adsflashdisable();
}
if (document.getElementById('fileoption').value == 'Url')
{
urlenable();
}
window.onload = function(){
<?php
if ($rs_roll ['rs_ads']->typeofadd == "prepost" || $rs_roll ['rs_ads']->typeofadd == '') {
  ?>
	checkadd('prepost');
<?php
} elseif ($rs_roll ['rs_ads']->typeofadd == "mid") {
  ?> checkadd('mid'); <?php
} elseif ($rs_roll ['rs_ads']->typeofadd == "ima") {
  ?> checkadd('ima'); <?php
}

if (! empty ( $imaaddetail ['imaadpath'] )) {
  ?> imaads('videoad'); <?php
} else {
  ?> imaads('textad'); <?php
}
?>
};
</script>
