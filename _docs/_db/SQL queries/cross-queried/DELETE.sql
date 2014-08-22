DELETE
FROM  auc13_virtuemart_products 
  WHERE virtuemart_product_id  > 1

DELETE
FROM  auc13_virtuemart_products_ru_ru 
  WHERE virtuemart_product_id  > 1

DELETE
FROM  auc13_virtuemart_prices 
  WHERE
virtuemart_product_price_id > 1
 
 DELETE
FROM  auc13_virtuemart_product_categories
  WHERE id > 1

DELETE
FROM  auc13_virtuemart_medias 
  WHERE virtuemart_media_id > 1

DELETE
FROM  auc13_virtuemart_product_medias 
  WHERE id > 1


