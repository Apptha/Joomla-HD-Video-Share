<?php 
/**
 * Upload method helper
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

/** Import filesystem libraries */
jimport ( 'joomla.filesystem.file' );

/**
 * Admin UploadFileHelper class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class UploadFileHelper {
 /**
  * Function to upload file type videos
  *
  * @param array $arrFormData
  *         post content
  * @param int $idval
  *         video id
  *         
  * @return uploadFile
  */
 public static function uploadFile($arrFormData, $idval) {
  /** Get video and set video name */
  $strVideoName = UploadFileHelper::getfileName ( $arrFormData, 'normalvideo' );
  
  /** Get hdvideo and set hdvideo name */
  $strHdVideoName = UploadFileHelper::getfileName ( $arrFormData, 'hdvideo' );
  
  /** Get thumb image name and set thumb image name */
  $strThumbImg = UploadFileHelper::getfileName ( $arrFormData, 'thumbimage' );
    
  /** Get preview image name and set preview image name */
  $strPreviewImg = UploadFileHelper::getfileName ( $arrFormData, 'previewimage' );
    
  /** Get and set subtitle1 of the video */
  $uploadSrtFile1 = UploadFileHelper::getfileName ( $arrFormData, 'subtitle_video_srt1' );
  
  /** Get and set subtitle2 of the video */
  $uploadSrtFile2 = UploadFileHelper::getfileName ( $arrFormData, 'subtitle_video_srt2' );
  
  $subtileLang1 = $arrFormData ['subtile_lang1'];
  $subtileLang2 = $arrFormData ['subtile_lang2'];
  
  $uploadProcessData = array ('subtitle1' => $subtileLang1, 'subtitle2' => $subtileLang2, 'id' => $idval, 'file' => $strVideoName, 'thumb'=> $strThumbImg, 'preview' => $strPreviewImg, 'srt1' => $uploadSrtFile1, 'srt2' => $uploadSrtFile2, 'hdvideo' => $strHdVideoName, 'upload' => $arrFormData ['newupload'], 'fileoption' => $arrFormData ['fileoption']); 
  /** Function to upload video */
  UploadFileHelper::uploadVideoProcessing ( $uploadProcessData );
  
  /**
   * DELETE TEMPORARY FILES
   * delete temporary existing videos,hd videos,thumb image and preview image
   * after files moved from temporary path to target path
   * @ Temp Path : components/com_contushdvideoshare/images/uploads/
   * @ Target Path : components/com_contushdvideoshare/videos
   */
  UploadFileHelper::checkFileToUnlink ( $strVideoName );
  UploadFileHelper::checkFileToUnlink ( $strHdVideoName );
  UploadFileHelper::checkFileToUnlink ( $strThumbImg );
  UploadFileHelper::checkFileToUnlink ( $strPreviewImg );
  UploadFileHelper::checkFileToUnlink ( $uploadSrtFile1 );
  UploadFileHelper::checkFileToUnlink ( $uploadSrtFile2 );  
 }
 
 /**
  * Function to get file name for upload method 
  * 
  * @param unknown $arrFormData
  * @param unknown $type
  * @return unknown
  */
 public static function getFileName ( $arrFormData, $type ) { 
    /** Get form value */
    $strName = $arrFormData [ $type . 'form-value'];
    /** Split file name */
    $arrName = explode ( 'uploads/', $strName );
    /** Check file name is exists */
    if (isset ( $arrName [1] )) {
      /** Get uploaded file name */
      $strName = $arrName [1];
    } 
    /** Return uploaded file name */
    return $strName;
 }
 
 /**
  * Function to check file is exists to unlink
  * 
  * @param unknown $strame
  */
 public static function checkFileToUnlink ( $strame ) {
   if ($strame != '') {
     UploadFileHelper::unlinkUploadedTmpFiles ( $strame );
   }
 }
 
 /**
  * Function to upload file from temporary path
  * 
  * @param unknown $uploadProcessData
  */
 public static function uploadVideoProcessing( $uploadProcess ) {  
  $uploadProcessData = $uploadProcess; 
  UploadFileHelper::getFileDetailsTocopy ( $uploadProcessData, 'file' );
  UploadFileHelper::getFileDetailsTocopy ( $uploadProcessData, 'thumb' );
  UploadFileHelper::getFileDetailsTocopy ( $uploadProcessData, 'preview' );
  
  UploadFileHelper::getFileDetailsTocopy ( $uploadProcessData, 'srt1' );
  UploadFileHelper::getFileDetailsTocopy ( $uploadProcessData, 'srt2' );
  UploadFileHelper::getFileDetailsTocopy ( $uploadProcessData, 'hdvideo' );  
 }
 
 /**
  * Function is used to get file path to copy uploaded files into videos folder  
  * 
  * @param unknown $uploadProcessData
  * @param unknown $type
  */
 public static function getFileDetailsTocopy ( $uploadProcessData, $type ) {
   $newupload = $fv = $urlType = ''; 
   $filepath = $idval = $s3bucket_video = 0;
   $strTargetPath = VPATH . "/";
   
   if(!empty ($uploadProcessData) && isset ($uploadProcessData)) {
     $uploadfile = $uploadProcessData [$type];
     $idval = $uploadProcessData ['id'];
     $filepath = $uploadProcessData ['fileoption'];
     $newupload = $uploadProcessData ['upload'];
   }
   
   /** Get site settings and bucket name */
   $dispenable = getSiteSettings();
   
   if ($uploadfile != '') {
     $exts = UploadFileHelper::getFileExtension ( $uploadfile );
     $strVidTempPath = FVPATH . "/images/uploads/" . $uploadfile;

     /** Call function to get file name and url type */
     $result = UploadFileHelper::setFileName ($type, $exts, $uploadProcessData);
     if( isset($result) && !empty ($result)) {
       $fv = $result ['filename'];
       $urlType = $result ['urltype']; 
     }
     
     $strVidTargetPath = $strTargetPath . $fv;
     if ($dispenable ['amazons3'] == 1) {
       include FVPATH . DS . 'helpers' . DS . 's3_config.php'; 
       $strVids3TargetPath = $fv; 
       if ($s3->putObjectFile ( $strVidTempPath, $bucket, $strVids3TargetPath, S3::ACL_PUBLIC_READ )) { 
         $s3bucket_video = 1; 
         UploadFileHelper::amazons3update ( $strVidTempPath, $fv, $idval, $urlType, $filepath );
       } 
     } elseif ($s3bucket_video == 0) {
       /** Function to copy from imasges/uploads to /components/com_hdvideoshare/videos/ */
       UploadFileHelper::copytovideos ( $strVidTempPath, $strVidTargetPath, $fv, $idval, $urlType, $newupload, $filepath );
     }
   }
 }
 
 /**
  * Function to set file name and url type
  * 
  * @param unknown $type
  * @param unknown $exts
  * @param unknown $uploadProcessData
  * @return multitype:string
  */
 public static function setFileName ($type, $exts, $uploadProcessData) {
   $idval = 0;
   $subtile_lang1 = $subtile_lang2 = '';
   
   if(!empty ($uploadProcessData) && isset ($uploadProcessData)) {
     $idval = $uploadProcessData ['id'];
     $subtile_lang1 = $uploadProcessData ['subtitle1'];
     $subtile_lang2 = $uploadProcessData ['subtitle2'];
   }
   
   /** Check type 
    * Assign file name and url type based on that */
   switch ($type) {
      case 'file' :
        $fv = $idval . "_video" . rand() . "." . $exts;
        $urlType = 'videourl';
        break;
      case 'thumb' :
        $fv = $idval . "_thumb" . rand() . "." . $exts;
        $urlType = 'thumburl';
        break;
      case 'preview' :
        $fv = $idval . "_preview" . rand() . "." . $exts;
        $urlType = 'previewurl';
        break;
      case 'srt1' :
        $subtile_lang1 = $uploadProcessData ['subtitle1'];
        $fv = $idval . "_" . $subtile_lang1 . rand() . "." . $exts;
        $urlType = 'subtitle1';
        break;
      case 'srt2' :
        $subtile_lang2 = $uploadProcessData ['subtitle2'];
        $fv = $idval . "_" . $subtile_lang2 . rand() . "." . $exts;        
        $urlType = 'subtitle2';
        break;
      case 'hdvideo' :
        $fv = $idval . "_hd" . rand() . "." . $exts;
        $urlType = 'hdurl';
        break;
      default: break;
   }
   /** Return file name and url type */
   return array ('filename' => $fv, 'urltype' => $urlType );
 }
 
 /**
  * Function to move files from temp path to target path
  *
  * @param var $strFileTempPath
  *         file temp path
  * @param var $vmfile
  *         db values
  * @param int $idval
  *         Video id
  * @param var $dbname
  *         db field name
  * @param var $filepath
  *         upload method type
  *         
  * @return amazons3update
  */
 public static function amazons3update($strFileTempPath, $vmfile, $idval, $dbname, $filepath) {
  $db = JFactory::getDBO ();
  $query = $db->getQuery ( true );
  
  /** Check thumb image is default thumb image */
  if ($strFileTempPath == 'default_thumb') {
   $vmfile = 'default_thumb.jpg';
  }
  
  /** Fields to update */
  $fields = array ( $db->quoteName ( 'streameroption' ) . '=\'None\'', $db->quoteName ( $dbname ) . '=\'' . $vmfile . '\'',
    $db->quoteName ( 'filepath' ) . '=\'' . $filepath . '\'', $db->quoteName ( 'amazons3' ) . '=\'1\'' );
  
  /** Conditions for which records should be updated */
  $conditions = array ( $db->quoteName ( 'id' ) . '=' . $idval );
  
  /** Update streamer option,thumb url and file path */
  $query->clear ()->update ( $db->quoteName ( '#__hdflv_upload' ) )->set ( $fields )->where ( $conditions );
  $db->setQuery ( $query );
  $db->query ();
 }
 
 /**
  * Function to move files from temp path to target path
  *
  * @param var $strFileTempPath
  *         file temp path
  * @param var $strFileTargetPath
  *         file target path
  * @param var $vmfile
  *         db values
  * @param int $idval
  *         Video id
  * @param var $dbname
  *         db field name
  * @param var $newupload
  *         to check whether newly uploaded video or not
  * @param var $filepath
  *         upload method type
  *         
  * @return copytovideos
  */
 public static function copytovideos($strFileTempPath, $strFileTargetPath, $vmfile, $idval, $dbname, $newupload, $filepath) {
  $db = JFactory::getDBO ();
  $query = $db->getQuery ( true );
  
  /** Check thumb image is default thumb image */
  if ($strFileTempPath != 'default_thumb') {
   /**
    * To make sure in edit mode video ,hd, thumb image or preview image file is exists
    * if exists then remove the old one
    */
   if ($newupload == 1 && JFile::exists ( $strFileTempPath ) && JFile::exists ( $strFileTargetPath )) {
     JFile::delete ( $strFileTargetPath );    
   }
   /** Function to files move from temp folder to target path */
   if (JFile::exists ( $strFileTempPath )) {
    rename ( $strFileTempPath, $strFileTargetPath );
   }
  } else {
   $vmfile = 'default_thumb.jpg';
  }
  
  /** Fields to update */
  $fields = array ( $db->quoteName ( 'streameroption' ) . '=\'None\'', $db->quoteName ( $dbname ) . '=\'' . $vmfile . '\'',
    $db->quoteName ( 'filepath' ) . '=\'' . $filepath . '\''  );
  
  /** Conditions for which records should be updated */
  $conditions = array ( $db->quoteName ( 'id' ) . '=' . $idval );
  
  /** Update streamer option,thumb url and file path */
  $query->clear ()->update ( $db->quoteName ( '#__hdflv_upload' ) )->set ( $fields )->where ( $conditions );
  $db->setQuery ( $query );
  $db->query ();
  
  $arrVideoName = explode ( 'uploads/', $strFileTempPath );
  
  if (isset ( $arrVideoName [1] )) {
   $arrVideoName [] = $arrVideoName [1];
  }
 }
 
 /**
  * Function to delete files from temp path
  *
  * @param var $strFileName
  *         temp file name
  *         
  * @return unlinkUploadedTmpFiles
  */
 public static function unlinkUploadedTmpFiles($strFileName) {
  $strFilePath = FVPATH . "/images/uploads/$strFileName";
  
  if (JFile::exists ( $strFilePath )) {
   JFile::delete ( $strFilePath );
  }
 }
 
 /**
  * Function to get file extensions
  *
  * @param var $strFileName
  *         file name
  *         
  * @return getFileExtension
  */
 public static function getFileExtension($strFileName) {
  $strFileName = strtolower ( $strFileName );
  
  return JFile::getExt ( $strFileName );
 }
}
