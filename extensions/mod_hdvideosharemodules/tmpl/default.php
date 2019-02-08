<?php
/**
 * Category module for HD Video Share
 *
 * This file is to display the particular category as a module in the admin panel 
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Check user logged in */
$user = JFactory::getUser();
$userId = $user->get('id');

/** Define rating positions in an array */ 
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );
$videoid = $scrolClass = $src_path = '';
$count = 0;

/** Get document object for videoshare modules */
$document = JFactory::getDocument ();

/** Check whether the component is used in the current page */
if (JRequest::getVar ( 'option' ) != 'com_contushdvideoshare') {
  $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/mod_stylesheet.min.css' );
  
  if (version_compare ( JVERSION, '3.0.0', 'ge' )) {
    /** Include joomla jquery framework */  
    JHtml::_ ( 'jquery.framework',False); 
  } else {
    $document->addScript ( JURI::base () . "components/com_contushdvideoshare/js/jquery.js", $type = "text/javascript", $defer = false, $async = true );
  }
  $document->addScript("//code.jquery.com/ui/1.11.4/jquery-ui.js");
  $document->addScriptDeclaration('var baseurl = "' . JURI::base() . '";');
  $document->addScriptDeclaration('var plylistadd = "' . JText::_('HDVS_PLAYLIST_ADDED') . '",plylistaddvideo = "' . JText::_('HDVS_PLAYLIST_ADDED_VIDEO') . '",plylistremove = "' . JText::_('HDVS_PLAYLIST_REMOVED') . '",plylistpblm = "' . JText::_('HDVS_PLAYLIST_PROBLEM') . '",hdvswait = "' . JText::_('HDVS_WAIT') . '",plylistexist = "' . JText::_('HDVS_MY_PLAYLIST_ALREADY_EXIST_ERROR') . '",plylistrestrict = "' . JText::_('HDVS_RESTRICTION_INFORMATION') . '",plylistnofound = "' . JText::_('HDVS_PLAYLIST_NO_FOUND') . '",plylistavail = "' . JText::_('HDVS_MY_PLAYLIST_AVAILABLE') . '",plylistnameerr = "' . JText::_('HDVS_PLAYLIST_NAME_ERROR') . '";');
  $document->addStyleSheet("//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css");
  $document->addScript(JURI::base() . "components/com_contushdvideoshare/js/playlist.js");
  
  /** Include htmltooltip JS file from component for videoshare modules */
  $document->addScript ( JURI::base () . "components/com_contushdvideoshare/js/htmltooltip.js" );
}

/** Get language direction from component helper for modules */
$langDirection = getLanguageDirection () ;

/** Based on the language include module css file */
if ($langDirection == 1) {
  $rtlLang = 1;
  $document->addStyleSheet ( JURI::base () . 'components/com_contushdvideoshare/css/mod_stylesheet_rtl.min.css' );
} else {
  $rtlLang = 0;
}

/** Get component settings for videoshare modules */
$dispenable = unserialize ( $result1 [0]->dispenable );

/** Get seo option value from settings */
$seoOption  = $dispenable ['seo_option']; 

/** Get video id from request */
if (JRequest::getVar ( 'id' )) {
  $videoid =  JRequest::getVar ( 'id', '', 'get', 'int' );
  $relMore = '&id=' . $videoid; 
} else {
  $videoid =  JRequest::getVar ( 'video' );
  $relMore = '&video=' . $videoid;
}

/** Get total thumb count from module file */
$totalrecords = count ( $result );
/** Check if user is logged in and check if the total records is 0 **/
$flagValue=2;
if(isset($userId) && !empty($userId) && $totalrecords > 0) {
	$flagValue = 1;
}
elseif(isset($userId) && !empty($userId) && $totalrecords == 0){
	$flagValue=3;
}

/** Check module type and set flag */
switch($modType ) {
  case 2:
  	$flag = 1;
  	$modTypeText='popular';
  	break;
  case 0:
  	$flag = 1;
  	$modTypeText='recent';
  	break;
  case 1:
  	$flag = 1;
  	$modTypeText='featured';
  	break;
  case 3:
  	$flag = 1;
  	$modTypeText='random';
  	break;
  case 5:
    $flag = 1;
    $modTypeText='category';
    break;
   case 6:
   	$flag=$flagValue;
   	$modTypeText='watchlater';
   	break;
   case 7:
   	$flag=$flagValue;
   	$modTypeText='watchhistory';
   	break;
  case 4:
    $flag = 0;
    if(isset($videoid) && !empty($videoid)) {
      $flag = 1;
    }
    if($totalrecords <= 1) {
      $flag = 4;
    }
    $modTypeText='related';
    break;
  default:
    $flag = 0;
    $modTypeText='';
    break;
}
$modTypeText='mod'.$modTypeText;
$count = getPlaylistCount ();
if ( $count > 5 ) {
  $scrolClass = ' popup_scroll';
}


