# ************************************************************
# Sequel Pro SQL dump
# Version 3121
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.1.44)
# Datenbank: yigg_devel
# Erstellungsdauer: 2011-01-21 14:31:33 +0100
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle api_call_log
# ------------------------------------------------------------



# Export von Tabelle api_object_map
# ------------------------------------------------------------



# Export von Tabelle auth_user_key
# ------------------------------------------------------------



# Export von Tabelle category
# ------------------------------------------------------------



# Export von Tabelle comment
# ------------------------------------------------------------



# Export von Tabelle comment_link
# ------------------------------------------------------------



# Export von Tabelle deal
# ------------------------------------------------------------



# Export von Tabelle deleted_object
# ------------------------------------------------------------



# Export von Tabelle domain_stats
# ------------------------------------------------------------



# Export von Tabelle email_lookup
# ------------------------------------------------------------



# Export von Tabelle feed
# ------------------------------------------------------------



# Export von Tabelle feed_entry
# ------------------------------------------------------------



# Export von Tabelle file
# ------------------------------------------------------------



# Export von Tabelle group_comment
# ------------------------------------------------------------



# Export von Tabelle group_index
# ------------------------------------------------------------



# Export von Tabelle group_speculation
# ------------------------------------------------------------



# Export von Tabelle group_story
# ------------------------------------------------------------



# Export von Tabelle group_tag
# ------------------------------------------------------------



# Export von Tabelle group_topic
# ------------------------------------------------------------



# Export von Tabelle group_topic_comment
# ------------------------------------------------------------



# Export von Tabelle groups
# ------------------------------------------------------------



# Export von Tabelle history
# ------------------------------------------------------------



# Export von Tabelle invitation
# ------------------------------------------------------------



# Export von Tabelle notification_message
# ------------------------------------------------------------



# Export von Tabelle rating
# ------------------------------------------------------------



# Export von Tabelle redirect
# ------------------------------------------------------------



# Export von Tabelle redirect_log
# ------------------------------------------------------------



# Export von Tabelle remember_key
# ------------------------------------------------------------



# Export von Tabelle reset_password_key
# ------------------------------------------------------------



# Export von Tabelle screenshot_files
# ------------------------------------------------------------



# Export von Tabelle speculation
# ------------------------------------------------------------



# Export von Tabelle speculation_bet
# ------------------------------------------------------------



# Export von Tabelle speculation_comment
# ------------------------------------------------------------



# Export von Tabelle speculation_consensus
# ------------------------------------------------------------



# Export von Tabelle speculation_tag
# ------------------------------------------------------------



# Export von Tabelle speculation_watch
# ------------------------------------------------------------



# Export von Tabelle sponsoring
# ------------------------------------------------------------



# Export von Tabelle sponsoring_place
# ------------------------------------------------------------



# Export von Tabelle stopwords
# ------------------------------------------------------------



# Export von Tabelle story
# ------------------------------------------------------------



# Export von Tabelle story_attachement
# ------------------------------------------------------------



# Export von Tabelle story_click
# ------------------------------------------------------------



# Export von Tabelle story_comment
# ------------------------------------------------------------



# Export von Tabelle story_domain_blacklist
# ------------------------------------------------------------



# Export von Tabelle story_index
# ------------------------------------------------------------



# Export von Tabelle story_rating
# ------------------------------------------------------------



# Export von Tabelle story_redirect
# ------------------------------------------------------------



# Export von Tabelle story_related
# ------------------------------------------------------------



# Export von Tabelle story_render
# ------------------------------------------------------------



# Export von Tabelle story_tag
# ------------------------------------------------------------



# Export von Tabelle story_tweet
# ------------------------------------------------------------



# Export von Tabelle tag
# ------------------------------------------------------------

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;

INSERT INTO `tag` (`id`, `name`)
VALUES
	(2,'apple');

/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle task_status
# ------------------------------------------------------------



# Export von Tabelle tweet
# ------------------------------------------------------------



# Export von Tabelle twitter_search
# ------------------------------------------------------------



