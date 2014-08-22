SELECT
  prods.virtuemart_product_id,
  cat_cats.category_child_id,
  cat_cats.category_parent_id,
  cats.virtuemart_category_id AS cats_cat_id,
  prod_cats.virtuemart_product_id,
  prod_cats.virtuemart_category_id AS prod_cat_id,
  auc13_virtuemart_products_ru_ru.product_name,
  auc13_virtuemart_products_ru_ru.virtuemart_product_id
FROM auc13_virtuemart_products prods
  INNER JOIN auc13_virtuemart_product_categories prod_cats
    ON prods.virtuemart_product_id = prod_cats.virtuemart_product_id
  INNER JOIN auc13_virtuemart_category_categories cat_cats
    ON prod_cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN auc13_virtuemart_categories cats
    ON prod_cats.virtuemart_category_id = cats.virtuemart_category_id AND cats.virtuemart_category_id = cat_cats.id
  INNER JOIN auc13_virtuemart_products_ru_ru
    ON auc13_virtuemart_products_ru_ru.virtuemart_product_id = prods.virtuemart_product_id AND auc13_virtuemart_products_ru_ru.virtuemart_product_id = prod_cats.virtuemart_product_id
WHERE cat_cats.category_parent_id = (SELECT
  cat_cats1.category_parent_id
FROM auc13_virtuemart_category_categories cat_cats1
WHERE cat_cats1.category_child_id = 10) AND prod_cats.virtuemart_category_id = 10