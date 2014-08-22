--
-- Описание для таблицы auc13_finder_links
--
CREATE TABLE IF NOT EXISTS auc13_finder_links (
  link_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  url varchar(255) NOT NULL,
  route varchar(255) NOT NULL,
  title varchar(255) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  indexdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  md5sum varchar(32) DEFAULT NULL,
  published tinyint(1) NOT NULL DEFAULT 1,
  state int(5) DEFAULT 1,
  access int(5) DEFAULT 0,
  language varchar(8) NOT NULL,
  publish_start_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  publish_end_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  start_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  end_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  list_price double UNSIGNED NOT NULL DEFAULT 0,
  sale_price double UNSIGNED NOT NULL DEFAULT 0,
  type_id int(11) NOT NULL,
  object mediumblob NOT NULL,
  PRIMARY KEY (link_id),
  INDEX idx_md5 (md5sum),
  INDEX idx_published_list (published, state, access, publish_start_date, publish_end_date, list_price),
  INDEX idx_published_sale (published, state, access, publish_start_date, publish_end_date, sale_price),
  INDEX idx_title (title),
  INDEX idx_type (type_id),
  INDEX idx_url (url (75))
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;