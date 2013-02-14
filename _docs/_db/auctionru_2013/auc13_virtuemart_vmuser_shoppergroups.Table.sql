--
-- Описание для таблицы auc13_virtuemart_vmuser_shoppergroups
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_vmuser_shoppergroups (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_user_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_shoppergroup_id smallint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE INDEX i_virtuemart_user_id (virtuemart_user_id, virtuemart_shoppergroup_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 11
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'xref table for users to shopper group';