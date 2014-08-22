--
-- Описание для таблицы auc13_acymailing_url
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_url (
  urlid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(250) NOT NULL,
  url text NOT NULL,
  PRIMARY KEY (urlid),
  INDEX url (url (250))
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;