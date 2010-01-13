--
-- Table structure for table `ext_sysmanager_extension`
--

CREATE TABLE `ext_sysmanager_extension` (
  `id` int(11) NOT NULL auto_increment,
  `date_create` int(10) unsigned NOT NULL default '0',
  `date_update` int(10) unsigned NOT NULL,
  `ext` smallint(5) unsigned NOT NULL,
  `version` varchar(16) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;