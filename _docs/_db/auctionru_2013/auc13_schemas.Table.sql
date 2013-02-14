--
-- Описание для таблицы auc13_schemas
--
CREATE TABLE IF NOT EXISTS auc13_schemas (
  extension_id int(11) NOT NULL,
  version_id varchar(20) NOT NULL,
  PRIMARY KEY (extension_id, version_id)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 20
character SET utf8
COLLATE utf8_general_ci;