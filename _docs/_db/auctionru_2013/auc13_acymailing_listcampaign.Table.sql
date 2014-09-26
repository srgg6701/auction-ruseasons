--
-- Описание для таблицы auc13_acymailing_listcampaign
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_listcampaign (
  campaignid smallint(5) UNSIGNED NOT NULL,
  listid smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (campaignid, listid)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;