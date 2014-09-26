--
-- Описание для таблицы auc13_virtuemart_rating_reviews
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_rating_reviews (
  virtuemart_rating_review_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  comment varchar(18000) DEFAULT NULL,
  review_ok tinyint(1) NOT NULL DEFAULT 0,
  review_rates int(1) UNSIGNED NOT NULL DEFAULT 0,
  review_ratingcount int(1) UNSIGNED NOT NULL DEFAULT 0,
  review_rating decimal(10, 2) NOT NULL DEFAULT 0.00,
  review_editable tinyint(1) NOT NULL DEFAULT 1,
  lastip char(50) NOT NULL DEFAULT '0',
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_rating_review_id),
  UNIQUE INDEX i_virtuemart_product_id (virtuemart_product_id, created_by)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;