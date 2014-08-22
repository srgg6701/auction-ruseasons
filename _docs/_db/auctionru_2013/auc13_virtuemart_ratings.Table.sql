--
-- Описание для таблицы auc13_virtuemart_ratings
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_ratings (
  virtuemart_rating_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  rates int(11) NOT NULL DEFAULT 0,
  ratingcount int(1) UNSIGNED NOT NULL DEFAULT 0,
  rating decimal(10, 1) NOT NULL DEFAULT 0.0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_rating_id),
  UNIQUE INDEX i_virtuemart_product_id (virtuemart_product_id, virtuemart_rating_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Stores all ratings for a product';