<?php
/**
 * Admin videos template file
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
include_once (JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'helper.php');
/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );
/** Get edit video details to display */
$editVideo = $this->editvideo;
/** Get player settings and unserialize */
$player_values = $this->player_values ;
/** Get eidtor object */
$editor = JFactory::getEditor ();
$usergroups = $editVideo ['user_groups'];
/** Get user and document object */
$user = JFactory::getUser ();
$document = JFactory::getDocument ();
/** Include js file and import joomla tooltip library */
$document->addScript ( JURI::base () . 'components/com_contushdvideoshare/js/upload_script.js' );
JHTML::_ ( 'behavior.tooltip' );
/** Internal css for admin videos layout Starts*/?>
<style type="text/css">
fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
form { float: left; }
fieldset input,fieldset textarea,fieldset select,fieldset img,fieldset button { float: none; }
table.adminlist .radio_algin input[type="radio"] { margin: 0 5px 0 0; }
fieldset label,fieldset span.faux-label { float: none; clear: left; display: block; margin: 5px 0; }
#video_det_id td.radio_algin input[type="radio"],#video_upload_part td.radio_algin input[type="radio"] { margin: 0 4px 0 0; }
#videouploadLoading { background: url(components/com_contushdvideoshare/images/page_loading.gif) no-repeat center center; left:-76%; height:50px; width:50px; position:relative; z-index:1002; top:50%; margin:3px 0 0 -25px; }
#video_det_id .table-striped td.radio_algin span,#video_upload_part .table-striped td.radio_algin span { line-height: 14px; display:block; float:left; margin-right:13px; }
</style>
<?php /** Internal css for admin videos layout ens*/ ?>
<script language="JavaScript" type="text/javascript">
<?php /** check condition for higher version */ ?>
<?php if (version_compare(JVERSION, '1.6.0', 'ge')) { ?> 
<?php /** Joomla submit button for version greater than 1.6.0 */ ?>
  Joomla.submitbutton = function(pressbutton){ 
<?php } else { ?> 
  function submitbutton(pressbutton) { <?php } ?>
    var form = document.adminForm;
    <?php /** check condition for cancel button */ ?> 
    if (pressbutton == 'CANCEL7') {
    <?php /** Function call for cancel task */ ?>   
       submitform(pressbutton); 
       return; 
    }
    <?php /** check condition for video upload button */ ?>  
    if (pressbutton == 'addvideoupload') {
      <?php /** Function call for videoupload task */ ?>    
       submitform(pressbutton); 
       return; 
    }
    <?php /** Do field validation */ ?> 
    if (pressbutton == "savevideos" || pressbutton == "applyvideos") {
       <?php /** varaible declaration to store file paths. */ ?>  
       var bol_file1 = (document.getElementById('filepath1').checked); 
       var bol_file2 = (document.getElementById('filepath2').checked); 
       var bol_file3 = (document.getElementById('filepath3').checked); 
       var bol_file4 = (document.getElementById('filepath4').checked); 
       bol_file5 = false;
       var bol_file5 = (document.getElementById('filepath5').checked); 
       <?php /** set default values.*/ ?>  
       var streamer_name = ''; 
       var islive = ''; 
       var stream_opt = document.getElementsByName('streameroption[]'); 
       var length_stream = stream_opt.length; 
       for (i = 0; i < length_stream; i++) {
        <?php /** check if streamer option is enabled.*/ ?> 
          if (stream_opt[i].checked == true) {
         <?php /** Get streameroption-value.*/ ?>  
             document.getElementById('streameroption-value').value = stream_opt[i].value;
             <?php /** Check streamer option is RTMP.*/ ?>   
             if (stream_opt[i].value == 'rtmp') {
             <?php /** Get streamer name.*/ ?> 
                streamer_name = document.getElementById('streamname').value;
                <?php /** Check streamer option is live.*/ ?>  
                var islivevalue2 = (document.getElementById('islive2').checked);
                <?php /** Check if streamer name is empty.*/ ?> 
                if (streamer_name == '') { 
                   alert("<?php echo JText::_('You must provide a streamer path!', true); ?>") 
                   return false; 
                }
                <?php /** Match to validate the streamer path .*/ ?>  
                var tomatch = /(rtmp:\/\/|rtmpe:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(rtmp:\/\/|rtmpe:\/\/)/ 
                if (!tomatch.test(streamer_name)) {
                <?php /** Prompt user to enter valid streamer path.*/ ?>  
                   alert("<?php echo JText::_('Please enter a valid streamer path', true); ?>")
                   <?php /** Focus streameroption field. */ ?> 
                   document.getElementById('streameroption-value').focus();   
                   return false;
                }
                <?php /** Get streamer name. */ ?>
                document.getElementById('streamerpath-value').value = streamer_name;
                <?php /** check entered streamer is live or not */ ?> 
                if (islivevalue2 == true) {
                <?php /** Set 'islive' value as 1 */ ?> 
                   document.getElementById('islive-value').value = 1; 
                } else {
                <?php /** Set 'islive' value as 0 */ ?> 
                   document.getElementById('islive-value').value = 0; 
                } 
            } 
         }
      <?php /** End for each */ ?> 
      } 
      <?php /** validation for video url  
            * @ empty url 
            * @ valid url */?>
      <?php /** Check if upload method URL is selected. */ ?>  
       if (bol_file2 == true) {
        <?php /** Check if video URL field is empty */ ?>  
          if (document.getElementById('videourl').value == "") {
        <?php /** Prompt users to enter a video URL */ ?>  
             alert("<?php echo JText::_('You must provide a Video URL', true); ?>") 
             return; 
          } else {
        <?php /** Get the Videourl value */ ?> 
             var theurl = document.getElementById("videourl").value;
             <?php /** Match to validate the videourl */ ?> 
             var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/
                 <?php /** validate the videourl. */ ?> 
             if (!tomatch.test(theurl)) { 
                for (i = 0; i < length_stream; i++) {
                <?php /** Check if stream option is selected. */ ?>
                   if (stream_opt[i].checked == true) {
                    <?php /** Check if stream option value selected is not RTMP. */ ?> 
                      if (stream_opt[i].value != 'rtmp') {
                    <?php /** Prompt users to enter a valid URL */ ?> 
                         alert("<?php echo JText::_('Please Enter Valid URL', true); ?>")
                         <?php /** Focus the videourl field. */ ?> 
                         document.getElementById("videourl").focus(); 
                         return false;
                      } 
                   } 
                } 
           }
          if(theurl.indexOf("youtu") > 0 ){
        	  for (i = 0; i < length_stream; i++) {
                  <?php /** Check if stream option is selected. */ ?>
                     if (stream_opt[i].checked == true) {
                      <?php /** Check if stream option value selected is not RTMP. */ ?> 
                        if (stream_opt[i].value == 'lighttpd') {
                      <?php /** Prompt users to enter a valid URL */ ?> 
                           alert("<?php echo JText::_('Please Enter Valid URL', true); ?>")
                           <?php /** Focus the videourl field. */ ?> 
                           document.getElementById("videourl").focus(); 
                           return false;
                        } 
                     } 
             } 
          }
        }
          <?php /** set fileoption value as URL. */ ?>
        document.getElementById('fileoption').value = 'Url';
        <?php /** check if videourl is empty. */ ?>  
        if (document.getElementById('videourl').value != "") {
        <?php /** Get videourl value. */ ?> 
           document.getElementById('videourl-value').value = document.getElementById('videourl').value.split("").reverse().join("");
        }
        <?php /** check if thumburl value is empty */ ?> 
        if (document.getElementById('thumburl').value != "") {
        <?php /** Get thumb url value. */ ?>  
           document.getElementById('thumburl-value').value = document.getElementById('thumburl').value.split("").reverse().join("");
           <?php /** variable declaration for thumburl */ ?> 
           thumbUrl = document.getElementById('thumburl').value;
           <?php /** Match to validate the thumb url */ ?>  
           var thumburlregex = thumbUrl.match("^(http:\/\/|https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
           <?php /** Check if Thumb URL entered is valid */ ?>  
           if (thumburlregex == null) { 
        <?php /** Promp;y users to enter a valid thumb URL. */ ?> 
              alert('Please Enter Valid Thumb URL'); 
              return; 
           } 
        }
        <?php /** Check if preview videourl value is empty */ ?>  
        if (document.getElementById('previewurl').value != "") {
        <?php /** Get preview url value. */ ?>  
           document.getElementById('previewurl-value').value = document.getElementById('previewurl').value.split("").reverse().join("");
           <?php /** variable declaration for previewurl */ ?> 
           previewUrl = document.getElementById('previewurl').value;
           <?php /** Match to validate previewvideo url */ ?>  
           var previewurlregex = previewUrl.match("^(http:\/\/|https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
           <?php /** check if preview video url is valid */ ?> 
           if (previewurlregex == null) {
          <?php /** Prompt users to enter a valid preview url  */ ?>  
              alert('Please Enter Valid Preview URL'); 
              return; 
           } 
        }
        <?php /** Check if hdurl value is empty */ ?> 
        if (document.getElementById('hdurl').value != "") {
        	<?php /** Get hdvideo url*/ ?>  
           document.getElementById('hdurl-value').value = document.getElementById('hdurl').value.split("").reverse().join("");
           <?php /** variable declaration for hdurl */ ?>  
           hdUrl = document.getElementById('hdurl').value;
           <?php /** Match to validate the hdurl. */ ?> 
           var hdurlregex = hdUrl.match("^(http:\/\/|https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
           <?php /** check if hdurl is valid */ ?> 
           if (hdurlregex == null) {
            <?php /** Prompt users to enter a valid hdurl. */ ?> 
              alert('Please Enter Valid HD URL'); 
              return; 
           }
        } 
    }
    <?php /** validation for Upload File @ Upload a video */?>
    <?php /** check if video upload method 'File' is selected */ ?>
    if (bol_file1 == true)  {
      <?php /** set fileoption as 'File' */ ?> 
       document.getElementById('fileoption').value = 'File'; 
       if (uploadqueue.length != "") {
        <?php /** Prompt user upload process in progress */ ?> 
          alert("<?php echo JText::_('Upload in Progress', true); ?>"); 
          return; 
       } 
       if (document.getElementById('id').value == "") {
          <?php /** check if noremalvideoformvalue and hdvideoform values are empty */ ?> 
          if (document.getElementById('normalvideoform-value').value == "" && document.getElementById('hdvideoform-value').value == "") {
          <?php /** Prompt users to upload a video */ ?> 
             alert("<?php echo JText::_('You must Upload a Video', true); ?>"); 
             return; 
          }
          <?php /** Check if thuumbimage form value is empty */ ?> 
          if (document.getElementById('thumbimageform-value').value == "") {
          <?php /** Prompt users to upload thumb image. */ ?> 
             alert("<?php echo JText::_('You must Upload a Thumb Image', true); ?>"); 
             return; 
          } 
       } 
    }
    <?php
    /** validation for Video File 
     * @ Upload a video file
     * @ Youtube and vimeo */     ?>
    <?php /** Check if upload method Youtube/vimeo is selected. */ ?> 
    if (bol_file4 == true) {
      <?php /** check the videourl value for Youtube/vimeo is empty */ ?> 
       if (document.getElementById('videourl').value == "") {
      <?php /** Prompt users to enter a valid Youtube/vimeo URL. */ ?> 
          alert("<?php echo JText::_('You must provide a Video URL', true); ?>") 
          return; 
       } else {
        <?php /** check the videourl value for Youtube/vimeo/Dailymotion is empty */ ?> 
          var theurl = document.getElementById("videourl").value;
          <?php /** check the videourl contains the valid domain names of Youtube/Vimeo/Dailymotion/Viddler */ ?> 
          if ( theurl.contains("youtube.com") || theurl.contains("vimeo.com") || theurl.contains("youtu.be") || theurl.contains("dailymotion") || theurl.contains("viddler") ) { 
          <?php /** check if file option Youtube.  */ ?>
              document.getElementById('fileoption').value = 'Youtube';
              <?php /** check if videoulr empty for video file method.  */ ?> 
             if (document.getElementById('videourl').value != "") 
                document.getElementById('videourl-value').value = document.getElementById('videourl').value.split("").reverse().join(""); 
          } else { 
          <?php /** Prompt users to enter a valid videofile url of Youtube/Vimeo/Dailymotion/Viddler   */ ?>
             alert("<?php echo JText::_('Please Enter Valid Youtube or Vimeo or Dailymotion or Viddler url', true); ?>")
              <?php /** Focus videourl field of video file field.   */ ?>
             document.getElementById("videourl").focus(); 
             return false; 
          } 
       } 
    }
    <?php /** validation for embed code */ ?>
    <?php /** check if upload method 'Embed' is selected. */ ?>  
    if (bol_file5 == true) {
      <?php /** Get the values of embed code. */ ?> 
        var embed_code = document.getElementById('embed_code').value;
        <?php /** set file option as embed. */ ?> 
        document.getElementById('fileoption').value = 'Embed';
        <?php /** validate embed code. */ ?> 
        embed_code = (embed_code + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0'); 
        document.getElementById('embedcode').value = embed_code;
        <?php /** check if embed code is empty */ ?> 
        if (embed_code === ''){
        <?php /** Prompt users to enter the embed code. */ ?> 
           alert("<?php echo JText::_('You must provide Embed code', true); ?>") 
           return; 
        }
        if (document.getElementById('id').value == "") {
        <?php /** check if thumimage for the embed code provided. */ ?> 
           if (document.getElementById('thumbimageform-value').value == "") {
        	   <?php /** Prompt users to enter a thumb image for embed code. */?> 
              alert("<?php echo JText::_('You must Upload a Thumb Image', true); ?>"); 
              return; 
           } 
        } 
    } 
    if (bol_file3 == true) { 
       document.getElementById('fileoption').value = 'FFmpeg'; 
       if (uploadqueue.length != "") { 
          alert("<?php echo JText::_('Upload in Progress', true); ?>"); 
          return; 
       } 
       if (document.getElementById('ffmpegform-value').value == "") { 
          alert("<?php echo JText::_('You must Upload a Video', true); ?>"); 
          return; 
       } 
    }
    <?php /** validation for Video Title */ ?>
    if (document.getElementById('title').value == "") { 
        alert("<?php echo JText::_('You must provide a Title', true); ?>"); 
        return; 
    }
    <?php /** validation for Video subtitle */ ?>
    if (document.getElementById('subtitle_video_srt1form-value').value !== "") { 
        if (document.getElementById('subtitle_lang1').value === "") { 
           alert("<?php echo JText::_('You must provide SubTitle1', true); ?>"); 
           document.getElementById('subtitle_lang1').focus(); 
           return; 
        } else { 
           document.getElementById('subtile_lang1').value = document.getElementById('subtitle_lang1').value; 
        } 
    }
    if (document.getElementById('subtitle_video_srt2form-value').value !== "") { 
       if (document.getElementById('subtitle_lang2').value === "") { 
          alert("<?php echo JText::_('You must provide SubTitle2', true); ?>"); 
          document.getElementById('subtitle_lang2').focus(); 
          return; 
       } else { 
          document.getElementById('subtile_lang2').value = document.getElementById('subtitle_lang2').value; 
       } 
    }
    <?php /** validation for Video Category */ ?>
    if (document.getElementById('playlistid').value == 0) { 
       alert("<?php echo JText::_('You must select a category', true); ?>") 
       return; 
    }
    submitform(pressbutton); 
    return;
  <?php /** End function */ ?>
  }
}
</script>
<?php $youtubefilepathchk = $isfilepathchk = $ffmpegchk = $embedchk = $filePathembed = ''; 
/** video fields start */ ?> 
<div style="position: relative;">
  <fieldset class="adminform">
  <?php /** Check jomla version and display title */ 
  if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?> 
  <legend>Video </legend> 
  <?php } else { ?> 
  <h2>Video</h2> 
  <?php } ?>   
  <table id="video_upload_part" 
    class="admintable <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
      echo 'table table-striped'; 
    } ?>"> 
    <?php /** Display streamer option */?>
      <tr> 
        <td> <?php echo JHTML::tooltip ( 'Select streamer option', '', '', 'Streamer Option' ); ?> </td> 
        <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
          echo 'class="radio_algin"'; 
        } ?>>
          <input type="radio" name="streameroption[]" id="streameroption1" value="None" checked="checked" onclick="streamer1('None');" />
          <span>None</span> 
          <input type="radio" name="streameroption[]" id="streameroption2" value="lighttpd" onclick="streamer1('lighttpd');" />
          <span>Lighttpd</span> 
          <input type="radio" name="streameroption[]" id="streameroption3" value="rtmp" onclick="streamer1('rtmp');" />
          <span>RTMP</span>
        </td>
      </tr>    
      <?php /** Display streamer path */?>  
      <tr id="stream1" name="stream1"> 
      <td><?php echo JHTML::tooltip ( 'Select Streamer Path', '', '', 'Streamer Path' ); ?></td> 
      <td><input type="text" name="streamname" id="streamname" style="width: 300px" maxlength="250" value="<?php echo $editVideo['rs_editupload']->streamerpath; ?>" /> </td>
      </tr>      
      <tr id="islive_visible" name="islive_visible"> 
        <td>Is Live</td> 
        <td class="radio_algin"><input type="radio" style="float: none;" name="islive[]" 
            id="islive2" <?php echo radioButtonCheck($editVideo ['rs_editupload']->islive); ?> value="1" /> 
            <span>Yes</span> 
            <input type="radio" style="float: none;" name="islive[]" 
            id="islive1" <?php echo radioButtonUnCheck($editVideo ['rs_editupload']->islive); ?> value="0" /><span>No</span> </td> 
      </tr>     
      <?php /** Display file upload option */?> 
      <tr> 
        <td width="200px;"><?php echo JHTML::tooltip ( 'Select file path', '', '', 'File Option' ); ?></td> 
        <td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
            echo 'class="radio_algin"'; 
          } ?>>
          <input type="radio" name="filepath" id="filepath1" <?php echo $isfilepathchk; ?> value="File" onclick="fileedit('File');" /> <span>File</span> 
          <input type="radio" name="filepath" id="filepath2" value="Url" onclick="fileedit('Url');" /> <span>URL</span> 
          <input type="radio" name="filepath" id="filepath4" <?php echo $youtubefilepathchk; ?> value="Youtube" onclick="fileedit('Youtube');" /> <span>YouTube / Vimeo / DailyMotion / Viddler</span> 
          <input type="radio" name="filepath" id="filepath3" <?php echo $ffmpegchk; ?> value="FFmpeg" onclick="fileedit('FFmpeg');" /><span>FFmpeg</span> 
          <input type="radio" name="filepath" id="filepath5" <?php echo $embedchk; ?> value="Embed" onclick="fileedit('Embed');" /><span>Embed Video</span>
        </td>
      </tr>      
      <?php /** Display embed code option */?>
      <tr id="ffmpeg_disable_new9" name="ffmpeg_disable_edit9" style="display: none"> 
          <td><?php echo JHTML::tooltip('Enter Embed Code', '', '', 'Embed Code'); ?></td> 
          <td> <textarea id="embed_code" name="embed_code" rows="5" cols="60" style="width: 300px">
          <?php if (isset ( $editVideo ['rs_editupload']->embedcode )) { 
            echo stripslashes ( $editVideo ['rs_editupload']->embedcode ); 
          } ?> </textarea> </td> 
      </tr>      
      <?php /** Display upload button to upload video */ ?>
      <tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1"> 
        <td> <?php echo JHTML::tooltip('Select video to upload', '', '', 'Upload Video');?> </td> 
        <td>  <div id="f1-upload-form"> <form name="normalvideoform" method="post" enctype="multipart/form-data"> 
              <input type="file" name="myfile" id="myfile" onchange="enableUpload(this.form.name);" /> 
              <input type="button" name="uploadBtn" <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
                echo 'class="modal btn"'; 
              } ?> value="Upload Video" style="margin: 2px 0 0 5px;" disabled="disabled" onclick="addQueue(this.form.name);" /> 
              <label id="lbl_normal"> <?php if ($editVideo ['rs_editupload']->filepath == 'File') { 
                echo $editVideo ['rs_editupload']->videourl; 
              } ?> </label> <input type="hidden" name="mode" value="video" /> 
              </form> </div>              
              <div id="f1-upload-progress" style="display: none"> 
                <table <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
                  echo 'class="adminlist table table-striped"'; 
                } ?>>
                <tr> <td><img id="f1-upload-image" style="float: left;" src="components/com_contushdvideoshare/images/empty.gif" alt="Uploading" /></td> 
                  <td><span style="float: left; clear: none; font-weight: bold;" id="f1-upload-filename">&nbsp;</span></td> 
                  <td><span id="f1-upload-message" style="float: left;"> </span> <label id="f1-upload-status" style="float: left;"> &nbsp; </label></td> 
                  <td><span id="f1-upload-cancel"> <a style="float: left; font-weight: bold" href="javascript:cancelUpload('normalvideoform');" name="submitcancel">Cancel</a> </span></td> 
                </tr> 
                </table> </div> 
        </td>
      </tr> 
      <?php /** Display upload button to upload hd video */ ?>
      <tr id="ffmpeg_disable_new2" name="ffmpeg_disable_new2"> 
        <td><?php echo JHTML::tooltip('Select Hdvideo to upload', '', '', 'Upload HD Video(optional)');?></td> 
        <td> <div id="f2-upload-form"> <form name="hdvideoform" method="post" enctype="multipart/form-data"> 
        <input type="file" name="myfile" id="myfile1" onchange="enableUpload(this.form.name);" /> 
        <input type="button" name="uploadBtn" <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
          echo 'class="modal btn"'; 
        } ?> value="Upload HD Video" style="margin: 2px 0 0 5px;" disabled="disabled" onclick="addQueue(this.form.name);" /> <label>
        <?php if ($editVideo ['rs_editupload']->filepath == 'File') { 
          echo $editVideo ['rs_editupload']->hdurl; 
        } ?></label> 
        <input type="hidden" name="mode" value="video" /> 
        </form> </div>         
        <div id="f2-upload-progress" style="display: none"> 
        <table <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
          echo 'class="adminlist table table-striped"'; 
        } ?>> 
        <tr> <td> <img id="f2-upload-image" style="float: left;" src="components/com_contushdvideoshare/images/empty.gif" alt="Uploading" /></td> 
          <td><span style="float: left; clear: none; font-weight: bold;" id="f2-upload-filename">&nbsp;</span></td> 
          <td><span id="f2-upload-message" style="float: left;"> </span> <label id="f2-upload-status" style="float: left;"> &nbsp; </label></td> 
          <td><span id="f2-upload-cancel"> <a style="float: left; font-weight: bold" href="javascript:cancelUpload('hdvideoform');" name="submitcancel">Cancel</a> </span></td> 
          </tr> </table> </div> 
          </td> 
        </tr> 
        <?php /** Display upload button to upload thumb */ ?>
        <tr id="ffmpeg_disable_new3" name="ffmpeg_disable_new3"> <td><?php echo JHTML::tooltip('Select thumb image to upload', '', '', 'Upload Thumb Image');?></td> 
        <td> <div id="f3-upload-form"> <form name="thumbimageform" method="post" enctype="multipart/form-data"> 
        <input type="file" name="myfile" id="myfile2" onchange="enableUpload(this.form.name);" /> 
        <input type="button" name="uploadBtn" <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
          echo 'class="modal btn"'; 
        } ?> value="Upload Thumb Image" style="margin: 2px 0 0 5px;" disabled="disabled" onclick="addQueue(this.form.name);" /> <label> 
        <?php if ($editVideo ['rs_editupload']->filepath == 'File' || $editVideo ['rs_editupload']->filepath == 'Embed') { 
          echo $editVideo ['rs_editupload']->thumburl; 
        } ?> 
        </label> <input type="hidden" name="mode" value="image" /> 
        </form> </div> 
        <div id="f3-upload-progress" style="display: none"> 
        <table <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="adminlist table table-striped"';
      }
      ?>> <tr> <td><img id="f3-upload-image" style="float: left;"
            src="components/com_contushdvideoshare/images/empty.gif"
            alt="Uploading" /></td>
            <td><span style="float: left; clear: none; font-weight: bold;"
            id="f3-upload-filename">&nbsp;</span></td>
            <td><span id="f3-upload-message" style="float: left;"> </span> <label
            id="f3-upload-status" style="float: left;"> &nbsp; </label></td>
            <td><span id="f3-upload-cancel"> <a
            style="float: left; font-weight: bold"
            href="javascript:cancelUpload('thumbimageform');"
            name="submitcancel">Cancel</a>
            </span></td>
            </tr>
          </table>
        </div>
       </td>
      </tr>
