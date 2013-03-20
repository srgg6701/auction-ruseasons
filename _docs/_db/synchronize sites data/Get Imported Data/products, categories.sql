USE auctionru_2013;
SELECT
  -- prods.id,
  prods.optional_field_1 AS 'auction_number',
  prods.title,
  prods.description,
  cats.category_id,
  cats.category_name,
  cats.parent_id,
  prods.price,
  -- prods.image,
  ( SELECT COUNT(*) FROM auc13_geodesic_classifieds_images_urls 
      WHERE classified_id = prods.id
  ) AS 'imgs_count_check',  
  -- prods.category,
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