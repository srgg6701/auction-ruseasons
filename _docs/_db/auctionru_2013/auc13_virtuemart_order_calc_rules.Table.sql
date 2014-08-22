--
-- Описание для таблицы auc13_virtuemart_order_calc_rules
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_order_calc_rules (
  virtuemart_order_calc_rule_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_order_id int(11) DEFAULT NULL,
  virtuemart_vendor_id smallint(11) NOT NULL DEFAULT 1,
  virtuemart_order_item_id int(11) DEFAULT NULL,
  calc_rule_name char(64) NOT NULL DEFAULT '' COMMENT 'Name of the rule',
  calc_kind char(16) NOT NULL DEFAULT '' COMMENT 'Discount/Tax/Margin/Commission',
  calc_mathop char(16) NOT NULL DEFAULT '' COMMENT 'Discount/Tax/Margin/Commission',
  calc_amount decimal(15, 5) NOT NULL DEFAULT 0.00000,
  calc_value decimal(15, 5) NOT NULL DEFAULT 0.00000,
  calc_currency smallint(1) DEFAULT NULL,
  calc_params varchar(18000) DEFAULT NULL,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_order_calc_rule_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Stores all calculation rules which are part of an order';