<?php /** Display upload button to upload preview */ ?>
      <tr id="ffmpeg_disable_new4" name="ffmpeg_disable_new4">
      <td><?php echo JHTML::tooltip('Select preview image to upload', '', '', 'Upload Preview Image(optional)');?>
      </td>
      <td>
      <div id="f4-upload-form">
      <form name="previewimageform" method="post" enctype="multipart/form-data">
      <input type="file" name="myfile" id="myfile3" onchange="enableUpload(this.form.name);" /> <input type="button"
      name="uploadBtn" <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'class="modal btn"';
        }
        ?> value="Upload Preview Image" style="margin: 2px 0 0 5px;"
            disabled="disabled" onclick="addQueue(this.form.name);" /> <label><?php
        if ($editVideo ['rs_editupload']->filepath == 'File') {
          echo $editVideo ['rs_editupload']->previewurl;
        }
        ?></label> <input type="hidden" name="mode" value="image" />
      </form>
</div>
<div id="f4-upload-progress" style="display: none">
<table <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="adminlist table table-striped"';
      }
      ?>> <tr>
        <td><img id="f4-upload-image" style="float: left;"
        src="components/com_contushdvideoshare/images/empty.gif"
        alt="Uploading" /></td>
        <td><span style="float: left; clear: none; font-weight: bold;"
        id="f4-upload-filename">&nbsp;</span></td>
        <td><span id="f4-upload-message" style="float: left;"> </span> <label
        id="f4-upload-status" style="float: left;"> &nbsp; </label></td>
        <td><span id="f4-upload-cancel"> <a
        style="float: left; font-weight: bold"
        href="javascript:cancelUpload('previewimageform');"
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
        <?php /** Display field to enter yoututbe and vimeo url */ ?>
        <tr id="ffmpeg_disable_new5" name="ffmpeg_disable_edit5"
        style="width: 200px;">
        <td><?php echo JHTML::tooltip('Enter Youtube/Vimeo/Video URL', '', '', 'Video URL'); ?></td>
        <td>
        <div style="float: left; margin-right: 5px;">
        <input type="text" name="videourl" style="width: 300px"
        id="videourl" size="100" onkeyup="generate12(this.value);" onpaste="e = this; setTimeout(function(){generate12(e.value);});"
        maxlength="250"
        value="<?php if ($editVideo ['rs_editupload']->filepath == 'Url' || $editVideo ['rs_editupload']->filepath == 'Youtube') {
                echo $editVideo ['rs_editupload']->videourl;
              }
              ?>" />
