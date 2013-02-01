<?php
/**
 * Order items export class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orderitemexport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for order items exports
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviModelOrderitemExport extends CsviModelExportfile {

	/**
	 * Order items export
	 *
	 * Exports order items data to either csv, xml or HTML format
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
		$db = JFactory::getDbo();
		$csvidb = new CsviDb();
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		$exportclass =  $jinput->get('export.class', null, null);
		$export_fields = $jinput->get('export.fields', array(), 'array');

		// Build something fancy to only get the fieldnames the user wants
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			switch ($field->field_name) {
				case 'created_on':
				case 'modified_on':
				case 'locked_on':
				case 'created_by':
				case 'modified_by':
				case 'locked_by':
				case 'virtuemart_order_id':
				case 'order_status':
				case 'virtuemart_vendor_id':
					$userfields[] = $db->quoteName('#__virtuemart_order_items').'.'.$db->quoteName($field->field_name);
					break; 
				case 'product_sku':
					$userfields[] = $db->quoteName('#__virtuemart_order_items').'.'.$db->quoteName('order_item_sku').' AS product_sku';
					break;
				case 'full_name':
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('first_name');
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('middle_name');
					$userfields[] = $db->quoteName('user_info1').'.'.$db->quoteName('last_name');
					break;
				default:
					$userfields[] = $db->quoteName($field->field_name);
					break;
			}
		}

		// Construct the query
		// Build the query
		$userfields = array_unique($userfields);
		$query = $db->getQuery(true);
		$query->select(implode(",\n", $userfields));
		$query->from('#__virtuemart_order_items');
		$query->leftJoin('#__virtuemart_orders ON #__virtuemart_orders.virtuemart_order_id = #__virtuemart_order_items.virtuemart_order_id');
		$query->leftJoin('#__virtuemart_order_userinfos AS user_info1 ON user_info1.virtuemart_order_id = #__virtuemart_order_items.virtuemart_order_id');
		$query->leftJoin('#__virtuemart_orderstates ON #__virtuemart_orderstates.order_status_code = #__virtuemart_order_items.order_status');

		// Check if there are any selectors
		$selectors = array();

		// Filter by order number start
		$ordernostart = $template->get('orderitemnostart', 'orderitem', array(), 'int');
		if ($ordernostart > 0) {
			$selectors[] = '#__virtuemart_order_items.virtuemart_order_id >= '.$ordernostart;
		}

		// Filter by order number end
		$ordernoend = $template->get('orderitemnoend', 'orderitem', array(), 'int');
		if ($ordernoend > 0) {
			$selectors[] = '#__virtuemart_order_items.virtuemart_order_id <= '.$ordernoend;
		}

		// Filter by list of order numbers
		$orderlist = $template->get('orderitemlist', 'orderitem');
		if ($orderlist) {
			$selectors[] = '#__virtuemart_order_items.virtuemart_order_id IN ('.$orderlist.')';
		}

		// Filter by order date start
		$orderdatestart = $template->get('orderitemdatestart', 'orderitem', false);
		if ($orderdatestart) {
			$orderdate = JFactory::getDate($orderdatestart);
			$selectors[] = '#__virtuemart_order_items.created_on >= '.$db->Quote($orderdate->toMySQL());
		}

		// Filter by order date end
		$orderdateend = $template->get('orderitemdateend', 'orderitem', false);
		if ($orderdateend) {
			$orderdate = JFactory::getDate($orderdateend);
			$selectors[] = '#__virtuemart_order_items.created_on <= '.$db->Quote($orderdate->toMySQL());
		}

		// Filter by order modified date start
		$ordermdatestart = $template->get('orderitemmdatestart', 'orderitem', false);
		if ($ordermdatestart) {
			$ordermdate = JFactory::getDate($ordermdatestart);
			$selectors[] = '#__virtuemart_order_items.modified_on >= '.$db->Quote($ordermdate->toMySQL());
		}

		// Filter by order modified date end
		$ordermdateend = $template->get('orderitemmdateend', 'orderitem', false);
		if ($ordermdateend) {
			$ordermdate = JFactory::getDate($ordermdateend);
			$selectors[] = '#__virtuemart_order_items.modified_on <= '.$db->Quote($ordermdate->toMySQL());
		}

		// Filter by order status
		$orderstatus = $template->get('orderitemstatus', 'orderitem', false);
		if ($orderstatus && $orderstatus[0] != '') {
			$selectors[] = '#__virtuemart_order_items.order_status IN (\''.implode("','", $orderstatus).'\')';
		}

		// Filter by order price start
		$pricestart = $template->get('orderitempricestart', 'orderitem', false, 'float');
		if ($pricestart) {
			$selectors[] = '#__virtuemart_orders.order_total >= '.$pricestart;
		}

		// Filter by order price end
		$priceend = $template->get('orderitempriceend', 'orderitem', false, 'float');
		if ($priceend) {
			$selectors[] = '#__virtuemart_orders.order_total <= '.$priceend;
		}

		// Filter by order product
		$orderproduct = $template->get('orderitemproduct', 'orderitem', false);
		if ($orderproduct && $orderproduct[0] != '') {
			$selectors[] = '#__virtuemart_order_items.order_item_sku IN (\''.implode("','", $orderproduct).'\')';
		}

		// Filter by order currency
		$ordercurrency = $template->get('orderitemcurrency', 'orderitem', false);
		if ($ordercurrency && $ordercurrency[0] != '') {
			$selectors[] = '#__virtuemart_orders.order_currency IN (\''.implode("','", $ordercurrency).'\')';
		}

		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0) $query->where(implode("\n AND ", $selectors));

		// Ignore fields
		$ignore = array('full_name');

		// Check if we need to group the orders together
		$groupby = $template->get('groupby', 'general', false, 'bool');
		if ($groupby) {
			$filter = $this->getFilterBy('groupby', $ignore);
			if (!empty($filter)) $query->group($filter);
		}

		// Order by set field
		$orderby = $this->getFilterBy('sort', $ignore);
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
						$fieldname = $field->field_name;
						// Add the replacement
						if (isset($record->$fieldname)) $fieldvalue = CsviHelper::replaceValue($field->replace, $record->$fieldname);
						else $fieldvalue = '';
						switch ($fieldname) {
							case 'created_on':
							case 'modified_on':
							case 'locked_on':
								$date = JFactory::getDate($record->$fieldname);
								$fieldvalue = CsviHelper::replaceValue($field->replace, date($template->get('export_date_format', 'general'), $date->toUnix()));
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
								break;
							case 'product_item_price':
							case 'product_final_price':
							case 'product_price':
								$fieldvalue =  number_format($fieldvalue, $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
								// Check if we have any content otherwise use the default value
								if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
								break;
							case 'full_name':
								$fieldvalue = str_replace('  ', ' ', $record->first_name.' '.$record->middle_name.' '.$record->last_name);
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
}
?>