/** Module display starts here 
 * Check flag is 1 then display details */
if($flag == 1) { 
?>
<div class="module_menu <?php echo $class; ?> module_videos"> 
  <div class="video-grid-container clearfix"> 
  <?php  $j = 0;
  
  /** Loop through module details */
  for($i = 0; $i < $totalrecords; $i ++) {
if ( isset($result [$i])) {

    if ($i == 0) { ?> 
      <ul class="ulvideo_thumb clearfix"> 
<?php }

    if ($i % $sidethumbview == 0 && $i != 0) { ?> 
      </ul> 
      <ul class="ulvideo_thumb clearfix"> 
 <?php }
    
    /** Get thumb url module videos */ 
    if (isset($result [$i]) && ($result [$i]->filepath == "File" || $result [$i]->filepath == "FFmpeg" || $result [$i]->filepath == "Embed")) {
      if (isset ( $result [$i]->amazons3 ) && $result [$i]->amazons3 == 1) {
        $src_path = $dispenable ['amazons3link'] . $result [$i]->thumburl;
      } else {
        $src_path = JURI::base () . "components/com_contushdvideoshare/videos/" . $result [$i]->thumburl;
      }
    }
    
    if (isset($result [$i]) && ($result [$i]->filepath == "Url" || $result [$i]->filepath == "Youtube")) {
      $src_path = $result [$i]->thumburl;
    }
    
    /** Display mdoule videos as list */ ?> 
      <li class="video-item"> 
        <div class="video_thumb_wrap">
        <?php /** Display thumb image for module videos */ 
          /** Display watch later, add to playlist on search videos thumb */
          displayVideoThumbImage ( $Itemid, $src_path, $result[$i], $modTypeText, $modType );?>
 
			<div id="<?php echo $modTypeText; ?>_playlistcontainer<?php echo  $result[$i]->id; ?>"  class="addtocontentbox" style="display:none">
                  	     <div id="<?php echo $modTypeText; ?>playliststatus<?php echo  $result[$i]->id; ?>" class="playliststatus" style="display:none"></div>
			         <p><?php echo JText::_('HDVS_PLAYLIST_ADD_NE'); ?></p>
                  			<ul id="<?php echo $modTypeText; ?>_playlists<?php echo  $result[$i]->id; ?>" class="playlists_ul<?php echo $scrolClass; ?>"></ul>
			      <?php if( $userId ) {?>
					<div class="no-playlists" id="<?php echo $modTypeText; ?>_no-playlists<?php echo  $result[$i]->id; ?>"></div>
					<div class="create_playlist border-top:2px solid gray;"><button id="<?php echo $modTypeText; ?>_playlistadd<?php echo  $result[$i]->id; ?>" onclick="opencrearesection('<?php echo $modTypeText; ?>',<?php echo  $result[$i]->id; ?>);" class="button playlistadd" ><?php echo JText::_('HDVS_ADDPLAYLIST_LABEL'); ?></button>
                       <div class="addplaylist" id="<?php echo $modTypeText; ?>_addplaylistform<?php echo  $result[$i]->id; ?>" style="display:none">
				       <input type="text" value="" placeholder="<?php echo JText::_('HDVS_PLAYLIST_NAME_ERROR')?>" id="<?php echo $modTypeText; ?>_playlistname_input<?php echo  $result[$i]->id; ?>" class="play_textarea" name="playlistname" autocomplete="off" autofocus="off"  
				       onkeyup="if (event.keyCode != 13) return addplaylist('<?php echo  $result[$i]->id; ?>','<?php echo $modTypeText; ?>');"  
				       onkeydown="if (event.keyCode == 13) document.getElementById('<?php echo $modTypeText; ?>_button-save-home<?php echo  $result[$i]->id; ?>').click()"/>
				       <span id="<?php echo $modTypeText; ?>-playlistresponse-<?php echo  $result[$i]->id; ?>" style="float:left; width:100%;"></span>
				       <input type="button" id="<?php echo $modTypeText; ?>_button-save-home<?php echo  $result[$i]->id; ?>" class="playlistaddform-hide-btn" onclick="return ajaxaddplaylist('<?php echo  $result[$i]->id; ?>','<?php echo $modTypeText; ?>');" value="<?php echo JText::_('HDVS_MY_ADDTO_SAVE_LABEL');?>">
				       <div id="<?php echo $modTypeText; ?>_playlistname_loading-play<?php echo  $result[$i]->id; ?>"></div>
				       </div>
				       </div> 
				       
                          <div id="<?php echo $modTypeText; ?>_restrict<?php echo  $result[$i]->id; ?>" name="<?php echo $modTypeText; ?>_restrict<?php echo  $result[$i]->id; ?>" class="restrict" style="display:none"><p><?php echo  JText::_('HDVS_RESTRICTION_INFORMATION'); ?> <a class="playlist_button" href="<?php	echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists"); ?>"><?php echo JText::_('HDVS_MY_PLAYLIST'); ?></a> </p></div>	       
                        
                       <?php } else { 
                     /** Helper function to display login / register link */    
			        displayLoginRegister ();
                      } ?>
			</div>
        </div> 
        
        <div class="floatleft video-item-details"> 
          <div class="show-title-container title"> 
          <?php /** Display video title for module videos */ ?>
          <a href="<?php echo generateVideoTitleURL ( $Itemid, $result [$i], '' ); ?>" 
          class="show-title-gray info_hover">
<?php  if (strlen ( $result [$i]->title ) > 30) {
      echo JHTML::_ ( 'string.truncate', ($result [$i]->title), 30 );
    } else {
      echo $result [$i]->title;
    } ?> </a> 
        </div> 
        <?php /** Calculate rating values for module videos */  
        if ($dispenable ['ratingscontrol'] == 1) { 
            if (isset ( $result [$i]->ratecount ) && $result [$i]->ratecount != 0) {
              $ratestar = round ( $result [$i]->rate / $result [$i]->ratecount );
            } else {
              $ratestar = 0;
            } 
            /** Display rating for module videos */ ?> 
        <div class="<?php echo $ratearray[$ratestar]; ?> floatleft"></div> 
    <?php } ?> 
    
        <div class="clear"></div> 
        <?php /** Display view count for module videos */ 
        if ($dispenable ['viewedconrtol'] == 1) { ?> 
          <span class="floatleft video-info"><?PHP echo JText::_('HDVS_VIEWS'); ?>: 
          <?php if( isset($result [$i])) { 
            echo $result[$i]->times_viewed; 
          } ?> </span> 
      
      			
      
      <?php } ?>
       
	
	</div>
    </li> 
<?php $j ++; 
}
} ?>
  </ul> 
