SELECT
  /*  cats.virtuemart_product_id,
  cats_ruru.slug,
  cats.virtuemart_category_id,
  cats_cats.category_parent_id,
  cats_ruru.category_name, */
  ( SELECT slug FROM auc13_virtuemart_categories_ru_ru 
     WHERE virtuemart_category_id = cats_cats.category_parent_id
  ) AS parent_slug
      FROM auc13_virtuemart_product_categories        AS cats
INNER JOIN auc13_virtuemart_categories_ru_ru          AS cats_ruru
           ON cats_ruru.virtuemart_category_id = cats.virtuemart_category_id
 LEFT JOIN auc13_virtuemart_category_categories  AS cats_cats
           ON cats_cats.id = cats.virtuemart_category_id
     WHERE cats.virtuemart_product_id = 2702
  LIMIT 1