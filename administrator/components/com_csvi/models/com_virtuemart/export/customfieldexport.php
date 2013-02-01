<?php
/**
 * Custom field export class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: couponexport.php 1834 2012-01-21 15:08:16Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for custom field exports
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviModelCustomfieldExport extends CsviModelExportfile {

	// Private variables
	private $_exportmodel = null;
	private $_plugins = array();
	private $_vendors = array();

	/**
	 * Custom field export
	 *
	 * Exports custom field details data to either csv, xml or HTML format
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
					case 'custom';
					case 'vendor_name':
						break;
					case 'plugin_name':
						$userfields[] = $db->quoteName('#__virtuemart_customs').'.'.$db->quoteName('custom_jplugin_id');
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
		$query->from('#__virtuemart_customs');
		
		// Check if there are any selectors
		$selectors = array();
		
		// Filter by published state
		$publish_state = $template->get('publish_state', 'general');
		if ($publish_state != '' && ($publish_state == 1 || $publish_state == 0)) {
			$selectors[] = '#__virtuemart_customs.published = '.$publish_state;
		}
		
		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0 ) $query->where(implode("\n AND ", $selectors));
		
		// Ignore these fields
		$ignore = array('custom', 'plugin_name', 'vendor_name');
		
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
							case 'plugin_name':
								if (!isset($this->_plugins[$record->custom_jplugin_id])) {
									$query = $db->getQuery(true);
									$query->select($db->quoteName('name'));
									$query->from($db->quoteName('#__extensions'));
									$query->where($db->quoteName('extension_id').' = '.$db->Quote($record->custom_jplugin_id));
									$query->where($db->quoteName('type').' = '.$db->Quote('plugin'));
									$db->setQuery($query);
									$name = $db->loadResult();
									$this->_plugins[$record->custom_jplugin_id]= $name;
								}
								else $name = $this->_plugins[$record->custom_jplugin_id];
								$name = CsviHelper::replaceValue($field->replace, $name);
								$this->addExportField($field->combine, $name, $fieldname, $field->column_header);
								break;
							case 'vendor_name':
								if (!isset($this->_vendors[$record->virtuemart_vendor_id])) {
									$query = $db->getQuery(true);
									$query->select($db->quoteName('vendor_name'));
									$query->from($db->quoteName('#__virtuemart_vendors'));
									$query->where($db->quoteName('virtuemart_vendor_id').' = '.$db->Quote($record->virtuemart_vendor_id));
									$db->setQuery($query);
									$name = $db->loadResult();
									$this->_vendors[$record->virtuemart_vendor_id]= $name;
								}
								else $name = $this->_vendors[$record->virtuemart_vendor_id];
								$name = CsviHelper::replaceValue($field->replace, $name);
								$this->addExportField($field->combine, $name, $fieldname, $field->column_header);
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