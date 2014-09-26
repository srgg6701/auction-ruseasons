--
-- Описание для таблицы auc13_virtuemart_migration_oldtonew_ids
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_migration_oldtonew_ids (
  id smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  cats longblob DEFAULT NULL,
  catsxref blob DEFAULT NULL,
  manus longblob DEFAULT NULL,
  mfcats blob DEFAULT NULL,
  shoppergroups longblob DEFAULT NULL,
  products longblob DEFAULT NULL,
  products_start int(1) DEFAULT NULL,
  orderstates blob DEFAULT NULL,
  orders longblob DEFAULT NULL,
  orders_start int(1) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 1
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'xref table for vm1 ids to vm2 ids';