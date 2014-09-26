--
-- Описание для таблицы auc13_virtuemart_vendors_ru_ru
--
CREATE TABLE IF NOT EXISTS auc13_virtuemart_vendors_ru_ru (
  virtuemart_vendor_id int(1) UNSIGNED NOT NULL,
  vendor_store_desc text NOT NULL,
  vendor_terms_of_service text NOT NULL,
  vendor_legal_info text NOT NULL,
  vendor_store_name char(180) NOT NULL DEFAULT '',
  vendor_phone char(26) NOT NULL DEFAULT '',
  vendor_url char(255) NOT NULL DEFAULT '',
  slug char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (virtuemart_vendor_id),
  UNIQUE INDEX slug (slug)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 5984
character SET utf8
COLLATE utf8_general_ci;