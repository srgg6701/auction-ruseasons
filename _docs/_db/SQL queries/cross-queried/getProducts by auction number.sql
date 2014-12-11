SELECT  prod_ru.virtuemart_product_id,
        prod_ru.product_name,
        prod.lot_number,
        ( SELECT COUNT(*) FROM auc13_virtuemart_product_medias
          WHERE virtuemart_product_id = prod_ru.virtuemart_product_id
        ) AS img_cnt,
        currency_symbol,
        CONCAT( TRUNCATE(prices.product_price,0), ' - ', TRUNCATE(price2,0)) AS prices,
        prod_ru.product_s_desc,
  CONCAT('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=',
          prod_ru.virtuemart_product_id,
         '&virtuemart_category_id=', 
         cats.virtuemart_category_id  ) AS link_common,
  CONCAT( ( SELECT alias
              FROM auc13_menu
             WHERE menutype = 'mainmenu' 
               AND link LIKE '%=com_virtuemart%'
               AND link LIKE CONCAT( '%virtuemart_category_id=',(
                                      SELECT category_parent_id
                                        FROM auc13_virtuemart_category_categories
                                       WHERE category_child_id = cats.virtuemart_category_id)
                                  )
          ), '/', cats_ru.slug, '/', prod_ru.slug, '-detail' ) AS link_sef,
  medias.file_url_thumb
FROM auc13_virtuemart_products_ru_ru prod_ru
  
  INNER JOIN auc13_virtuemart_products prod
    ON prod_ru.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories cats
    ON cats.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id

  LEFT JOIN auc13_virtuemart_product_prices AS prices
    ON prices.virtuemart_product_id = prod.virtuemart_product_id

  LEFT JOIN auc13_dev_sales_price AS prices2
    ON prices2.virtuemart_product_id = prod.virtuemart_product_id

  LEFT JOIN auc13_virtuemart_currencies AS currency
    ON virtuemart_currency_id = product_currency

  LEFT OUTER JOIN auc13_virtuemart_product_medias prods_media
    ON prod_ru.virtuemart_product_id = prods_media.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_medias medias
    ON prods_media.virtuemart_media_id = medias.virtuemart_media_id
-- WHERE prod.auction_number = 102030

  /*SELECT  prod_ru.virtuemart_product_id,
  prod_ru.product_name AS title,
  prod_ru.product_s_desc,
  CONCAT( 'http://localhost/auction-ruseasons/аукцион/', 
  ( SELECT alias
     FROM auc13_menu
     WHERE menutype = 'mainmenu'
           AND link LIKE '%=com_virtuemart%'
           AND link LIKE CONCAT( '%virtuemart_category_id=',(
       SELECT category_parent_id
       FROM auc13_virtuemart_category_categories
       WHERE category_child_id = cats.virtuemart_category_id)
     )
), '/', cats_ru.slug, '/', prod_ru.slug, '-detail' ) AS href,
  medias.file_url_thumb AS image
FROM auc13_virtuemart_products_ru_ru prod_ru
  INNER JOIN auc13_virtuemart_products prod
    ON prod_ru.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories cats
    ON cats.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
  LEFT OUTER JOIN auc13_virtuemart_product_mediAS prods_media
    ON prod_ru.virtuemart_product_id = prods_media.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_mediAS medias
    ON prods_media.virtuemart_media_id = medias.virtuemart_media_id */