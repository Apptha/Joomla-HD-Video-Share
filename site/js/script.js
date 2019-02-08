/**
 * Field Validation for HD Video Share
 *
 * This file script is used for HD Video Share component
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
var YoutubeVideoid;
var scriptLoaded = false;
function YouTube_Error( Youtubeid ){
   YoutubeVideoid = Youtubeid;
if(document.getElementById('videoPlay')) {
   document.getElementById('videoPlay').innerHTML = '<div id="iframeplayer"></div>';
} else {
if(document.getElementById('flashplayer'))
   document.getElementById('flashplayer').innerHTML = '<div id="iframeplayer"></div>';
}
 if(!scriptLoaded) {
   var tag = document.createElement("script");
   tag.src = "https://www.youtube.com/iframe_api";
   scriptLoaded=true; 
   var firstScriptTag = document.getElementsByTagName("script")[0];
   firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
 } else {
   onYouTubeIframeAPIReady();
 }
}
function onYouTubeIframeAPIReady() {  
     player = new YT.Player('iframeplayer', {
      width: "100%",
      videoId: YoutubeVideoid,
      playerVars: {
          'autoplay': 1
        },
      events: {
        'onStateChange': onPlayerStateChange
      }
    });
}
function onPlayerStateChange(event) {
   var done = false;
   if (event.data == YT.PlayerState.PLAYING && !done) {
      currentVideoP(YoutubeVideoid);
      done = true;
   }
}window.onload = function(){
jQuery.noConflict();
};
jQuery(".ulvideo_thumb").mouseover(function() {
   if(typeof htmltooltipCallback === 'function') {
   htmltooltipCallback("htmltooltip", "",rtlLang);
   htmltooltipCallback("htmltooltip1", "1",rtlLang);
   htmltooltipCallback("htmltooltip2", "2",rtlLang);
   }
});
function membervalue(memid) {
   document.getElementById('memberidvalue').value = memid;
   document.memberidform.submit();
}
function changepage(pageno) {
   document.getElementById("video_pageid").value = pageno;
   document.pagination.submit();
}
function my_message(vid) {
   var flg = confirm(confirmDeleteVideo);
   if (flg) {
      var r = document.getElementById('deletevideo').value = vid;
      document.deletemyvideo.submit();
      return true;
   } else {
      return false;
   }
}
function videoplay(vid, cat) {
   window.open('index.php?Itemid='+itemid+'&amp;option=com_contushdvideoshare&view=player&id=' + vid + '&catid=' + cat, '_self');
}
function editvideo(evid) {
   window.open(evid, '_self');
}
function sortvalue(sortvalue) {
   document.getElementById("sorting").value = sortvalue;
   document.sortform.submit();
}
function bindvideo() {
   if (document.getElementById('Youtubeurl').value != '' || document.getElementById('embed_code').value != '') {
      document.getElementById('videourl').value = 0;
   }
}
function getvideoData(vid, title, desc) {
   document.getElementById("viewtitle").innerHTML =  title;
}
function deletePlaylistVideo(vid,catid)
{
   var flg = confirm('Do you Really Want To Delete This Video From This Playlist? \n\nClick OK to continue. Otherwise click Cancel.\n');
   if (flg)  {
      var r = document.getElementById('deletevideo').value = vid;
      var c = document.getElementById('deletecat').value = catid;
      document.deletemyvideoplay.submit();
      return true;
   } else {
      return false;
   }
}
function filetypeshow(obj) { 
   if (obj.value == 0 || obj == 0) {
      document.getElementById("typefile").style.display = "none";
      document.getElementById("typeff").style.display = "none";
      document.getElementById("typeurl").style.display = "block";
      document.getElementById("rtmpcontainer").style.display = "none";
      document.getElementById("down_load").style.display = "none";
      document.getElementById("hd_url").style.display = "none";
      document.getElementById("nonhd_url").style.display = "block";
      document.getElementById("image_path").style.display = "none";
      document.getElementById('seltype').value = 0;
      document.getElementById('video_filetype').value = 'Youtube';
      document.getElementById("ffmpeg").style.display = "none";
      document.getElementById("ffmpeg_disable_new9").style.display = "none";
      document.getElementById("normalvideoformval").style.display = "none";
   }
   if (obj.value == 5 || obj == 5) {
      document.getElementById("typeff").style.display = "block";
      document.getElementById("typeurl").style.display = "none";
      document.getElementById("rtmpcontainer").style.display = "none";
      document.getElementById("down_load").style.display = "none";
      document.getElementById("hd_url").style.display = "none";
      document.getElementById("nonhd_url").style.display = "none";
      document.getElementById("image_path").style.display = "none";
      document.getElementById('seltype').value = 6;
      document.getElementById('video_filetype').value = 'FFMPEG';
      document.getElementById("ffmpeg").style.display = "none";
      document.getElementById("ffmpeg_disable_new9").style.display = "none";
      document.getElementById("normalvideoformval").style.display = "none";
   }
   if (obj.value == 1 || obj == 1) {      
      document.getElementById("typefile").style.display = "block";
      document.getElementById("typeurl").style.display = "none";
      document.getElementById("typeff").style.display = "none";
      document.getElementById("down_load").style.display = "block";
      document.getElementById("rtmpcontainer").style.display = "none";
      document.getElementById('seltype').value = 1;
      document.getElementById('video_filetype').value = 'File'; 
      document.getElementById("ffmpeg").style.display = "none";
      document.getElementById("ffmpeg_disable_new9").style.display = "none";
      document.getElementById("normalvideoformval").style.display = "block";      
   }
   if (obj.value == 2 || obj == 2) {
      document.getElementById("typefile").style.display = "none";
      document.getElementById("typeurl").style.display = "block";
      document.getElementById("hd_url").style.display = "block";
      document.getElementById("nonhd_url").style.display = "block";
      document.getElementById("down_load").style.display = "block";
      document.getElementById("image_path").style.display = "block";
      document.getElementById("imageurllabel").style.display = "";
      document.getElementById("imageurlpath").style.display = "";
      document.getElementById("typeff").style.display = "none";
      document.getElementById("ffmpeg").style.display = "none";
      document.getElementById('seltype').value = 2;
      document.getElementById('video_filetype').value = 'Url';
      document.getElementById("normalvideoformval").style.display = "block";
      document.getElementById("ffmpeg_disable_new9").style.display = "none";
      document.getElementById("rtmpcontainer").style.display = "none";
   }
   if (obj.value == 3 || obj == 3) {
      document.getElementById("rtmpcontainer").style.display = "block";
      document.getElementById("typeurl").style.display = "block";
      document.getElementById("typefile").style.display = "none";
      document.getElementById("hd_url").style.display = "none";
      document.getElementById("nonhd_url").style.display = "block";
      var islivevalue2 = (document.getElementById('islive2').checked);

      if (islivevalue2 === true) {
         document.getElementById('islive-value').value = 1;
      } else {
         document.getElementById('islive-value').value = 0;
      }

      document.getElementById("down_load").style.display = "none";
      document.getElementById("image_path").style.display = "block";
      document.getElementById("imageurllabel").style.display = "";
      document.getElementById("imageurlpath").style.display = "";
      document.getElementById("typeff").style.display = "none";
      document.getElementById("ffmpeg").style.display = "none";
      document.getElementById('seltype').value = 3;
      document.getElementById('video_filetype').value = 'Url';
      document.getElementById("ffmpeg_disable_new9").style.display = "none";
      document.getElementById("normalvideoformval").style.display = "block";
   }
   if (obj.value == 4 || obj == 4) {
      document.getElementById("rtmpcontainer").style.display = "none";
      document.getElementById("typeurl").style.display = "block";
      document.getElementById("typefile").style.display = "none";
      document.getElementById("hd_url").style.display = "none";
      document.getElementById("nonhd_url").style.display = "none";
      var islivevalue2 = (document.getElementById('islive2').checked);

      if (islivevalue2 === true) {
         document.getElementById('islive-value').value = 1;
      } else {
         document.getElementById('islive-value').value = 0;
      }

      document.getElementById("down_load").style.display = "none";
      document.getElementById("image_path").style.display = "block";
      document.getElementById("imageurllabel").style.display = "none";
      document.getElementById("imageurlpath").style.display = "none";
      document.getElementById("typeff").style.display = "none";
      document.getElementById("ffmpeg").style.display = "none";
      document.getElementById('seltype').value = 4;
      document.getElementById('video_filetype').value = 'Embed';
      document.getElementById("ffmpeg_disable_new9").style.display = "block";
      document.getElementById("normalvideoformval").style.display = "block";
   }
}
function resetcategory() {
   document.getElementById('tagname').value = '';
}
function catselect(categ) {
   var r = document.getElementById('selcat').value = categ;

   if (document.getElementById('tagname').value == '') {
      document.getElementById('tagname').value = r;
   } else {
      document.getElementById('tagname').value = r;
   }
}
function generate12(str1) {
   var theurl = str1;
   var youtubeoccr1 = theurl.indexOf("youtube");
   var youtubeoccr2 = theurl.indexOf("youtu.be");
   if (youtubeoccr1 !== - 1 || youtubeoccr2 !== - 1){
      document.getElementById('generate').style.visibility = "visible";
   } else {
      document.getElementById('generate').style.visibility = "hidden";
   }
}
function createObject() {
   var request_type;
   var browser = navigator.appName;
   if (browser == "Microsoft Internet Explorer"){
      request_type = new ActiveXObject("Microsoft.XMLHTTP");
   } else{
      request_type = new XMLHttpRequest();
   }
   return request_type;
}
function assignurl(str) {
   if (str == "") {
      return false;
   }
   var match_exp = /http\:\/\/www\.youtube\.com\/watch\?v=[^&]+/;

   if (str.match(match_exp) == null) {
      var metacafe = /http:\/\/www\.metacafe\.com\/watch\/(.*?)\/(.*?)\//;

      if (str.match(metacafe) != null) {
         document.upload1111.url1.value = document.getElementById('url').value;
         document.getElementById('generate').style.display = "block";
         return false;
      } else {
         alert(enterVideoURL);
         document.getElementById('url').focus();
         document.upload1111.url.value = "1";
         return false;
      }
   } else {
      document.getElementById('generate').style.display = "block";
      document.upload1111.flv.value = document.getElementById('url').value;
      document.upload1111.url1.value = "1";
      return false;
   }
}
function fileformate_check(thumburl) {
   if ((thumburl.value.length > 0)) {
      if ( thumburl.value.substring(thumburl.value.length - 3) == 'gif'
            || thumburl.value.substring(thumburl.value.length - 3) == 'GIF'
            || thumburl.value.substring(thumburl.value.length - 3) == 'JPG'
            || thumburl.value.substring(thumburl.value.length - 3) == 'jpg'
            || thumburl.value.substring(thumburl.value.length - 3) == 'PNG'
            || thumburl.value.substring(thumburl.value.length - 3) == 'png'
   ) {
      } else {
         alert(invalidFileFormat);
         thumburl.value = '';
         return false;
      }
   }
}
function enableEmbed() {
   embedFlag = document.getElementById('flagembed').value
   if (embedFlag != 1) {
      document.getElementById('embedcode').style.display = "block";
      document.getElementById('flagembed').value = '1';
      if (document.getElementById('reportadmin')) {
         document.getElementById('reportadmin').style.display = 'none';
         document.getElementById('flagreport').value = '0';
      }
      if (document.getElementById('iframecode')) {
         document.getElementById('iframecode').style.display = 'none';
         document.getElementById('flagiframe').value = '0';
      }
   }
   else {
      document.getElementById('embedcode').style.display = "none";
      document.getElementById('flagembed').value = '0';
      if (document.getElementById('reportadmin')) {
         document.getElementById('reportadmin').style.display = 'none';
         document.getElementById('flagreport').value = '0';
      }
      if (document.getElementById('iframecode')) {
         document.getElementById('iframecode').style.display = 'none';
         document.getElementById('flagiframe').value = '0';
      }
   }
}
function enableIFrame() {
   iframeFlag = document.getElementById('flagiframe').value
   if (iframeFlag != 1) {
      document.getElementById('iframecode').style.display = "block";
      document.getElementById('flagiframe').value = '1';
      if (document.getElementById('reportadmin')) {
         document.getElementById('reportadmin').style.display = 'none';
         document.getElementById('flagreport').value = '0';
      }
      if (document.getElementById('embedcode')) {
         document.getElementById('embedcode').style.display = 'none';
         document.getElementById('flagembed').value = '0';
      }
   }
   else {
      document.getElementById('iframecode').style.display = "none";
      document.getElementById('flagiframe').value = '0';
      if (document.getElementById('reportadmin')) {
         document.getElementById('reportadmin').style.display = 'none';
         document.getElementById('flagreport').value = '0';
      }
      if (document.getElementById('embedcode')) {
         document.getElementById('embedcode').style.display = 'none';
         document.getElementById('flagembed').value = '0';
      }
   }
}
function showreport() {
   reportFlag = document.getElementById('flagreport').value
   if (reportFlag != 1) {
      document.getElementById('reportadmin').style.display = "block";
      document.getElementById('flagreport').value = '1';
      if (document.getElementById('embedcode')) {
         document.getElementById('embedcode').style.display = 'none';
         document.getElementById('flagembed').value = '0';
      }
      if (document.getElementById('iframecode')) {
         document.getElementById('iframecode').style.display = 'none';
         document.getElementById('flagiframe').value = '0';
      }  
   } else {
      document.getElementById('reportadmin').style.display = "none";
      document.getElementById('flagreport').value = '0';
      if (document.getElementById('embedcode')) {
         document.getElementById('embedcode').style.display = 'none';
         document.getElementById('flagembed').value = '0';
      }
      if (document.getElementById('iframecode')) {
         document.getElementById('iframecode').style.display = 'none';
         document.getElementById('flagiframe').value = '0';
      }      
   }
}
function closereport() {
   document.getElementById('reportadmin').style.display = 'none';
}
function parentvalue(parentid) {
   document.getElementById('parentvalue').value = parentid;
   document.getElementById('username').focus();
}
function textdisplay(rid) {
   if (document.getElementById('divnum').value > 0) {
      document.getElementById(document.getElementById('divnum').value).innerHTML = "";
   }
   document.getElementById('initial').innerHTML = " ";
   var r = rid;
   var d = document.getElementById('txt').innerHTML;
   document.getElementById(r).innerHTML = d;
   document.getElementById('txt').style.display = "none";
   document.getElementById('divnum').value = r;
}
function hidebox() {
   document.getElementById('txt').style.display = "none";
   document.getElementById('initial').innerHTML = " ";
}
function CountLeft(field, count, max) {
   // if the length of the string in the input field is greater than the max value, trim it
   if (field.value.length > max) {
      field.value = field.value.substring(0, max);
      count.value = max - field.value.length;
   }
   else {
      count.value = max - field.value.length;
   }
}
function comments() {
   var d = document.getElementById('txt').innerHTML;
   document.getElementById('initial').innerHTML = d;
}

var http = createObject();
function  playlistresponse () {
   alert(http.response.html) 
} 
function delete_playlist(playListID) {
   var flg = confirm('Do you Really Want To Delete This playlist? \n\nClick OK to continue. Otherwise click Cancel.\n');
   if ( !flg)  {   
      return false;
   }
}
function generateyoutubedetail(){
   var videourl = document.getElementById('Youtubeurl').value;
   if(document.getElementById('videouploadLoading')){
   document.getElementById('videouploadLoading').style.display = "block";
   }
   var match = videourl.match(/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/);
   if(match&&match[7].length==11) {
       var video_id = match[7];
   } else {
       alert('Cannot fetch YouTube video id from your URL');
       false;
   }
   http.open('get',frontbase+'index.php?option=com_contushdvideoshare&task=youtubeurl&tmpl=component&videourl=' + video_id,
   true);
   http.onreadystatechange = insertReply;
   http.send(null);
}
function insertReply() {
   if (http.readyState == 4) {
        var result = http.responseText;
        if(document.getElementById('videouploadLoading')){
        document.getElementById('videouploadLoading').style.display = "none";
        }
        var resarray = JSON.parse(result);
        document.getElementById('videotitle').value = resarray.title;
           document.getElementById('Youtubeurl').value = resarray.urlpath;
           document.getElementById('description').innerHTML = resarray.description;
           if (typeof resarray.tags === 'undefined') {
              document.getElementById('tags1').value = '';
           } else {
              document.getElementById('tags1').value = resarray.tags;
           }

   }
}
function addWatchLater(videoId, baseUrl, elementId) {
	jQuery(elementId).find(".watch_later").removeClass( "success-watch-later error-watch-later default-watch-later" );
	jQuery(elementId).find(".watch_later").addClass( "loading-watch-later" );
	var requesturl = "index.php?option=com_contushdvideoshare&tmpl=component&task=watchlater";
	jQuery.ajax({
		url:requesturl,
		type:"POST",
		data:"&vid="+videoId,
		success : function( result ){
			if( result == 1  ) {
				jQuery(elementId).find(".watch_later").removeClass( "loading-watch-later error-watch-later default-watch-later" );
				jQuery(elementId).find(".watch_later").addClass( "success-watch-later" );
				jQuery(elementId).find(".watch_later").attr("title", addedToWatchLater);
			}
			if( result == 0  ) {
				jQuery(elementId).find(".watch_later").removeClass( "loading-watch-later success-watch-later default-watch-later" );
				jQuery(elementId).find(".watch_later").addClass( "error-watch-later" );
				jQuery(elementId).find(".watch_later").attr("title", addWatchLaterError);
			}
			jQuery(elementId).attr("onclick", "");
		}
		 
	});
}
function removeWatchLater(userId){
	var confirmStatus = confirm(confirmRemoveWatchLater);
	if (confirmStatus == true) {
		var requesturl = "index.php?option=com_contushdvideoshare&tmpl=component&task=removewatchlater";
		jQuery.ajax({
			url:requesturl,
			type:"POST",
			data:"&userId="+userId,
			success : function( result ){
				if( result == 1  ) {
					alert(watchLaterCleared);
					location.reload();
				}
				if( result == 0  ) {
					alert(clearWatchLaterError);
					location.reload();
				}
			}
			 
		});
	}
}
/**
 * Function to clear the videos from the user history
 * 
 * @param string event clear whole history or single video
 * @param int video id
 */
