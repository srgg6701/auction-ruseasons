SELECT
  prods_ru.     virtuemart_product_id    AS 'prod.id',
  prods_ru.     product_name,
  prices.       product_price,
  -- cats.virtuemart_category_id           AS 'category id',
  CONCAT(cats_ru_parent.category_name,"/",
  cats_ru.category_name)                 AS 'category',
  prods_media.  virtuemart_media_id      AS 'VM.media.id',
  medias.       file_title,
  -- medias.       file_description,
  -- medias.       file_meta,
  medias.       file_url,
  medias.       file_url_thumb,
  medias.       file_is_product_image
  
FROM auc13_virtuemart_product_medias  AS prods_media

  LEFT JOIN auc13_virtuemart_medias   AS medias
    ON prods_media.virtuemart_media_id = medias.virtuemart_media_id

  INNER JOIN auc13_virtuemart_products_ru_ru AS prods_ru
    ON prods_media.virtuemart_product_id = prods_ru.virtuemart_product_id

  INNER JOIN auc13_virtuemart_product_prices AS prices
    ON prices.virtuemart_product_id = prods_ru.virtuemart_product_id

  LEFT OUTER JOIN auc13_virtuemart_product_categories AS cats
    ON cats.virtuemart_product_id = prods_media.virtuemart_product_id

  LEFT JOIN auc13_virtuemart_category_categories AS cats_cats
    ON cats_cats.category_child_id = cats.virtuemart_category_id
  
  LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru_parent
    ON cats_ru_parent.virtuemart_category_id = cats_cats.category_parent_id
  
  LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id

WHERE prods_media.virtuemart_product_id IN ( 3212, 3237 )