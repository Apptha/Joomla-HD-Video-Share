<?php
/**
 * Video Upload view file for front end users
 *
 * This file is to display add video page for front end users
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Variable initialization */
$editing = $videoedit = '';
/** Get base url */
$baseurl = JURI::base ();

/** Get type param from request using helper */
$type = JRequest::getString ( 'type' );
/** Get id param from request using helper */
$id = JRequest::getInt ( 'id' );

/** Check type is edit */
if ($type == 'edit') {
  /** Get edit video detials for site */
  $videoedit1 = $this->videodetails;  
  /** Check video edit enabled */
  if (isset ( $videoedit1 [0] )) {
  	/** Asigning variable for video edit */
    $videoedit = $videoedit1 [0];
  }  
  /** Check video file path */
  if (isset ( $videoedit->filepath )) {
  	/** Asigning variable file path for editing */
    $editing = $videoedit->filepath;
  }
}

/** Get site settings */
$dispenable = $this->dispenable;

/** Add js file for video upload page */
$document = JFactory::getDocument ();
$document->addScript ( JURI::base () . 'components/com_contushdvideoshare/js/upload_script-min.js' );
$document->addScript ( JURI::base () . 'components/com_contushdvideoshare/js/script-min.js' );
$document->addScript ( JURI::base () . 'components/com_contushdvideoshare/js/membervalidator-min.js' );

/** Get URL from request */
if (JRequest::getString ( 'url' )) {
  /** Create object for videourl class */
  $video = new videourl ();
  $vurl = JRequest::getString ( 'url' );
  /** Get video type and image */
  $video->getVideoType ( $vurl );
  /** Get description from model based on video id */
  $description = $video->catchData ( $vurl );
  /** Get video thumb image from model */
  $imgurl = $video->imgURL ( $vurl );
}

/** Call function to display myvideos, myplaylists link for videoupload page*/
playlistMenu( '' , '' );
?>
<div class="player clearfix" id="clsdetail">
	<input type="hidden" name="editmode" id="editmode"
		value="<?php echo $editing; ?>" />
<?php /** Display video upload page title */ ?>
	<h1 class="uploadtitle"> <?php if (JRequest::getString ( 'type' ) != 'edit') {
    echo JText::_ ( 'HDVS_VIDEO_UPLOAD' );
  } else {
    echo JText::_ ( 'HDVS_EDIT_VIDEO' );
  } ?> </h1>
	<div class="addvideo_top_select">
		<div class="floatleft allform_left">
			<label><?php echo JText::_('HDVS_VIDEO_TYPE'); ?>:</label>
			<ul id="upload_thread">
