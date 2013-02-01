<?php
/**
 * User fields import
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: shopperfieldimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for user fields
 *
* @package CSVI
 */
class CsviModelShopperfieldimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_shipping_rate table */
	private $_userfields = null;

	// Public variables
	/** @var int the database ID of the user field */
	public $virtuemart_userfield_id = null;
	public $shopperfield_delete = 'N';

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
				case 'name':
					$this->$name = strtolower(JFilterInput::clean($value, 'alnum'));
					break;
				default:
					$this->$name = $value;
					break;
			}
		}

		// Check if we have a field ID
		if (empty($this->virtuemart_userfield_id)) $this->_getFieldId();

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
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);

		// Check if a field needs to be deleted
		if ($this->shopperfield_delete == 'Y') {
			$this->_deleteShopperField();
		}
		else {
			// Bind the data
			$this->_userfields->bind($this);

			// Check for modified data
			if (!isset($this->modified_on)) {
				$this->_userfields->modified_on = $this->date->toMySQL();
				$this->_userfields->modified_by = $this->user->id;
			}

			// Add a creating date if there is no virtuemart_userfield_id
			if (empty($this->virtuemart_userfield_id)) {
				$this->_userfields->created_on = $this->date->toMySQL();
				$this->_userfields->created_by = $this->user->id;
			}

			// Add the name field as Joomla doesn't bind it
			$this->_userfields->name = $this->name;

			// Store the data
			if ($this->_userfields->store()) {
				if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_SHOPPERFIELD'));
				else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_SHOPPERFIELD'));

				// Create a field in the userinfos table if needed
				if ($this->type != 'delimiter') {
					switch($this->type) {
						case 'date':
							$fieldtype = 'DATE';
							break;
						case 'editorta':
						case 'textarea':
						case 'multiselect':
						case 'multicheckbox':
							$fieldtype = 'MEDIUMTEXT';
							break;
						case 'checkbox':
							$fieldtype = 'TINYINT';
							break;
						default:
							$fieldtype = 'VARCHAR(255)';
							break;
					}
					$query = "ALTER TABLE ".$db->quoteName('#__virtuemart_userinfos')." ADD COLUMN ".$db->quoteName($this->_userfields->name)." ".$fieldtype;
					$db->setQuery($query);
					$db->query();

					// Store the debug message
					$csvilog->addDebug(JText::_('COM_CSVI_USERINFO_TABLE_QUERY'), true);
				}
			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_SHOPPERFIELD_NOT_ADDED', $this->_userfields->getError()));

			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_SHOPPERFIELD_QUERY'), true);
		}

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the user field related tables
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _loadTables() {
		$this->_userfields = $this->getTable('userfields');
	}

	/**
	 * Cleaning the user field related tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.0
	 */
	 protected function cleanTables() {
		$this->_userfields->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}

	/**
	 * Load the field ID for a fieldname
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _getFieldId() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('virtuemart_userfield_id');
		$query->from('#__virtuemart_userfields');
		$query->where($db->quoteName('name').' = '.$db->Quote($this->name));
		$db->setQuery($query);
		$this->virtuemart_userfield_id = $db->loadResult();
		$csvilog->addDebug(JText::_('COM_CSVI_GET_FIELD_ID'), true);
	}

	/**
	 * Delete a shopper field
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _deleteShopperField() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Delete the shopperfield
		if ($this->_userfields->delete($this->virtuemart_userfield_id)) {
			$db = JFactory::getDbo();
			// Delete the userinfos field
			$query = "ALTER TABLE ".$db->quoteName('#__virtuemart_userinfos')." DROP COLUMN ".$db->quoteName($this->name);
			$db->setQuery($query);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_USERINFOS_FIELD'), true);
			$db->query();

			$csvilog->AddStats('deleted', JText::sprintf('COM_CSVIVIRTUEMART_SHOPPERFIELD_DELETED', $this->name));
		}
	}
}
?>
