--
-- Описание для таблицы auc13_extensions
--
CREATE TABLE IF NOT EXISTS auc13_extensions (
  extension_id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  type varchar(20) NOT NULL,
  element varchar(100) NOT NULL,
  folder varchar(100) NOT NULL,
  client_id tinyint(3) NOT NULL,
  enabled tinyint(3) NOT NULL DEFAULT 1,
  access int(10) UNSIGNED NOT NULL DEFAULT 1,
  protected tinyint(3) NOT NULL DEFAULT 0,
  manifest_cache text NOT NULL,
  params text NOT NULL,
  custom_data text NOT NULL,
  system_data text NOT NULL,
  checked_out int(10) UNSIGNED NOT NULL DEFAULT 0,
  checked_out_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ordering int(11) DEFAULT 0,
  state int(11) DEFAULT 0,
  PRIMARY KEY (extension_id),
  INDEX element_clientid (element, client_id),
  INDEX element_folder_clientid (element, folder, client_id),
  INDEX extension (type, element, folder, client_id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 10069
AVG_ROW_LENGTH = 485
character SET utf8
COLLATE utf8_general_ci;