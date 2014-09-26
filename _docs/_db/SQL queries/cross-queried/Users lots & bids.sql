USE auctionru_2013;
SELECT      DISTINCT rates.id                    AS 'id ставки',
  CONCAT( prod.virtuemart_product_id,
      ':', prod_ru_ru.product_name )             AS 'Предмет',
  prod.product_available_date                    AS 'Начало торгов',
  prod.auction_date_finish                       AS 'Окончание торгов',
            rates.bidder_user_id                 AS 'id юзера',
  IF (users.username, users.username, 'autobid') AS 'username',
            sum                                  AS 'Ставка',
  ( SELECT value 
      FROM auc13_dev_user_bids 
     WHERE bidder_id = rates.bidder_user_id
            AND virtuemart_product_id = rates.virtuemart_product_id
  )                                              AS 'Заоч.бид',
  TRUNCATE(sales_prices.min_price,0)             AS 'Резерв.цена',
           users.                                    name,
           users.                                    email,
           rates.datetime                         AS 'Дата/время ставки',
  ( SELECT `datetime` 
      FROM auc13_dev_user_bids 
     WHERE bidder_id = rates.bidder_user_id
            AND virtuemart_product_id = rates.virtuemart_product_id
  )                                              AS 'Дата/время заоч.бида',
  prod_cats.virtuemart_category_id               AS 'id категории'
        FROM auc13_virtuemart_products                AS prod
  INNER JOIN auc13_virtuemart_products_ru_ru          AS prod_ru_ru
          ON prod.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
  INNER JOIN auc13_virtuemart_product_categories      AS prod_cats
              ON prod_cats.virtuemart_product_id    = prod.virtuemart_product_id
  INNER JOIN auc13_dev_auction_rates                  AS rates
              ON rates.virtuemart_product_id         = prod.virtuemart_product_id   
  LEFT JOIN auc13_users                              AS users
              ON users.id = rates.bidder_user_id

  LEFT JOIN auc13_dev_sales_price                    AS sales_prices
              ON sales_prices.virtuemart_product_id = prod.virtuemart_product_id
      -- WHERE     prod.virtuemart_product_id = 2757
  -- rates.bidder_user_id = 385
  -- GROUP BY prod_ru_ru.product_name 
  ORDER BY prod_ru_ru.product_name, rates.id DESC
  -- , user_max_bid DESC