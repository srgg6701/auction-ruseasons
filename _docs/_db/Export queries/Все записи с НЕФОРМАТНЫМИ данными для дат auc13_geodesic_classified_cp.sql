USE auctionru_2013

SELECT REPLACE(prods.optional_field_1,'%B9','') AS 'auction_number',
  prods.optional_field_5 AS 'lot_number',
  prods.optional_field_6 AS 'contract_number',
  prods.title,
  ( SELECT category_name FROM auc13_geodesic_categories
      WHERE parent_id = 0 
        AND category_id = cat.parent_id 
  ) AS parent_category_name,
  cat.category_name AS '* category *',
  prods.date AS 'date_show',
  prods.ends AS 'date_hide',
  REPLACE(prods.optional_field_3, '%3A',':') AS 'date_start',
  REPLACE(prods.optional_field_4, '%3A',':') AS 'date_stop',
  SUBSTRING(prods.description, 1, 70) AS 'short_desc',
  -- '' AS 'short_desc',
  prods.description AS 'desc', 
  prods.price, 								-- min price
  prods.optional_field_2 AS 'max_price',	-- max price
  prods.final_price AS 'sales_price', 		-- final price
  prods.image AS 'images',
  prods.id 
  FROM auc13_geodesic_classifieds_cp prods
    LEFT JOIN  auc13_geodesic_categories AS cat ON cat.category_id = prods.category 
  -- WHERE title LIKE '%Натюрморт с розами и маком%'
  -- ################ ВЫБРАТЬ НЕФОРМАТНЫЕ ДАННЫЕ ДЛЯ ДАТ: #################################
  WHERE ( prods.optional_field_3 REGEXP '(E[0-9])|(FF)' AND prods.optional_field_3 <> '' ) 
     OR ( prods.optional_field_4 REGEXP '(E[0-9])|(FF)' AND  prods.optional_field_4 <> '' )
  -- ######################################################################################
  -- WHERE prods.optional_field_3 = ''
  ORDER BY parent_category_name, title