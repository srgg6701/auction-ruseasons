--
-- Описание для таблицы auc13_csvi_template_tables
--
CREATE TABLE IF NOT EXISTS auc13_csvi_template_tables (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  template_type_name varchar(55) NOT NULL,
  template_table varchar(55) NOT NULL,
  component varchar(55) NOT NULL,
  indexed int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX type_name (template_type_name, template_table, component)
)
ENGINE = MYISAM
AUTO_INCREMENT = 78
AVG_ROW_LENGTH = 70
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Template tables used per template type for CSVI';