<?php /** Get upload method values */   
    $separate_values = explode ( ',', $dispenable ['upload_methods'] );
    for($i = 0; $i < count ( $separate_values ); $i ++) {
      $upload_methods [$separate_values [$i]] = $separate_values [$i];
    }
    
    /** Display upload method options */
    /** Check upload method - URL */
    if (isset ( $upload_methods ['URL'] )) { ?>
					<li><input type="radio" name="filetype" class="butnmargin"
					id="filetype3" value="2" onclick="filetypeshow(this);setUploadMethod('filetype3', 2);" /> <span
					class="select_videotype"> <?php echo JText::_('HDVS_URL'); ?> </span>
				</li>
				<?php }
	/** Check upload method - Youtube */
    if (isset ( $upload_methods [YOUTUBE] )) { ?>
					<li><input type="radio" class="butnmargin" name="filetype"
					value="0" id="filetype2" onclick="filetypeshow(this);setUploadMethod('filetype2', 0);" /><span
					class="select_videotype"> 
								   <?php echo JText::_('HDVS_YOUTUBE'); ?> / 
									   <?php echo JText::_('HDVS_VIMEO'); ?> / 
										   <?php echo JText::_('HDVS_DAILYMOTION'); ?> / 
											   <?php echo JText::_('HDVS_VIDDLER'); ?></span></li>
				<?php  }   
	/** Check upload method - Uploaded video */
    if (isset ( $upload_methods ['Upload'] )) { ?>
					<li><input type="radio" class="butnmargin" name="filetype"
					id="filetype1" value="1" onclick="filetypeshow(this);" /> <span
					class="select_upload">
								   <?php echo JText::_('HDVS_UPLOAD'); ?></span></li>
				<?php }
	/** Check upload method - RMTP */
    if (isset ( $upload_methods ['RTMP'] )) { ?>
					<li><input type="radio" class="butnmargin" value="3" name="filetype"
					id="filetype4" onclick="filetypeshow(this);setUploadMethod('filetype4', 3);" /> <span class="select_upload"><?php echo JText::_('HDVS_RTMP'); ?></span></li>
				<?php }
	/** Check upload method - Embed Code */
    if (isset ( $upload_methods [EMBED] ) ) { ?>
					<li><input type="radio" class="butnmargin" name="filetype" onclick="filetypeshow(this);"
					id="filetype5" value="4" /> <span class="select_upload">
					<?php echo JText::_('HDVS_EMBED_METHOD'); ?></span>
				</li>
				<?php  }
	/** Check upload method- FFMPEG */
    if (isset ( $upload_methods ['FFMPEG'] )) { ?>
					<li><input type="radio" class="butnmargin" name="filetype"
					id="filetype6" value="5" onclick="filetypeshow(this);" /> <span
					class="select_upload"><?php echo JText::_('HDVS_FFMPEG'); ?></span>
				</li>
				<?php  } ?>
			</ul>
		</div>
		<?php /** Display back to my videos link */ ?>
		<span class="floatright"> <input type="button"
			value="<?php echo JText::_('HDVS_BACK_TO_MY_VIDEOS'); ?>"
			class="button cursor_pointer"
			onclick="window.open('index.php?option=com_contushdvideoshare&view=myvideos', '_self');" />
		</span>
	</div>
	<div id="typeff" name="typeff">
		<div class="allform">
			<table class="table_upload" cellpadding="0" cellspacing="0"
				width="100%">
				<tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1">
				<?php /** Display video upload form */ ?>
					<td class="form-label">
					<?php echo JText::_('HDVS_UPLOAD_VIDEO'); ?><span class="star">*</span>
					</td>
					<td>
						<div id="f11-upload-form">
							<form name="ffmpeg" method="post" enctype="multipart/form-data">
								<input type="file" name="myfile" id="myfile"
									onchange="enableUpload(this.form.name);" /> <input
									type="button" name="uploadBtn"
									value="<?php echo JText::_('HDVS_UPLOAD_VIDEO'); ?>"
									disabled="disabled" class="button cursor_pointer upload_video"
									onclick="return addQueue(this.form.name, this.form.myfile.value);" />
								<label id="lbl_normal">
							<?php 
		/** Check file is FFMPEG */
			 if (isset ( $videoedit->filepath ) && $videoedit->filepath == 'FFmpeg') {
            $vidURL = $videoedit->videourl;
        /** Check Video url length */
            if (strlen ( $videoedit->videourl ) > 50) {
		/** Limit url length*/
              $vidURL = JHTML::_ ( 'string.truncate', ($videoedit->videourl), 50 );
            } 
            echo $vidURL;            
        } ?></label> <input type="hidden" name="mode" value="video" /> </form> </div>
						<div id="f11-upload-progress">
							<div class="floatleft">
								<img id="f11-upload-image" src="components/com_contushdvideoshare/images/empty.gif"
									alt="Uploading" class="clsempty" /> <label class="postroll"
									id="f11-upload-filename">PostRoll.flv</label> </div>
							<div class="floatright"> <span id="f11-upload-cancel"> <a class="clscnl"
									href="javascript:cancelUpload('normalvideoform');" name="submitcancel">
										<?php echo JText::_('HDVS_CANCEL'); ?></a>
								</span> <label id="f11-upload-status" class="clsupl"><?php echo JText::_('HDVS_UPLOADING'); ?></label>
								<span id="f11-upload-message" class="clsupl_fail"> <b><?php echo JText::_('HDVS_UPLOAD_FAILED'); ?>:</b> 
										<?php echo JText::_('HDVS_USER_CANCELLED_THE_UPLOAD'); ?>
								</span>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div name="typefile" id="typefile">
		<div class="allform">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1">
					<td class="form-label">
					<?php echo JText::_('HDVS_UPLOAD_VIDEO'); ?><span class="star">*</span>
					</td>
					<td>
					<?php /** Display video upload option in site */ ?>
						<div id="f1-upload-form">
							<form name="normalvideoform" method="post"
								enctype="multipart/form-data">
								<input type="file" name="myfile" id="myfile"
									onchange="enableUpload(this.form.name);" /> <input
									class="button cursor_pointer upload_video" type="button"
									name="uploadBtn" value="<?php echo JText::_('HDVS_UPLOAD'); ?>"
									disabled="disabled"
									onclick="return addQueue(this.form.name, this.form.myfile.value);" />
								<label id="lbl_normal">
									<?php
			/** Check file is File */
		  if (isset ( $videoedit->filepath ) && $videoedit->filepath == 'File') {
            $videoURL = $videoedit->videourl;
            /** Limit url length display in frontend  */
            if (strlen ( $videoedit->videourl ) > 50) {
              $videoURL = JHTML::_ ( 'string.truncate', ($videoedit->videourl), 50 );
            } 
            echo $videoURL;            
        } ?></label> <input type="hidden" name="mode" value="video" />
							</form>
						</div> <div id="f1-upload-progress">
							<div class="floatleft"> <img id="f1-upload-image" alt="Uploading" src="components/com_contushdvideoshare/images/empty.gif"
									 class="clsempty" /> <label class="postroll" id="f1-upload-filename">PostRoll.flv</label> </div>
							<div class="floatright"> <span id="f1-upload-cancel"> <a class="clscnl" name="submitcancel" 
									href="javascript:cancelUpload('normalvideoform');" >
										<?php echo JText::_('HDVS_CANCEL'); ?></a> </span> <label id="f1-upload-status" class="clsupl">
										<?php echo JText::_('HDVS_UPLOADING'); ?></label>
								<span id="f1-upload-message" class="clsupl_fail"> <b><?php echo JText::_('HDVS_UPLOAD_FAILED'); ?>:</b> 
										<?php echo JText::_('HDVS_USER_CANCELLED_THE_UPLOAD'); ?> </span>
							</div> </div>
					</td> </tr>
				<tr id="ffmpeg_disable_new2" name="ffmpeg_disable_new1">
					<td class="form-label">
					<?php echo JText::_('HDVS_UPLOAD_HD_VIDEO'); ?></td>
					<?php /** Display hd video upload option in site */ ?>
					<td>
						<div id="f2-upload-form">
							<form name="hdvideoform"  enctype="multipart/form-data" method="post">
								<input type="file" name="myfile" onchange="enableUpload(this.form.name);" /> <input
									class="button upload_video cursor_pointer" type="button" name="uploadBtn" value="<?php echo JText::_('HDVS_UPLOAD'); ?>"
									disabled="disabled" onclick="return addQueue(this.form.name, this.form.myfile.value);" />
								<label id="lbl_normal"> <?php  if (isset ( $videoedit->filepath ) && $videoedit->filepath == 'File') {
            $hdurl = $videoedit->hdurl;            
            if (strlen ( $videoedit->hdurl ) > 50) {
              $hdurl = JHTML::_ ( 'string.truncate', ($videoedit->hdurl), 50 );
            } 
            echo $hdurl;
        }
        ?></label> <input type="hidden" name="mode" value="video" /> </form>
						</div>
						<div id="f2-upload-progress">
							<div class="floatleft">
								<img id="f2-upload-image" src="components/com_contushdvideoshare/images/empty.gif" class="clsempty"
									alt="Uploading" /> <label class="postroll"
									id="f2-upload-filename">PostRoll.flv</label>
							</div>
							<div class="floatright"> <span id="f2-upload-cancel"> <a class="clscnl"
									href="javascript:cancelUpload('hdvideoform');" name="submitcancel"><?php echo JText::_('HDVS_CANCEL'); ?></a>
								</span> <label id="f2-upload-status" class="clsupl"><?php echo JText::_('HDVS_UPLOADING'); ?></label>
								<span id="f2-upload-message" class="clsupl_fail"> <b><?php echo JText::_('HDVS_UPLOAD_FAILED'); ?>:</b> 
										<?php echo JText::_('HDVS_USER_CANCELLED_THE_UPLOAD'); ?>
								</span> </div>
						</div> </td> </tr>
				<tr id="ffmpeg_disable_new3" name="ffmpeg_disable_new1">
					<td class="form-label">
					<?php echo JText::_('HDVS_UPLOAD_THUMB_IMAGE'); ?><span
						class="star">*</span>
					</td>
					<td>
					<?php /** Display thumb upload option in site */ ?>
						<div id="f3-upload-form">
							<form enctype="multipart/form-data" name="thumbimageform" method="post" >
								<input name="myfile" type="file" onchange="enableUpload(this.form.name);" /> <input
									class="button upload_video cursor_pointer" type="button"
									name="uploadBtn" value="<?php echo JText::_('HDVS_UPLOAD'); ?>"
									disabled="disabled" onclick="return addQueue(this.form.name, this.form.myfile.value);" />
								<label id="lbl_normal">
									<?php if (isset ( $videoedit->filepath ) && $videoedit->filepath == 'File') {
            $thumburl =  $videoedit->thumburl;  
            /** Limit url length display  */
            if (strlen ( $videoedit->thumburl ) > 50) {
              $thumburl = JHTML::_ ( 'string.truncate', ($videoedit->thumburl), 50 );
            } 
             echo $thumburl;
        }
        ?></label> <input type="hidden" name="mode" value="image" />
							</form> </div> <div id="f3-upload-progress">
							<div class="floatleft">
								<img src="components/com_contushdvideoshare/images/empty.gif" id="f3-upload-image" alt="Uploading" class="clsempty" /> <label class="postroll"
									id="f3-upload-filename">PostRoll.flv</label>
							</div> <div class="floatright"> <span id="f3-upload-cancel"> 
							<a class="clscnl" href="javascript:cancelUpload('thumbimageform');"
									name="submitcancel"><?php echo JText::_('HDVS_CANCEL'); ?></a>
								</span> <label class="clsupl" id="f3-upload-status"><?php echo JText::_('HDVS_UPLOADING'); ?></label>
								<span class="clsupl_fail" id="f3-upload-message" > <b><?php echo JText::_('HDVS_UPLOAD_FAILED'); ?>:</b> 
										<?php echo JText::_('HDVS_USER_CANCELLED_THE_UPLOAD'); ?> </span>
							</div> </div> </td> </tr>
				<tr id="ffmpeg_disable_new4" name="ffmpeg_disable_new1">
					<td class="form-label"><?php echo JText::_('HDVS_UPLOAD_PREVIEW_IMAGE'); ?></td>
					<td>
					<?php /** Display preview upload option in site */ ?>
						<div id="f4-upload-form">
							<form name="previewimageform" method="post"
								enctype="multipart/form-data">
								<input type="file" name="myfile"
									onchange="enableUpload(this.form.name);" /> <input
									class="button upload_video cursor_pointer" type="button"
									name="uploadBtn" value="<?php echo JText::_('HDVS_UPLOAD'); ?>"
									disabled="disabled"
									onclick="return addQueue(this.form.name, this.form.myfile.value);" />
								<label id="lbl_normal">
									<?php  if (isset ( $videoedit->filepath ) && $videoedit->filepath == 'File') {
            $prevwURL = $videoedit->previewurl;            
            if (strlen ( $videoedit->previewurl ) > 50) {
/** Truncate url */
              $prevwURL = JHTML::_ ( 'string.truncate', ($videoedit->previewurl), 50 );
            } 
            /** Display preview url */
            echo $prevwURL;
        } ?></label> <input type="hidden" name="mode" value="image" />
							</form> </div>
						<div id="f4-upload-progress"> <div class="floatleft">
								<img id="f4-upload-image" src="components/com_contushdvideoshare/images/empty.gif" alt="Uploading" class="clsempty" /> <label class="postroll"
									id="f4-upload-filename">PostRoll.flv</label> </div>
							<div class="floatright">
								<span id="f4-upload-cancel"> <a class="clscnl" href="javascript:cancelUpload('previewimageform');"
									name="submitcancel"><?php echo JText::_('HDVS_CANCEL'); ?></a>
								</span> <label id="f4-upload-status" class="clsupl"> <?php echo JText::_('HDVS_UPLOADING'); ?></label> <span
									id="f4-upload-message" class="clsupl_fail"> <b><?php echo JText::_('HDVS_UPLOAD_FAILED'); ?>:</b> 
										<?php echo JText::_('HDVS_USER_CANCELLED_THE_UPLOAD'); ?> </span>
							</div> </div>
						<div id="nor"> <iframe id="uploadvideo_target" name="uploadvideo_target" src=""></iframe> </div>
					</td> </tr> </table>
		</div>
	</div>
