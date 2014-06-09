SELECT
  prod_ru_ru.virtuemart_product_id  AS product_id,
  prod.product_sku AS 'contract',
  prod_ru_ru.product_name,
  prod.product_in_stock AS 'count', -- колич. предметов
  CONCAT(cats_ruru.virtuemart_category_id,": ",cats_ruru.category_name) 
                                    AS 'category',
  prod_ru_ru.product_s_desc,
  prod_ru_ru.product_desc,
  prod_ru_ru.slug,
  prod_prices.product_price,
  CONCAT( DATE_FORMAT(prod_prices.product_price_publish_up, "%d.%m.%Y"), "-", 
          DATE_FORMAT(prod_prices.product_price_publish_down, "%d.%m.%Y")
        ) AS 'show period',
  prod.product_available_date AS 'date_start',
  prod.product_availability AS 'date_from', 
  prod.product_available_date_closed 'date_to',
  medias.file_title, 
  medias.file_description,
  medias.file_url,
  medias.file_url_thumb
        FROM auc13_virtuemart_products prod
  INNER JOIN auc13_virtuemart_products_ru_ru prod_ru_ru
          ON prod.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_product_prices prod_prices
          ON prod_prices.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
             AND prod_prices.virtuemart_product_id = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_product_categories prod_cats
          ON prod_cats.virtuemart_product_id = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_categories_ru_ru cats_ruru
          ON cats_ruru.virtuemart_category_id = prod_cats.virtuemart_category_id
   LEFT JOIN auc13_virtuemart_product_medias prod_medias
          ON prod_medias.virtuemart_product_id = prod.virtuemart_product_id
   LEFT JOIN auc13_virtuemart_medias medias
          ON medias.virtuemart_media_id = prod_medias.virtuemart_media_id