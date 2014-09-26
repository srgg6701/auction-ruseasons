--
-- Описание для таблицы auc13_csvi_related_products_1360822840
--
CREATE TABLE IF NOT EXISTS auc13_csvi_related_products_1360822840 (
  product_sku varchar(64) NOT NULL,
  related_sku text NOT NULL
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'Related products import for CSVI';