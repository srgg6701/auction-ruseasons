SELECT
  DISTINCT prod.virtuemart_product_id     AS 'prod.id',
  -- prod.                                    lot_number,
  prod_ru_ru.product_name                 AS 'item name',
  (SELECT COUNT(*) FROM auc13_dev_bids WHERE virtuemart_product_id = prod.virtuemart_product_id) 
                                          AS bids,
  prod_prices.product_price               AS 'Старт.цена',
  -- sales_prices.price2                     AS 'Конеч.цена',
  sales_prices.min_price                  AS 'Резерв.цена',
  -- prod.product_in_stock                   AS 'cnt', -- колич. предметов  
  -- TRUNCATE(sales_prices.price2,0)         AS final_price,
  -- TRUNCATE(sales_prices.min_price,0)      AS 'minimal_price',           -- prod_prices.product_override_price AS 'final_price',
  -- IF(shop_orders.status IS NOT NULL, shop_orders.status, '') AS 'ordered',
  prod_prices.product_price_publish_up    AS 'publish_up',
  prod.product_available_date             AS 'auction_start',
  prod_prices.product_price_publish_down  AS 'publish_down',
  prod.auction_date_finish                AS 'auction_finish',
  CONCAT(( SELECT CONCAT(cats_ruru_p.virtuemart_category_id,":",cats_ruru_p.category_name)
      FROM auc13_virtuemart_categories_ru_ru AS cats_ruru_p
     WHERE cats_ruru_p.virtuemart_category_id = (
        SELECT category_parent_id FROM auc13_virtuemart_category_categories
         WHERE id = cats_ruru.virtuemart_category_id )
                                  ), "/",
  CONCAT(cats_ruru.virtuemart_category_id,":",cats_ruru.category_name)) 
                                          AS 'categories',       -- prod_ru_ru.product_s_desc, prod_ru_ru.product_desc, prod_ru_ru.slug,
  -- TRUNCATE(prod_prices.product_price,0)   AS product_price
  prod.product_sku                        AS 'contract_number',
  prod.auction_number
  -- cats_ruru2.category_name                AS 'parent category'
  -- CONCAT( DATE_FORMAT(prod_prices.product_price_publish_up,"%d.%m.%Y %h:%i"),   " | ", DATE_FORMAT(prod.product_available_date,"%d.%m.%Y %h:%i") ) AS 'show_up/auction_start',
  -- CONCAT( DATE_FORMAT(prod_prices.product_price_publish_down,"%d.%m.%Y %h:%i"), " | ", DATE_FORMAT(prod.auction_date_finish,"%d.%m.%Y %h:%i") )    AS 'show_down/auction_finish',
  -- ( SELECT COUNT(*) FROM auc13_virtuemart_product_medias WHERE virtuemart_product_id = prod.virtuemart_product_id ) AS 'imgs', 
  /* ,
  medias.                               file_title, 
  medias.                               file_description,
  medias.                               file_url,
  medias.                               file_url_thumb */
        FROM auc13_virtuemart_products                AS prod
  INNER JOIN auc13_virtuemart_products_ru_ru          AS prod_ru_ru
          ON prod.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_product_prices          AS prod_prices
              ON prod_prices.virtuemart_product_id  = prod_ru_ru.virtuemart_product_id
             AND prod_prices.virtuemart_product_id  = prod.virtuemart_product_id
   LEFT JOIN auc13_dev_sales_price                    AS sales_prices
              ON sales_prices.virtuemart_product_id = prod_prices.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_product_categories      AS prod_cats
              ON prod_cats.virtuemart_product_id    = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_categories_ru_ru        AS cats_ruru
              ON cats_ruru.virtuemart_category_id   = prod_cats.virtuemart_category_id

   LEFT JOIN auc13_virtuemart_category_categories     AS catscats
              ON catscats.category_child_id = prod_cats.virtuemart_category_id

   LEFT JOIN auc13_virtuemart_categories_ru_ru        AS cats_ruru2
              ON cats_ruru2.virtuemart_category_id   = catscats.category_parent_id
   
   LEFT JOIN auc13_dev_shop_orders                    AS shop_orders
              ON shop_orders.virtuemart_product_id  = prod.virtuemart_product_id
   /*LEFT JOIN auc13_dev_bids                           AS bids
              ON bids.virtuemart_product_id         = prod.virtuemart_product_id */
   LEFT JOIN auc13_virtuemart_product_medias          AS prod_medias
              ON prod_medias.virtuemart_product_id  = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_medias                  AS medias
              ON medias.virtuemart_media_id = prod_medias.virtuemart_media_id
  WHERE   cats_ruru2.category_name LIKE 'Онлайн торги'
          AND prod.virtuemart_product_id = 2702 -- 110 р.
              OR prod.virtuemart_product_id = 2772
            -- prod_ru_ru.product_name LIKE '%Икона%' AND
            -- sales_prices.sales_price IS NOT null
  ORDER BY bids, prod_ru_ru.product_name LIMIT 500