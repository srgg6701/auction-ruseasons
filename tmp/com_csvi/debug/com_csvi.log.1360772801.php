#
#<?php die('Forbidden.'); ?>
#Date: 2013-02-13 16:26:41 UTC
#Software: Joomla Platform 11.4.0 Stable [ Brian Kernighan ] 03-Jan-2012 00:00 GMT

#Fields: date	time	line_nr	action	comment
2013-02-13	16:26:41	0	[DEBUG]	Версия CSVI: 4.0.1
2013-02-13	16:26:41	0	[DEBUG]	Версия PHP: 5.3.3
2013-02-13	16:26:41	0	[DEBUG]	Общие настройки
2013-02-13	16:26:41	0	[DEBUG]	Имя шаблона Export2
2013-02-13	16:26:41	0	[DEBUG]	Путь: todownload
2013-02-13	16:26:41	0	[DEBUG]	Экспортировать имя файла: CSVI_VM_Export2_13-02-2013_19.26.csv
2013-02-13	16:26:41	0	[DEBUG]	Тип экспорта: 
2013-02-13	16:26:41	0	[DEBUG]	Пользовательское имя файла: 
2013-02-13	16:26:41	0	[DEBUG]	Экспортировать тип файла: csv
2013-02-13	16:26:41	0	[DEBUG]	Использовать разделители: ;
2013-02-13	16:26:41	0	[DEBUG]	Использовать разделители текста:
2013-02-13	16:26:41	0	[DEBUG]	Включить заголовки колонок:Да
2013-02-13	16:26:41	0	[DEBUG]	Добавить подпись: Нет
2013-02-13	16:26:41	0	[DEBUG]	Экспорт лицевой панели: Нет
2013-02-13	16:26:41	0	[DEBUG]	Состояние экспорта: Оба(е)
2013-02-13	16:26:41	0	[DEBUG]	Число записей для экспорта 
2013-02-13	16:26:41	0	[DEBUG]	Начать с числа:
2013-02-13	16:26:41	0	[DEBUG]	Групировать записи: Да
2013-02-13	16:26:41	0	[DEBUG]	Идентификатор: 
2013-02-13	16:26:41	0	[DEBUG]	Формат даты: d/m/Y H:i:s
2013-02-13	16:26:41	0	[DEBUG]	Количество знаков после запятой: 2
2013-02-13	16:26:41	0	[DEBUG]	Резделитель дробей: 
2013-02-13	16:26:41	0	[DEBUG]	Разделитель тысяч: 
2013-02-13	16:26:41	0	[DEBUG]	Добавить валюту к цене: Нет
2013-02-13	16:26:41	0	[DEBUG]	Поля: Name
2013-02-13	16:26:41	0	[DEBUG]	Поля: Params
2013-02-13	16:26:41	0	[DEBUG]	Поля: Description
2013-02-13	16:26:41	0	[DEBUG]	Поля: id
2013-02-13	16:26:41	-	-	-
2013-02-13	16:26:41	0	[DEBUG]	Export query
2013-02-13	16:26:41	-	-	-
2013-02-13	16:26:41	0	[QUERY]	SELECT p.virtuemart_product_id        FROM #__virtuemart_products p        LEFT JOIN #__virtuemart_product_categories x        ON p.virtuemart_product_id = x.virtuemart_product_id        WHERE x.virtuemart_category_id IN ('1')
2013-02-13	16:26:41	-	-	-
2013-02-13	16:26:41	0	[DEBUG]	Export query
2013-02-13	16:26:41	-	-	-
2013-02-13	16:26:41	0	[QUERY]	SELECT p.virtuemart_product_id         FROM #__virtuemart_products p         WHERE p.product_parent_id IN ('1')
2013-02-13	16:26:41	0	[DEBUG]	Export query
2013-02-13	16:26:41	-	-	-
2013-02-13	16:26:41	0	[QUERY]	 SELECT `#__virtuemart_products`.`virtuemart_product_id`, `product_params` FROM #__virtuemart_products LEFT JOIN #__virtuemart_product_prices ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_prices.virtuemart_product_id LEFT JOIN #__virtuemart_product_manufacturers ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_manufacturers.virtuemart_product_id LEFT JOIN #__virtuemart_shoppergroups ON #__virtuemart_product_prices.virtuemart_shoppergroup_id = #__virtuemart_shoppergroups.virtuemart_shoppergroup_id LEFT JOIN #__virtuemart_manufacturers ON #__virtuemart_product_manufacturers.virtuemart_manufacturer_id = #__virtuemart_manufacturers.virtuemart_manufacturer_id LEFT JOIN #__virtuemart_product_categories ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_categories.virtuemart_product_id LEFT JOIN #__virtuemart_categories ON #__virtuemart_product_categories.virtuemart_category_id = #__virtuemart_categories.virtuemart_category_id LEFT JOIN #__virtuemart_currencies ON #__virtuemart_currencies.virtuemart_currency_id = #__virtuemart_product_prices.product_currency WHERE #__virtuemart_products.virtuemart_product_id IN ('1') GROUP BY `product_params`,`virtuemart_product_id`
2013-02-13	16:26:41	-	-	-
2013-02-13	16:26:41	-	-	-
2013-02-13	16:26:41	1	[DEBUG]	Clean up old logs. Found 4 logs and threshold is 25 logs
