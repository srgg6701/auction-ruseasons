--
-- Описание для таблицы auc13_acymailing_filter
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_filter (
  filid mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(250) DEFAULT NULL,
  description text DEFAULT NULL,
  published tinyint(3) UNSIGNED DEFAULT NULL,
  lasttime int(10) UNSIGNED DEFAULT NULL,
  `trigger` text DEFAULT NULL,
  report text DEFAULT NULL,
  action text DEFAULT NULL,
  filter text DEFAULT NULL,
  PRIMARY KEY (filid)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;