<?php
/**
 * Youtube helper
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

/**
 * Admin UploadYouTubeHelper class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class UploadYouTubeHelper {
 /**
  * Function to upload youtube type videos
  *
  * @param array $arrFormData
  *         post content
  * @param int $idval
  *         video id
  *         
  * @return uploadYouTube
  */
 public static function uploadYouTube( $arrFormData, $idval ) {
  $hdurl = $tags = "";

  /** Update fields */
  $db     = JFactory::getDBO ();
  $query  = $db->getQuery ( true );
  
  /** Get upload helper file to upload thumb */
  require_once FVPATH . DS . 'helpers' . DS . 'uploadfile.php';
  
  $videourl         = strrev ( $arrFormData ['videourl-value'] );
  /** Assign streameroption */
  $streamer_option  = $arrFormData ['streameroption-value'];
  $fileoption       = $arrFormData ['fileoption'];
  if (! empty ( $arrFormData ['tags'] )) {
    $tags           = $arrFormData ['tags'];
  }
  
  /** Get and set subtitle1 of the video */
  $ytSrtFile1 = UploadFileHelper::getfileName ( $arrFormData, 'subtitle_video_srt1' );  
  /** Get and set subtitle2 of the video */
  $ytSrtFile2 = UploadFileHelper::getfileName ( $arrFormData, 'subtitle_video_srt2' );
  $yt_subtile_lang1 = $arrFormData ['subtile_lang1'];
  $yt_subtile_lang2 = $arrFormData ['subtile_lang2'];
  
  /** Check video url is youtube */
  if (strpos ( $videourl, 'youtube' ) > 0 || strpos ( $videourl, 'youtu.be' ) > 0) {
     $imgval      = getYoutubeVideoID ($videourl) ;
     $imgURL      = "http://img.youtube.com/vi/" . $imgval;
     $previewurl  = $imgURL . "/maxresdefault.jpg";
     $img         = $imgURL . "/mqdefault.jpg";
     $videourl    = "http://www.youtube.com/watch?v=" . $imgval ;
  } elseif (strpos ( $videourl, 'vimeo' ) > 0) {    
     /** Check video url is youtube */
     $vimeoVideoData = getVimeoDetails($videourl);
     if(!empty($vimeoVideoData) && isset($vimeoVideoData)) {
        $img  = $vimeoVideoData[0];
        $tags = $vimeoVideoData[1];
     }
  } elseif (strpos ( $videourl, 'dailymotion' ) > 0) {
     /** Check video url is dailymotion */
     $split_id  = getDailymotionVideoID ($videourl);
     $img       = $previewurl = 'http://www.dailymotion.com/thumbnail/video/' . $split_id [0];
  } elseif (strpos ( $videourl, 'viddler' ) > 0) {
     /** Check video url is viddler */
     $imgstr  = explode ( "/", $videourl );
     $img     = $previewurl = "http://cdn-thumbs.viddler.com/thumbnail_2_" . $imgstr [4] . "_v1.jpg";
  }  else {
     /** Call function to get video details using curl */ 
     $location    = UploadYouTubeHelper::fetchDetailsUsingCurl ( $videourl );
     $location2   = explode ( 'location2=', $location [2] );
     $imageurl    = explode ( 'imageurl=', $location [4] );
     $img         = $imageurl [1];
     if ($location2 [1] != "") {
      $hdurl = $videourl;
     }
  }

  /** Fields to update */
  $fields = array ( $db->quoteName ( 'streameroption' ) . '=\'' . $streamer_option . '\'', $db->quoteName ( 'filepath' ) . '=\'' . $fileoption . '\'',
    $db->quoteName ( 'videourl' ) . '=\'' . $videourl . '\'', $db->quoteName ( 'thumburl' ) . '=\'' . $img . '\'', $db->quoteName ( 'previewurl' ) . '=\'' . $previewurl . '\'', 
    $db->quoteName ( 'hdurl' ) . '=\'' . $hdurl . '\'', $db->quoteName ( 'tags' ) . '=\'' . $tags . '\''  );
  /** Conditions for which records should be updated */
  $conditions = array ( $db->quoteName ( 'id' ) . '=' . $idval  );  
  /** Update streameroption,streamerpath,etc */
  $query->clear ()->update ( $db->quoteName ( '#__hdflv_upload' ) )->set ( $fields )->where ( $conditions );
  $db->setQuery ( $query );
  $db->query ();
    
  $uploadProcessData = array ('subtitle1' => $yt_subtile_lang1, 'subtitle2' => $yt_subtile_lang2, 'id' => $idval, 'file' => '', 'thumb'=> '', 'preview' => '', 'srt1' => $ytSrtFile1, 'srt2' => $ytSrtFile2, 'hdvideo' => '', 'upload' =>  $arrFormData ['newupload'], 'fileoption' => $fileoption);
  UploadFileHelper::uploadVideoProcessing ( $uploadProcessData );
  
  /** Delete temp file */
  UploadFileHelper::checkFileToUnlink ( $ytSrtFile1 );
  UploadFileHelper::checkFileToUnlink ( $ytSrtFile2 );
 }
 
 /**
  * Function to get video details using curl method
  * 
  * @param unknown $videourl
  * @return multitype:
  */
 public static function fetchDetailsUsingCurl ( $videourl ) {
   $timeout = $header = '';
   $str1           = explode ( 'administrator', JURI::base () );
   $videoshareurl  = $str1 [0] . "index.php?option=com_contushdvideoshare&view=videourl";
   
   /** Is cURL exit or not */
   if (! function_exists ( 'curl_init' )) {
     echo "<script> alert('Sorry cURL is not installed!');window.history.go(-1); </script>\n";
     exitAction ();
   }
   $curl = curl_init ();
   curl_setopt ( $curl, CURLOPT_URL, $videoshareurl . '&url=' . $videourl . '&imageurl=' . $videourl );
   curl_setopt ( $curl, CURLOPT_TIMEOUT, $timeout );
   curl_setopt ( $curl, CURLOPT_USERAGENT, sprintf ( "Mozilla/%d.0", rand ( 4, 5 ) ) );
   curl_setopt ( $curl, CURLOPT_HEADER, ( int ) $header );
   curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
   curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
   $videoshareurl_location = curl_exec ( $curl );
   curl_close ( $curl );
   return explode ( '&', $videoshareurl_location );
 }
}
