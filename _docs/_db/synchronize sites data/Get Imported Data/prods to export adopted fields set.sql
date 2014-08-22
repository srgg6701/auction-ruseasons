SELECT  
  prods.id,
  REPLACE(prods.optional_field_1,'%B9','') AS 'auction_number',
  prods.optional_field_5 AS 'lot_number',
  prods.optional_field_6 AS 'contract_number',
  prods.date AS 'date_show',
  prods.ends AS 'date_hide',
  REPLACE(prods.optional_field_3, '%3A',':') AS 'date_start',
  REPLACE(prods.optional_field_4, '%3A',':') AS 'date_stop',
  prods.title,
  ' ' AS 'short_desc',
  prods.description AS 'desc',
  prods.current_bid AS 'price',
  prods.final_price AS 'actual_price', 

  cats.category_id,
  prods.image AS 'images'
FROM auc13_geodesic_classifieds_cp prods
  INNER JOIN auc13_geodesic_categories cats
    ON prods.category = cats.category_id   
        AND cats.parent_id = (  SELECT category_id 
             FROM auc13_geodesic_categories 
            WHERE category_name = 'Магазин'  
           )
	-- WHERE cats.category_id IN (269)
ORDER BY cats.category_name, prods.title