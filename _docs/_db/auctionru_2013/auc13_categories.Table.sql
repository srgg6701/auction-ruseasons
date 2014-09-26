--
-- Описание для таблицы auc13_categories
--
CREATE TABLE IF NOT EXISTS auc13_categories (
  id int(11) NOT NULL AUTO_INCREMENT,
  asset_id int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
  parent_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  lft int(11) NOT NULL DEFAULT 0,
  rgt int(11) NOT NULL DEFAULT 0,
  level int(10) UNSIGNED NOT NULL DEFAULT 0,
  path varchar(255) NOT NULL DEFAULT '',
  extension varchar(50) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL,
  alias varchar(255) binary character SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  note varchar(255) NOT NULL DEFAULT '',
  description mediumtext NOT NULL,
  published tinyint(1) NOT NULL DEFAULT 0,
  checked_out int(11) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  access int(10) UNSIGNED NOT NULL DEFAULT 0,
  params text NOT NULL,
  metadesc varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  metakey varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  metadata varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  created_user_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  created_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_user_id int(10) UNSIGNED NOT NULL DEFAULT 0,
  modified_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  hits int(10) UNSIGNED NOT NULL DEFAULT 0,
  language char(7) NOT NULL,
  PRIMARY KEY (id),
  INDEX cat_idx (extension, published, access),
  INDEX idx_access (access),
  INDEX idx_alias (alias),
  INDEX idx_checkout (checked_out),
  INDEX idx_language (language),
  INDEX idx_left_right (lft, rgt),
  INDEX idx_path (path)
)
ENGINE = MYISAM
AUTO_INCREMENT = 10
AVG_ROW_LENGTH = 185
character SET utf8
COLLATE utf8_general_ci;