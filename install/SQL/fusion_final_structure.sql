SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `account_data`
-- ----------------------------
DROP TABLE IF EXISTS `account_data`;
CREATE TABLE `account_data` (
  `id` int(11) NOT NULL,
  `vp` int(11) DEFAULT '0',
  `dp` int(11) DEFAULT '0',
  `location` varchar(255) DEFAULT NULL,
  `nickname` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `articles`
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `headline` varchar(70) DEFAULT NULL,
  `content` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `comments` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `changelog`
-- ----------------------------
DROP TABLE IF EXISTS `changelog`;
CREATE TABLE `changelog` (
  `change_id` int(10) NOT NULL AUTO_INCREMENT,
  `changelog` text NOT NULL,
  `author` varchar(50) NOT NULL,
  `type` int(10) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`change_id`),
  KEY `FK_changelog_changelog_type` (`type`),
  CONSTRAINT `FK_changelog_changelog_type` FOREIGN KEY (`type`) REFERENCES `changelog_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COMMENT='Changelog is being saved here';
-- ----------------------------
-- Table structure for `changelog_type`
-- ----------------------------
DROP TABLE IF EXISTS `changelog_type`;
CREATE TABLE `changelog_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `typeName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='The type of changelog, example: race change, ....';
-- ----------------------------
-- Table structure for `ci_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `daily_signups`
-- ----------------------------
DROP TABLE IF EXISTS `daily_signups`;
CREATE TABLE `daily_signups` (
  `date` varchar(255) NOT NULL DEFAULT '',
  `amount` int(11) DEFAULT '0',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `image_slider`
-- ----------------------------
DROP TABLE IF EXISTS `image_slider`;
CREATE TABLE `image_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT '#',
  `text` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `item_display`
-- ----------------------------
DROP TABLE IF EXISTS `item_display`;
CREATE TABLE `item_display` (
  `entry` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `displayid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`entry`),
  KEY `displayid` (`displayid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='Item System';
-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT 'FusionCMS Link' COMMENT 'Name of the link',
  `link` varchar(255) DEFAULT '#' COMMENT 'Where does the link goes to',
  `side` varchar(255) DEFAULT 'top' COMMENT 'Where do we want to place the menu',
  `rank` int(11) NOT NULL COMMENT 'Everything higher or equal to this can access this',
  `specific_rank` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Do we want it to be available for the selected rank only?',
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_menu_ranks` (`rank`),
  CONSTRAINT `FK_menu_ranks` FOREIGN KEY (`rank`) REFERENCES `ranks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `monthly_income`
-- ----------------------------
DROP TABLE IF EXISTS `monthly_income`;
CREATE TABLE `monthly_income` (
  `month` varchar(255) NOT NULL DEFAULT '',
  `amount` int(11) DEFAULT '0',
  PRIMARY KEY (`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `monthly_votes`
-- ----------------------------
DROP TABLE IF EXISTS `monthly_votes`;
CREATE TABLE `monthly_votes` (
  `month` varchar(255) NOT NULL DEFAULT '',
  `amount` int(11) DEFAULT '0',
  PRIMARY KEY (`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `order_log`
-- ----------------------------
DROP TABLE IF EXISTS `order_log`;
CREATE TABLE `order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `completed` int(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vp_cost` int(11) DEFAULT NULL,
  `dp_cost` int(11) DEFAULT NULL,
  `cart` text,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `pages`
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `content` text,
  `rank_needed` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `fk_rank_needed_ranks` (`rank_needed`),
  CONSTRAINT `fk_rank_needed_ranks` FOREIGN KEY (`rank_needed`) REFERENCES `ranks` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `paygol_logs`
-- ----------------------------
DROP TABLE IF EXISTS `paygol_logs`;
CREATE TABLE `paygol_logs` (
  `message_id` varchar(255) NOT NULL DEFAULT '',
  `service_id` varchar(255) DEFAULT NULL,
  `shortcode` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `custom` varchar(255) DEFAULT NULL,
  `points` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `paypal_logs`
-- ----------------------------
DROP TABLE IF EXISTS `paypal_logs`;
CREATE TABLE `paypal_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_status` varchar(50) NOT NULL,
  `payment_amount` double NOT NULL,
  `payment_currency` varchar(10) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `validated` int(1) DEFAULT '0',
  `error` text,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `pending_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `pending_accounts`;
CREATE TABLE `pending_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `expansion` int(3) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `private_message`
-- ----------------------------
DROP TABLE IF EXISTS `private_message`;
CREATE TABLE `private_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `sender_id` int(10) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `time` int(10) NOT NULL,
  `read` int(1) DEFAULT '0',
  `deleted_user` int(1) DEFAULT '0',
  `deleted_sender` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_private_message_account_data` (`user_id`),
  KEY `FK_private_message_account_data_2` (`sender_id`),
  CONSTRAINT `FK_private_message_account_data` FOREIGN KEY (`user_id`) REFERENCES `account_data` (`id`),
  CONSTRAINT `FK_private_message_account_data_2` FOREIGN KEY (`sender_id`) REFERENCES `account_data` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `ranks`
-- ----------------------------
DROP TABLE IF EXISTS `ranks`;
CREATE TABLE `ranks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rank_name` varchar(50) DEFAULT 'RANK',
  `access_id` varchar(10) DEFAULT '0',
  `is_gm` int(1) DEFAULT '0',
  `is_dev` int(1) DEFAULT '0',
  `is_admin` int(1) DEFAULT '0',
  `is_owner` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `realms`
-- ----------------------------
DROP TABLE IF EXISTS `realms`;
CREATE TABLE `realms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostname` varchar(255) DEFAULT NULL COMMENT 'Ip of the remote database',
  `username` varchar(255) DEFAULT NULL COMMENT 'Username to connect to the database',
  `password` varchar(255) DEFAULT NULL COMMENT 'Password to connect to the database',
  `char_database` varchar(255) DEFAULT NULL COMMENT 'Holds the name of the chardatabase',
  `world_database` varchar(255) DEFAULT NULL COMMENT 'Holds the name of the worlddatabase',
  `cap` int(5) DEFAULT '100' COMMENT 'The maximum player cap of the database.',
  `realmName` varchar(255) DEFAULT NULL,
  `console_username` varchar(255) DEFAULT NULL,
  `console_password` varchar(255) DEFAULT NULL,
  `console_port` int(6) DEFAULT NULL,
  `emulator` varchar(255) DEFAULT NULL,
  `realm_port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `shouts`
-- ----------------------------
DROP TABLE IF EXISTS `shouts`;
CREATE TABLE `shouts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author` int(30) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_shouts_account_data` (`author`),
  CONSTRAINT `FK_shouts_account_data` FOREIGN KEY (`author`) REFERENCES `account_data` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `sideboxes`
-- ----------------------------
DROP TABLE IF EXISTS `sideboxes`;
CREATE TABLE `sideboxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT 'Name of the sidebox',
  `displayName` varchar(50) DEFAULT NULL COMMENT 'Name how you want users to see it.',
  `rank_needed` int(10) NOT NULL DEFAULT '1',
  `order` int(11) DEFAULT '100',
  PRIMARY KEY (`id`),
  KEY `fk_sb_rank_needed` (`rank_needed`),
  CONSTRAINT `fk_sb_rank_needed` FOREIGN KEY (`rank_needed`) REFERENCES `ranks` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `sideboxes_custom`
-- ----------------------------
DROP TABLE IF EXISTS `sideboxes_custom`;
CREATE TABLE `sideboxes_custom` (
  `sidebox_id` int(10) NOT NULL,
  `content` text NOT NULL,
  UNIQUE KEY `sidebox_id` (`sidebox_id`),
  CONSTRAINT `FK_sideboxes_custom_sideboxes` FOREIGN KEY (`sidebox_id`) REFERENCES `sideboxes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `sideboxes_poll_answers`
-- ----------------------------
DROP TABLE IF EXISTS `sideboxes_poll_answers`;
CREATE TABLE `sideboxes_poll_answers` (
  `answerid` int(10) NOT NULL AUTO_INCREMENT,
  `questionid` int(10) NOT NULL,
  `answer` varchar(50) NOT NULL,
  PRIMARY KEY (`answerid`),
  KEY `FK__sideboxes_poll_questions` (`questionid`),
  CONSTRAINT `FK__sideboxes_poll_questions` FOREIGN KEY (`questionid`) REFERENCES `sideboxes_poll_questions` (`questionid`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `sideboxes_poll_questions`
-- ----------------------------
DROP TABLE IF EXISTS `sideboxes_poll_questions`;
CREATE TABLE `sideboxes_poll_questions` (
  `questionid` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(50) NOT NULL,
  PRIMARY KEY (`questionid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `sideboxes_poll_votes`
-- ----------------------------
DROP TABLE IF EXISTS `sideboxes_poll_votes`;
CREATE TABLE `sideboxes_poll_votes` (
  `questionid` int(11) DEFAULT NULL,
  `answerid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  KEY `fk_answers` (`questionid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `spelltext_en`
-- ----------------------------
DROP TABLE IF EXISTS `spelltext_en`;
CREATE TABLE `spelltext_en` (
  `spellId` int(11) NOT NULL,
  `spellText` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `store_groups`
-- ----------------------------
DROP TABLE IF EXISTS `store_groups`;
CREATE TABLE `store_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `store_items`
-- ----------------------------
DROP TABLE IF EXISTS `store_items`;
CREATE TABLE `store_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` text,
  `name` varchar(255) DEFAULT NULL,
  `quality` int(2) DEFAULT NULL,
  `vp_price` int(4) DEFAULT NULL,
  `dp_price` int(4) DEFAULT NULL,
  `realm` int(3) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT 'inv_misc_questionmark',
  `group` int(11) DEFAULT NULL,
  `query` text,
  `query_database` varchar(50) DEFAULT '' COMMENT 'cms | realmd | realm',
  `query_need_character` int(1) DEFAULT '0',
  `tooltip` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_group` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `teleport_locations`
-- ----------------------------
DROP TABLE IF EXISTS `teleport_locations`;
CREATE TABLE `teleport_locations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'Unnamed',
  `description` varchar(255) DEFAULT NULL,
  `x` float DEFAULT '0',
  `y` float DEFAULT '0',
  `z` float DEFAULT '0',
  `orientation` float DEFAULT '0',
  `mapId` smallint(6) DEFAULT '0',
  `vpCost` int(11) DEFAULT '0',
  `dpCost` int(11) DEFAULT '0',
  `goldCost` int(11) DEFAULT '0',
  `realm` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `realm_fk` (`realm`),
  CONSTRAINT `realm_fk` FOREIGN KEY (`realm`) REFERENCES `realms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Here you can find every unstuck location that people can use to unstuck to.';
-- ----------------------------
-- Table structure for `visitor_log`
-- ----------------------------
DROP TABLE IF EXISTS `visitor_log`;
CREATE TABLE `visitor_log` (
  `date` varchar(10) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for `vote_log`
-- ----------------------------
DROP TABLE IF EXISTS `vote_log`;
CREATE TABLE `vote_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vote_site_id` int(10) NOT NULL DEFAULT '0' COMMENT 'The id of the vote site.',
  `user_id` int(50) NOT NULL COMMENT 'use rid',
  `ip` varchar(50) NOT NULL DEFAULT '127.0.0.1' COMMENT 'The ip wich they voted with',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT 'The time when they voted',
  PRIMARY KEY (`id`),
  KEY `FK_vote_log_vote_sites` (`vote_site_id`),
  CONSTRAINT `FK_vote_log_vote_sites` FOREIGN KEY (`vote_site_id`) REFERENCES `vote_sites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This holds the log of all who voted, we limit it to the ip so they can eventually cheat :P';
-- ----------------------------
-- Table structure for `vote_sites`
-- ----------------------------
DROP TABLE IF EXISTS `vote_sites`;
CREATE TABLE `vote_sites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vote_sitename` varchar(50) DEFAULT 'FusionCMS' COMMENT 'Name of the site.',
  `vote_url` varchar(255) DEFAULT 'http://' COMMENT 'The url of the site.',
  `vote_image` varchar(255) DEFAULT NULL,
  `hour_interval` int(10) NOT NULL DEFAULT '12' COMMENT 'The interval of when they are able to vote again.',
  `points_per_vote` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'How many points do the users get on each vote? (max 255)',
  `api_enabled` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Here you need to fill in all the vote sites where people can vote for.';
-- ----------------------------
-- Table structure for `vote_site_callback`
-- ----------------------------
DROP TABLE IF EXISTS `vote_site_callback`;
CREATE TABLE `vote_site_callback` (
  `site_id` int(10) NOT NULL,
  `custom_callback_url` varchar(255) NOT NULL COMMENT 'This is the url that we use to callback, example: http://www.openwow.com/?vote=2200&spb={account_id} just make sure that the callback is right in the settings on the site you are voting for.',
  KEY `FK__vote_sites` (`site_id`),
  CONSTRAINT `FK__vote_sites` FOREIGN KEY (`site_id`) REFERENCES `vote_sites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;