</div>
          <div style="float: left; margin-top: 2px;">
          <input id="generate" type="submit" name="youtube_media" class="button-primary" value="Generate details" onclick="generateyoutubedetail();" />
          <div id="videouploadLoading" style="display: none;"></div>
          </div>
          </td>
          </tr>
          <?php /** Display field to enter hd video url */ ?>
          <tr id="ffmpeg_disable_new8" name="ffmpeg_disable_edit8">
          <td>
          <?php echo JHTML::tooltip('Enter HD Video URL (Eg:http://www.yourdomain.com/video.flv)', '', '', 'HD URL'); ?></td>
          <td><input type="text" name="hdurl" style="width: 300px" id="hdurl"
          size="100" maxlength="250"
          value="<?php if ($editVideo ['rs_editupload']->filepath == 'Url') {
                echo $editVideo ['rs_editupload']->hdurl;
              }
    ?>" /></td>
</tr>
        <?php /** Display field to enter thumb url */ ?>
        <tr id="ffmpeg_disable_new6" name="ffmpeg_disable_edit6">
        <td>
        <?php echo JHTML::tooltip('Enter Video Thumb URL (Eg:http://www.yourdomain.com/images)', '', '', 'Thumb URL'); ?></td>
        <td><input type="text" name="thumburl" style="width: 300px"
        id="thumburl" size="100" maxlength="250"
        value="<?php if ($editVideo ['rs_editupload']->filepath == 'Url') {
              echo $editVideo ['rs_editupload']->thumburl;
            }
            ?>" /></td>
