--
-- Описание для таблицы auc13_virtuemart_modules
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_modules (
  module_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  module_name char(255) DEFAULT NULL,
  module_description varchar(21000) DEFAULT NULL,
  module_perms char(255) DEFAULT NULL,
  published tinyint(1) NOT NULL DEFAULT 1,
  is_admin enum ('0', '1') NOT NULL DEFAULT '0',
  ordering int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (module_id),
  INDEX idx_module_name (module_name),
  INDEX idx_module_ordering (ordering)
)
ENGINE = MYISAM
AUTO_INCREMENT = 14
AVG_ROW_LENGTH = 130
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'VirtueMart Core Modules, not: Joomla modules';