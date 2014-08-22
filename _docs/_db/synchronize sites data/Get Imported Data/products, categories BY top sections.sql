SELECT
  prods.id,
  prods.title,
  prods.description,
  cats.category_id,
  cats.parent_id,
  cats.category_name,
  prods.price,
  ( SELECT COUNT(*) FROM auc13_geodesic_classifieds_images_urls 
      WHERE classified_id = prods.id
  ) AS 'imgs_count_check',  
  prods.ends,
  prods.date,
  prods.duration,
  prods.optional_field_2,
  prods.optional_field_1,
  prods.optional_field_3,
  prods.optional_field_4,
  prods.optional_field_5-- ,
FROM auc13_geodesic_classifieds_cp prods
  INNER JOIN auc13_geodesic_categories cats
    ON prods.category = cats.category_id 
   AND cats.parent_id = (  SELECT category_id 
                            FROM auc13_geodesic_categories 
                           WHERE category_name = 'Магазин'  
                       )
 WHERE cats.category_id IN (311,325)  
 ORDER BY cats.category_name, prods.title