SELECT
  prods.virtuemart_product_id,
  ru_ru.virtuemart_product_id AS prod_id_ru_ru,
  prod_cats.virtuemart_product_id AS prod_id_cats,
  CONCAT(ru_ru.product_name, ': ',auc13_virtuemart_product_prices.product_price, '; ',cats_ru_ru.category_name) 
    AS 'product, price, category',
  ru_ru.product_desc,
  ru_ru.slug,
  prods.product_in_stock,
  cats.category_product_layout,
  cats.virtuemart_category_id,
  cats_ru_ru.virtuemart_category_id AS cat_id_ru_ru,
  prod_cats.virtuemart_category_id AS cat_id_prod,
  cats.category_layout,
  ru_ru.product_s_desc,
  auc13_virtuemart_product_prices.virtuemart_product_id
FROM auc13_virtuemart_products prods
  INNER JOIN auc13_virtuemart_products_ru_ru ru_ru
    ON prods.virtuemart_product_id = ru_ru.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories prod_cats
    ON prods.virtuemart_product_id = prod_cats.virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories cats
    ON prod_cats.virtuemart_category_id = cats.virtuemart_category_id AND ru_ru.virtuemart_product_id = prod_cats.virtuemart_product_id
  INNER JOIN auc13_virtuemart_categories_ru_ru cats_ru_ru
    ON cats.virtuemart_category_id = cats_ru_ru.virtuemart_category_id AND prod_cats.virtuemart_category_id = cats_ru_ru.virtuemart_category_id
  INNER JOIN auc13_virtuemart_product_prices
    ON auc13_virtuemart_product_prices.virtuemart_product_id = ru_ru.virtuemart_product_id