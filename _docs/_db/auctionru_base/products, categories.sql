SELECT
  prods.id,
  prods.title,
  prods.description,
  cats.category_id,
  cats.category_name,
  ( SELECT category_name 
        FROM geodesic_categories
      WHERE category_id = cats.parent_id
  ) AS parent,
  prods.price,
  prods.image,
  ( SELECT COUNT(*) FROM geodesic_classifieds_images_urls 
      WHERE classified_id = prods.id
  ) AS 'imgs_count_check',  
  prods.category,
  prods.ends,
  prods.date,
  prods.order_item_id,
  prods.item_type,
  prods.quantity,
  prods.auction_type,
  prods.price_plan_id,
  prods.seller,
  prods.live,
  prods.precurrency,
  prods.postcurrency,
  -- prods.conversion_rate,
  prods.duration,
  -- prods.location_city,
  -- prods.location_state,
  -- prods.location_country,
  -- prods.location_zip,
  prods.optional_field_2,
  prods.optional_field_1,
  prods.optional_field_3,
  prods.optional_field_4,
  prods.optional_field_5-- ,
  -- prods.optional_field_6,
  -- prods.optional_field_7,
  -- prods.optional_field_8,
  -- prods.optional_field_9,
  -- prods.optional_field_10
  -- prods.optional_field_11,
  -- prods.optional_field_12,
  -- prods.optional_field_13,
  -- prods.optional_field_14,
  -- prods.optional_field_15,
  -- prods.optional_field_16,
  -- prods.optional_field_17,
  -- prods.optional_field_18,
  -- prods.optional_field_19,
  -- prods.optional_field_20,
FROM geodesic_classifieds_cp prods
  INNER JOIN geodesic_categories cats
    ON prods.category = cats.category_id
   WHERE prods.optional_field_3 REGEXP '\%E4\%E5\%EA' AND cats.category_name = 'Западноевропейская  живопись'
ORDER BY parent, cats.category_name, prods.title
  