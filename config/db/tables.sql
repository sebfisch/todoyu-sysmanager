--
-- Table structure for table `ext_sysmanager_extension`
--

CREATE TABLE `ext_sysmanager_extension` (
  `id` int(11) NOT NULL auto_increment,
  `date_update` int(10) unsigned NOT NULL,
  `date_create` int(10) unsigned NOT NULL default '0',
  `version` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;