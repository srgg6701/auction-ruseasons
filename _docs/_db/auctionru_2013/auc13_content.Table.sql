--
-- Описание для таблицы auc13_content
--
CREATE TABLE IF NOT EXISTS auc13_content (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  asset_id int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
  title varchar(255) NOT NULL DEFAULT '',
  alias varchar(255) binary character SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  title_alias varchar(255) binary character SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'Deprecated in Joomla! 3.0',
  introtext mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  state tinyint(3) NOT NULL DEFAULT 0,
  sectionid int(10) UNSIGNED NOT NULL DEFAULT 0,
  mask int(10) UNSIGNED NOT NULL DEFAULT 0,
  catid int(10) UNSIGNED NOT NULL DEFAULT 0,
  created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(10) UNSIGNED NOT NULL DEFAULT 0,
  created_by_alias varchar(255) NOT NULL DEFAULT '',
  modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_up datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_down datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  images text NOT NULL,
  urls text NOT NULL,
  attribs varchar(5120) NOT NULL,
  version int(10) UNSIGNED NOT NULL DEFAULT 1,
  parentid int(10) UNSIGNED NOT NULL DEFAULT 0,
  ordering int(11) NOT NULL DEFAULT 0,
  metakey text NOT NULL,
  metadesc text NOT NULL,
  access int(10) UNSIGNED NOT NULL DEFAULT 0,
  hits int(10) UNSIGNED NOT NULL DEFAULT 0,
  metadata text NOT NULL,
  featured tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Set if article is featured.',
  language char(7) NOT NULL COMMENT 'The language code for the article.',
  xreference varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  PRIMARY KEY (id),
  INDEX idx_access (access),
  INDEX idx_catid (catid),
  INDEX idx_checkout (checked_out),
  INDEX idx_createdby (created_by),
  INDEX idx_featured_catid (featured, catid),
  INDEX idx_language (language),
  INDEX idx_state (state),
  INDEX idx_xreference (xreference)
)
ENGINE = MYISAM
AUTO_INCREMENT = 22
AVG_ROW_LENGTH = 5348
character SET utf8
COLLATE utf8_general_ci;