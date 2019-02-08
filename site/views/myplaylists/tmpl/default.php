<?php
/**
 * User video view file
 *
 * This file is to display logged in user videos
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
/** Get user details */
$userId = (int) getUserID();
/** Get all playlist details */
$myplaylists = $this->myplaylists;
/** load model */
$model = $this->model;
/** Get item id */
$Itemid = $this->itemId;
/** Get site settings */
$settingDetails = $this->siteSetting;
/** Get seo options */
$seoOption = $settingDetails['seo_option'];
/** Get playlist limit settings */
$playlistLimit =  $settingDetails['playlist_limit'];
/** 
 * Method to call top menu from helper
 */
playlistMenu($Itemid, JUri::getInstance()); 
$myPlaylistsURL = "index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists";
 ?>
<div class="player clearfix myplay_lists" id="clsdetail">
 <div class="playlist_title"><h1 style="margin-left: 28px !important;margin-top: 18px !important;"> <?php echo JText::_('HDVS_MY_PLAYLISTS'); ?></h1></div>
</div>
<?php 
 /**
  *  Showing Add playlist link in my playlists page
  *  It will be shown playlist limit not reached 
  */
 if( count( $myplaylists) < $playlistLimit ) { ?>
		<div id="add-to-playlist" class="myplay_list_popup">
				<a href="javascript:void(0)" onclick="return openaddplaylist('myplaylist',0);" class="youtube-addto-link-player" id="add_playlist_button">
		        <span style="display:block!important;" class="myplaylistimg-plus"><?php echo JText::_('HDVS_ADD_MY_PLAYLISTS');  ?></span>
		         </a>
		        <div class="addtocontentbox" id="myplaylist_playlistcontainer"  style="display:none">
	                  	     <div class="playliststatus" id="myplaylistplayliststatus" style="display:none"></div>
					     <?php 
					     /** Check user id is exists */
					     /**
					      * If user id available
					      * Show playlists 
					      */
					     if( $userId ) {
									/** Show playlists */?>
	                       <div id="myplaylist_addplaylistform" class="addplaylist"  style="display:block">
						       <?php /** Show create playlists */?>
						       <input type="text" id="myplaylist_playlistname_input0" value="" placeholder="<?php echo JText::_('HDVS_PLAYLIST_NAME_ERROR')?>" class="play_textarea" name="playlistname" autocomplete="off" autofocus="off" onkeyup="if (event.keyCode != 13) return addplaylist(0,'myplaylist');" onkeydown="if (event.keyCode == 13) document.getElementById('myplaylist_button-save-home0').click()"/>
						      <?php /**
									* Response text 
									* PlaylistAvailable , Exists
									 */?>
						       <span class="myplaylist_playlist_response" id="myplaylist-playlistresponse-0" ></span>
						        <?php /** Submit button  */?>
						       <input type="button" id="myplaylist_button-save-home0" class="playlistaddform-hide-btn" onclick="return ajaxaddplaylist(0,'myplaylist');" value="<?php echo JText::_('HDVS_MY_ADDTO_SAVE_LABEL');?>">
						        <?php /** Loading gif */?>
						       <div id="myplaylist_playlistname_loading-play0"></div> </div>
					        <div id="myplaylist_restrict" name="myplaylist_restrict"  class="restrict" style="display:none"><p><?php echo  JText::_('HDVS_RESTRICTION_INFORMATION'); ?> <a class="playlist_button" href="<?php	echo JRoute::_( $myPlaylistsURL ); ?>"><?php echo JText::_('HDVS_MY_PLAYLIST'); ?></a> </p></div>	       
	                       <?php } else { 
	                       /** If user id not exist show login register */
	                       /** 
					         * Set URL for login page and  register page
					         * @param $login_url login url
					         * @param $register_url register url
					         */
	                         displayLoginRegister(); 
	                       } ?>
					</div></div>
					<div style="clear: both;"></div>
		        
 <?php } ?>
