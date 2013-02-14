--
-- Описание для таблицы auc13_csvi_icecat_index
--
CREATE TABLE IF NOT EXISTS auc13_csvi_icecat_index (
  path varchar(100) DEFAULT NULL,
  product_id int(2) DEFAULT NULL,
  updated int(14) DEFAULT NULL,
  quality varchar(6) DEFAULT NULL,
  supplier_id int(1) DEFAULT NULL,
  prod_id varchar(16) DEFAULT NULL,
  catid int(3) DEFAULT NULL,
  m_prod_id varchar(10) DEFAULT NULL,
  ean_upc varchar(10) DEFAULT NULL,
  on_market int(1) DEFAULT NULL,
  country_market varchar(10) DEFAULT NULL,
  model_name varchar(26) DEFAULT NULL,
  product_view int(5) DEFAULT NULL,
  high_pic varchar(51) DEFAULT NULL,
  high_pic_size int(5) DEFAULT NULL,
  high_pic_width int(3) DEFAULT NULL,
  high_pic_height int(3) DEFAULT NULL,
  m_supplier_id int(3) DEFAULT NULL,
  m_supplier_name varchar(51) DEFAULT NULL,
  INDEX manufacturer_name (supplier_id),
  INDEX product_mpn (prod_id)
)
ENGINE = MYISAM
character SET utf8
COLLATE utf8_general_ci
COMMENT = 'ICEcat index data for CSVI';