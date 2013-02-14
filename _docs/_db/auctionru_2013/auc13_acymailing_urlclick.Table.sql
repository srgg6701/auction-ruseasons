--
-- Описание для таблицы auc13_acymailing_urlclick
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_urlclick (
  urlid int(10) UNSIGNED NOT NULL,
  mailid mediumint(8) UNSIGNED NOT NULL,
  click smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  subid int(10) UNSIGNED NOT NULL,
  date int(10) UNSIGNED NOT NULL,
  ip varchar(100) DEFAULT NULL,
  PRIMARY KEY (urlid, mailid, subid),
  INDEX dateindex (date),
  INDEX mailidindex (mailid),
  INDEX subidindex (subid)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;