</tr>
        <?php /** Display field to enter preview url */ ?>
        <tr id="ffmpeg_disable_new7" name="ffmpeg_disable_edit7">
        <td> <?php echo JHTML::tooltip('Enter Video Preview URL (Eg:http://www.yourdomain.com/images)', '', '', 'Preview URL'); ?></td>
        <td><input type="text" name="previewurl" style="width: 300px"
        id="previewurl" size="100" maxlength="250"
        value="<?php if ($editVideo ['rs_editupload']->filepath == 'Url') {
      echo $editVideo ['rs_editupload']->previewurl;
    }
    ?>" /></td>
</tr>
        <tr id="fvideos" name="fvideos">
        <td><?php echo JHTML::tooltip ( 'Select video to upload', '', '', 'Upload Video' ); ?></td>
        <td>
        <div id="f5-upload-form">
        <form name="ffmpegform" method="post"
        enctype="multipart/form-data">
        <input type="file" name="myfile" id="myfile4"
        onchange="enableUpload(this.form.name);" /> <input type="button"
        name="uploadBtn" value="Upload Video" disabled="disabled"
        <?php
        if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'class="modal btn"';
        }
        ?> onclick="addQueue(this.form.name);" /> <label><?php
        if ($editVideo ['rs_editupload']->filepath == 'FFmpeg') {
          echo $editVideo ['rs_editupload']->videourl;
        }
        ?></label> <input name="mode" type="hidden" value="video_ffmpeg" />
          </form> </div>
	<div id="f5-upload-progress" style="display: none"> <table
