--
-- Описание для таблицы auc13_acymailing_mail
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_mail (
  mailid mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  subject varchar(250) NOT NULL,
  body longtext NOT NULL,
  altbody longtext NOT NULL,
  published tinyint(4) DEFAULT 1,
  senddate int(10) UNSIGNED DEFAULT NULL,
  created int(10) UNSIGNED DEFAULT NULL,
  fromname varchar(250) DEFAULT NULL,
  fromemail varchar(250) DEFAULT NULL,
  replyname varchar(250) DEFAULT NULL,
  replyemail varchar(250) DEFAULT NULL,
  type enum ('news', 'autonews', 'followup', 'unsub', 'welcome', 'notification') NOT NULL DEFAULT 'news',
  visible tinyint(4) NOT NULL DEFAULT 1,
  userid int(10) UNSIGNED DEFAULT NULL,
  alias varchar(250) DEFAULT NULL,
  attach text DEFAULT NULL,
  html tinyint(4) NOT NULL DEFAULT 1,
  tempid smallint(6) NOT NULL DEFAULT 0,
  `key` varchar(200) DEFAULT NULL,
  frequency varchar(50) DEFAULT NULL,
  params text DEFAULT NULL,
  sentby int(10) UNSIGNED DEFAULT NULL,
  metakey text DEFAULT NULL,
  metadesc text DEFAULT NULL,
  filter text DEFAULT NULL,
  PRIMARY KEY (mailid),
  INDEX senddate (senddate),
  INDEX typemailidindex (type, mailid),
  INDEX useridindex (userid)
)
ENGINE = MYISAM
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 376
character SET utf8
COLLATE utf8_general_ci;