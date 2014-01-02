CREATE TABLE `pvp_arenateam_cache` (
  `cache_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(2) unsigned DEFAULT NULL,
  `time` int(20) unsigned DEFAULT NULL,
  `id` int(20) unsigned DEFAULT NULL,
  `rank` int(6) unsigned DEFAULT NULL,
  `lastweek_rank` int(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`cache_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1003 DEFAULT CHARSET=utf8;