SELECT  
  REPLACE(prods.optional_field_1,'%B9','') AS 'auction_number',
  prods.date AS 'date_show',
  prods.ends AS 'date_hide',
  prods.optional_field_3 AS 'date_start',
  prods.optional_field_4 AS 'date_stop',
  prods.title,
  ' ' AS 'short_desc',
  prods.description AS 'desc', 
  cats.category_id,
  prods.image AS 'images', 
  prods.id
FROM auc13_geodesic_classifieds_cp prods
  INNER JOIN auc13_geodesic_categories cats
    ON prods.category = cats.category_id   
        AND cats.parent_id = (  SELECT category_id 
             FROM auc13_geodesic_categories 
            WHERE category_name = 'Магазин'  
           )
	-- WHERE cats.category_id IN (269)
ORDER BY cats.category_name, prods.title