--
-- Описание для таблицы auc13_acymailing_userstats
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_userstats (
  mailid mediumint(8) UNSIGNED NOT NULL,
  subid int(10) UNSIGNED NOT NULL,
  html tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  sent tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  senddate int(10) UNSIGNED NOT NULL,
  open tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  opendate int(11) NOT NULL,
  bounce tinyint(4) NOT NULL DEFAULT 0,
  fail tinyint(4) NOT NULL DEFAULT 0,
  ip varchar(100) DEFAULT NULL,
  PRIMARY KEY (mailid, subid),
  INDEX senddateindex (senddate),
  INDEX subidindex (subid)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;