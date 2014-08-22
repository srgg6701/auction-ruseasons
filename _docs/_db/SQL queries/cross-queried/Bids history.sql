SELECT bids.virtuemart_product_id, prods_ru.product_name,
  bidder_user_id,
  CASE 
       WHEN bidder_user_id=-1 THEN 'autobid'
       ELSE users.username
  END AS username,
  sum, 
  bids.datetime, bids.id AS 'bid id'
      FROM auc13_dev_bids AS bids
INNER JOIN auc13_virtuemart_products_ru_ru AS prods_ru
           ON prods_ru.virtuemart_product_id = bids.virtuemart_product_id
 LEFT JOIN auc13_users AS users
           ON users.id = bids.bidder_user_id
  ORDER BY bids.virtuemart_product_id, bids.id DESC