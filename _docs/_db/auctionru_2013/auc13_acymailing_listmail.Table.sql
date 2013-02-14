--
-- Описание для таблицы auc13_acymailing_listmail
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_listmail (
  listid smallint(5) UNSIGNED NOT NULL,
  mailid mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (listid, mailid)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;