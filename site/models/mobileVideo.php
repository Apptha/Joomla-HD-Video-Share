<?php 
/**
 * Mobile video model file
 *
 * This file is used to display mobile player
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
/** For youtube videos */
if ($htmlVideoDetails->filepath == "Youtube" || strpos($htmlVideoDetails->videourl, 'youtube.com') > 0) {
								if (strpos($htmlVideoDetails->videourl, 'youtube.com') > 0) {
								  /** variable declaration for Youtube videos */
									$url = $htmlVideoDetails->videourl;
									/** Array varaible declaration */
									$query_string = array();
									/** parse the Youtube video URL */
									parse_str(parse_url($url, PHP_URL_QUERY), $query_string);
									/** Youtube video id */
									$id = $query_string["v"];
									/** trimmed video id of Youtube */
									$videoid = trim($id);
									/** Youtube thumbimage URL */
									$video_thumb = "http://i3.ytimg.com/vi/$videoid/hqdefault.jpg";
									/** Youtube preview image URL */
									$video_preview = "http://i3.ytimg.com/vi/$videoid/maxresdefault.jpg";
									/** Youtube video URL */
									$video = "http://www.youtube.com/embed/$videoid";
									/** Load Youtube video in Iframe for mobile device */
									?>
										<iframe width="100%" height="100%" src="<?php echo $video; ?>" class="iframe_frameborder" ></iframe>
<span class="playerCloseButton" onclick="closePlayer()"><img src="<?php echo $playerCloseButton; ?>"></span>
										<?php
									}
									elseif (strpos($htmlVideoDetails->videourl, 'dailymotion') > 0) {
										/** For dailymotion videos */
										$daily_videourl = $htmlVideoDetails->videourl;
										/** explode Dailymotion video URL */
										$split = explode("/", $daily_videourl);
										/** Get video id from Dailymotion video URL */
										$split_id = explode("_", $split[4]);
										/** Dailymotion video and preview image display URL */
										$video = $previewurl = 'http://www.dailymotion.com/embed/video/' . $split_id[0];
										/** Load Dailymotion video URL for mobile device */
										?>
										<iframe width="100%" height="100%" src="<?php
										echo $video;
										?>"
										class="iframe_frameborder" ></iframe>
										<span class="playerCloseButton" onclick="closePlayer()"><img src="<?php echo $playerCloseButton; ?>"></span>
										
							<?php
									}
						elseif (strpos($htmlVideoDetails->videourl, 'viddler') > 0) {
							/** For viddler videos */
							$imgstr = explode("/", $htmlVideoDetails->videourl);
							/** Load viddler video in Iframe for mobile device */
							?>
										<iframe id="viddler-<?php echo $imgstr; ?>" src="//www.viddler.com/embed/<?php
										echo $imgstr; ?>/?f=1&autoplay=0&player=full&secret=26392356&loop=false&nologo=false&hd=false"
										width="100%" height="100%"
										frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
										<span class="playerCloseButton" onclick="closePlayer()"><img src="<?php echo $playerCloseButton; ?>"></span>
										
												<?php
						}
						else {
/** No value */
}
								}
							else if ($htmlVideoDetails->filepath == "File"
								|| $htmlVideoDetails->filepath == "FFmpeg"
								|| $htmlVideoDetails->filepath == "Url") {
                                /** Set path for the uploaded videos */
								$current_path = "components/com_contushdvideoshare/videos/";
								/** Check if filepath id URL method */
								if ($htmlVideoDetails->filepath == "Url") {
									/** For URL Method videos */
									if ($htmlVideoDetails->streameroption == 'rtmp') {
                                    /** Check for RTMP videos */
										$rtmp = str_replace('rtmp', 'http', $htmlVideoDetails->streamerpath);
										/** For RTMP videos */
										/** Set videoURL for RTMP videos */
										$video = $rtmp . '_definst_/mp4:' . $htmlVideoDetails->videourl
												. '/playlist.m3u8';
									}
									else {
                                        /** Video URL */
										$video = $htmlVideoDetails->videourl;
									}
								}
								else {
                                    /** Check if Amazon s3 is enabled */
									if (isset($htmlVideoDetails->amazons3) && $htmlVideoDetails->amazons3 == 1) {
                                              /** Set Amazon s3 video link */
										$video = $dispenable['amazons3link']
												. $htmlVideoDetails->videourl;
									}
									else {
                                              /** store the video in the local folder */
										/** For upload Method videos */
										$video = JURI::base() . $current_path . $htmlVideoDetails->videourl;
									}
								}
								/** Load uploaded videos using video tag for mobile devices */
								?>
								<video id="video" src="<?php
								echo $video;
								?>" width="100%"
								height="100%" autobuffer controls
								onerror="failed(event)">
								Html5 Not support This video Format.
								</video>
								<span class="playerCloseButton" onclick="closePlayer()"><img src="<?php echo $playerCloseButton; ?>"></span>
<script type="text/javascript">
								playerHeight();
								</script>
								<?php
							}
							else {
								/** No value */
							}