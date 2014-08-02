SELECT prods.virtuemart_product_id, 
       prods_ru.product_name,
       product_available_date, auction_date_finish
FROM  auc13_virtuemart_products             AS prods,
      auc13_virtuemart_product_categories   AS pcats,
      auc13_virtuemart_products_ru_ru       AS prods_ru
  WHERE product_available_date < NOW() AND auction_date_finish > NOW()
        
        AND prods.virtuemart_product_id NOT IN ( SELECT virtuemart_product_id FROM auc13_dev_lots_active )
        
        AND pcats.virtuemart_product_id = prods.virtuemart_product_id
        AND pcats.virtuemart_category_id IN (
    SELECT ccats.category_child_id 
  FROM auc13_virtuemart_category_categories AS ccats, 
       auc13_virtuemart_categories          AS cts       
 WHERE category_parent_id IN
      ( SELECT cats_cats.category_child_id 
          FROM auc13_virtuemart_category_categories AS cats_cats
    INNER JOIN auc13_virtuemart_categories          AS cats 
               ON cats.virtuemart_category_id = cats_cats.category_child_id
                  AND cats_cats.category_parent_id = 0 )
   AND ccats.category_child_id = cts.virtuemart_category_id
   AND cts.category_layout = 'online' 
  ) 
  AND prods_ru.virtuemart_product_id = prods.virtuemart_product_id