<?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="adminlist table table-striped"';
      } ?>> <tr>
            <td><img id="f5-upload-image" style="float: left;" src="components/com_contushdvideoshare/images/empty.gif"
            alt="Uploading" /></td> <td><span style="float: left; clear: none; font-weight: bold;"
            id="f5-upload-filename">&nbsp;</span></td>
            <td><span id="f5-upload-message" style="float: left;"> </span> <label
            id="f5-upload-status" style="float: left;"> &nbsp; </label></td>
            <td><span id="f5-upload-cancel"> <a style="float: left; font-weight: bold"
            href="javascript:cancelUpload('ffmpegform');"
            name="submitcancel">Cancel</a> </span></td>
            </tr> </table> </div>
            </td> </tr>
            <tr id="subtitle_video_srt1" name="subtitle_video_srt1">
            <td>
<?php echo JHTML::tooltip ( 'Select srt file to upload', '', '', 'Upload Video Subtitle1' );
    ?></td>
<td>
<div id="f7-upload-form">
<form name="subtitle_video_srt1form" method="post"
enctype="multipart/form-data">
<input type="file" name="myfile" id="myfile7" onchange="enableUpload(this.form.name);" /> <input type="button"
name="uploadBtn"
<?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'class="modal btn"';
        } ?> value="Upload Video Subtitle1" style="margin: 2px 0 0 5px;"
                    disabled="disabled" onclick="addQueue(this.form.name);" /> <label><?php
        if ($editVideo ['rs_editupload']->filepath != 'Embed') {
          echo $editVideo ['rs_editupload']->subtitle1;
        }
        ?></label> <input type="hidden" name="mode" value="srt" />
</form>
</div>
<div id="f7-upload-progress" style="display: none">
<table
<?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
        echo 'class="adminlist table table-striped"';
      } ?>>
