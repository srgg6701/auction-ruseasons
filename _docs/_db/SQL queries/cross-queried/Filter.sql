SELECT DISTINCT
  prod.virtuemart_product_id      AS 'prod.id',
  prod_ru_ru.product_name         AS item_name,
  prod_prices.product_price_publish_up,
  prod.product_available_date,
  prod.auction_date_finish,
  prod_prices.product_price_publish_down,
  CONCAT(
          ( SELECT
      CONCAT(cats_ruru_p.virtuemart_category_id, ":", cats_ruru_p.category_name) AS expr1
    FROM auc13_virtuemart_categories_ru_ru cats_ruru_p
    WHERE cats_ruru_p.virtuemart_category_id = (
              SELECT auc13_virtuemart_category_categories.category_parent_id
                FROM auc13_virtuemart_category_categories
               WHERE auc13_virtuemart_category_categories.id = cats_ruru.virtuemart_category_id)), "/", CONCAT(cats_ruru.virtuemart_category_id, ":", cats_ruru.category_name)
        )                         AS 'categories'
FROM auc13_virtuemart_products                         AS prod
  INNER JOIN auc13_virtuemart_products_ru_ru           AS prod_ru_ru
    ON prod.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_product_prices      AS prod_prices
    ON prod_prices.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
    AND prod_prices.virtuemart_product_id = prod.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_product_categories  AS prod_cats
    ON prod_cats.virtuemart_product_id = prod.virtuemart_product_id
  LEFT OUTER JOIN auc13_virtuemart_categories_ru_ru    AS cats_ruru
    ON cats_ruru.virtuemart_category_id = prod_cats.virtuemart_category_id
  LEFT OUTER JOIN auc13_virtuemart_category_categories AS catscats
    ON catscats.category_child_id = prod_cats.virtuemart_category_id
  LEFT OUTER JOIN auc13_virtuemart_categories_ru_ru    AS cats_ruru2
    ON cats_ruru2.virtuemart_category_id = catscats.category_parent_id
  WHERE cats_ruru2.category_name LIKE 'Онлайн торги'
        AND prod_prices.product_price_publish_up > NOW()
        AND prod.product_available_date > NOW()
ORDER BY item_name ASC
LIMIT 500