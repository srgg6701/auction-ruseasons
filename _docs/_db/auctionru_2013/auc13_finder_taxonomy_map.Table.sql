--
-- Описание для таблицы auc13_finder_taxonomy_map
--
CREATE TABLE IF NOT EXISTS auc13_finder_taxonomy_map (
  link_id int(10) UNSIGNED NOT NULL,
  node_id int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (link_id, node_id),
  INDEX link_id (link_id),
  INDEX node_id (node_id)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci;