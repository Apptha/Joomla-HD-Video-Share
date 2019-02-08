<?php
/**
 * Controller file for Contus HD Video Share
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
/** Import Joomla controller library */
jimport ( 'joomla.application.component.controller' );
/** HD Video Share component main controller */
if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
  $jlang = JFactory::getLanguage ();
  $jlang->load ( COMPONENT, JPATH_SITE, $jlang->get ( 'tag' ), true );
  $jlang->load ( COMPONENT, JPATH_SITE, null, true );
}

/**
 * ContushdvideoshareParentController Class is used to perform the given task
 * 
 * @author Vid-Maha
 */
class ContushdvideoshareParentController extends ContusvideoshareController {
	/**
	 * Function is used to set model and view object
	 * 
	 * @param string $modelName
	 * 
	 * @return object
	 */
	public function getModelObject ( $modelName ) {
		$model = $this->getModel( $modelName, MODELCONTUSHDVIDEOSHARE );
		$view = $this->getView( $modelName,'html');
		$view->setModel($model, true);
		return $view;
	}
	
	/**
	 * Function to insert data into watch later database table.
	 *
	 * @return void
	 */
	public function watchlater() {
		global $contusDB, $contusQuery;
		$result = 0;
		$vID =  $contusDB->quote(JRequest::getInt('vid'));
		$userID = (int) getUserID() ;
		if(!empty($vID) && !empty($userID)) {
			$contusQuery->clear()->select('COUNT(*)')->from($contusDB->quoteName( WATCHLATERTABLE ))->where(array($contusDB->quoteName(USER_ID)." = ".$userID, $contusDB->quoteName('video_id')." = ".$vID, ));
			$contusDB->setQuery($contusQuery);
			$checkId = $contusDB->loadResult();
			/** Check if watch later has already been inserted for the current user and current video or not. */
			if(!empty($checkId)){
				$result = 1 ;
			} else{
				$columns =  array(USER_ID,'video_id');
				$values   = array( $userID , $vID );
				$contusQuery->clear()->insert($contusDB->quoteName( WATCHLATERTABLE ))->columns($contusDB->quoteName($columns))->values(implode(',', $values));
				$contusDB->setQuery($contusQuery);
				if( $contusDB->execute() ) {
					$result = 1 ;
				} else {
					$result = 0 ;
				}
			}
		}
		echo $result;
		exitAction ( '' );
	}
	
	/**
	 * Function to remove watch later for a particular user.
	 *
	 * @return void
	 */
	public function removewatchlater() {
		global $contusDB, $contusQuery;
		$result = 0;
		$userID =  $contusDB->quote(JRequest::getInt('userId'));
		if(!empty($userID)) {
			$contusQuery->clear()->delete()->from( WATCHLATERTABLE )->where($contusDB->quoteName(USER_ID).'='.$userID);
			$contusDB->setQuery($contusQuery, 0, $userID);
			$contusDB->query();
			$deleted = $contusDB->execute();
			if(!empty($deleted)){
				$result = 1 ;
			}
		}
		echo $result;
		exitAction ( '' );
	}
	
	/**
	 * Method to clear history of the watched videos
	 *
	 * @return void
	 */
	public function ClearHistory(){
		$event = JRequest::getVar( 'event' );
		$UserId = (int) getUserID();
		$VideoId = JRequest::getInt ( 'VideoId' );
		$HistoryModel = JModelLegacy::getInstance ('watchhistoryvideos', MODELCONTUSHDVIDEOSHARE);
		$ClearResult = $HistoryModel->ClearHistory($event,$UserId,$VideoId);
		echo  $ClearResult;
		exitAction ( '' );
	}
	
	/**
	 * Method to plause history state of the users
	 *
	 * @return void
	 */
	public function PauseHistory(){
		$UserId = (int) getUserID();
		$HistoryStatus = JRequest::getInt( 'status' );
		$HistoryModel = JModelLegacy::getInstance ('watchhistoryvideos', MODELCONTUSHDVIDEOSHARE);
		$PauseResult = $HistoryModel->PauseHistory($UserId,$HistoryStatus);
		echo  $PauseResult;
		exitAction ( '' );
	}
	/**
	 * Function to update video view count and watch history for the current user when the video starts playing.
	 * This function is called through Ajax.
	 *
	 * @return void
	 */
	public function videoPlaying() {
	  $videoId =  JRequest::getInt('videoId');
	  updateViewCount($videoId);
	  insertWatchHistory($videoId);
	  exitAction ( '' );
	}
	/**
	 * Function to save subscribers image uploaded by user.
	 *
	 * @return void
	 */
	public function saveSubscriperImage() {
		$sid = (isset($_REQUEST[ SUBID ]) && $_REQUEST[ SUBID ] !=null) ? intVal($_REQUEST[ SUBID ]) : '';
		$this->imageUpload($sid);
	}
	
