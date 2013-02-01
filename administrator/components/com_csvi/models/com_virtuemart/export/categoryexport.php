<?php
/**
 * Category export class
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: categoryexport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for category details exports
 *
 * @package 	CSVI
 * @subpackage 	Export
 */
class CsviModelCategoryExport extends CsviModelExportfile {

	/**
	 * Category details export
	 *
	 * Exports category details data to either csv, xml or HTML format
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

		// Get all categories
		$query = $db->getQuery(true);
		$query->select('LOWER(l.category_name) AS category_name, category_child_id AS cid, category_parent_id AS pid');
		$query->from('#__virtuemart_categories c');
		$query->leftJoin('#__virtuemart_category_categories x ON x.category_child_id = c.virtuemart_category_id');
		$query->leftJoin('#__virtuemart_categories_'.$template->get('language', 'general').' l ON l.virtuemart_category_id = c.virtuemart_category_id');
		$db->setQuery($query);
		$cats = $db->loadObjectList();

		// Check if there are any categories
		if (empty($cats)) {
			if (!is_null($csvidb->getErrorMsg())) {
				$this->addExportContent(JText::sprintf('COM_CSVI_ERROR_RETRIEVING_DATA', $csvidb->getErrorMsg()));
				$csvilog->AddStats('incorrect', $csvidb->getErrorMsg());
			}
			else {
				$this->addExportContent(JText::_('COM_CSVI_NO_DATA_FOUND'));
			}
			$this->writeOutput();
			return false;
		}

		$categories = array();
		// Group all categories together according to their level
		foreach ($cats as $key => $cat) {
			$categories[$cat->pid][$cat->cid] = $cat->category_name;
		}
		// Build something fancy to only get the fieldnames the user wants
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			if ($field->process) {
				switch ($field->field_name) {
					case 'virtuemart_category_id':
					case 'ordering':
						$userfields[] = '#__virtuemart_categories.'.$field->field_name;
						break;
					case 'file_url':
					case 'file_url_thumb':
						$userfields[] = '#__virtuemart_category_medias.virtuemart_media_id';
						break;
					case 'category_name':
					case 'category_description':
					case 'metadesc':
					case 'metakey':
					case 'slug':
					case 'category_path':
						$userfields[] = $db->qn('#__virtuemart_categories').'.'.$db->qn('virtuemart_category_id');
						break;
					default:
						$userfields[] = $db->qn($field->field_name);
						break;
				}
			}
		}

		// Build the query
		$userfields = array_unique($userfields);
		$query = $db->getQuery(true);
		$query->select(implode(",\n", $userfields));
		$query->from('#__virtuemart_categories');
		$query->leftJoin('#__virtuemart_categories_'.$template->get('language', 'general').' ON #__virtuemart_categories_'.$template->get('language', 'general').'.virtuemart_category_id = #__virtuemart_categories.virtuemart_category_id');
		$query->leftJoin('#__virtuemart_category_medias ON #__virtuemart_category_medias.virtuemart_category_id = #__virtuemart_categories.virtuemart_category_id');

		// Check if there are any selectors
		$selectors = array();

		// Filter by published state
		$publish_state = $template->get('publish_state', 'general');
		if ($publish_state != '' && ($publish_state == 1 || $publish_state == 0)) {
			$selectors[] = '#__virtuemart_categories.published = '.$publish_state;
		}

		// Check if we need to attach any selectors to the query
		if (count($selectors) > 0 ) $query->where(implode("\n AND ", $selectors));

		// Ignore fields
		$ignore = array('category_path','file_url','file_url_thumb','');

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
						$fieldreplace = $field->field_name.$field->column_header;
						// Add the replacement
						if (isset($record->$fieldname)) $fieldvalue = CsviHelper::replaceValue($field->replace, $record->$fieldname);
						else $fieldvalue = '';
						switch ($fieldname) {
							case 'category_path':
							   $fieldvalue = $helper->createCategoryPathById($record->virtuemart_category_id);
							   $fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
							   $this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header, true);
							   break;
							case 'file_url':
							case 'file_url_thumb':
								$query = $db->getQuery(true);
								$query->select($fieldname);
								$query->from('#__virtuemart_medias');
								$query->where('virtuemart_media_id = '.$record->virtuemart_media_id);
								$db->setQuery($query);
								$fieldvalue = $db->loadResult();
								$fieldvalue = CsviHelper::replaceValue($field->replace, $fieldvalue);
								$this->addExportField($field->combine, $fieldvalue, $fieldname, $field->column_header);
								break;
							case 'category_name':
							case 'category_description':
							case 'metadesc':
							case 'metakey':
							case 'slug':
							case 'customtitle':
								$query = $db->getQuery(true);
								$query->select($fieldname);
								$query->from('#__virtuemart_categories_'.$template->get('language', 'general'));
								$query->where('virtuemart_category_id = '.$record->virtuemart_category_id);
								$db->setQuery($query);
								$fieldvalue = CsviHelper::replaceValue($field->replace, $db->loadResult());
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