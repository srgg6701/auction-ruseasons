--
-- Описание для таблицы auc13_acymailing_queue
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_queue (
  senddate int(10) UNSIGNED NOT NULL,
  subid int(10) UNSIGNED NOT NULL,
  mailid mediumint(8) UNSIGNED NOT NULL,
  priority tinyint(3) UNSIGNED DEFAULT 3,
  try tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  paramqueue varchar(250) DEFAULT NULL,
  PRIMARY KEY (subid, mailid),
  INDEX listingindex (senddate, subid),
  INDEX mailidindex (mailid),
  INDEX orderingindex (priority, senddate, subid)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;