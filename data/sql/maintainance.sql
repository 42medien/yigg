# The intention of this file is to delete data from the database that we do not need any more and to keep its size down
# THIS MIGHT TAKE LONG HOWEVER SO ONLY RUN AT NIGHT!!

# Clean up logs
DELETE LOW_PRIORITY FROM story_render WHERE user_id = 1 AND created_at < DATE_SUB(NOW(), INTERVAL 1 WEEK);
# Auth user keys

DELETE LOW_PRIORITY FROM auth_user_key WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 WEEK);
# Flush worldspy items
DELETE LOW_PRIORITY FROM feed_entry WHERE created < DATE_SUB(NOW(), INTERVAL 1 WEEK);
DELETE LOW_PRIORITY FROM wspy_tweet WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
# Other Tables
DELETE LOW_PRIORITY FROM notification_message WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 MONTH);
DELETE LOW_PRIORITY FROM postbox_message WHERE deleted_inbox_at IS NOT NULL AND deleted_outbox_at IS NOT NULL;
DELETE LOW_PRIORITY FROM reset_password_key WHERE expires < NOW();
# Stopwords cleanup

DELETE LOW_PRIORITY FROM story_tag WHERE tag_id IN (SELECT id FROM tag WHERE name IN (SELECT word FROM stopwords));
DELETE LOW_PRIORITY FROM user_tag WHERE tag_id IN (SELECT id FROM tag WHERE name IN (SELECT word FROM stopwords));
DELETE LOW_PRIORITY FROM tag WHERE name IN (SELECT word FROM stopwords);
# Deleted Story Cleanup
DELETE LOW_PRIORITY FROM story_tweet WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_redirect WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_related WHERE related_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_related WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_rating WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_comment WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_tag WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_render WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM history WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_attachement WHERE story_id IN (SELECT id FROM story WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story WHERE deleted_at IS NOT NULL;
DELETE LOW_PRIORITY FROM rating WHERE id NOT IN (SELECT rating_id FROM story_rating);
DELETE LOW_PRIORITY FROM redirect_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);
DELETE LOW_PRIORITY FROM wspy_resolved_url WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
DELETE LOW_PRIORITY FROM user_online_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);
DELETE LOW_PRIORITY FROM story_render WHERE user_id IN(SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_tag WHERE user_id IN(SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM story_comment WHERE comment_id IN (SELECT id FROM comment WHERE user_id IN(SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM comment_link WHERE comment_id IN (SELECT id FROM comment WHERE user_id IN(SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM comment WHERE user_id IN(SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM history WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_tweet WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_attachement WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_redirect WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_related WHERE related_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_related WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_rating WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_comment WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_tag WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story_render WHERE story_id IN (SELECT id FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL));
DELETE LOW_PRIORITY FROM story WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM email_lookup WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_following WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_following WHERE following_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_online_log WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM notification_message WHERE sender_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM notification_message WHERE recipient_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM invitation WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
UPDATE deal SET user_id = 1 WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM postbox_message WHERE author_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM postbox_message WHERE recipient_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_tweet WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_permission WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_settings WHERE id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_stats WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM remember_key WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM auth_user_key WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE LOW_PRIORITY FROM user_domain_subscription WHERE user_id IN (SELECT id FROM user WHERE deleted_at IS NOT NULL);
DELETE FROM user WHERE deleted_at IS NOT NULL;