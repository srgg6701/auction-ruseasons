--
-- Описание для таблицы auc13_acymailing_listsub
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_listsub (
  listid smallint(5) UNSIGNED NOT NULL,
  subid int(10) UNSIGNED NOT NULL,
  subdate int(10) UNSIGNED DEFAULT NULL,
  unsubdate int(10) UNSIGNED DEFAULT NULL,
  status tinyint(4) NOT NULL,
  PRIMARY KEY (listid, subid),
  INDEX listidstatusindex (listid, status),
  INDEX subidindex (subid)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 16
character SET utf8
COLLATE utf8_general_ci;