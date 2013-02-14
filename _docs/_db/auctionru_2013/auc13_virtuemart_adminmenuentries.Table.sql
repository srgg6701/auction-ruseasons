--
-- Описание для таблицы auc13_virtuemart_adminmenuentries
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_adminmenuentries (
  id tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  module_id tinyint(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'The ID of the VM Module, this Item is assigned to',
  parent_id tinyint(11) UNSIGNED NOT NULL DEFAULT 0,
  name char(64) NOT NULL DEFAULT '0',
  link char(64) NOT NULL DEFAULT '0',
  depends char(64) NOT NULL DEFAULT '' COMMENT 'Names of the Parameters, this Item depends on',
  icon_class char(96) DEFAULT NULL,
  ordering int(2) NOT NULL DEFAULT 0,
  published tinyint(1) NOT NULL DEFAULT 1,
  tooltip char(128) DEFAULT NULL,
  view char(32) DEFAULT NULL,
  task char(32) DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX module_id (module_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 29
AVG_ROW_LENGTH = 1449
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Administration Menu Items';