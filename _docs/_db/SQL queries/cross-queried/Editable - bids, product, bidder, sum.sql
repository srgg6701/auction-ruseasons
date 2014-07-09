SELECT bids.id, bids.virtuemart_product_id, prod_ru.product_name, bidder_user_id, 
       users.username AS login, users.name AS username, sum-- , datetime
  FROM auc13_dev_bids AS bids
  INNER JOIN auc13_virtuemart_products_ru_ru AS prod_ru 
            ON prod_ru.virtuemart_product_id = bids.virtuemart_product_id
  INNER JOIN auc13_users AS users 
            ON users.id = bids.bidder_user_id
  ORDER BY prod_ru.product_name, bids.id DESC