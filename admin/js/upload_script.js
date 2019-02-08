/**
 * Upload script js file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 * @Creation Date   March 2010
 * @Modified Date   September 2015
 * */

var uploadqueue = [];
var uploadmessage = '';
/**
 * function to get uploaded file details
 * from form 
 */
function addQueue(whichForm) {
	uploadqueue.push(whichForm);
	if (uploadqueue.length == 1)
		processQueue();
	else
		holdQueue();
}
/**
 * function to process upload queue 
 */
function processQueue() {		
	if (uploadqueue.length > 0) {	
		form_handler = uploadqueue[0];
		setStatus(form_handler, 'Uploading');
		submitUploadForm(form_handler);
	}
}
/**
 * function to hold upload queue 
 */
function holdQueue() {
	form_handler = uploadqueue[uploadqueue.length - 1];
	setStatus(form_handler, 'Queued');
}
/**
 * function to display status message of upload
 */
function updateQueue(statuscode, statusmessage, outfile) {
	uploadmessage = statusmessage;
	form_handler = uploadqueue[0];
	if (statuscode == 0){
		document.getElementById(form_handler + "-value").value = outfile;
                if(form_handler === 'subtitle_video_srt1form'){
                    getsubtitle1name();
                }
                if(form_handler === 'subtitle_video_srt2form'){
                    getsubtitle2name();
                }
        }
	setStatus(form_handler, statuscode);
	uploadqueue.shift();
	processQueue();
}
/**
 * function to display staus message of upload
 */
function submitUploadForm(form_handle) {
	document.forms[form_handle].target = "uploadvideo_target";
	documentBasePath = document.location.href;
	if (documentBasePath.indexOf('?') != -1)
		documentBasePath = documentBasePath.substring(0, documentBasePath
				.indexOf('?'));
	if (documentBasePath.indexOf('administrator') == -1) {
		baseURL = documentBasePath + "/administrator";
	} else {
		if (documentBasePath.lastIndexOf('administrator/') == -1) {
			baseURL = documentBasePath;
		} else {
			documentBasePath = documentBasePath.substring(0, documentBasePath
					.lastIndexOf('/'));
			baseURL = documentBasePath;
		}
	}
	document.forms[form_handle].action = baseURL
			+ "/index.php?option=com_contushdvideoshare&tmpl=component&layout=adminvideos&task=uploadfile&processing=1";
	document.forms[form_handle].submit();
}
/**
 * function to set status message of upload
 */
function setStatus(form_handle, status) {
	switch (form_handle) {
	case "normalvideoform":
		divprefix = 'f1';
		break;
	case "hdvideoform":
		divprefix = 'f2';
		break;
	case "thumbimageform":
		divprefix = 'f3';
		break;
	case "previewimageform":
		divprefix = 'f4';
		break;
	case "ffmpegform":
		divprefix = 'f5';
		break;
	case "rollform":
		divprefix = 'f6';
		break;
	case "subtitle_video_srt1form":
		divprefix = 'f7';
		break;
	case "subtitle_video_srt2form":
		divprefix = 'f8';
		break;
	}
	//displayed uploading form and process message based on status
	switch (status) {
	case "Queued":
		document.getElementById(divprefix + "-upload-form").style.display = "none";
		document.getElementById(divprefix + "-upload-progress").style.display = "";
		document.getElementById(divprefix + "-upload-status").innerHTML = "Queued";
		document.getElementById(divprefix + "-upload-message").style.display = "none";
		document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
		document.getElementById(divprefix + "-upload-image").src = 'components/com_contushdvideoshare/images/empty.gif';
		break;

	case "Uploading":
		document.getElementById(divprefix + "-upload-form").style.display = "none";
		document.getElementById(divprefix + "-upload-progress").style.display = "";
		document.getElementById(divprefix + "-upload-status").style.fontWeight = 'bold';
		document.getElementById(divprefix + "-upload-status").innerHTML = "Uploading...";
		document.getElementById(divprefix + "-upload-message").style.display = "none";
		document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
		document.getElementById(divprefix + "-upload-image").src = 'components/com_contushdvideoshare/images/page_loading.gif';
		break;
	case "Retry":
	case "Cancelled":
		document.forms[form_handle].reset();
		document.getElementById(divprefix + "-upload-form").style.display = "";
		document.getElementById(divprefix + "-upload-progress").style.display = "none";
		document.forms[form_handle].myfile.value = '';
		enableUpload(form_handle);
		break;
	case 0:
		document.getElementById(divprefix + "-upload-image").src = 'components/com_contushdvideoshare/images/success.gif';
		document.getElementById(divprefix + "-upload-status").style.display = 'none';
		document.getElementById(divprefix + "-upload-message").style.display = "";
		document.getElementById(divprefix + "-upload-message").style.color = "green";
		document.getElementById(divprefix + "-upload-message").innerHTML = '<b>Upload Status</b> :'+uploadmessage;
		document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
		break;

	default:
		document.getElementById(divprefix + "-upload-image").src = 'components/com_contushdvideoshare/images/error.gif';
		document.getElementById(divprefix + "-upload-status").style.display = 'none';
		document.getElementById(divprefix + "-upload-message").style.display = "";
		document.getElementById(divprefix + "-upload-message").style.color = "red";
		document.getElementById(divprefix + "-upload-message").innerHTML = '<b>Upload Status</b> :'+uploadmessage
				+ " <a href=javascript:setStatus('"
				+ form_handle
				+ "','Retry')><b style='margin-left: 5px;'>Retry</b></a>";
		document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
		break;
	}
}
/**
 * function to enable the upload button
 */
function enableUpload(whichForm) {
	if (document.forms[whichForm].myfile.value != '')
		document.forms[whichForm].uploadBtn.disabled = "";
	else
		document.forms[whichForm].uploadBtn.disabled = "disabled";
}
/**
 * function to cancel uploading process
 */
function cancelUpload(whichForm) {
	document.getElementById('uploadvideo_target').src = '';
	setStatus(whichForm, 'Cancelled');
	pos = uploadqueue.lastIndexOf(whichForm);
	if (pos == 0) {
		//if (uploadqueue.length > 1) {
			uploadqueue.shift();
			processQueue();
		//}
	} else {	
		uploadqueue.splice(pos, 1);
	}
}