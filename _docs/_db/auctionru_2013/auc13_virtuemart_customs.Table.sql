--
-- Описание для таблицы auc13_virtuemart_customs
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_customs (
  virtuemart_custom_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  custom_parent_id int(1) UNSIGNED NOT NULL DEFAULT 0,
  virtuemart_vendor_id smallint(11) NOT NULL DEFAULT 1,
  custom_jplugin_id int(11) NOT NULL DEFAULT 0,
  custom_element char(50) NOT NULL DEFAULT '',
  admin_only tinyint(1) NOT NULL DEFAULT 0 COMMENT '1:Display in admin only',
  custom_title char(255) NOT NULL DEFAULT '' COMMENT 'field title',
  custom_tip char(255) NOT NULL DEFAULT '' COMMENT 'tip',
  custom_value char(255) DEFAULT NULL COMMENT 'defaut value',
  custom_field_desc char(255) DEFAULT NULL COMMENT 'description or unit',
  field_type char(1) NOT NULL DEFAULT '0' COMMENT 'S:string,I:int,P:parent, B:bool,D:date,T:time,H:hidden',
  is_list tinyint(1) NOT NULL DEFAULT 0 COMMENT 'list of values',
  is_hidden tinyint(1) NOT NULL DEFAULT 0 COMMENT '1:hidden',
  is_cart_attribute tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Add attributes to cart',
  layout_pos char(24) DEFAULT NULL COMMENT 'Layout Position',
  custom_params text DEFAULT NULL,
  shared tinyint(1) NOT NULL DEFAULT 0 COMMENT 'valide for all vendors?',
  published tinyint(1) NOT NULL DEFAULT 1,
  created_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_by int(11) NOT NULL DEFAULT 0,
  ordering int(2) NOT NULL DEFAULT 0,
  modified_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified_by int(11) NOT NULL DEFAULT 0,
  locked_on datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  locked_by int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (virtuemart_custom_id),
  INDEX idx_custom_parent_id (custom_parent_id),
  INDEX idx_custom_plugin_element (custom_element),
  INDEX idx_custom_plugin_ordering (ordering),
  INDEX idx_custom_plugin_virtuemart_vendor_id (virtuemart_vendor_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 21
AVG_ROW_LENGTH = 84
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'custom fields definition';