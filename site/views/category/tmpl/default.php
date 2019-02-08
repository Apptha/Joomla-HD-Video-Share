<?php
/**
 * Category videos view file
 *
 * This file is to display Category videos
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

$current_url = '';
/** Define rating star array for category page */
$ratearray = array ( "nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1" );

/** Check user logged in */
$userId = (int) getUserID();

/** Get thumbview settings from category view and unserialize it */ 
$thumbview  = unserialize ( $this->categoryrowcol [0]->thumbview );
$dispenable = unserialize ( $this->categoryrowcol [0]->dispenable );

/** Get player value settings from category view and unserialize it */
$player_values  = unserialize ( $this->player_values->player_values );
$player_icons   = unserialize ( $this->player_values->player_icons );

/** Get base url and item id */
$baseURL  = getBaseURL();
$Itemid   = $this->Itemid;

/** Get document object and add css */
addStyleForViews ( $thumbview ['categorywidth'] );

/** Get seo option from settings and category id from request */
$seoOption  = $dispenable ['seo_option'];
$category   = CATEGORYPARAM; 
$catid      = CATIDPARAM;

$widthMatch = 'width="';
$widthPattern = '/width[=":]*[0-9]+[":]*/';
$heightPattern = '/height[=":]*[0-9]+[":]*/';
$width = 'width="' . $player_values [WIDTH]. '"';
$height = 'height="' . $player_values [HEIGHT]. '"';

/** Get category id based on seo option */
if (isset ( $category ) || isset ( $catid )) {
  if ($seoOption == 1) {
    $featuredCategoryVal = "category=" . $category;
  } else {
    $flatCatid = is_numeric ( $category );
    
    if ($flatCatid == 1) {
      $catid = $category;
    }
    
    $featuredCategoryVal = "catid=" . $catid;
  }
  /** Get current URL for category page */ 
  $current_url = 'index.php?option=com_contushdvideoshare&view=category&' . $featuredCategoryVal;
  
}
/** Get video page id from request */ 
$requestpage = JRequest::getInt ( 'video_pageid' );

/** Call function to display myvideos, myplaylists link in category page */
playlistMenu( $Itemid, $current_url );
/** Display category page starts here */
?>
<div class="player clearfix" id="clsdetail">
<?php /** Get total records for category page */
$totalrecords = $thumbview ['categorycol'] * $thumbview ['categoryrow'];

if (count ( $this->categoryview ) - 6 < $totalrecords) {
  $totalrecords = count ( $this->categoryview ) - 6;
}

