--
-- Table structure for table `ext_sysmanager_extension`
--

CREATE TABLE `ext_sysmanager_extension` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`date_create` int(10) unsigned NOT NULL default '0',
	`date_update` int(10) unsigned NOT NULL,
	`ext` int(10) unsigned NOT NULL,
	`version` varchar(16) NOT NULL,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;