--
-- Описание для таблицы auc13_csvi_replacements_1360822840
--
CREATE TABLE IF NOT EXISTS auc13_csvi_replacements_1360822840 (
  id int(10) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  findtext text NOT NULL,
  replacetext text NOT NULL,
  multivalue enum ('0', '1') NOT NULL,
  method enum ('text', 'regex') NOT NULL DEFAULT 'text',
  checked_out int(11) UNSIGNED DEFAULT 0,
  checked_out_time datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Replacement rules for CSVI';