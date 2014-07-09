SELECT
  prices.virtuemart_product_id as product_id,
  prods_ru.product_name,
  cats.virtuemart_category_id,
  cats_ru.category_name,
  prices.product_price,
  -- DATE_FORMAT( prices.product_price_publish_up, '%d.%m.%Y' ) AS publis_up,
  -- DATE_FORMAT( prods.product_available_date, '%d.%m.%Y' ) AS auction_start,
  -- DATE_FORMAT( prices.product_price_publish_down, '%d.%m.%Y' ) AS publish_down,
  -- DATE_FORMAT( prods.auction_date_finish, '%d.%m.%Y' ) AS  auction_finish,
  prices.product_price_publish_up,
  prods.product_available_date,
  prices.product_price_publish_down,
  prods.auction_date_finish
FROM auc13_virtuemart_product_prices AS prices
  INNER JOIN auc13_virtuemart_products AS prods
    ON prices.virtuemart_product_id = prods.virtuemart_product_id
  INNER JOIN auc13_virtuemart_products_ru_ru AS prods_ru
    ON prods_ru.virtuemart_product_id = prods.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories AS cats
    ON cats.virtuemart_product_id = prods.virtuemart_product_id
INNER JOIN auc13_virtuemart_categories_ru_ru AS cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
WHERE prices.virtuemart_product_id = 2772