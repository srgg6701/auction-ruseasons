<?php
/**
 * Import model
 *
 * @package		CSVI
 * @author		Roland Dalmulder
 * @link		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version		$Id: process.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.modelform' );

/**
 * Import Model
 *
* @package CSVI
 */
class CsviModelProcess extends JModelForm {

	private $context = 'com_csvi.process';

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
	 * @since 		4.0
	 */
	public function getForm($data = array(), $loadData = true, $options = array()) {
		// Get the action
		$jinput = JFactory::getApplication()->input;
		$jform = $jinput->get('jform', array(), 'array');
		if (!isset($jform['options'])) $action = 'import';
		else $action = $jform['options']['action'];

		// Construct the super XML
		$xml = '<?xml version="1.0" encoding="utf-8"?>
						<form>';
		// Add the main XML
		$xml .= JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/models/forms/'.$action.'.xml');

		// Load additional XMLs
		if (!empty($jform) && isset($jform['options'])) {
			// Get the component name
			$component = $jform['options']['component'];
			if (!empty($options)) {
				foreach ($options as $option) {
					$readfile = false;
					// Check the component specific XML
					$filename = JPATH_COMPONENT_ADMINISTRATOR.'/models/forms/'.$component.'/'.$action.'/'.$option.'.xml';
					if (JFile::exists($filename)) $readfile = $filename;
					else {
						// Check if there is a generic XML
						$filename = JPATH_COMPONENT_ADMINISTRATOR.'/models/forms/'.$action.'/'.$option.'.xml';
						if (JFile::exists($filename)) $readfile = $filename;
					}
					// Read the file
					if ($readfile) {
						$subxml = JFile::read($readfile);
						if ($subxml) $xml .= $subxml;
					}
				}
			}
		}

		// Close the XML
		$xml .= '</form>';

		// Load the form
		$form = $this->loadForm($this->context, $xml, array('control' => 'jform', 'load_data' => $loadData));
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
	 * @since 		4.0
	 */
	protected function loadFormData() {
		$jinput = JFactory::getApplication()->input;
		$data	= $jinput->get('jform', array(), 'array');

		return $data;
	}

	/**
	 * Load the option templates
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		4.0
	 */
	public function getOptions() {
		$jinput = JFactory::getApplication()->input;
		$jform = $jinput->get('jform', array(), 'array');
		$options = array();
		if (!empty($jform) && isset($jform['options']) && isset($jform['options']['component']) && isset($jform['options']['operation'])) {
			// Get the operation the user wants to perform
			$component = $jform['options']['component'];
			$operation = $jform['options']['operation'];

			// Get the option templates needed for the operation
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('options');
			$query->from('#__csvi_template_types');
			$query->where('template_type_name = '.$db->Quote($operation));
			$query->where('component = '.$db->Quote($component));
			$db->setQuery($query);
			$result = $db->loadResult();
			$options = explode(',', $result);
		}

		return $options;
	}

	

















	/**
	* Get the list of order statussen
	 */
	public function getOrderStatus() {
		$db = JFactory::getDBO();
		$q = "SELECT order_status_code, order_status_name
			FROM #__virtuemart_orderstates
			ORDER BY ordering";
		$db->setQuery($q);
		return $db->loadObjectList();
	}





	/**
	* Get the list of order products
	*/
	public function getOrderCurrency() {
		$db = JFactory::getDBO();
		$q = "SELECT order_currency, currency_name
			FROM #__vm_orders o, #__vm_currency c
			WHERE o.order_currency = c.currency_code
			GROUP BY currency_name
			ORDER BY currency_name;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}

	/**
	* Get the list of exchange rate currencies
	*/
	public function getExchangeRateCurrency() {
		$db = JFactory::getDBO();
		$q = "SELECT #__csvi_currency.currency_code AS currency_code,
			IF (#__vm_currency.currency_name IS NULL, #__csvi_currency.currency_code, #__vm_currency.currency_name) AS currency_name
			FROM #__csvi_currency
			LEFT JOIN #__vm_currency
			on #__vm_currency.currency_code = #__csvi_currency.currency_code;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}



	/**
	* Check if there are any templates with fields
	 */
	public function getCountTemplateFields() {
		$db = JFactory::getDBO();
		$q = "SELECT field_template_id, COUNT(field_template_id) AS total
			FROM #__csvi_template_fields
			WHERE field_template_id in (
				SELECT template_id
				FROM #__csvi_templates
				WHERE template_type
				LIKE '%export')
			GROUP BY field_template_id";
		$db->setQuery($q);
		$nrfields = $db->loadResultArray();
		if ($db->getErrorNum() > 0) {
			JError::raiseWarning(0, $db->getErrorMsg());
			return false;
		}
		else {
			/* Check if there are any templates with more than 0 fields */
			foreach ($nrfields as $key => $nr) {
				if ($nr > 0) return true;
			}
		}
	}





	/**
	* Get a list of possible VM Item IDs
	*/
	public function getVmItemids() {
	 	$db = JFactory::getDBO();
	 	$q = "SELECT id AS value, name AS text
	 		FROM #__menu
	 		WHERE link LIKE '%com_virtuemart%'";
	 	$db->setQuery($q);
	 	return $db->loadObjectList();
	}










	/**
	 * Get the list of selected order item products
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Do not use INFORMATION_SCHEMA
	 * @see
	 * @access 		public
	 * @param
	 * @return		array	list of order item product objects
	 * @since 		3.0
	 */
	public function getJoomFishLanguages() {
		$db = JFactory::getDBO();
		$conf = JFactory::getConfig();
		$q = "SELECT table_name FROM information_schema.tables WHERE table_schema = ".$db->Quote($conf->getValue('config.db'))." AND table_name = ".$db->Quote($conf->getValue('config.dbprefix').'languages');
		$db->setQuery($q);
		$total = $db->loadResult();
		if (!empty($total)) {
			$q = "SELECT ".$db->quoteName('name')." AS ".$db->quoteName('text').",
					".$db->quoteName('id')." AS ".$db->quoteName('value')."
				FROM #__languages
				ORDER BY name";
			$db->setQuery($q);
			return $db->loadObjectList();
		}
		else return array();
	}

	/**
	 * Get a list of XML sites
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$type	the type of files to find (XML or HTML)
	 * @return 		array	list of XML sites
	 * @since 		3.0
	 */
	public function getExportSites($type) {
		jimport('joomla.filesystem.folder');
		$files = array();
		$path = JPATH_COMPONENT_ADMINISTRATOR.'/helpers/file/export/'.$type;
		if (JFolder::exists($path)) {
			$files = JFolder::files($path, '.php');
			if (!empty($files)) {
				foreach ($files as $fkey => $file) {
					$files[$fkey] = basename($file, '.php');
				}
			}
			else $files = array();
		}

		return $files;
	}

	/**
	 * Get a dropdown list of replacements 
	 * 
	 * @copyright 
	 * @author 		RolandD
	 * @todo 
	 * @see 
	 * @access 		public
	 * @param 
	 * @return		array of replacements 
	 * @since 		4.0
	 */
	public function getReplacements() {
    $replacements = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id AS value, name AS text');
		$query->from('#__csvi_replacements');
		$db->setQuery($query);
		$replacements = $db->loadObjectList();
		
		// Add a make choice option
		$option = new StdClass();
		$option->value = '';
		$option->text = JText::_('COM_CSVI_NOT_USED');
		
		if (!empty($replacements))	array_unshift($replacements, $option);
		else $replacements[] = $option;
		
		return $replacements;
	}

}
?>