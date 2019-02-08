<?php
/**
 * User playlist add view file
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
$user = JFactory::getUser();
/** Getplaylist id */
$playlistID =  JRequest::getVar("playlist_id");
$playlistName = $parentCategoryID = $description = '' ;
/** Get all playlist details */
if( $playlistID ) {
	/** Get playlist details */
	$playlistDetail = $this->playlistdetails;	
	/** Get playlist name */
	$playlistName = $playlistDetail[0];
	/** Get playlist id */
	$parentCategoryID =  $playlistDetail[1];
	/** Get playlist description */
	$description  =  $playlistDetail[2];
}
/** Get all playlist */
$parentCategories =  $this->parentCategory;
/** Load site settings */
$dispenable =  $this->siteSetting;
/** Get playlist limit */
$playlistLimit = $dispenable['playlist_limit'];  
/** Get playlist count */
$userplaylist =  $this->totalPlaylist;
/** Get item id */
$Itemid = $this->itemId;
/** 
 * Method to call top menu from helper
*/
playlistMenu($Itemid , JUri::getInstance());
?>
<div class="player clearfix" id="clsdetail-add">
     
     <?php
     /** If playlist id exist */
      if( $playlistID ) {  ?>
     <h1> <?php echo JText::_('HDVS_UPDATE_PLAYLIST'); ?></h1>
     <?php } 
     /** If playlist id not exist*/
     else {  ?>
     <h1> <?php echo JText::_('HDVS_ADDPLAYLIST_LABEL'); ?></h1>
     <?php } ?>
</div>
<script type="text/javascript">
		var xmlhttp;
		var nocache = 0;		
     function playlistresponse(){
     }  
	 function playlistexistcheck() {
			var playlistName =  document.getElementById('playlist_name').value;
			playlistName =  playlistName.trim();
			document.getElementById('playlistAjax').innerHTML = "";
			if( playlistName.length >= 1 ) {
                // loading image for check the playlist exists  function    
              	document.getElementById("playlistresponse").innerHTML ="<img src='<?php echo JURI::base();?>components/com_contushdvideoshare/images/loading.gif'/><span><?php echo JText::_('HDVS_WAIT');?></span>" ;
				xmlhttp = createObject();
				if (xmlhttp == null)
				{
					alert("Browser does not support HTTP Request");
					return;
				}
				var url = "<?php echo JURI::base(); ?>index.php?option=com_contushdvideoshare&tmpl=component&task=ajaxPlaylistExists&playlist_name="+playlistName;
				xmlhttp.onreadystatechange = function playlistreponse()
				{
					if (xmlhttp.readyState == 4)
					{
				     	if( xmlhttp.responseText == 1  ) {
					     	if(playlistName == "<?php echo $playlistName; ?>"){ 
					     		document.getElementById('playlistAjax').innerHTML = "";
	                 			document.getElementById("playlistresponse").innerHTML ="";
					     	} else {
					     		document.getElementById('playlistAjax').innerHTML = "";
	                 			document.getElementById("playlistresponse").innerHTML ="<img src='<?php echo JURI::base();?>components/com_contushdvideoshare/images/error.gif'/><span class='error'><?php echo JText::_('HDVS_MY_PLAYLIST_ALREADY_EXIST_ERROR');?></span>";
	                 			return false;
					     	}
                        } else {
                    	 	document.getElementById('playlistAjax').innerHTML = "";
                     		document.getElementById("playlistresponse").innerHTML ="<img src='<?php echo JURI::base();?>components/com_contushdvideoshare/images/success.gif'/><span class='success'><?php echo JText::_('HDVS_MY_PLAYLIST_AVAILABLE');?></span>" ;
                    	}
					}
				};
				xmlhttp.open("GET", url, true);
				xmlhttp.send(null);
		   }
		}
	  function descriptioncheck() {
			var playlistDesc =  document.getElementById('playlistdescription').value;
			playlistDesc =  playlistDesc.trim();
			if(playlistDesc.length > 1){
			 document.getElementById('playlistdescription-error').innerHTML = "";
			}
		}
		function  validatePlaylistAdd() {
			var playlistName =  document.getElementById('playlist_name').value;
			playlistName =  playlistName.trim();
			var playlistdescription =  document.getElementById('playlistdescription').value;
			playlistdescription =  playlistdescription.trim();
			if( playlistName == '' && playlistdescription == '') { 
				document.getElementById('playlistAjax').innerHTML = "<?php echo '<span class=error>'.JText::_('HDVS_MY_PLAYLIST_ERROR_LABEL').'</span>';?>";
				document.getElementById('playlistdescription-error').innerHTML="<?php echo JText::_('HDVS_PLAYLIST_DESCRIPTION_ERROR'); ?>";
				return false;
		    }
			if( playlistName == '') { 
				document.getElementById('playlistAjax').innerHTML = "<?php echo '<span class=error>'.JText::_('HDVS_MY_PLAYLIST_ERROR_LABEL').'</span>';?>";				
				return false;
		    }
		    if( playlistdescription == '') {
			     document.getElementById('playlistdescription-error').innerHTML="<?php echo JText::_('HDVS_PLAYLIST_DESCRIPTION_ERROR'); ?>";
                 return  false;
		    }
	    }
		function  validatePlaylistCancel() {
			window.location.href="<?php echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists");?>";
			 return false;
		}
