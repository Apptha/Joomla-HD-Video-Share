/**
 * Javascript functions file for popup video player of channel functionality 
 *
 * This file script is used for operations of popup video player in channel pages.
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

jQuery(document).ready(function() {
      jQuery('.playerContainer').hide();
      jQuery('.subscripeContainer').hide();
      jQuery('.mysubscriptionContainer').hide();
      
      var totalChild = jQuery('.notificationParent').children().length;
      var totalChildHeight = 0;
      if(totalChild >= 4) {
         jQuery('.notificationParent').children().each(function(c) {
             if(c==3) {
                 return false;
             }
             totalChildHeight += this.offsetHeight;
         }); 
         notificationContainerHeight = totalChildHeight + 31;
         notificationRowHeight       = totalChildHeight;
         jQuery('.notificationRows').css('max-height',notificationRowHeight+'px');
      }
      
      jQuery('.notificationContainers').mouseleave(function(){
      	jQuery(this).hide();
      });
      
      jQuery('body').click(function(e){
      	if(e.target.className != 'notificationLink' && e.target.className != 'notificationLis' ) {
      		jQuery('.notificationContainers').hide();
      	}
      });
      jQuery('.notificationContainers').hide();
      jQuery('.notificationContainer').hide();
      jQuery('.searchChannelButton').hide(); 
      
      jQuery('.myVideosButton').click(function(){
          jQuery('.searchButton').show();
          jQuery('.searchChannelButton').hide();
          jQuery('.search').val('');
          jQuery('.aboutContainer').hide();
          jQuery('.subscripeContainer').hide();
          jQuery('.mysubscriptionContainer').hide();
          jQuery('.notificationContainer').hide();
          jQuery('.videoContainer').show();
          jQuery('.channelMenuContainer p').css('left','0px');
          jQuery('.channelMenuContainer p').css('color','rgb(89, 85, 85)');
          jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');
          jQuery(this).css('color','white');
          var currentView=jQuery(this).data("view");
          channelMyVideos(jQuery(this).attr('class'),'','',currentView);
      });
      
      jQuery('.searchButton').click(function(){
      	var searchName;
      	 jQuery('.aboutContainer').hide();
      	 jQuery('.subscripeContainer').hide();
      	 jQuery('.mysubscriptionContainer').hide();
      	 jQuery('.notificationContainer').hide();
      	 jQuery('.videoContainer').show();
      	 searchName = jQuery('.search').val();
          jQuery('.channelMenuContainer p').css('left','0px');
          jQuery('.channelMenuContainer p').css('color','rgb(89, 85, 85)');
          jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');	
          var currentView=jQuery(this).data("view");
          channelMyVideos(jQuery(this).attr('class'),'','',currentView);
      });
      

      jQuery('.aboutButton ').trigger('click');
      jQuery('.aboutButton').click(function(){
         var currentView=jQuery(this).data("view"); 
         if (currentView == 'channel') {
            jQuery('#ch_aboutButton').addClass('aboutButton active'); 
            jQuery('#ch_mySubscriptionButton').attr('class','');
            jQuery('#ch_mySubscriptionButton').addClass('mySubscriptionButton');
         } else {
            jQuery('#aboutButton').addClass('aboutButton active');
            jQuery('#myVideosButton').attr('class','');
            jQuery('#myVideosButton').addClass('myVideosButton');
            jQuery('#mySubscriptionButton').attr('class','');
            jQuery('#mySubscriptionButton').addClass('mySubscriptionButton');
         }
         jQuery('#browseChannelButton').addClass('browseChannelButton');
      	jQuery('.searchButton').show();
      	jQuery('.searchChannelButton').hide();
      	jQuery('.search').val('');
      	jQuery('.videoContainer').hide();
      	jQuery('.subscripeContainer').hide();
      	jQuery('.notificationContainer').hide();
      	jQuery('.mysubscriptionContainer').hide();
      	jQuery('.aboutContainer').show();
         jQuery('.channelMenuContainer p').css('left','0px');
         jQuery('.channelMenuContainer p').css('color','rgb(89, 85, 85)');
      	jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');
      	jQuery(this).css('left','10px');
      	jQuery(this).css('color','white');
      	jQuery(this).css('background','rgb(250, 118, 87)');
      
      });
      
      jQuery('.saveDescription').click(function(){
      	var currentView=jQuery(this).data("view");
      	channelMyVideos(jQuery(this).attr('class'),'','',currentView);
      });
      
      jQuery('.browseChannelButton').click(function(){
      	jQuery('.search').val('');
      	jQuery('.searchButton').hide();
      	jQuery('.searchChannelButton').show();
      	jQuery('.videoContainer').hide();
      	jQuery('.aboutContainer').hide();
      	jQuery('.mysubscriptionContainer').hide();
         jQuery('.channelMenuContainer p').css('left','0px');
         jQuery('.channelMenuContainer p').css('color','rgb(89, 85, 85)');
      	jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');
      	jQuery('.notificationContainer').hide();
      	jQuery('.subscripeContainer').show();
      });
      
      jQuery('.searchChannelButton').click(function(){
      	jQuery('.videoContainer').hide();
      	jQuery('.aboutContainer').hide();
      	jQuery('.mysubscriptionContainer').hide();
      	jQuery('.notificationContainer').hide();
         jQuery('.channelMenuContainer p').css('left','0px');
         jQuery('.channelMenuContainer p').css('color','rgb(89, 85, 85)');
      	jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');
      	jQuery('.subscripeContainer').show();	
      	var currentView=jQuery(this).data("view");
      	channelMyVideos(jQuery(this).attr('class'),'','',currentView);
      });
      
      jQuery('.browseChannelButton').click(function(){
      	var currentView=jQuery(this).data("view");
      	channelMyVideos(jQuery(this).attr('class'),'','',currentView);
      });
      
      jQuery('.mySubscriptionButton').click(function(){
      	jQuery('.searchButton').show();
      	jQuery('.searchChannelButton').hide();
      	jQuery('.notificationContainer').hide();
      	jQuery('.search').val('');
      	jQuery('.videoContainer').hide();
      	jQuery('.aboutContainer').hide();
      	jQuery('.subscripeContainer').hide();
      	jQuery('.mysubscriptionContainer').show();
         jQuery('.channelMenuContainer p').css('left','0px');
         jQuery('.channelMenuContainer p').css('color','rgb(89, 85, 85)');
      	jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');
      	jQuery(this).css('left','10px');
      	jQuery(this).css('color','white');
      	jQuery(this).css('background','rgb(250, 118, 87)');
      	var currentView=jQuery(this).data("view");
      	channelMyVideos(jQuery(this).attr('class'),'','',currentView);
      });
      
      jQuery('.notificationLink').click(function(){
      	jQuery('.searchButton').show();
      	jQuery('.searchChannelButton').hide();
      	jQuery('.notificationContainers').show();
      });
      
      jQuery('.subButton').click(function(){	
      	notificationId = jQuery(this).parent().find('.subscriperId').val();
      	var currentView=jQuery(this).data("view");
      	channelMyVideos(jQuery(this).attr('class'),notificationId,jQuery(this),currentView);
      });
      
      jQuery('.subDeleteButton').click(function(){	
      	notificationId = jQuery(this).parent().find('.subscriperId').val();
      	var currentView=jQuery(this).data("view");
      	channelMyVideos(jQuery(this).attr('class'),notificationId,jQuery(this),currentView);
      });
      
      jQuery('.seeMoreLink').click(function(){
      	jQuery('.notificationContainer').show();
      	jQuery('.searchChannelButton').hide();
      	jQuery('.search').val('');
      	jQuery('.videoContainer').hide();
      	jQuery('.aboutContainer').hide();
      	jQuery('.subscripeContainer').hide();
      	jQuery('.mysubscriptionContainer').hide();
         jQuery('.channelMenuContainer p').css('left','0px');
         jQuery('.channelMenuContainer p').css('color','rgb(89, 85, 85)');
      	jQuery('.channelMenuContainer p').css('background','rgb(252, 193, 179)');	
      });
      
      jQuery('.cancelNotification').click(function(){
         jQuery('.notificationContainer').hide();
         jQuery('.myVideosButton').click();
      });
       
      jQuery('.deleteNotification').click(function(){
         var currentView=jQuery(this).data("view");
         channelMyVideos(jQuery(this).attr('class'),'','',currentView);
      });
});
function saveSubscriper(e,currentView) {
	subscriperId = jQuery(e).parent().find('.subscriperId').val();
	channelMyVideos(jQuery(e).attr('class'),subscriperId,'',currentView);
}
function closemysubscripers(e,currentView) {
	if (confirm(confirmUnsubscribe) == true) {
		msid = jQuery(e).parent().find('.msid').val();
		channelMyVideos(jQuery(e).attr('class'),msid,'',currentView);
	}
}
function playerHeight() {
	jQuery('.popup_player').css('height','448px');
}