</div>
</div>

<?php /** Module videos tooltip Starts Here */
for($i = 0; $i < $totalrecords; $i ++) { ?>
  <div class="htmltooltip">
  <?php /** Display description in module videos tooltip */ 
  if ( isset($result [$i]->description)) { ?> 
    <p class="tooltip_discrip"> <?php echo JHTML::_('string.truncate', (strip_tags($result[$i]->description)), 120); ?></p> 
  <?php } 
  
  if ( isset($result [$i])) { 
  /** Display category name in module videos tooltip */
  toolTip ($result[$i]->category,$result[$i]->times_viewed, $dispenable['viewedconrtol'], $i );
  }
   ?> 
  </div>
<?php
} 
/** Module videos tooltip end Here */ 
$t = count ( $result );

if ($t > 1) {
  /** For SEO settings in module videos tooltip */
  if ($modType == 5) {
    if ($seoOption == 1) {
      $CategoryVal = "&category=" . $result [0]->seo_category;
    } else {
      $CategoryVal = "&catid=" . $result [0]->catid;
    }
  } else if($modType == 4) {
    $CategoryVal =  $relMore;
  } else {
    $CategoryVal = '';
  }
  ?>
<div class="clear"></div>
<?php 
/** Check module type is not a random. 
 * Then display more videos link for modules */ 
if( $modType != 3 ) { ?>
<div class="morevideos"> 
  <a  href="<?php echo JRoute::_ ( $moreURL . $CategoryVal ); ?>"
		title="<?php echo JText::_ ( 'HDVS_MORE_VIDEOS' ); ?>">
			<?php echo JText::_('HDVS_MORE_VIDEOS'); ?></a>
</div>
<?php /** More videos Link ends */ 
    }
  } 
  /** Checking flag ends */
} else if($flag==2){ ?>
<span style="text-align: center; display: block;"><?php echo JText::_('HDVS_LOGIN_TO_SEE_SECTION'); ?> </span>
<?php }
else if($flag==3) {
	?><span style="text-align: center; display: block;"><?php echo JText::_('HDVS_NO_VIDEOS_IN_SECTION'); ?> </span><?php
}
 else { 
/** Display no related videos message when flag is 0 */ ?>
<span style="text-align: center; display: block;"><?php echo JText::_('HDVS_NO_RELATED_VIDEOS'); ?> </span>
<?php } ?>

<div class="clear"></div>

<?php /** Tooltip for module video thumbs */ ?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(".ulvideo_thumb").mouseover(function() {
   if(typeof htmltooltipCallback === 'function') {
  htmltooltipCallback("htmltooltip", "",<?php echo $rtlLang; ?>);
  htmltooltipCallback("htmltooltip1", "1",<?php echo $rtlLang; ?>);
  htmltooltipCallback("htmltooltip2", "2",<?php echo $rtlLang; ?>);
   }
});
</script>
