--
-- Описание для таблицы auc13_csvi_available_fields
--
CREATE TABLE IF NOT EXISTS auc13_csvi_available_fields (
  id int(11) NOT NULL AUTO_INCREMENT,
  csvi_name varchar(255) NOT NULL,
  component_name varchar(55) NOT NULL,
  component_table varchar(55) NOT NULL,
  component varchar(55) NOT NULL,
  isprimary tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX component_name_table (component_name, component_table, component)
)
ENGINE = MYISAM
AUTO_INCREMENT = 656
AVG_ROW_LENGTH = 67
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Available fields for CSVI';