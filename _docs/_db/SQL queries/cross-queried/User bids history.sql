-- SELECT * FROM auc13_dev_user_bids

SELECT user_bids.virtuemart_product_id, 
  prods_ru.product_name,
  bidder_id,
  CASE 
       WHEN bidder_id=-1 THEN 'autobid'
       ELSE users.username
  END AS username,
  users.name, 
  `value`, 
  user_bids.datetime, user_bids.id AS 'user bid id'
  FROM auc13_dev_user_bids AS user_bids
INNER JOIN auc13_virtuemart_products_ru_ru AS prods_ru
  ON prods_ru.virtuemart_product_id = user_bids.virtuemart_product_id
LEFT JOIN auc13_users AS users
  ON users.id = user_bids.bidder_id
  ORDER BY user_bids.virtuemart_product_id, user_bids.id DESC