<?php
/**
 * Product export class
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: productexport.php 2312 2013-02-04 06:30:36Z RolandD $
 */

defined('_JEXEC') or die;

/**
 * Processor for product exports
 */
class CsviModelProductExport extends CsviModelExportfile {

	// Private variables
	private $_domainname = null;
	private $_prices = array();
	private $_customfields = array();

	/**
	 * Product export
	 *
	 * Exports product data to either csv, xml or HTML format
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		void
	 * @since 		3.0
	 */
	public function getStart() {
		// Get some basic data
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$csvidb = new CsviDb();
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$exportclass =  $jinput->get('export.class', null, null);
		$export_fields = $jinput->get('export.fields', array(), 'array');
		$this->_domainname = CsviHelper::getDomainName();
		$helper = new Com_VirtueMart();
		$sef = new CsviSef();

		// Build something fancy to only get the fieldnames the user wants
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			if ($field->process) {
				switch ($field->field_name) {
					case 'created_on':
					case 'modified_on':
					case 'locked_on':
					case 'created_by':
					case 'modified_by':
					case 'locked_by':
					case 'virtuemart_product_id':
					case 'virtuemart_vendor_id':
					case 'hits':
					case 'metaauthor':
					case 'metarobot':
					case 'published':
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn($field->field_name);
						break;
					case 'category_id':
					case 'category_path':
						$userfields[] = $db->qn('#__virtuemart_product_categories').'.'.$db->qn('virtuemart_category_id');
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('virtuemart_product_id');
						break;
					case 'product_name':
					case 'product_s_desc':
					case 'product_desc':
					case 'metadesc':
					case 'metakey':
					case 'slug':
					case 'customtitle':
					case 'custom_value':
					case 'custom_price':
					case 'custom_param':
					case 'custom_title':
					case 'file_url':
					case 'file_url_thumb':
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('virtuemart_product_id');
						break;
					case 'product_parent_sku':
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('product_parent_id');
						break;
					case 'related_products':
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('virtuemart_product_id').' AS main_product_id';
						break;
					case 'product_box':
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('product_packaging');
						break;
					case 'product_price':
					case 'price_with_tax':
						$userfields[] = $db->qn('#__virtuemart_product_prices').'.'.$db->qn('product_price');
						$userfields[] = $db->qn('#__virtuemart_currencies').'.'.$db->qn('currency_code_3');
						break;
					case 'product_url':
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('virtuemart_product_id');
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('product_url');
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('product_parent_id');
						break;
					case 'price_with_discount':
						$userfields[] = $db->qn('#__virtuemart_product_prices').'.'.$db->qn('product_price');
						$userfields[] = $db->qn('#__virtuemart_currencies').'.'.$db->qn('currency_code_3');
						//$userfields[] = $db->qn('#__virtuemart_calcs').'.'.$db->qn('calc_value');
						//$userfields[] = $db->qn('#__virtuemart_calcs').'.'.$db->qn('calc_value_mathop');
						//$userfields[] = $db->qn('#__virtuemart_calcs').'.'.$db->qn('calc_kind');
						break;
					case 'custom_shipping':
						$userfields[] = $db->qn('#__virtuemart_product_prices').'.'.$db->qn('product_price');
						$userfields[] = '1 AS tax_rate';
						break;
					case 'max_order_level':
					case 'min_order_level':
						$userfields[] = $db->qn('#__virtuemart_products').'.'.$db->qn('product_params');
						break;
					case 'basepricewithtax':
					case 'discountedpricewithouttax':
					case 'pricebeforetax':
					case 'salesprice':
					case 'taxamount':
					case 'discountamount':
					case 'pricewithouttax':
					case 'product_currency':
						$userfields[] = $db->qn('#__virtuemart_products.virtuemart_product_id');
						$userfields[] = $db->qn('#__virtuemart_currencies.currency_code_3');
						break;
					// Man made fields, do not export them
					case 'custom':
					case 'picture_url':
					case 'manufacturer_name':
						break;
					default:
						$userfields[] = $db->qn($field->field_name);
						break;
				}
			}
		}

