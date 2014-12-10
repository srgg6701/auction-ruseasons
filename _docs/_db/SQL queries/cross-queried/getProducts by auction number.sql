SELECT
  prod_ru.virtuemart_product_id,
  prod_ru.product_name,
  prod_ru.slug,
  /*(SELECT
      auc13_virtuemart_categories_ru_ru.slug
    FROM auc13_virtuemart_categories_ru_ru
    WHERE auc13_virtuemart_categories_ru_ru.virtuemart_category_id = (SELECT
        auc13_virtuemart_category_categories.category_parent_id
      FROM auc13_virtuemart_category_categories
      WHERE auc13_virtuemart_category_categories.category_child_id = cats.virtuemart_category_id)) 
    AS parent_category_slug,*/
  cats.virtuemart_category_id,
  cats_ru.slug,
  prod_ru.product_s_desc,
  /*(SELECT
        auc13_virtuemart_category_categories.category_parent_id
      FROM auc13_virtuemart_category_categories
      WHERE auc13_virtuemart_category_categories.category_child_id = cats.virtuemart_category_id)
  AS parent_category_id, */
  (SELECT alias-- , id, menutype, title, link 
    FROM auctionru_2013.auc13_menu
    WHERE menutype = 'mainmenu' 
    AND link LIKE '%=com_virtuemart%'
    AND link LIKE CONCAT('%virtuemart_category_id=',(
      SELECT category_parent_id
        FROM auc13_virtuemart_category_categories
       WHERE category_child_id = cats.virtuemart_category_id)
    )
  ) AS top_category_alias,
  prod.auction_number,
  medias.file_url_thumb
FROM auc13_virtuemart_products_ru_ru prod_ru
  INNER JOIN auc13_virtuemart_products prod
    ON prod_ru.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories cats
    ON cats.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
  LEFT OUTER JOIN auc13_virtuemart_product_medias prods_media
    ON prod_ru.virtuemart_product_id = prods_media.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_medias medias
    ON prods_media.virtuemart_media_id = medias.virtuemart_media_id
WHERE prod.auction_number = 102030