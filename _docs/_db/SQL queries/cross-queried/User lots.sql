USE auctionru_2013;
SELECT      DISTINCT bids.id         AS 'bid id',
  CONCAT( prod.virtuemart_product_id,
      ':', prod_ru_ru.product_name ) AS 'item',
            bids.bidder_user_id      AS user_id,
  IF (users.username, users.username, 'autobid') AS 'username',
            users.                      name,
           bids.                        datetime,
            sum                      AS 'sum',
  ( SELECT value 
      FROM auc13_dev_user_bids 
     WHERE bidder_id = bids.bidder_user_id
            AND virtuemart_product_id = bids.virtuemart_product_id
  )                                  AS 'user_bid value',
            -- prod.                             auction_date_finish,
  /*( SELECT MAX(sum) 
      FROM auc13_dev_bids 
     WHERE bidder_user_id = bids.bidder_user_id 
           AND virtuemart_product_id = prod.virtuemart_product_id
  )                                       AS  user_max_bid, */
  prod_cats.virtuemart_category_id   AS  'category_id'
        FROM auc13_virtuemart_products                AS prod
  INNER JOIN auc13_virtuemart_products_ru_ru          AS prod_ru_ru
          ON prod.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories      AS prod_cats
              ON prod_cats.virtuemart_product_id    = prod.virtuemart_product_id
  INNER JOIN auc13_dev_bids                           AS bids
              ON bids.virtuemart_product_id         = prod.virtuemart_product_id   
  LEFT JOIN auc13_users                              AS users
              ON users.id = bids.bidder_user_id
  -- WHERE     prod.virtuemart_product_id = 2702
  -- bids.bidder_user_id = 385
  -- GROUP BY prod_ru_ru.product_name 
  ORDER BY prod_ru_ru.product_name, sum DESC
  -- , user_max_bid DESC