		/** Export SQL Query
		 * Get all products - including items
		 * as well as products without a price
		 */
		$userfields = array_unique($userfields);
		$query = $db->getQuery(true);
		$query->select(implode(",\n", $userfields));
		$query->from('#__virtuemart_products');
		$query->leftJoin('#__virtuemart_product_prices ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_prices.virtuemart_product_id');
		$query->leftJoin('#__virtuemart_product_manufacturers ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_manufacturers.virtuemart_product_id');
		$query->leftJoin('#__virtuemart_shoppergroups ON #__virtuemart_product_prices.virtuemart_shoppergroup_id = #__virtuemart_shoppergroups.virtuemart_shoppergroup_id');
		$query->leftJoin('#__virtuemart_manufacturers ON #__virtuemart_product_manufacturers.virtuemart_manufacturer_id = #__virtuemart_manufacturers.virtuemart_manufacturer_id');
		$query->leftJoin('#__virtuemart_product_categories ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_categories.virtuemart_product_id');
		$query->leftJoin('#__virtuemart_categories ON #__virtuemart_product_categories.virtuemart_category_id = #__virtuemart_categories.virtuemart_category_id');
		$query->leftJoin('#__virtuemart_currencies ON #__virtuemart_currencies.virtuemart_currency_id = #__virtuemart_product_prices.product_currency');

		// Check if there are any selectors
		$selectors = array();

		// Filter by product category
		/**
		 * We are doing a selection on categories, need to redo the query to make sure child products get included
		 * 1. Search all product ID's for that particular category
		 * 2. Search for all child product ID's
		 * 3. Load all products with these ids
		 */
		$productcategories = $template->get('product_categories', 'product', false);
		if ($productcategories && $productcategories[0] != '') {
			$product_ids = array();

			// If selected get products of all subcategories as well
			if ($template->get('incl_subcategory', 'product', false)) {
				$q_subcat_ids = "SELECT category_child_id
									FROM #__virtuemart_category_categories
									WHERE category_parent_id IN ('".implode("','", $productcategories)."')";
				$db->setQuery($q_subcat_ids);
				$subcat_ids = $db->loadResultArray();
				$productcategories = array_merge($productcategories, $subcat_ids);
				$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);
			}