<?php  /** Check type is not eidt then call videoupload script function */
if (JRequest::getString ( 'type' ) == 'edit') {
  $javascript = '';
} else {
  $javascript = 'onsubmit="return videoupload();"';
}
?>
	<form name="upload1111"
		action="<?php echo JRoute::_('index.php?option=com_contushdvideoshare&view=videoupload'); ?>"
		method="post" enctype="multipart/form-data" <?php echo $javascript?>
		id="hupload">
		<?php /** Display streamer path in site */ ?>
		<div id="rtmpcontainer" class="allform">
			<ul id="stream1" name="stream1">
				<li class="videotype_url_list"><label>Streamer Path<span
						class="star">*</span></label> <input class="text" type="text"
					name="streamname" id="streamname" style="width: 300px"
					maxlength="250"
					value="
<?php
/** Check Video streamer path is available
 * Display path if exists
 */
if (isset ( $videoedit->streamerpath )) {
  echo $videoedit->streamerpath;
}
?>" /></li>
			</ul>
			<?php /** Display is live option in site */ ?>
			<ul id="islive_visible" name="islive_visible">
			<?php /** Display video path in site
					*
					* Display Upload path
					*/ ?>
				<li><label>Is Live<span class="star">*</span></label> <input
					type="radio" style="float: none;" name="islive[]" id="islive2"
					<?php  if (isset ( $videoedit->islive ) && ($videoedit->islive == '1')) {
      echo 'checked="checked" ';
    }  ?>  value="1" />Yes <input type="radio" style="float: none;"
					name="islive[]" id="islive1"
					<?php if (isset ( $videoedit->islive ) && ($videoedit->islive == '0' || $videoedit->islive == '')) {
        echo 'checked="checked" ';
    }  ?> value="0" />No</li>
			</ul>
		</div>
		<div id="typeurl" class="allform">
			<div class="uplcolor" align="center"><?php  if (($this->upload)) {
    echo $this->upload . '<br/><br/>';
  }
  ?>
			</div>
			<?php /** Display embed video option in site */ ?>
			<ul id="videotype_url">
				<li class="videotype_url_list" id="ffmpeg_disable_new9"
					style="display: none"><label><?php echo JText::_('HDVS_EMBED_METHOD'); ?><span
						class="star">*</span></label> <textarea onchange="bindvideo();"
						id="embed_code" name="embed_code" rows="5" cols="60"
						style="width: 300px"><?php if (isset ( $videoedit->embedcode )) {
        echo stripslashes ( $videoedit->embedcode );
      }  ?></textarea></li>
      <?php /** Display video url option in site */ ?>
				<li id="nonhd_url" class="videotype_url_list"><label><?php echo JText::_('HDVS_UPLOAD_URL'); ?><span
						class="star">*</span></label> <input type="text" name="Youtubeurl"
					value="<?php
    if (isset ( $videoedit->filepath ) && ($videoedit->filepath == YOUTUBE || $videoedit->filepath == 'Url')) {
      echo $videoedit->videourl;
    } ?>" class="text" size="20" id="Youtubeurl" onchange="bindvideo();"
    onpaste="e = this; setTimeout(function(){generate12(e.value);});" 
					onkeyup="generate12(this.value);" /> <input id="generate"
					class="button" type="button" name="youtube_media"
					class="button-primary" value="Generate details"
					onclick="generateyoutubedetail();" /></li>
				<li class="videotype_url_list">
					<div id="hd_url">
						<label><?php echo JText::_('HDVS_UPLOAD_HDURL'); ?></label> <input
							type="text" name="hdurl"
							value="
