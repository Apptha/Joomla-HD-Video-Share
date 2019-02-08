<?php
/**
 * Video Upload model file for front end users
 *
 * This file is to store user videos in to database
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
/** Import filesystem libraries. */
jimport ( 'joomla.filesystem.file' );
/** Define constants for category page */
define ('VIDEO_UPLOAD', 'video');
define ('VIDEOTYPE', 'videotype');
define ('VSORDERING', 'ordering');
define ('PUBLISHED', 'published');
define ('MEMBER_ID', 'member_id');
define ('STREAMERPATH', 'streamerpath');
define ('STREAMEROPTION', 'streameroption');
define ('VIDEOPATH', JURI::Base(). 'components/com_contushdvideoshare/videos/');
define ('FFMPEGVALUE', JRequest::getString ( 'ffmpeg' ));
define ('THUMBIMAGEFORMVALUE', JRequest::getString ( 'thumbimageformval' ));
define ('TITLE', 'title');
define ('VSSEOTITLE', 'seotitle');
define ('DOWNLOAD', 'download');
define ('ADMINAPPROVE', 'adminapprove');
/**
 * Videoupload model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class Modelcontushdvideosharevideoupload extends ContushdvideoshareModel {
  /**
   * Fucntion is used to perform task in edit mode
   * 
   * @param $flagVideo int
   */
  public function editUploadTask ($flagVideo){
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    /** Get video id param from request */
    $video = JRequest::getVar ( VIDEO_UPLOAD );
    $id = JRequest::getInt ( 'id' );
    /** Check video param is exist */
    if (isset ( $video ) && $video != '') {
      if ($flagVideo != 1) {
        /** Joomla router replaced to : from - in query string */
        $videoTitle = JRequest::getString ( VIDEO_UPLOAD );
        $videoid = str_replace ( ':', '-', $videoTitle );
      } else {
        $videoid = JRequest::getInt ( VIDEO_UPLOAD );
      }
    } elseif (isset ( $id ) && $id != '') {
      /** Check id param is exist */
      $videoid = JRequest::getInt ( 'id' );
    } else {
      $videoid ='';
    }
    /** Query to select video details for the given video */
    $query->clear ()->select ( array ( 'a.id', 'a.memberid', 'a.' . PUBLISHED, 'a.title', 'a.seotitle', 'a.islive', 'a.embedcode', 'a.featured', 'a.type', 'a.rate', 'a.ratecount', TIMESVIEWED,
        'a.videos', 'a.filepath', 'a.' . VIDEOURL, 'a.' . THUMBURL, 'a.' . PREVIEWURL,'a.' . HDURL, 'a.home', 'a.playlistid', 'a.duration', 'a.' . VSORDERING, 'a.' . STREAMERPATH, 'a.' . STREAMEROPTION,
        'a.postrollads', 'a.prerollads', 'a.midrollads', 'a.description','a.amazons3', 'a.targeturl', 'a.download', 'a.prerollid', 'a.postrollid', 'a.created_date', 'a.addedon', 'a.usergroupid', 'a.tags',
        'a.useraccess', 'b.vid', 'b.catid', 'c.id', 'c.' . MEMBER_ID, 'c.' . CATEGORY, 'c.seo_category', 'c.parent_id', 'c.lft', 'c.rgt', 'c.' . PUBLISHED ) )->from ( PLAYERTABLE . VIDEOTABLECONSTANT )->leftJoin ( '#__hdflv_video_category AS b ON a.id=b.vid' )->leftJoin ( CATEGORYTABLE . ' AS c ON c.id=b.catid' );
    if ($flagVideo != 1) {
      /** Get video details based on the video seo title */
      $query->where ( $db->quoteName ( 'a.seotitle' ) . ' = ' . $db->quote ( $videoid ) );
    } else {
      /** Get video details based on the video id */
      $query->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $videoid ) );
    }
    $db->setQuery ( $query );
    return $db->loadObjectList ();
  }
  /**
   * Function to display the category in the upload page
   *
   * @return array
   */
  public function getupload() {
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    /** Variable Declaration */
    $value = $updateform = $streamerpath = $streameroption = $streamname = $url = $flv = $hd = $hq = $ftype = $success = $editvideo1 = $userID = '';
    $s3status = 0;
    $flagVideo = 1;
    /** Set default thumb and preview image */
    $img = JURI::base () . 'components/com_contushdvideoshare/images/default_thumb.jpg';
    $previewurl = JURI::base () . 'components/com_contushdvideoshare/images/default_preview.jpg';
    /** Get user id from helper for upload view */
    $member_id = getUserID ();
    /** Get dispenable settings from db 
     * and get type param */
    $dispenable = getSiteSettings( );    
    /** Get task edit param from request */
    $task_edit  = JRequest::getString ( 'type' );    
    if ($task_edit == 'edit') {
      /** Call function to edit videos in site */
      $editvideo1 = $this->editUploadTask ($flagVideo);
    }
    /** Get videourl and other params from request */
    $videourl                 = JRequest::getString ( VIDEOURL );
    $normalvideoformval       = strrev ( JRequest::getString ( 'normalvideoformval' ) );
    $normalvideoforms3status  = JRequest::getString ( 'normalvideoforms3status' );
    $hdvideoforms3status      = JRequest::getString ( 'hdvideoforms3status' );
    $thumbimageforms3status   = JRequest::getString ( 'thumbimageforms3status' );
    $previewimageforms3status = JRequest::getString ( 'previewimageforms3status' );
    $seltype                  = JRequest::getVar ( 'seltype' );
    /** Get video file type from request */
    if ($ftype == '' && JRequest::getString ( 'video_filetype' )) {
      $ftype = JRequest::getString ( 'video_filetype' );
    }    
    if ($normalvideoforms3status == 1 || $hdvideoforms3status == 1 || $thumbimageforms3status == 1 || $previewimageforms3status == 1) {
      $s3status = 1;
    }
    $seltype_array = array(0,2,3,4);
    /** Check type and edit task */
    if ((in_array($seltype, $seltype_array)) && (JRequest::getVar ( VIDEOTYPE ) == 'edit')) {
       $normalvideoformval = '';
    }
    /** Get category details from category table */
    $query->clear ()->select ( array ( 'id', 'member_id', CATEGORY, 'seo_category', 'parent_id', VSORDERING, 'lft', 'rgt', PUBLISHED ) )
    ->from ( CATEGORYTABLE )->where ( $db->quoteName ( PUBLISHED ) . ' = ' . $db->quote ( '1' ) )->where ( '(' . $db->quoteName ( MEMBER_ID ) . ' = ' . $db->quote ( '0' ) . ' OR ' . $db->quoteName ( MEMBER_ID ) . ' = ' . $db->quote ( $member_id ) . ')' )->order ( $db->escape ( CATEGORY . ' ' . 'ASC' ) );
    $db->setQuery ( $query );
    $category1 = $db->loadObjectList ();
    /** If details not found then display error messge */
    if ($category1 === null) {
      JError::raiseError ( 500, 'Category was empty' );
    }
    /** Check joomla version and get upload button value */
    if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
      $input = JFactory::getApplication ()->input;
      $uploadbutton = $input->get ( 'uploadbtn' );
    } else {
      $uploadbutton = JRequest::getCmd ( 'uploadbtn' );
    }

    $userID = getUserID();
    if ($uploadbutton) {
      if ($userID) {
        /** Setting the loginid into session */
       $memberid = $userID;
      }
      
      if ($videourl == '1' || $normalvideoformval) {
        /** Checking for normal file type of videos */
        if (strlen ( $normalvideoformval ) > 0 && !strlen ( FFMPEGVALUE )) {
          /** Getting the normal video name */
        	
          if( !empty($normalvideoformval) && !strstr($normalvideoformval, COMPONENT) ) { 
            $url =  $normalvideoformval;
            $s3status = 1;
          } else {
            $normalflv = substr ( $normalvideoformval, 16, strlen ( $normalvideoformval ) );
            $flv = substr ( $normalflv, strrpos ( $normalflv, '/' ) + 1, strlen ( $normalflv ) );
            $url = $flv;
            $s3status = 0;
          }

          $ftype = 'File';
          
          /** Getting the hd video name */
          $hdvideoformval = strrev (JRequest::getString ( 'hdvideoformval' ) );
          if( !empty($hdvideoformval) &&  !strstr($hdvideoformval, COMPONENT) ) {
            $hd = $hdvideoformval;
            $s3status = 1;
          } else {
            $hdvideo = substr ( $hdvideoformval , 16, strlen ( strrev ( JRequest::getString ( 'hdvideoformval' ) ) ) );
            $hd = substr ( $hdvideo, strrpos ( $hdvideo, '/' ) + 1, strlen ( $hdvideo ) );
            $s3status = 0;
          }
          
          /** Getting the thumb image name */
          $thumbimageformval = strrev (THUMBIMAGEFORMVALUE);
          if( !empty($thumbimageformval) && !strstr($thumbimageformval, COMPONENT)) {
            $img = $thumbimageformval;
            $s3status = 1;
          } else {
            $thumimg = substr ( $thumbimageformval, 16, strlen ( strrev ( THUMBIMAGEFORMVALUE ) ) );
            $img = substr ( $thumimg, strrpos ( $thumimg, '/' ) + 1, strlen ( $thumimg ) );
            $s3status = 0;
          }
          /** Getting the preview image name */
          $previewimageformval = strrev (JRequest::getString ( 'previewimageformval' ));
          if(!empty($previewimageformval) && !strstr($previewimageformval, COMPONENT)) {
            $previewurl = $previewimageformval;
            $s3status = 1;
          } else {
            $previewimg = substr ( $previewimageformval, 16, strlen ( strrev ( JRequest::getString ( 'previewimageformval' ) ) ) );
            $previewurl = substr ( $previewimg, strrpos ( $previewimg, '/' ) + 1, strlen ( $previewimg ) );
            $s3status = 0;
          }
        } elseif (strlen ( FFMPEGVALUE ) > 0) {
        	$s3status = 0;
          $player_values = getPlayerIconSettings('');
          /** Get ffmpeg values from player settings */
          if ($player_values ['ffmpegpath']) {
            $strFfmpegPath = $player_values ['ffmpegpath'];
          }
          $VPATH1 = JPATH_COMPONENT . '/videos/';
          $vpath = $VPATH1;
          $ffmpegVal = strrev ( FFMPEGVALUE );
          if(!empty($ffmpegVal) && !strstr($ffmpegVal, COMPONENT)) {
          	$filename = explode ( '.', $ffmpegVal );
          	$destFile = $vpath . $ffmpegVal;
          	/** Set target file name */
          	$target_path2 = $VPATH1 . $filename [0] . '.flv';
          	$s3status = 1;
          } else {          
	          /** Getting the normal video name */
	          $path = substr ( $ffmpegVal, 16, strlen ( $ffmpegVal ) );
	          $fileName = substr ( $path, strrpos ( $path, '/' ) + 1, strlen ( $path ) );
	          $filename = explode ( '.', $fileName );          
	          $destFile = $vpath . $fileName; 
	          /** Set target file name */
	          $target_path2 = $VPATH1 . $filename [0] . '.flv';
	          $s3status = 0;
          }
          /** Execute commands to convert video file and generate thum image */
          exec ( $strFfmpegPath . ' ' . '-i' . ' ' . $destFile . ' ' . '-sameq' . ' ' . $target_path2 );
          exec ( $strFfmpegPath . " -i " . $target_path2 . ' ' . "-an -ss 00:00:03 -an -r 1 -s 120x68 -f image2" . ' ' . $vpath . $filename [0] . "_thumb.jpg" );
          exec ( $strFfmpegPath . " -i " . $target_path2 . ' ' . "-an -ss 00:00:03 -an -r 1 -vframes 1 -y" . ' ' . $vpath . $filename [0] . '_preview' . ".jpg" );
          $url = $filename [0] . '.flv';
          
          /** Getting Hd path */
          $hd = '';
          /** Getting Hq path */
          $hq = '';          
          /** Getting thumb path */
          $img = $filename [0] . '_thumb.jpg';
          $previewurl = $filename [0] . '_preview.jpg';
          $ftype = 'FFmpeg';
        }
      } else {
        /** Checking condition for urls */
        $flv = JRequest::getString ( 'Youtubeurl' );
        $embed_code = JRequest::getVar ( 'embed_code', '', 'post', TYPE_STRING, JREQUEST_ALLOWRAW );
        $url = $flv;
        
        if (! empty ( $embed_code ) && $ftype == 'Embed') {
          $flv = '';
        }        
        if (! empty ( $flv )) {
          $ftype = 'Url';
        }
        
        $streamerpath = $streamname = $updatestreamer = '';
        $streamname = JRequest::getString ( 'streamname' );
        $isLive = JRequest::getString ( 'islive-value' );
        
        if (! empty ( $streamname ) && $seltype == 3) {
          $streameroption = 'rtmp';
          $streamerpath = $streamname;
          $updatestreamer .= $db->quoteName ( STREAMERPATH ) . ' = ' . $db->quote ( $streamname );
          $updatestreamer .= ', ' . $db->quoteName ( STREAMEROPTION ) . ' = ' . $db->quote ( $streameroption );
          $updatestreamer .= ', ' . $db->quoteName ( 'islive' ) . ' = ' . $db->quote ( $isLive );
        }
        
        /** Getting Hd path */
        $hd = JRequest::getString ( HDURL );        
        /** Getting Hq path */
        $hq = JRequest::getString ( 'hq' );

        /** Getting Image path */
        $img = JRequest::getString ( THUMBURL );
        $uploadFile = JRequest::getVar ( THUMBURL, null, 'files', 'array' );

        /** Check file name is not empty */
        if ($uploadFile ['name'] != '' ) {
          if($ftype == 'Embed'){
            /** Set image name and preview name for embed method */
            $img = $uploadFile ['name'];
            $previewurl = $uploadFile ['name'];
          }else if($ftype == 'Url'){
            /** Set image name and preview name for upload method */
            $img = VIDEOPATH .$uploadFile ['name'];
            $previewurl = VIDEOPATH .$uploadFile ['name'];
          }
          /** check upload file type is jpg /gif or png */
          if ($uploadFile ['type'] == 'image/gif' || ($uploadFile ['type'] == 'image/jpeg') || ($uploadFile ['type'] == 'image/png')) {
            /** Move uploaded image file to destination */
            move_uploaded_file ( $_FILES [THUMBURL] ['tmp_name'], 'components/com_contushdvideoshare/videos/' . $_FILES [THUMBURL] ['name'] );
          }
        } else if ($img == '') { 
          $img = strrev (THUMBIMAGEFORMVALUE);
        } 
        
        if ($img == '') {  
          /** Check videourl is youtube */
          if (strpos ( $url, 'youtube' ) > 0 || strpos ( $url, 'youtu.be' ) > 0) {
            /** Get youtube video id */
            $imgval = getYoutubeVideoID ($url);
            /** Set thumb and preview url for youtube videos */
            $previewurl = 'http://img.youtube.com/vi/' . $imgval . '/maxresdefault.jpg';
            $img = 'http://img.youtube.com/vi/' . $imgval . '/mqdefault.jpg';
          } elseif (strpos ( $url, 'vimeo' ) > 0) {
            /** Get vimeo details */
            $vimeoData = getVimeoDetails($url);
            if(!empty($vimeoData) && isset($vimeoData)) {
              /** Get thumb and preview for vimeo videos */
              $img = $vimeoData[0];
              $tags = $vimeoData[1];
            }
          } elseif (strpos ( $url, 'dailymotion' ) > 0) {
            /** Check video url is dailymotion 
             * Fetch dailymotion video id */
            $split_id = getDailymotionVideoID ( $url );
            $img = $previewurl = 'http://www.dailymotion.com/thumbnail/video/' . $split_id [0];
          } elseif (strpos ( $url, 'viddler' ) > 0) {
            /** Check video url is viddler */
            $imgstr = explode ( '/', $url );
            $img = $previewurl = 'http://cdn-thumbs.viddler.com/thumbnail_2_' . $imgstr [4] . '_v1.jpg';
          } elseif($seltype == 2 || $seltype == 3){
          	$img = $previewurl = strrev(THUMBIMAGEFORMVALUE);
          	if($img == '')
          	  $img = JURI::base () . 'components/com_contushdvideoshare/images/default_thumb.jpg';
          	if($previewurl == '')
          	  $previewurl = JURI::base () . 'components/com_contushdvideoshare/images/default_preview.jpg';
          } else {
            /** Set default thumb image */
            $img = JURI::base () . 'components/com_contushdvideoshare/images/default_thumb.jpg';
          }
        }
      }
      
      if ($seltype == 0) {
        $ftype = YOUTUBE;
      }

      /** Get title and seo title from request */
      $gettitle = JRequest::getString ( TITLE );
      $title = $db->quote ( $gettitle );
      $seoTitle = JRequest::getString ( VSSEOTITLE );
      /** Get video description from request */
      $getdescription = JRequest::getString (DESCRIPTION );
      $description = $getdescription;
      /** Get video tags from request */
      $gettagname = JRequest::getString ( 'tagname' );
      $videoTags = JRequest::getString ( 'tags1' );      
      if (! empty ( $videoTags )) {
        $tags = $videoTags;
      }
      /** Get video type from request */
      $type = JRequest::getString ( 'type' );
      /** Get video ordering from request */
      $ordering = JRequest::getString ( VSORDERING );   
      /** Set user access based on video type */   
      if ($type == 1) {
        $useraccess = 2;
      } else {
        $useraccess = 0;
      }
      /** Get video download from request */
      $download = JRequest::getVar ( DOWNLOAD );
      /** Get tag name and split tags name */
      $tagname1 = $gettagname;
      $split_tagname = explode ( ',', $tagname1 );
      $tagname = implode ( ',', $split_tagname );      
      /** Get id from category table based on the tags */
      $query->clear ()->select ( 'id' )->from ( CATEGORYTABLE )->where ( $db->quoteName ( 'category' ) . ' IN (' . $db->quote ( $tagname ) . ')' );
      $db->setQuery ( $query );
      $result = $db->LoadObjectList ();  
      /** Looping through category details */    
      foreach ( $result as $category ) {
        $cid = $category->id;
      }
      
      /** Get created date for videos */
      $cdate = date ( 'Y-m-d h:m:s' );
      $value = $updateform = '';      
      $videotype = JRequest::getString ( VIDEOTYPE );
      
      /** Code for seo option */
      if (trim ( $seoTitle ) == '') {
        $seoTitle = $title;
      }      
      $seoTitle = JApplication::stringURLSafe ( $seoTitle );      
      if (trim ( str_replace ( '-', '', $seoTitle ) ) == '') {
        $seoTitle = JFactory::getDate ()->format ( 'Y-m-d-H-i-s' );
      }
      
      /** Get admin videos table */
      $table = $this->getTable ( 'adminvideos' );      
      while ( $table->load ( array ( 'seotitle' => $seoTitle ) ) && $videotype != 'edit' ) {
        /** Load admin videos table and get seo title */
        $seoTitle = JString::increment ( $seoTitle, 'dash' );
      }
      
      /** Check videotype is edit */
      if ($videotype != 'edit') {
        /** Count ordering from player table */
        $query->clear ()->select ( 'count(' . VSORDERING .')' )->from ( PLAYERTABLE );
        $db->setQuery ( $query );
        $ordering = $db->loadResult ();
      }
      
      $setEmpty = ' = ""';
      if ($videotype == 'edit') {
        $edit_video_id = JRequest::getInt ( 'videoid' );
        /** Check preview url value and set in update condition */
        if ($previewurl != '') {
          $updateform .= $db->quoteName ( PREVIEWURL ) . ' = ' . $db->quote ( $previewurl );
        } else {
          $updateform .= $db->quoteName ( PREVIEWURL ) . $setEmpty;
        }
        /** Check hd url value and set in update condition */
        if ($hd != '') {
          $updateform .= ', ' . $db->quoteName ( HDURL ) . ' = ' . $db->quote ( $hd );
        } else {
          $updateform .= ', ' . $db->quoteName ( HDURL ) . $setEmpty;
        }        
        /** Check video url value and set in update condition */
        if ($url != '') {
          $updateform .= ', ' . $db->quoteName ( VIDEOURL ) . ' = ' . $db->quote ( $url );
        } else {
          $updateform .= ', ' . $db->quoteName ( VIDEOURL ) . $setEmpty;
        }        
        /** Check thumb url value and set in update condition */
        if ($img != '') {
          $updateform .= ', ' . $db->quoteName ( THUMBURL ) . ' = ' . $db->quote ( $img );
        } else {
          $updateform .= ', ' . $db->quoteName ( THUMBURL ) . $setEmpty;
        }
        
        switch($seltype) {
          case 0:
          case 1:
          case 2:
          case 4:
          case 6:
            $updatestreamer .= $db->quoteName ( STREAMERPATH ) . ' = ""';
            $updatestreamer .= ', ' . $db->quoteName ( STREAMEROPTION ) . ' = ""';
            break;
          default:
            break;
        }
        
        /** Set array of fields to update player table */
        $fields = array ( $db->quoteName ( 'filepath' ) . ' = ' . $db->quote ( $ftype ), $db->quoteName ( 'amazons3' ) . ' = ' . $db->quote ( $s3status ),
            $db->quoteName ( 'tags' ) . ' = ' . $db->quote ( $tags ), $db->quoteName ( 'title' ) . ' = ' . $title, $db->quoteName ( 'seotitle' ) . ' = ' . $db->quote ( $seoTitle ),
            $db->quoteName ( 'embedcode' ) . ' = ' . $db->quote ( $embed_code ), $db->quoteName ( VSORDERING ) . ' = ' . $db->quote ( $ordering ),
            $db->quoteName ( 'useraccess' ) . ' = ' . $db->quote ( $useraccess ), $db->quoteName ( 'type' ) . ' = ' . $db->quote ( $type ),
            $db->quoteName ( DOWNLOAD) . ' = ' . $db->quote ( $download ), $db->quoteName ( 'description' ) . ' = ' . $db->quote ( $description ),
            $updateform, $updatestreamer );
        $query->clear ()->update ( $db->quoteName ( PLAYERTABLE ) )->set ( $fields )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $edit_video_id ) );        
        $db->setQuery ( $query ); 
        $db->query ();
        
        $query->clear ()->delete ( $db->quoteName ( '#__hdflv_video_category' ) )->where ( $db->quoteName ( 'vid' ) . ' = ' . $db->quote ( $edit_video_id ) );
        $db->setQuery ( $query );
        $db->query ();
        $value = $edit_video_id;
      } else {
        /** Get usergroup from helper */
        $ugp = getUserGroup ();        
        $usergroup = $ugp->group_id;
        
        /** Get admin approve option from settings */ 
        if (isset ( $dispenable [ADMINAPPROVE] ) && $dispenable [ADMINAPPROVE] == 0) {
          $adminapprove = 0;
          $success = JText::_ ( 'HDVS_VIDEO_UPLOADED_APPROVE_MESSAGE' );
        } else {
          $adminapprove = 1;
          $success = JText::_ ( 'HDVS_VIDEO_UPLOADED_SUCCESS' );
        }
        /** Insert new video to player table */
        $columns = array ( 'islive', STREAMERPATH, 'amazons3', STREAMEROPTION, 'title', 'seotitle', 'filepath', VIDEOURL, THUMBURL, PREVIEWURL, PUBLISHED, 'type', 'memberid',
            'description', 'created_date', 'addedon','usergroupid', 'playlistid', HDURL, 'tags', 'download', 'useraccess', VSORDERING, 'embedcode' );
        $values = array ( $db->quote ( $isLive ), $db->quote ( $streamerpath ), $db->quote ( $s3status ),
            $db->quote ( $streameroption ), $title, $db->quote ( $seoTitle ), $db->quote ( $ftype ),
            $db->quote ( $url ), $db->quote ( $img ), $db->quote ( $previewurl ), $db->quote ( $adminapprove ),
            $db->quote ( $type ), $db->quote ( $memberid ), $db->quote ( $description ), $db->quote ( $cdate ),
            $db->quote ( $cdate ), $db->quote ( $usergroup ), $db->quote ( $cid ), $db->quote ( $hd ),
            $db->quote ( $tags ), $db->quote ( $download ), $db->quote ( $useraccess ), $db->quote ( $ordering ),
            $db->quote ( $embed_code )  );         
        $query->clear ()->insert ( $db->quoteName ( PLAYERTABLE ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
        $db->setQuery ( $query );
        $db->query ();
        $db_insert_id = $db->insertid ();
        $value = $db_insert_id;
        
        /** Call function to send mail to admin to intimate new videos added */
        $this->videoMailForAdmin ($value, $title);
      }
      
      $cid = $category->id;
      $category_columns = array ( 'vid',  'catid'  );
      /** Insert video and category id into video category table */
      $category_values = array (  $db->quote ( $value ), $db->quote ( $cid )  );
      $query->clear ()->insert ( $db->quoteName ( '#__hdflv_video_category' ) )->columns ( $db->quoteName ( $category_columns ) )->values ( implode ( ',', $category_values ) );
      $db->setQuery ( $query );
      $db->query ();
      
      if (count ( $result ) > 0 && $videotype == 'edit') {
        $query->clear ()->update ( $db->quoteName ( PLAYERTABLE ) )->set ( $db->quoteName ( 'playlistid' ) . ' = ' . $db->quote ( $cid ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $edit_video_id ) );
        $db->setQuery ( $query );
        $db->query ();
        $success = JText::_ ( 'HDVS_UPDATED_SUCCESS' );
      }
      $baseurl = JURI::base ();
      $url = JRoute::_ ( $baseurl . 'index.php?option=com_contushdvideoshare&view=myvideos' );
      JFactory::getApplication ()->redirect ( $url, $success, MESSAGE );
    }    
    return array (  $category1, $success,  $editvideo1  );
  }
  
  public function videoMailForAdmin ( $value, $title ) {
    /** Alert admin regarding new video upload */
    $mailer = JFactory::getMailer ();
    $config = JFactory::getConfig ();
    /** Call function to get user details from helper */
    $user_details = getUserDetails ();
    $sender = $config->get ( 'mailfrom' );
    $mailer->setSender ( $user_details->email );
    $featureVideoVal = 'id=' . $value;
    $mailer->addRecipient ( $sender );
    
    $dispenable = getSiteSettings( );
    if (isset ( $dispenable [ADMINAPPROVE] ) && $dispenable [ADMINAPPROVE] == 0) {
    	$subject = 'New video uploaded for your approval on your site.';
    	$messageContent = 'is waiting for his video "'. $title .'" to publish on your site!!';
    } else {
    	$subject = 'New video added by ' . $user_details->username . ' on your site.';
    	$messageContent = 'have uploaded a new video "'. $title .'" on your site!!';
    }    
    $baseurl = JURI::base ();
    $video_url = $baseurl . 'index.php?option=com_contushdvideoshare&view=player&' . $featureVideoVal . '&adminview=true';
    $get_html_message = file_get_contents ( $baseurl . '/components/com_contushdvideoshare/emailtemplate/membervideoupload.html' );
    $update_baseurl = str_replace ( '{baseurl}', $baseurl, $get_html_message );
    $update_username = str_replace ( '{username}', $user_details->username, $update_baseurl );
    $update_content = str_replace ( '{messageContent}', $messageContent, $update_username );
    $message = str_replace ( '{video_url}', $video_url, $update_content );
    $mailer->isHTML ( true );
    $mailer->setSubject ( $subject );
    $mailer->Encoding = 'base64';
    $mailer->setBody ( $message );
    $mailer->Send ();
  }
}