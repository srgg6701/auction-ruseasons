#
#<?php die('Forbidden.'); ?>
#Date: 2013-02-13 16:19:10 UTC
#Software: Joomla Platform 11.4.0 Stable [ Brian Kernighan ] 03-Jan-2012 00:00 GMT

#Fields: date	time	line_nr	action	comment
2013-02-13	16:19:10	0	[DEBUG]	CSVI Version: 4.5.1
2013-02-13	16:19:10	0	[DEBUG]	PHP version: 5.3.3
2013-02-13	16:19:10	0	[DEBUG]	General settings
2013-02-13	16:19:10	0	[DEBUG]	Template name: Exportproducttest
2013-02-13	16:19:10	0	[DEBUG]	Destination: todownload
2013-02-13	16:19:10	0	[DEBUG]	Export filename: CSVI_VM_Exportproducttest_13-02-2013_19.19.csv
2013-02-13	16:19:10	0	[DEBUG]	Export type: 
2013-02-13	16:19:10	0	[DEBUG]	User given filename: 
2013-02-13	16:19:10	0	[DEBUG]	Export filetype: csv
2013-02-13	16:19:10	0	[DEBUG]	Using delimiter: ;
2013-02-13	16:19:10	0	[DEBUG]	Using enclosure: 
2013-02-13	16:19:10	0	[DEBUG]	Include column headers: Yes
2013-02-13	16:19:10	0	[DEBUG]	Add signature: Yes
2013-02-13	16:19:10	0	[DEBUG]	Export frontend: No
2013-02-13	16:19:10	0	[DEBUG]	Export state: Both
2013-02-13	16:19:10	0	[DEBUG]	Number of records to export: 
2013-02-13	16:19:10	0	[DEBUG]	Start from record number: 
2013-02-13	16:19:10	0	[DEBUG]	Record grouping: Yes
2013-02-13	16:19:10	0	[DEBUG]	Item ID: 
2013-02-13	16:19:10	0	[DEBUG]	Date format: d/m/Y H:i:s
2013-02-13	16:19:10	0	[DEBUG]	Number of decimals: 2
2013-02-13	16:19:10	0	[DEBUG]	Decimal separator: 
2013-02-13	16:19:10	0	[DEBUG]	Thousand separator: 
2013-02-13	16:19:10	0	[DEBUG]	Add currency to price: No
2013-02-13	16:19:10	0	[DEBUG]	Field: id категории
2013-02-13	16:19:10	0	[DEBUG]	Field: url картинки
2013-02-13	16:19:10	0	[DEBUG]	Field: url preview
2013-02-13	16:19:10	0	[DEBUG]	Field: url большого изображения
2013-02-13	16:19:10	0	[DEBUG]	Field: описание предмета
2013-02-13	16:19:10	0	[DEBUG]	Field: Название предмета
2013-02-13	16:19:10	0	[DEBUG]	Field: Параметры
2013-02-13	16:19:10	0	[DEBUG]	Export query
2013-02-13	16:19:10	-	-	-
2013-02-13	16:19:10	0	[QUERY]	 SELECT `#__virtuemart_product_categories`.`virtuemart_category_id`, `#__virtuemart_products`.`virtuemart_product_id`, `product_params` FROM #__virtuemart_products LEFT JOIN #__virtuemart_product_prices ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_prices.virtuemart_product_id LEFT JOIN #__virtuemart_product_manufacturers ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_manufacturers.virtuemart_product_id LEFT JOIN #__virtuemart_shoppergroups ON #__virtuemart_product_prices.virtuemart_shoppergroup_id = #__virtuemart_shoppergroups.virtuemart_shoppergroup_id LEFT JOIN #__virtuemart_manufacturers ON #__virtuemart_product_manufacturers.virtuemart_manufacturer_id = #__virtuemart_manufacturers.virtuemart_manufacturer_id LEFT JOIN #__virtuemart_product_categories ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_categories.virtuemart_product_id LEFT JOIN #__virtuemart_categories ON #__virtuemart_product_categories.virtuemart_category_id = #__virtuemart_categories.virtuemart_category_id LEFT JOIN #__virtuemart_currencies ON #__virtuemart_currencies.virtuemart_currency_id = #__virtuemart_product_prices.product_currency GROUP BY `product_params`
2013-02-13	16:19:10	-	-	-
2013-02-13	16:19:10	-	-	-
2013-02-13	16:19:10	-	-	-
2013-02-13	16:19:10	1	[DEBUG]	Clean up old logs. Found 3 logs and threshold is 25 logs
