<?php
/**
 * HD Video Share Player modul
 *
 * This file is to display HD Video Share Player module 
 *
 * @category   Apptha
 * @package    mod_hdvideosharerss
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper file */
include_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_contushdvideoshare' . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Get language param for videoshare modules */
$language = JRequest::getVar ( 'lang' );
$languages = '';
if ($language != '') {
  $languages = '&slang=' . $language;
} else {
  $languages = '&slang=en';
}

/** Get base URL and swf path for videoshare modules */
$baseURL    = JURI::base ();
$playerpath = JURI::base () . "components/com_contushdvideoshare/hdflvplayer/hdplayer.swf";

/**
 * Check whether the current component is video share component.
 * If not then add nowPlaying javascript function.
 */
if(JRequest::getVar( 'option' )!="com_contushdvideoshare") {
	?>
	<script type="text/javascript">
	function nowPlaying(videoId, relatedVideoIds) {
		var requesturl = "index.php?option=com_contushdvideoshare&tmpl=component&task=videoPlaying";
		jQuery.ajax({
			url:requesturl,
			type:"POST",
			data:"&videoId="+videoId,
			success : function( result ){}
		});
	}
	</script>
	<?php
}
/** Display videoshare module title */
?>
<h2 id="mod_viewtitle"><?php if(!empty($videoList)) { 
  echo $videoList->title;
}?></h2>
<div class="mod_hdvideoplayer <?php echo $class; ?>">
<?php /** Get mobile device value */
$mobile = videoshare_Detect_mobile ();

/** If mobile is not detected, then display title */
if ($mobile !== true) { ?>
    <script type="text/javascript">
      function currentvideom(id,title,desc,view){
         document.getElementById('mod_viewtitle').innerHTML = title;
      }
    </script> 
<?php }

/** Get access level for videoshare modules */
$homepageaccess = '';
if(!empty($videoList)) {
  $homepageaccess = getUserAccessLevel ( $videoList->useraccess, '' );
}

if (getUserID () != '') {
  $error_msg    = JText::_ ( 'HDVS_NOT_AUTHORIZED' );
} else {
  $error_msg    = JText::_ ( 'HDVS_LOGIN_TO_WATCH' );
}

