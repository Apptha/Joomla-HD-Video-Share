<?php
/**
 * FFMPEG helper file
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

/** Import Joomla filesystem library */
jimport ( 'joomla.filesystem.file' );

/**
 * Admin UploadFfmpegHelper class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class UploadFfmpegHelper {
 /**
  * Function to upload FFMPEG type videos
  *
  * @param array $arrFormData
  *         post content
  * @param int $idval
  *         video id
  *         
  * @return uploadFfmpeg
  */
 public static function uploadFfmpeg($arrFormData, $idval) {
  $db = JFactory::getDBO ();
  $query = $db->getQuery ( true );
  /** Query for get HEIGTH,WIDTH player size and FFMPEG path from player settings */  
  $player_values = getPlayerIconSettings('');
  $dispenable = getSiteSettings();  
  require_once FVPATH . DS . 'helpers' . DS . 'uploadfile.php';
  
  /** Check valid record */
  if (isset($player_values [ FFMPEGPATH ]) && !empty($player_values [ FFMPEGPATH ])) {
   /** To get ffmpeg path */
    $strFfmpegPath = $player_values [ FFMPEGPATH ];
  }
  
  $fileoption   = $arrFormData [ FILEOPTION ];
  $ffmpeg_video = $arrFormData ['ffmpegform-value'];
  $videoName   = explode ( UPLOADS. '/', $ffmpeg_video );  
  /** Set file names to update */
  $video_name = uploadFfmpegHelper::setFileFFMPEG (VIDEOURL, $idval);
  $hd_name = uploadFfmpegHelper::setFileFFMPEG (HDURL, $idval);
  $thumb_name = uploadFfmpegHelper::setFileFFMPEG (THUMBURL, $idval);
  $prvw_name = uploadFfmpegHelper::setFileFFMPEG (PREVIEWURL, $idval);
  
  if (! empty ( $videoName [1] )) {
   $strTmpVidName = $videoName [1];   
   /** FVPATH - temporary path /components/com_contushdvideoshare/images/uploads */
   $strTmpPath = FVPATH . DS . "images" . DS . UPLOADS . DS . $strTmpVidName;   
   /** VPATH - target path /components/com_contushdvideoshare/videos */
   $strTargetPath     = VPATH . DS;
   $strVidTargetPath  = $strTargetPath . $video_name;
   
   /** Function to move video from temp path to target path */
   if (JFile::exists ( $strTmpPath )) {
    rename ( $strTmpPath, $strVidTargetPath );
   }   
   $strTmpPath = $strTargetPath . $video_name;   
   
   $s3bucket_videurl = $s3bucket_hdurl = $s3bucket_thumburl = $s3bucket_previewurl = 0;   
   /** To check for HD or Flv or other movies */
    exec ( $strFfmpegPath . ' ' . "-i" . ' ' . $strTmpPath . ' ' . "-sameq" . ' ' . $strTargetPath . $video_name );
    exec ( $strFfmpegPath . " -i " . $strTmpPath . ' ' . "-an -ss 00:00:03 -an -r 1 -s 120x68 -f image2" . ' ' . $strTargetPath . $thumb_name );
    exec ( $strFfmpegPath . " -i " . $strTmpPath . ' ' . "-an -ss 00:00:03 -an -r 1 -vframes 1 -y" . ' ' . $strTargetPath . $prvw_name );
        
    if ($dispenable ['amazons3'] == 1) {
     $s3bucket_videurl = $s3bucket_hdurl = $s3bucket_thumburl = $s3bucket_previewurl = 1;     
     $s3bucket_videurl = uploadFfmpegHelper::getBucketURL ($strTargetPath . $video_name , $video_name, $idval, $fileoption, 'video' );          
     $s3bucket_thumburl = uploadFfmpegHelper::getBucketURL ($strTargetPath . $thumb_name , $thumb_name, $idval, $fileoption, THUMB);     
     $s3bucket_previewurl = uploadFfmpegHelper::getBucketURL ($strTargetPath . $prvw_name , $prvw_name, $idval, $fileoption, '');               
    }   

    /** Call function to call update ffmpeg data function */
    uploadFfmpegHelper::callerToUpdateFFMPEG ( $s3bucket_videurl, $video_name, VIDEOURL, $idval);
    uploadFfmpegHelper::callerToUpdateFFMPEG ( $s3bucket_hdurl, $hd_name, HDURL, $idval);
    uploadFfmpegHelper::callerToUpdateFFMPEG ( $s3bucket_thumburl, $thumb_name, THUMBURL, $idval);
    uploadFfmpegHelper::callerToUpdateFFMPEG ( $s3bucket_previewurl, $prvw_name, PREVIEWURL, $idval); 
        
  }
  
  /** Assign streameroption */
  $streamer_option = $arrFormData ['streameroption-value'];  
  /** Fields to update */
  $fields = array ( $db->quoteName ( 'streameroption' ) . '=\'' . $streamer_option . '\'', $db->quoteName ( 'filepath' ) . '=\'' . $fileoption . '\'' );
  /** Conditions for which records should be updated */
  $conditions = array ( $db->quoteName ( 'id' ) . '=' . $idval );
  /** To update the video file name in database table */
  $query->clear ()->update ( $db->quoteName ( '#__hdflv_upload' ) )->set ( $fields )->where ( $conditions );
  $db->setQuery ( $query );
  $db->query ();
  
  /** Call function to add srt files for ffmpeg videos */
  uploadFfmpegHelper::uploadFFmpegSRTFiles ($arrFormData, $idval);
 }
 
 /**
  * Function is used to set file name
  * 
  * @param string $type
  * @param int $idval
  * 
  * @return string
  */
 public static function setFileFFMPEG($type, $idval) { 
    /** Check upload type
     * Set file name to update */
    switch ($type) {
      case VIDEOURL :
        $name = $idval . '_video' . rand () . ".flv";
        break;
      case HDURL :
        $name = '';
        break;
      case THUMBURL :
        $name = $idval . '_thumb' . rand () . ".jpeg";
        break;
      case PREVIEWURL :
        $name = $idval . '_preview' . rand () . ".jpeg";
        break;
      default :
        break;
    }
    return $name;
  }
  
 /**
  * Call function to call the function to update ffmpeg data
  * 
  * @param string $s3bucketurl
  * @param string $type
  * @param int $idval
  * 
  * @return void
  */
 public static function callerToUpdateFFMPEG ( $s3bucketurl, $name, $type, $idval) {
   /** Check bucket url is created
    * If exists update bucket url into db */ 	
   if ($s3bucketurl == 0) {
    uploadFfmpegHelper::updateFFmpegData ($type, $name, $idval);
   }
 }
 
 /**
  * Function is used to insert srt files for ffmpeg
  * 
  * @param array $arrFormData
  * @param int $idval
  * 
  * @return void
  */
 public static function uploadFFmpegSRTFiles ($arrFormData, $idval) {
   /** Get and set subtitle1 of the video */
   $ffmpgSrtFile1 = $arrFormData ['subtitle_video_srt1form-value'];
   $ffmpgArrSrtFile1 = explode ( UPLOADS . '/', $ffmpgSrtFile1 );
   if (isset ( $ffmpgArrSrtFile1 [1] )) {
     $ffmpgSrtFile1 = $ffmpgArrSrtFile1 [1];
   }
   
   /** Get and set subtitle2 of the video */
   $ffmpgSrtFile2 = $arrFormData ['subtitle_video_srt2form-value'];
   $ffmpgArrSrtFile2 = explode ( UPLOADS . '/', $ffmpgSrtFile2 );
   if (isset ( $ffmpgArrSrtFile2 [1] )) {
     $ffmpgSrtFile2 = $ffmpgArrSrtFile2 [1];
   }
   
   $ffmpeg_subtile_lang1 = $arrFormData ['subtile_lang1'];
   $ffmpeg_subtile_lang2 = $arrFormData ['subtile_lang2'];
   
   /** Get upload helper file to upload thumb */
   $uploadProcessData = array ('subtitle1' => $ffmpeg_subtile_lang1, 'subtitle2' => $ffmpeg_subtile_lang2, 'id' => $idval, 'file' => '', 'thumb'=> '', 'preview' => '', 'srt1' => $ffmpgSrtFile1, 'srt2' => $ffmpgSrtFile2, 'hdvideo' => '', 'upload' => $arrFormData ['newupload'], 'fileoption' => $arrFormData [ FILEOPTION ]);
   UploadFileHelper::uploadVideoProcessing ( $uploadProcessData );
   
   /** Delete temp file */
   if ($ffmpgSrtFile1 != '') {
     UploadFileHelper::unlinkUploadedTmpFiles ( $ffmpgSrtFile1 );
   }
   
   if ($ffmpgSrtFile2 != '') {
     UploadFileHelper::unlinkUploadedTmpFiles ( $ffmpgSrtFile2 );
   }
 }
 
 /**
  * Function to get file extensions
  *
  * @param var $strFileName
  *         filename
  *         
  * @return getFileExtension
  * 
  * @return string
  */
 public static function getFileExtension($strFileName) {
  $strFileName = strtolower ( $strFileName );
  
  return JFile::getExt ( $strFileName );
 }
 
 /** 
  * Fucntion is used to generate amazon bucket url
  * 
  * @param string $strTargetPath
  * @param int $idval
  * @param string $file_name
  * @param int $fileoption
  * @param string $type
  * 
  * @return number
  */
 public static function getBucketURL ($strTargetPath , $file_name, $idval, $fileoption, $type) {   
   include FVPATH . DS . 'helpers' . DS . 's3_config.php';
      
   if($type == 'video') {
    $URLType = VIDEOURL;    
   } else if ($type == THUMB) {
     $URLType = THUMBURL;    
   } else {
     $URLType = PREVIEWURL; 
   }
   $strVids3TargetPath = $file_name;
   
   /** Get bucket url */
   if ($s3->putObjectFile ( $strTargetPath, $bucket, $strVids3TargetPath, S3::ACL_PUBLIC_READ )) {
     UploadFileHelper::amazons3update ( $strTargetPath , $file_name, $idval, $URLType, $fileoption );
   } else {
     $s3bucket_url = 0;
   } 
   return $s3bucket_url;   
 }

  /**
   * Function is used to update videos table fields
   * 
   * @param string $urltype
   * @param string $filename
   * @param int $idval
   * 
   * @return void
   */
  public static function updateFFmpegData ($urltype, $filename, $idval) {
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Fields to update */
    $fields = array ( $db->quoteName ( $urltype ) . '=\'' . $filename . '\'' );
    /** Conditions for which records should be updated */
    $conditions = array ( $db->quoteName ( 'id' ) . '=' . $idval );
    /** Query to update video information to video table */
    $query->clear ()->update ( $db->quoteName ( '#__hdflv_upload' ) )->set ( $fields )->where ( $conditions );
    $db->setQuery ( $query );
    $db->query ();
  } 
}
