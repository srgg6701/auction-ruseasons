--
-- Описание для таблицы auc13_csvi_template_types_current
--
CREATE TABLE IF NOT EXISTS auc13_csvi_template_types_current (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  template_type_name varchar(55) NOT NULL,
  template_type varchar(55) NOT NULL,
  component varchar(55) NOT NULL COMMENT 'Name of the component',
  url varchar(100) DEFAULT NULL COMMENT 'The URL of the page the import is for',
  options varchar(255) NOT NULL DEFAULT 'fields' COMMENT 'The template pages to show for the template type',
  PRIMARY KEY (id),
  UNIQUE INDEX type_name (template_type_name, template_type, component)
)
ENGINE = MYISAM
AUTO_INCREMENT = 38
AVG_ROW_LENGTH = 113
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Template types for CSVI';