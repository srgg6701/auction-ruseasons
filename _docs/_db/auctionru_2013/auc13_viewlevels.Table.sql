--
-- Описание для таблицы auc13_viewlevels
--
CREATE TABLE IF NOT EXISTS auc13_viewlevels (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  title varchar(100) NOT NULL DEFAULT '',
  ordering int(11) NOT NULL DEFAULT 0,
  rules varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (id),
  UNIQUE INDEX idx_assetgroup_title_lookup (title)
)
ENGINE = MYISAM
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 26
character SET utf8
COLLATE utf8_general_ci;