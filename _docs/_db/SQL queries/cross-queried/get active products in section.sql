-- USE auctionru_2013;
SELECT  prices.   virtuemart_product_id, 
        prods_ru.         product_name,
        prices.           product_price_publish_up,
        p.                product_available_date,
        p.                auction_date_finish,
        prices.           product_price_publish_down,
        pc.               virtuemart_category_id

      FROM auc13_virtuemart_products            AS p

LEFT JOIN auc13_virtuemart_product_categories  AS pc
           ON p.virtuemart_product_id = pc.virtuemart_product_id

LEFT JOIN auc13_virtuemart_products_ru_ru      AS prods_ru
           ON prods_ru.virtuemart_product_id = pc.virtuemart_product_id

LEFT JOIN auc13_virtuemart_category_categories AS cat_cats
           ON pc.virtuemart_category_id = cat_cats.category_child_id

LEFT JOIN auc13_virtuemart_product_prices      AS prices
           ON prices.virtuemart_product_id = pc.virtuemart_product_id

     WHERE cat_cats.category_parent_id = 21
  
           AND prices.product_price_publish_up    < NOW()
           AND p.product_available_date           < NOW()
           AND p.auction_date_finish              > NOW()
           AND prices.product_price_publish_down  > NOW()

           AND p.virtuemart_product_id NOT IN (                  
                  SELECT virtuemart_product_id 
                    -- online, fulltime
                    FROM auc13_dev_sold 
                   WHERE section = 1 -- online 2 -- shop                    
                  -- FROM auc13_dev_shop_orders
                )
  ORDER BY prices.product_price_publish_up  -- LIMIT 0, 15