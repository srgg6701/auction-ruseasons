SELECT
  prod_ru_ru.virtuemart_product_id  AS product_id,
  prod.product_sku AS 'contract',
  prod_ru_ru.product_name,
  prod.product_in_stock AS 'count', -- колич. предметов  
  ( SELECT CONCAT(cats_ruru_p.virtuemart_category_id,": ",cats_ruru_p.category_name)
      FROM auc13_virtuemart_categories_ru_ru AS cats_ruru_p
     WHERE cats_ruru_p.virtuemart_category_id = (
        SELECT category_parent_id FROM auc13_virtuemart_category_categories
         WHERE id = cats_ruru.virtuemart_category_id )
                                  ) AS 'category parent',
  CONCAT(cats_ruru.virtuemart_category_id,": ",cats_ruru.category_name) 
                                    AS 'category child',       -- prod_ru_ru.product_s_desc, prod_ru_ru.product_desc, prod_ru_ru.slug,
  prod_prices.product_price,
  sales_prices.sales_price  AS 'minimal_price',           -- prod_prices.product_override_price AS 'final_price',
  CONCAT( DATE_FORMAT(prod_prices.product_price_publish_up, "%d.%m.%Y"), "-", 
          DATE_FORMAT(prod_prices.product_price_publish_down, "%d.%m.%Y")
        ) AS 'show period',
  prod.product_available_date AS 'auction_start',
  prod.auction_date_finish AS 'auction_finish',           -- prod.product_availability AS 'date_from', prod.product_available_date_closed 'date_to',
  medias.file_title, 
  medias.file_description,
  medias.file_url,
  medias.file_url_thumb
        FROM auc13_virtuemart_products                AS prod
  INNER JOIN auc13_virtuemart_products_ru_ru          AS prod_ru_ru
          ON prod.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_product_prices          AS prod_prices
              ON prod_prices.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
             AND prod_prices.virtuemart_product_id = prod.virtuemart_product_id
   LEFT JOIN auc13_dev_sales_price                    AS sales_prices
              ON sales_prices.virtuemart_product_id = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_product_categories      AS prod_cats
              ON prod_cats.virtuemart_product_id = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_categories_ru_ru        AS cats_ruru
              ON cats_ruru.virtuemart_category_id = prod_cats.virtuemart_category_id
   LEFT JOIN auc13_virtuemart_product_medias          AS prod_medias
              ON prod_medias.virtuemart_product_id = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_medias                  AS medias
              ON medias.virtuemart_media_id = prod_medias.virtuemart_media_id