<?php if (isset ( $videoedit->filepath ) && ($videoedit->filepath == YOUTUBE || $videoedit->filepath == 'Url')) {
  echo $videoedit->hdurl;
}
?> " class="text" size="20" id="hdurl" onchange="bindvideo();" />
					</div>
				</li>
				<li class="videotype_url_list">
					<div id="image_path">
					<?php /** Display image url option in site */ ?>
						<label><?php echo JText::_('HDVS_UPLOAD_IMAGEURL'); ?></label>
						 <input class="img_ulrpath" type="radio" name="imagepath"
							id="imageurlpath" value="1"
							<?php if (isset ( $videoedit->thumburl ) && !strstr($videoedit->thumburl, COMPONENT)) {
        echo "checked='checked'";
      } ?> onclick="changeimageurltype(this);"> <span id="imageurllabel"
							class="select_viedoupload_type"> <?php echo JText::_('HDVS_UPLOAD_IMAGEURL'); ?></span>
						<input type="radio" name="imagepath" id="imageuploadpath"
							value="0"
							<?php if (isset ( $videoedit->thumburl ) && strstr($videoedit->thumburl, COMPONENT)) {
        echo "checked='checked'";
      }  ?> onclick="changeimageurltype(this);"> <span id="imageuploadlabel"> 
      <?php echo JText::_('HDVS_IMAGE_UPLOAD'); ?></span>
						<div id="imageurltype"></div>
