SELECT products_ru_ru. virtuemart_product_id      AS product_id,
       products_ru_ru.                               product_name,  
       products.                                     auction_number,
       products.                                     contract_number,
       products.                                     lot_number,
    -- product_categories. virtuemart_category_id     AS category_id,
     CONCAT( (select category_name 
                FROM auc13_virtuemart_categories_ru_ru    AS ctsru, 
                    auc13_virtuemart_category_categories AS catscats
              WHERE ctsru.virtuemart_category_id = catscats.category_parent_id
                AND catscats.category_child_id = product_categories. virtuemart_category_id
            ),"/",
     categories_ru_ru.             category_name) AS categories,
       -- product_prices. override, product_prices. product_override_price,
       product_prices. product_price_publish_up   AS publish_up,
       product_prices. product_price_publish_down AS publish_down,
       IF ( product_prices.created_on <> product_prices.modified_on, 
             CONCAT( DATE_FORMAT(product_prices.created_on, '%d.%m.%Y %h:%i'), 
                     ", ", 
                     DATE_FORMAT(product_prices.modified_on, '%d.%m.%Y %h:%i')        
                   ),DATE_FORMAT(product_prices.created_on, '%d.%m.%Y %h:%i')
       )                                         AS 'created/modified',
       TRUNCATE(product_prices. product_price,0)  AS price,
       IF(         dsprices. sales_price IS NOT NULL,
          TRUNCATE(dsprices. sales_price,0), '')          AS sales_price
        FROM auc13_virtuemart_products_ru_ru     
     AS products_ru_ru
  INNER JOIN auc13_virtuemart_products     
     AS products          ON  products_ru_ru.     virtuemart_product_id = 
                              products.           virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories 
     AS product_categories ON products_ru_ru.     virtuemart_product_id = 
                          product_categories.     virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru   
     AS categories_ru_ru   ON product_categories. virtuemart_category_id = 
                                categories_ru_ru. virtuemart_category_id
  INNER JOIN auc13_virtuemart_product_prices     
     AS product_prices     ON products_ru_ru.     virtuemart_product_id = 
                              product_prices.     virtuemart_product_id
   LEFT JOIN auc13_dev_sales_price
     AS dsprices           ON dsprices.           virtuemart_product_id =
                              product_prices.     virtuemart_product_id

where products_ru_ru.product_name LIKE '%Икона%'

  ORDER BY products_ru_ru.product_name