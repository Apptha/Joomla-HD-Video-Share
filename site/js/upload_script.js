/**
 * Video upload JS for HD Video Share
 *
 * This file is to validate and upload videos in HD Video Share component
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

var uploadqueue = [];
var uploadmessage = ""; 
function addQueue(e, t) {
    var n = extension(t);
    if (e == "normalvideoform" || e == "hdvideoform" || e == "ffmpeg") {
        if (n != "flv" && n != "FLV" && n != "mp4" && n != "MP4" && n != "m4v" && n != "M4V" && n != "mp4v" && n != "Mp4v" && n != "m4a" && n != "M4A" && n != "mov" && n != "MOV" && n != "f4v" && n != "F4V" && n != "mp3" && n != "MP3") {
            alert(n + " is not a valid Video Extension");
            return false;
        }
    } else {
        if (n != "jpg" && n != "png" && n != "jpeg") {
            alert(n + " is not a valid Image Extension");
            return false;
        }
    }
    uploadqueue.push(e);
    if (uploadqueue.length == 1) {
        processQueue();
    } else {
        holdQueue();
    }
}
function processQueue() {
    if (uploadqueue.length > 0) {
        form_handler = uploadqueue[0];
        setStatus(form_handler, "Uploading");
        submitUploadForm(form_handler);
    }
}
function holdQueue() {
    form_handler = uploadqueue[uploadqueue.length - 1];
    setStatus(form_handler, "Queued");
}
function updateQueue(e, t, n, r) {
    uploadmessage = t;
    form_handler = uploadqueue[0];
    var n = n.split("").reverse().join("");
    if (form_handler == "normalvideoform" || form_handler == "hdvideoform" || form_handler == "thumbimageform" || form_handler == "previewimageform") {
        form_handlers = form_handler + "val";
        amazons3bucketstatus = form_handler + "s3status";
        document.getElementById(form_handlers).value = n;
        document.getElementById(amazons3bucketstatus).value = r;
    } else {
        document.getElementById(form_handler).value = n;
    }
    setStatus(form_handler, e);
    uploadqueue.shift();
    processQueue();
}
function submitUploadForm(e) {
    document.forms[e].target = "uploadvideo_target";
    document.forms[e].action = document.getElementById("videouploadformurl").value + "/index.php?option=com_contushdvideoshare&tmpl=component&task=uploadfile&processing=1&clientupload=true";
    document.forms[e].submit();
}
function setStatus(e, t) {
    switch (e) {
        case "ffmpeg":
            divprefix = "f11";
            break;
        case "normalvideoform":
            divprefix = "f1";
            break;
        case "hdvideoform":
            divprefix = "f2";
            break;
        case "thumbimageform":
            divprefix = "f3";
            break;
        case "previewimageform":
            divprefix = "f4";
            break;
    }
    var n = document.getElementById("videouploadformurl").value;
    switch (t) {
        case "Queued":
            document.getElementById(divprefix + "-upload-form").style.display = "none";
            document.getElementById(divprefix + "-upload-progress").style.display = "block";
            document.getElementById(divprefix + "-upload-status").innerHTML = "Queued";
            document.getElementById(divprefix + "-upload-message").style.display = "none";
            document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[e].myfile.value;
            document.getElementById(divprefix + "-upload-image").src = n + "components/com_contushdvideoshare/images/empty.gif";
            document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("' + e + '") name="submitcancel">Cancel</a>';
            break;
        case "Uploading":
            document.getElementById(divprefix + "-upload-form").style.display = "none";
            document.getElementById(divprefix + "-upload-progress").style.display = "block";
            document.getElementById(divprefix + "-upload-status").innerHTML = "Uploading";
            document.getElementById(divprefix + "-upload-message").style.display = "none";
            document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[e].myfile.value;
            document.getElementById(divprefix + "-upload-image").src = n + "components/com_contushdvideoshare/images/page_loading.gif";
            document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("' + e + '") name="submitcancel">Cancel</a>';
            break;
        case "Retry":
        case "Cancelled":
            document.getElementById(divprefix + "-upload-form").style.display = "block";
            document.getElementById(divprefix + "-upload-progress").style.display = "none";
            document.forms[e].myfile.value = "";
            enableUpload(e);
            break;
        case 0:
            document.getElementById(divprefix + "-upload-image").src = n + "components/com_contushdvideoshare/images/success.gif";
            document.getElementById(divprefix + "-upload-status").innerHTML = "";
            document.getElementById(divprefix + "-upload-message").style.display = "block";
            document.getElementById(divprefix + "-upload-message").style.backgroundColor = "#CEEEB2";
            document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage;
            document.getElementById(divprefix + "-upload-cancel").innerHTML = "";
            break;
        default:
            document.getElementById(divprefix + "-upload-image").src = n + "components/com_contushdvideoshare/images/error.gif";
            document.getElementById(divprefix + "-upload-status").innerHTML = " ";
            document.getElementById(divprefix + "-upload-message").style.display = "block";
            document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage + " <a href=javascript:setStatus('" + e + "','Retry')>Retry</a>";
            document.getElementById(divprefix + "-upload-cancel").innerHTML = "";
            break;
    }
}
function enableUpload(e, t) {
    if (document.forms[e].myfile.value != "") document.forms[e].uploadBtn.disabled = "";
    else document.forms[e].uploadBtn.disabled = "disabled";
}
function cancelUpload(e) {
    document.getElementById("uploadvideo_target").src = "";
    setStatus(e, "Cancelled");
    pos = uploadqueue.lastIndexOf(e);
    if (pos == 0) {
        if (uploadqueue.length >= 1) {
            uploadqueue.shift();
            processQueue();
        } else {
            uploadqueue.splice(pos, 1);
        }
    } else {
        uploadqueue.splice(pos, 1);
    }
}
function extension(e) {
    var t = e.lastIndexOf(".");
    var n = e.length;
    if (t != -1 && n != t + 1) {
        var r = e.split(".");
        var i = r.length;
        var s = r[i - 1].toLowerCase();
    } else {
        s = "No extension found";
    }
    return s;
}
