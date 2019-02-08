<?php
/**
 * Upload videos model file
 *
 * This file is to upload videos for front end users
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

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );

/** Import filesystem libraries */
jimport ( 'joomla.filesystem.file' );

/**
 * Upload videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModeluploadvideo extends ContushdvideoshareModel {
  /**
   * Constructor - global variable initialization
   */
  public function __construct() {
    global $option, $mainframe, $allowedExtensionsSite;
    global $target_path, $errorSite, $errorcodeSite, $errormsg, $clientupload_val_site, $s3bucket_video;
    parent::__construct ();
    
    /** Get global configuration */
    $mainframe = JFactory::getApplication ();
    /** Varaible declaration for option */
    $option = JRequest::getVar ( 'option' );
    /** variable declaration for target and errorsite */
    $target_path = $errorSite = '';
    /** set default value of error to 12 */
    $errorcodeSite = 12;
    /** set client value to false */
    $clientupload_val_site= "false";
    /** Message for file uploaded successfully */
    $errormsg [0] = " " . JText::_ ( 'HDVS_FILE_UPLOAD_SUCCESSFULLY' );
    /** message when use cancels the upload */
    $errormsg [1] = " " . JText::_ ( 'HDVS_CANCELLED_BY_USER' );
    /** Error message to specify invalid file type */
    $errormsg [2] = " " . JText::_ ( 'HDVS_INVALID_FILE_TYPE_SPECIFIED' );
    /** Error message when the file upload exceeds the maximum limit */
    $errormsg [3] = " " . JText::_ ( 'HDVS_YOUR_FILE_EXCEEDS_SERVER_LIMIT_SIZE' );
    /** Error message to notify when unknow error occurs */
    $errormsg [4] = " " . JText::_ ( 'HDVS_UNKNOWN_ERROR_OCCURED' );
    /** Error message to notify if file upload exceeds upload max size directive */
    $errormsg [5] = " " . JText::_ ( 'HDVS_UPLOAD_FILE_EXCEEDS_THE_UPLOAD_MAX_FILESIZE_DIRECTIVE' );
    $errormsg [6] = " " . JText::_ ( 'HDVS_UPLOAD_FILE_EXCEEDS_THE_UPLOAD_MAX_FILESIZE_DIRECTIVE_THAT_WAS_SPECIFIED' );
    /** Error message to notify that file was partially uploaded. */
    $errormsg [7] = " " . JText::_ ( 'HDVS_THE_UPLOAD_FILE_WAS_ONLY_PARTIALLY_UPLOADED' );
    /** Error message to notify that file was not uploaded */
    $errormsg [8] = " " . JText::_ ( 'HDVS_NO_FILE_WAS_UPLOADED' );
    /** Error to notify that temporary folder is missing */
    $errormsg [9] = " " . JText::_ ( 'HDVS_MISSING_A_TEMORARY_FOLDER' );
    /** Error message to notify thet uploaded video failed to write in the disk */
    $errormsg [10] = " " . JText::_ ( 'HDVS_FAILED_TO_WRITE_FILE_TO_DISK' );
    /** Error message to notify that file upload stopped by extension */
    $errormsg [11] = " " . JText::_ ( 'HDVS_FILE_UPLOAD_STOPPED_BY_EXTENSION' );
    /** Error message to notify that unknow file upload error occured */
    $errormsg [12] = " " . JText::_ ( 'HDVS_UNKNOWN_UPLOAD_ERROR' );
    /** Error message to notify to check the PHP ini settings */
    $errormsg [13] = " " . JText::_ ( 'HDVS_PLEASE_CHECK_PHPINI_SETTINGS' );
    define ('ERROR', 'error');
  }
  
  /**
   * Function to check allowed extensions while uploading file
   *
   * @return multitype:string
   */
  public function allowedExtensionSite () {
    /** Declare global variable */
   global $allowedExtensionsSite;
    if (JRequest::getVar ( 'mode' ) != '') {
      /** variable declaration for extension type */
      $exttype_site = JRequest::getVar ( 'mode' );
      /** Switch extension type */
      switch($exttype_site) {
        case 'video':
          /** allowed extension for uploading the video */
          $allowedExtensionsSite = array ( "mp3", "MP3", "flv", "FLV", "mp4", "MP4", "m4v", "M4V", "M4A", "m4a", "MOV", "mov", "mp4v", "Mp4v", "F4V", "f4v" );
          break;
        case 'image':
          /** allowed extension for the uploaded image file */
          $allowedExtensionsSite = array ( "jpg", "JPG", "jpeg", "JPEG", "png", "PNG" );
          break;
        case 'srt':
          /** allowed extension format for srt files */
          $allowedExtensionsSite = array ( "srt", "SRT" );
          break;
          /** allowed extensions for the FFMPEG method */
        case 'video_ffmpeg':
          $allowedExtensionsSite = array ( "avi", "AVI", "dv", "DV", "3gp", "3GP", "3g2", "3G2", "mpeg", "MPEG", "wav", "WAV", "rm", "RM",
          "mp3", "MP3", "flv", "FLV", "mp4", "MP4", "m4v", "M4V", "M4A", "m4a", "MOV", "mov", "mp4v", "Mp4v", "F4V", "f4v" );
          break;
        default:
          break;
      }
      /** return array of allowed extensions */
      return $allowedExtensionsSite;
    }
  }
  
  /**
   * Function to get uploaded file details from form
   *
   * @return void
   */
  public function fileupload() {
    /** Declare global variable */
    global $clientupload_val_site, $allowedExtensionsSite, $errorcodeSite, $errorSite, $s3bucket_video, $errormsg;
    
    if (JRequest::getVar ( ERROR ) != '') {
      /** variable declaration for the error variable */
      $errorSite = JRequest::getVar ( ERROR );
    }
    
    if (JRequest::getVar ( 'processing' ) != '') {
      /** variable declaration for processing */
      $proSite = JRequest::getVar ( 'processing' );
    }
    
    if (JRequest::getVar ( 'clientupload' ) != '') {
      /** variable declaration for the client upload */
      $clientupload_val_site = JRequest::getVar ( 'clientupload' );
    }
    /** get uploaded file data */
    $uploadFileSite = JRequest::getVar ( 'myfile', null, 'files', 'array' );
    /** call to method to check the extensions */
    $allowedExtensionsSite = $this->allowedExtensionSite();
    
    /** Function to check error */
    if (! $this->iserror ()) {
      /** Check if stopped by post_max_size */
      if (($proSite == 1) && (empty ( $uploadFileSite ))) {
        /** set error code defined in 13 */
        $errorcodeSite = 13;
      } else {
        /** variable declaration for the uploaded file */
        $file = $uploadFileSite;
        /** Check file size */
        if ($this->no_file_upload_error ( $file ) && $this->isAllowedExtension ( $file ) && ! $this->filesizeexceeds ( $file )) {
          /** call to method to upload the files to the respective directory */
           $final_target_path = $this->doupload ( $file, $clientupload_val_site);
        }
      }
    }
    /** include script file to throw messages for the upload */
    ?>
<script language="javascript" type="text/javascript"> 
    window.top.window.updateQueue( <?php echo $errorcodeSite;?>, 
          "<?php echo $errormsg[$errorcodeSite]; ?>", 
          "<?php echo $final_target_path; ?>", 
          "<?php echo $s3bucket_video; ?>" ); 
</script>
<?php
  }
  
  /**
   * Function to check error
   *
   * @return bool
   */
  public function iserror() {
    /** declare global variable for errorsite and errorcode site */
    global $errorSite;
    global $errorcodeSite;    
    if ($errorSite == "cancel") {
  /** variable declaration for errorcode */
      $errorcodeSite = 1;      
      /** return true */
      return true;
    } else {
      /** return false */
      return false;
    }
  }
  
  /**
   * Function to set file upload error
   *
   * @param Object $file video file detail
   *          
   * @return bool
   */
  function no_file_upload_error($file) {    
    /** Get error code */
    $error_code = $file [ERROR];
    /** If file is upload then return true */
    if($error_code == 0) {
    /** return true */
      return true;
    } else {
      /** Call function to get error code
       * Else set error message based on the error codes */
      setErrorCode($error_code);
      /** return false */
      return false;
    }
  }
  
  /** 
   * Function to set error code to display error message 
   * 
   * @return unknown
   */
  function setErrorCode($error_code) {
/** declare global variable for errorcode site */
  global $errorcodeSite;
    switch ($error_code) {
      case 1 :
      case 2 :
        $errorcodeSite = 5;
        break;
      case 3 :
        $errorcodeSite = 7;
        break;
      case 4 :
        $errorcodeSite = 8;
        break;
      case 6 :
        $errorcodeSite = 9;
        break;
      case 7 :
        $errorcodeSite = 10;
        break;
      case 8 :
        $errorcodeSite = 11;
        break;
      default :
        $errorcodeSite = 12;
        break;
    }
    /** return respective error codes based on the condition */
    return $errorcodeSite;
  }
 
  
  /**
   * Function to check the extension of the file
   *
   * @param Object $file
   *          video file detail
   *          
   * @return bool
   */
  public function isAllowedExtension($fileSite) {
  /** global variable declaration for checking the extensions */
    global $allowedExtensionsSite;
    /** global varible declared for the errorcode  */
    global $errorcodeSite; 
    /** variable for filename */
    $filename_site = $fileSite ['name'];
    /** explode the file name */
    $ext = explode ( ".", $filename_site ) ;
    /** check for the availabilty file extension  */
    $output = in_array ( $ext[1], $allowedExtensionsSite );
    if (! $output) {
      /** set message stated in error code 2 if extension is not allowed */
      $errorcodeSite = 2;
      return false;
    } else {
      /** return true */
      return true;
    }
  }
  
  /**
   * Function to check the file size
   *
   * @param Object $file
   *          video file detail
   *          
   * @return bool
   */
  public function filesizeexceeds() {
    /** Global variable errorcodesite */
    global $errorcodeSite;
    /** get post_max_size form the ini file */
    $POST_MAX_SIZE = ini_get ( 'post_max_size' );
    /** calculate the post max size of the uploaded file */
    $calc = substr ( $POST_MAX_SIZE, - 1 );
    $calc = ($calc == 'M' ? 1048576 : ($calc == 'K' ? 1024 : ($calc == 'G' ? 1073741824 : 1)));
    if ($_SERVER ['CONTENT_LENGTH'] > $calc * ( int ) $POST_MAX_SIZE && $POST_MAX_SIZE) {
      /** set error code 3 if uploaded file exceeds the post_max_size */
      $errorcodeSite = 3;
      return true; 
    } else {
      /** return true */
      return false;
    }
  }
  
  /**
   * Function to upload video to temporary folder
   *
   * @param Object $file
   *          video file detail
   * @param string $clientupload_val_site
   *          check for new upload
   *          
   * @return string
   */
  public function doupload($file, $clientupload_val_site) {
  /** global variable for Amazon s3 bucket and error code */
    global $errorcodeSite, $s3bucket_video;
    /** global varaible for video target path */
    global $target_path;
    /** Get display enable setting from the database */
    $dispenable = getSiteSettings (); 
    /** set destination path for the video upload */
    $destination_path = "components/com_contushdvideoshare/views/videoupload/tmpl";
    if ($clientupload_val_site== "true") { 
     /** set destination path for the uploaded video*/
      $destination = realpath ( dirname ( __FILE__ ) . '/../../../components/com_contushdvideoshare/videos/' );
      $destination_path = str_replace ( '\\', '/', $destination ) . "/";
    }
    /** set a safe name for the uploaded videos */
    $filename = JFile::makeSafe ( $file ['name'] );
    /** Explode file name  */
    $fileext = explode ( ".", $filename );
    /** set the target path for the uploaded video file */
    $target_path = $destination_path . rand () . "." .$fileext[1];
    
    /** Clean up filename to get rid of strange characters like spaces etc */
    $sourceImage = $file ['tmp_name'];
    /** check if Amazon s3 option is enabled */
    if ($dispenable ['amazons3'] == 1) {
      $s3bucket_video = 1;
      /** include the path for Amazon S3 bucket */
      include JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 's3_config.php';
      /** Uploaded video target path in Amazon bucket */
      $strVids3TargetPath = $dispenable ['amazons3path'] . $filename;
      /** Move the file to Amazon S3 bucket */
      if ($s3->putObjectFile ( $sourceImage, $bucket, $strVids3TargetPath, S3::ACL_PUBLIC_READ )) {
        $s3bucket_video = 1;
        $errorcodeSite = 0;
      } else {
        $s3bucket_video = 0;
      }
    }

    if ($s3bucket_video == 0) {
      $strVids3TargetPath = $target_path;
      
      /** To store images to a directory called components/com_contushdvideoshare/videos */
      if (JFile::upload ( $sourceImage, $target_path )) {
            /** set the error code 0 */
        $errorcodeSite = 0;
      } else {
        /** set the error code 4 */
        $errorcodeSite = 4;
      }
    }
    
    sleep ( 1 );    
    /** return the video uploaded path */
    return $strVids3TargetPath;
  }
}
