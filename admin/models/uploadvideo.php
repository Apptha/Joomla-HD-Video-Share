<?php
/**
 * Upload videos model file
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

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );

/** Import filesystem libraries */
jimport ( 'joomla.filesystem.file' );

/**
 * Admin upload video model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModeluploadvideo extends ContushdvideoshareModel {
  /** Constructor function to declare global value */
  public function __construct() {
    global $option, $mainframe, $allowedExtensions;
    global $target_path, $errorAdmin, $errorcode, $errormsg, $clientupload_val_admin;
    parent::__construct ();
    
    /** Get global configuration */
    $mainframe = JFactory::getApplication ();
    $option = JRequest::getVar ( 'option' );
    $target_path = $errorAdmin = $allowedExtensions = '';
    $errorcode = 12;
    $clientupload_val_admin = "false";
    $errormsg [0] = " File Uploaded Successfully";
    $errormsg [1] = " Cancelled by user";
    $errormsg [2] = " Invalid File type specified";
    $errormsg [3] = " Your File Exceeds Server Limit size";
    $errormsg [4] = " Unknown Error Occured";
    $errormsg [5] = " The uploaded file exceeds the upload_max_filesize directive in php.ini";
    $errormsg [6] = " The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
    $errormsg [7] = " The uploaded file was only partially uploaded";
    $errormsg [8] = " No file was uploaded";
    $errormsg [9] = " Missing a temporary folder";
    $errormsg [10] = " Failed to write file to disk";
    $errormsg [11] = " File upload stopped by extension";
    $errormsg [12] = " Unknown upload error.";
    $errormsg [13] = " Please check post_max_size in php.ini settings";
    define ('ADMIN_ERROR', 'error');
  }
  
  /**
   * Function to check allowed extensions while uploading file
   * 
   * @return multitype:string
   */
  public function allowedExtension () {
    if (JRequest::getVar ( 'mode' ) != '') {
      $exttypeAdmin = JRequest::getVar ( 'mode' );
      switch($exttypeAdmin) {
        case 'video':
          $allowedExtensions = array ( "mp3", "MP3", "flv", "FLV", "mp4", "MP4", "m4v", "M4V", "M4A", "m4a", "MOV", "mov", "mp4v", "Mp4v", "F4V", "f4v" );
          break;
        case 'image':
          $allowedExtensions = array ( "jpg", "JPG", "jpeg", "JPEG", "png", "PNG" );
          break;
        case 'srt':
          $allowedExtensions = array ( "srt", "SRT" );
          break;
        case 'video_ffmpeg':
          $allowedExtensions = array ( "avi", "AVI", "dv", "DV", "3gp", "3GP", "3g2", "3G2", "mpeg", "MPEG", "wav", "WAV", "rm", "RM",
          "mp3", "MP3", "flv", "FLV", "mp4", "MP4", "m4v", "M4V", "M4A", "m4a", "MOV", "mov", "mp4v", "Mp4v", "F4V", "f4v" );
          break;
        default:
          break;
      }
      return $allowedExtensions;
    }
  }
  /**
   * Function to get uploaded file details from form
   *
   * @return fileupload
   */
  public function fileupload() {
    global $clientupload_val_admin, $allowedExtensions, $errorcode, $errorAdmin, $target_path, $errormsg;
    
    if (JRequest::getVar ( ADMIN_ERROR ) != '') {
      $errorAdmin = JRequest::getVar ( ADMIN_ERROR );
    }
    
    if (JRequest::getVar ( 'processing' ) != '') {
      $proAdmin = JRequest::getVar ( 'processing' );
    }
    
    if (JRequest::getVar ( 'clientupload' ) != '') {
      $clientupload_val_admin = JRequest::getVar ( 'clientupload' );
    }
    
    $uploadFileAdmin = JRequest::getVar ( 'myfile', null, 'files', 'array' );
    
    $allowedExtensions = $this->allowedExtension();
    
    /** Check error */
    if (! $this->iserror ()) {
      /** Check if stopped by post_max_size */
      if (($proAdmin == 1) && (empty ( $uploadFileAdmin ))) {
        $errorcode = 13;
      } else {
        $file = $uploadFileAdmin;
        /** Check file size */
        if ($this->no_file_upload_error ( $file ) && $this->isAllowedExtension ( $file ) && ! $this->filesizeexceeds ( $file )) {
              $this->doupload ( $file, $clientupload_val_admin );
        }
      }
    }
    ?>
<script language="javascript" type="text/javascript">
window.top.window.updateQueue( <?php echo $errorcode;?>, 
      "<?php echo $errormsg[$errorcode]; ?>", 
      "<?php echo $target_path; ?>" ); 
</script>
<?php
  }
  
  /**
   * Function to check error
   *
   * @return iserror
   */
  public function iserror() {
    global $errorAdmin;
    global $errorcode;
    
    if ($errorAdmin == "cancel") {
      $errorcode = 1;
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Function to set file upload error
   *
   * @param object $file
   *          uploaded file
   *          
   * @return no_file_upload_error
   */
  function no_file_upload_error($file) {
    /** Get error code */
    $adminerror_code = $file [ADMIN_ERROR];
    /** If file is upload then return true */
    if($adminerror_code == 0) {
      return true;
    } else {
      /** Call function to get error code
       * Else set error message based on the error codes */
      setErrorCode($adminerror_code);
      return false;
    }
  }
  
  /**
   * Function to set error code to display error message
   *
   * @return unknown
   */
  function setErrorCode($adminerror_code) {
    global $errorcode;
    switch ($adminerror_code) {
      case 1 :
      case 2 :
        $errorcode = 5;
        break;
      case 3 :
        $errorcode = 7;
        break;
      case 4 :
        $errorcode = 8;
        break;
      case 6 :
        $errorcode = 9;
        break;
      case 7 :
        $errorcode = 10;
        break;
      case 8 :
        $errorcode = 11;
        break;
      default :
        $errorcode = 12;
        break;
    }
    return $errorcode;
  }
  
  /**
   * Function to check the extension of the file
   *
   * @param object $file
   *          uploaded file
   *          
   * @return isAllowedExtension
   */
  public function isAllowedExtension($file) {
    global $allowedExtensions;
    global $errorcode;
    $filename = $file ['name'];
    $fileext =  explode ( ".", $filename ); 
    $output = in_array ( $fileext[1], $allowedExtensions );
    
    if (! $output) {
      $errorcode = 2;
      
      return false;
    } else {
      return true;
    }
  }
  
  /**
   * Function to check the file size
   *
   * @param object $file
   *          uploaded file
   *          
   * @return filesizeexceeds
   */
  public function filesizeexceeds() {
    global $errorcode;
    $POST_MAX_SIZE = ini_get ( 'post_max_size' );
    $mul = substr ( $POST_MAX_SIZE, - 1 );
    $muxsize = ($mul == 'M' ? 1048576 : ($mul == 'K' ? 1024 : ($mul == 'G' ? 1073741824 : 1)));
    
    if ($_SERVER ['CONTENT_LENGTH'] > $muxsize * ( int ) $POST_MAX_SIZE && $POST_MAX_SIZE) {
      $errorcode = 3;
      return true;      
    } else {
      return false;
    }
  }
  
  /**
   * Function to upload video to temporary folder
   *
   * @param object $file
   *          uploaded file
   * @param boolean $clientupload_val_admin
   *          uploaded type
   *          
   * @return doupload
   */
  public function doupload($file, $clientupload_val_admin) {
    global $errorcode;
    global $target_path;
    $destination_path = "components/com_contushdvideoshare/images/uploads/";
    
    if ($clientupload_val_admin == "true") {
      $destination = realpath ( dirname ( __FILE__ ) . '/../../../components/com_contushdvideoshare/videos/' );
      $destination_path = str_replace ( '\\', '/', $destination ) . "/";
    }
    
    $filename = JFile::makeSafe ( $file ['name'] );
    $fileext = explode ( ".", $filename );
    $target_path = $destination_path . rand () . "." . $fileext[1];
    
    /** Clean up filename to get rid of strange characters like spaces etc */
    $sourceImage = $file ['tmp_name'];
    
    /** To store images to a directory called components/com_contushdvideoshare/videos */
    if (JFile::upload ( $sourceImage, $target_path )) {
      $errorcode = 0;
    } else {
      $errorcode = 4;
    }
    sleep ( 1 );
  }
}
