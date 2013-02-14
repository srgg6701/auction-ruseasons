--
-- Описание для таблицы auc13_acymailing_history
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_history (
  subid int(10) UNSIGNED NOT NULL,
  date int(10) UNSIGNED NOT NULL,
  ip varchar(50) DEFAULT NULL,
  action varchar(50) NOT NULL COMMENT 'different actions: created,modified,confirmed',
  data text DEFAULT NULL,
  source text DEFAULT NULL,
  mailid mediumint(8) UNSIGNED DEFAULT NULL,
  INDEX actionindex (action, mailid),
  INDEX dateindex (date),
  INDEX subid (subid, date)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 456
character SET utf8
COLLATE utf8_general_ci;