<?php if (isset ( $videoedit->thumburl ) && !strstr($videoedit->thumburl, COMPONENT)) {  ?>
<script type="text/javascript">
document.getElementById('imageurltype').innerHTML = '<input type="text" name="thumburl" value="<?php if (isset ( $videoedit->thumburl ) && $videoedit->filepath != YOUTUBE) { 
  echo $videoedit->thumburl; 
}?>" class="text" size="20" id="thumburl"/>';
	</script>
	<?php } else { ?>
	<script type="text/javascript"> 
		document.getElementById('imageurltype').innerHTML = '<input type="file" name="thumburl" id="thumburl" onchange="fileformate_check(this);" value="<?php  if (isset ( $videoedit->thumburl )) { 
		  echo $videoedit->thumburl;
		} ?>"/><label><?php if (isset ( $videoedit->thumburl ) && strstr($videoedit->thumburl, "com_contushdvideoshare")) {
		  echo substr(strrchr($videoedit->thumburl, "/"),1);
		}?></label>';
	</script>
<?php
}
?> </div>
				</li>
			</ul>
			<style type="text/css">
.text_tool_cont { position: relative; }
.text_tool_cont:hover .text_tool { display: block; left: 285px; bottom: 0; background: #000; padding: 3px; color: #fff; }
.text_tool { display: none; position: absolute; }
</style>
		</div>
		<div class="allform">
			<ul id="videoupload_pageform">
<?php /** Display video title option in site */ ?>
				<li class="videoupload_list"><label><?php echo JText::_('HDVS_TITLE'); ?><span
						class="star">*</span></label> <input type="text" name="title"
					value="<?php
					/** Show video title   */
					  if (isset ( $videoedit->title )) {
      echo $videoedit->title;
    } ?>"
					class="text" size="20" id="videotitle" /></li>
<?php /** Display video description option in site */ ?>
				<li class="videoupload_list"><label><?php echo JText::_('HDVS_DESCRIPTION'); ?></label>
					<textarea name="description" id="description" style="height: 80px;"><?php
					/** Show video description   */
    if (isset ( $videoedit->description )) {
      echo $videoedit->description;
    } ?></textarea>
    <?php /** Display video tag option in site */ ?>
					<div id="videouploadLoading" style="display: none;"></div></li>
				<li class="videoupload_list"><label><?php echo JText::_('HDVS_TAGS'); ?></label>
					<div class="text_tool_cont">
						<textarea name="tags1" class="info" id="tags1"><?php
						/** Show video tags   */
      if (isset ( $videoedit->tags )) {
        echo $videoedit->tags;
      } ?></textarea>
						<span><?php echo JText::_('HDVS_TAG_SEPARATE'); ?></span>
						<p class="text_tool"><?php echo JText::_('HDVS_TAG_TOOLTIP'); ?></p>
					</div></li>
<?php /** Display video category option in site */ ?>
				<li class="videoupload_list"><label><?php echo JText::_('HDVS_SELECT_CATEGORY'); ?></label>
					<div class="catclass floatleft" align="left" id="selcat"> <?php $n = count ( $this->videocategory );    
    foreach ( $this->videocategory as $cat ) {
		/** Display video categories   */
      ?> <a class="cursor_pointer" title="<?php echo $cat->category; ?>"
							onclick="catselect('<?php
      echo $cat->category;
      ?>');">
      <?php echo $cat->category . ","; ?>
							</a>
								   <?php } ?></div></li>
				<li class="videoupload_list"><label><?php echo JText::_('HDVS_CATEGORY'); ?><span
						class="star">*</span></label> <input type="text" readonly
					name="tagname"
					value="<?php
					/** Check category name available and displays if exist */
					  if (isset ( $videoedit->category )) {
      echo $videoedit->category;
    } ?>"
					class="text" size="20" id="tagname" /> <input type="button"
					value="<?php echo JText::_('HDVS_RESET_CATEGORY'); ?>"
					class="button" onclick="resetcategory();"></li>
					<?php /** Display video type option in site */ ?>
				<li class="videoupload_list"><label><?php echo JText::_('HDVS_TYPE'); ?></label>
					<input type="radio" class="butnmargin addvideo_radio_option"
					name="type" value=0
					<?php if (isset ( $videoedit->type ) && $videoedit->type == '0') {
      echo 'checked="checked"';
    } ?>
    
					checked="checked" /> <span class="hd_select_public"><?php echo JText::_('HDVS_PUBLIC'); ?></span>
					<input type="radio" class="butnmargin addvideo_radio_option"
					name="type" value=1 <?php if (isset ( $videoedit->type ) && $videoedit->type == '1') {
      echo 'checked="checked"';
    }
    ?> /> <span class="hd_select_private"><?php echo JText::_('HDVS_PRIVATE'); ?></span>
				</li>
				<li class="videoupload_list">
					<div id="down_load">
					<?php /** Display video download option in site */ ?>
						<label><?php echo JText::_('HDVS_DOWNLOAD'); ?></label> <input
							type="radio" class="butnmargin addvideo_radio_option"
							name="download" value=1 
							<?php if (isset ( $videoedit->download ) && $videoedit->download == '1') { 
        echo 'checked="checked"';
      } 
      ?>  checked="checked"/> <span class="hd_select_enable"><?php echo JText::_('HDVS_ENABLE'); ?></span>
						<input type="radio" class="butnmargin addvideo_radio_option"
							name="download" value=0 
							<?php  if (isset ( $videoedit->download ) && ($videoedit->download == '0' || $videoedit->download == '')) {
        echo 'checked="checked"';
      } 
      ?> /> <span class="hd_select_disable"><?php echo JText::_('HDVS_DISABLE'); ?></span>
					</div>
				</li>
			</ul>
<?php /** Check type is edit and display update button in site */ 
  if ($type == 'edit') {
    $editbutton = JText::_ ( 'HDVS_UPDATE' );
  } else {
    /** Else display save button in site */ 
    $editbutton = JText::_ ( 'HDVS_SAVE' );
  }
  ?>
			<div>
				<input type="submit" name="uploadbtn" value="<?php echo $editbutton; ?>" class="button cursor_pointer" />
				<?php /** Display cancel button in site */ ?>
				<input type="button" 
					onclick="window.open('<?php echo JRoute::_ ( 'index.php?option=com_contushdvideoshare&view=myvideos' ); ?>', '_self');"
					value="<?php echo JText::_('HDVS_CANCEL'); ?>"
					class="button cursor_pointer" />
			</div>
		</div>
		<br /> <br /> <input type="hidden" id="videouploadformurl"
			name="videouploadformurl" value="<?php echo JURI::base(); ?>" /> <input
			type="hidden" name="videourl" value="1" class="text" size="20"
			id="videourl" /> <input type="hidden" name="normalvideoforms3status"
			value="" id="normalvideoforms3status" /> <input type="hidden"
			name="hdvideoforms3status" value="" id="hdvideoforms3status" /> <input
			type="hidden" name="thumbimageforms3status" value=""
			id="thumbimageforms3status" /> <input type="hidden"
			name="previewimageforms3status" value=""
			id="previewimageforms3status" /> <input type="hidden" name="thump"
			value="<?php  if (isset ( $imgurl )) {
    echo $imgurl;
  }
  ?>"> 
  <?php /** Set hidden value for url method upload */ ?> 
  <input type="hidden" name="flv"
			value="<?php if (JRequest::getString ( 'url' )) {
    echo JRequest::getString ( 'url' );
  }
  ?>"> 
  <?php /** Set hidden value for FFMPEG method upload */ ?>
  <input type="hidden" name="hd" value=""> <input type="hidden"
			name="hq" value=""> <input type="hidden" name="ffmpeg" id="ffmpeg"
			value="<?php  if (isset ( $videoedit->filepath ) &&  ($videoedit->filepath == 'FFmpeg')){
      echo strrev ( JPATH_COMPONENT . '/videos/' . $videoedit->videourl );    
  }
  ?>"> 
  <?php /** Set hidden value for video url */ ?>
  <input type="hidden" name="normalvideoformval"
			id="normalvideoformval"
			value="<?php if (!empty ( $videoedit )) { 
			  if (isset ( $videoedit->filepath ) &&  ($videoedit->filepath == 'File') && ( $videoedit->amazons3 == 0)){
      echo strrev ( JPATH_COMPONENT . '/videos/' . $videoedit->videourl );
  } else {
echo strrev ( $videoedit->videourl );
} 
			}else {
echo '';
}
  ?>">
  <?php /** Set hidden value for file path */ ?>
   <input type="hidden" name="video_filetype" id="video_filetype"
			value="<?php if (isset ( $videoedit->filepath ) &&  ($videoedit->filepath == 'File')){
      echo $videoedit->filepath;    
  }
  ?>">
  <?php /** Set hidden value for thumb url */ ?>
   <input type="hidden" name="hdvideoformval" id="hdvideoformval"
			value="<?php  if (!empty ( $videoedit )) { 
			  if (isset ( $videoedit->filepath ) &&  ($videoedit->filepath == 'File') && ( $videoedit->amazons3 == 0)) {
      echo strrev ( JPATH_COMPONENT . '/videos/' . $videoedit->hdurl );    
  } else {
echo strrev ( $videoedit->hdurl );
}
			}else {
echo '';
}
  ?>"> 
			
			<input type="hidden" name="thumbimageformval"
			id="thumbimageformval"
			value="<?php  if (!empty ( $videoedit )) { 
			  if (isset ( $videoedit->filepath ) &&  ($videoedit->filepath == 'File') && ( $videoedit->amazons3 == 0)) {
      echo strrev ( JPATH_COMPONENT . '/videos/' . $videoedit->thumburl );    
  } else {
echo strrev ( $videoedit->thumburl );
}
			}else {
echo '';
}
  ?>">
  <?php /** Set hidden value for thumb url */ ?>
   <input type="hidden" name="streamerpath-value"
			id="streamerpath-value" value=""> <input type="hidden"
			name="islive-value" id="islive-value" value=""> <input type="hidden"
			name="previewimageformval" id="previewimageformval"
			value="<?php  if (!empty ( $videoedit )) { 
			  if (isset ( $videoedit->filepath ) &&  ($videoedit->filepath == 'File') && ( $videoedit->amazons3 == 0)) {
      echo strrev ( JPATH_COMPONENT . '/videos/' . $videoedit->previewurl );    
  } else {
echo strrev ( $videoedit->previewurl );
}
			}else {
echo '';
}
  ?>">
  <?php /** Set hidden value for seotitle */ ?>
   <input type="hidden" name="seltype" id="seltype" value="0"> <input
			type="hidden" name="seotitle" id="seotitle"
			value="<?php  if (isset ( $videoedit->seotitle )) {
    echo $videoedit->seotitle;
  }
  ?>">
  <?php /** Set hidden value for ordering */ ?>
    <input type="hidden" name="ordering" id="ordering"
			value="<?php if (isset ( $videoedit->ordering )) {
    echo $videoedit->ordering;
  }
  ?>">
  <?php /** Set hidden value for videoid */ ?>
     <input type="hidden" name="videotype" id="videotype"
			value="<?php echo $type; ?>"> <input type="hidden" name="videoid"
			id="videoid" value="<?php echo $id; ?>">
	</form>
