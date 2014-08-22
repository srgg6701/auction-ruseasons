CREATE TABLE IF NOT EXISTS `#__acymailing_config` (
	`namekey` varchar(200) NOT NULL,
	`value` text,
	PRIMARY KEY (`namekey`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_fields` (
	`fieldid` smallint unsigned NOT NULL AUTO_INCREMENT,
	`fieldname` varchar(250) NOT NULL,
	`namekey` varchar(50) NOT NULL,
	`type` varchar(50) DEFAULT NULL,
	`value` text NOT NULL,
	`published` tinyint unsigned NOT NULL DEFAULT '1',
	`ordering` smallint unsigned DEFAULT '99',
	`options` text,
	`core` tinyint unsigned NOT NULL DEFAULT '0',
	`required` tinyint unsigned NOT NULL DEFAULT '0',
	`backend` tinyint unsigned NOT NULL DEFAULT '1',
	`frontcomp` tinyint unsigned NOT NULL DEFAULT '0',
	`default` varchar(250) DEFAULT NULL,
	`listing` tinyint unsigned DEFAULT NULL,
	PRIMARY KEY (`fieldid`),
	UNIQUE KEY `namekey` (`namekey`),
	KEY `orderingindex` (`published`,`ordering`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_filter` (
	`filid` mediumint unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(250) DEFAULT NULL,
	`description` text,
	`published` tinyint unsigned DEFAULT NULL,
	`lasttime` int unsigned DEFAULT NULL,
	`trigger` text,
	`report` text,
	`action` text,
	`filter` text,
	PRIMARY KEY (`filid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_history` (
	`subid` int unsigned NOT NULL,
	`date` int unsigned NOT NULL,
	`ip` varchar(50) DEFAULT NULL,
	`action` varchar(50) NOT NULL COMMENT 'different actions: created,modified,confirmed',
	`data` text,
	`source` text,
	`mailid` mediumint unsigned DEFAULT NULL,
	KEY `subid` (`subid`,`date`),
	KEY `dateindex` (`date`),
	KEY `actionindex` (`action`,`mailid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_list` (
	`name` varchar(250) NOT NULL,
	`description` text,
	`ordering` smallint unsigned DEFAULT NULL,
	`listid` smallint unsigned NOT NULL AUTO_INCREMENT,
	`published` tinyint DEFAULT NULL,
	`userid` int unsigned DEFAULT NULL,
	`alias` varchar(250) DEFAULT NULL,
	`color` varchar(30) DEFAULT NULL,
	`visible` tinyint NOT NULL DEFAULT '1',
	`welmailid` mediumint DEFAULT NULL,
	`unsubmailid` mediumint DEFAULT NULL,
	`type` enum('list','campaign') NOT NULL DEFAULT 'list',
	`access_sub` varchar(250) DEFAULT 'all',
	`access_manage` varchar(250) NOT NULL DEFAULT 'none',
	`languages` varchar(250) NOT NULL DEFAULT 'all',
	PRIMARY KEY (`listid`),
	KEY `typeorderingindex` (`type`,`ordering`),
	KEY `useridindex` (`userid`),
	KEY `typeuseridindex` (`type`,`userid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_listcampaign` (
	`campaignid` smallint unsigned NOT NULL,
	`listid` smallint unsigned NOT NULL,
	PRIMARY KEY (`campaignid`,`listid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_listmail` (
	`listid` smallint unsigned NOT NULL,
	`mailid` mediumint unsigned NOT NULL,
	PRIMARY KEY (`listid`,`mailid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_listsub` (
	`listid` smallint unsigned NOT NULL,
	`subid` int unsigned NOT NULL,
	`subdate` int unsigned DEFAULT NULL,
	`unsubdate` int unsigned DEFAULT NULL,
	`status` tinyint NOT NULL,
	PRIMARY KEY (`listid`,`subid`),
	KEY `subidindex` (`subid`),
	KEY `listidstatusindex` (`listid`,`status`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_mail` (
	`mailid` mediumint unsigned NOT NULL AUTO_INCREMENT,
	`subject` varchar(250) NOT NULL,
	`body` longtext NOT NULL,
	`altbody` longtext NOT NULL,
	`published` tinyint DEFAULT '1',
	`senddate` int unsigned DEFAULT NULL,
	`created` int unsigned DEFAULT NULL,
	`fromname` varchar(250) DEFAULT NULL,
	`fromemail` varchar(250) DEFAULT NULL,
	`replyname` varchar(250) DEFAULT NULL,
	`replyemail` varchar(250) DEFAULT NULL,
	`type` enum('news','autonews','followup','unsub','welcome','notification') NOT NULL DEFAULT 'news',
	`visible` tinyint NOT NULL DEFAULT '1',
	`userid` int unsigned DEFAULT NULL,
	`alias` varchar(250) DEFAULT NULL,
	`attach` text,
	`html` tinyint NOT NULL DEFAULT '1',
	`tempid` smallint NOT NULL DEFAULT '0',
	`key` varchar(200) DEFAULT NULL,
	`frequency` varchar(50) DEFAULT NULL,
	`params` text,
	`sentby` int unsigned DEFAULT NULL,
	`metakey` text,
	`metadesc` text,
	`filter` text,
	PRIMARY KEY (`mailid`),
	KEY `senddate` (`senddate`),
	KEY `typemailidindex` (`type`,`mailid`),
	KEY `useridindex` (`userid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_queue` (
	`senddate` int unsigned NOT NULL,
	`subid` int unsigned NOT NULL,
	`mailid` mediumint unsigned NOT NULL,
	`priority` tinyint unsigned DEFAULT '3',
	`try` tinyint unsigned NOT NULL DEFAULT '0',
	`paramqueue` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`subid`,`mailid`),
	KEY `listingindex` (`senddate`,`subid`),
	KEY `mailidindex` (`mailid`),
	KEY `orderingindex` (`priority`,`senddate`,`subid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_rules` (
	`ruleid` smallint unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(250) NOT NULL,
	`ordering` smallint DEFAULT NULL,
	`regex` text NOT NULL,
	`executed_on` text NOT NULL,
	`action_message` text NOT NULL,
	`action_user` text NOT NULL,
	`published` tinyint unsigned NOT NULL,
	PRIMARY KEY (`ruleid`),
	KEY `ordering` (`published`,`ordering`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_stats` (
	`mailid` mediumint unsigned NOT NULL,
	`senthtml` int unsigned NOT NULL DEFAULT '0',
	`senttext` int unsigned NOT NULL DEFAULT '0',
	`senddate` int unsigned NOT NULL,
	`openunique` mediumint unsigned NOT NULL DEFAULT '0',
	`opentotal` int unsigned NOT NULL DEFAULT '0',
	`bounceunique` mediumint unsigned NOT NULL DEFAULT '0',
	`fail` mediumint unsigned NOT NULL DEFAULT '0',
	`clicktotal` int unsigned NOT NULL DEFAULT '0',
	`clickunique` mediumint unsigned NOT NULL DEFAULT '0',
	`unsub` mediumint unsigned NOT NULL DEFAULT '0',
	`forward` mediumint unsigned NOT NULL DEFAULT '0',
	`bouncedetails` text,
	PRIMARY KEY (`mailid`),
	KEY `senddateindex` (`senddate`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_subscriber` (
	`subid` int unsigned NOT NULL AUTO_INCREMENT,
	`email` varchar(200) NOT NULL,
	`userid` int unsigned NOT NULL DEFAULT '0',
	`name` varchar(250) NOT NULL,
	`created` int unsigned DEFAULT NULL,
	`confirmed` tinyint NOT NULL DEFAULT '0',
	`enabled` tinyint NOT NULL DEFAULT '1',
	`accept` tinyint NOT NULL DEFAULT '1',
	`ip` varchar(100) DEFAULT NULL,
	`html` tinyint NOT NULL DEFAULT '1',
	`key` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`subid`),
	UNIQUE KEY `email` (`email`),
	KEY `userid` (`userid`),
	KEY `queueindex` (`enabled`,`accept`,`confirmed`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_template` (
	`tempid` smallint unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(250) DEFAULT NULL,
	`description` text,
	`body` longtext,
	`altbody` longtext,
	`created` int unsigned DEFAULT NULL,
	`published` tinyint NOT NULL DEFAULT '1',
	`premium` tinyint NOT NULL DEFAULT '0',
	`ordering` smallint unsigned NOT NULL DEFAULT '99',
	`namekey` varchar(50) NOT NULL,
	`styles` text,
	`subject` varchar(250) DEFAULT NULL,
	`stylesheet` text,
	`fromname` varchar(250) DEFAULT NULL,
	`fromemail` varchar(250) DEFAULT NULL,
	`replyname` varchar(250) DEFAULT NULL,
	`replyemail` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`tempid`),
	UNIQUE KEY `namekey` (`namekey`),
	KEY `orderingindex` (`ordering`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_url` (
	`urlid` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(250) NOT NULL,
	`url` text NOT NULL,
	PRIMARY KEY (`urlid`),
	KEY `url` (`url`(250))
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_urlclick` (
	`urlid` int unsigned NOT NULL,
	`mailid` mediumint unsigned NOT NULL,
	`click` smallint unsigned NOT NULL DEFAULT '0',
	`subid` int unsigned NOT NULL,
	`date` int unsigned NOT NULL,
	`ip` varchar(100) DEFAULT NULL,
	PRIMARY KEY (`urlid`,`mailid`,`subid`),
	KEY `dateindex` (`date`),
	KEY `mailidindex` (`mailid`),
	KEY `subidindex` (`subid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;

CREATE TABLE IF NOT EXISTS `#__acymailing_userstats` (
	`mailid` mediumint unsigned NOT NULL,
	`subid` int unsigned NOT NULL,
	`html` tinyint unsigned NOT NULL DEFAULT '1',
	`sent` tinyint unsigned NOT NULL DEFAULT '1',
	`senddate` int unsigned NOT NULL,
	`open` tinyint unsigned NOT NULL DEFAULT '0',
	`opendate` int NOT NULL,
	`bounce` tinyint NOT NULL DEFAULT '0',
	`fail` tinyint NOT NULL DEFAULT '0',
	`ip` varchar(100) DEFAULT NULL,
	PRIMARY KEY (`mailid`,`subid`),
	KEY `senddateindex` (`senddate`),
	KEY `subidindex` (`subid`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci*/;