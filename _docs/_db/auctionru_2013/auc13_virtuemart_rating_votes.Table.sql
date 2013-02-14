--
-- Описание для таблицы auc13_virtuemart_rating_votes
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_rating_votes (
  virtuemart_rating_vote_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_product_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  vote int(11) NOT NULL DEFAULT 0,
  lastip char(50) NOT NULL DEFAULT '0',
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_rating_vote_id),
  UNIQUE INDEX i_virtuemart_product_id (virtuemart_product_id, created_by)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Stores all ratings for a product';