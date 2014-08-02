USE auctionru_2013;
SELECT  prods.                          virtuemart_product_id, 
                                        bidder_id,
        bids.`value`                AS  'max_value',
        prices.                         product_price,
        prods_ru.                       product_name,
        users.                          name, 
        users.                          email,
        users.                          phone_number,
        users.                          phone2_number,
        product_available_date, auction_date_finish
      FROM  auc13_virtuemart_products       AS prods
INNER JOIN auc13_virtuemart_product_prices  AS prices
           ON prices.virtuemart_product_id = prods.virtuemart_product_id
INNER JOIN auc13_dev_user_bids              AS bids
           ON bids.virtuemart_product_id = prods.virtuemart_product_id
              AND bids.bidder_id = ( SELECT bidder_id FROM auc13_dev_user_bids
                                      WHERE virtuemart_product_id = prods.virtuemart_product_id
                                  ORDER BY `value` DESC LIMIT 1 )
INNER JOIN auc13_virtuemart_products_ru_ru  AS prods_ru
           ON prods.virtuemart_product_id = prods_ru.virtuemart_product_id
INNER JOIN auc13_dev_lots_active            AS alots 
           ON prods.virtuemart_product_id = alots.virtuemart_product_id 
INNER JOIN auc13_users                      AS users
           ON users.id  = bids.bidder_id
     WHERE auction_date_finish < NOW()
           AND bids.`value` > prices.product_price