<tr>
<td><img id="f7-upload-image" style="float: left;"
src="components/com_contushdvideoshare/images/empty.gif"
alt="Uploading" /></td>
<td><span style="float: left; clear: none; font-weight: bold;"
id="f7-upload-filename">&nbsp;</span></td>
<td><span id="f7-upload-message" style="float: left;"> </span> <label
id="f7-upload-status" style="float: left;"> &nbsp; </label></td>
<td><span id="f7-upload-cancel"> <a
style="float: left; font-weight: bold"
href="javascript:cancelUpload('subtitle_video_srt1form');"
name="submitcancel">Cancel</a>
</span></td>
</tr>
</table>
</div>
</td>
</tr>
<tr id="subtilelang1" style="display: none;">
<td width="17%">
<?php echo JHTML::tooltip('Enter subtile1 language', '', '', 'Subtile1 language'); ?></td>
<td width="83%"><input type="text" name="subtitle_lang1"
id="subtitle_lang1" style="width: 300px" maxlength="250"
value="<?php echo htmlentities($editVideo['rs_editupload']->subtile_lang1); ?>" /></td>
</tr>
<tr id="subtitle_video_srt2" name="subtitle_video_srt2">
<td>
<?php echo JHTML::tooltip ( 'Select srt file to upload', '', '', 'Upload Video Subtitle2' ); ?></td>
<td>
<div id="f8-upload-form">
<form name="subtitle_video_srt2form" method="post" enctype="multipart/form-data">
<input type="file" name="myfile" id="myfile8"
onchange="enableUpload(this.form.name);" /> <input type="button"
name="uploadBtn"
<?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
          echo 'class="modal btn"';
        }
        ?> value="Upload Video Subtitle2" style="margin: 2px 0 0 5px;" disabled="disabled" onclick="addQueue(this.form.name);" /> 
        <label><?php if ($editVideo ['rs_editupload']->filepath != 'Embed') {
          echo $editVideo ['rs_editupload']->subtitle2;
        }
        ?></label> 
        <input type="hidden" name="mode" value="srt" /> </form>
</div> <div id="f8-upload-progress" style="display: none">
<table <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) { 
  echo 'class="adminlist table table-striped"'; 
} ?>>
<tr> <td><img id="f8-upload-image" style="float: left;" src="components/com_contushdvideoshare/images/empty.gif"
alt="Uploading" /></td> <td><span style="float: left; clear: none; font-weight: bold;"
id="f8-upload-filename">&nbsp;</span></td>
<td><span id="f8-upload-message" style="float: left;"> </span> <label
id="f8-upload-status" style="float: left;"> &nbsp; </label></td>
<td><span id="f8-upload-cancel"> <a style="float: left; font-weight: bold" href="javascript:cancelUpload('subtitle_video_srt2form');"
name="submitcancel">Cancel</a> </span></td>
</tr>
</table> </div>
</td> </tr>
<tr id="subtilelang2" style="display: none;">
<td width="17%">
<?php echo JHTML::tooltip('Enter subtile2 language', '', '', 'Subtile2 language'); ?></td>
<td width="83%"><input type="text" name="subtitle_lang2"
id="subtitle_lang2" style="width: 300px" maxlength="250"
value="<?php echo htmlentities($editVideo['rs_editupload']->subtile_lang2); ?>" />
</td>
</tr>
</table>
</fieldset>
</div>
<?php /** video fields end */ 
/** video info form start */ ?>
<form action='index.php?option=com_contushdvideoshare&layout=adminvideos<?php echo (JRequest::getVar ( 'user', '', 'get' ) == 'admin') ? "&user=" . JRequest::getVar ( 'user', '', 'get' ) : ''; ?>'
	method="post" name="adminForm" id="adminForm"
	enctype="multipart/form-data" style="float: none; position: relative;">
	<fieldset class="adminform">
<?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) { ?>
    <legend>Video Info</legend>
<?php } else { ?> 
    <h2>Video Info</h2> 
<?php } ?>
<table id="video_det_id" <?php if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
    echo 'class="admintable"';
  } else {
    echo 'class="adminlist table table-striped"';
  } ?> width="100%">
  <tr> <td width="17%"><?php  echo JHTML::tooltip ( 'Enter title for the video', '', '', 'Title' ); ?></td>
<td width="83%"><input type="text" name="title" id="title"
style="width: 300px" maxlength="250"
value="<?php if (preg_match ( '/[\'^$%&*()}{@#~?><>,|=_+-]/', $editVideo ['rs_editupload']->title )) {
      echo htmlentities ( $editVideo ['rs_editupload']->title );
    } else {
      echo $editVideo ['rs_editupload']->title;
    } ?>" /></td>
</tr>
<tr>
<td><?php  echo JHTML::tooltip ( 'Enter description for the video', '', '', 'Description' ); ?></td>
<td>
<?php $imageDesc = "";
    if (isset ( $editVideo ['rs_editupload']->description )) {
      $imageDesc = $editVideo ['rs_editupload']->description;
    }    
    if (! version_compare ( JVERSION, '3.0.0', 'ge' )) {
      $display_width = 350;
    } else {
      $display_width = '100%';
    }    
    echo $editor->display ( 'description', $imageDesc, "'" . $display_width . "'", '200', '60', '20', false );
?> </td>
</tr>
<tr>
<td><?php echo JHTML::tooltip ( 'Enter tags for the video', '', '', 'Tags' ); ?></td>
<td><input type="text" name="tags" id="tags"
style="width: 300px; float: left;" maxlength="250"
value="<?php echo $editVideo['rs_editupload']->tags; ?>" /> <label>Separate
tags by comma</label></td>
</tr>
<tr id="target">
<td><?php  echo JHTML::tooltip ( 'Enter target url for the video (Not supported for vimeo)', '', '', 'Target URL' ); ?></td>
<td><input type="text" name="targeturl" id="targeturl"
style="width: 300px" maxlength="250"
value="<?php echo $editVideo['rs_editupload']->targeturl; ?>" /></td>
</tr>
<script language="JavaScript">
var user = new Array(<?php echo count($editVideo['rs_play']); ?>);
<?php  for($i = 0; $i < count ( $editVideo ['rs_play'] ); $i ++) { 
  $playlistnames = $editVideo ['rs_play'] [$i]; ?>  
  user[<?php echo $i; ?>] = new Array(2); 
  user[<?php echo $i; ?>][1] = 
     "<?php echo $playlistnames->id; ?>"; 
     user[<?php echo $i; ?>][0] = "<?php 
     echo $playlistnames->category; ?>"; 
<?php } ?>
</script>
<?php /** Display filter option for categories */ ?>
<tr>
<td><?php echo JHTML::tooltip ( 'Select option to filter categories', '', '', 'Filter by Category' ); ?></td>
<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="radio_algin"';
    } ?>><input type="radio" name="playliststart" id='playliststart0'