	/**
	 * Function to process the uploaded image by the user. This function validates the uploaded image and saves into the server.
	 *
	 * @param string $subscriperId The id of the user whose image is being uploaded by the administrator.
	 * @return void
	 */
	public function imageUpload($subscriperId='') {
		$imageSize = $imageType = $imageWidth = $imageHeight =  $model = $imageName = $uploadTypeMatch = $typeMatch = $extension = $extensionMatch = $validType = $targetPath = $user = $userId = $isAdmin = $imageDetails = $invalidUser =  '';
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		preg_match('/MSIE/i',$u_agent,$ie);
		if(isset($_SESSION[IMAGENAME]) && $_SESSION[IMAGENAME] !=null) {
			if(file_exists(CHANNEL_DIRPATH . $_SESSION[IMAGENAME])) {
				unlink( CHANNEL_DIRPATH . $_SESSION[IMAGENAME]);
			}
			unset($_SESSION[IMAGENAME]);
		}		
		removeSessionValue ( USER_ID );
		removeSessionValue ( IMAGEEXTENSION );
		removeSessionValue ( IMAGEUPLOADTYPE );
		
		if(empty($subscriperId)) {
			$uid = intVal($_REQUEST['ui']);
		} else {
			$uid = $subscriperId;
		}
		$model = $this->getModel(CHANNEL,MODELCONTUSHDVIDEOSHARE);
		$uploadType  = strip_tags($_REQUEST[UPLOADTYPE]);
		$user = JFactory::getUser();
		$userId = $user->id;
		$imageType = $_FILES[IMAGES]['type'];
		preg_match('/^image\/(jpeg|png|gif)$/',$imageType,$typeMatch);
		preg_match('/(png|jpeg|gif)$/',$imageType,$extensionMatch);
		preg_match('/^(profile|cover)$/',$uploadType,$uploadTypeMatch);
		if(empty($uploadTypeMatch)) {
			checkType ( $ie, 'Page Not Found');
		}
		if(!empty($typeMatch)) {
			$validType = true;
		} else {
			$validType = false;
			checkType ( $ie, 'Invalid type"');
		}
		if($userId !=0 && $user->guest == 0) {
			$isAdmin = in_array('8',$user->groups);
		} else {
			$isAdmin = false;
		}
		if(!$isAdmin) {
			$invalidUser = ($uid != $userId ) ? true : false;
		} else {
			$invalidUser = false;
		}
		if($invalidUser === true) {
			checkType ( $ie, 'Invalid type');
		}
		$extension = getImageExtension ( $imageType );
		$imageName  = $uploadType.$userId.time().'.'.$extension;
		$targetPath = CHANNEL_DIRPATH . $imageName;
		$imageSize =  floor($_FILES[IMAGES]['size']/1024/1024);
		$imageDetails = getimagesize($_FILES[IMAGES]['tmp_name']);
		$imageWidth = $imageDetails[0];
		$imageHeight = $imageDetails[1];
		if($imageWidth < 1000 || $imageHeight < 700 ) {
			if(empty($ie)) {
				echo json_encode(array(ERRORMSG=>'true',ERRMSG=>'Image must be greater than 1024X700 pixels'));
				exitAction ( '' );
			} else {
				JError::raiseError(404, JText::_("Image must be greater than 1024X700 pixels"));
			}
		}
		if(move_uploaded_file($_FILES[IMAGES]["tmp_name"],$targetPath)) {
			$_SESSION[IMAGENAME] = $imageName;
			$_SESSION[USER_ID]   = $uid;
			$_SESSION[IMAGEUPLOADTYPE] = $uploadType;
			$_SESSION[IMAGEEXTENSION]  = $extension;
			$result = json_encode(array('imageName'=>$imageName,'imageWidth'=>$imageWidth,'imageHeight'=>$imageHeight,ERRORMSG=>'false','uploadType'=>$uploadType));
			if(!empty($ie)) {
				$this->croppingCoverImage();
			} else {
				echo $result;
				exitAction ( '' );
			}
		} else {
			echo 'not moved';
			exitAction ( '' );
		}
	}
	
