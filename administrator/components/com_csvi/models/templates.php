<?php
/**
 * Templates model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: templates.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );

/**
 * Templates Model
 *
* @package CSVI
 */
class CsviModelTemplates extends JModel {

	/**
	 * Get the saved templates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array	list of template objects
	 * @since 		3.0
	 */
	public function getTemplates() {
		$db = JFactory::getDBO();
		$q = "SELECT name AS text, id AS value
			FROM #__csvi_template_settings
			ORDER BY name";
		$db->setQuery($q);
		$templates = $db->loadObjectList();
		if (!is_array($templates)) $templates = array();
		$new = array();
		$new[] = JHtml::_('select.option', '', JText::_('COM_CSVI_SAVE_AS_NEW_FOR_NEW_TEMPLATE'));
		$templates = array_merge($new, $templates);
		return $templates;
	}

	/**
	 * Save export settings
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		array	$data	the data to be stored
	 * @return 		bool	true on success | false on failure
	 * @since 		3.0
	 */
	public function save($data) {
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$table = $this->getTable('csvi_template_settings');
		$bind = array();
		$id = $jinput->get('template_id', 0, 'int');
		if ($id > 0) $table->load($id);
		else $bind['name'] = $jinput->get('template_name', 'Template '.time(), 'string');
		$bind['settings'] = json_encode($data);
		$table->bind($bind);
		if ($table->store()) {
			$app->enqueueMessage(JText::sprintf('COM_CSVI_PROCESS_SETTINGS_SAVED', $table->name));
		}
		else {
			$app->enqueueMessage(JText::sprintf('COM_CSVI_PROCESS_SETTINGS_NOT_SAVED', $table->getError()), 'error');
		}
		return $table->id;
	}

	/**
	 * Remove a settings template
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		array	$data	the data to be stored
	 * @return 		bool	true on success | false on failure
	 * @since 		3.0
	 */
	public function remove() {
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$table = $this->getTable('csvi_template_settings');
		$table->load($jinput->get('template_id', null, 'int'));
		if ($table->delete()) {
			$app->enqueueMessage(JText::sprintf('COM_CSVI_PROCESS_SETTINGS_DELETED', $table->name));
		}
		else {
			$app->enqueueMessage(JText::sprintf('COM_CSVI_PROCESS_SETTINGS_NOT_DELETED', $table->getError()), 'error');
		}
	}

		/**
	* Get the template details
	*
	* Retrieves the template details from the csvi_templates table. If the
	* template id is 0, it will automatically retrieve the template details
	* for the template with the lowest ID in the database
	*
	* @see self::GetFirstTemplateId();
	* @param $templateid integer Template ID to retrieve
	*/
	public function _getTemplate() {
		$row = $this->getTable($this->_tablename);
		if ($this->_id == 0) {
			$this_id = $this->GetFirstTemplateId();
		}
		$row->load($this->_id);

		// Fix the price format
		$row->export_price_formats = self::getNumberFormat($row->export_price_format);
		return $row;
	}

	/**
	 * Load the template types based on type
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$type	The type of template to filter on
	 * @return 		array	list of template types
	 * @since 		3.0
	 */
	function getTemplateTypes($type=false, $component=false) {
		$db = JFactory::getDBO();
		$q = "SELECT CONCAT('COM_CSVI_', UPPER(template_type_name)) AS name, template_type_name AS value
			FROM #__csvi_template_types ";
		// Check any selectors
		$selectors = array();
		if ($type) $selectors[] = "template_type = ".$db->Quote($type);
		if ($component) $selectors[] = "component = ".$db->Quote($component);
		if (!empty($selectors)) $q .= "WHERE ".implode(' AND ', $selectors);
		// Order by name
		$q .= " ORDER BY template_type_name";
		$db->setQuery($q);
		$types = $db->loadObjectList();

		// Translate the strings
		foreach ($types as $key => $type) {
			$type->value = JText::_($type->value);
			$types[$key] = $type;
		}
		return $types;
	}
}
?>