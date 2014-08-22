SELECT  prods.                          virtuemart_product_id,
                                        bidder_id,
        bids.`value`                 AS 'max_value',
        TRUNCATE(prices.product_price,0)
                                     AS product_price,
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

LEFT JOIN auc13_users                      AS users
           ON users.id  = bids.bidder_id
     WHERE prods.auction_date_finish < NOW()
            
           AND bids.`value` > prices.product_price 

           -- ВЫБРАТЬ ЛОТЫ, которые :
              -- отсутствуют среди проданных И
           AND prods.virtuemart_product_id NOT IN ( 
                        SELECT virtuemart_product_id 
                          FROM auc13_dev_sold )
           
           AND  (   /* ... и в проданных нет записи, добавленной позже или одновременно с 
                    датой закрытия аукциона, что означает, что после закрытия торгов аукцин 
                    повторно не назначался, т.о., у предмета остался статус непроданного */
                    SELECT COUNT(*)
                      FROM auc13_dev_unsold 
                     WHERE virtuemart_product_id = prods.virtuemart_product_id
                       AND `datetime` >= prods.auction_date_finish
                  ) = 0
              
              /*(  -- отсутствуют среди НЕпроданных вообще 
                  ( SELECT COUNT(*) 
                      FROM auc13_dev_unsold 
                     WHERE  virtuemart_product_id = prods.virtuemart_product_id
                  ) = 0
                OR  -- ИЛИ
                  -- присутствуют, НО запись добавлена ДО даты закрытия торгов, что означает, что аукцион был назначен повторно
                  ( SELECT COUNT(*)
                      FROM auc13_dev_unsold 
                     WHERE virtuemart_product_id = prods.virtuemart_product_id
                       AND `datetime` < prods.auction_date_finish
                  ) > 0
               )*/