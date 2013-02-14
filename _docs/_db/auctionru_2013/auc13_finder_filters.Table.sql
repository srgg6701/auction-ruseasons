--
-- Описание для таблицы auc13_finder_filters
--
CREATE TABLE IF NOT EXISTS auc13_finder_filters (
  filter_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  state tinyint(1) NOT NULL DEFAULT 1,
  created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(10) UNSIGNED NOT NULL,
  created_by_alias varchar(255) NOT NULL,
  modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  map_count int(10) UNSIGNED NOT NULL DEFAULT 0,
  data text NOT NULL,
  params mediumtext DEFAULT NULL,
  PRIMARY KEY (filter_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;