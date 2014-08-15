SELECT user_bids.id, 
  user_bids.virtuemart_product_id AS product_id, 
  prods_ru.product_name,
  bidder_id, users.email,
  CASE 
       WHEN bidder_id=-1 THEN 'autobid'
       ELSE users.username
  END AS username,
  users.name, 
  `value` AS 'Заочный бид', 
  user_bids.datetime, 
  user_bids.id AS 'user bid id' -- не путать user_bids (auc13_dev_user_bids) и auc13_dev_bids
  FROM auc13_dev_user_bids AS user_bids
INNER JOIN auc13_virtuemart_products_ru_ru AS prods_ru
  ON prods_ru.virtuemart_product_id = user_bids.virtuemart_product_id
LEFT JOIN auc13_users AS users
  ON users.id = user_bids.bidder_id
  ORDER BY user_bids.virtuemart_product_id, user_bids.id DESC