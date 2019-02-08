<?php
/**
 * View file to display language xml for HD Video Share
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

/** Include component helper */
include_once (JPATH_COMPONENT_SITE.DIRECTORY_SEPARATOR.'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** clean page content to load language xml inputs for player */
ob_clean();

/** Set content type for language xml */
header("content-type: text/xml");

/** Set xml version and encoding for language xml */
echo '<?xml version="1.0" encoding="utf-8"?>';

/** Generate language xml here */
echo '<language>';
/** Language xml data */
echo '<Play><![CDATA['.JText::_('HDVS_PLAY').']]></Play>
<Pause><![CDATA['.JText::_('HDVS_PAUSE').']]></Pause>
<Replay><![CDATA['.JText::_('HDVS_REPLAY').']]></Replay>
<Changequality><![CDATA['.JText::_('HDVS_CHANGE_QUALITY').']]></Changequality>
<zoomIn><![CDATA['.JText::_('HDVS_ZOOM_IN').']]></zoomIn>
<zoomOut><![CDATA['.JText::_('HDVS_ZOOM_OUT').']]></zoomOut>
<zoom><![CDATA['.JText::_('HDVS_ZOOM').']]></zoom>
<share><![CDATA['.JText::_('HDVS_SHARE').']]></share>
<FullScreen><![CDATA['.JText::_('HDVS_FULL_SCREEN').']]></FullScreen>
<ExitFullScreen><![CDATA['.JText::_('HDVS_EXIT_FULL_SCREEN').']]></ExitFullScreen>
<PlayListHide><![CDATA['.JText::_('HDVS_HIDE_RELATED_VIDEOS').']]></PlayListHide>
<PlayListView><![CDATA['.JText::_('HDVS_SHOW_RELATED_VIDEOS').']]></PlayListView>
<sharetheword><![CDATA['.JText::_('HDVS_SHARE_THIS_VIDEO').']]></sharetheword>
<sendanemail><![CDATA['.JText::_('HDVS_SEND_AN_EMAIL').']]></sendanemail>
<Mail><![CDATA['.JText::_('HDVS_EMAIL').']]></Mail>
<to><![CDATA['.JText::_('HDVS_TO').']]></to>
<from><![CDATA['.JText::_('HDVS_FROM').']]></from>
<note><![CDATA['.JText::_('HDVS_NOTE').']]></note>
<send><![CDATA['.JText::_('HDVS_SEND').']]></send>
<copy><![CDATA['.JText::_('HDVS_COPY').']]></copy>
<link><![CDATA['.JText::_('HDVS_LINK').']]></link>
<social><![CDATA['.JText::_('HDVS_SOCIAL').']]></social>
<embed><![CDATA['.JText::_('HDVS_EMBED').']]></embed>
<Quality><![CDATA['.JText::_('HDVS_QUALITY').']]></Quality>
<facebook><![CDATA['.JText::_('HDVS_SHARE_ON_FACEBOOK').']]></facebook>
<tweet><![CDATA['.JText::_('HDVS_SHARE_ON_TWITTER').']]></tweet>
<tumblr><![CDATA['.JText::_('HDVS_SHARE_ON_THUMBLR').']]></tumblr>
<google+><![CDATA['.JText::_('HDVS_SHARE_ON_GPLUS').']]></google+>
<autoplayOff><![CDATA['.JText::_('HDVS_AUTOPLAYOFF').']]></autoplayOff>
<autoplayOn><![CDATA['.JText::_('HDVS_AUTOPLAYON').']]></autoplayOn>
<adindicator><![CDATA['.JText::_('HDVS_ADINDICATOR').']]></adindicator>
<skip><![CDATA['.JText::_('HDVS_SKIP_AD').']]></skip>
<skipvideo><![CDATA['.JText::_('HDVS_SKIP_AD_IN').']]></skipvideo>
<login><![CDATA['.JText::_('HDVS_LOGIN').']]></login>
<not_permission><![CDATA['.JText::_('HDVS_NOT_PERMISSION').']]></not_permission>
<not_authorized><![CDATA['.JText::_('HDVS_NOT_AUTHORIZED').']]></not_authorized>
<youtube_video_url_incorrect><![CDATA['.JText::_('HDVS_INCORRECT_YOUTUBE_URL_PLAYER').']]></youtube_video_url_incorrect>
<youtube_video_notallow><![CDATA['.JText::_('HDVS_NOT_ALLOWED_IN_EMBED_PLAYER').']]></youtube_video_notallow>
<youtube_video_removed><![CDATA['.JText::_('HDVS_YOUTUBE_VIDEO_REMOVED').']]></youtube_video_removed>
<download><![CDATA['.JText::_('HDVS_DOWNLOAD').']]></download>
<Volume><![CDATA['.JText::_('HDVS_VOLUME').']]></Volume>
<ads><![CDATA['.JText::_('HDVS_MID').']]></ads>
<nothumbnail><![CDATA['.JText::_('HDVS_NO_THUMB_IMAGE').']]></nothumbnail>
<live><![CDATA['.JText::_('HDVS_LIVE').']]></live>
<fill_required_fields><![CDATA['.JText::_('HDVS_FILL_FIELDS').']]></fill_required_fields>
<wrong_email><![CDATA['.JText::_('HDVS_MISS_FIELDS').']]></wrong_email>
<email_wait><![CDATA['.JText::_('HDVS_WAIT').']]></email_wait>
<email_sent><![CDATA['.JText::_('HDVS_MAIL_SENT').']]></email_sent>
<video_not_allow_embed_player><![CDATA['.JText::_('HDVS_NOT_ALLOWED_IN_EMBED_PLAYER').']]></video_not_allow_embed_player>
<youtube_ID_Invalid><![CDATA['.JText::_('HDVS_INCORRECT_YOUTUBE_URL_PLAYER').']]></youtube_ID_Invalid>
<video_Removed_or_private>
<![CDATA['.JText::_('HDVS_YOUTUBE_VIDEO_REMOVED').']]></video_Removed_or_private>
<streaming_connection_failed><![CDATA['.JText::_('HDVS_STREAM_CONNECTION_FAILED').']]></streaming_connection_failed>
<audio_not_found><![CDATA['.JText::_('HDVS_AUDIO_NOT_FOUND').']]></audio_not_found>
<video_access_denied><![CDATA['.JText::_('HDVS_VIDEO_NOT_FOUND').']]></video_access_denied>
<FileStructureInvalid><![CDATA['.JText::_('HDVS_FLASH_INVALID').']]></FileStructureInvalid>
<NoSupportedTrackFound><![CDATA['.JText::_('HDVS_NO_SUPPORTED_TRACK_FOUND').']]></NoSupportedTrackFound>';
/** Language xml ends here */
echo '</language>';
/** Exit language xml page */
exitAction ('');
