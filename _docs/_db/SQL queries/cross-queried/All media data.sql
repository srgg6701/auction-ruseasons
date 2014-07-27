USE auctionru_2013;
SELECT
  product_medias.virtuemart_product_id  AS 'product id',
  prods_ru.product_name,
  medias.file_title AS 'medias.file_title',
  medias.file_url_thumb,
  medias.file_url,
  -- cats.virtuemart_category_id           AS 'category id',
  CONCAT(cats_ru_parent.category_name,"/",
  cats_ru.category_name)                 AS 'category',
  -- medias.virtuemart_media_id AS 'medias.virtuemart_media_id',
  product_medias.id AS 'product_medias.id',
  product_medias.virtuemart_media_id AS 'product_medias.virtuemart_media_id' /*,
  medias.created_on,
  medias.published */
            FROM auc13_virtuemart_medias          AS medias

LEFT OUTER JOIN auc13_virtuemart_product_medias  AS product_medias
                 ON medias.virtuemart_media_id = product_medias.id

INNER JOIN auc13_virtuemart_products_ru_ru        AS prods_ru
                 ON prods_ru.virtuemart_product_id = product_medias.virtuemart_product_id

  LEFT OUTER JOIN auc13_virtuemart_product_categories AS cats
    ON cats.virtuemart_product_id = product_medias.virtuemart_product_id

  LEFT JOIN auc13_virtuemart_category_categories AS cats_cats
    ON cats_cats.category_child_id = cats.virtuemart_category_id
  
  LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru_parent
    ON cats_ru_parent.virtuemart_category_id = cats_cats.category_parent_id
  
  LEFT JOIN auc13_virtuemart_categories_ru_ru AS cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id

WHERE product_medias.virtuemart_product_id = 2772
ORDER BY product_medias.virtuemart_product_id