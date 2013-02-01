<?php
/**
 * Order export class
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orderexport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

/**
 * Processor for order exports
 */
class CsviModelOrderExport extends CsviModelExportfile {

	/**
	 * Order export
	 *
	 * Exports order details
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		void
	 * @since 		3.0
	 */
	public function getStart() {

		// Get some basic data
		$db = JFactory::getDbo();
		$csvidb = new CsviDb();
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$exportclass =  $jinput->get('export.class', null, null);
		$export_fields = $jinput->get('export.fields', array(), 'array');

		$address = strtoupper($template->get('order_address', 'order', false));
		if ($address == 'BTST') $user_info_fields = CsviModelAvailablefields::DbFields('virtuemart_order_userinfos');
		else $user_info_fields = array();

		// Build something fancy to only get the fieldnames the user wants
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			switch ($field->field_name) {
				case 'created_by':
				case 'created_on':
				case 'locked_by':
				case 'locked_on':
				case 'modified_by':
				case 'modified_on':
				case 'order_status':
				case 'virtuemart_user_id':
				case 'virtuemart_vendor_id':
				case 'virtuemart_order_id':
					$userfields[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName($field->field_name);
					break;
				case 'email':
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName($field->field_name);
					break;
				case 'id':
					$userfields[] = $db->quoteName('#__users').'.'.$db->quoteName($field->field_name);
					break;
				case 'payment_element':
					$userfields[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('virtuemart_paymentmethod_id');
					break;
				case 'shipment_element':
					$userfields[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('virtuemart_shipmentmethod_id');
					break;
				case 'state_2_code':
				case 'state_3_code':
				case 'state_name':
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('virtuemart_state_id');
					break;
				case 'country_2_code':
				case 'country_3_code':
				case 'country_name':
				case 'virtuemart_country_id':
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('virtuemart_country_id');
					break;
				case 'user_currency':
					$userfields[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('user_currency_id');
					break;
				case 'username':
					$userfields[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('virtuemart_user_id');
					break;
				case 'full_name':
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('first_name');
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('middle_name');
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('last_name');
					break;
				case 'total_order_items':
					$userfields[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('virtuemart_order_id');
					break;
				case 'product_price_total':
					$userfields[] = 'product_item_price*product_quantity AS product_price_total';
					break;
				case 'discount_percentage':
					$userfields[] = '(order_discount/order_total)*100 AS discount_percentage';
					break;
				case 'custom':
					// These are man made fields, do not try to get them from the database
					break;
				default:
					if ($address == 'BTST' && preg_match("/".$field->field_name."/i", join(",", array_keys($user_info_fields)))) {
						$userfields[] = 'COALESCE(user_info2.'.$field->field_name.', user_info1.'.$field->field_name.') AS '.$field->field_name;
					}
					else $userfields[] = $db->quoteName($field->field_name);
					break;
			}
		}

		// Build the query
		$userfields = array_unique($userfields);
		$query = $db->getQuery(true);
		$query->select(implode(",\n", $userfields));
		$query->from('#__virtuemart_orders');
		$query->leftJoin('#__virtuemart_order_items ON #__virtuemart_orders.virtuemart_order_id = #__virtuemart_order_items.virtuemart_order_id');
		$query->leftJoin('#__virtuemart_order_userinfos AS user_info1 ON #__virtuemart_orders.virtuemart_order_id = user_info1.virtuemart_order_id');
		if ($address == 'BTST') {
			$query->leftJoin('#__virtuemart_order_userinfos AS user_info2 ON #__virtuemart_orders.virtuemart_order_id = user_info2.virtuemart_order_id AND user_info2.address_type = '.$db->Quote('ST'));
		}
		$query->leftJoin('#__virtuemart_orderstates ON #__virtuemart_orders.order_status = #__virtuemart_orderstates.order_status_code');
		$query->leftJoin('#__virtuemart_product_manufacturers ON #__virtuemart_order_items.virtuemart_product_id = #__virtuemart_product_manufacturers.virtuemart_product_id');
		$query->leftJoin('#__virtuemart_manufacturers ON #__virtuemart_product_manufacturers.virtuemart_manufacturer_id = #__virtuemart_manufacturers.virtuemart_manufacturer_id');
		$query->leftJoin('#__users ON #__users.id = user_info1.virtuemart_user_id');
		$query->leftJoin('#__virtuemart_countries ON #__virtuemart_countries.virtuemart_country_id = user_info1.virtuemart_country_id');

		// Check if there are any selectors
		$selectors = array();

		// Filter by manufacturer
		$manufacturer = $template->get('ordermanufacturer', 'order', false);
		if ($manufacturer && $manufacturer[0] != 'none') {
			$selectors[] = '#__virtuemart_manufacturers.virtuemart_manufacturer_id IN ('.implode(',', $manufacturer).')';
		}

		// Filter by order number start
		$ordernostart = $template->get('ordernostart', 'order', false, 'int');
		if ($ordernostart > 0) {
			$selectors[] = '#__virtuemart_orders.virtuemart_order_id >= '.$ordernostart;
		}

		// Filter by order number end
		$ordernoend = $template->get('ordernoend', 'order', false, 'int');
		if ($ordernoend > 0) {
			$selectors[] = '#__virtuemart_orders.virtuemart_order_id <= '.$ordernoend;
		}

		// Filter by list of order numbers
		$orderlist = $template->get('orderlist', 'order');
		if ($orderlist) {
			$selectors[] = '#__virtuemart_orders.virtuemart_order_id IN ('.$orderlist.')';
		}

		// Filter by order date start
		$orderdatestart = $template->get('orderdatestart', 'order', false);
		if ($orderdatestart) {
			$orderdate = JFactory::getDate($orderdatestart);
			$selectors[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('created_on').' >= '.$db->Quote($orderdate->toMySQL());
		}

		// Filter by order date end
		$orderdateend = $template->get('orderdateend', 'order', false);
		if ($orderdateend) {
			$orderdate = JFactory::getDate($orderdateend);
			$selectors[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('created_on').' <= '.$db->Quote($orderdate->toMySQL());
		}

		// Filter by order modified date start
		$ordermdatestart = $template->get('ordermdatestart', 'order', false);
		if ($ordermdatestart) {
			$ordermdate = JFactory::getDate($ordermdatestart);
			$selectors[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('modified_on').' >= '.$db->Quote($ordermdate->toMySQL());
		}

		// Filter by order modified date end
		$ordermdateend = $template->get('ordermdateend', 'order', false);
		if ($ordermdateend) {
			$ordermdate = JFactory::getDate($ordermdateend);
			$selectors[] = $db->quoteName('#__virtuemart_orders').'.'.$db->quoteName('modified_on').' <= '.$db->Quote($ordermdate->toMySQL());
		}

		// Filter by order status
		$orderstatus = $template->get('orderstatus', 'order', false);
		if ($orderstatus && $orderstatus[0] != '') {
			$selectors[] = '#__virtuemart_orders.order_status IN (\''.implode("','", $orderstatus).'\')';
		}

		// Filter by order price start
		$pricestart = $template->get('orderpricestart', 'order', false, 'float');
		if ($pricestart) {
			$selectors[] = '#__virtuemart_orders.order_total >= '.$pricestart;
		}

		// Filter by order price end
		$priceend = $template->get('orderpriceend', 'order', false, 'float');
		if ($priceend) {
			$selectors[] = '#__virtuemart_orders.order_total <= '.$priceend;
		}

		// Filter by order user id
		$orderuser = $template->get('orderuser', 'order', false);
		if ($orderuser && $orderuser[0] != '') {
			$selectors[] = '#__virtuemart_orders.virtuemart_user_id IN (\''.implode("','", $orderuser).'\')';
		}

		// Filter by order product
		$orderproduct = $template->get('orderproduct', 'order', false);
		if ($orderproduct && $orderproduct[0] != '') {
			$selectors[] = '#__virtuemart_order_items.order_item_sku IN (\''.implode("','", $orderproduct).'\')';
		}

		// Filter by address type
		if ($address) {
			switch (strtoupper($address)) {
				case 'BTST':
					$selectors[] = "user_info1.address_type = 'BT'";
					break;
				default:
					$selectors[] = 'user_info1.address_type = '.$db->Quote(strtoupper($address));
					break;
			}
		}

		// Filter by order currency
		$ordercurrency = $template->get('ordercurrency', 'order', false);
		if ($ordercurrency && $ordercurrency[0] != '') {
			$selectors[] = '#__virtuemart_orders.order_currency IN (\''.implode("','", $ordercurrency).'\')';
		}

		// Filter by payment method
		$orderpayment = $template->get('orderpayment', 'order', false);
		if ($orderpayment && $orderpayment[0] != '') {
			$selectors[] = '#__virtuemart_orders.virtuemart_paymentmethod_id IN (\''.implode("','", $orderpayment).'\')';
		}

		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0) $query->where(implode("\n AND ", $selectors));

		// Check if we need to group the orders together
		$groupby = $template->get('groupby', 'general', false, 'bool');
		if ($groupby) {
			$filter = $this->getFilterBy('groupby', $address, $user_info_fields);
			if (!empty($filter)) $query->group($filter);
		}

		// Order by set field
		$orderby = $this->getFilterBy('sort', $address, $user_info_fields);
		if (!empty($orderby)) $query->order($orderby);

		// Add a limit if user wants us to
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
			$jinput->set('logcount', $logcount);
			if ($logcount > 0) {
				$linenumber = 1;
				while ($record = $csvidb->getRow()) {
					$csvilog->setLinenumber($linenumber++);
					if ($template->get('export_file', 'general') == 'xml' || $template->get('export_file', 'general') == 'html') $this->addExportContent($exportclass->NodeStart());
					foreach ($export_fields as $column_id => $field) {
						if ($field->process) {
							$fieldname = $field->field_name;
							// Add the replacement
							if (isset($record->$fieldname)) $fieldvalue = CsviHelper::replaceValue($field->replace, $record->$fieldname);
							else $fieldvalue = '';
							switch ($fieldname) {
								case 'payment_element':
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__virtuemart_paymentmethods');
									$query->where('virtuemart_paymentmethod_id = '.$record->virtuemart_paymentmethod_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'shipment_element':
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__virtuemart_shipmentmethods');
									$query->where('virtuemart_shipmentmethod_id = '.$record->virtuemart_shipmentmethod_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'state_2_code':
								case 'state_3_code':
								case 'state_name':
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__virtuemart_states');
									$query->where('virtuemart_state_id = '.$record->virtuemart_state_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'country_2_code':
								case 'country_3_code':
								case 'country_name':
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__virtuemart_countries');
									$query->where('virtuemart_country_id = '.$record->virtuemart_country_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'user_currency':
									$query = $db->getQuery(true);
									$query->select('currency_code_3');
									$query->from('#__virtuemart_currencies');
									$query->where('virtuemart_currency_id = '.$record->user_currency_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'user_email':
									$fieldvalue = CsviHelper::replaceValue($field->replace, $record->email);
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'user_id':
									$fieldvalue = CsviHelper::replaceValue($field->replace, $record->virtuemart_user_id);
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'created_on':
								case 'modified_on':
								case 'locked_on':
									$date = JFactory::getDate($record->$fieldname);
									$fieldvalue = CsviHelper::replaceValue($field->replace, date($template->get('export_date_format', 'general'), $date->toUnix()));
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'address_type':
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									if ($fieldvalue == 'BT') $fieldvalue = JText::_('COM_CSVI_BILLING_ADDRESS');
									else if ($fieldvalue == 'ST') $fieldvalue = JText::_('COM_CSVI_SHIPPING_ADDRESS');
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'full_name':
									$fieldvalue = str_replace('  ', ' ', $record->first_name.' '.$record->middle_name.' '.$record->last_name);
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'total_order_items':
									$query = $db->getQuery(true);
									$query->select('COUNT(virtuemart_order_id) AS totalitems');
									$query->from('#__virtuemart_order_items');
									$query->where('virtuemart_order_id = '.$record->virtuemart_order_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'custom':
									// Has no database value, get the default value only
									$fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'username':
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__users');
									$query->where('id = '.$record->virtuemart_user_id);
									$db->setQuery($query);
									$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
									$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
									break;
								case 'order_tax':
								case 'order_total':
								case 'order_subtotal':
								case 'order_shipment':
								case 'order_shipment_tax':
								case 'order_payment':
								case 'order_payment_tax':
								case 'coupon_discount':
								case 'order_discount':
								case 'user_currency_rate':
								case 'product_price_total':
								case 'discount_percentage':
									$fieldvalue =  number_format($fieldvalue, $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
									$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
									// Check if we have any content otherwise use the default value
									if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
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

					// Clean the totalitems
					JRequest::setVar('total_order_items', 0);

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
	 * Create an SQL filter
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param 		string	$filter	what kind of SQL type should be created
	 * @return 		string	the SQL part to add to the query
	 * @since 		3.0
	 */
	protected function getFilterBy($filter, $address, $user_info_fields) {
		$db = JFactory::getDBO();
		$export_fields = JRequest::getVar('export.fields');
		$fields = array();

		foreach ($export_fields as $column_id => $field) {
			switch ($filter) {
				case 'groupby':
					$process = true;
					break;
				case 'sort':
					$process = $field->sort;
					break;
				default:
					$process = false;
			}
			if ($process) {
				switch ($field->field_name) {
					case 'custom':
					case 'total_order_items':
					case 'discount_percentage':
					case 'product_price_total':
					case 'full_name':
					case 'payment_element':
					case 'shipment_element':
					case 'state_2_code':
					case 'state_3_code':
					case 'state_name':
					case 'country_2_code':
					case 'country_3_code':
					case 'country_name':
					case 'user_currency':
					case 'user_email':
					case 'user_id':
					case 'virtuemart_country_id':
						break;
					case 'user_id':
						$fields[] = $db->qn('#__virtuemart_orders.virtuemart_user_id');
						break;
					case 'product_price':
						$fields[] = $db->qn('product_item_price');
						break;
					case 'ordering':
						$fields[] = $db->qn('#__virtuemart_orderstates.ordering');
						break;
					default:
						if ($address == 'BTST' && preg_match("/".$field->field_name."/i", join(",", array_keys($user_info_fields)))) {
							$fields[] = $db->qn('user_info1.'.$field->field_name);
						}
						else $fields[] = $db->qn($field->field_name);
						break;
				}
			}
		}

		// Construct the SQL part
		if (!empty($fields)) {
			switch ($filter) {
				case 'groupby':
					$groupby_fields = array_unique($fields);
					$q = implode(',', $groupby_fields);
					break;
				case 'sort':
					$sort_fields = array_unique($fields);
					$q = implode(', ', $sort_fields);
					break;
				default:
					$q = '';
					break;
			}
		}
		else $q = '';

		return $q;
	}
}
?>