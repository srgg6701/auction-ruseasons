--
-- Описание для таблицы auc13_finder_taxonomy
--
CREATE TABLE IF NOT EXISTS auc13_finder_taxonomy (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  parent_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  title varchar(255) NOT NULL,
  state tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  access tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  ordering tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  INDEX access (access),
  INDEX idx_parent_published (parent_id, state, access),
  INDEX ordering (ordering),
  INDEX parent_id (parent_id),
  INDEX state (state)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 20
character SET utf8
COLLATE utf8_general_ci;