<?php
/**
 * Manufacturer import
 *
 * @package		CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: manufacturerimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined('_JEXEC') or die;

/**
 * Processor for manufacturer import
 *
* @package CSVI
 */
class CsviModelManufacturerimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_manufacturer table */
	private $_manufacturers = null;
	/** @var object contains the vm_manufacturer table */
	private $_manufacturers_lang = null;
	/** @var object contains the vm_manufacturer table */
	private $_manufacturer_categories_lang = null;

	// Public variables
	/** @var integer contains the manufacturer ID */
	public $virtuemart_manufacturer_id = null;
	/** @var integer contains the category ID for a manufacturer */
	public $virtuemart_manufacturercategories_id = null;
	/** @var string sets if the manufacturer should be deleted */
	public $manufacturer_delete = 'N';

	// Private variables
	private $_tablesexist = true;

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
					case 'mf_category_name':
						$this->_manufacturer_categories_lang->mf_category_name = $value;
						break;
					default:
						$this->$name = $value;
						break;
				}
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

		// Check if we need to get manufacturer category ID
		if (empty($this->virtuemart_manufacturercategories_id) && isset($this->_manufacturer_categories_lang->mf_category_name)) {
			if ($this->_manufacturer_categories_lang->check(false)) {
				$this->virtuemart_manufacturercategories_id = $this->_manufacturer_categories_lang->virtuemart_manufacturercategories_id;
			}
		}

		// Check for the manufacturer ID
		if (!isset($this->virtuemart_manufacturer_id)) $this->_getManufacturerId();

		// Bind the data
		$this->_manufacturers->bind($this);

		// Set the modified date as we are modifying the product
		if (!isset($this->modified_on)) {
			$this->_manufacturers->modified_on = $this->date->toMySQL();
			$this->_manufacturers->modified_by = $this->user->id;
		}

		// Add a creating date if there is no product_id
		if (empty($this->virtuemart_manufacturer_id)) {
			$this->_manufacturers->created_on = $this->date->toMySQL();
			$this->_manufacturers->created_by = $this->user->id;
		}

		// Check if we need to delete the manufacturer
		if ($this->manufacturer_delete == 'Y') {
			$this->_deleteManufacturer();
		}
		else {
			// Store the data
			if ($this->_manufacturers->store()) {
				if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_MANUFACTURER'));
				else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_MANUFACTURER'));

				$this->virtuemart_manufacturer_id = $this->_manufacturers->get('virtuemart_manufacturer_id');
			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MANUFACTURER_NOT_ADDED', $this->_manufacturers->getError()));

			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_MANUFACTURER_QUERY'), true);

			// Store the language fields
			$this->_manufacturers_lang->bind($this);
			$this->_manufacturers_lang->virtuemart_manufacturer_id = $this->virtuemart_manufacturer_id;

			if ($this->_manufacturers_lang->check()) {
				if ($this->_manufacturers_lang->store()) {
					if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_PRODUCT_LANG'));
					else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_PRODUCT_LANG'));
				}
				else {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_PRODUCT_LANG_NOT_ADDED', $this->_manufacturers_lang->getError()));
					return false;
				}
			}
			else {
				$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_PRODUCT_LANG_NOT_ADDED', $this->_manufacturers_lang->getError()));
				return false;
			}

			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_MANUFACTURER_LANG_QUERY'), true);
		}

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the manufacturer related tables
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
		
		$this->_manufacturers = $this->getTable('manufacturers');
		
		// Check if the language tables exist
		$db = JFactory::getDbo();
		$tables = $db->getTableList();
		if (!in_array($db->getPrefix().'virtuemart_manufacturers_'.$template->get('language', 'general'), $tables)) {
			$this->_tablesexist = false;
		}
		else if (!in_array($db->getPrefix().'virtuemart_manufacturercategories_'.$template->get('language', 'general'), $tables)) {
			$this->_tablesexist = false;
		}
		else {
			$this->_tablesexist = true;
			$this->_manufacturers_lang = $this->getTable('manufacturers_lang');
			$this->_manufacturer_categories_lang = $this->getTable('manufacturer_categories_lang');
		}
	}

	/**
	 * Cleaning the manufacturer related tables
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
		$this->_manufacturers->reset();
		$this->_manufacturers_lang->reset();
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
	 * Delete a manufacturer and its references
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
	private function _deleteManufacturer() {
		if (!empty($this->virtuemart_manufacturer_id)) {
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			$db = JFactory::getDbo();

			// Delete product manufacturer xref
			$query = $db->getQuery(true);
			$query->delete('#__product_manufacturers');
			$query->where('virtuemart_manufacturer_id = '.$this->virtuemart_manufacturer_id);
			$db->setQuery();
			if ($db->query()) {
				$csvilog->addStats('deleted', JText::_('COM_CSVI_MANUFACTURER_XREF_DELETED'));
			}
			else {
				$csvilog->addStats('incorrect', JText::sprintf('COM_CSVI_MANUFACTURER_XREF_NOT_DELETED', $db->getErrorMsg()));
			}

			// Delete translations
			jimport('joomla.language.helper');
			$languages = array_keys(JLanguageHelper::getLanguages('lang_code'));
			foreach ($languages as $language){
				$query = $db->getQuery(true);
				$query->delete('#__virtuemart_manufacturers_'.strtolower(str_replace('-', '_', $language)));
				$query->where('virtuemart_manufacturer_id = '.$this->virtuemart_manufacturer_id);
				$db->setQuery($query);
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_MANUFACTURER_LANG_XREF'), true);
				$db->query();
			}

			// Delete manufacturer
			if ($this->_manufacturers->delete($this->virtuemart_manufacturer_id)) {
				$csvilog->AddStats('deleted', JText::_('COM_CSVI_DELETE_MANUFACTURER'));
			}
			else {
				$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MANUFACTURER_NOT_DELETED', $this->_manufacturers->getError()));
			}
			
			// Delete media
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_manufacturer_medias');
			$query->where('virtuemart_manufacturer_id = '.$this->virtuemart_manufacturer_id);
			$db->setQuery($query);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_MEDIA_XREF'), true);
			$db->query();
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_MANUFACTURER_NOT_DELETED_NO_ID'));
		}
	}

	/**
	 * Get the manufacturer ID
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
	private function _getManufacturerId() {
		$this->_manufacturers_lang->set('mf_name', $this->mf_name);
		if ($this->_manufacturers_lang->check(false)) {
			$this->virtuemart_manufacturer_id = $this->_manufacturers_lang->virtuemart_manufacturer_id;
			return true;
		}
		else return false;
	}
}
?>
