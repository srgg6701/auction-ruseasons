--
-- Описание для таблицы auc13_acymailing_template
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_template (
  tempid smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(250) DEFAULT NULL,
  description text DEFAULT NULL,
  body longtext DEFAULT NULL,
  altbody longtext DEFAULT NULL,
  created int(10) UNSIGNED DEFAULT NULL,
  published tinyint(4) NOT NULL DEFAULT 1,
  premium tinyint(4) NOT NULL DEFAULT 0,
  ordering smallint(5) UNSIGNED NOT NULL DEFAULT 99,
  namekey varchar(50) NOT NULL,
  styles text DEFAULT NULL,
  subject varchar(250) DEFAULT NULL,
  stylesheet text DEFAULT NULL,
  fromname varchar(250) DEFAULT NULL,
  fromemail varchar(250) DEFAULT NULL,
  replyname varchar(250) DEFAULT NULL,
  replyemail varchar(250) DEFAULT NULL,
  PRIMARY KEY (tempid),
  UNIQUE INDEX namekey (namekey),
  INDEX orderingindex (ordering)
)
ENGINE = MYISAM
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5112
character SET utf8
COLLATE utf8_general_ci;