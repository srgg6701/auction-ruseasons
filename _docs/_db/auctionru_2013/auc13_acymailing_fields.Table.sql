--
-- Описание для таблицы auc13_acymailing_fields
--
CREATE TABLE IF NOT EXISTS auc13_acymailing_fields (
  fieldid smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  fieldname varchar(250) NOT NULL,
  namekey varchar(50) NOT NULL,
  type varchar(50) DEFAULT NULL,
  value text NOT NULL,
  published tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  ordering smallint(5) UNSIGNED DEFAULT 99,
  options text DEFAULT NULL,
  core tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  required tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  backend tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  frontcomp tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `default` varchar(250) DEFAULT NULL,
  listing tinyint(3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (fieldid),
  UNIQUE INDEX namekey (namekey),
  INDEX orderingindex (published, ordering)
)
ENGINE = MYISAM
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 49
character SET utf8
COLLATE utf8_general_ci;