SELECT  prods_ru.product_name,
        cats_ru.category_name AS category,
        
        --  показать только максимальную ставку:
            -- MAX(sum) AS max_bid_value, 
        
        sum,
        prods.auction_date_finish AS date_finish,
        -- NOW() AS now,
        TRUNCATE((UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60,0) AS 'rest_in_minutes',
        CASE WHEN (UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60>0
                AND (UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60<5
               THEN FROM_UNIXTIME(UNIX_TIMESTAMP(prods.auction_date_finish)+5*60)
             ELSE ' '
        END                       AS '5_min_plus',
        bidder_user_id, 
        users.username AS login, 
        users.name AS username
     FROM auc13_dev_bids AS bids
LEFT JOIN auc13_virtuemart_products AS prods
          ON prods.virtuemart_product_id = bids.virtuemart_product_id
LEFT JOIN auc13_virtuemart_products_ru_ru AS prods_ru
          ON prods_ru.virtuemart_product_id = prods.virtuemart_product_id
INNER JOIN auc13_virtuemart_product_categories AS cats
          ON cats.virtuemart_product_id = prods.virtuemart_product_id
INNER JOIN auc13_virtuemart_categories_ru_ru AS cats_ru
          ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
INNER JOIN auc13_users AS users 
            ON users.id = bids.bidder_user_id
WHERE prods.virtuemart_product_id = 2772
  ORDER BY sum DESC