/** Check total count of category page */
if ($totalrecords <= 0) {
  /** If the count is 0 then this part will be executed */
/** Display category title */
  ?>
		<h1 class="home-link hoverable"><?php echo $this->categoryview[0]->category; ?></h1>
		<?php /** If total is 0 then display no records message */
  echo '<div class="hd_norecords_found"> ' . JText::_ ( 'HDVS_NO_CATEGORY_VIDEOS_FOUND' ) . ' </div>';
} else {
  ?>
		<div id="video-grid-container" class="clearfix">

	<?php /** Display video title in category page */
  if (isset ( $dispenable ['categoryplayer'] ) && $dispenable ['categoryplayer'] == 1) {
    ?>
		<h1 id="viewtitle" class="floatleft" style=""><?php echo $this->categoryview[VIDEOFORPLAYER][0]->title; ?></h1>
		<div class="clear"></div>
<?php /** Call function to check user agent */ 
$mobile = videoshare_Detect_mobile ();
    /** Check mobile is not detected */
    if ($mobile !== true) {
/** Script to bind video title while playing video */
      ?>
				<script type="text/javascript">1
				function getvideoData(id,title,desc){
					document.getElementById('viewtitle').innerHTML = title;
				}
				</script>
				
<?php
    }
    /** Display player on category page */
    if (($this->categoryview [VIDEOFORPLAYER] [0]->filepath == EMBED ) || (! empty ( $this->categoryview [VIDEOFORPLAYER] [0] ) && (preg_match ( '/vimeo/', $this->categoryview [VIDEOFORPLAYER] [0]->videourl )) && ($this->categoryview [VIDEOFORPLAYER] [0]->videourl != ''))) {
/** Check access level for category page */  
if ($this->homepageaccess == 'true') {
/** Check uplaod method is embed for category page */
        if ($this->categoryview [VIDEOFORPLAYER] [0]->filepath == EMBED ) {
          $playerembedcode = $this->categoryview [VIDEOFORPLAYER] [0]->embedcode;
          /**  Check mobile is detected for category page */
          if ($mobile === true) {
			if(strpos($playerembedcode, $widthMatch )) {
				$playerembedcode=preg_replace( $widthPattern, 'width="100%"', $playerembedcode );
			}
			elseif(strpos($playerembedcode, 'width=')) {
				$playerembedcode=preg_replace( $widthPattern, '', $playerembedcode );
			}
			$playerembedcode=preg_replace($heightPattern, '', $playerembedcode );
          } else {
				if(strpos($playerembedcode, $widthMatch)) {
					$playerembedcode=preg_replace( $widthPattern , $width, $playerembedcode);
					$playerembedcode=preg_replace($heightPattern, $height, $playerembedcode);
				}
				elseif(strpos($playerembedcode, 'width=')) {
					$playerembedcode=preg_replace($widthPattern , 'width=' . $player_values [WIDTH], $playerembedcode);
					$playerembedcode=preg_replace($widthPattern , 'height=' . $player_values [HEIGHT], $playerembedcode);
				}
 			}
			?>
			<script type="text/javascript">
	        jQuery( document ).ready(function() {
	        	nowPlaying(<?php echo $this->categoryview [VIDEOFORPLAYER] [0]->id; ?>);
			});
	        </script>
	        <div id="flashplayer"><?php echo $playerembedcode; ?></div>
			<?php
          /** For embed code videos in category page */
        } elseif (! empty ( $this->categoryview [VIDEOFORPLAYER] [0] ) && (preg_match ( '/vimeo/', $this->categoryview [VIDEOFORPLAYER] [0]->videourl )) && ($this->categoryview [VIDEOFORPLAYER] [0]->videourl != '')) {
          /** For vimeo videos in category page */
          $split = explode ( "/", $this->categoryview [VIDEOFORPLAYER] [0]->videourl );
          
          if ($mobile === true) {
            $widthheight = '';
          } else {
            $widthheight =  $width . ' ' .  $height ;
          }
          
          $output='<div id="flashplayer">
			<script src="https://f.vimeocdn.com/js/froogaloop2.min.js"></script>
			<iframe id="vimeo-player" '.$widthheight.' src="https://player.vimeo.com/video/'.$split[3].'?api=1&player_id=vimeo-player" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			<script>
			jQuery(function() {
				var iframe = jQuery("#vimeo-player")[0];
				var player = $f(iframe);
				// When the player is ready, add listeners for pause, finish, and playProgress
				player.addEvent("ready", function() {
					player.addEvent("play", onPlay);
				});
				var done = false;
				function onPlay()
				{
					if (!done) {
						nowPlaying('.$this->categoryview [VIDEOFORPLAYER] [0]->id.');
						done = true;
					}
				}
			});
			</script></div>';
          echo $output;
        }
      } else {
/** If access level is set then display login message */
        ?>
						<style type="text/css">
.login_msg { height: <?php echo $player_values[HEIGHT]; ?> px; color: #fff; width: 100%; 
margin: <?php echo ceil( $player_values[ WIDTH ] / 3) ; ?> px 0 0; }
.login_msg a { background: #999; color: #fff; padding: 5px; }
</style>

		<div id="video" style="height:<?php echo $player_values[HEIGHT]; ?>px;
							 background-color:#000000; position: relative;" >
			<div class="login_msg">
			<?php /** Display login message with url in category page */ ?>
				<h3>Please login to watch this video</h3>
				<a
					href="<?php
        if (! empty ( $player_icons ['login_page_url'] )) {
          echo $player_icons ['login_page_url'];
        } else {
          echo "#";
        }
        ?>"><?php echo JText::_('HDVS_LOGIN'); ?></a>
			</div>
		</div>
			<?php
      }
    } else {
/** Check mobile is detected */
      if ($mobile === true) {
/** HTML5 player starts here */
        ?>                     
		<div id="htmlplayer">
							<?php /** Generate details for HTML5 player */
        if ($this->homepageaccess == 'true') {
          /** Check video url is youtube */
          if ($this->categoryview [VIDEOFORPLAYER] [0]->filepath == "Youtube" || strpos ( $this->categoryview [VIDEOFORPLAYER] [0]->videourl, 'youtube.com' ) > 0) {
            /** For youtube videos in category page */
            if (strpos ( $this->categoryview [VIDEOFORPLAYER] [0]->videourl, 'youtube.com' ) > 0) {
				$url = $this->categoryview [VIDEOFORPLAYER] [0]->videourl;
				/** Get youtube video id from helper for category page */
				$videoid = getYoutubeVideoID ( $url );
				// Generate youtube embed code for html5 player
				$output='<div id="player"></div>
				<script>
	      		var tag = document.createElement("script");
	      		tag.src = "https://www.youtube.com/iframe_api";
	      		var firstScriptTag = document.getElementsByTagName("script")[0];
	      		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	      		var player;
	      		function onYouTubeIframeAPIReady() {
	        		player = new YT.Player("player", {
	          			width: "100%",
	          			videoId: "'.$videoid.'",
	          			playerVars: {
	          				"rel": 0,
	          				"showinfo":0,
							"modestbranding":0
						},
	          			events: {
	            			"onStateChange": onPlayerStateChange
	          			}
	        		});
	      		}
	      		var done = false;
	      		function onPlayerStateChange(event) {
	        		if (event.data == YT.PlayerState.PLAYING && !done) {
	          			nowPlaying('.$this->categoryview [VIDEOFORPLAYER] [0]->id.');
	          			done = true;
	        		}
	      		}
	    		</script>';
				echo $output;
              } elseif (strpos ( $this->categoryview [VIDEOFORPLAYER] [0]->videourl, 'dailymotion' ) > 0) {
              /** For dailymotion videos in category page */
              $video = $this->categoryview [VIDEOFORPLAYER] [0]->videourl;
              $split = explode ( "/", $video );
              $split_id = explode ( "_", $split [4] );
              
              $output='<script src="http://api.dmcdn.net/all.js"></script>
				<div id="player"></div>
				<script>
				    var player = DM.player(document.getElementById("player"),{
				        video: "'.$split_id [0].'",
				        width: "100%",
				        params: {
				            html: 0,
				            wmode: "opaque"
				        },
				        events: {
				        	playing: function()
				        	{
				        		onPlayerStateChange();
				        	}
				        }
				    });
	              
				    var done = false;
				    function onPlayerStateChange()
				    {
				    	if (!done) {
				    		nowPlaying('.$this->categoryview [VIDEOFORPLAYER] [0]->id.');
				  			done = true;
						}
				    }
				</script>';
              echo $output;
            } elseif (strpos ( $this->categoryview [VIDEOFORPLAYER] [0]->videourl, 'viddler' ) > 0) {
              /** For viddler videos in category [age  */
              $imgstr = explode ( "/", $this->categoryview [VIDEOFORPLAYER] [0]->videourl );
              
              $output='<script type="text/javascript" src="//static.cdn-ec.viddler.com/js/arpeggio/v3/build/main-built.js"></script>
				<div id="my-player"></div>
				<script>
				var embed = new ViddlerEmbed({
					videoId: "'.$imgstr[4].'",
					width: "100%",
					target: "#my-player"
				});
				var done = false;
				embed.manager.events.on("videoPlayer:play", function() {
				    if (!done) {
				    	nowPlaying('.$this->categoryview [VIDEOFORPLAYER] [0]->id.');
				  		done = true;
					}
				});
				</script>';
              echo $output;
            }
          } 
          /** Check upload method is file / ffmpeg / url */  
          else if ($this->categoryview [VIDEOFORPLAYER] [0]->filepath == "File" || $this->categoryview [VIDEOFORPLAYER] [0]->filepath == "FFmpeg" || $this->categoryview [VIDEOFORPLAYER] [0]->filepath == "Url") {
/** Get video directory path */            
$current_path = "components/com_contushdvideoshare/videos/";
            
            if ($this->categoryview [VIDEOFORPLAYER] [0]->filepath == "Url") {
              /** For URL Method videos */
              if ($this->categoryview [VIDEOFORPLAYER] [0]->streameroption == 'rtmp') {
                $rtmp = str_replace ( 'rtmp', 'http', $this->categoryview [VIDEOFORPLAYER] [0]->streamerpath );
                $video = $rtmp . '_definst_/mp4:' . $this->categoryview [VIDEOFORPLAYER] [0]->videourl . '/playlist.m3u8';
                
                /** For RTMP videos in category page */
              } else {
                $video = $this->categoryview [VIDEOFORPLAYER] [0]->videourl;
              }
            } else {
/** Check video url is amazon 
 * amazon bucket is enable in settings */
              if (isset ( $this->categoryview [VIDEOFORPLAYER] [0]->amazons3 ) && $this->categoryview [VIDEOFORPLAYER] [0]->amazons3 == 1) {
                $video = $dispenable ['amazons3link'] . $this->categoryview [VIDEOFORPLAYER] [0]->videourl;
              } else {
                $video = JURI::base () . $current_path . $this->categoryview [VIDEOFORPLAYER] [0]->videourl;
                /** For upload Method videos in category page */
              }
            }
            /** Play custom url 
             * upload videos in category page using html5 player */ 
            ?>
		<video id="video" src="<?php echo $video; ?>"
				width="<?php echo $player_values [WIDTH]; ?>"
				height="<?php echo $player_values [HEIGHT]; ?>"
				autobuffer controls onerror="failed(event)"> Html5 Not support This
				video Format.
			</video>
			<script type="text/javascript">
				jQuery("video").bind("play", function() {
					nowPlaying(<?php echo $this->categoryview [VIDEOFORPLAYER] [0]->id; ?>);
				});
			</script>
									<?php }
        } else {
          /** Restricted video design part */ ?>
			<style type="text/css">
.login_msg { vertical-align: middle; height: <?php echo $player_values[HEIGHT]; ?> px; display: table-cell; color: #fff; }
.login_msg a { background: #999; color: #fff; padding: 5px; }
</style>

			<div id="video" style="height:<?php echo $player_values[HEIGHT]; ?>px;
				 background-color:#000000; position: relative;" >
				 <?php /** Display login message within the palyer */ ?> 
				<div class="login_msg">
					<h3>Please login to watch this video</h3>
					<a
						href="<?php
          if (! empty ( $player_icons ['login_page_url'] )) {
            echo $player_icons ['login_page_url'];
          } else {
            echo "#";
          }
          ?>"><?php echo JText::_('HDVS_LOGIN'); ?></a>
				</div>
			</div>
						<?php
        }
        ?>
						</div>
      <?php } else {
/** Flash player Start for category page */ ?>                             
		<div id="flashplayer">
		<?php /** Diksplay embed code to play videos in flash */?>
			<embed wmode="opaque" src="<?php echo PLAYERPATH; ?>" type="application/x-shockwave-flash"
		   allowscriptaccess="always" allowfullscreen="true" flashvars="baserefJHDV=<?php echo $baseURL; ?>
<?php echo '&mtype=playerModule&amp;id=' . $this->categoryview [VIDEOFORPLAYER] [0]->id . '&amp;catid=' . $this->categoryview [VIDEOFORPLAYER] [0]->playlistid;
        ?>"  style="width:<?php
        echo $player_values [WIDTH];
        ?>px; height:<?php
        echo $player_values [HEIGHT];
        ?>px" />
		</div>
<?php }
    }
    /** CAtegory page player ends */
  }
  
  /** Specifying the no of columns */
  $no_of_columns = $thumbview ['categorycol'];
  
  /** Looping through category details */
  foreach ( $this->categoryList as $val ) {
    $current_column = 1;
    $l = 0;
    
    for($i = 0; $i < $totalrecords; $i ++) {
/** Check category id is exists */
      if ($val->parent_id == $this->categoryview [$i]->parent_id && $val->category == $this->categoryview [$i]->category) {
        $colcount = $current_column % $no_of_columns;
        
        if ($colcount == 1 && $l == 0) {
          echo "<div class='clear'></div><h1 class='home-link hoverable'> $val->category </h1>";
        }
        
        if ($colcount == 1 || $no_of_columns == 1) {
          echo "</ul><ul class='ulvideo_thumb clearfix'>";
          $l ++;
        }
        
        /** Check upload methods
         * Get thumb url based on that
         */
        if ($this->categoryview [$i]->filepath == "File" || $this->categoryview [$i]->filepath == "FFmpeg" || $this->categoryview [$i]->filepath == EMBED) {
          if (isset ( $this->categoryview [$i]->amazons3 ) && $this->categoryview [$i]->amazons3 == 1) {
            $src_path = $dispenable ['amazons3link'] . $this->categoryview [$i]->thumburl;
          } else {
            $src_path = "components/com_contushdvideoshare/videos/" . $this->categoryview [$i]->thumburl;
          }
        }
        
        if ($this->categoryview [$i]->filepath == "Url" || $this->categoryview [$i]->filepath == "Youtube") {
          $src_path = $this->categoryview [$i]->thumburl;
        }
        /** Display category page content */
        ?>
				<?php
        if ($this->categoryview [$i]->id != '') {
          ?>
							<li class="video-item">
			<div class="home-thumb">
				<div class="video_thumb_wrap">
				<?php /** Display thumb image for video in category page */ 				 
				displayVideoThumbImage ( $Itemid, $src_path, $this->categoryview[$i], 'category', 100 );
				
				/** Display playlist popup in recent category page*/
			displayPlaylistPopup ( $this->categoryview[$i], 'category', $Itemid ); ?>
	
					</div>
				<div class="video_thread" >
				<?php /** Display video title in category page */ 
				
				displayVideoTitle ( $Itemid, $this->categoryview[$i], '' );?>

									<?php /** Calculate rating for category page */
          if ($dispenable ['ratingscontrol'] == 1) {
            if (isset ( $this->categoryview [$i]->ratecount ) && $this->categoryview [$i]->ratecount != 0) {
              $ratestar = round ( $this->categoryview [$i]->rate / $this->categoryview [$i]->ratecount );
            } else {
              $ratestar = 0;
            }
            /** Display ratings for video in category page */
            ?>
										<div class="ratethis1 <?php echo $ratearray[$ratestar]; ?> "></div>
			<?php 
          }

			
          /** Display view count in category page */
          if ($dispenable ['viewedconrtol'] == 1) {
            ?>

										<span class="floatright viewcolor">
											<?php echo $this->categoryview[$i]->times_viewed; ?> 
												<?php echo JText::_('HDVS_VIEWS'); ?></span>
							<?php
          }
          ?>
          
								</div>
			</div> 
			<?php /** Tooltip Starts Here for category page */ ?>
			<div class="htmltooltip">
		<?php /** Display video description in category page */
          if ($this->categoryview [$i]->description) {
            ?>
<p class="tooltip_discrip">
	<?php echo JHTML::_('string.truncate', (strip_tags($this->categoryview[$i]->description)), 120); ?></p>
		<?php
          }
          /** Display category, viewcounts in tootip for category videos */
          toolTip ( $this->categoryview[$i]->category, $this->categoryview[$i]->times_viewed, $dispenable['viewedconrtol'], $i );
          ?>
			</div> 
<?php 
/** Tooltip ends for category page
 * pagination starts here for category page */ ?>
		</li>
						<?php
        }
         /** First row for category page */ 
        if ($colcount == 0) {
          echo '</ul>';
          $current_column = 0;
        }
        
        $current_column ++;
      }
    }
  }
  ?>
</div>
<?php /** Category pagination starts here */ ?>
	<ul class="hd_pagination">
<?php  if (isset ( $this->categoryview ['pageno'] )) {
    videosharePagination ( $this->categoryview , $requestpage);  
  } ?>
	</ul>
<?php /** Category pagination ends here */ 
} ?>
</div>

<?php /** Get request url for category page */
$page = $_SERVER ['REQUEST_URI'];
$hidden_page = '';
/** Get current category page number */
if ($requestpage) {
  $hidden_page = $requestpage;
} else {
  $hidden_page = '';
} 
/** Display pagination for category page */ ?>
<form name="pagination" id="pagination" action="<?php echo $page; ?>"
	method="post">
	<input type="hidden" id="video_pageid" name="video_pageid"
		value="<?php echo $hidden_page ?>" />
</form>

<?php /** Get language direction for category page  */
$rtlLang = getLanguageDirection();  ?>
<script type="text/javascript">
<?php /** Assign category page language direction in script */ ?> 
  rtlLang = <?php echo $rtlLang; ?>;
</script>
