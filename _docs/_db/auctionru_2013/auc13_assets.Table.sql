--
-- Описание для таблицы auc13_assets
--
CREATE TABLE IF NOT EXISTS auc13_assets (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  parent_id int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set parent.',
  lft int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set lft.',
  rgt int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.',
  level int(10) UNSIGNED NOT NULL COMMENT 'The cached level in the nested tree.',
  name varchar(50) NOT NULL COMMENT 'The unique name for the asset.
',
  title varchar(100) NOT NULL COMMENT 'The descriptive title for the asset.',
  rules varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (id),
  UNIQUE INDEX idx_asset_name (name),
  INDEX idx_lft_rgt (lft, rgt),
  INDEX idx_parent_id (parent_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 66
AVG_ROW_LENGTH = 139
character SET utf8
COLLATE utf8_general_ci;