--
-- Описание для таблицы auc13_virtuemart_coupons
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_coupons (
  virtuemart_coupon_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  coupon_code char(32) NOT NULL DEFAULT '',
  percent_or_total enum ('percent', 'total') NOT NULL DEFAULT 'percent',
  coupon_type enum ('gift', 'permanent') NOT NULL DEFAULT 'gift',
  coupon_value decimal(15, 5) NOT NULL DEFAULT 0.00000,
  coupon_start_date datetime DEFAULT NULL,
  coupon_expiry_date datetime DEFAULT NULL,
  coupon_value_valid decimal(15, 5) NOT NULL DEFAULT 0.00000,
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_coupon_id),
  INDEX idx_coupon_code (coupon_code)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Used to store coupon codes';