value="0z" <?php echo 'checked'; ?> onchange="select_alphabet('0z')" />All&nbsp;&nbsp; <input  type="radio" name="playliststart" id="playliststart1" value="AF"
onchange="select_alphabet('AF')" />A-F&nbsp;&nbsp; <input type="radio" name="playliststart" id='playliststart2' value="GL"
onchange="select_alphabet('GL')" />G-L&nbsp;&nbsp; <input type="radio" name="playliststart" id='playliststart3' value="MR"
onchange="select_alphabet('MR')" />M-R&nbsp;&nbsp; <input type="radio" name="playliststart" id='playliststart4' value="SV"
onchange="select_alphabet('SV')" />S-V&nbsp;&nbsp; <input type="radio" name="playliststart" value="WZ" id='playliststart5' 
onchange="select_alphabet('WZ')" />W-Z&nbsp;&nbsp; <input type="radio" id='playliststart6' name="playliststart" value="09" 
onchange="select_alphabet('09')" />0-9&nbsp;&nbsp;</td>
</tr>
<?php /** Display option to select category */ ?>
<tr>
<td><?php echo JHTML::tooltip ( 'Select category for the video', '', '', 'Category' ); ?></td>
<td><select name="playlistid" id="playlistid"> <option value="0" id="0">Uncategorised</option>
<?php $count = count ( $editVideo ['rs_play'] );
      if ($count >= 1) {
        for($i = 0; $i < $count; $i ++) {
          $row_play = &$editVideo ['rs_play'] [$i];
          ?>
          <option value="<?php echo $row_play->id; ?>" 
          id="<?php echo $row_play->id; ?>"> 
          <?php echo $row_play->category; ?> </option>
<?php } 
} ?> </select>
<?php  if ($editVideo ['rs_editupload']->playlistid) {
        echo '<script>document.getElementById("' . $editVideo ['rs_editupload']->playlistid . '").selected="selected"</script>';
      }      
      $selected = '';  ?>
</td>
</tr>
<tr>
<td><?php echo JHTML::tooltip ( 'Enter order for the video', '', '', 'Order' );
    ?></td>
<td><input type="text" name="ordering" id="ordering"
style="width: 50px" maxlength="250"
value="<?php
    echo $editVideo ['rs_editupload']->ordering;
    ?>" /></td>
</tr>
<tr>
<td><?php  echo JHTML::tooltip ( 'Select user access level (Not supported for vimeo)', '', '', 'User Acess Level' );
    ?></td>
<td><select name="useraccess">
<?php  for($i = 0; $i < count ( $usergroups ); $i ++) {
  $selected = '';
  
  if ($editVideo ['rs_editupload']->useraccess && $editVideo ['rs_editupload']->useraccess == $usergroups [$i]->id) {
      $selected = 'selected="selected"';
  }  
  echo '<option value=' . $usergroups [$i]->id . ' ' . $selected . ' >' . $usergroups [$i]->title . '</option>';
} ?> </select></td>
</tr>
<tr id="postroll-ad">
<td>
<?php echo JHTML::tooltip ( 'Post-roll ads (Not supported for vimeo)', '', '', 'Post-roll Ad' ); ?></td>
<?php $postRollEnable = $postRollDisable = '';
      
      if ($editVideo ['rs_editupload']->postrollads == '1') {
        $postRollEnable = "inside " . 'checked="checked" ';
      }      
      if ($editVideo ['rs_editupload']->postrollads == '0' || $editVideo ['rs_editupload']->postrollads == '') {
        $postRollDisable = 'checked="checked" ';
      } ?>
<td <?php  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="radio_algin"';
    }
    ?>><input type="radio" name="postrollads" id="postrollads"
<?php echo $postRollEnable; ?> value="1" onclick="postroll('1');" />Enable
<input type="radio" name="postrollads" id="postrollads"
<?php echo $postRollDisable; ?> value="0" onclick="postroll('0');" />Disable
</td>
</tr>
<tr id="postroll">
<td class="key"><?php echo JHTML::tooltip ( 'Post-roll Name (Not supported for vimeo)', '', '', 'Post-roll Name' );
    ?></td>
<td><select name="postrollid" id="postrollid">
<?php  $count = count ( $editVideo ['rs_ads'] );
if ($count >= 1) {
  for($i = 0; $i < $count; $i ++) {
    $row_Ads = &$editVideo ['rs_ads'] [$i];
    ?> <option value="<?php echo $row_Ads->id;
    ?>" id="5<?php echo $row_Ads->id;
    ?>" name="<?php echo $row_Ads->id;
    ?>"><?php  echo $row_Ads->adsname;
    ?> </option>   
    <?php }
}
?> </select>
<?php $prerolladsEnable = $prerolladsDisable = '';
      if ($editVideo ['rs_editupload']->postrollid) {
        echo '<script>document.getElementById("5' . $editVideo ['rs_editupload']->postrollid . '").selected="selected"</script>';
      }
      if ($editVideo ['rs_editupload']->prerollads == '1') {
        $prerolladsEnable = 'checked="checked" ';
      }
      if ($editVideo ['rs_editupload']->prerollads == '0' || $editVideo ['rs_editupload']->prerollads == '') {
        $prerolladsDisable = 'checked="checked" ';
      } ?> </td>
</tr>
<tr id="preroll-ad">
<td><?php echo JHTML::tooltip ( 'Pre-roll ads (Not supported for vimeo)', '', '', 'Pre-roll Ad' ); ?></td>
<td <?php if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="radio_algin"';
    }
    ?>><input type="radio" name="prerollads" id="prerollads"
<?php echo $prerolladsEnable; ?> value="1" onclick="preroll('1');" />Enable
<input type="radio" name="prerollads" id="prerollads"
<?php echo $prerolladsDisable; ?> value="0" onclick="preroll('0');" />Disable
</td>
</tr>
<tr id="preroll">
<td class="key"><?php echo JHTML::tooltip ( 'Pre-roll Name (Not supported for vimeo)', '', '', 'Pre-roll Name' ); ?></td>
<td><select name="prerollid" id="prerollid">
<?php  $count = count ( $editVideo ['rs_ads'] );
    if ($count >= 1) {
      for($v = 0; $v < $count; $v ++) {
        $row_Ads = &$editVideo ['rs_ads'] [$v]; ?> 
        <option value="<?php echo $row_Ads->id;
        ?>" id="6<?php echo $row_Ads->id;
        ?>" name="<?php echo $row_Ads->id;
        ?>"><?php echo $row_Ads->adsname; ?> 
        </option> <?php }
    } ?> </select>
<?php /** check ads section and download section values to display */ 
  if ($editVideo ['rs_editupload']->prerollid) {
      echo '<script>document.getElementById("6' . $editVideo ['rs_editupload']->prerollid . '").selected="selected"</script>';
    }    
    $downloadEnable = $downloadDisable = '';
    if ($editVideo ['rs_editupload']->download == '1' || $editVideo ['rs_editupload']->download == '') {
      $downloadEnable = 'checked="checked" ';
    }    
    if ($editVideo ['rs_editupload']->download == '0') {
      $downloadDisable = 'checked="checked" ';
    }    
    $midrollenable = $midrolldisable = '';    
    if ($editVideo ['rs_editupload']->midrollads == '1') {
      $midrollenable = 'checked="checked"';
    }    
    if ($editVideo ['rs_editupload']->midrollads == '0' || $editVideo ['rs_editupload']->midrollads == '') {
      $midrolldisable = 'checked="checked"';
    }    
    $imaenable = $imadisable = '';    
    if ($editVideo ['rs_editupload']->imaads == '1') {
      $imaenable = 'checked="checked"';
    }    
    if ($editVideo ['rs_editupload']->imaads == '0' || $editVideo ['rs_editupload']->imaads == '') {
      $imadisable = 'checked="checked"';
    }
    ?> </td>
