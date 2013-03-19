SELECT
  prods.id,
  prods.title,
  prods.description,
  cats.category_id,
  cats.category_name,
  prods.price,
  -- prods.image,
  ( SELECT COUNT(*) FROM auc13_geodesic_classifieds_images_urls 
      WHERE classified_id = prods.id
  ) AS 'imgs_count_check',  
  prods.category,
  prods.ends,
  prods.date,
  -- prods.order_item_id,
  -- prods.item_type,
  -- prods.quantity,
  -- prods.auction_type,
  -- prods.price_plan_id,
  -- prods.seller,
  -- prods.live,
  -- prods.precurrency,
  -- prods.postcurrency,
  prods.duration,
  prods.optional_field_2,
  prods.optional_field_1,
  prods.optional_field_3,
  prods.optional_field_4,
  prods.optional_field_5-- ,
FROM auc13_geodesic_classifieds_cp prods
  INNER JOIN auc13_geodesic_categories cats
    ON prods.category = cats.category_id
ORDER BY cats.category_name, prods.title