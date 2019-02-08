<?php
/**
 * Upload URL helper
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
 * Admin UploadUrlHelper class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class UploadUrlHelper {
 /**
  * Function to upload url type videos
  *
  * @param array $arrFormData post content
  * @param int $idval video id
  *         
  * @return uploadUrl
  */
 public function uploadUrl($arrFormData, $idval) {
   /** Declare database*/
  $db = JFactory::getDBO ();
  /** set database query */
  $query = $db->getQuery ( true );
  /** variable declaration for videoURL and hdURL **/
  $videourl = $hdurl = "";
  /** Define baseURL for admin files */
  $baseUrl = str_replace ( "administrator/", "", JURI::base () );
  /** Define default thumbimage URL */
  $thumburl = $baseUrl . 'components/com_contushdvideoshare/videos/default_thumb.jpg';
  /** Define default previewimage URL */
  $previewurl = $baseUrl . 'components/com_contushdvideoshare/videos/default_preview.jpg';
  /** Assign streameroption */
  $streamer_option = $arrFormData ['streameroption-value'];
  /** varaible declaration for fileoption **/
  $fileoption = $arrFormData ['fileoption'];
  /** Assign video url */
  if ($arrFormData ['videourl-value'] != "") {
  /** Store reversed videoURL */
   $videourl = strrev ( $arrFormData ['videourl-value'] );
  }
  /** Assign hd url */
  if ($arrFormData ['hdurl-value'] != "") {
  /** Variable declaration for hdURL by reversing the URL */
   $hdurl = strrev ( $arrFormData ['hdurl-value'] );
  }
  /** Assign thumb image url */
  if ($arrFormData ['thumburl-value'] != "") {
    /** variable declaration for thumbURL */
   $thumburl = strrev ( $arrFormData ['thumburl-value'] );
  }
  /** Assign preview image url */
  if ($arrFormData ['previewurl-value'] != "") {
    /** variable declaration for previewURL */
   $previewurl = strrev ( $arrFormData ['previewurl-value'] );
  }
  /** Assign streamer path */
  /** set streamer path */
  $streamer_path = ($arrFormData ['streamerpath-value'] != '') ? $arrFormData ['streamerpath-value'] : '';
  /** Set video islive data */
  $isLive = $arrFormData ['islive-value'];
  /** Fields to update */
  $URLFields = array (
    $db->quoteName ( 'streameroption' ) . '=\'' . $streamer_option . '\'',
    $db->quoteName ( 'streamerpath' ) . '=\'' . $streamer_path . '\'',
    $db->quoteName ( 'filepath' ) . '=\'' . $fileoption . '\'',
    $db->quoteName ( 'videourl' ) . '=\'' . $videourl . '\'',
    $db->quoteName ( 'thumburl' ) . '=\'' . $thumburl . '\'',
    $db->quoteName ( 'previewurl' ) . '=\'' . $previewurl . '\'',
    $db->quoteName ( 'hdurl' ) . '=\'' . $hdurl . '\'',
    $db->quoteName ( 'islive' ) . '=\'' . $isLive . '\'' 
  );
  /** Conditions for which records should be updated */
  $URLConditions = array ( $db->quoteName ( 'id' ) . '=' . $idval  );
  /** Update streameroption,streamerpath,etc */
  $query->clear ()->update ( $db->quoteName ( '#__hdflv_upload' ) )->set ( $URLFields )->where ( $URLConditions );
  $db->setQuery ( $query );
  $db->query ();
  /** Get and set subtitle1 of the video */
  $URLSrtFile1 = $arrFormData ['subtitle_video_srt1form-value'];
  /** Explode srt1 files */
  $URLArrSrtFile1 = explode ( 'uploads/', $URLSrtFile1 );
  if (isset ( $URLArrSrtFile1 [1] )) {
   $URLSrtFile1 = $URLArrSrtFile1 [1];
  }
  /** Get and set subtitle2 of the video */
  $URLSrtFile2 = $arrFormData ['subtitle_video_srt2form-value'];
  /** Explode srt2 files */
  $URLArrSrtFile2 = explode ( 'uploads/', $URLSrtFile2 );
  if (isset ( $URLArrSrtFile2 [1] )) {
   $URLSrtFile2 = $URLArrSrtFile2 [1];
  }
  /** Assign subtitle for language1 */
  $url_subtile_lang1 = $arrFormData ['subtile_lang1'];
  /** Assign subtitle for language2 */
  $url_subtile_lang2 = $arrFormData ['subtile_lang2'];
  /** Get upload helper file to upload thumb */
  require_once FVPATH . DS . 'helpers' . DS . 'uploadfile.php';
  /** Declare array for process data */
  $uploadProcessData = array ('subtitle1' => $url_subtile_lang1, 'subtitle2' => $url_subtile_lang2, 'id' => $idval, 'file' => '', 'thumb'=> '', 'preview' => '', 'srt1' => $URLSrtFile1, 'srt2' => $URLSrtFile2, 'hdvideo' => '', 'upload' =>  $arrFormData ['newupload'], 'fileoption' => $fileoption);
  /** call to method for video processing */
  UploadFileHelper::uploadVideoProcessing ( $uploadProcessData );
  /** Delete temp file */
  if ($URLSrtFile1 != '') {
    /** call to method to unlink temporary srt1 files */
   UploadFileHelper::unlinkUploadedTmpFiles ( $URLSrtFile1 );
  }
  if ($URLSrtFile2 != '') {
    /** call to method to unlink temporary srt2 files */
   UploadFileHelper::unlinkUploadedTmpFiles ( $URLSrtFile2 );
  }
 }
}