</script>

<?php 
/** Check playlist exists and playlist limit not exceeded */ 
if( ( $playlistID  ||  $userplaylist < $playlistLimit ) ) {  ?>
<table>
 <form method="post">
  <table class="form" id="userplaylistform" style="width:100%">
  <tr>
	  <td><label><?php
		/** Shows playlist name */
	  echo JText::_('HDVS_PLAYLIST_NAME');?></label></td>
	  <td><input type="text" class="playlistinputbox" id="playlist_name" name="playlistname" autofocus="off" autocomplete="off" onKeyup="return playlistexistcheck();" value="<?php echo $playlistName; ?>" /> <span id="playlistresponse"></span>
	       <span id="playlistAjax"></span></td>
  </tr>
  <tr>
   <td><label><?php
   /** Shows playlist name */
    echo JText::_('HDVS_PLAYLIST_DESCRIPTION');?></label></td>
   <td>
   <textarea class="" rows="5" cols="300" name="playlistdescription"
						id="playlistdescription"
						onKeyDown="CountLeft(this.form.playlistdescription, this.form.left, 300);"
						onpaste="e = this; setTimeout(function(){CountLeft(e, e.form.left, 300);}, 4);"
						onKeyUp="CountLeft(this.form.playlistdescription, this.form.left, 300);descriptioncheck();"><?php 
   /** Shows playlist name */
   echo $description; ?></textarea> 
    <span id="playlistdescription-error"></span></td> 
   </tr> 
   <tr><td><div class="remaining_character">
						<div class="floatleft" style="margin-top: 2px;">
									<?php echo JText::_('HDVS_REMAINING_CHARECHTER'); ?>&nbsp;:&nbsp;</div>
						<div class="commenttxt">
							<input readonly type="text" name="left" size=1 maxlength=8
								value="<?php if(!empty($description)) { 
									echo 300 - strlen($description);
								} else { echo 'else';
echo '300';
}?>" style="border: none; background: none; width: 70px;" />
						</div>
					</div></td>
  <tr>
	  <td>&nbsp;</td>
	  <td><input style="margin-right:10px" type="submit" id="playlistsubmit" name="playlist_addbutton" onClick="return validatePlaylistAdd(this);" />
	  <input type="submit" value="Cancel" id="playlistcancel" name="playlist_cancelbutton" onClick="return validatePlaylistCancel();" /></td>
  </tr>
  </table>
 </form>	
</table>
<?php } 
/** Show restriction info */ 
else  { ?>
	
	<div class="information">
	   <?php
	   /** If no playlist available */
	    echo JText::_('HDVS_RESTRICTION_INFORMATION'); ?> 
	   <a class="myplaylistlink" href="<?php echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=myplaylists");?>"><?php echo JText::_('HDVS_MY_PLAYLISTS');  ?></a></div>
<?php }?>
