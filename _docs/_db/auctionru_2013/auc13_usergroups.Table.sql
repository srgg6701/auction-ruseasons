--
-- Описание для таблицы auc13_usergroups
--
CREATE TABLE IF NOT EXISTS auc13_usergroups (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  parent_id int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Adjacency List Reference Id',
  lft int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set lft.',
  rgt int(11) NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.',
  title varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  INDEX idx_usergroup_adjacency_lookup (parent_id),
  INDEX idx_usergroup_nested_set_lookup (lft, rgt),
  UNIQUE INDEX idx_usergroup_parent_title_lookup (parent_id, title),
  INDEX idx_usergroup_title_lookup (title)
)
ENGINE = MYISAM
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 30
character SET utf8
COLLATE utf8_general_ci;