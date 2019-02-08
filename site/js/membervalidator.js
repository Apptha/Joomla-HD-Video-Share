/**
 * Field Validation for HD Video Share
 *
 * This file is to validate the fields of Commenting users in HD Video Share component
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
function videoupload() {
   if (document.getElementById("filetype2") && document.getElementById("filetype2").checked === true) {
      if (document.getElementById("Youtubeurl").value === "" || document.getElementById("Youtubeurl").value === " ") {
         alert("Please Enter the Video URL");
         document.getElementById("Youtubeurl").focus();
         return false;
      } else {
         var e = document.getElementById("Youtubeurl").value;
         if (e.indexOf("youtube.com") > -1 || e.indexOf("vimeo.com") > -1 || e.indexOf("viddler.com") > -1 || e.indexOf("dailymotion.com") > -1 || e.indexOf("youtu.be") > -1) {
         } else {
            alert("URL invalid. Try again.");
            document.getElementById("Youtubeurl").focus();
            return false;
         }
      }
   }
   if (document.getElementById("filetype3") && document.getElementById("filetype3").checked === true) {
      if (document.getElementById("Youtubeurl").value == "" || document.getElementById("Youtubeurl").value == " ") {
         alert("Please Enter the Video URL");
         document.getElementById("Youtubeurl").focus();
         return false;
      } else {
         var e = document.getElementById("Youtubeurl").value; 
         var t = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/;
         if (!t.test(e)) {
            alert("URL invalid. Try again.");
            document.getElementById("Youtubeurl").focus();
            return false;
         }
      }
   }
   if (document.getElementById("filetype4") && document.getElementById("filetype4").checked === true) {
      var n = document.getElementById("streamname").value;
      var r = document.getElementById("islive2").checked;
      if (n == "") {
         alert("You must provide a streamer path!");
         return false;
      }
      var t = /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/;
      if (!t.test(n)) {
         alert("Please enter a valid streamer path");
         document.getElementById("streamname").focus();
         return false;
      }
      document.getElementById("streamerpath-value").value = n;
      if (r == true) {
         document.getElementById("islive-value").value = 1;
      } else {
         document.getElementById("islive-value").value = 0;
      }
      if (document.getElementById("Youtubeurl").value == "" || document.getElementById("Youtubeurl").value == " ") {
         alert("Please Enter the Video URL");
         document.getElementById("Youtubeurl").focus();
         return false;
      }
   }
   if (document.getElementById("filetype5") && document.getElementById("filetype5").checked === true) {
      if (document.getElementById("embed_code").value == "" || document.getElementById("embed_code").value == " ") {
         alert("Please Enter the Embed Code");
         document.getElementById("embed_code").focus();
         return false;
      }
   }
   if (document.getElementById("filetype6") && document.getElementById("filetype6").checked === true) {
      if (document.getElementById("ffmpeg").value == "" && document.getElementById("seltype").value == 6) {
         alert("Please Select Upload Video");
         document.getElementById("ffmpeg").focus();
         return false;
      }
   }
   if (document.getElementById("Youtubeurl") && document.getElementById("Youtubeurl").value === "" && document.getElementById("seltype").value === 2) {
      alert("Please Enter Video Url");
      document.getElementById("normalvideoformval").focus();
      return false;
   }
   if (document.getElementById("normalvideoformval").value == "" && document.getElementById("seltype").value == 1) {
      alert("Please Select Upload Video");
      document.getElementById("normalvideoformval").focus();
      return false;
   }
   if (document.getElementById("thumbimageformval").value == "" && document.getElementById("seltype").value == 1) {
      alert("Please Select Thumb Image");
      document.getElementById("thumbimageformval").focus();
      return false;
   }
   if (document.getElementById("videotitle").value == "") {
      alert("Please Enter Your Video Title");
      document.getElementById("videotitle").focus();
      return false;
   }
   if (document.getElementById("tagname").value == "") {
      alert("Please Choose Video Category");
      document.getElementById("tagname").focus();
      return false;
   }
   return true;
}
