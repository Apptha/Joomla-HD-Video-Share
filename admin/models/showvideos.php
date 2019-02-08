<?php
/**
 * Show videos model file
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

/** Import Joomla model library */
jimport ( 'joomla.application.component.model' );

/** Import Joomla pagination */
jimport ( 'joomla.html.pagination' );
/** set redirect user link */
$redirectUserLink = 'index.php?layout=adminvideos&option=' . JRequest::getVar ( 'option' ) . '&user=' . JRequest::getVar ( 'user' );
/** define constants */
define ('CATORDERING', 'ordering');
define ('REDIRECTUSERLINK', $redirectUserLink);
define ('VIDEOMEMBER', 'a.memberid');
define ('VSSEOTITLE', 'seotitle');
define ('ADMINVIDEOS', 'adminvideos');

/**
 * Admin show videos model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModelshowvideos extends ContushdvideoshareModel {
  /**
   * Constructor function to declare global value
   */
  public function __construct() {
    global $option, $mainframe, $db;
    parent::__construct ();
    
    /** Get global configuration */
    $mainframe = JFactory::getApplication ();
    $option = JRequest::getVar ( 'option' );
    $db = JFactory::getDBO ();
  }  
  
  /**
   * Function to get videos details for grid view
   *
   * @return showvideosmodel
   */
  public function showvideosmodel() {
    /** Declare global variable */
    global $option, $mainframe, $db;
    /** set query */
    $query = $db->getQuery ( true );
    /** To store and retrieve filter variables that are stored with the session */
    $filter_order = $mainframe->getUserStateFromRequest ( $option . 'filter_order_adminvideos', 'filter_order', ORDERING, 'cmd' );
    $filter_order_Dir = $mainframe->getUserStateFromRequest ( $option . 'filter_order_Dir_adminvideos', 'filter_order_Dir', 'asc', 'word' );
    $search = $mainframe->getUserStateFromRequest ( $option . SEARCH, SEARCH, '', TYPE_STRING );
    $search1 = $search;
    $state_filter = $mainframe->getUserStateFromRequest ( $option . 'filter_state', 'filter_state', '', 'int' );
    $featured_filter = $mainframe->getUserStateFromRequest ( $option . 'filter_featured', 'filter_featured', '', TYPE_STRING );
    $category_filter = $mainframe->getUserStateFromRequest ( $option . 'filter_category', 'filter_category', '', '' );
    
    /** Default List Limit */
    $limit = $mainframe->getUserStateFromRequest ( $option . '.limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
    $limitstart = $mainframe->getUserStateFromRequest ( $option . '.limitstart', LIMITSTART, 0, 'int' );
    
    /** Set user = admin for admin videos */
    $strAdmin = (JRequest::getVar ( 'user', '', 'get' )) ? JRequest::getVar ( 'user', '', 'get' ) : '';
    /** Query to get the category details  */
    $query->clear ()->select ( array ( 'id', 'member_id', 'category', 'seo_category', 'parent_id', CATORDERING, 'published' ) )->from ( '#__hdflv_category' )->where ( 'published = 1' );
    $db->setQuery ( $query );
    /** store array of playlistname */
    $rs_showplaylistname = $db->loadObjectList ();
    
    /** Select videos details  */
    if ($filter_order) {
      /** For select videos details */
      $query->clear ()->select ( array ( 'DISTINCT(d.videoid) AS cvid', 'a.id', 'a.memberid', 'a.published', 'a.title', 'a.seotitle', 'a.featured', 'a.type', 'a.rate', 'a.ratecount', 'a.times_viewed', 'a.videos', 'a.filepath', 'a.videourl', 'a.thumburl', 'a.previewurl', 'a.hdurl', 'a.home',
          'a.playlistid', 'a.duration', 'a.ordering', 'a.streamerpath', 'a.streameroption', 'a.postrollads', 'a.prerollads', 'a.midrollads', 'a.imaads', 'a.embedcode', 'a.description', 'a.targeturl', 'a.download', 'a.prerollid', 'a.postrollid', 'a.created_date',
          'a.addedon', 'a.usergroupid', 'a.tags', 'a.useraccess', 'b.category', 'c.username', 'a.amazons3'  ) )->from ( '#__hdflv_upload a' )->innerJoin ( '#__users c ON c.id = a.memberid' )->leftJoin ( '#__hdflv_category b ON a.playlistid=b.id' )->leftJoin ( '#__hdflv_comments d ON d.videoid=a.id' );
      /** Call function to get where condition */
      $query->where ( $this->getWhereQuery($strAdmin) );
    }
    
    /** Assign filter variables */
    $lists ['order_Dir'] = $filter_order_Dir;
    /** Assign filter variable for ordering */
    $lists ['order'] = $filter_order;
    /** Call component helper for videos page */
    $search = phpSlashes ( $search );
    
    /** Filtering based on search keyword */
    if ($search) {
      /** search query */
      $dbescape_search = $db->quote ( '%' . $db->escape ( $search, true ) . '%' );
      /** where condition based on the search value entered */
      $query->where ( 'a.title LIKE ' . $dbescape_search );
      /** strore search data in a array */
      $lists [SEARCH] = $search1;
    }
    
    /** Filtering based on status */
    if ( $state_filter ) {
      /** Call function to get state filter value for videos */
      $state_filterval = $this->getStatefilterValue ( $state_filter );
      /** filter video that are published state */
      $query->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( $state_filterval ) );
      /** store the filter varaible in a array */
      $lists ['state_filter'] = $state_filter;
    } else {
      /** select video's that are unpublished */
      $query->where ( $db->quoteName ( VIDEOPUBLISH ) . ' != ' . $db->quote ( '-2' ) );
    }
    
    /** Filtering based on featured status */
    if ($featured_filter) {
      /** check filter val */
      $featured_filterval = ($featured_filter == '1') ? '1' : '0';
      /** query to filter videos that are featured */
      $query->where ( $db->quoteName ( 'a.featured' ) . ' = ' . $db->quote ( $featured_filterval ) );
      /** store the featured videos in an array */
      $lists ['featured_filter'] = $featured_filter;
    }    
    if ($category_filter) {
      /** query to filter videos based on the selected category */
      $query->where ( $db->quoteName ( 'a.playlistid' ) . ' = ' . $db->quote ( $category_filter ) );
      /** store the category videos */
      $lists ['category_filter'] = $category_filter;
    }
    /** order the video based on the order direction ASC/DESC */
    $query->order ( $db->escape ( $filter_order . ' ' . $filter_order_Dir ) );
    /** Execute */
    $db->setQuery ( $query );
    /** store the resulted video in an array */
    $getarrVideoList = $db->loadObjectList ();
    /** get the count of the videos */
    $strTotalVideos = count ( $getarrVideoList );
    
    /** Set pagination */
    $pageNav = new JPagination ( $strTotalVideos, $limitstart, $limit );
    /** set pagination limit for the videos. */
    $db->setQuery ( $query, $pageNav->limitstart, $pageNav->limit );
    /** store the resulted videos in an array */
    $arrVideoList = $db->loadObjectList ();    
    /** Display the last database error message in a standard format */
    if ($db->getErrorNum ()) {
      /** raise error if any there is an error */
      JError::raiseWarning ( $db->getErrorNum (), $db->stderr () );
    }
    /** get sitesettings for the videos */
    $dispenable = getSiteSettings ();
    /** return the data as array */
    return array ( 'pageNav' => $pageNav, 'limit' => $limit, 'limitstart' => $limitstart, 'lists' => $lists, 'rs_showupload' => $arrVideoList, 'rs_showplaylistname' => $rs_showplaylistname, 'dispenable' => $dispenable 
    );
  }
  
  /**
   * Function to get state filter value for videos
   * 
   * @return number
   */
  public function getStatefilterValue ( $state_filter ) {
    /** Check filter option and set value based on that */
    switch ($state_filter ) {
      case 1:
        $state_filterval = 1;
        break;
      case 2:
        $state_filterval = 0;
        break;
      default:
        $state_filterval = - 2;
        break;
    }
    /** Return state filter value */
    return $state_filterval;
  }
  
  public function getWhereQuery ($strAdmin ) {
    /** set global variable for the database */
    global $db;    

    /** Get logged user and user group */
    $userid = getUserID();
    /** call to method to get the user group */
    $arrUserGroup = getUserGroup ();
    
    /** For select user group id */
    if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
      /**
       * User group
       * 6 - Manager
       * 7 - Administrator
       * 8 - Super Users
       */
      /** For videos added by admin */
      if ($strAdmin == ADMIN) {
        if ($arrUserGroup->group_id == '8') {
          /** query to execute if the user belongs to any of the below user group */
          $where = 'a.usergroupid IN (6,7,8)' ;
        } else {
          /** query to check the group id for the user */
          $where = $db->quoteName ( 'a.usergroupid' ) . ' = ' . $db->quote ( $arrUserGroup->group_id ) . ' AND ' . $db->quoteName ( VIDEOMEMBER ) . '=' . $db->quote ( $userid ) ;
        }
        /** For videos added by member  */
      } else {
        /** query to execute when user does not belong to the user groups mentioned below */
        $where = 'a.usergroupid NOT IN (6,7,8) AND ' . $db->quoteName ( VIDEOMEMBER ) . '!=' . $db->quote ( $userid ) ;
      }
    } else {
      /** For videos added by admin */
      if ($strAdmin == ADMIN) {
        switch($arrUserGroup->gid) {
          case 25:
            /** query to execute for user belonging to group id 25 */
            $where = $db->quoteName ( 'c.gid' ) . ' = ' . $db->quote ( '25' );
            break;
          case 24:
            /** query to execute for user belonging to group id 24 */
            $where = $db->quoteName ( 'c.gid' ) . ' = ' . $db->quote ( '24' ) ;
            break;
          default:
            break;
        }
      } else {
        /** For videos added by member */
        $where = 'c.gid NOT IN (24,25)' ;
      }
    }
    /** return the respective query */
    return $where;
  }
  
  /**
   * Function to publish and unpublish videos
   *
   * @param array $arrVideoId
   *          video detail array
   *          
   * @return showadsmodel
   */
  public function changevideostatus($arrVideoId) {
    /** Global variable declaration */
    global $mainframe;    
    if ($arrVideoId ['task'] == "publish") {
      /** Message to display if the video published succesfully */
      $msg = 'Published successfully';
      
      /** Define joomla mailer */
      /** call to mail function */
      $mailer = JFactory::getMailer ();
      /** call to get method to get the config data */
      $config = JFactory::getConfig ();
      /** fetch admin main id */
      $sender = $config->get ( 'mailfrom' );
      /** fetch site name */
      $site_name = $config->get ( 'sitename' );
      /** call to method to set sender */
      $mailer->setSender ( $sender );
      /** Query variable */
      $db = JFactory::getDBO ();
      /** set query */
      $query = $db->getQuery ( true );
      /** get site settings */
      $dispenable = getSiteSettings ( );
      
      foreach ( $arrVideoId ['cid'] as $videoid ) {
        /** Query is to display recent videos in home page */
        $query->clear ()->select ( array ( 'd.email', 'b.seo_category', 'a.seotitle', 'e.catid', 'a.id', 'd.username' ) )->from ( '#__hdflv_upload a' )->leftJoin ( '#__users d ON a.memberid=d.id' )->leftJoin ( '#__hdflv_video_category e ON e.vid=a.id' )->leftJoin ( '#__hdflv_category b ON e.catid=b.id' )->where ( $db->quoteName ( 'a.id' ) . ' = ' . $db->quote ( $videoid ) )->group ( $db->escape ( 'e.vid' ) );
        $db->setQuery ( $query );
        /** store user details */
        $user_details = $db->loadObject ();
        
        /** For SEO settings */
        $seoOption = $dispenable ['seo_option'];
        
        if ($seoOption == 1) {
          /** set seo title of category */
          $featureCategoryVal = "category=" . $user_details->seo_category;
          /** set seo title of video */
          $featureVideoVal = "video=" . $user_details->seotitle;
        } else {
          /** set category id */
          $featureCategoryVal = "catid=" . $user_details->catid;
          /** set video id */
          $featureVideoVal = "id=" . $user_details->id;
        }
        /** get logged in user mail id */
        $mailer->addRecipient ( $user_details->email );
        /** set subject for the mail */
        $subject = JText::_ ( 'HDVS_VIDEO_APPROVED_BY_ADMIN' );
        /**  set admin baseURL*/
        $baseurl = str_replace ( 'administrator/', '', JURI::base () );
        /** set video baseurl */
        $video_url = $baseurl . 'index.php?option=com_contushdvideoshare&view=player&' . $featureCategoryVal . '&' . $featureVideoVal;
        $video_url = str_replace ( 'administrator/', '', $video_url );
        /** get mail template */
        $filepath = file_get_contents ( $baseurl . '/components/com_contushdvideoshare/emailtemplate/approveadmin.html' );
        $baseurl_replace = str_replace ( "{baseurl}", $baseurl, $filepath );
        $site_name_replace = str_replace ( "{site_name}", $site_name, $baseurl_replace );
        $username_replace = str_replace ( "{username}", $user_details->username, $site_name_replace );
        $subject_replace = str_replace ( "{approved}", $subject, $username_replace );
        $message = str_replace ( "{video_url}", $video_url, $subject_replace );
        $mailer->isHTML ( true );
        $mailer->setSubject ( $subject );
        $mailer->Encoding = 'base64';
        $mailer->setBody ( $message );
        $mailer->Send ();
      }
      /** set publish status of the video */
      $publish = 1;
    } elseif ($arrVideoId ['task'] == 'trash') {
      /** set the trashed status of the videos */
      $publish = - 2;
      /** display video trashed message */
      $msg = 'Trashed Successfully';
    } else {
      /** display unpublished message */
      $msg = 'Unpublished successfully';
      /** set unpublished status of the video */
      $publish = 0;
    }
    /** call to method to videotable */
    $objAdminVideosTable = &$this->getTable ( ADMINVIDEOS );
    /** call to method to update the status of the video */
    $objAdminVideosTable->publish ( $arrVideoId ['cid'], $publish );
    /** set the redirecting link */
    $strRedirectPage = REDIRECTUSERLINK;
    /** redirect link */
    $mainframe->redirect ( $strRedirectPage, $msg, MESSAGE );
  }
  
  /**
   * Function to save videos
   *
   * @param string $task
   *          action to be performed
   *          
   * @return savevideos
   */
  public function savevideos( $task ) {
    /** database variable to save the video */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** To get an instance of the adminvideos table object */
    $rs_saveupload = JTable::getInstance ( ADMINVIDEOS, 'Table' );
    /** get multiple selected video ids */
    $arrCatId = JRequest::getVar ( 'cid', array ( 0 ), '', 'array' );
    /** array varaible */
    $strCatId = $arrCatId [0];
    /** call to method to save the data */
    $rs_saveupload->load ( $strCatId );
    
    /** Variable initialization */
    $arrFormData = JRequest::get ( 'post' );
    /** get embed code */
    $embedcode = JRequest::getVar ( 'embedcode', '', 'post', TYPE_STRING, JREQUEST_ALLOWRAW );
    /** store embed code data in an array */
    $arrFormData ['embedcode'] = $embedcode;
    
    if (trim ( $arrFormData [VSSEOTITLE] ) == '') {
      /** assign seo title for the video */
      $arrFormData [VSSEOTITLE] = $arrFormData ['title'];
    } 
    /** set seo title format */
    $arrFormData [VSSEOTITLE] = JApplication::stringURLSafe ( $arrFormData [VSSEOTITLE] );    
    if (trim ( str_replace ( '-', '', $arrFormData [VSSEOTITLE] ) ) == '') {
      /** set seo title format to data */
      $arrFormData [VSSEOTITLE] = JFactory::getDate ()->format ( 'Y-m-d-H-i-s' );
    }
    /** call to method tp get the admin video from the video table */
    $table = $this->getTable ( ADMINVIDEOS );    
    while ( $table->load ( array ( 'seotitle' => $arrFormData [VSSEOTITLE] ) ) && empty ( $arrFormData ['id'] ) ) {
      /** set the seo title */
      $arrFormData [VSSEOTITLE] = JString::increment ( $arrFormData [VSSEOTITLE], 'dash' );
    }    
    if (empty ( $arrFormData ['id'] ) && empty ( $arrFormData [ORDERING] )) {
      /** get count of ordering from the video table */
      $query->clear ()->select ( 'count(ordering)' )->from ( PLAYERTABLE );
      $db->setQuery ( $query );
      /** store the ordering result in an array */
      $arrFormData [ORDERING] = $db->loadResult ();
    }
    /** get video description */
    $description = JRequest::getVar ( 'description', '', 'post', TYPE_STRING, JREQUEST_ALLOWRAW );
    /** store the description in an array */
    $arrFormData ['description'] = $description;
    
    /** Call function to bind video detials in db */
    $this->bindVideoDetails ( $rs_saveupload, $arrFormData );
  }
  
  /**
   * Function is used to bind video detials and complete save action
   * 
   * @param unknown $rs_saveupload
   * @param unknown $arrFormData
   */
  public function bindVideoDetails ( $rs_saveupload, $arrFormData ) {
    /** Object to bind to the instance */
    if (! $rs_saveupload->bind ( $arrFormData )) {
      /** raise error when video details fail to bind */
      JError::raiseWarning ( 500, $rs_saveupload->getError () );
    }
    /** Get and assign video url */
    if (isset ( $rs_saveupload->videourl) && $rs_saveupload->videourl != "") {
      /** trim video url */
      $rs_saveupload->videourl = trim ( $rs_saveupload->videourl );
    }
    /** Inserts a new row if id is zero or updates an existing row in the hdflv_upload table */
    if (! $rs_saveupload->store ()) {
      /** raise error when video fails to save */
      JError::raiseWarning ( 500, $rs_saveupload->getError () );
    }
    /** Check in the item */
    /** call to method to check the item */
    $rs_saveupload->checkin ();
    /**  store the video id*/
    $idval = $rs_saveupload->id;
    
    /** Call function to include helper files */
    $this->includeHelper ($arrFormData, $idval);
    
    /** Call function to end save videos action */
    $this->saveVideosSuccess ($idval, $task);
  }
  /**
   * Function to include helper files
   * 
   * @param unknown $arrFormData
   * @param unknown $idval
   */
  public function includeHelper ($arrFormData, $idval) {
    /** file upload option */
    $fileoption = $arrFormData ['fileoption'];
    /** helper file path */
    $helperPath = FVPATH . DS . 'helpers' . DS;
    /** Uploading videos type : URL  */
    if ($fileoption == 'Url') {
      /** helper file path for URL method */
      require_once $helperPath . 'uploadurl.php';
      /** call to method to upload video for URL method */
      UploadUrlHelper::uploadUrl ( $arrFormData, $idval );
    }
    /** Uploading videos type : YOUTUBE */
    if ($fileoption == "Youtube") {
      /** helper file path for YouTube method */
      require_once $helperPath . 'uploadyoutube.php';
      /** call to method to upload YouTube videos */
      UploadYouTubeHelper::uploadYouTube ( $arrFormData, $idval );
    }
    /** Uploading videos type : Embed */
    if ($fileoption == "Embed") {
      /** helper file path for embed method */
      require_once $helperPath . 'uploadembed.php';
      /** call to method to upload embed videos */
      UploadEmbedHelper::uploadEmbed ( $arrFormData, $idval );
    }
    /** Uploading videos type : FILE */
    if ($fileoption == "File") {
      /** helper file path for file method */
      require_once $helperPath . 'uploadfile.php';
      /** call to method to upload file videos */
      UploadFileHelper::uploadFile ( $arrFormData, $idval );
    }
    /** Uploading videos type : FFMPEG */
    if ($fileoption == "FFmpeg") {
      /** helper file path for FFMPEG method */
      require_once $helperPath . 'uploadffmpeg.php';
      /** call to method to FFMPEG videos */
      UploadFfmpegHelper::uploadFfmpeg ( $arrFormData, $idval );
    }
  }
  /**
   * Function to finifh the save videos action
   * 
   * @param unknown $idval
   * @param unknown $task
   */
  public function saveVideosSuccess ($idval, $task) {
    global $option, $mainframe;
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    /** variable declaration for current data */
    $createddate = date ( "Y-m-d h:m:s" );
    /** varaible declaration for usertype redirect */
    $userTypeRedirect = (JRequest::getVar ( 'user', '', 'get' ) == ADMIN) ? "&user=" . JRequest::getVar ( 'user', '', 'get' ) : "";
   
    /** Query to update created date */
    $query->clear ()->update ( $db->quoteName ( PLAYERTABLE ) )->set ( $db->quoteName ( 'created_date' ) . ' = ' . $db->quote ( $createddate ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $idval ) );
    $db->setQuery ( $query );
    $db->query ();
    /** get playlist id  */
    $catid = JRequest::getVar ( 'playlistid' );
    /** Query to find the existing of category for video */
    $query->clear ()->select ( 'count(vid)' )->from ( VIDEOCATEGORYTABLE )->where ( $db->quoteName ( 'vid' ) . ' = ' . $db->quote ( $idval ) );
    $db->setQuery ( $query );
    /** store total count of the video */
    $total = $db->loadResult ();
    if ($total != 0) {
      $query->clear ()->update ( $db->quoteName ( VIDEOCATEGORYTABLE ) )->set ( $db->quoteName ( 'catid' ) . ' = ' . $db->quote ( $catid ) )->where ( $db->quoteName ( 'vid' ) . ' = ' . $db->quote ( $idval ) );
    } else {
      $values = array ( $db->quote ( $idval ), $db->quote ( $catid ) );
      $query->clear ()->insert ( $db->quoteName ( VIDEOCATEGORYTABLE ) )->values ( implode ( ',', $values ) );
    }
    $db->setQuery ( $query );
    $db->query ();
    
    switch ($task) {
      case 'applyvideos' :
        /** redirect url when apply video button is clicked  */
        $link = 'index.php?option=' . $option . '&layout=adminvideos&task=editvideos' . $userTypeRedirect . '&cid[]=' . $idval;
        break;
      case 'savevideoupload' :
      default :
        /** redirect when save button is clicked */
        $link = 'index.php?option=' . $option . '&layout=adminvideos' . $userTypeRedirect;
        break;
    }
    $msg = SAVE_SUCCESS;
    /** Set to redirect */
    $mainframe->redirect ( $link, $msg, MESSAGE );
  }
  
  /**
   * Function to make video as featured/unfeatured
   *
   * @param array $arrVideoId
   *          video detail array
   *          
   * @return featuredvideo
   */
  public function featuredvideo($arrVideoId) {
    global $mainframe;
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    if ($arrVideoId ['task'] == "featured") {
      /** set publish state to 1 */
      $publish = 1;
    } else {
      /**  set publish state to 0 */
      $publish = 0;
    }
    /** message for successful update */
    $msg = 'Updated Successfully';
    /** implode the video ids */
    $strVideoIds = implode ( ',', $arrVideoId ['cid'] );
    /** query to update player table  */
    $query->clear ()->update ( $db->quoteName ( PLAYERTABLE ) )->set ( $db->quoteName ( 'featured' ) . ' = ' . $db->quote ( $publish ) )->where ( $db->quoteName ( 'id' ) . ' IN (' . $strVideoIds . ')' );
    $db->setQuery ( $query );
    $db->query ();
    /** set redirect link */
    $strRedirectPage = REDIRECTUSERLINK;
    $mainframe->redirect ( $strRedirectPage, $msg, MESSAGE );
  }
  
  /**
   * Function to display comments in video grid view
   *
   * @return featuredvideo
   */
  public function getcomment() {
    /** Variable initialization */
    global $option, $mainframe, $db;
    $query = $db->getQuery ( true );
    $sub = $db->getQuery ( true );
    $commentId = JRequest::getVar ( 'cmtid', '', 'get', 'int' );
    $id = JRequest::getVar ( 'id', '', 'get', 'int' );
    
    /** For pagination */
    $limit = $mainframe->getUserStateFromRequest ( $option . '.limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
    $limitstart = $mainframe->getUserStateFromRequest ( $option . LIMITSTART, LIMITSTART, 0, 'int' );
    
    /** Query for delete the comments */
    if ($commentId) {
      $query->clear ()->delete ( $db->quoteName ( COMMENTSTABLE ) )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $commentId ) . ' OR ' . $db->quoteName ( 'parentid' ) . ' = ' . $db->quote ( $commentId ) );
      $db->setQuery ( $query );
      $db->query ();
      
      /** Message for deleting comment */
      $mainframe->enqueueMessage ( 'Comment Successfully Deleted' );
    }
    
    $id = JRequest::getVar ( 'id', '', 'get', 'int' );
    $query->clear ()->select ( 'COUNT(id)' )->from ( COMMENTSTABLE )->where ( $db->quoteName ( 'videoid' ) . ' = ' . $db->quote ( $id ) );
    $db->setQuery ( $query );
    $db->query ();
    $commentcount = $db->getNumRows ();
    
    if (! $commentcount) {
      $strRedirectPage = REDIRECTUSERLINK;
      $mainframe->redirect ( $strRedirectPage );
    }
    
    /** Query is to display the comments posted for particular video */
    
    $sub->select ( array ( 'parentid as number', 'id', 'parentid', 'videoid', 'subject', 'name', 'created', 'message' ) )->from ( COMMENTSTABLE )->where ( $db->quoteName ( 'parentid' ) . ' != ' . $db->quote ( '0' ) )->where ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'videoid' ) . ' = ' . $db->quote ( $id ) );
    
    $query->clear ()->select ( array ( 'id as number', 'id', 'parentid', 'videoid', 'subject', 'name', 'created', 'message' ) )->from ( COMMENTSTABLE )->where ( $db->quoteName ( 'parentid' ) . ' = ' . $db->quote ( '0' ) )->where ( $db->quoteName ( 'published' ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'videoid' ) . ' = ' . $db->quote ( $id ) . ' UNION ' . $sub )->order ( $db->escape ( 'number' . ' ' . 'DESC' ) . ',' . $db->escape ( 'parentid' ) );
    $db->setQuery ( $query );
    $db->query ();
    $commentTotal = $db->getNumRows ();
    
    $pageNav = new JPagination ( $commentTotal, $limitstart, $limit );
    $db->setQuery ( $query, $pageNav->limitstart, $pageNav->limit );
    $comment = $db->loadObjectList ();
    
    $query->clear ()->select ( array (
        'title' 
    ) )->from ( PLAYERTABLE )->where ( $db->quoteName ( 'id' ) . ' = ' . $db->quote ( $id ) );
    $db->setQuery ( $query );
    $videoTitle = $db->loadResult ();
    
    /** Display the last database error message in a standard format */
    if ($db->getErrorNum ()) {
      JError::raiseWarning ( $db->getErrorNum (), $db->stderr () );
    }
    
    return array ( 'pageNav' => $pageNav, 'limitstart' => $limitstart, 'comment' => $comment, 'videotitle' => $videoTitle );
  }
  
  /**
   * Function to get comments count
   *
   * @param int $videoId
   *          video id
   *          
   * @return featuredvideo
   */
  public function getCommentcount($videoId) {
    /** Variable initialization */
    global $db;
    $query = $db->getQuery ( true );
    $query->clear ()->select ( array (
        'count(id)' 
    ) )->from ( COMMENTSTABLE )->where ( $db->quoteName ( 'videoid' ) . ' = ' . $db->quote ( $videoId ) );
    $db->setQuery ( $query );
    return $db->loadResult ();
  }
}