</div>

<script type="text/javascript">
var frontbase = '<?php echo JURI::base (); ?>';
document.getElementById('generate').style.visibility = "hidden";
<?php if (isset ( $upload_methods [YOUTUBE] )) { ?>		
		setUploadMethod('filetype2', 0);
<?php  } elseif (isset ( $upload_methods ['Upload'] )) { ?>
		setUploadMethod('filetype1', 1);
<?php  } elseif (isset ( $upload_methods ['URL'] )) { ?>
		setUploadMethod('filetype3', 2);
<?php  } elseif (isset ( $upload_methods ['RTMP'] )) { ?>
		setUploadMethod('filetype4', 3);		
<?php  } elseif (isset ( $upload_methods [EMBED] )) { ?>
		setUploadMethod('filetype5', 4);
<?php  } elseif (isset ( $upload_methods ['FFMPEG'] )) { ?>
		setUploadMethod('filetype6', 5);
<?php } ?>
	document.getElementById("typeff").style.display = "none";
function setUploadMethod(filetype, value) {
document.getElementById(filetype).checked = true;
if(filetype == 'filetype4')
	document.getElementById ("islive1").checked = true;
if(filetype == 'filetype4' || filetype == 'filetype3')
	document.getElementById ("imageuploadpath").checked = true;
	filetypeshow(value);   
}
	function changeimageurltype(urltype) {
		if (urltype.value == 1 || urltype == 1) {
			document.getElementById('imageurltype').innerHTML = '<input type="text" name="thumburl"  class="text" size="20" id="thumburl" value="<?php
  if (isset ( $videoedit->thumburl ) && $videoedit->filepath != YOUTUBE) {
    echo $videoedit->thumburl;
  }
  ?>" />';
		} else {
			document.getElementById('imageurltype').innerHTML = '<input type="file" name="thumburl" class="text" size="20" id="thumburl" onchange="fileformate_check(this);" value="<?php
  if (isset ( $videoedit->thumburl )) {
    echo $videoedit->thumburl;
  } ?>" /><label><?php if (isset ( $videoedit->thumburl )) { 
echo substr(strrchr($videoedit->thumburl, "/"),1);
} ?></label>';
		}
	}
