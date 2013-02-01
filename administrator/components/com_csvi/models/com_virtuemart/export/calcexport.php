<?php
/**
 * Calculation rules export class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: calcexport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for calculation rules exports
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviModelCalcExport extends CsviModelExportfile {

	// Private variables
	private $_exportmodel = null;

	/**
	 * Calculation rules export
	 *
	 * Exports calculation rules details data to either csv, xml or HTML format
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
		$helper = new Com_VirtueMart();

		// Build something fancy to only get the fieldnames the user wants
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			if ($field->process) {
				switch ($field->field_name) {
					case 'virtuemart_calc_id':
					case 'created_by':
					case 'created_on':
					case 'locked_by':
					case 'locked_on':
					case 'modified_by':
					case 'modified_on':
					case 'ordering':
					case 'published':
					case 'shared':
					case 'virtuemart_vendor_id':
						$userfields[] = '#__virtuemart_calcs.'.$field->field_name;
						break;
					case 'category_path':
					case 'shopper_group_name':
					case 'country_name':
					case 'country_2_code':
					case 'country_3_code':
					case 'state_name':
					case 'state_2_code':
					case 'state_3_code':
						$userfields[] = '#__virtuemart_calcs.virtuemart_calc_id';
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
		$query->from('#__virtuemart_calcs');
		$query->leftJoin('#__virtuemart_calc_categories ON #__virtuemart_calc_categories.virtuemart_calc_id = #__virtuemart_calcs.virtuemart_calc_id');
		$query->leftJoin('#__virtuemart_calc_countries ON #__virtuemart_calc_countries.virtuemart_calc_id = #__virtuemart_calcs.virtuemart_calc_id');
		$query->leftJoin('#__virtuemart_calc_shoppergroups ON #__virtuemart_calc_shoppergroups.virtuemart_calc_id = #__virtuemart_calcs.virtuemart_calc_id');
		$query->leftJoin('#__virtuemart_calc_states ON #__virtuemart_calc_states.virtuemart_calc_id = #__virtuemart_calcs.virtuemart_calc_id');
		$query->leftJoin('#__virtuemart_currencies ON #__virtuemart_currencies.virtuemart_currency_id = #__virtuemart_calcs.calc_currency');

		// Check if there are any selectors
		$selectors = array();
		
		// Filter by published state
		$publish_state = $template->get('publish_state', 'general');
		if ($publish_state != '' && ($publish_state == 1 || $publish_state == 0)) {
			$selectors[] = '#__virtuemart_calcs.published = '.$publish_state;
		}
		
		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0 ) $query->where(implode("\n AND ", $selectors));
		
		// Ignore fields
		$ignore = array('category_path', 'shopper_group_name','country_name', 'country_2_code', 'country_3_code', 'state_name', 'state_2_code', 'state_3_code');

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
							case 'category_path':
								// Get all the category IDs
								$query = $db->getQuery(true);
								$query->select('virtuemart_category_id');
								$query->from('#__virtuemart_calc_categories');
								$query->where('virtuemart_calc_id = '.$record->virtuemart_calc_id);
								$db->setQuery($query);
								$catids = $db->loadColumn();
								if (!empty($catids)) {
									$categories = array();
									foreach ($catids as $catid) {
										$categories[] = $helper->createCategoryPathById($catid);
									}
									$fieldvalue = implode('|', $categories);
								}
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
							   break;
							case 'shopper_group_name':
								$query = $db->getQuery(true);
								$query->select('virtuemart_shoppergroup_id');
								$query->from('#__virtuemart_calc_shoppergroups');
								$query->where('virtuemart_calc_id = '.$record->virtuemart_calc_id);
								$db->setQuery($query);
								$groupids = $db->loadResultArray();
								if (!empty($groupids)) {
									$query = $db->getQuery(true);
									$query->select('shopper_group_name');
									$query->from('#__virtuemart_shoppergroups');
									$query->where('virtuemart_shoppergroup_id IN ('.implode(',', $groupids).')');
									$db->setQuery($query);
									$names = $db->loadResultArray();
									$fieldvalue = implode('|', $names);
								}
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
								break;
							case 'country_name':
							case 'country_2_code':
							case 'country_3_code':
								$query = $db->getQuery(true);
								$query->select('virtuemart_country_id');
								$query->from('#__virtuemart_calc_countries');
								$query->where('virtuemart_calc_id = '.$record->virtuemart_calc_id);
								$db->setQuery($query);
								$groupids = $db->loadResultArray();
								if (!empty($groupids)) {
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__virtuemart_countries');
									$query->where('virtuemart_country_id IN ('.implode(',', $groupids).')');
									$db->setQuery($query);
									$names = $db->loadResultArray();
									$fieldvalue = implode('|', $names);
								}
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
								break;
							case 'state_name':
							case 'state_2_code':
							case 'state_3_code':
								$query = $db->getQuery(true);
								$query->select('virtuemart_state_id');
								$query->from('#__virtuemart_calc_states');
								$query->where('virtuemart_calc_id = '.$record->virtuemart_calc_id);
								$db->setQuery($query);
								$groupids = $db->loadResultArray();
								if (!empty($groupids)) {
									$query = $db->getQuery(true);
									$query->select($fieldname);
									$query->from('#__virtuemart_states');
									$query->where('virtuemart_state_id IN ('.implode(',', $groupids).')');
									$db->setQuery($query);
									$names = $db->loadResultArray();
									$fieldvalue = implode('|', $names);
								}
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
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