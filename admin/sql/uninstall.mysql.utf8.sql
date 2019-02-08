DROP TABLE IF EXISTS `#__hdflv_upload_backup`;
RENAME TABLE `#__hdflv_upload` TO `#__hdflv_upload_backup`;

DROP TABLE IF EXISTS `#__hdflv_video_category_backup`;
RENAME TABLE `#__hdflv_video_category` TO `#__hdflv_video_category_backup`;
DROP TABLE IF EXISTS `#__hdflv_category_backup`;
RENAME TABLE `#__hdflv_category` TO `#__hdflv_category_backup`;

DROP TABLE IF EXISTS `#__hdflv_video_playlist_backup`;
RENAME TABLE `#__hdflv_video_playlist` TO `#__hdflv_video_playlist_backup`;
DROP TABLE IF EXISTS `#__hdflv_playlist_backup`;
RENAME TABLE `#__hdflv_playlist` TO `#__hdflv_playlist_backup`;

DROP TABLE IF EXISTS `#__hdflv_player_settings_backup`;
RENAME TABLE `#__hdflv_player_settings` TO `#__hdflv_player_settings_backup`;
DROP TABLE IF EXISTS `#__hdflv_site_settings_backup`;
RENAME TABLE `#__hdflv_site_settings` TO `#__hdflv_site_settings_backup`;

DROP TABLE IF EXISTS `#__hdflv_user_backup`;
RENAME TABLE `#__hdflv_user` TO `#__hdflv_user_backup`;
DROP TABLE IF EXISTS `#__hdflv_comments_backup`;
RENAME TABLE `#__hdflv_comments` TO `#__hdflv_comments_backup`;
DROP TABLE IF EXISTS `#__hdflv_googlead_backup`;
RENAME TABLE `#__hdflv_googlead` TO `#__hdflv_googlead_backup`;
DROP TABLE IF EXISTS `#__hdflv_ads_backup`;
RENAME TABLE `#__hdflv_ads` TO `#__hdflv_ads_backup`;

DROP TABLE IF EXISTS `#__hdflv_watchhistory_backup`;
RENAME TABLE `#__hdflv_watchhistory` TO `#__hdflv_watchhistory_backup`;
DROP TABLE IF EXISTS `#__hdflv_watchlater_backup`;
RENAME TABLE `#__hdflv_watchlater` TO `#__hdflv_watchlater_backup`;

DROP TABLE IF EXISTS `#__hdflv_channel_backup`;
RENAME TABLE `#__hdflv_channel` TO `#__hdflv_channel_backup`;
DROP TABLE IF EXISTS `#__hdflv_channel_notification_backup`;
RENAME TABLE `#__hdflv_channel_notification` TO `#__hdflv_channel_notification_backup`;
DROP TABLE IF EXISTS `#__hdflv_channel_videos_backup`;
RENAME TABLE `#__hdflv_channel_videos` TO `#__hdflv_channel_videos_backup`;
DROP TABLE IF EXISTS `#__hdflv_channel_subscribe_backup`;
RENAME TABLE `#__hdflv_channel_subscribe` TO `#__hdflv_channel_subscribe_backup`;

DELETE FROM `#__menu_types` WHERE `menutype` = 'hiddencategorymenu';
DELETE FROM `#__menu` WHERE `menutype` = 'hiddencategorymenu';