<div id="delete_success_message"> </div>
<div class="myplaylist_table_container">
<table class="myplaylist-table">
    <thead>
    <?php  /**
     *Playlist Thumb
     *Playlist name
     *Playlist description
     *Playlist status
     *Playlist Actions play, view, edit, delete
     */ ?>
	     <tr>
	         <th width="20%"><?php echo JText::_('HDVS_PLAYLIST_IMAGE'); ?></th>
		     <th width="15%"><?php echo JText::_('HDVS_PLAYLIST_NAME'); ?></th>
			 <th width="25%"> <?php echo JText::_('HDVS_PLAYLIST_DESC'); ?></th>  
			 <th width="5%"> <?php echo JText::_('HDVS_PLAYLIST_COUNT'); ?></th>
			 <th width="35%"> <?php echo JText::_('HDVS_PLAYLIST_ACTIONS'); ?></th>   
	     </tr>
     </thead>
	<?php 
	 /** Check user playlist is exists */
	 if( $myplaylists) {
		  foreach($myplaylists as $playlist) { 
			/** Set playlist category id */
            $image = Modelcontushdvideosharemyplaylists::getthumbimage($playlist->id);
?>
			 <tr class="playlistrows">
			 <?php
			 /** 
			  * Show thumb image if exists
			  *  Shows first added video thumb image
			  */
			 if(!empty($image)){ 
if(strpos($image[0]->thumburl,'/')) {?>
			 <td><img src="<?php echo $image[0]->thumburl?>" height="50" width="100" ></td>
			 <?php } else{ ?>
			 <td><img src="<?php echo JURI::base(); ?>components/com_contushdvideoshare/videos/<?php echo $image[0]->thumburl?>" height="50" width="100" ></td>
              <?php  }
              } else {
              /** Show defaut image when thumb not available */?>
			  <td><img src="<?php echo JURI::base(); ?>components/com_contushdvideoshare/images/MyPlaylistButton.jpg" height="50" width="100" ></td>
			 <?php }?>
				 <td><?php
				 /** Show First letter in upper case in Playlist name */
				  echo ucfirst( $playlist->category ); ?> </td>
				  <td class="myplaylist_description"><?php
				   /** Show First letter in upper case in Description */
				   echo ucfirst( $playlist->description ); ?> </td>
				<td class="center"><?php 
				  /** Show videos count */
				echo $playlist->count;
?> </td>
				 <td class="action">
				<?php if(!empty($image)) {?>
				<a href="<?php  if ($seoOption == 1){
					/** Set playlist value */
					$playlistValue = "playlist=" . $playlist->seo_category;
					/** category value */
					$catgoryVideo = "play=1";
					/** Set video id */
					$videid = "video=".$image[0]->seotitle;
				} else{
					/** Set playlist id */
					$playlistValue = "playid=" . $playlist->id;
					/** Set playlist category id */
					$catgoryVideo = "play=1";
					/** Set video id */
					$videid = "id=".$image[0]->id;
				} 
				/** Link for viewing videos in playlist */
				echo JRoute::_("index.php?option=com_contushdvideoshare&view=player&" .$playlistValue. "&" .$videid. "&" .$catgoryVideo );?>" 
				title="<?php echo JText::_('HDVS_VIEWS');?>" class="showicon">
				<?php echo JText::_('HDVS_PLAYLIST_PLAY');?></a> | 
				<?php } ?>
				
				<a  href="<?php  if ($seoOption == 1){
					/** Set playlist Category name */
					$playlistValue = "playlist=" . $playlist->seo_category;
				}else{
					/** Set playlist Category id */
					$playlistValue = "playid=" . $playlist->id;
				} 
				/** Link for EDIT and DELETE playlist */
				echo JRoute::_("index.php?option=com_contushdvideoshare&view=playlist&" .$playlistValue );?>" title="<?php echo JText::_('HDVS_VIEWS');?>" class="showicon"><?php echo JText::_('');?><?php echo JText::_('HDVS_VIEW');?></a> | <a  OnClick="return delete_playlist(<?php echo $playlist->id;?>);" title="<?php echo JText::_('HDVS_DELETE');?>" class="crossicon" href="<?php echo JRoute::_( $myPlaylistsURL . "&delete_id=".$playlist->id);?>"><?php echo JText::_('HDVS_DELETE');?></a> | <a href="<?php echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=addplaylist&playlist_id=".$playlist->id); ?>" class="pencilicon" title="<?php echo  JText::_('HDVS_EDIT');?>"><?php echo JText::_('HDVS_EDIT');?></a></td>
			 </tr>
	<?php }
        } else  { 
			/** Show message when playlists is empty */ ?>
		 <tr>
		      <td colspan="5"><?php echo JText::_('HDVS_NOPLAYLIST_MESSAGE'); ?></td>
		 </tr>        
	<?php } ?>
	</table>
	</div>
