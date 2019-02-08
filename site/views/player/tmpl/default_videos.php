<?php
/**
 * Player view file
 *
 * This file is to display the videos thumb images in video home page 
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
$count = 0;
$scrolClass = '';
$thumbview = unserialize ( $this->homepagebottomsettings [0]->homethumbview );
$dispenable = unserialize ( $this->homepagebottomsettings [0]->dispenable );
$Itemid = $this->Itemid;
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );
$playlists = $this->playlists;
/** Get user details */
$user = JFactory::getUser();
$userId = $user->get('id');

$count = getPlaylistCount ();
if ( $count > 5 ) {
  $scrolClass = ' popup_scroll';
}
if (empty ( $this->videodetails )) {
	$current_url = JUri::getInstance();
	if (version_compare(JVERSION, '1.6.0', 'ge')) {
		$login_url = JURI::base() . "index.php?option=com_users&amp;view=login&return=" . base64_encode($current_url);
		$register_url = "index.php?option=com_users&amp;view=registration";
	} else {
		$login_url = JURI::base() . "index.php?option=com_user&amp;view=login&return=" . base64_encode($current_url);
		$register_url = "index.php?option=com_user&amp;view=register";
	}
  /** Home page bottom starts here */
  ?>
<div class="section clearfix ">
		<?php
  /** Featured video */
  for($coun_tmovie_post = 1; $coun_tmovie_post <= 3; $coun_tmovie_post ++) {
    if ($thumbview ['homefeaturedvideo'] == 1 && $thumbview ['homefeaturedvideoorder'] == $coun_tmovie_post) {
      ?>
				<div id="video-grid-container" class="clearfix">
		<?php homeLink('featuredvideos',$Itemid,JText::_('HDVS_FEATURED_VIDEOS')); ?>
		<ul class="ulvideo_thumb clearfix">
						<?php 
      $totalrecords = count ( $this->rs_playlist1 [0] );
      
      for($i = 0; $i < $totalrecords; $i ++) {
        /** For SEO settings */
        $seoOption = $dispenable ['seo_option'];
        
        if ($seoOption == 1) {
          $featureCategoryVal = "category=" . $this->rs_playlist1 [0] [$i]->seo_category;
          $featureVideoVal = "video=" . $this->rs_playlist1 [0] [$i]->seotitle;
        } else {
          $featureCategoryVal = "catid=" . $this->rs_playlist1 [0] [$i]->catid;
          $featureVideoVal = "id=" . $this->rs_playlist1 [0] [$i]->id;
        }
        
        if ($this->rs_playlist1 [0] [$i]->filepath == "File" || $this->rs_playlist1 [0] [$i]->filepath == "FFmpeg" || $this->rs_playlist1 [0] [$i]->filepath == "Embed") {
          if (isset ( $this->rs_playlist1 [0] [$i]->amazons3 ) && $this->rs_playlist1 [0] [$i]->amazons3 == 1) {
            $feture_src_path = $dispenable ['amazons3link'] . $this->rs_playlist1 [0] [$i]->thumburl;
          } else {
            $feture_src_path = "components/com_contushdvideoshare/videos/" . $this->rs_playlist1 [0] [$i]->thumburl;
          }
        }
        
        if ($this->rs_playlist1 [0] [$i]->filepath == "Url" || $this->rs_playlist1 [0] [$i]->filepath == "Youtube") {
          $feture_src_path = $this->rs_playlist1 [0] [$i]->thumburl;
        }
        ?>
							<li class="video-item featured_gutterwidth">
							<div class="video_thumb_wrap">
							<a class="info_hover featured_vidimg" rel="htmltooltip"
				href="<?php
        echo JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&amp;view=player&amp;' . $featureCategoryVal . '&amp;' . $featureVideoVal, true );
        ?>"> <img class="yt-uix-hovercard-target" src="<?php echo $feture_src_path; ?>"
					width="145" height="80" title="" alt="thumb_image" />
					<?php
					if(!empty($userId) && !empty($this->rs_playlist1[0][$i]->VideoId)){
						?><div class="watched_overlay"></div><?php
					}
					?>
					</a>
					<?php
					if(!empty($userId)) {
						if(empty($this->rs_playlist1[0][$i]->video_id)) {
							?>
							<a class="watch_later_wrap" href="javascript:void(0)" onclick="addWatchLater(<?php echo $this->rs_playlist1[0][$i]->id; ?>, '<?php echo JURI::base(); ?>', this);">
								<span class="watch_later default-watch-later" title="<?php echo JText::_ ( 'HDVS_ADD_TO_LATER_VIDEOS' );?>"></span>
							</a>
				        	<?php
						}
						else {
							?>
							<a class="watch_later_wrap" href="javascript:void(0)" >
								<span class="watch_later success-watch-later" title="<?php echo JText::_ ( 'HDVS_ADDED_TO_LATER_VIDEOS' );?>"></span>
							</a>
							<?php
						}
					}
					?>
			        <a href="javascript:void(0)" onclick="return openplaylistpopup('featured',<?php echo $this->rs_playlist1[0][$i]->id; ?>)" class="add_to_playlist_wrap">
			        	<span class="add_to_playlist" title="<?php echo JText::_ ( 'HDVS_ADD_TO_PLAYLIST' );?>"></span>
			        </a>
			        <div class="addtocontentbox" id="featured_playlistcontainer<?php echo  $this->rs_playlist1[0][$i]->id; ?>" style="display:none">
                  	     <div id="featuredplayliststatus<?php echo  $this->rs_playlist1[0][$i]->id; ?>" style="display:none" class="playliststatus"></div>
			         <p><?php echo JText::_('HDVS_PLAYLIST_ADD_NE'); ?></p>
                  			<ul id="featured_playlists<?php echo  $this->rs_playlist1[0][$i]->id; ?>" class="playlists_ul<?php echo $scrolClass; ?>">
                  			</ul>
			      <?php if( $userId ) {?>
					<div id="featured_no-playlists<?php echo  $this->rs_playlist1[0][$i]->id; ?>" class="no-playlists"></div>
					<div class="create_playlist border-top:2px solid gray;">
					<button id="featured_playlistadd<?php echo  $this->rs_playlist1[0][$i]->id; ?>" onclick="opencrearesection('featured',<?php echo  $this->rs_playlist1[0][$i]->id; ?>);" class="button playlistadd" ><?php echo JText::_('HDVS_ADDPLAYLIST_LABEL'); ?></button>
                       <div class="addplaylist" id="featured_addplaylistform<?php echo  $this->rs_playlist1[0][$i]->id; ?>" style="display:none">
				       <input type="text" value="" placeholder="<?php echo JText::_('HDVS_PLAYLIST_NAME_ERROR')?>" id="featured_playlistname_input<?php echo  $this->rs_playlist1[0][$i]->id; ?>" class="play_textarea" name="playlistname" autocomplete="off" autofocus="off" onkeyup="if (event.keyCode != 13) return addplaylist('<?php echo  $this->rs_playlist1[0][$i]->id; ?>','featured');" onkeydown="if (event.keyCode == 13) document.getElementById('featured_button-save-home<?php echo  $this->rs_playlist1[0][$i]->id; ?>').click()" />
				       <span id="featured-playlistresponse-<?php echo  $this->rs_playlist1[0][$i]->id; ?>" style="float:left; width:100%;">
				       </span>
				       <input type="button" id="featured_button-save-home<?php echo  $this->rs_playlist1[0][$i]->id; ?>" class="playlistaddform-hide-btn" onclick="return ajaxaddplaylist('<?php echo  $this->rs_playlist1[0][$i]->id; ?>','featured');" value="<?php echo JText::_('HDVS_MY_ADDTO_SAVE_LABEL');?>">
				       <div class="featured_playlistname_loading-play" id="featured_playlistname_loading-play<?php echo  $this->rs_playlist1[0][$i]->id; ?>"></div>
				       </div>
				       </div> 
                       <?php restriction_info($Itemid,'featured',$this->rs_playlist1[0][$i]->id); } else { displayLoginRegister(); } ?>
			    </div>
					</div>
				<div class="video_thread">
					<div class="show-title-container">
						<a class="show-title-gray info_hover"
							href="<?php
        echo JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&amp;view=player&amp;' . $featureCategoryVal . '&amp;' . $featureVideoVal, true );
        ?>"><?php
        if (strlen ( $this->rs_playlist1 [0] [$i]->title ) > 50) {
          echo JHTML::_ ( 'string.truncate', ($this->rs_playlist1 [0] [$i]->title), 50 );
        } else {
          echo $this->rs_playlist1 [0] [$i]->title;
        }
        ?> </a>
					</div>
									<?php
        if ($dispenable ['ratingscontrol'] == 1) {
          if (isset ( $this->rs_playlist1 [0] [$i]->ratecount ) && $this->rs_playlist1 [0] [$i]->ratecount != 0) {
            $feture_ratestar = round ( $this->rs_playlist1 [0] [$i]->rate / $this->rs_playlist1 [0] [$i]->ratecount );
          } else {
            $feture_ratestar = 0;
          }
          ?>
										<div class="ratethis1 <?php echo $ratearray[$feture_ratestar]; ?> "></div>
				
		<?php
        }
		
        
        if ($dispenable ['viewedconrtol'] == 1) {
			timesViewed($this->rs_playlist1[0][$i]->times_viewed);
        }
        ?>
								</div></li>
							<?php
        if ((($i + 1) % $thumbview ['homefeaturedvideocol']) == 0 && ($i + 1) != $totalrecords) {
          ?>
							</ul>
		<ul class="ulvideo_thumb clearfix">
							<?php
        }
        ?>
							<?php
      }
      ?>
							</ul>
		<a class="playerpage_morevideos" href="<?php echo JRoute::_ ( "index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&amp;view=featuredvideos" ); ?>" title="<?php echo JText::_ ( 'HDVS_MORE_VIDEOS' );?>">
		<?php echo JText::_ ( 'HDVS_MORE_VIDEOS' ); ?>
		</a>
	</div>
	<!--Tooltip Starts Here-->
				<?php
      for($i = 0; $i < $totalrecords; $i ++) {
        ?>
					<div class="htmltooltip">
						<?php
        if ($this->rs_playlist1 [0] [$i]->description) {
          ?>
							<p class="tooltip_discrip">
<?php
          echo JHTML::_ ( 'string.truncate', (strip_tags ( $this->rs_playlist1 [0] [$i]->description )), 120 );
          ?>
							</p>
						<?php
        }
        toolTip($this->rs_playlist1[0][$i]->category,$this->rs_playlist1[0][$i]->times_viewed,$dispenable['viewedconrtol'],$i);
        ?>
					</div>
					<?php
      }
      ?>
				<!--Tooltip end Here-->
			<?php
    }
    ?>
			<!-- Code end here for featured videos and begin for popular videos -->
			<?php
    if ($thumbview ['homepopularvideo'] == 1 && $thumbview ['homepopularvideoorder'] == $coun_tmovie_post) {
      ?>
				<div id="video-grid-container_pop" class="clearfix">
		<?php homeLink('popularvideos',$Itemid,JText::_('HDVS_POPULAR_VIDEOS')); ?>
		<ul class="ulvideo_thumb clearfix">
						<?php
      $totalrecords = count ( $this->rs_playlist1 [2] );
      
      for($i = 0; $i < $totalrecords; $i ++) {
        // For SEO settings
        $seoOption = $dispenable ['seo_option'];
        
        if ($seoOption == 1) {
          $popularCategoryVal = "category=" . $this->rs_playlist1 [2] [$i]->seo_category;
          $popularVideoVal = "video=" . $this->rs_playlist1 [2] [$i]->seotitle;
        } else {
          $popularCategoryVal = "catid=" . $this->rs_playlist1 [2] [$i]->catid;
          $popularVideoVal = "id=" . $this->rs_playlist1 [2] [$i]->id;
        }
        
        if ($this->rs_playlist1 [2] [$i]->filepath == "File" || $this->rs_playlist1 [2] [$i]->filepath == "FFmpeg" || $this->rs_playlist1 [2] [$i]->filepath == "Embed") {
          if (isset ( $this->rs_playlist1 [2] [$i]->amazons3 ) && $this->rs_playlist1 [2] [$i]->amazons3 == 1) {
            $popular_src_path = $dispenable ['amazons3link'] . $this->rs_playlist1 [2] [$i]->thumburl;
          } else {
            $popular_src_path = "components/com_contushdvideoshare/videos/" . $this->rs_playlist1 [2] [$i]->thumburl;
          }
        }
        
        if ($this->rs_playlist1 [2] [$i]->filepath == "Url" || $this->rs_playlist1 [2] [$i]->filepath == "Youtube") {
          $popular_src_path = $this->rs_playlist1 [2] [$i]->thumburl;
        }
        ?>
							<li class="video-item popular_gutterwidth">
							<div class="video_thumb_wrap">
							<a class=" info_hover featured_vidimg" rel="htmltooltip1"
				href="<?php
        echo JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&amp;view=player&amp;' . $popularCategoryVal . '&amp;' . $popularVideoVal, true );
        ?>"> <img class="yt-uix-hovercard-target" src="<?php echo $popular_src_path; ?>"
					width="145" height="80" title="" alt="thumb_image" />
					<?php
					if(!empty($userId) && !empty($this->rs_playlist1[2][$i]->VideoId)){
						?><div class="watched_overlay"></div><?php
					}
					?>
					</a>
					<?php
					if(!empty($userId)){
						if(empty($this->rs_playlist1[2][$i]->video_id)){
							?>
							<a class="watch_later_wrap" href="javascript:void(0)" onclick="addWatchLater(<?php echo $this->rs_playlist1[2][$i]->id; ?>,'<?php echo JURI::base(); ?>', this)">
								<span class="watch_later default-watch-later" title="<?php echo JText::_ ( 'HDVS_ADD_TO_LATER_VIDEOS' );?>"></span>
							</a>
						<?php
						}
						else {
						?>
							<a class="watch_later_wrap" href="javascript:void(0)" >
								<span class="watch_later success-watch-later" title="<?php echo JText::_ ( 'HDVS_ADDED_TO_LATER_VIDEOS' );?>" ></span>
							</a>
			        		<?php
						}
					}
					?>
					<a href="javascript:void(0)" onclick="return openplaylistpopup('popular',<?php echo $this->rs_playlist1[2][$i]->id; ?>)" class="add_to_playlist_wrap" >
			        	<span class="add_to_playlist" title="<?php echo JText::_ ( 'HDVS_ADD_TO_PLAYLIST' );?>"></span>
			        </a>
			        <div id="popular_playlistcontainer<?php echo  $this->rs_playlist1[2][$i]->id; ?>"  class="addtocontentbox" style="display:none">  
                  		<div class="playliststatus" id="popularplayliststatus<?php echo  $this->rs_playlist1[2][$i]->id; ?>" style="display:none"></div>
                        <p><?php echo JText::_('HDVS_PLAYLIST_ADD_NE'); ?></p>
                 		<ul id="popular_playlists<?php echo  $this->rs_playlist1[2][$i]->id; ?>" class="playlists_ul<?php echo $scrolClass; ?>"></ul>
				      <?php if( $userId ) { ?>
					   <div id="popular_no-playlists<?php echo  $this->rs_playlist1[2][$i]->id; ?>" class="no-playlists"></div>
                       <div class="create_playlist border-top:2px solid gray;">
	                       <button id="popular_playlistadd<?php echo  $this->rs_playlist1[2][$i]->id; ?>" onclick="opencrearesection('popular',<?php echo  $this->rs_playlist1[2][$i]->id; ?>);" class="button playlistadd" ><?php echo JText::_('HDVS_ADDPLAYLIST_LABEL'); ?></button>
	                       <div class="addplaylist" id="popular_addplaylistform<?php echo  $this->rs_playlist1[2][$i]->id; ?>"  style="display:none">
						       <input type="text" value="" placeholder="<?php echo JText::_('HDVS_PLAYLIST_NAME_ERROR')?>" id="popular_playlistname_input<?php echo  $this->rs_playlist1[2][$i]->id; ?>" class="play_textarea" name="playlistname" autocomplete="off" autofocus="off" onkeyup="if (event.keyCode != 13) return addplaylist('<?php echo  $this->rs_playlist1[2][$i]->id; ?>','popular');" onkeydown="if (event.keyCode == 13) document.getElementById('popular_button-save-home<?php echo  $this->rs_playlist1[2][$i]->id; ?>').click()"/>
						       <span id="popular-playlistresponse-<?php echo  $this->rs_playlist1[2][$i]->id; ?>" style="float:left; width:100%;"></span>
						      <input type="button" id="popular_button-save-home<?php echo  $this->rs_playlist1[2][$i]->id; ?>" class="playlistaddform-hide-btn" onclick="return ajaxaddplaylist('<?php echo  $this->rs_playlist1[2][$i]->id; ?>','popular');" value="<?php echo JText::_('HDVS_MY_ADDTO_SAVE_LABEL');?>">
						       <div class="popular_playlistname_loading-play" id="popular_playlistname_loading-play<?php echo  $this->rs_playlist1[2][$i]->id; ?>"></div>
					       </div>
				       </div> 
				<?php restriction_info($Itemid,'popular',$this->rs_playlist1[2][$i]->id); } else { displayLoginRegister(); } ?>
				</div>
					</div>
				<div class="video_thread">

					<div class="show-title-container">
						<a
							href="<?php
        echo JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&amp;view=player&amp;' . $popularCategoryVal . '&amp;' . $popularVideoVal, true );
        ?>"
							class="show-title-gray info_hover"><?php
        if (strlen ( $this->rs_playlist1 [2] [$i]->title ) > 50) {
          echo JHTML::_ ( 'string.truncate', ($this->rs_playlist1 [2] [$i]->title), 50 );
        } else {
          echo $this->rs_playlist1 [2] [$i]->title;
        }
        ?></a>
					</div>
					<div class="clsratingvalue">
										<?php
        if ($dispenable ['ratingscontrol'] == 1) {
          if (isset ( $this->rs_playlist1 [2] [$i]->ratecount ) && $this->rs_playlist1 [2] [$i]->ratecount != 0) {
            $popular_ratestar = round ( $this->rs_playlist1 [2] [$i]->rate / $this->rs_playlist1 [2] [$i]->ratecount );
          } else {
            $popular_ratestar = 0;
          }
          ?>
											<div class="ratethis1 <?php echo $ratearray[$popular_ratestar]; ?> "></div>
		<?php
        }
		
        ?>
									</div>
				
				
                
									<?php
        if ($dispenable ['viewedconrtol'] == 1) {
			timesViewed($this->rs_playlist1[2][$i]->times_viewed);
        }
        ?>
								</div></li>
							<?php
        if ((($i + 1) % $thumbview ['homepopularvideocol']) == 0 && ($i + 1) != $totalrecords) {
          ?>
							</ul>
		<ul class="ulvideo_thumb clearfix">
								<?php
        }
      }
      ?>                                     </ul>
		<a class="playerpage_morevideos" title="<?php echo JText::_ ( 'HDVS_MORE_VIDEOS' ); ?>"
		href="<?php echo JRoute::_ ( "index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&amp;view=popularvideos" ); ?>">
		<?php echo JText::_ ( 'HDVS_MORE_VIDEOS' ); ?>
		</a>
	</div>
	<!--Tooltip Starts Here-->
				<?php
      for($i = 0; $i < $totalrecords; $i ++) {
        ?>
					<div class="htmltooltip1">
						<?php
        if ($this->rs_playlist1 [2] [$i]->description) {
          ?>
							<p class="tooltip_discrip">
<?php
          echo JHTML::_ ( 'string.truncate', (strip_tags ( $this->rs_playlist1 [2] [$i]->description )), 120 );
          ?>
							</p>
						<?php
        }
        toolTip($this->rs_playlist1[2][$i]->category,$this->rs_playlist1[2][$i]->times_viewed,$dispenable['viewedconrtol'],$i);
        ?>
					</div>
					<?php
      }
      ?>
				<!--Tooltip end Here-->
			<?php
    }
    ?>
			<?php
    if ($thumbview ['homerecentvideo'] == 1 && $thumbview ['homerecentvideoorder'] == $coun_tmovie_post) {
      ?>
				<!-- Code end here for Popular videos and begin for Recent videos -->
	<div id="video-grid-container_rec" class="clearfix">
		<?php homeLink('recentvideos',$Itemid,JText::_('HDVS_RECENT_VIDEOS')); ?>
		<ul class="ulvideo_thumb clearfix">
						<?php
      $totalrecords = count ( $this->rs_playlist1 [1] );
      
      for($i = 0; $i < $totalrecords; $i ++) {
        // For SEO settings
        $seoOption = $dispenable ['seo_option'];
        
        if ($seoOption == 1) {
          $recentCategoryVal = "category=" . $this->rs_playlist1 [1] [$i]->seo_category;
          $recentVideoVal = "video=" . $this->rs_playlist1 [1] [$i]->seotitle;
        } else {
          $recentCategoryVal = "catid=" . $this->rs_playlist1 [1] [$i]->catid;
          $recentVideoVal = "id=" . $this->rs_playlist1 [1] [$i]->id;
        }
        
        if ($this->rs_playlist1 [1] [$i]->filepath == "File" || $this->rs_playlist1 [1] [$i]->filepath == "FFmpeg" || $this->rs_playlist1 [1] [$i]->filepath == "Embed") {
          if (isset ( $this->rs_playlist1 [1] [$i]->amazons3 ) && $this->rs_playlist1 [1] [$i]->amazons3 == 1) {
            $recent_src_path = $dispenable ['amazons3link'] . $this->rs_playlist1 [1] [$i]->thumburl;
          } else {
            $recent_src_path = "components/com_contushdvideoshare/videos/" . $this->rs_playlist1 [1] [$i]->thumburl;
          }
        }
        
        if ($this->rs_playlist1 [1] [$i]->filepath == "Url" || $this->rs_playlist1 [1] [$i]->filepath == "Youtube") {
          $recent_src_path = $this->rs_playlist1 [1] [$i]->thumburl;
        }
        ?>
							<li class="video-item recent_gutterwidth" >
							<div class="video_thumb_wrap">
							<a class=" info_hover featured_vidimg" rel="htmltooltip2"
				href="<?php
        echo JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&amp;view=player&amp;' . $recentCategoryVal . '&amp;' . $recentVideoVal, true );
        ?>"> <img class="yt-uix-hovercard-target" src="<?php echo $recent_src_path; ?>"
					width="145" height="80" title="" alt="thumb_image" />
					<?php
					if(!empty($userId) && !empty($this->rs_playlist1[1][$i]->VideoId)){
						?><div class="watched_overlay"></div><?php
					}
					?>
					</a>
					<?php
		        if(!empty($userId)){
		        	if(empty($this->rs_playlist1[1][$i]->video_id)){
		        		?>
        			<a class="watch_later_wrap" href="javascript:void(0)" onclick="addWatchLater(<?php echo $this->rs_playlist1[1][$i]->id; ?>,'<?php echo JURI::base(); ?>', this)">
        				<span class="watch_later default-watch-later" title="<?php echo JText::_ ( 'HDVS_ADD_TO_LATER_VIDEOS' );?>"></span>
        			</a>
        			<?php
        			}
        			else {
        				?>
        				<a class="watch_later_wrap" href="javascript:void(0)" >
        					<span class="watch_later success-watch-later" title="<?php echo JText::_ ( 'HDVS_ADDED_TO_LATER_VIDEOS' );?>"></span>
        				</a>
        				<?php
        			}
        		}
        		?>
        		<a  href="javascript:void(0)" onclick="return openplaylistpopup('recent',<?php echo $this->rs_playlist1[1][$i]->id; ?>)" class="add_to_playlist_wrap" >
		        	<span class="add_to_playlist" title="<?php echo JText::_ ( 'HDVS_ADD_TO_PLAYLIST' );?>"></span>
		        </a>
		        <div id="recent_playlistcontainer<?php echo  $this->rs_playlist1[1][$i]->id; ?>" style="display:none" class="addtocontentbox">
                  		<div style="display:none" id="recentplayliststatus<?php echo  $this->rs_playlist1[1][$i]->id; ?>" class="playliststatus"></div>
                       		 <p><?php echo JText::_('HDVS_PLAYLIST_ADD_NE'); ?></p>
                  					<ul class="playlists_ul<?php echo $scrolClass; ?>" id="recent_playlists<?php echo  $this->rs_playlist1[1][$i]->id; ?>"></ul>
				      <?php  if( $userId ) {?>
						<div id="recent_no-playlists<?php echo  $this->rs_playlist1[1][$i]->id; ?>" class="no-playlists"></div>
						<div class="create_playlist border-top:2px solid gray;"><button id="recent_playlistadd<?php echo  $this->rs_playlist1[1][$i]->id; ?>" onclick="opencrearesection('recent',<?php echo  $this->rs_playlist1[1][$i]->id; ?>);" class="button playlistadd" ><?php echo JText::_('HDVS_ADDPLAYLIST_LABEL'); ?></button>
                       <div class="addplaylist" id="recent_addplaylistform<?php echo  $this->rs_playlist1[1][$i]->id; ?>"  style="display:none">
					       <input type="text" value="" placeholder="<?php echo JText::_('HDVS_PLAYLIST_NAME_ERROR')?>" id="recent_playlistname_input<?php echo  $this->rs_playlist1[1][$i]->id; ?>" class="play_textarea" name="playlistname" autocomplete="off" autofocus="off" onkeyup="if (event.keyCode != 13) return addplaylist('<?php echo  $this->rs_playlist1[1][$i]->id; ?>','recent');" onkeydown="if (event.keyCode == 13) document.getElementById('recent_button-save-home<?php echo  $this->rs_playlist1[1][$i]->id; ?>').click()" />
					       <span id="recent-playlistresponse-<?php echo  $this->rs_playlist1[1][$i]->id; ?>" style="float:left; width:100%;"></span>
					       <input type="button" id="recent_button-save-home<?php echo  $this->rs_playlist1[1][$i]->id; ?>" class="playlistaddform-hide-btn" onclick="return ajaxaddplaylist('<?php echo  $this->rs_playlist1[1][$i]->id; ?>','recent');" value="<?php echo JText::_('HDVS_MY_ADDTO_SAVE_LABEL');?>">
					       <div id="recent_playlistname_loading-play<?php echo  $this->rs_playlist1[1][$i]->id; ?>" class="featured_playlistname_loading-play"></div>
				       </div>
				       </div> 
                         <?php restriction_info($Itemid,'recent',$this->rs_playlist1[1][$i]->id); } else { displayLoginRegister(); } ?>
				</div>
					</div>

				<div class="video_thread">
					<div class="show-title-container">
						<a class="show-title-gray info_hover"
							href="<?php
        echo JRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&amp;view=player&amp;' . $recentCategoryVal . '&amp;' . $recentVideoVal, true );
        ?>"><?php
        if (strlen ( $this->rs_playlist1 [1] [$i]->title ) > 50) {
          echo JHTML::_ ( 'string.truncate', ($this->rs_playlist1 [1] [$i]->title), 50 );
        } else {
          echo $this->rs_playlist1 [1] [$i]->title;
        }
        ?></a>
		        
		        
					</div>
									<?php
        if ($dispenable ['ratingscontrol'] == 1) {
          if (isset ( $this->rs_playlist1 [1] [$i]->ratecount ) && $this->rs_playlist1 [1] [$i]->ratecount != 0) {
            $recent_ratestar = round ( $this->rs_playlist1 [1] [$i]->rate / $this->rs_playlist1 [1] [$i]->ratecount );
          } else {
            $recent_ratestar = 0;
          }
          ?>
										<div class="ratethis1 <?php echo $ratearray[$recent_ratestar]; ?> "></div>
				
				
										<?php 
        }
        
		
        
        if ($dispenable ['viewedconrtol'] == 1) {
			timesViewed($this->rs_playlist1[1][$i]->times_viewed);
        }
        ?></div></li>
							<?php
        if ((($i + 1) % $thumbview ['homerecentvideocol']) == 0 && ($i + 1) != $totalrecords) {
          ?>
							</ul>
		<ul class="ulvideo_thumb clearfix">
							<?php
        }
      }
      ?> </ul>
		<a class="playerpage_morevideos"
			href="<?php
      echo jRoute::_ ( 'index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&amp;view=recentvideos' );
      ?>"
			title="<?php echo JText::_('HDVS_MORE_VIDEOS'); ?>"><?php
      echo JText::_ ( 'HDVS_MORE_VIDEOS' );
      ?></a>
	</div>
	<!--Tooltip Starts Here-->
				<?php
      for($i = 0; $i < $totalrecords; $i ++) {
        ?>
					<div class="htmltooltip2">
						<?php
        
if ($this->rs_playlist1 [1] [$i]->description) {
          ?>
							<p class="tooltip_discrip"><?php
          
echo JHTML::_ ( 'string.truncate', (strip_tags ( $this->rs_playlist1 [1] [$i]->description )), 120 );
          ?></p>
				<?php
        }
        toolTip($this->rs_playlist1[1][$i]->category,$this->rs_playlist1[1][$i]->times_viewed,$dispenable['viewedconrtol'],$i);
        ?>
					</div>
					<?php
      }
      ?>
				<!--Tooltip end Here-->
				<?php
    }
  }
  ?>
		<!-- Code end here for Recent videos in home page display -->
</div>
<?php
}
