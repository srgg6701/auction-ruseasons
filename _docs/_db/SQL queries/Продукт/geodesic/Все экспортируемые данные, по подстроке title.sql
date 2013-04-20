SELECT REPLACE(prods.optional_field_1,'%B9','') AS 'auction_number',
  prods.optional_field_5 AS 'lot_number',
  prods.optional_field_6 AS 'contract_number',
  prods.date AS 'date_show',
  prods.ends AS 'date_hide',
  REPLACE(prods.optional_field_3, '%3A',':') AS 'date_start',
  REPLACE(prods.optional_field_4, '%3A',':') AS 'date_stop',
  prods.title,
  '' AS 'short_desc',
  prods.description AS 'desc', 
  prods.price, 								-- min price
  prods.optional_field_2 AS 'max_price',	-- max price
  prods.final_price AS 'sales_price', 		-- final price
  prods.image AS 'images',
  prods.id 
  FROM auctionru_ruse.geodesic_classifieds_cp prods
  WHERE title LIKE '%Натюрморт с розами и маком%'