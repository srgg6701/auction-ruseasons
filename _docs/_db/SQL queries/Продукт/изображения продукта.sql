
  SELECT media.* 
  FROM auc13_virtuemart_product_medias AS media,
       auc13_virtuemart_products_ru_ru AS ruru
 WHERE product_name LIKE '%Натюрморт с розами и маком%' 
   AND media.virtuemart_product_id = ruru.virtuemart_product_id