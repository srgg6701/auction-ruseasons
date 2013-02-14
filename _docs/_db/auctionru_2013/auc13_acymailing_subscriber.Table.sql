--
-- Описание для таблицы auc13_acymailing_subscriber
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_subscriber (
  subid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  email varchar(200) NOT NULL,
  userid int(10) UNSIGNED NOT NULL DEFAULT 0,
  name varchar(250) NOT NULL,
  created int(10) UNSIGNED DEFAULT NULL,
  confirmed tinyint(4) NOT NULL DEFAULT 0,
  enabled tinyint(4) NOT NULL DEFAULT 1,
  accept tinyint(4) NOT NULL DEFAULT 1,
  ip varchar(100) DEFAULT NULL,
  html tinyint(4) NOT NULL DEFAULT 1,
  `key` varchar(250) DEFAULT NULL,
  PRIMARY KEY (subid),
  UNIQUE INDEX email (email),
  INDEX queueindex (enabled, accept, confirmed),
  INDEX userid (userid)
)
ENGINE = MYISAM
AUTO_INCREMENT = 166
AVG_ROW_LENGTH = 98
character SET utf8
COLLATE utf8_general_ci;