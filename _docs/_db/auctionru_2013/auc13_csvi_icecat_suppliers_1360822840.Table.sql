--
-- Описание для таблицы auc13_csvi_icecat_suppliers_1360822840
--
CREATE TABLE IF NOT EXISTS auc13_csvi_icecat_suppliers_1360822840 (
  supplier_id int(11) UNSIGNED NOT NULL,
  supplier_name varchar(255) NOT NULL,
  INDEX `Supplier name` (supplier_name),
  UNIQUE INDEX `Unique supplier` (supplier_id, supplier_name)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'ICEcat supplier data for CSVI';