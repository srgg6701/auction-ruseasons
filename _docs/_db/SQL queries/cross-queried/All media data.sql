SELECT
  auc13_virtuemart_medias.virtuemart_media_id AS 'ID: Medias',
  auc13_virtuemart_product_medias.id AS 'ID: Product Medias',
  auc13_virtuemart_medias.file_title AS 'File title in Medias',
  auc13_virtuemart_product_medias.virtuemart_product_id AS 'Product id in Product Medias',
  auc13_virtuemart_product_medias.virtuemart_media_id AS 'Media id in Product Medias',
  auc13_virtuemart_medias.created_on,
  auc13_virtuemart_medias.published
FROM auc13_virtuemart_medias
  RIGHT OUTER JOIN auc13_virtuemart_product_medias
    ON auc13_virtuemart_medias.virtuemart_media_id = auc13_virtuemart_product_medias.id
ORDER BY 'File title in Medias'