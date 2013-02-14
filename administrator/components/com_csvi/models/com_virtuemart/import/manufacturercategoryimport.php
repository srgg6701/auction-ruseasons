<?php
/**
 * Manufacturer category import
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: manufacturercategoryimport.php 2307 2013-02-03 07:23:02Z RolandD $
 */

defined( '_JEXEC' ) or die;

/**
 * Processor for manufacturer categories
 *
 * Main processor for handling manufacturer categories
 *
 * @todo	Remove images
 * @todo	check update null fields
 */
class CsviModelManufacturercategoryimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the csvi_templates table */
	private $_manufacturer_categories = null;
	private $_manufacturer_categories_lang = null;

	// Private variables
	private $_tablesexist = true;

	// Public variables
	public $mf_category_delete = 'N';
	public $virtuemart_manufacturerscategories_id = null;

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
		// Get the logger
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);

		// Only continue if all tables exist
		if ($this->_tablesexist) {
			// Load the data
			$this->loadData();

			// Process data
			foreach ($this->csvi_data as $name => $value) {
				// Check if the field needs extra treatment
				switch ($name) {
					case 'published':
						switch ($value) {
							case 'n':
							case 'N':
							case '0':
								$value = 0;
								break;
							default:
								$value = 1;
								break;
						}
						$this->published = $value;
						break;
					case 'mf_category_delete':
						$this->$name = $this->mf_category_delete = strtoupper($this->_datafield);
						break;
					default:
						$this->$name = $value;
						break;
				}
			}

			// If we have no manufacturer category name we cannot continue
			if (empty($this->mf_category_name)) {
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_MANUFACTURERCATEGORY_PATH_SET'));
				return false;
			}

			return true;
		}
		else {
			$template = $jinput->get('template', null, null);
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_LANG_TABLE_NOT_EXIST', $template->get('language', 'general')));
			return false;
		}
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
		$template = $jinput->get('template', null, null);

		// Check if we have a manufacturer category ID, if not get it
		if (!isset($this->virtuemart_manufacturerscategories_id)) $this->_getManufacturerCategoryId();

		// Set the modified date as we are modifying the product
		if (!isset($this->modified_on)) {
			$this->_manufacturer_categories->modified_on = $this->date->toMySQL();
			$this->_manufacturer_categories->modified_by = $this->user->id;
		}

		// Add a creating date if there is no product_id
		if (empty($this->virtuemart_manufacturerscategories_id)) {
			$this->_manufacturer_categories->created_on = $this->date->toMySQL();
			$this->_manufacturer_categories->created_by = $this->user->id;
		}

		// Bind the data
		$this->_manufacturer_categories->bind($this);

		// User wants to delete the manufacturer
		if ($this->virtuemart_manufacturerscategories_id && $this->mf_category_delete == "Y") {
			if ($this->_manufacturer_categories->delete($this->virtuemart_manufacturerscategories_id)) {
				$csvilog->addDebug(JText::_('COM_CSVI_DELETE_MANUFACTURER_CATEGORY'), true);
				$csvilog->AddStats('deleted', JText::_('COM_CSVI_MANUFACTURER_CAT_DELETED'));
			}
			else $csvilog->AddStats('error', JText::sprintf('COM_CSVI_MANUFACTURER_CAT_NOT_DELETED', $this->_manufacturer_categories->getError()));
		}
		else if (!$this->virtuemart_manufacturerscategories_id && $template->get('ignore_non_exist', 'general')) {
			// Do nothing for new categories when user chooses to ignore new categories
			if (isset($this->mf_category_name)) $value = $this->mf_category_name;
			else $value = '';
			$csvilog->AddStats('skipped', JText::sprintf('COM_CSVI_IGNORE_NON_EXIST_DATA', $value));
		}
		// User wants to add or update the manufacturer category
		else {
			if ($this->_manufacturer_categories->store()) {
				if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_MANUFACTURER_CATEGORY'));
				else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_MANUFACTURER_CATEGORY'));
			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MANUFACTURER_CATEGORY_NOT_ADDED', $this->_manufacturer_categories->getError()));

			$this->virtuemart_manufacturercategories_id = $this->_manufacturer_categories->virtuemart_manufacturercategories_id;

			// Store the language fields
			$this->_manufacturer_categories_lang->load($this->virtuemart_manufacturercategories_id);
			$this->_manufacturer_categories_lang->bind($this);

			// Check and store the language data
			if ($this->_manufacturer_categories_lang->check()) {
				if ($this->_manufacturer_categories_lang->store()) {
					if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_MANUFACTURERCATEGORY_LANG'));
					else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_MANUFACTURERCATEGORY_LANG'));
				}
				else {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MANUFACTURERCATEGORY_LANG_NOT_ADDED', $this->_manufacturer_categories_lang->getError()));
					return false;
				}
			}
			else {
				$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MANUFACTURERCATEGORY_LANG_NOT_ADDED', $this->_manufacturer_categories_lang->getError()));
				return false;
			}

			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_MANUFACTURER_CATEGORY_QUERY'), true);
		}

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the manufacturer category related tables
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
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$this->_manufacturer_categories = $this->getTable('manufacturer_categories');

		// Check if the language tables exist
		$db = JFactory::getDbo();
		$tables = $db->getTableList();
		if (!in_array($db->getPrefix().'virtuemart_manufacturercategories_'.$template->get('language', 'general'), $tables)) {
			$this->_tablesexist = false;
		}
		else {
			$this->_tablesexist = true;
			$this->_manufacturer_categories_lang = $this->getTable('manufacturer_categories_lang');
		}
	}

	/**
	 * Cleaning the manufacturer related related tables
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
		$this->_manufacturer_categories->reset();
		$this->_manufacturer_categories_lang->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}

	/**
	 * Get the manufacturer category ID
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		mixed	integer when category ID found | false when not found
	 * @since 		3.0
	 */
	private function _getManufacturerCategoryId() {
		$this->_manufacturer_categories_lang->set('mf_category_name', $this->mf_category_name);
		if ($this->_manufacturer_categories_lang->check(false)) {
			$this->virtuemart_manufacturercategories_id = $this->_manufacturer_categories_lang->virtuemart_manufacturercategories_id;
			return true;
		}
		else return false;
	}
}
?>
