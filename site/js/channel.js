/**
 * Basic and image file upload javascript functions file for channel functionality 
 *
 * This file script is used for basic channel operations like ajax, etc. and image process operations like cropping, uploading, etc.
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

function uploadImage(formData,uploadType,ui,view) {
   var componentpath = baseurl+"index.php?option=com_contushdvideoshare";
	if(view=="subscriber") {
		var subid=subscriberId;
	}
	if(uploadType == 'cover') {
		if(view=="channel") {
			var url = componentpath + "&task=imageUpload&uploadType="+uploadType+"&ui="+ui;
		} else {
			var url = componentpath + "&task=saveSubscriperImage&uploadType="+uploadType+"&ui="+ui+"&subid="+subid;
		}
	}
	else {
		var bannerwidth = jQuery('.bannerContainer')[0].offsetWidth;
		if(view=="channel") {
			var url = componentpath + "&task=imageUpload&uploadType="+uploadType+"&ui="+ui+"&bannerWidth="+bannerwidth;
		} else {
			var url = componentpath + "&task=saveSubscriperImage&uploadType="+uploadType+"&ui="+ui+"&bannerWidth="+bannerwidth+"&subid="+subid;
		}
	}
	jQuery.ajax({
		xhr: function() {
			 var ua = window.navigator.userAgent;
				var msie = ua.indexOf("MSIE ");
			    if(msie < 0) {
			       var xhr = new XMLHttpRequest();
			    } else {
			    	var xhr = new window.XMLHttpRequest();
			    }
			    if(msie < 0) {
			    //Upload progress
			    xhr.upload.addEventListener("progress", function(evt){
			      if (evt.lengthComputable) {
			        var percentComplete = (evt.loaded / evt.total) * 100;
			        //Do something with upload progress
					jQuery('.loadingBar').show();
					jQuery('.loadingBar').focus();
			        jQuery('.loadingBar').css('width',percentComplete+'%');
			      }
			    }, false);
			    //Download progress
			    xhr.addEventListener("progress", function(evt){
			      if (evt.lengthComputable) {
			        var percentComplete = evt.loaded / evt.total;
			      }
			    }, false);
			    }
			    return xhr;
		  },
    url: url,
    type: "POST",
    data: formData,
    success: function (msg) {
    	jQuery('.loadingBar').show();
    	jQuery('.loadingBar').css('width','0%');
    	var obj = jQuery.parseJSON( msg );
        var imageUrl,imageWidth,imageHeight,errorMsg,uploadType;
        imageUrl = baseurl+'images/channel/banner/' + obj.imageName; 
        imageHeight = obj.imageHeight;
        imageWidth  = obj.imageWidth;
        errorMsg    = obj.errormsg;
        uploadType  = obj.uploadType;
        if(view=="channel") {
	        if(obj.errmsg == '404' && errorMsg == 'true') {
	            var redirectUrl;
	            redirectUrl = baseurl;
	            window.location =  redirectUrl;
	            return;
	        }
        }
            
        if(errorMsg == 'true') {
        	 jQuery('.fileContent').val('');
        	 alert(obj.errmsg);
        	 return;
        }
        if(uploadType == 'cover' && errorMsg == 'false') {
            jQuery('.cropContainer').css('width',imageWidth);
            jQuery('.cropContainer').css('height',imageHeight);
            jQuery('.cropContainer').css('background-image','url('+imageUrl+')');
            jQuery('.bannerContainer').css('background','ghostwhite');
            jQuery('.dragContainer').hide();
            jQuery('.profileDragContainer').hide();
            jQuery('.cropContainer').show();
            jQuery('.dragButtonContainer').hide();
            jQuery('.saveButtonContainer').show();
            jQuery('.channel_dragreposition').show();
            jQuery('.fileContent').val('');
        }
        if(uploadType == 'profile' && errorMsg == 'false') {
            jQuery('.profileDragContainer').css('width',imageWidth);
            jQuery('.profileDragContainer').css('height',imageHeight);
            jQuery('.profileDragContainer').css('background-image','url('+imageUrl+')');
            jQuery('.bannerContainer').css('background','ghostwhite');
      		jQuery('.dragContainer').hide();
           	jQuery('.cropContainer').hide();
           	jQuery('.saveButtonContainer').hide();
           	jQuery('.profileDragContainer').show();
           	jQuery('.dragButtonContainer').show();
   			jQuery('.dragBox').show();
   			if(view=="channel") {
   				jQuery('.channel_dragreposition').show();
            }
           	jQuery('.fileContent').val('');
        }
		jQuery('.bannerContainer').css('height','250px');
    },
    cache: false,
    contentType: false,
    processData: false
});	
}

function cropImage(cx,cy,cw,ch,view) {
	var ajaxURL=baseurl+"index.php?option=com_contushdvideoshare&task=croppingCoverImage";  
	jQuery.ajax({
		xhr: function() {
					 var ua = window.navigator.userAgent;
						var msie = ua.indexOf("MSIE ");
					    if(msie < 0) {
					       var xhr = new XMLHttpRequest();
					    } else {
					    	var xhr = new window.XMLHttpRequest();
					    }
					    if(msie < 0) {
					    //Upload progress
					    xhr.upload.addEventListener("progress", function(evt){
					      if (evt.lengthComputable) {
					        var percentComplete = (evt.loaded / evt.total) * 100;
					        //Do something with upload progress
							jQuery('.loadingBar').show();
							jQuery('.loadingBar').focus();
					        jQuery('.loadingBar').css('width',percentComplete+'%');
					      }
					    }, false);
					    //Download progress
					    xhr.addEventListener("progress", function(evt){
					      if (evt.lengthComputable) {
					        var percentComplete = evt.loaded / evt.total;
					      }
					    }, false);
					    }
					    return xhr;
		  },
    url: ajaxURL,
    type: "POST",
    data: '&cx='+cx+'&cy='+cy+'&cw='+cw+'&ch='+ch,
    success: function (msg) {
    	var obj = jQuery.parseJSON( msg );
    	if(view=="channel") {
    		errorMsg    = obj.errormsg;
        	try {
        	   if(obj.errmsg == '404' && errorMsg == 'true') {
                var redirectUrl;
                redirectUrl = baseurl;
                window.location =  redirectUrl;
                return;
            }
        	}catch(e) { }
      }
      var cimageUrl;
      cimageUrl = baseurl+'images/channel/banner/' + obj.uploadType+'/'+obj.image;
      if(obj.uploadType == 'profile') {
        jQuery('.profileImages').attr('src',cimageUrl);
      }
      if(obj.uploadType == 'cover') {
            jQuery('.coverImages').attr('src',cimageUrl);
      }
      jQuery('.fileContent').val('');    
      jQuery('.dragBox').css('top','0px');
 		jQuery('.dragBox').css('left','0px');
		jQuery('.cropContainer').css('top','0px');
		jQuery('.cropContainer').css('left','0px');
		jQuery('.profileDragContainer').css('top','0px');
		jQuery('.profileDragContainer').css('left','0px');
    	jQuery('.profileUpload').hide();
    	jQuery('.coverUpload').hide();
    	jQuery('.cropContainer').hide();
    	jQuery('.dragContainer').hide();
		jQuery('.dragBox').hide();
    	jQuery('.profileDragContainer').hide();
    	jQuery('.saveButtonContainer').hide();
    	jQuery('.channel_dragreposition').hide();
		jQuery('.loadingBar').hide();
		jQuery('.loadingBar').css('width','0%');
    	jQuery('.coverContainer').show();
    	jQuery('.profileContainer').show();
    	jQuery('.fileContent').val('');
    }
	});	
}

function channelMyVideos ( buttonClicked, current, currentElement, view) {  
	var url,searchName,postData,description;
	if(view=="subscriber") {
		var subid = subscriberId;
   }
	if(buttonClicked == 'myVideosButton') {
		if(view == "subscriber") {
        	url = baseurl+'index.php?option=com_contushdvideoshare&task=subscripeMyVideos&tmpl=component';
    		postData='&subid='+subid;
    		if(document.getElementById("myVideosButton")) {
             document.getElementById("myVideosButton").className = "myVideosButton active";
             if(document.getElementById("mySubscriptionButton"))
                document.getElementById("mySubscriptionButton").className = "mySubscriptionButton";
             if(document.getElementById("aboutButton"))
             document.getElementById("aboutButton").className = "aboutButton";
    		}
      }		
	} else if(buttonClicked == 'searchButton') {
		searchName = document.getElementsByClassName('search');
		searchName = searchName[0].value;
		if(view=="channel") {
			url = baseurl+'index.php?option=com_contushdvideoshare&task=channelMyVideos&tmpl=component';
			postData = '&videoSearch='+searchName;
        } else {
        	url = baseurl+'index.php?option=com_contushdvideoshare&task=subscripeMyVideos&tmpl=component';
    		postData = '&videoSearch='+searchName+'&subid='+subid;
        }
	} else if(buttonClicked == 'saveDescription') {
		description = jQuery('.channelDescription').val();
		userName    = jQuery('.userName').val();
		if(view=="channel") {
			url = baseurl+'index.php?option=com_contushdvideoshare&task=channelDescription&tmpl=component';
			postData = '&channelDescription='+description+'&userName='+userName;
        } else {
        	url = baseurl+'index.php?option=com_contushdvideoshare&task=saveSubscriperDescription&tmpl=component';
    		postData = '&channelDescription='+description+'&subid='+subid+'&userName='+userName;
        }
	} else if(buttonClicked == 'browseChannelButton') {
		url = baseurl+'index.php?option=com_contushdvideoshare&task=subscriperDetails&tmpl=component';
		postData = '';		
	}
	
	if(buttonClicked == 'searchChannelButton') {
		searchName = jQuery('.search').val();
		url = baseurl+'index.php?option=com_contushdvideoshare&task=subscriperDetails&tmpl=component';
		postData = '&videoSearch='+searchName;
	}
	if(buttonClicked == 'subscripeLinkButton') {
		url = baseurl+'index.php?option=com_contushdvideoshare&task=saveSubscriper&tmpl=component';
		postData = '&sid='+current;
	}
	if(buttonClicked == 'subButton') {
		url = baseurl+'index.php?option=com_contushdvideoshare&task=saveSubscriper&tmpl=component';
		postData = '&sid='+current;
	}
	if(buttonClicked == 'mySubscriptionButton') {
		if(view=="channel") {
   			url = baseurl+'index.php?option=com_contushdvideoshare&task=mySubscriperDetails&tmpl=component';
   			postData = '&sid='+current;
   			if(document.getElementById("ch_mySubscriptionButton")) {
               document.getElementById("ch_mySubscriptionButton").className = "mySubscriptionButton active";
               if(document.getElementById("ch_aboutButton"))
                  document.getElementById("ch_aboutButton").className = "aboutButton";
            }
        } else {
           	url = baseurl+'index.php?option=com_contushdvideoshare&task=getMySubscriperDetails&tmpl=component';
       		postData = '&subid='+subid+'&sid='+current;
       		if(document.getElementById("mySubscriptionButton")) {
               document.getElementById("mySubscriptionButton").className = "mySubscriptionButton active";
            if(document.getElementById("myVideosButton"))
               document.getElementById("myVideosButton").className = "myVideosButton"; 
            if(document.getElementById("aboutButton"))
               document.getElementById("aboutButton").className = "aboutButton";
           }
        }
	}
	if(buttonClicked == 'closeSubscripe') {
		  if(view=="channel") {
			url = baseurl+'index.php?option=com_contushdvideoshare&task=closeSubscripe&tmpl=component';
			postData = '&msid='+current;
        } else {
        	url = baseurl+'index.php?option=com_contushdvideoshare&task=closeSubscripeDetail&tmpl=component';
    		postData = '&subid='+subid+'&msid='+current;
        }		
	}	
	if(buttonClicked == 'deleteNotification') {
		url = baseurl+'index.php?option=com_contushdvideoshare&task=deleteNotification&tmpl=component';
		postData = '';
	}
	if(buttonClicked == 'subDeleteButton') {
		url = baseurl+'index.php?option=com_contushdvideoshare&task=deleteNotification&tmpl=component';
		postData = '&delId='+current;
	}
	
	jQuery.ajax({		
    url: url,
    type: "POST",
    data: postData,
    success: function (myvideos) { 
    	if(view=="channel") {
	    	try {
	    		var obj = jQuery.parseJSON( myvideos );
	    		errorMsg    = obj.errormsg;
	        	if(obj.errmsg == '404' && errorMsg == 'true') {
	                var redirectUrl;
	                redirectUrl = baseurl;
	                window.location =  redirectUrl;
	                return false;
	            }
	    	} catch(e) { }
        }
    	
        if(buttonClicked == 'saveDescription') {
        	 try {
        		 if(view=="subscriber") {
        			 var obj = jQuery.parseJSON( myvideos );
             }
        		 errorMsg    = obj.errormsg;
        		 if(errorMsg == 'true') {
                     alert(obj.errmsg);
                     jQuery('.userName').focus();
                     return false;
             } else {
                 	alert(obj.errmsg);
             }
        	 } catch(e) { }
     	   jQuery('.aboutButton').css('left','10px');
     	   jQuery('.authorHeading').text(jQuery('.userName').val());
      	jQuery('.aboutButton').css('background','rgb(250, 118, 87) !important');  	   
         return false;
        }
        if(buttonClicked == 'deleteNotification') {
                 jQuery('.notifi').hide();
                 jQuery('.notificationContainer').hide();
                 jQuery('.notificationContainers').hide();
                 jQuery('.myVideosButton').click();
                 return false;
        }
        if(buttonClicked == 'subscripeLinkButton') {
           	var scount;
           	if(myvideos == 'error') {
           	 return false;
           	} else {
               jQuery('.subscripeContainer').html('');
               jQuery('.subscripeContainer').html(myvideos);
               jQuery('.subscripRow').length;
              	scount = Number(jQuery('.subscriptionCount').text());
              	jQuery('.subscriptionCount').text(scount + 1);
              	return false;
           	} 
        }
        if(buttonClicked == 'subDeleteButton') {
            if(myvideos == 'error') {
                return false;
            } else {
            	scount = Number(jQuery('.ncount').text());
            	jQuery('.ncount').text(scount - 1);
   				countValue = scount - 1;
   				delIdValue = currentElement.parent().parent().attr('id');
   				delClassValue = currentElement.parent().parent().attr('class');
   				jQuery('.notificationLis#' + delIdValue).hide();
   				jQuery('.notificationLi#' + delIdValue).hide();
            	if(scount < 5) {
                    jQuery('.seeMoreLink').hide();
               }
            	if(countValue != 0 ) {
   					if(delClassValue == 'notificationLis') {
   						jQuery('.notificationContainers').show();
   					}            		
            	} else {
            		jQuery('.notificationContainers').hide();
            		jQuery('.notificationContainer').hide();
            		jQuery('.notifi').hide();
            	}
            	return false;
            }            
        }
        if(buttonClicked == 'subButton') {
        	var scount;
        	if(myvideos == 'error') {
        	 return false;
        	} else {
            	var mysubsDisplayValue,subsDisplayValue;
            	mysubsDisplayValue = jQuery('.mysubscriptionContainer').attr('style');
            	subsDisplayValue   = jQuery('.subscripeContainer').attr('style');
            	if(mysubsDisplayValue.indexOf('block') != -1) {
            	    scount = Number(jQuery('.subscriptionCount').text());
            	    jQuery('.subscriptionCount').text(scount + 1);
            	    currentElement.hide();	    
            	    jQuery('.mySubscriptionButton').click();
            	    notificationClassValue = currentElement.parent().parent().attr('class');
            	    if( notificationClassValue == 'notificationLis') {
            	       jQuery('.notificationContainers').show();
            	    }
            	    return;
               }
            	if(subsDisplayValue.indexOf('block') != -1) {
            		jQuery('.subscripeContainer').html('');
            		jQuery('.subscripeContainer').html(myvideos);
            	    scount = Number(jQuery('.subscriptionCount').text());
            	    jQuery('.subscriptionCount').text(scount + 1);
            	    currentElement.hide();
            	    return;
            	}
           	   scount = Number(jQuery('.subscriptionCount').text());
           	   jQuery('.subscriptionCount').text(scount + 1);
               notificationIdValue = currentElement.parent().parent().attr('id');
   			   notificationClassValue = currentElement.parent().parent().attr('class');
           	   jQuery('#' + notificationIdValue + ' .subButton').hide();
               if( notificationClassValue == 'notificationLis') {
   					jQuery('.notificationContainers').show();
   				}		    
               return;
        	} 
        }
        if(buttonClicked == 'closeSubscripe') {
        	var scount;
        	if(myvideos == 'error') {
            	return;
        	} else {
            jQuery('.mysubscriptionRow').html('');
            jQuery('.mysubscriptionRow').html(myvideos);
           	scount = Number(jQuery('.subscriptionCount').text()); 
           	jQuery('.subscriptionCount').text(scount - 1);
            return;
        	} 
        }        
        if(buttonClicked == 'browseChannelButton' || buttonClicked== 'searchChannelButton') {
        	   if(view=="channel") {
           		jQuery('.search').val('');
           		jQuery("#dynamicplacholder").attr("placeholder", "Search Channel");
            }
            jQuery('.subscripeContainer').html('');
            jQuery('.subscripeContainer').html(myvideos);
            jQuery('.subscripRow').length;
            return;            
        }
        if(buttonClicked == 'mySubscriptionButton') {
            jQuery('.mysubscriptionRow').html('');
            jQuery('.mysubscriptionRow').html(myvideos);
            return false;  
        }
        if(buttonClicked != 'searchButton')  {
    	   jQuery('.search').val('');
    	   jQuery('.'+buttonClicked).css('left','10px');
    	   jQuery('.'+buttonClicked).css('background','rgb(250, 118, 87)');
       }
       else {
    	   jQuery('.channelMenuContainer p').css('left','0px');
    	   jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');
       }   
       jQuery('.videoContent').html('');
       jQuery('.videoContent').html(myvideos);
       if(view=="channel") {
    	   jQuery("#dynamicplacholder").attr("placeholder", "Search");
       }
       return false;
    }
});		
}