			// Get only the parent products and products without children
			if ($template->get('parent_only', 'product', 0, 'bool')) {
				// Get all product IDs in the selected categories
				$q_product_ids = "SELECT p.virtuemart_product_id
							FROM #__virtuemart_products p
							LEFT JOIN #__virtuemart_product_categories x
							ON p.virtuemart_product_id = x.virtuemart_product_id
							WHERE x.virtuemart_category_id IN ('".implode("','", $productcategories)."')
							AND p.product_parent_id = 0";
				$db->setQuery($q_product_ids);
				$product_ids = $db->loadResultArray();
				$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);
			}
			// Get only the child products and products without children
			else if ($template->get('child_only', 'product', 0, 'bool')) {
				// Load all non child IDs
				$q_child = "SELECT p.virtuemart_product_id
									FROM #__virtuemart_products p
									LEFT JOIN #__virtuemart_product_categories x
									ON p.virtuemart_product_id = x.virtuemart_product_id
									WHERE x.virtuemart_category_id IN ('".implode("','", $productcategories)."')";
				$db->setQuery($q_child);
				$allproduct_ids = $db->loadResultArray();
				$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

				// Get all child product IDs in the selected categories
				$q_child = "SELECT p.virtuemart_product_id
							FROM #__virtuemart_products p
							WHERE p.product_parent_id IN ('".implode("','", $allproduct_ids)."')";
				$db->setQuery($q_child);
				$child_ids = $db->loadResultArray();
				$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

				// Get all parent product IDs in the selected categories
				$q_child = "SELECT p.product_parent_id
							FROM #__virtuemart_products p
							WHERE p.virtuemart_product_id IN ('".implode("','", $child_ids)."')";
				$db->setQuery($q_child);
				$parent_ids = $db->loadResultArray();
				$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

				// Combine all the IDs
				$product_ids = array_merge($child_ids, array_diff($allproduct_ids, $parent_ids));
			}
			else {
				// Get all product IDs
				$q_product_ids = "SELECT p.virtuemart_product_id
							FROM #__virtuemart_products p
							LEFT JOIN #__virtuemart_product_categories x
							ON p.virtuemart_product_id = x.virtuemart_product_id
							WHERE x.virtuemart_category_id IN ('".implode("','", $productcategories)."')";
				$db->setQuery($q_product_ids);
				$product_ids = $db->loadResultArray();
				$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

				// Get all child product IDs
				if ($product_ids) {
					$q_childproduct_ids = "SELECT p.virtuemart_product_id
								FROM #__virtuemart_products p
								WHERE p.product_parent_id IN ('".implode("','", $product_ids)."')";
					$db->setQuery($q_childproduct_ids);
					$childproduct_ids = $db->loadResultArray();
					$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

					// Now we have all the product IDs
					$product_ids = array_merge($product_ids, $childproduct_ids);
				}
			}

			// Check if the user want child products
			if (!empty($product_ids)) $selectors[] = '#__virtuemart_products.virtuemart_product_id IN (\''.implode("','", $product_ids).'\')';
		}
		else {
			// Filter by published category state
			$category_publish = $template->get('publish_state_categories', 'product');

			// Filter on parent products and products without children
			if ($template->get('parent_only', 'product', 0, 'bool')) {
				$selectors[] = '#__virtuemart_products.product_parent_id = 0';
				if (!empty($category_publish)) {
					$selectors[] = '#__virtuemart_categories.published = '.$category_publish;
				}
			}

			// Filter on child products and products without children
			else if ($template->get('child_only', 'product', 0, 'bool')) {
				// Load all non child IDs
				$q_nonchild = 'SELECT #__virtuemart_products.virtuemart_product_id FROM #__virtuemart_products ';
				$state = ($category_publish == '1') ? '0' : '1';
				if (!empty($category_publish)) {
					$q_nonchild .= 'LEFT JOIN #__virtuemart_product_categories
								ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_categories.virtuemart_product_id
								LEFT JOIN #__virtuemart_categories
								ON #__virtuemart_product_categories.virtuemart_category_id = #__virtuemart_categories.virtuemart_category_id
								WHERE #__virtuemart_categories.published = '.$state;
				}
				$db->setQuery($q_nonchild);
				$nonchild_ids = $db->loadResultArray();
				$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

				// Get the child IDs from the filtered category
				if (!empty($category_publish)) {
					$q_nonchild = 'SELECT #__virtuemart_products.virtuemart_product_id FROM #__virtuemart_products ';
					$q_nonchild .= 'LEFT JOIN #__virtuemart_product_categories
								ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_product_categories.virtuemart_product_id
								LEFT JOIN #__virtuemart_categories
								ON #__virtuemart_product_categories.virtuemart_category_id = #__virtuemart_categories.virtuemart_category_id
								WHERE #__virtuemart_products.product_parent_id IN (\''.implode("','", $nonchild_ids).'\')';
					$q_nonchild .= ' GROUP BY virtuemart_product_id';
					$db->setQuery($q_nonchild);
					$child_ids = $db->loadResultArray();
					$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);
					if (is_array($child_ids)) $nonchild_ids = array_merge($nonchild_ids, $child_ids);
				}

				$selectors[] = '#__virtuemart_products.virtuemart_product_id NOT IN (\''.implode("','", $nonchild_ids).'\')';
			}
			else {
				if (!empty($category_publish)) {
					// Get all product IDs
					$q_product_ids = "SELECT p.virtuemart_product_id
								FROM #__virtuemart_products p
								LEFT JOIN #__virtuemart_product_categories x
								ON p.virtuemart_product_id = x.virtuemart_product_id
								LEFT JOIN #__virtuemart_categories c
								ON x.virtuemart_category_id = c.virtuemart_category_id
								WHERE c.category_publish = ".$db->q($category_publish);
					$db->setQuery($q_product_ids);
					$product_ids = $db->loadResultArray();
					$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

					// Get all child product IDs
					if ($product_ids) {
						$q_childproduct_ids = "SELECT p.virtuemart_product_id
									FROM #__virtuemart_products p
									WHERE p.product_parent_id IN ('".implode("','", $product_ids)."')";
						$db->setQuery($q_childproduct_ids);
						$childproduct_ids = $db->loadResultArray();
						$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

						// Now we have all the product IDs
						$product_ids = array_merge($product_ids, $childproduct_ids);
					}

					// Check if the user want child products
					if (!empty($product_ids)) $selectors[] = '#__virtuemart_products.virtuemart_product_id IN (\''.implode("','", $product_ids).'\')';
				}
			}
		}

		// Filter on featured products
		$featured = $template->get('featured', 'product', '');
		if ($featured) {
			$selectors[] = "#__virtuemart_products.product_special = 1";
		}

		// Filter by published state
		$product_publish = $template->get('publish_state', 'general');
		if ($product_publish !== '' && ($product_publish == 1 || $product_publish == 0)) {
			$selectors[] = '#__virtuemart_products.published = '.$db->q($product_publish);
		}

		// Filter by product SKU
		$productskufilter = $template->get('productskufilter', 'product');
		if ($productskufilter) {
			$productskufilter .= ',';
			if (strpos($productskufilter, ',')) {
				$skus = explode(',', $productskufilter);
				$wildcard = '';
				$normal = array();
				foreach ($skus as $sku) {
					if (!empty($sku)) {
						if (strpos($sku, '%')) {
							$wildcard .= "#__virtuemart_products.product_sku LIKE ".$db->q($sku)." OR ";
						}
						else $normal[] = $db->q($sku);
					}
				}
				if (substr($wildcard, -3) == 'OR ') $wildcard = substr($wildcard, 0, -4);
				if (!empty($wildcard) && !empty($normal)) {
					$selectors[] = "(".$wildcard." OR #__virtuemart_products.product_sku IN (".implode(',', $normal)."))";
				}
				else if (!empty($wildcard)) {
					$selectors[] = "(".$wildcard.")";
				}
				else if (!empty($normal)) {
					$selectors[] = "(#__virtuemart_products.product_sku IN (".implode(',', $normal)."))";
				}
			}
		}

		// Filter on price from
		$priceoperator = $template->get('priceoperator', 'product', 'gt');
		$pricefrom = $template->get('pricefrom', 'product', 0, 'float');
		$priceto = $template->get('priceto', 'product', 0, 'float');
		if (!empty($pricefrom)) {
			switch ($priceoperator) {
				case 'gt':
					$selectors[] = "ROUND(#__virtuemart_product_prices.product_price, ".$template->get('export_price_format_decimal', 'general', 2, 'int').") > ".$pricefrom;
					break;
				case 'eq':
					$selectors[] = "ROUND(#__virtuemart_product_prices.product_price, ".$template->get('export_price_format_decimal', 'general', 2, 'int').") = ".$pricefrom;
					break;
				case 'lt':
					$selectors[] = "ROUND(#__virtuemart_product_prices.product_price, ".$template->get('export_price_format_decimal', 'general', 2, 'int').") < ".$pricefrom;
					break;
				case 'bt':
					$selectors[] = "ROUND(#__virtuemart_product_prices.product_price, ".$template->get('export_price_format_decimal', 'general', 2, 'int').") BETWEEN ".$pricefrom." AND ".$priceto;
					break;
			}
		}

		// Filter by stocklevel start
		$stocklevelstart = $template->get('stocklevelstart', 'product', 0, 'int');
		if ($stocklevelstart) {
			$selectors[] = '#__virtuemart_products.product_in_stock >= '.$stocklevelstart;
		}

		// Filter by stocklevel end
		$stocklevelend = $template->get('stocklevelend', 'product', 0, 'int');
		if ($stocklevelend) {
			$selectors[] = '#__virtuemart_products.product_in_stock <= '.$stocklevelend;
		}

		// Filter by shopper group id
		$shopper_group = $template->get('shopper_groups', 'product', array());
		if ($shopper_group && $shopper_group[0] != 'none') {
			$selectors[] = "#__virtuemart_product_prices.virtuemart_shoppergroup_id IN ('".implode("','", $shopper_group)."')";
		}

		// Filter by manufacturer
		$manufacturer = $template->get('manufacturers', 'product', array());
		if ($manufacturer && $manufacturer[0] != 'none') {
			$selectors[] = "#__virtuemart_manufacturers.virtuemart_manufacturer_id IN ('".implode("','", $manufacturer)."')";
		}

		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0 ) $query->where(implode("\n AND ", $selectors));

		// Ingore fields
		$ignore = array('metadesc', 'metakey', 'product_name', 'product_s_desc', 'product_desc', 'slug', 'customtitle', 'category_path', 'manufacturer_name',
					'category_id', 'picture_url', 'product_box', 'product_parent_sku', 'related_products', 'custom_shipping',
					'basepricewithtax', 'discountedpricewithouttax', 'pricebeforetax', 'salesprice', 'taxamount', 'discountamount',
					'pricewithouttax', 'custom_title', 'custom_value', 'custom_price', 'custom_param', 'file_url', 'file_url_thumb',
					'min_order_level', 'max_order_level');

		// Check if we need to group the orders together
		$groupby = $template->get('groupby', 'general', false, 'bool');
		if ($groupby) {
			$filter = $this->getFilterBy('groupby', $ignore);
			if (!empty($filter)) $query->group($filter);
		}

		// Order by set field
		$orderby = $this->getFilterBy('sort', $ignore);
		if (!empty($orderby)) $query->order($orderby);

		// Add export limits
		$limits = $this->getExportLimit();

		// Execute the query
		$csvidb->setQuery($query, $limits['offset'], $limits['limit']);
		$csvilog->addDebug(JText::_('COM_CSVI_EXPORT_QUERY'), true);

		// There are no records, write SQL query to log
		if (!is_null($csvidb->getErrorMsg())) {
			$this->addExportContent(JText::sprintf('COM_CSVI_ERROR_RETRIEVING_DATA', $csvidb->getErrorMsg()));
			$this->writeOutput();
			$csvilog->AddStats('incorrect', $csvidb->getErrorMsg());
		}
		else {
			$logcount = $csvidb->getNumRows();
			JRequest::setVar('logcount', $logcount);
			if ($logcount > 0) {
				$linenumber = 1;
				while ($record = $csvidb->getRow()) {
					$csvilog->setLinenumber($linenumber++);
					// Start the XML/HTML output
					if ($template->get('export_file', 'general') == 'xml' || $template->get('export_file', 'general') == 'html') $this->addExportContent($exportclass->NodeStart());

					// Load JoomFish translation if needed
					$joomfish = array();
					if ($template->get('use_joomfish', 'product', false)) {
						$jfdb = JFactory::getDBO();
						// Get the product details
						$q = "SELECT value, reference_field
							FROM #__jf_content
							WHERE reference_table = 'vm_product'
							AND language_id = ".$template->get('joomfish_language', 'product')."
							AND reference_id = ".$record->main_product_id;
						$jfdb->setQuery($q);
						$joomfish = $jfdb->loadObjectList('reference_field');
					}

					// Reset the prices
					$this->_prices = array();

					// Process all the export fields
					foreach ($export_fields as $column_id => $field) {
						if ($field->process) {
							$fieldname = $field->field_name;
							// Add the replacement & JoomFish
							if (isset($record->$fieldname)) {
								// Get the JoomFish data
								if (array_key_exists($fieldname, $joomfish)) $fieldvalue = $joomfish[$fieldname]->value;
								else $fieldvalue = $record->$fieldname;
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
							}
							else $fieldvalue = '';
							switch ($fieldname) {
								case 'category_id':
									$category_path = trim($helper->createCategoryPath($record->virtuemart_product_id, true));
									if (strlen(trim($category_path)) == 0) $category_path = $field->default_value;
									$category_path = CsviHelper::replaceValue($field->replace, $category_path);
									$this->addExportField($field->combine, $category_path, $fieldname, $field->column_header, true);
									break;
								case 'category_path':
									$category_path = trim($helper->createCategoryPath($record->virtuemart_product_id));
									if (strlen(trim($category_path)) == 0) $category_path = $field->default_value;
									$category_path = CsviHelper::replaceValue($field->replace, $category_path);
									$this->addExportField($field->combine, $category_path, $fieldname, $field->column_header, true);
									break;
								case 'product_name':
								case 'product_s_desc':
								case 'product_desc':
								case 'metadesc':
								case 'metakey':
								case 'slug':
								case 'customtitle':
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__virtuemart_products_'.$template->get('language', 'general'));
									$query->where('virtuemart_product_id = '.$record->virtuemart_product_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
									break;
								case 'picture_url':
									$query = $db->getQuery(true);
									$query->select('file_url');
									$query->from('#__virtuemart_medias');
									$query->leftJoin('#__virtuemart_product_medias ON #__virtuemart_product_medias.virtuemart_media_id = #__virtuemart_medias.virtuemart_media_id');
									$query->where('virtuemart_product_id = '.$record->virtuemart_product_id);
									$query->order('#__virtuemart_product_medias.ordering');
									$db->setQuery($query, 0, 1);
									$fieldvalue = $db->loadResult();
									// Check if there is already a product full image
									if (strlen(trim($fieldvalue)) > 0) $picture_url = $this->_domainname.'/'.$fieldvalue;
									else $picture_url = $field->default_value;
									$picture_url = CsviHelper::replaceValue($field->replace, $picture_url);
									$this->addExportField($field->combine, $picture_url, $fieldname, $field->column_header);
									break;
								case 'product_parent_sku':
									$query = $db->getQuery(true);
									$query->select('product_sku');
									$query->from('#__virtuemart_products');
									$query->where('virtuemart_product_id = '.$record->product_parent_id);
									$db->setQuery($query);
									$product_parent_sku = $db->loadResult();
									$product_parent_sku = CsviHelper::replaceValue($field->replace, $product_parent_sku);
									$this->addExportField($field->combine, $product_parent_sku, $fieldname, $field->column_header);
									break;
								case 'related_products':
									// Get the custom ID
									$related_records = array();
									$query = $db->getQuery(true);
									$query->select($db->qn('#__virtuemart_products').'.'.$db->qn('product_sku'));
									$query->from($db->qn('#__virtuemart_product_customfields'));
									$query->leftJoin($db->qn('#__virtuemart_customs').' ON '.$db->qn('#__virtuemart_customs').'.'.$db->qn('virtuemart_custom_id').' = '.$db->qn('#__virtuemart_product_customfields').'.'.$db->qn('virtuemart_custom_id'));
									$query->leftJoin($db->qn('#__virtuemart_products').' ON '.$db->qn('#__virtuemart_products').'.'.$db->qn('virtuemart_product_id').' = '.$db->qn('#__virtuemart_product_customfields').'.'.$db->qn('custom_value'));
									$query->where($db->qn('#__virtuemart_customs').'.'.$db->qn('field_type').' = '.$db->q('R'));
									$query->where($db->qn('#__virtuemart_product_customfields').'.'.$db->qn('virtuemart_product_id').' = '.$db->q($record->virtuemart_product_id));
									$query->group($db->qn('#__virtuemart_products').'.'.$db->qn('product_sku'));
									$db->setQuery($query);
									$related_records = $db->loadResultArray();
									if (is_array($related_records)) $related_products = implode('|', $related_records);
									else $related_products = '';
									if (strlen(trim($related_products)) == 0) $related_products = $field->default_value;
									$related_products = CsviHelper::replaceValue($field->replace, $related_products);
									$this->addExportField($field->combine, $related_products, $fieldname, $field->column_header);
									break;
								case 'product_available_date':
								case 'created_on':
								case 'modified_on':
								case 'locked_on':
									$date = JFactory::getDate($record->$fieldname);
									$fieldvalue = CsviHelper::replaceValue($field->replace, date($template->get('export_date_format', 'general'), $date->toUnix()));
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'product_box':
									$product_box = $record->product_packaging>>16&0xFFFF;
									$product_box = CsviHelper::replaceValue($field->replace, $product_box);
									$this->addExportField($field->combine, $product_box, $fieldname, $field->column_header);
									break;
								case 'product_packaging':
									$product_packaging = $record->product_packaging & 0xFFFF;
									$product_packaging = CsviHelper::replaceValue($field->replace, $product_packaging);
									$this->addExportField($field->combine, $product_packaging, $fieldname, $field->column_header);
									break;
								case 'product_price':
									$product_price = $this->_convertPrice($record->product_price, $record->currency_code_3);
									$product_price =  number_format($product_price, $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
									if (strlen(trim($product_price)) == 0) $product_price = $field->default_value;
									if ($template->get('add_currency_to_price', 'general')) {
										if ($template->get('targetcurrency', 'product') != '') {
											$product_price = $template->get('targetcurrency', 'product').' '.$product_price;
										}
										else $product_price = $record->currency_code_3.' '.$product_price;
									}
									$product_price = CsviHelper::replaceValue($field->replace, $product_price);
									$this->addExportField($field->combine, $product_price, $fieldname, $field->column_header);
									break;
								case 'product_override_price':
									$product_price =  number_format($record->product_override_price, $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
									if (strlen(trim($product_price)) == 0) $product_price = $field->default_value;
									if ($template->get('add_currency_to_price', 'general')) {
										if ($template->get('targetcurrency', 'product') != '') {
											$product_price = $template->get('targetcurrency', 'product').' '.$product_price;
										}
										else $product_price = $record->currency_code_3.' '.$product_price;
									}
									$product_price = CsviHelper::replaceValue($field->replace, $product_price);
									$this->addExportField($field->combine, $product_price, $fieldname, $field->column_header);
									break;
								case 'product_url':
									// Check if there is already a product URL
									if (is_null($record->product_url) || strlen(trim($record->product_url)) == 0) {
										// Get the category id
										// Check to see if we have a child product
										$category_id = $helper->getCategoryId($record->virtuemart_product_id);

										if ($category_id > 0) {
											// Let's create a SEF URL
											$_SERVER['QUERY_STRING'] = 'option=com_virtuemart&Itemid='.$template->get('vm_itemid', 'product', 1, 'int').'&view=productdetails&virtuemart_product_id='.$record->virtuemart_product_id.'&virtuemart_category_id='.$category_id;
											$product_url = $sef->getSiteRoute('index.php?'.$_SERVER['QUERY_STRING']);
										}
										else $product_url = "";
									}
									// There is a product URL, use it
									else $product_url = $record->product_url;

									// Add the suffix
									if (!empty($product_url)) $product_url .= $template->get('producturl_suffix', 'product');

									// Check for https, replace with http. https has unnecessary overhead
									if (substr($product_url, 0, 5) == 'https') $product_url = 'http'.substr($product_url, 5);
									$product_url = CsviHelper::replaceValue($field->replace, $product_url);
									$this->addExportField($field->combine, $product_url, $fieldname, $field->column_header, true);
									break;
								case 'price_with_tax':
									$prices = $this->_getProductPrice($record->virtuemart_product_id);
									$price_with_tax = number_format($prices['salesPrice'], $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
									// Check if we have any content otherwise use the default value
									if (strlen(trim($price_with_tax)) == 0) $price_with_tax = $field->default_value;
									if ($template->get('add_currency_to_price', 'general')) $price_with_tax = $record->product_currency.' '.$price_with_tax;
									$price_with_tax = CsviHelper::replaceValue($field->replace, $price_with_tax);
									$this->addExportField($field->combine, $price_with_tax, $fieldname, $field->column_header);
									break;
								case 'basepricewithtax':
								case 'discountedpricewithouttax':
								case 'pricebeforetax':
								case 'salesprice':
								case 'taxamount':
								case 'discountamount':
								case 'pricewithouttax':
									$prices = $this->_getProductPrice($record->virtuemart_product_id);
									if (isset($prices[$fieldname])) {
										$price = number_format($prices[$fieldname], $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
									}

									// Check if we have any content otherwise use the default value
									if (strlen(trim($price)) == 0) $price = $field->default_value;

									// Check if the currency needs to be added
									if ($template->get('add_currency_to_price', 'general')) $price = $record->product_currency.' '.$price;

									// Perform the replacement rules
									$price = CsviHelper::replaceValue($field->replace, $price);

									// Export the data
									$this->addExportField($field->combine, $price, $fieldname, $field->column_header);
									break;
								case 'product_currency':
									$fieldvalue = $record->currency_code_3;
									// Check if we have any content otherwise use the default value
									if ($template->get('targetcurrency', 'product') != '') {
										$fieldvalue = $template->get('targetcurrency', 'product');
									}
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'custom_shipping':
									// Get the prices
									$prices = $this->_getProductPrice($record->virtuemart_product_id);
									// Check the shipping cost
									if (isset($prices['salesprice'])) {
										$price_with_tax = number_format($prices['salesprice'], $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
										$result = $helper->shippingCost($price_with_tax);
										if ($result) $fieldvalue = $result;
									}

									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;

									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'manufacturer_name':
									$query = $db->getQuery(true);
									$query->select('mf_name');
									$query->from('#__virtuemart_manufacturers_'.$template->get('language', 'general'));
									$query->leftJoin('#__virtuemart_product_manufacturers ON #__virtuemart_product_manufacturers.virtuemart_manufacturer_id = #__virtuemart_manufacturers_'.$template->get('language', 'general').'.virtuemart_manufacturer_id');
									$query->where('virtuemart_product_id = '.$record->virtuemart_product_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
									break;
								case 'custom_title':
									// Get the custom title
									$query = $db->getQuery(true);
									$query->select($db->qn('custom_title'));
									$query->from($db->qn('#__virtuemart_customs').' AS c');
									$query->leftJoin($db->qn('#__virtuemart_product_customfields').' AS f ON c.virtuemart_custom_id = f.virtuemart_custom_id');
									$query->where($db->qn('virtuemart_product_id').' = '.$db->q($record->virtuemart_product_id));
									// Check if we need to filter
									$title_filter = array();
									$title_filter = $template->get('custom_title', 'product', array(), 'array');
									if (!empty($title_filter) && $title_filter[0] != '') {
										$query->where($db->qn('f').'.'.$db->qn('virtuemart_custom_id').' IN ('.implode(',', $title_filter).')');
									}
									$query->order($db->qn('f').'.'.$db->qn('virtuemart_custom_id'));
									$db->setQuery($query);
									$titles = $db->loadResultArray();
									if (is_array($titles)) {
										$fieldvalue = CsviHelper::replaceValue($field->replace, implode('~', $titles));
										// Check if we have any content otherwise use the default value
									}
									else $fieldvalue = '';
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
									break;
								case 'custom_value':
								case 'custom_price':
								case 'custom_param':
									if (!isset($this->_customfields[$record->virtuemart_product_id][$fieldname])) {
										$query = $db->getQuery(true);
										$query->select($db->qn($fieldname));
										$query->from($db->qn('#__virtuemart_product_customfields'));
										$query->where($db->qn('virtuemart_product_id').' = '.$db->q($record->virtuemart_product_id));
										// Check if we need to filter
										$title_filter = array();
										$title_filter = $template->get('custom_title', 'product', array());
										if (!empty($title_filter) && $title_filter[0] != '') {
											$query->where($db->qn('virtuemart_custom_id').' IN ('.implode(',', $title_filter).')');
										}
										$query->order($db->qn('virtuemart_custom_id'));
										$db->setQuery($query);
										$customfields = $db->loadObjectList();
										$csvilog->addDebug('COM_CSVI_CUSTOM_FIELD_QUERY', true);
										if (!empty($customfields)) {
											$values = array();
											foreach ($customfields as $customfield) {
												$values[] = $customfield->$fieldname;
											}
											$this->_customfields[$record->virtuemart_product_id][$fieldname] = $values;
											$fieldvalue = implode('~', $this->_customfields[$record->virtuemart_product_id][$fieldname]);
										}
										else $fieldvalue = '';
									}
									else {
										$fieldvalue = implode('~', $this->_customfields[$record->virtuemart_product_id][$fieldname]);
									}

									$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
									break;
								case 'file_url':
								case 'file_url_thumb':
									$query = $db->getQuery(true);
									$query->select($db->qn($fieldname));
									$query->from($db->qn('#__virtuemart_medias').' AS m');
									$query->leftJoin($db->qn('#__virtuemart_product_medias').' AS p ON m.virtuemart_media_id = p.virtuemart_media_id');
									$query->where($db->qn('virtuemart_product_id').' = '.$db->q($record->virtuemart_product_id));
									$query->where($db->qn('file_type').' = '.$db->q('product'));
									$query->order('p.ordering');
									$db->setQuery($query);
									$titles = $db->loadResultArray();
									if (is_array($titles)) {
										$fieldvalue = CsviHelper::replaceValue($field->replace, implode('|', $titles));
										// Check if we have any content otherwise use the default value
									}
									else $fieldvalue = '';
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
									break;
								case 'min_order_level':
								case 'max_order_level':
									if (strpos($record->product_params, '|')) {
										$params = explode('|', $record->product_params);
										foreach ($params as $param) {
											if ($param) {
												list($param_name, $param_value) = explode('=', $param);
												if ($param_name == $fieldname) {
													$fieldvalue = str_replace('"', '', $param_value);
												}
											}
										}
									}
									else $fieldvalue = '';

									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								default:
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
							}
						}
					}
					if ($template->get('export_file', 'general') == 'xml' || $template->get('export_file', 'general') == 'html') {
						$this->addExportContent($exportclass->NodeEnd());
					}

					// Output the contents
					$this->writeOutput();
				}
			}
			else {
				$this->addExportContent(JText::_('COM_CSVI_NO_DATA_FOUND'));
				// Output the contents
				$this->writeOutput();
			}
		}
	}

	/**
	 * Convert prices to the new currency
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _convertPrice($product_price, $product_currency) {
		if (empty($product_price)) return $product_price;
		else {
			$jinput = JFactory::getApplication()->input;
			$template = $jinput->get('template', null, null);
			// See if we need to convert the price
			if ($template->get('targetcurrency', 'product', '') != '') {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('currency_code, currency_rate');
				$query->from('#__csvi_currency');
				$query->where('currency_code IN ('.$db->q($product_currency).", ".$db->q($template->get('targetcurrency', 'product', 'EUR')).")");
				$db->setQuery($query);
				$rates = $db->loadObjectList('currency_code');

				/* Convert to base price */
				$baseprice = $product_price / $rates[strtoupper($product_currency)]->currency_rate;

				/* Convert to destination currency */
				return $baseprice * $rates[strtoupper($template->get('targetcurrency', 'product', 'EUR'))]->currency_rate;
			}
			else return $product_price;
		}
	}

	/**
	 * Get product prices
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		int	$product_id	the ID of the product
	 * @return
	 * @since 		4.0
	 */
	private function _getProductPrice($product_id) {
		if (!isset($this->_prices[$product_id])) {
			// Define VM constant to make the classes work
			if (!defined('JPATH_VM_ADMINISTRATOR')) define('JPATH_VM_ADMINISTRATOR', JPATH_ADMINISTRATOR.'/components/com_virtuemart/');

			// Load the configuration for the currency formatting
			require_once(JPATH_ADMINISTRATOR.'/components/com_virtuemart/helpers/config.php');

			// Include the calculation helper
			require_once(JPATH_ADMINISTRATOR.'/components/com_virtuemart/helpers/calculationh.php');
			$calc = calculationHelper::getInstance();

			// Include the version class to compare
			require_once(JPATH_ADMINISTRATOR.'/components/com_virtuemart/version.php');

			// Do a version check due to changed core code
			if (version_compare(vmVersion::$RELEASE, '2.0.6', '>')) {
				require_once(JPATH_ADMINISTRATOR.'/components/com_virtuemart/models/product.php');
				$product = $this->getInstance('Product', 'VirtueMartModel');
				$prices = $calc->getProductPrices($product->getProductSingle($product_id));
			}
			else {
				$prices = $calc->getProductPrices($product_id);
			}
			if (is_array($prices)) $this->_prices[$product_id] = array_change_key_case($prices, CASE_LOWER);
			else $this->_prices[$product_id] = array();
		}
		return $this->_prices[$product_id];
	}
}
?>