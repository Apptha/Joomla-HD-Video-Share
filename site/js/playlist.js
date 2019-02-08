/**
 * Playlist JS for HD Video Share
 *
 * This file will hold playlist script code.
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

function opencrearesection(containerType, videoId){
	jQuery( "#"+containerType+'_playlistadd'+videoId ).hide();
	jQuery( "#"+containerType+'_addplaylistform'+videoId ).show();
	jQuery( "#"+containerType+'_playlistname_input'+videoId ).focus();
	document.getElementById(containerType+"_button-save-home"+videoId).disabled = true;
}
function openplaylistpopup(containerType, videoId) { 
	jQuery(".addtocontentbox").hide("fast");
	jQuery( "#"+containerType+'_playlistname_input'+videoId ).val('');
	jQuery( "#"+containerType+'-playlistresponse-'+videoId ).html('');
	jQuery( "#"+containerType+'_addplaylistform'+videoId ).hide();	
    var requesturl = baseurl+"index.php?option=com_contushdvideoshare&tmpl=component&task=videoPlaylists";
    jQuery.ajax({
        url:requesturl,
        type:"POST",
        data:"&vid="+videoId+"&containerType="+containerType,
        success : function( result ){ 
        	document.getElementById(containerType+"_playlists"+videoId).innerHTML = result ;
        	
        	if(jQuery(result).filter('li').size() == 0){
        		jQuery( "#"+containerType+'_no-playlists'+videoId ).html(plylistnofound).show();
        	}
        	else if(jQuery(result).filter('li').size() >= playlistlimit){
        		jQuery( "#"+containerType+'_playlistadd'+videoId ).hide("fast");
        		jQuery( "#"+containerType+'_restrict'+videoId ).show();
        	}
        	else{
        		jQuery( "#"+containerType+'_playlistadd'+videoId ).show();
        		jQuery( "#"+containerType+'_no-playlists'+videoId ).hide();
        		jQuery( "#"+containerType+'_restrict'+videoId ).hide("fast");
        	}
        	if(jQuery(result).filter('li').size() > 5) {
            document.getElementById(containerType+'_playlists'+videoId).className = 'playlists_ul popup_scroll'              
         }
        	}
    });    
    jQuery( "#"+containerType+'_playlistcontainer'+videoId ).show();
}
function openaddplaylist(container,id){
	jQuery( "#"+'myplaylist_playlistcontainer' ).show();
	document.getElementById(container+"_button-save-home"+id).disabled = true;
}
jQuery(document).mouseup(function (e) {
    var popup = jQuery(".addtocontentbox");
    if (!popup.is(e.target) && popup.has(e.target).length == 0) {
        popup.hide();
    }
});
function addVideoToplaylist(checkboxvalue,containerType, videoId , playlistId) {
	var requesturl;
	if(checkboxvalue.checked == true ) {
		requesturl = baseurl+"index.php?option=com_contushdvideoshare&task=addvideoPlaylist";
	} else {
		requesturl = baseurl+"index.php?option=com_contushdvideoshare&task=removevideoPlaylist";
	}

	jQuery.ajax({
        url:requesturl,
        type:"POST",
        data:"cat_id="+ playlistId+"&vid="+videoId,
        success : function( result ){
        	if( result == 1  ) { 
        		jQuery("#"+containerType+'playliststatus'+videoId).html(plylistadd).show().delay(1000).fadeOut().css({"margin-left":"45px","background":"#32AE08"});				
			} else if( result == 2  ) {
				jQuery("#"+containerType+'playliststatus'+videoId).html(plylistremove).show().delay(1000).fadeOut().css({"margin-left":"30px","background":"#EE3324"});
			} else if( result == 0  ) {
				jQuery("#"+containerType+'playliststatus'+videoId).html(plylistpblm).show().delay(1000).fadeOut().css({"margin-left":"30px","background":"#EE3324"});
			}
        }
	 });
}
// Function add playlist if user  wants
function addplaylist(id,container) {
	var playlistName =  document.getElementById(container+'_playlistname_input'+id).value;
	playlistName =  playlistName.trim();
	if( playlistName ) {
		
		document.getElementById(container+"-playlistresponse-"+id).innerHTML ="<img src='"+baseurl+"components/com_contushdvideoshare/images/loading.gif' align='center'/><span>"+hdvswait+"</span>" ;

		var requesturl = baseurl+"index.php?option=com_contushdvideoshare&task=ajaxPlaylistExists";
		jQuery.ajax({
			url:requesturl,
			type:"POST",
			data:"playlist_name="+ playlistName+"&tmpl=component",
			success : function( result ){
				if( result == 1  ) {
					document.getElementById(container+"-playlistresponse-"+id).innerHTML ="<img src='"+baseurl+"components/com_contushdvideoshare/images/error.gif' /><span class='error-msg'>"+plylistexist+"</span>";
					document.getElementById(container+"_button-save-home"+id).disabled = true;
				} else if( result == 2  ) {
					document.getElementById(container+"-playlistresponse-"+id).innerHTML ="<img src='"+baseurl+"components/com_contushdvideoshare/images/error.gif'/><span class='error-msg'>"+plylistrestrict+"</span>" ;
				} else {
					document.getElementById(container+"-playlistresponse-"+id).innerHTML ="<img src='"+baseurl+"components/com_contushdvideoshare/images/success.gif'/><span class='success-msg'>"+plylistavail+"</span>" ;
					document.getElementById(container+"_button-save-home"+id).disabled = false;
				}
			}
		});
	}
	else{
		document.getElementById(container+"-playlistresponse-"+id).innerHTML = "";
		document.getElementById(container+"_button-save-home"+id).disabled = true;
	}
}
function ajaxaddplaylist(id,containerType) {
	 var xmlhttp;
	 document.getElementById(containerType+'-playlistresponse-'+id).innerHTML ="";
	 var playlistName =  document.getElementById(containerType+'_playlistname_input'+id).value;
		playlistName =  playlistName.trim();
	 var playlistdescription = playlistName;
	 var requesturl = baseurl+"index.php?option=com_contushdvideoshare&task=ajaxplaylistadd";
		jQuery.ajax({
			url:requesturl,
			type:"POST",
			data:"playlist_name="+ playlistName+"&tmpl=component"+"&description="+playlistdescription+"&vid="+id+"&container="+containerType,
			success : function( result ){ 
				if( result == 1  ) {
					document.getElementById(containerType+"-playlistresponse-"+id).innerHTML =plylistexist ;
            		document.getElementById(containerType+"_button-save-home"+id).disabled = true;
            		
				} else if( result == 2  ) {
					document.getElementById(containerType+"-playlistresponse-"+id).innerHTML = plylistpblm ;
				}
				 else if( result == 3 ) {
						document.getElementById(containerType+"-playlistresponse-"+id).innerHTML =plylistexist ;
					}
				 else if( result == 4 ) {
					 location.reload();
					}
				 else {
                	document.getElementById(containerType+"_playlists"+id).innerHTML =result ;
                	jQuery("#"+containerType+'playliststatus'+id).html(plylistaddvideo).show().delay(1000).fadeOut().css({"margin-left":"45px","background":"#90C140"});
    				jQuery( "#"+containerType+'_no-playlists'+id ).hide();
                	jQuery( "#"+containerType+'_playlistname_input'+id ).val('');
    				jQuery( "#"+containerType+'_addplaylistform'+id ).hide();
    				jQuery( "#"+containerType+'_playlistcontainer'+id ).delay(1000).fadeOut();
    				if(jQuery(result).filter('li').size() >= playlistlimit){
		        		jQuery( "#"+containerType+'_playlistadd'+id ).hide("fast");
		        		jQuery( "#"+containerType+'_restrict'+id ).show();
		        		}
    				else{
    					jQuery( "#"+containerType+'_playlistadd'+id ).show();
    				}
 				} 
				if(jQuery(result).filter('li').size() > 5) {
				   document.getElementById(containerType+'_playlists'+id).className = 'playlists_ul popup_scroll' 				   
				}
			}		
		});
 }