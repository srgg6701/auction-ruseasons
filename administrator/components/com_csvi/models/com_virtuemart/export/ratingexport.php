<?php
/**
 * Rating export class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: ratingexport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for reviews
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviModelRatingExport extends CsviModelExportfile {

	/**
	 * Product reviews export
	 *
	 * Exports product reviews data to either csv, xml or HTML format
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
				case 'virtuemart_product_id':
				case 'published':
					$userfields[] = $db->quoteName('#__virtuemart_rating_reviews').'.'.$db->quoteName($field->field_name);
					break;
				case 'product_sku':
				case 'vote':
					$userfields[] = $db->quoteName('#__virtuemart_rating_reviews').'.'.$db->quoteName('virtuemart_product_id');
					$userfields[] = $db->quoteName('#__virtuemart_rating_reviews').'.'.$db->quoteName('created_by');
					break;
				case 'username':
					$userfields[] = $db->quoteName('#__virtuemart_rating_reviews').'.'.$db->quoteName('created_by');
				// Man made fields, do not export them
				case 'custom':
					break;
				default:
					$userfields[] = $db->quoteName($field->field_name);
					break;
			}
		}

		
		/** Export SQL Query
		 * Get all products - including items
		 * as well as products without a price
		 */
		$userfields = array_unique($userfields);
		$query = $db->getQuery(true);
		$query->select(implode(",\n", $userfields));
		$query->from('#__virtuemart_rating_reviews');
		$query->leftJoin('#__virtuemart_products ON #__virtuemart_products.virtuemart_product_id = #__virtuemart_rating_reviews.virtuemart_product_id');
		$query->leftJoin('#__users ON #__users.id = #__virtuemart_rating_reviews.created_by');

		// Check for any filters
		$selectors = array();

		// Filter by published state
		$publish_state = $template->get('publish_state', 'general');
		if ($publish_state !== '' && ($publish_state == 1 || $publish_state == 0)) {
			$selectors[] = '#__virtuemart_rating_reviews.published = '.$publish_state;
		}

		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0 ) $query->where(implode("\n AND ", $selectors));

		// Fields to ignore
		$ignore = array('product_sku', 'vote', 'username');
		
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
							case 'product_sku':
								$query = $db->getQuery(true);
								$query->select('product_sku');
								$query->from('#__virtuemart_products');
								$query->where('virtuemart_product_id = '.$record->virtuemart_product_id);
								$db->setQuery($query);
								$product_sku = $db->loadResult();
								$product_sku = CsviHelper::replaceValue($field->replace, $product_sku);
								$this->addExportField($field->combine, $product_sku, $fieldname, $field->column_header);
								break;
							case 'vote':
								$query = $db->getQuery(true);
								$query->select('vote');
								$query->from('#__virtuemart_rating_votes');
								$query->where('virtuemart_product_id = '.$record->virtuemart_product_id);
								$query->where('created_by = '.$record->created_by);
								$db->setQuery($query);
								$vote = $db->loadResult();
								$vote = CsviHelper::replaceValue($field->replace, $vote);
								$this->addExportField($field->combine, $vote, $fieldname, $field->column_header);
								break;
							case 'username':
								$query = $db->getQuery(true);
								$query->select('username');
								$query->from('#__users');
								$query->where('id = '.$record->created_by);
								$db->setQuery($query);
								$username = $db->loadResult();
								$username = CsviHelper::replaceValue($field->replace, $username);
								$this->addExportField($field->combine, $username, $fieldname, $field->column_header);
								break;
							case 'created_on':
							case 'modified_on':
							case 'locked_on':
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