	/**
	 * Function to crop cover image of a user's channel.
	 *
	 * @return void
	 */
	public function croppingCoverImage() {
		$imageName = $uploadType = $imgExtension = $user = $cropWidth = $cropHeight = $cropX = $cropY = '';
		$uid = 0;
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		preg_match('/MSIE/i',$u_agent,$ie);
		/** Get values from seeion */
		$imageName = strip_tags( getSessionValue (IMAGENAME) );
		$uid = intVal( getSessionValue (USER_ID));
		$uploadType = strip_tags( getSessionValue (IMAGEUPLOADTYPE));
		$imgExtension = strip_tags( getSessionValue (IMAGEEXTENSION));	
		
		/** Get user id and check user is logged in */
		$user = JFactory::getUser();
		$userId = $user->id;
		if($userId !=0 && $user->guest == 0) {
			$isAdmin = in_array('8',$user->groups);
		} else {
			$isAdmin = false;
		}
	
		if(!$isAdmin) {
			$invalidUser = ($uid != $userId ) ? true : false;
		} else {
			$invalidUser = false;
		}
	
		if($invalidUser === true && empty($ie)) {
			echo json_encode(array(ERRORMSG=>'true',ERRMSG=>'404'));
			exitAction ( '' );
		}
		/** Get channel model object */
		$model = $this->getModel(CHANNEL,MODELCONTUSHDVIDEOSHARE);
		$imageDetails = json_decode($model->bannerImageDetails($uid),true);
		
		$cropX  = (isset($_REQUEST['cx'])) ? intVal($_REQUEST['cx']) : 0;
		$cropY = (isset($_REQUEST['cy'])) ? intVal($_REQUEST['cy']) : 0;
		$coverImagePath = CHANNEL_DIRPATH . $imageName;
		if($uploadType == 'cover') {			
			$cropWidth  = 980;
			$cropHeight = 250;			
			$destImagePath = CHAN_COVERPATH . $imageName;
			$coverImageDetails = getimagesize($coverImagePath);
			$orignalImageWidth = $coverImageDetails[0];
			$offsetXdifference = $orignalImageWidth - $cropWidth;
			if($cropX > $offsetXdifference) {
				$cropX = $offsetXdifference;
			}
			$imageDetails['coverImage'] = $imageName;
		}
		if($uploadType == 'profile') {			
			$cropWidth  = 160;
			$cropHeight = 160;			
			$destImagePath = CHAN_PROFILEPATH . $imageName;
			$imageDetails['profileImage'] = $imageName;
		}
		croppingImage ( $imgExtension, $cropX, $cropY, $cropWidth, $cropHeight,$coverImagePath,$destImagePath);
		
		if(file_exists($coverImagePath)) {
			unlink($coverImagePath);
		}
		
		$jsonDetails = json_encode($imageDetails);
		$updateResult = $model->updateImageDetails($jsonDetails,$uid,$uploadType);
		if($updateResult) {
			$result = json_encode(array("uploadType"=>$uploadType,"image"=>$imageName));
			if(!empty($ie)) {
				header('location:'.$_SERVER['HTTP_REFERER']);
			} else {
				echo $result;
				exitAction ( '' );
			}
		} else {
			echo 'not updated';
			exitAction ( '' );
		}
	}
	
