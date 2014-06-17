SELECT  p.                          virtuemart_product_id -- , 
        -- prodru.                     product_name, count(p.virtuemart_product_id) AS cnt
  FROM `auc13_virtuemart_products`            AS p,
       `auc13_virtuemart_product_categories`  AS pc,
       `auc13_virtuemart_product_prices`      AS prices -- , -- ,auc13_virtuemart_categories AS cats
       -- `auc13_virtuemart_products_ru_ru`      AS prodru
 WHERE pc.    `virtuemart_category_id` = 34
   AND p.     `virtuemart_product_id`     = pc. `virtuemart_product_id`
   AND prices.`virtuemart_product_id`     = pc. `virtuemart_product_id`
   -- AND prodru.`virtuemart_product_id`     = p.  `virtuemart_product_id`
   AND p.     `published` = "1"
   -- AND p.     `product_in_stock`          > 0
   AND prices.`product_price_publish_up`  < NOW() 
   AND prices.`product_price_publish_down`> NOW()