--
-- Описание для таблицы auc13_acymailing_list
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_list (
  name varchar(250) NOT NULL,
  description text DEFAULT NULL,
  ordering smallint(5) UNSIGNED DEFAULT NULL,
  listid smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  published tinyint(4) DEFAULT NULL,
  userid int(10) UNSIGNED DEFAULT NULL,
  alias varchar(250) DEFAULT NULL,
  color varchar(30) DEFAULT NULL,
  visible tinyint(4) NOT NULL DEFAULT 1,
  welmailid mediumint(9) DEFAULT NULL,
  unsubmailid mediumint(9) DEFAULT NULL,
  type enum ('list', 'campaign') NOT NULL DEFAULT 'list',
  access_sub varchar(250) DEFAULT 'all',
  access_manage varchar(250) NOT NULL DEFAULT 'none',
  languages varchar(250) NOT NULL DEFAULT 'all',
  PRIMARY KEY (listid),
  INDEX typeorderingindex (type, ordering),
  INDEX typeuseridindex (type, userid),
  INDEX useridindex (userid)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 88
character SET utf8
COLLATE utf8_general_ci;