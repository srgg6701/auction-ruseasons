--
-- Описание для таблицы auc13_update_sites_extensions
--
CREATE TABLE IF NOT EXISTS auc13_update_sites_extensions (
  update_site_id int(11) NOT NULL DEFAULT 0,
  extension_id int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (update_site_id, extension_id)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 9
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Links extensions to update sites';