<?php
/**
 * Waitinglist import
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: customfieldimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Main processor for importing waitinglists
 *
* @package CSVI
 */
class CsviModelCustomfieldimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_waiting_list table */
	private $_customs = null;

	// Public variables
	/** @var integer contains the waiting list ID for an entry */
	public $virtuemart_custom_id = null;
	public $virtuemart_vendor_id = null;
	public $plugin_name = null;


	// Private variables

	/**
	 * Constructor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.4
	 */
	public function __construct() {
		parent::__construct();
		// Load the tables that will contain the data
		$this->_loadTables();
		$this->loadSettings();
		// Set some initial values
		$this->date = JFactory::getDate();
		$this->user = JFactory::getUser();
    }

	/**
	 * Here starts the processing
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getStart() {
		$jinput = JFactory::getApplication()->input;

		// Load the data
		$this->loadData();

		// Load the helper
		$this->helper = new Com_VirtueMart();

		// Get the logger
		$csvilog = $jinput->get('csvilog', null, null);

		$this->virtuemart_vendor_id = $this->helper->getVendorId();

		// Process data
		foreach ($this->csvi_data as $name => $value) {
			// Check if the field needs extra treatment
			switch ($name) {
				case 'created_on':
				case 'modified_on':
				case 'locked_on':
					$this->$name = $this->convertDate($value);
					break;
				default:
					$this->$name = $value;
					break;
			}
		}

		// All is good
		return true;
	}

	/**
	 * Process each record and store it in the database
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getProcessRecord() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);

		// Get the plugin ID
		if (empty($this->custom_jplugin_id) && !empty($this->custom_element)) {
			$this->custom_jplugin_id = $this->_getPluginId();
			if (empty($this->custom_jplugin_id)) {
				$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_NO_PLUGIN_FOUND', $this->plugin_name));
				return false;
			}
			else {
				// Make sure the custom_value is the same as custom_element when dealing with a plugin
				// This is needed as otherwise the plugin is not called
				$this->custom_value = $this->custom_element;
			}
		}
		
		// Bind the data
		$this->_customs->bind($this);

		// Check the data
		$this->_customs->check();

		// Set the modified date as we are modifying the product
		if (!isset($this->modified_on)) {
			$this->_customs->modified_on = $this->date->toMySQL();
			$this->_customs->modified_by = $this->user->id;
		}

		if (empty($this->_customs->virtuemart_custom_id)) {
			$this->_customs->custom_params = '';
			$this->_customs->custom_field_desc = '';
			$this->_customs->created_on = $this->date->toMySQL();
			$this->_customs->created_by = $this->user->id;
		}

		// Store the data
		if ($this->_customs->store()) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_CUSTOMFIELD'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_CUSTOMFIELD'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CUSTOMFIELD_NOT_ADDED', $this->_customs->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_CUSTOMFIELD_QUERY'), true);
		
		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the waiting list related tables
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.01
	 */
	private function _loadTables() {
		$this->_customs = $this->getTable('customs');
	}

	/**
	 * Cleaning the waiting list related tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.1
	 */
	protected function cleanTables() {
		$this->_customs->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}
	
	/**
	 * Get the user ID
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		mixed int when user ID found | false when not found
	 * @since 		3.1
	 */
	private function _getPluginId() {
		if (isset($this->plugin_name)) {
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('extension_id');
			$query->from('#__extensions');
			$query->where('name = '.$db->Quote($this->plugin_name));
			$query->where('type = '.$db->Quote('plugin'));
			$db->setQuery($query);
			$result = $db->loadResult();
			$csvilog->addDebug(JText::_('COM_CSVI_FIND_USER_ID'), true);
			if ($result) return $result;
			else return false;
		}
		else return false;
	}
}
?>