# Export von Tabelle user
# ------------------------------------------------------------

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `username`, `salt`, `password`, `status`, `adsense_id`, `last_login`, `email`, `privacy`, `gender`, `last_ip`, `failed_logins`, `avatar_id`, `yipps`, `award_comment`, `award_story`, `points`, `mclient_salt`, `apikey`, `fb_user_id`, `added_from_fb`, `first_name`, `last_name`, `street`, `zip`, `city`, `country`, `settings`, `twitter_name`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(1,'marvin','02e11ae5c8c36f976c9fde7d909357b76d100cd2','ffd8a899da2e38077bd7e3d184d765f51aad3e6b',1,NULL,NULL,'robert@rocu.de',0,NULL,NULL,NULL,NULL,1000,NULL,NULL,NULL,'63bc3b0afeeab477398f2ccd264def1e35d3a9ce',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'a:2:{i:0;N;i:1;N;}',NULL,'2011-01-21 09:51:40','2011-01-21 09:51:40','2011-01-21 14:15:03'),
	(2,'rocu','ab96c20bc238535692e1ad706cac8f4abbdebf6c','2216b2e5444701feace96a087d333f6241ab1acd',1,NULL,'2011-01-21 13:01:22','test@test.de',0,NULL,'127.0.0.1',0,NULL,1000,NULL,NULL,NULL,'f63bc3b0afeeab477398f2ccd264dkef1e35d3a9ce',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'a:2:{i:0;N;i:1;a:1:{s:7:\"profile\";a:14:{s:3:\"sex\";s:4:\"none\";s:8:\"birthday\";N;s:7:\"website\";s:0:\"\";s:4:\"blog\";s:0:\"\";s:4:\"city\";s:14:\"robert@rocu.de\";s:7:\"country\";s:13:\"United States\";s:10:\"occupation\";s:0:\"\";s:8:\"about_me\";s:0:\"\";s:9:\"interests\";s:0:\"\";s:10:\"fave_books\";s:0:\"\";s:15:\"fave_newspapers\";s:0:\"\";s:8:\"why_yigg\";s:0:\"\";s:13:\"fave_products\";s:0:\"\";s:6:\"avatar\";N;}}}',NULL,'2011-01-21 09:51:40','2011-01-21 13:39:29','2011-01-21 14:15:03'),
	(3,'nachrichten-muenchen.de','02e11ae5c8c36f976c9fde7d909357b76d100cd2','ffd8a899da2e38077bd7e3d184d765f51aad3e6b',1,NULL,'2011-01-21 09:55:05','robert@rocu.de',0,NULL,'127.0.0.1',0,NULL,1000,NULL,NULL,NULL,'g63bc3b0afeeab477398f2ccd264dkef1e35d3a9ce',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,'a:2:{i:0;N;i:1;N;}',NULL,'2011-01-21 09:51:40','2011-01-21 09:55:05','2011-01-21 14:15:03');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle user_following
# ------------------------------------------------------------



# Export von Tabelle user_group
# ------------------------------------------------------------



# Export von Tabelle user_online_log
# ------------------------------------------------------------



# Export von Tabelle user_permission
# ------------------------------------------------------------



# Export von Tabelle user_reference
# ------------------------------------------------------------



# Export von Tabelle user_settings
# ------------------------------------------------------------



# Export von Tabelle user_stats
# ------------------------------------------------------------



# Export von Tabelle user_tag
# ------------------------------------------------------------



# Export von Tabelle user_tweet
# ------------------------------------------------------------



# Export von Tabelle video_ad
# ------------------------------------------------------------



# Export von Tabelle video_ad_rating
# ------------------------------------------------------------



# Export von Tabelle wspy_tweet
# ------------------------------------------------------------



# Export von Tabelle yipp_history
# ------------------------------------------------------------

LOCK TABLES `yipp_history` WRITE;
/*!40000 ALTER TABLE `yipp_history` DISABLE KEYS */;

INSERT INTO `yipp_history` (`id`, `user_id`, `speculation_id`, `consensus_id`, `amount`, `type`, `balance`, `deleted_at`, `created_at`, `updated_at`)
VALUES
	(1,1,NULL,NULL,1000,6,1000,'2011-01-21 14:15:03','2011-01-21 09:51:40','2011-01-21 09:51:40');

/*!40000 ALTER TABLE `yipp_history` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
