<?php
/**
 * Subscription export class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: couponexport.php 1760 2012-01-02 19:50:19Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for coupons exports
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviModelSubscriptionExport extends CsviModelExportfile {

	// Private variables
	private $_exportmodel = null;

	/**
	 * Subscription export
	 *
	 * Exports subscription details data to either csv, xml or HTML format
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
			if ($field->process) {
				switch ($field->field_name) {
					case 'notes':
					case 'params':
					case 'state':
						$userfields[] = $db->quoteName('#__akeebasubs_users').'.'.$db->quoteName($field->field_name);
						break;
					case 'user_id':
						$userfields[] = $db->quoteName('#__akeebasubs_subscriptions').'.'.$db->quoteName('user_id');
						break;
					case 'custom':
						break;
					default:
						$userfields[] = $db->quoteName($field->field_name);
						break;
				}
			}
		}

		// Build the query
		$userfields = array_unique($userfields);
		$query = $db->getQuery(true);
		$query->select(implode(",\n", $userfields));
		$query->from('#__akeebasubs_subscriptions');
		$query->leftJoin('#__akeebasubs_users ON #__akeebasubs_users.user_id = #__akeebasubs_subscriptions.user_id');
		$query->leftJoin('#__users ON #__users.id = #__akeebasubs_subscriptions.user_id');
		
		// Check if there are any selectors
		$selectors = array();
		
		// Filter by published state
		$publish_state = $template->get('publish_state', 'general');
		if ($publish_state !== '' && ($publish_state == 1 || $publish_state == 0)) {
			$selectors[] = '#__akeebasubs_subscriptions.enabled = '.$publish_state;
		}
		
		// Filter by order number start
		$ordernostart = $template->get('ordernostart', 'order', array(), 'int');
		if ($ordernostart > 0) {
			$selectors[] = '#__akeebasubs_subscriptions.akeebasubs_subscription_id >= '.$ordernostart;
		}
		
		// Filter by order number end
		$ordernoend = $template->get('ordernoend', 'order', array(), 'int');
		if ($ordernoend > 0) {
			$selectors[] = '#__akeebasubs_subscriptions.akeebasubs_subscription_id <= '.$ordernoend;
		}
		
		// Filter by list of order numbers
		$orderlist = $template->get('orderlist', 'order');
		if ($orderlist) {
			$selectors[] = '#__akeebasubs_subscriptions.akeebasubs_subscription_id IN ('.$orderlist.')';
		}
		
		// Filter by order date start
		$orderdatestart = $template->get('orderdatestart', 'order', false);
		if ($orderdatestart) {
			$orderdate = JFactory::getDate($orderdatestart);
			$selectors[] = $db->quoteName('#__akeebasubs_subscriptions').'.'.$db->quoteName('created_on').' >= '.$db->Quote($orderdate->toMySQL());
		}
		
		// Filter by order date end
		$orderdateend = $template->get('orderdateend', 'order', false);
		if ($orderdateend) {
			$orderdate = JFactory::getDate($orderdateend);
			$selectors[] = $db->quoteName('#__akeebasubs_subscriptions').'.'.$db->quoteName('created_on').' <= '.$db->Quote($orderdate->toMySQL());
		}
		
		// Filter by order status
		$orderstatus = $template->get('orderstatus', 'order', false);
		if ($orderstatus && $orderstatus[0] != '') {
			$selectors[] = '#__akeebasubs_subscriptions.state IN (\''.implode("','", $orderstatus).'\')';
		}
		
		// Filter by payment method
		$orderpayment = $template->get('orderpayment', 'order', false);
		if ($orderpayment && $orderpayment[0] != '') {
			$selectors[] = '#__akeebasubs_subscriptions.processor IN (\''.implode("','", $orderpayment).'\')';
		}
		
		// Filter by order price start
		$pricestart = $template->get('orderpricestart', 'order', false, 'float');
		if ($pricestart) {
			$selectors[] = '#__akeebasubs_subscriptions.gross_amount >= '.$pricestart;
		}
		
		// Filter by order price end
		$priceend = $template->get('orderpriceend', 'order', false, 'float');
		if ($priceend) {
			$selectors[] = '#__akeebasubs_subscriptions.gross_amount <= '.$priceend;
		}
		
		// Filter by order user id
		$orderuser = $template->get('orderuser', 'order', false);
		if ($orderuser && $orderuser[0] != '') {
			$selectors[] = '#__akeebasubs_subscriptions.user_id IN (\''.implode("','", $orderuser).'\')';
		}
		
		// Filter by order product
		$orderproduct = $template->get('orderproduct', 'order', false);
		if ($orderproduct && $orderproduct[0] != '') {
			$selectors[] = '#__akeebasubs_subscriptions.akeebasubs_level_id IN (\''.implode("','", $orderproduct).'\')';
		}
		
		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0 ) $query->where(implode("\n AND ", $selectors));
		
		// Check if we need to group the orders together
		$groupby = $template->get('groupby', 'general', false, 'bool');
		if ($groupby) {
			$filter = $this->getFilterBy('groupby');
			if (!empty($filter)) $query->group($filter);
		}

		// Order by set field
		$orderby = $this->getFilterBy('sort');
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
				while ($record = $csvidb->getRow()) {
					if ($template->get('export_file', 'general') == 'xml' || $template->get('export_file', 'general') == 'html') $this->addExportContent($exportclass->NodeStart());
					foreach ($export_fields as $column_id => $field) {
						$fieldname = $field->field_name;
						// Add the replacement
						if (isset($record->$fieldname)) $fieldvalue = CsviHelper::replaceValue($field->replace, $record->$fieldname);
						else $fieldvalue = '';
						switch ($fieldname) {
							case 'net_amount':
							case 'tax_amount':
							case 'gross_amount':
							case 'prediscount_amount':
							case 'discount_amount':
							case 'affiliate_comission':
								$fieldvalue =  number_format($fieldvalue, $template->get('export_price_format_decimal', 'general', 2, 'int'), $template->get('export_price_format_decsep', 'general'), $template->get('export_price_format_thousep', 'general'));
								if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
								break;
							case 'publish_up':
							case 'publish_down':
							case 'created_on':
							case 'first_contact':
							case 'second_contact':
								$date = JFactory::getDate($record->$fieldname);
								$fieldvalue = CsviHelper::replaceValue($field->replace, date($template->get('export_date_format', 'general'), $date->toUnix()));
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