</tr>
<tr>
<td><?php echo JHTML::tooltip('Option to enable/disable mid-roll ads', '', '', 'Mid-roll Ad'); ?></td>
<td
<?php
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="radio_algin"';
    }
    ?>><input type="radio" style="float: none;" name="midrollads"
id="midrollads" <?php echo $midrollenable; ?> value="1" />Enable <input
type="radio" style="float: none;" name="midrollads" id="midrollads"
<?php echo $midrolldisable; ?> value="0" />Disable</td>
</tr>
<tr>
<td><?php echo JHTML::tooltip('Option to enable/disable IMA ads', '', '', 'IMA Ad'); ?></td>
<td <?php  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="radio_algin"';
    }
    ?>><input type="radio" style="float: none;" name="imaads"
id="imaads" <?php echo $imaenable; ?> value="1" />Enable <input
type="radio" style="float: none;" name="imaads" id="imaads"
<?php echo $imadisable; ?> value="0" />Disable</td>
</tr>
<tr id="download"> <td> <?php echo JHTML::tooltip ( 'Download Video (Not supported for vimeo, youtube and streamer)', '', '', 'Download Video' ); ?></td>
<td <?php  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="radio_algin"';
    } ?>><input type="radio" name="download" id="download"
<?php echo $downloadEnable; ?> value="1" />Enable <input type="radio" name="download" id="download"
<?php echo $downloadDisable; ?> value="0" />Disable</td>
</tr> <?php $baseUrl = JURI::base() . "components/com_contushdvideoshare/"; ?>
<tr> <td><?php  echo JHTML::tooltip ( 'Option to enable/disable video', '', '', 'Status' );
    ?></td>
<td <?php  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      echo 'class="radio_algin"';
    } ?>><select name="published" id="published">
<option value="1" <?php  if (isset ( $editVideo ['rs_editupload']->published ) && $editVideo ['rs_editupload']->published == 1) {
        echo 'selected';
      } ?>>Published</option> <option value="0" <?php if (isset ( $editVideo ['rs_editupload']->published ) && $editVideo ['rs_editupload']->published == 0) {
        echo 'selected';
      } ?>>Unpublished</option> <option value="-2" <?php
      if (isset ( $editVideo ['rs_editupload']->published ) && $editVideo ['rs_editupload']->published == - 2) {
        echo 'selected';
      } ?>>Trashed</option>
  </select></td> </tr> </table>
</fieldset>
<?php $userid = $user->get ( 'id' );
if (isset ( $editVideo ['rs_editupload']->memberid )) {
  $videosid = $editVideo ['rs_editupload']->memberid;
} else {
  $videosid = $userid;
}
if (isset ( $editVideo ['rs_editupload']->memberid )) {
  $videostype = $editVideo ['rs_editupload']->usergroupid;
} else {
  $videostype = $editVideo ['user_group_id']->group_id;
} ?>
<input type="hidden" name="id" id="id" value="<?php echo $editVideo['rs_editupload']->id; ?>" /> <input  type="hidden" name="task" /> <input type="hidden" name="newupload" id="newupload" value="1"> <input type="hidden" name="fileoption"
id="fileoption" value="<?php echo $editVideo['rs_editupload']->filepath; ?>" /> <input type="hidden" name="seotitle" id="seotitle" 
value="<?php echo $editVideo['rs_editupload']->seotitle; ?>" /> <input type="hidden" name="normalvideoform-value" id="normalvideoform-value" value="" /> <input type="hidden" name="hdvideoform-value"
id="hdvideoform-value" value="" /> <input type="hidden" name="subtile_lang1" id="subtile_lang1" value="" /> <input type="hidden" name="subtile_lang2" id="subtile_lang2" value="" /> <input type="hidden" name="thumbimageform-value" id="thumbimageform-value"
value="" /> <input type="hidden" name="previewimageform-value" id="previewimageform-value" value="" /> <input type="hidden" name="subtitle_video_srt1form-value" id="subtitle_video_srt1form-value" value="<?php echo $editVideo ['rs_editupload']->subtitle1; ?>" /> 
<input type="hidden" name="subtitle_video_srt2form-value" id="subtitle_video_srt2form-value" value="<?php echo $editVideo ['rs_editupload']->subtitle2; ?>" /> <input type="hidden" name="ffmpegform-value"
id="ffmpegform-value" value="<?php echo strrev($editVideo['rs_editupload']->videourl); ?>" /> <input type="hidden" name="videourl-value" id="videourl-value" value="" />
<input type="hidden" name="embedcode" id="embedcode" value="" /> <input type="hidden" name="thumburl-value" id="thumburl-value" value="" /> <input type="hidden" name="previewurl-value" id="previewurl-value" value="" />
<input type="hidden" name="hdurl-value" id="hdurl-value" value="" /> <input type="hidden" name="streameroption-value" id="streameroption-value" value="<?php echo $editVideo ['rs_editupload']->streameroption; ?>" /> <input type="hidden" name="streamerpath-value"
id="streamerpath-value" value="" /> <input type="hidden" name="islive-value" id="islive-value" value="" /> <input type="hidden" name="usergroupid" id="usergroupid" value="<?php echo $videostype; ?>" />
<input type="hidden" name="memberid" id="memberid" value="<?php echo $videosid; ?>" /> <input type="hidden" name="mode1"
id="mode1" value="<?php echo $editVideo['rs_editupload']->filepath; ?>" /> <input type="hidden" name="submitted" value="true" id="submitted">
</form>
<?php /** video info form end */ ?>
<script type="text/javascript">
var baseurl = "<?php echo JURI::base(); ?>";
var streameroption = "<?php if (isset($editVideo ['rs_editupload']->streameroption)) {
  echo $editVideo ['rs_editupload']->streameroption ; 
}?>" ;
var filepath = "<?php if(isset($editVideo ['rs_editupload']->filepath)) {
  echo $editVideo ['rs_editupload']->filepath; 
} ?>"; 
</script>
<script type="text/javascript" src="<?php echo JURI::base() . 'components/com_contushdvideoshare/js/adminvideos.js'; ?>"></script> 
<script type="text/javascript">
<?php if (! empty ( $editVideo ['rs_editupload']->subtitle1 )) { ?>
getsubtitle1name();
<?php } if (! empty ( $editVideo ['rs_editupload']->subtitle2 )) { ?> 
getsubtitle2name();
<?php } ?>
</script>