/** Check upload method is embed or vimeo */
if (! empty ( $videoList ) && (($videoList->filepath == 'Embed') || ((preg_match('/vimeo/', $videoList->videourl)) && ($videoList->videourl != '') ))) {
  /** Check accesslevel is true for videoshare module */
  if ($homepageaccess == 'true') {
      if ($videoList->filepath == 'Embed') {
        $playerembedcode    = $videoList->embedcode;
        
        if ($mobile === true) {
          	if(strpos($playerembedcode, 'width="')) {
				$playerembedcode=preg_replace('/width[=":]*[0-9]+[":]*/', 'width="100%"', $playerembedcode);
			} elseif(strpos($playerembedcode, 'width=')) {
				$playerembedcode=preg_replace('/width[=":]*[0-9]+[":]*/', '', $playerembedcode);
			}
			$playerembedcode=preg_replace('/height[=":]*[0-9]+[":]*/', '', $playerembedcode);
        } else {
			if(strpos($playerembedcode, 'width="')) {
				$playerembedcode=preg_replace('/width[=":]*[0-9]+[":]*/', 'width="' . $width. '"', $playerembedcode);
				$playerembedcode=preg_replace('/height[=":]*[0-9]+[":]*/', 'height="' . $height. '"', $playerembedcode);
			} elseif(strpos($playerembedcode, 'width=')) {
				$playerembedcode=preg_replace('/width[=":]*[0-9]+[":]*/', 'width=' . $width, $playerembedcode);
				$playerembedcode=preg_replace('/height[=":]*[0-9]+[":]*/', 'height=' . $height, $playerembedcode);
			}
        }
        /** For embed code videos in videoshare player module */
        ?>
        <script type="text/javascript">
        jQuery( document ).ready(function() {
        	nowPlaying(<?php echo $videoList->id; ?>);
		});
        </script>
        <div id="flashplayer-module"><?php echo $playerembedcode; ?></div>
        <?php
      } elseif (! empty ( $videoList ) && (preg_match ( '/vimeo/', $videoList->videourl )) && ($videoList->videourl != '')) {
        /** For vimeo videos in videoshare player module */
        $split = explode ( "/", $videoList->videourl );
        
        if ($mobile === true) {
          $widthheight = '';
        } else {
          $widthheight = 'width="' . $width . '" height="' . $height . '"';
        }
        
        $output='<div id="flashplayer-module">
		<script src="https://f.vimeocdn.com/js/froogaloop2.min.js"></script>
		<iframe id="vimeo-player-module" '.$widthheight.' src="https://player.vimeo.com/video/'.$split[3].'?api=1&player_id=vimeo-player-module" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		<script>
		jQuery(function() {
			var iframe = jQuery("#vimeo-player-module")[0];
			var player = $f(iframe);
			// When the player is ready, add listeners for pause, finish, and playProgress
			player.addEvent("ready", function() {
				player.addEvent("play", onPlayModule);
			});
			var doneModule = false;
			function onPlayModule()
			{
				if (!doneModule) {
					nowPlaying('.$videoList->id.');
					doneModule = true;
				}
			}
		});
		</script></div>';
        echo $output;
        } 
  } else { 
/** Style to display login message */ ?>
  <style type="text/css"> .login_msg { height: 200px; color: #fff; width: 100%; margin: <?php echo ceil(300/ 3); ?> px 0 0; text-align: center; }
  .login_msg a { background: #999; color: #fff; padding: 5px; } </style> 
  
  <div id="video" style="height: 200px; background-color: #000000; position: relative;"> 
    <div class="login_msg"> <h3><?php echo $error_msg; ?></h3> 
        <?php if ( getUserID () == '') { ?> 
            <a href="<?php if (! empty ( $player_icons ['login_page_url'] )) { 
              echo $player_icons ['login_page_url']; 
            } else { 
              echo "#"; 
            } ?>"> 
            <?php echo JText::_('HDVS_LOGIN'); ?> </a> 
        <?php } ?>
    </div> 
  </div> 
  <?php }
} else {
  if ($mobile === true) { 
    /** HTML5 player starts here 
     * Generate details for HTML5 player */
    if ($homepageaccess == 'true') {
      /** Check upload method is youtube */
      if ($videoList->filepath == "Youtube" || strpos ( $videoList->videourl, 'youtube' ) > 0) {

        /** Display html5 player for Youtube, dailymotion and viddler */
        if (strpos ( $videoList->videourl, 'youtube' ) > 0) {
			$query_string = array ();
			/** Get video URL for videoshare player module */
			$url      = $videoList->videourl;
			parse_str ( parse_url ( $url, PHP_URL_QUERY ), $query_string );
			$id       = $query_string ["v"];
			$videoid  = trim ( $id );

			$output='<div id="player-module"></div>
			<script>
      		var tag = document.createElement("script");
      		tag.src = "https://www.youtube.com/iframe_api";
      		var firstScriptTag = document.getElementsByTagName("script")[0];
      		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
      		var player;
      		function onYouTubeIframeAPIReady() {
        		player = new YT.Player("player-module", {
          			width: "100%",
          			videoId: "'.$videoid.'",
          			playerVars: {
          				"rel": 0,
          				"showinfo":0,
						"modestbranding":0
					},
          			events: {
            			"onStateChange": onPlayerStateChangeModule
          			}
        		});
      		}
      		var doneModule = false;
      		function onPlayerStateChangeModule(event) {
        		if (event.data == YT.PlayerState.PLAYING && !doneModule) {
          			nowPlaying('.$videoList->id.');
          			doneModule = true;
        		}
      		}
    		</script>';
			echo $output;
 		} elseif (strpos ( $videoList->videourl, 'dailymotion' ) > 0) {                
		   	/** For dailymotion videos in videoshare player module */
		   	$daily_videourl = $videoList->videourl;
		   	$split = explode ( "/", $daily_videourl );
		   	$split_id = explode ( "_", $split [4] );
		   	
   			$output='<script src="http://api.dmcdn.net/all.js"></script>
			<div id="player-module"></div>
			<script>
			    var player = DM.player(document.getElementById("player-module"),{
			        video: "'.$split_id [0].'",
			        width: "100%",
			        params: {
			            html: 0,
			            wmode: "opaque"
			        },
			        events: {
			        	playing: function()
			        	{
			        		onPlayerStateChangeModule();
			        	}
			        }
			    });
   	
			    var doneModule = false;
			    function onPlayerStateChangeModule()
			    {
			    	if (!doneModule) {
			    		nowPlaying('.$videoList->id.');
			  			doneModule = true;
					}
			    }
			</script>';
   			echo $output;
         } elseif (strpos ( $videoList->videourl, 'viddler' ) > 0) {                
               /** For viddler videos in videoshare player module */ 
            $imgstr = explode ( "/", $videoList->videourl ); 
            
            $output='<script type="text/javascript" src="//static.cdn-ec.viddler.com/js/arpeggio/v3/build/main-built.js"></script>
			<div id="my-player-module"></div>
			<script>
			var embed = new ViddlerEmbed({
				videoId: "'.$imgstr[4].'",
				width: "100%",
				target: "#my-player-module"
			});
			var doneModule = false;
			embed.manager.events.on("videoPlayer:play", function() {
			    if (!doneModule) {
			    	nowPlaying('.$videoList->id.');
			  		doneModule = true;
				}
			});
			</script>';
            echo $output;
         } 
     } else if ($videoList->filepath == "File" || $videoList->filepath == "FFmpeg" || $videoList->filepath == "Url") {
          $current_path = "components/com_contushdvideoshare/videos/"; 
          
          if ($videoList->filepath == "Url") {
              /** For URL Method videos in videoshare player module */
              if ($videoList->streameroption == 'rtmp') {
                $rtmp = str_replace ( 'rtmp', 'http', $videoList->streamerpath );
                
                /** For RTMP videos in videoshare player module */
                $video = $rtmp . '_definst_/mp4:' . $videoList->videourl . '/playlist.m3u8';
              } else {
                $video = $videoList->videourl;
              }
          } else {
/** Check amazon bucket is enabled in settings */
              if (isset ( $videoList->amazons3 ) && $videoList->amazons3 == 1) {
                $video = $dispenable ['amazons3link'] . $videoList->videourl;
              } else {
                /** For upload Method videos in videoshare player module */
                $video = JURI::base () . $current_path . $videoList->videourl;
              }
          } 
          /** Display html5 player for URL, upload and ffmpeg videos */
          ?> 
          <video id="video-module" src="<?php echo $video; ?>"  
          width="<?php echo $width; ?>" onerror="failed(event)"  
          height="<?php echo $height; ?>" autoplay controls ></video> 
			<script type="text/javascript">
				jQuery("video#video-module").bind("play", function() {
					nowPlaying(<?php echo $videoList->id; ?>);
				});
			</script>
<?php }
    /** Home page access check ends */
    } else {
      /** Restricted video design part */ ?>
      <style type="text/css"> 
      .login_msg { vertical-align: middle; height: 200px; display: table-cell; color: #fff; }
      .login_msg a { background: #999; color: #fff; padding: 5px; }
      </style> 
      
      <?php /** Display Login message to watch the video */ ?>
      <div id="video" style="height: 200px; background-color: #000000; position: relative;"> 
        <div class="login_msg"> <h3><?php echo $error_msg; ?></h3> 
          <?php /** Check user id is exists */ 
          if ( getUserID () == '') { ?> 
          <a href="<?php if (! empty ( $player_icons ['login_page_url'] )) {
                    echo $player_icons ['login_page_url'];
                  } else {
                    echo '#';
                } ?>">
              <?php echo JText::_('HDVS_LOGIN'); ?></a> 
          <?php } ?> 
          </div> 
        </div> 
<?php }
  /** Mobile check ends for videoshare module */
  } else { 
        /** Display embed flash player for videoshare module */
?>
       <embed wmode="opaque" src="<?php echo $playerpath; ?>" type="application/x-shockwave-flash" allowscriptaccess="always"  
       allowfullscreen="<?php echo $fullscreen; ?>" 
       flashvars="baserefJHDV=<?php echo $baseURL . $playsettings . '&mid=playerModule' . $languages; ?>" 
       width="<?php echo $width; ?>" 
       height="<?php echo $height; ?>"></embed> 
<?php }
} ?> 
</div>
