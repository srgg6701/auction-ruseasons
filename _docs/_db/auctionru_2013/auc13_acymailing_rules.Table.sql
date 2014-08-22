--
-- Описание для таблицы auc13_acymailing_rules
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_rules (
  ruleid smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(250) NOT NULL,
  ordering smallint(6) DEFAULT NULL,
  regex text NOT NULL,
  executed_on text NOT NULL,
  action_message text NOT NULL,
  action_user text NOT NULL,
  published tinyint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (ruleid),
  INDEX ordering (published, ordering)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;