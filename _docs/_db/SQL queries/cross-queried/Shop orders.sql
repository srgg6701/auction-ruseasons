SELECT  orders.   id,
        prod_ru.  virtuemart_product_id,
        prod_ru.  product_name,
        cats_ru.  virtuemart_category_id,
        cats_ru.  category_name,
        cats_ru.  slug,
TRUNCATE
      ( prices.   sales_price, 0) 
               AS price,
        prod_ru.  product_s_desc,
        prod_ru.  product_desc,
        orders.   user_id,
        orders.   status,
        users.    name,
        users.    middlename,
        users.    lastname,
        users.    username
FROM auc13_virtuemart_products_ru_ru             prod_ru
  INNER JOIN auc13_dev_shop_orders               orders
          ON prod_ru.virtuemart_product_id = orders.virtuemart_product_id

   LEFT JOIN auc13_virtuemart_product_categories prod_cats
          ON prod_cats.virtuemart_product_id = orders.virtuemart_product_id

   LEFT JOIN auc13_virtuemart_categories        cats
          ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id

   LEFT JOIN auc13_virtuemart_categories_ru_ru  cats_ru
          ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id

  INNER JOIN auc13_users                        users
          ON orders.user_id = users.id
  INNER JOIN auc13_dev_sales_price              prices
          ON prices.virtuemart_product_id = orders.virtuemart_product_id
 -- WHERE user_id = 385
  ORDER BY orders.id DESC