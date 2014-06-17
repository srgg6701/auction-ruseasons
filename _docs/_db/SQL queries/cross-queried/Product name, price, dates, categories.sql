SELECT products_ru_ru. virtuemart_product_id      AS product_id,
       products_ru_ru.                               product_name,  
       product_prices. product_price              AS price,
    -- product_categories. virtuemart_category_id     AS category_id,
     CONCAT( (select category_name 
              FROM auc13_virtuemart_categories_ru_ru    AS ctsru, 
                   auc13_virtuemart_category_categories AS catscats
              WHERE ctsru.virtuemart_category_id = catscats.category_parent_id
                AND catscats.category_child_id = product_categories. virtuemart_category_id
            ),"/",
     categories_ru_ru.                               category_name) AS categories,
       -- product_prices. override, product_prices. product_override_price,
       product_prices. product_price_publish_up   AS publish_up,
       product_prices. product_price_publish_down AS publish_down
        FROM auc13_virtuemart_products_ru_ru     
     AS products_ru_ru
  INNER JOIN auc13_virtuemart_product_prices     
     AS product_prices     ON products_ru_ru.     virtuemart_product_id = 
                              product_prices.     virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories 
     AS product_categories ON products_ru_ru.     virtuemart_product_id = 
                          product_categories.     virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru   
     AS categories_ru_ru   ON product_categories. virtuemart_category_id = 
                                categories_ru_ru. virtuemart_category_id
  ORDER BY products_ru_ru.product_name