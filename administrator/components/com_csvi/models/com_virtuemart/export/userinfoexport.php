<?php
/**
 * User info export class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: userinfoexport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for user info exports
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviModelUserInfoExport extends CsviModelExportfile {

	/**
	 * User info export
	 *
	 * Exports user info data to either csv, xml or HTML format
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
				case 'virtuemart_user_id':
				case 'created_on':
				case 'modified_on':
				case 'locked_on':
				case 'created_by':
				case 'modified_by':
				case 'locked_by':
				case 'name':
				case 'agreed':
					$userfields[] = $db->quoteName('#__virtuemart_userinfos').'.'.$db->quoteName($field->field_name);
					break;
				case 'full_name':
					$userfields[] = $db->quoteName('#__virtuemart_userinfos').'.'.$db->quoteName('first_name');
					$userfields[] = $db->quoteName('#__virtuemart_userinfos').'.'.$db->quoteName('middle_name');
					$userfields[] = $db->quoteName('#__virtuemart_userinfos').'.'.$db->quoteName('last_name');
					break;
				case 'id':
					$userfields[] = $db->quoteName('#__users').'.'.$db->quoteName('id');
					break;
				case 'usergroup_name':
					$userfields[] = $db->quoteName('#__virtuemart_userinfos').'.'.$db->quoteName('virtuemart_user_id');
					break;
				case 'virtuemart_vendor_id':
					$userfields[] = $db->quoteName('#__virtuemart_vmusers').'.'.$db->quoteName('virtuemart_vendor_id');
					break;
				case 'state_2_code':
				case 'state_3_code':
				case 'state_name':
					$userfields[] = $db->quoteName('#__virtuemart_userinfos').'.'.$db->quoteName('virtuemart_state_id');
					break;
				case 'country_2_code':
				case 'country_3_code':
				case 'country_name':
				case 'virtuemart_country_id':
					$userfields[] = $db->quoteName('#__virtuemart_userinfos').'.'.$db->quoteName('virtuemart_country_id');
					break;
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
		$query->from('#__virtuemart_userinfos');
		$query->leftJoin('#__virtuemart_vmusers ON #__virtuemart_vmusers.virtuemart_user_id = #__virtuemart_userinfos.virtuemart_user_id');
		$query->leftJoin('#__virtuemart_vmuser_shoppergroups ON #__virtuemart_vmuser_shoppergroups.virtuemart_user_id = #__virtuemart_userinfos.virtuemart_user_id');
		$query->leftJoin('#__virtuemart_vendors ON #__virtuemart_vendors.virtuemart_vendor_id = #__virtuemart_vmusers.virtuemart_vendor_id');
		$query->leftJoin('#__virtuemart_shoppergroups ON #__virtuemart_shoppergroups.virtuemart_shoppergroup_id = #__virtuemart_vmuser_shoppergroups.virtuemart_shoppergroup_id');
		$query->leftJoin('#__users ON #__users.id = #__virtuemart_userinfos.virtuemart_user_id');

		// Check if there are any selectors
		$selectors = array();

		// Filter by vendors
		$vendors = $template->get('vendors', 'userinfo', false);
		if ($vendors && $vendors[0] != 'none') {
			$selectors[] = '#__virtuemart_vmusers.virtuemart_vendor_id IN (\''.implode("','", $vendors).'\')';
		}

		// Filter by permissions
		$permissions = $template->get('permissions', 'userinfo', false);
		if ($permissions && $permissions[0] != 'none') {
			$selectors[] = '#__virtuemart_vmusers.perms IN (\''.implode("','", $permissions).'\')';
		}

		// Filter by address type
		$address = $template->get('userinfo_address', 'userinfo', false);
		if ($address) {
			$selectors[] = '#__virtuemart_userinfos.address_type = '.$db->Quote(strtoupper($address));
		}

		// Filter by user info modified date start
		$date = $template->get('userinfomdatestart', 'userinfo', false);
		if ($date) {
			$userinfomdate = JFactory::getDate($date);
			$selectors[] = '#__virtuemart_userinfos.modified_on >= '.$db->Quote($userinfomdate->toMySQL());
		}

		// Filter by user info date end
		$date = $template->get('userinfomdateend', 'userinfo', false);
		if ($date) {
			$userinfomdate = JFactory::getDate($date);
			$selectors[] = '#__virtuemart_userinfos.modified_on <= '.$db->Quote($userinfomdate->toMySQL());
		}

		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0 ) $query->where(implode("\n AND ", $selectors));

		// Array of fields not to handle
		$ignore = array('full_name', 'usergroup_name', 'state_2_code', 'state_3_code', 'state_name', 'country_2_code', 'country_3_code', 'country_name');

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
			JRequest::setVar('logcount', array('export' => $logcount));
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
							case 'lastvisitdate':
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
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
								// Check if we have any content otherwise use the default value
								if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
								break;
							case 'usergroup_name':
								$query = $db->getQuery(true);
								$query->select($fieldname);
								$query->from('#__usergroups');
								$query->leftJoin('#__user_usergroup_map ON #__user_usergroup_map.group_id = #__usergroups.id');
								$query->where($db->quoteName('user_id').' = '.$record->virtuemart_user_id);
								$db->setQuery($query);
								$fieldvalue = $db->loadResult();
								if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
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