	/**
	 * Function to dispay the channel page to the user.
	 *
	 * @return void
	 */
	public function channel() {
		includeChannelSubscribeJS ();
		$model = $this->getModel(CHANNEL,MODELCONTUSHDVIDEOSHARE);
		$view = $this->getView ( CHANNEL, 'html' );
		$view->setModel ( $model, true );
		$view->display ();
	}
	/**
	 * Function to add a new channel for a user.
	 * This function is called by the channel page after checking whether channel exists for the current user or not.
	 *
	 * @return void
	 */
	public function addnewchannel() {
		includeChannelSubscribeJS ();
		$model = $this->getModel(CHANNEL,MODELCONTUSHDVIDEOSHARE);
		$user = JFactory::getUser();
		if($user->id !=0 && $user->guest==0) {
			$userKey = $model->addnewuser();
			$redirectURL = JRoute::_('index.php?option=com_contushdvideoshare&task=channel&ukey='.$userKey);
			header("location:".$redirectURL);
		}
	}
	/**
	 * Function to display my videos section in the my channel page.
	 *
	 * @return void
	 */
	public function channelMyVideos() {
		$view = $this->getModelObject ( CHANNEL );
		$view->setLayout('myvideos');
		$view->channelMyVideos();
	}
	/**
	 * Function to display my videos section in the subscriber page.
	 *
	 * @return void
	 */
	public function subscripeMyVideos() {
		$view = $this->getModelObject ( SUBSCRIBE );
		$view->setLayout('myvideos');
		$view->channelMyVideos();
	
	}
	/**
	 * Function to display description section in the my channel page.
	 *
	 * @return void
	 */
	public function channelDescription() {
		$view = $this->getModelObject ( CHANNEL );
		$view->channelDescriptionView();
	}
	/**
	 * Function to save subscriber description into the database.
	 *
	 * @return void
	 */
	public function saveSubscriperDescription() {
		$view = $this->getModelObject ( SUBSCRIBE );
		$view->channelDescriptionView();
	}
	/**
	 * Function to display the channels which have not been subscribed by the current user.
	 *
	 * @return void
	 */
	public function subscriperDetails() {
		$view = $this->getModelObject ( CHANNEL );
		$view->setLayout('subscriper');
		$view->subscriperDetailsView();
	}
	/**
	 * Function to display subscription details of the current user in the channel page.
	 *
	 * @return void
	 */
	public function mySubscriperDetails() {
		$view = $this->getModelObject ( CHANNEL );
		$view->setLayout(MYSUBSCRIBE);
		$view->mySubscriperDetailsView();
	}

	/**
	 * Function to display subscription details of a user to the administrator in the subscriber page.
	 *
	 * @return void
	 */
	public function getMySubscriperDetails() {
		$view = $this->getModelObject ( SUBSCRIBE );
		$view->setLayout(MYSUBSCRIBE);
		$view->getMySubscriperDetailsView();
	}
	/**
	 * Function to remove a channel from the subscription list of the current user.
	 *
	 * @return void
	 */
	public function closeSubscripe() {
		$view = $this->getModelObject ( CHANNEL );
		$view->setLayout(MYSUBSCRIBE);
		$view->closeSubscripeView();
	}
	/**
	 * Function to remove a channel from the subscription list of the a user by the administrator.
	 *
	 * @return void
	 */
	public function closeSubscripeDetail() {
		$view = $this->getModelObject ( SUBSCRIBE );
		$view->setLayout(MYSUBSCRIBE);
		$view->closeSubscripeDetailView();
	}	
	/** Channel Functions End */
}