<?php if (isset ( $videoedit->streameroption ) && $videoedit->streameroption == 'rtmp') { ?>
		filetypeshow("3");
		document.getElementById("filetype4").checked = true;
<?php } else { 
    /** Javascript condition starts */ ?> 
    var filepath = "<?php if (isset($videoedit->filepath)) { 
      echo $videoedit->filepath; 
    } ?>"; 
    switch(filepath) {
       case 'File':
        filetypeshow("1");
        document.getElementById("filetype1").checked = true;
        break;
       case 'Url':
        filetypeshow("2");
        document.getElementById("filetype3").checked = true;
        break;
       case 'Youtube':
        filetypeshow("0");
        document.getElementById("filetype2").checked = true;
        break;
       case 'Embed':
        filetypeshow("4");
        document.getElementById("filetype5").checked = true;
        break;
       case 'FFmpeg':
        filetypeshow("5");
        document.getElementById("filetype6").checked = true;
        break;
       default:
        break;
    }
<?php }
if (isset ( $videoedit->filepath ) && ($videoedit->filepath == YOUTUBE || $videoedit->filepath == 'Url' || $videoedit->filepath == EMBED)) { ?>
		bindvideo();
<?php }
if (isset ( $videoedit->thumburl ) && !strstr($videoedit->thumburl, COMPONENT)) { ?>
	changeimageurltype(1);
<?php }
 ?>
</script>