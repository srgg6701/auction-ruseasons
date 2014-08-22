--
-- Описание для таблицы auc13_virtuemart_calcs
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_calcs (
  virtuemart_calc_id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  virtuemart_vendor_id smallint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Belongs to vendor',
  calc_jplugin_id int(11) NOT NULL DEFAULT 0,
  calc_name char(64) NOT NULL DEFAULT '' COMMENT 'Name of the rule',
  calc_descr char(128) NOT NULL DEFAULT '' COMMENT 'Description',
  calc_kind char(16) NOT NULL DEFAULT '' COMMENT 'Discount/Tax/Margin/Commission',
  calc_value_mathop char(8) NOT NULL DEFAULT '' COMMENT 'the mathematical operation like (+,-,+%,-%)',
  calc_value decimal(10, 4) NOT NULL DEFAULT 0.0000 COMMENT 'The Amount',
  calc_currency smallint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Currency of the Rule',
  calc_shopper_published tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Visible for Shoppers',
  calc_vendor_published tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Visible for Vendors',
  publish_up datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Startdate if nothing is set = permanent',
  publish_down datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Enddate if nothing is set = permanent',
  for_override tinyint(1) NOT NULL DEFAULT 0,
  calc_params varchar(18000) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  shared tinyint(1) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_calc_id),
  INDEX i_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci;