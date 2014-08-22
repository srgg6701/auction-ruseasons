SELECT
  prod_cats.virtuemart_product_id,
  cats.virtuemart_category_id,
  cats_ruru.virtuemart_category_id AS '[categories_ru_ru]',
  prod_cats.virtuemart_category_id AS '[product_categories]',
  cat_cats.category_child_id AS '[category_categories].child_id',
  cats_ruru.category_name,
  cat_cats.category_parent_id,
  ( SELECT
    COUNT(*) AS expr1
  FROM auc13_virtuemart_product_medias avpm
  WHERE avpm.virtuemart_product_id = prod_cats.virtuemart_product_id) AS 'mediafiles_count'
FROM auc13_virtuemart_categories cats
  INNER JOIN auc13_virtuemart_category_categories cat_cats
    ON cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_product_categories prod_cats
    ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id AND prod_cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_categories_ru_ru cats_ruru
    ON cats.virtuemart_category_id = cats_ruru.virtuemart_category_id
WHERE cats.virtuemart_category_id = 9