/**
 * Featured Videos Module installer file
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareController extends ContushdvideoshareParentController {
  /**
   * Function to set layout and model for view page.
   *
   * @param boolean $cachable
   *          If true, the view output will be cached
   * @param boolean $urlparams
   *          An array of safe url parameters and their variable types          
   * @return ContushdvideoshareController object to support chaining.        
   * @since 1.5
   */
  public function display($cachable = false, $urlparams = false) {    
    /** Get view name in controller */
    $viewName = JRequest::getVar ( 'view' );     
    /** check view name as player xml */
    if ($viewName != "languagexml" && $viewName != "configxml" && $viewName != "playxml" && $viewName != "googlead") {
      /** Include js for jquery and tooltip */
      includeCommonJSCss();
    }
    
    /** Set view name as player for empty and index view */
    if ($viewName == "" || $viewName == "index") {
      $viewName = 'player';
    }    
    /** Display view */
    $this->getdisplay ( $viewName );
  }  

  /**
   * Function to assign model for the view
   *
   * @param string $viewName
   *          view name
   *
   * @return getdisplay
   */
  public function getdisplay($viewName = "index") {
    /** Get document object and view type */
    $document = JFactory::getDocument ();
    $viewType = $document->getType ();
    $view = $this->getmodView ( $viewName, $viewType );
    /** Get model for the given view */
    $model = $this->getModel ( $viewName, MODELCONTUSHDVIDEOSHARE );
    /** If model object is created then set model  object */
    if (! JError::isError ( $model )) {
      $view->setModel ( $model, true );
    }
  
    $view->display ( $cachable = false, $urlparams = false );
  }
  
  /**
   * Function to assign view if view not exist
   *
   * @param string $name view name
   * @param string $type view type
   * @param string $prefix view prefix
   * @param array $config config array
   *
   * @return &getmodView
   */
  public function &getmodView($name = '', $type = '', $prefix = '', $config = array()) {
    static $views;
  
    /** Set view name with prefix */
    if (empty ( $prefix )) {
      $prefix = $this->getName () . 'View';
    }
    /** check view name is empty */
    if (empty ( $views [$name] )) {
      /** Check joomla version */
      if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
        if ($view = $this->createView ( $name, $prefix, $type, $config )) {
          /** Create view and set view name lower version */
          $views [$name] = & $view;
        } else {
          /** If view is not created then redirect to player view */
          header ( "Location:index.php?option=com_contushdvideoshare&view=player&itemid=0" );
        }
      } else {
        if ($view = $this->_createView ( $name, $prefix, $type, $config )) {
          /** Create view and set view name for version 3.0+ */
          $views [$name] = & $view;
        } else {
          /** If view is not created then redirect to player view */
          header ( "Location:index.php?option=com_contushdvideoshare&view=player&itemid=0" );
        }
      }
    }
    return $views [$name];
  }

  /**
   * Function to send report of a video
   *
   * @return sendreport
   */
  public function sendreport() {
    global $contusDB, $contusQuery;
    /** Get report message from param */
    $repmsg = JRequest::getVar ( 'reportmsg' );
    /** Get video id from param */
    $videoid = JRequest::getInt ( 'videoid' );
    /** Get user object to send report */
    /** Get member id for report section */
    $memberid = getUserID ();
    
    /** Create object for mailer */
    $mailer = JFactory::getMailer ();
    /** Get default configuration */
    $config = JFactory::getConfig ();
    
    /** Query is to display recent videos in home page */
    $contusQuery->clear ()->select ( array ( 'email', 'username' ) )->from ( '#__users' )->where ( $contusDB->quoteName ( 'id' ) . ' = ' . $contusDB->quote ( $memberid ) );
    $contusDB->setQuery ( $contusQuery );
    $user_details = $contusDB->loadObject ();
    
    /** Get from email to send report */
    $sender = $config->get ( 'mailfrom' );
    /** Set to email address to send report */
    $mailer->setSender ( $user_details->email );
    /** Set video id */
    $featureVideoVal = "id=" . $videoid;
    $mailer->addRecipient ( $sender );
    /** Define mail subject */
    $subject = JText::_ ( 'HDVS_USER_REPORTED' );
    /** Get base url and video url */
    $baseurl = JURI::base ();
    $video_url = $baseurl . 'index.php?option=com_contushdvideoshare&view=player&' . $featureVideoVal . '&adminview=true';
    /** Get report email template content */
    $get_htmlmessage = file_get_contents ( $baseurl . '/components/com_contushdvideoshare/emailtemplate/reportvideo.html' );
    /** Replace the smarty with the appropriate value */
    $update_baseurl = str_replace ( "{baseurl}", $baseurl, $get_htmlmessage );
    $update_username = str_replace ( "{username}", $user_details->username, $update_baseurl );
    $update_rptmsg = str_replace ( "{reportmsg}", $repmsg, $update_username );
    $message = str_replace ( "{video_url}", $video_url, $update_rptmsg );
    $mailer->isHTML ( true );
    /** Set mail subject */
    $mailer->setSubject ( $subject );
    /** Set mail encoding */
    $mailer->Encoding = 'base64';
    /** Set mail body */
    $mailer->setBody ( $message );
    /** Send report email */
    $send = $mailer->Send ();
    /** If mail is not sent display error message */
    if ($send !== true) {
      echo 'Error sending email: ' . $send->message;
    } else {
      /** Else display success message */
      echo JText::_ ( 'HDVS_REPORTED_SUCCESS' );
    }
  }  
  
  /**
   * Function for assigning model for upload method
   *
   * @return uploadfile
   */
  public function uploadfile() {
    /** Get model for upload video */
    $model = $this->getModel ( 'uploadvideo' );
    /** Call function to upload files */ 
    $model->fileupload ();
  }
  
  /**
   * Function to call helper function to get youtube data
   *
   * @return youtubeurl
   */
  public function youtubeurl() {
    fetchYouTubeDetails ();
  }
  
  /**
   * Function for email option in player
   *
   * @return emailuser
   */
  public function emailuser() {
  	/** Get referer URL */
  	$referrer         = parse_url ( $_SERVER ['HTTP_REFERER'] );
  	$referrer_host    = $referrer ['scheme'] . '://' . $referrer ['host'];
  	/** Set http into $pageURL */
  	$pageURL          = 'http';
  	/** Check whether the referer URL is https or http */
  	if (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') {
  		/** If https then add 's' for $pageUTL */
  		$pageURL .= 's';
  	}
  	$pageURL .= '://';
  	
  	/** Check the server port is not https */
  	if ($_SERVER ['SERVER_PORT'] != '80') {
  		/**  Get server name and server port number */
  		$pageURL .= $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ['SERVER_PORT'];
  	} else {
  		/** Get server name alone */
  		$pageURL .= $_SERVER ['SERVER_NAME'];
  	}
  	/** Check page url and referer url is equal */
  	if ($referrer_host === $pageURL) {
	    /** Get to, from address and url */
	    $to = JRequest::getVar ( 'to' );
	    $from = JRequest::getVar ( 'from' );
	    $video_url = JRequest::getVar ( 'url' );
	    /** Get username form email id */
	    $toEmailArray = explode ( '@', $to );
	    $toUserName = (! empty ( $title )) ? $title : ucfirst ( $toEmailArray [0] );
	    $fromEmailArray = explode ( '@', $from );
	    $fromUserName = (! empty ( $title )) ? $title : ucfirst ( $fromEmailArray [0] );
	    /** Set subject for mail content */
	    $subject = $fromUserName . ' ' . JText::_ ( 'HDVS_SENT_A_VIDEO' ) . '"' . JRequest::getVar ( 'title' ) . '"';
	    
	    /** Get mailer object and set content for mail */
	    $mailer = JFactory::getMailer ();
	    $mailer->setSender ( $from );
	    $mailer->addRecipient ( $to );
	    /** Get base url and site name from global configuration */
	    $baseurl = JURI::base ();
	    $config = JFactory::getConfig ();
	    $site_name = $config->get ( 'sitename' );
	    /** Include share video email template */
	    $get_htmlmessage = file_get_contents ( $baseurl . '/components/com_contushdvideoshare/emailtemplate/sharevideo.html' );
	    /** Set mail content and headers */
	    $update_baseurl = str_replace ( "{baseurl}", $baseurl, $get_htmlmessage );
	    $site_name_replace = str_replace ( "{site_name}", $site_name, $update_baseurl );
	    $updateToUserName = str_replace ( "{username}", $toUserName, $site_name_replace );
	    $updateFromUserName = str_replace ( "{fromusername}", $fromUserName, $updateToUserName );
	    $message = str_replace ( "{video_url}", $video_url, $updateFromUserName );
	    $mailer->isHTML ( true );
	    $mailer->setSubject ( $subject );
	    $mailer->Encoding = 'base64';
	    $mailer->setBody ( $message );
	    $send = $mailer->Send ();	    
	    /** Check mail is sent or not */
	    if ($send !== true) {
	      echo "success=error";
	    } else {
	      echo "success=sent";
	    }
  	}
    exitAction ( '' );
  }  
  /**
   * Function to subscribe a user to a channel by the administrator in the subscriber page.
   *
   * @return void
   */
  public function subscripe() {
  	includeChannelSubscribeJS ();
  	if(isset($_SESSION[SUBSCRIBERID]) && $_SESSION[SUBSCRIBERID] != '') {
  		unset($_SESSION[SUBSCRIBERID]);
  	}
  	$view = $this->getModelObject ( SUBSCRIBE );
  	$view->display();
  }
  /**
   * Function to delete notification in the channel page.
   *
   * @return void
   */
  public function deleteNotification() {
  	$model = $this->getModel('channelhelper',MODELCONTUSHDVIDEOSHARE);
  	$delId = intVal($_POST['delId']);
  	if(empty($delId)) {
  		$model->deleteNotificationModel();
  	} else {
  		$model->updateNotificationModel($delId);
  	}
  }
  /**
   * Function to subscribe to a channel in the my channel page by the current user.
   *
   * @return void
   */
  public function saveSubscriper() {
  	$model = $this->getModel('channelhelper',MODELCONTUSHDVIDEOSHARE);
  	$sid = intVal($_POST['sid']);
  	$model->saveSubscriperId($sid);
  	$model->saveNotification($sid);
  	$model->notificationMail($sid);
  	$view = $this->getView(CHANNEL,'html');
  	$view->setModel($model, true);
  	$view->setLayout('subscriper');
  	$view->subscriperDetailsView();
  }
  
  /**
   * Function check the playlist already exists  or not
   */
  public  function ajaxPlaylistExists() {
  	global $contusDB, $contusQuery;
    $playlistName =  JRequest::getVar('playlist_name');
  	$userID = (int) getUserID();
  	$contusQuery->clear()->select($contusDB->quoteName('id','category','ordering')) ->from( PLAYLISTTABLE ) ->where($contusDB->quoteName('category').'='.$contusDB->quote( $contusDB->escape( $playlistName ,true) ,false ) )
  	->where($contusDB->quotename('member_id').'='.$contusDB->quote($userID));
  	$contusDB->setQuery($contusQuery);
  	if($contusDB->loadResult()){
  		echo '1';
  	} else {
  		echo '0';
  	}
  	exitAction ( '' );
  }
     
       /**
        * Function  for  add  playlist for player  details page  via ajax  method
        */
       public function ajaxplaylistadd() {
          global $contusDB, $contusQuery;
          $count = 0;
          $playLists ='';
        	$playlistName =  JRequest::getVar('playlist_name');
        	$description  = JRequest::getVar('description');
        	$vid  = JRequest::getInt('vid');
        	$container  = JRequest::getVar('container');
        	/** initialize mail objects */
        	$userID =  (int) getUserID() ;
        	$playLists = getPlaylistDetails ();
        	
        	$contusQuery->clear()->select($contusDB->quoteName('id','category','ordering')) ->from( PLAYLISTTABLE ) ->where($contusDB->quoteName('category').'='.$contusDB->quote( $contusDB->escape( $playlistName ,true) ,false ) )
        	->where($contusDB->quotename('member_id').'='.$contusDB->quote( $userID ));
        	$contusDB->setQuery($contusQuery);
        	if($contusDB->loadResult()){
         		echo '3';
         	} else {
          		$category =  $contusDB->quote( $contusDB->escape( $playlistName,true), false);
          		$description =  $contusDB->quote( $contusDB->escape($description , true) , false );
        
          		$contusQuery->clear()->select('max(ordering)')->from( PLAYLISTTABLE );
          		$contusDB->setQuery($contusQuery);
          		$maxorder =  $contusDB->loadResult();
          		$ordering = $maxorder + 1;
          		$seo_category =  makeSEOTitle( $category ); 
          		foreach ($playLists AS $playList ) {
            		if ( $seo_category == $playList->seo_category ) {
            		  /** Load admin videos table and get seo title */
            		  $seo_category = JString::increment ( $seo_category, 'dash' );
            		}
          		}
          		$seo_category =  $contusDB->quote( $seo_category );
          		$ordering     =  $contusDB->quote($ordering);
          		$siteSetting  =  getSiteSettings();
           		$playlistCount =  $siteSetting['playlist_limit'];
       
          		$count = getPlaylistCount ();
          		if( $count >= $playlistCount) {
           			echo "2";
           			exitAction ( '' );
           		}
           		$published =  $contusDB->quote('1');
           		$columns =  array('member_id','category','seo_category','description','published','ordering');
           		$values   = array(  $userID , $category , $seo_category ,$description, $published ,$ordering );
           		$contusQuery->clear()->insert($contusDB->quoteName( PLAYLISTTABLE ))->columns($contusDB->quoteName($columns))->values(implode(',', $values));
           		$contusDB->setQuery($contusQuery);
           		$contusDB->query();
           		$lastinsertID = $contusDB->insertid();
           		if($lastinsertID){
            			if($vid==0){
             				echo '4';
             				exitAction ( '' );
             			}
             			/** Insert into video playlist */
             			$columns =  array('vid','catid');
             			$values   = array(  $vid , $lastinsertID );
             			$contusQuery->clear()->insert($contusDB->quoteName( VIDEOPLAYLISTTABLE ))->columns($contusDB->quoteName($columns))->values(implode(',', $values));
             			$contusDB->setQuery($contusQuery);
             			$contusDB->query();
           
             			/** Mail Functionlity start */
             			sendMail ( $lastinsertID, $playlistName, 'playlist' );
             			/** End mail function
             			 * Select video playlist for user */
             			if( $userID ) {
               				$contusQuery->clear()->select($contusDB->quoteName('catid'))->from( VIDEOPLAYLISTTABLE )->where($contusDB->quoteName('vid') . ' = ' . $contusDB->quote($vid));
              				$contusDB->setQuery($contusQuery);
              				$playlistIDs = $contusDB->loadColumn();
            
              				$playLists = getPlaylistDetails ();
              				displayPlaylists ( $playLists, $playlistIDs, $container, $vid );
              				}
              			} else {
               				echo '0';
               			}
               		}
               		exitAction ( '' );
               	}
             
               	/**
               	 * Function for add video Playlist by  login user
               	 *
               	 * @return void
               	 */
               	public  function removevideoPlaylist() {
                		global $contusDB, $contusQuery;
                		$result = 0;
                		$vID =  $contusDB->quote(JRequest::getInt('vid'));
                		$catID =  $contusDB->quote(JRequest::getInt('cat_id'));
                		$contusQuery->clear()->select($contusDB->quoteName('catid'))->from($contusDB->quoteName( VIDEOPLAYLISTTABLE ))->where( $contusDB->quoteName('vid').'='.$vID.' AND '.$contusDB->quoteName('catid').'='.$catID );
                		$contusDB->setQuery($contusQuery);
                		if( $contusDB->loadResult() ) {
                 			$contusQuery->clear()->delete( $contusDB->quoteName( VIDEOPLAYLISTTABLE ))->where( $contusDB->quoteName('vid').'='.$vID.' AND '.$contusDB->quoteName('catid').'='.$catID );
                 			$contusDB->setQuery( $contusQuery );
                 			if( $contusDB->query()) {
                  				$result = 2;
                  			}
                  		}
                  		echo $result;
                  		exitAction ( '' );
                  	}
                  	/**
                  	 * Function for showing playlists
                  	 *
                  	 * @return void
                  	 */
                  	public  function videoPlaylists() {
                 	  	$vID =  JRequest::getInt('vid');
                 	  	$containerType =  JRequest::getVar('containerType');
                 	  	$userId = (int) getUserID() ;
                 	  	if( $userId ) {
                  	  	$playerModel = JModelLegacy::getInstance ( 'player', 'Modelcontushdvideoshare' );
                  	  	$playlists = getuserplaylists();
                  	  	$uservideoplaylist = $playerModel->getvideoplaylists($vID);
                  	    displayPlaylists ( $playlists, $uservideoplaylist, $containerType, $vID  );
                  		}
                  	  	exitAction ( '' );
                    	}
                    	/**
                    	 * Function for add video Playlist by  login user
                    	 *
                    	 * @return void
                    	 */
                    	public function addvideoPlaylist() {
                     		global $contusDB, $contusQuery;
                     		$result = 0;
                     		$vID =  $contusDB->quote(JRequest::getInt('vid'));
                     		$catID =  $contusDB->quote(JRequest::getInt('cat_id'));
                     		if(!empty($vID) && !empty($catID)) {
                      			$columns =  array('vid','catid');
                      			$values   = array( $vID , $catID );
                      			$contusQuery->clear()->insert($contusDB->quoteName( VIDEOPLAYLISTTABLE ))->columns($contusDB->quoteName($columns))->values(implode(',', $values));
                      			$contusDB->setQuery($contusQuery);
                      			if( $contusDB->execute() ) {
                       				$result = 1 ;
                       			}
                       		}
                       		echo $result;
                       		exitAction ( '' );
                       	}
}