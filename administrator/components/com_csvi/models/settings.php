<?php
/**
 * Settings model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: settings.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.modeladmin');

/**
 * Settings Model
 *
 * @package CSVI
 */
class CsviModelSettings extends JModelAdmin {

	private $context = 'com_csvi.settings';

	/** @var array The parameter object */
	private $_params = false;

	/**
	 * Method to get the record form located in models/forms
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		array $data Data for the form.
	 * @param 		boolean $loadData True if the form is to load its own data (default case), false if not.
	 * @return 		mixed
	 * @since 		1.0
	 */
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm($this->context, 'settings', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) return false;

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see 		getForm()
	 * @access 		protected
	 * @param
	 * @return 		mixed The data for the form
	 * @since 		1.0
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_csvi.settings.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Load the attributes
	 * @see
	 * @access 		public
	 * @param 		integer The id of the primary key
	 * @return 		mixed Object on success | false on failure
	 * @since 		1.0
	 */
	public function getItem($pk = null) {
		if (!$this->_params) {
			$row  = $this->getTable('settings');
			$row->load(1);
			$registry = new JRegistry();
			$registry->loadString($row->params);
			$this->_params = $registry->toArray();
		}

		return $this->_params;
	}


	/**
	 * Store the settings
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see			CsviModelAvailablefields::prepareAvailableFields
	 * @access 		public
	 * @param
	 * @return 		bool	true on success | false on failure
	 * @since 		4.0
	 */
	public function save($data) {
		$row  = $this->getTable('settings');
		$registry = new JRegistry();
		$registry->loadArray($data);

		// Set the values
		$row->id = 1;
		$row->params = $registry->toString();
		if ($row->store()) {
			$this->_params = $registry;

			// Add the custom tables to the template tables table
			$db = JFactory::getDbo();
			$tables = $registry->get('tables.tablelist');
			$q = "INSERT IGNORE INTO ".$db->quoteName('#__csvi_template_tables')." (".$db->quoteName('template_type_name').", ".$db->quoteName('template_table').", ".$db->quoteName('component').") VALUES ";
			$fields = array();
			foreach ($tables as $table) {
				$fields[] = "(".$db->quote('customimport').", ".$db->quote($table).", ".$db->quote('com_csvi').")";
				$fields[] = "(".$db->quote('customexport').", ".$db->quote($table).", ".$db->quote('com_csvi').")";
			}
			$q .= implode(',', $fields);
			$db->setQuery($q);
			$db->query();
			return true;
		}
		else {
			$this->setError($row->getError());
			return false;
		}
	}

	/**
	 * Get a list of custom tables for import/export
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array	of tables
	 * @since 		3.0
	 */
	public function getTableList() {
		$db = JFactory::getDbo();
		$tables = $db->getTableList();
		$prefix = $db->getPrefix();

		// Remove the table prefix
		foreach ($tables as $tkey => $table) {
			if (stristr($table, $prefix)) $tables[$tkey] = str_replace($prefix, '', $table);
			else unset($tables[$tkey]);
		}
		return $tables;
	}

	/**
	 * Reset the settings
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool	true if settings reset | false if settings not reset
	 * @since 		3.1.1
	 */
	public function getResetSettings() {
		$row  = $this->getTable('settings');
		$row->id = 1;
		$row->params = '';
		return $row->store();
	}
}
?>