function ClearHistory(event,VideoId){
   if(event == 'all'){
      confirmStatus = confirm(confirmClearWatchHistory);
   } else{
      confirmStatus = confirm(confirmClearWatchHistorySingle);
	}
	if (confirmStatus == true) {
		var url 	= "index.php?option=com_contushdvideoshare&task=ClearHistory";
		jQuery.ajax({
			url: url,
			type: "POST",
			data: ({event:event,VideoId:VideoId}),
			success: function(data){
					location.reload();
			} 
		});
	}
}
/**
 * Function to Pause/resume the history status of the user
 * 
 * @param int history status 0/1
 */
function PauseHistory(status){
	if(status == "0"){
		var statusText=pauseWatchHistory;
		var onClickValue=1;
	}else{
		var statusText=resumeWatchHistory;
		var onClickValue=0;
	}
	var url 	= "index.php?option=com_contushdvideoshare&task=PauseHistory";
	jQuery.ajax({
		url: url,
		type: "POST",
		data: ({status:status}),
		success: function(data){
			if(data=="success")
			{
				jQuery("#PauseHistory").attr("onclick", "PauseHistory("+onClickValue+");");
				jQuery("#PauseHistory").text( statusText );
			}
		} 
	});
}
function nowPlaying(videoId, relatedVideoIds)  {
	var requesturl = "index.php?option=com_contushdvideoshare&tmpl=component&task=videoPlaying";
	jQuery.ajax({
		url:requesturl,
		type:"POST",
		data:"&videoId="+videoId,
		success : function( result ){}
	});
}