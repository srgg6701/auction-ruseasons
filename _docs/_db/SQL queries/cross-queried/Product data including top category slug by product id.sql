SELECT prod.*,
       sales_prices.sales_price AS minimal_price
FROM `auc13_virtuemart_product_prices` AS prod
    LEFT JOIN `auc13_dev_sales_price` AS sales_prices
  ON sales_prices.`virtuemart_product_id` = prod.`virtuemart_product_id`
WHERE prod.`virtuemart_product_id` = 2702 