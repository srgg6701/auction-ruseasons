SELECT
  prods.id,
  prods.title,
  prods.current_bid AS 'price',
  cats.category_name,
  prods.final_price AS 'sales_price',
  ( SELECT category_name 
      FROM auc13_geodesic_categories
      WHERE category_id = cats.parent_id
  ) AS parent,
  REPLACE(prods.optional_field_1, '%B9', '') AS 'auction_number',
  prods.optional_field_5 AS 'lot_number',
  prods.optional_field_6 AS 'contract_number',
  prods.date AS 'date_show',
  prods.ends AS 'date_hide',
  REPLACE(prods.optional_field_3, '%3A', ':') AS 'date_start',
  REPLACE(prods.optional_field_4, '%3A', ':') AS 'date_stop',
  '' AS 'short_desc',
  prods.description AS 'desc',
  prods.image AS 'images',
  cats.parent_id,
  cats.category_id
FROM auc13_geodesic_classifieds_cp prods
  INNER JOIN auc13_geodesic_categories cats
    ON prods.category = cats.category_id
ORDER BY parent, price DESC, prods.title