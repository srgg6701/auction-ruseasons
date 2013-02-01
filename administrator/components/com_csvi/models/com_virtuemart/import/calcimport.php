<?php
/**
 * Calculation rule import
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: calcimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Main processor for importing calculation rules
 *
* @package CSVI
 */
class CsviModelCalcimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_waiting_list table */
	private $_calcs = null;

	// Public variables
	/** @var integer contains the waiting list ID for an entry */
	public $virtuemart_calc_id = null;
	public $virtuemart_vendor_id = null;
	public $currency_code_3 = null;

	// Private variables
	private $_categorymodel = null;

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
				case 'currency_code_3':
					$this->$name = strtoupper($value);
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
		$db = JFactory::getDbo();

		// Bind the data
		$this->_calcs->bind($this);

		// Check the currency
		if (isset($this->currency_code_3)) {
			$this->_calcs->calc_currency = $this->helper->getCurrencyId($this->currency_code_3, $this->virtuemart_vendor_id);
		}

		// Check the data
		$this->_calcs->check();

		// Set the modified date as we are modifying the product
		if (!isset($this->modified_on)) {
			$this->_calcs->modified_on = $this->date->toMySQL();
			$this->_calcs->modified_by = $this->user->id;
		}

		if (empty($this->_calcs->virtuemart_calc_id)) {
			$this->_calcs->calc_shopper_published = (isset($this->calc_shopper_published)) ? $this->calc_shopper_published : 1;
			$this->_calcs->calc_vendor_published = (isset($this->calc_vendor_published)) ? $this->calc_vendor_published : 1;
			$this->_calcs->calc_params = (isset($this->calc_params)) ? $this->calc_params : '';
			$this->_calcs->created_on = $this->date->toMySQL();
			$this->_calcs->created_by = $this->user->id;
		}

		// Store the data
		if ($this->_calcs->store()) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_CALC'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_CALC'));

			// Process any categories
			if (isset($this->category_path)) {
				// Remove any existing categories for the calc rule
				$query = $db->getQuery(true);
				$query->delete('#__virtuemart_calc_categories');
				$query->where('virtuemart_calc_id = '.$this->_calcs->virtuemart_calc_id);
				$db->setQuery($query);
				$db->query();

				// Add any new categories
				if (is_null($this->_categorymodel)) $this->_categorymodel = new CsviModelCategory();
				$this->_categorymodel->getStart();
				$categories = explode('|', $this->category_path);
				$query = $db->getQuery(true);
				$query->insert('#__virtuemart_calc_categories');
				foreach ($categories as $category) {
					$catid = $this->_categorymodel->getCategoryIdFromPath($category, $this->virtuemart_vendor_id);
					$query->values('null, '.$this->_calcs->virtuemart_calc_id.', '.$catid['category_id']);
				}
				$db->setQuery($query);
				$db->query();
				// Store the debug message
				$csvilog->addDebug(JText::_('COM_CSVI_CALC_CATEGORY_QUERY'), true);
			}

			// Process any countries
			if (isset($this->country_name) || isset($this->country_2_code) || isset($this->country_3_code)) {
				// Remove any existing countries for the calc rule
				$query = $db->getQuery(true);
				$query->delete('#__virtuemart_calc_countries');
				$query->where('virtuemart_calc_id = '.$this->_calcs->virtuemart_calc_id);
				$db->setQuery($query);
				$db->query();

				// Add any new countries
				if (isset($this->country_name)) $countries = explode('|', $this->country_name);
				else if (isset($this->country_2_code)) $countries = explode('|', $this->country_2_code);
				else if (isset($this->country_3_code)) $countries = explode('|', $this->country_3_code);
				$query = $db->getQuery(true);
				$query->insert('#__virtuemart_calc_countries');
				foreach ($countries as $country) {
					if (isset($this->country_name)) $cid = $this->helper->getCountryId($country);
					else if (isset($this->country_2_code)) $cid = $this->helper->getCountryId(null, $country);
					else if (isset($this->country_3_code)) $cid = $this->helper->getCountryId(null, null, $country);
					$query->values('null, '.$this->_calcs->virtuemart_calc_id.', '.$cid);
				}
				$db->setQuery($query);
				$db->query();
				// Store the debug message
				$csvilog->addDebug(JText::_('COM_CSVI_CALC_COUNTRY_QUERY'), true);
			}

			// Process any shoppergroups
			if (isset($this->shopper_group_name)) {
				// Remove any existing countries for the calc rule
				$query = $db->getQuery(true);
				$query->delete('#__virtuemart_calc_shoppergroups');
				$query->where('virtuemart_calc_id = '.$this->_calcs->virtuemart_calc_id);
				$db->setQuery($query);
				$db->query();

				// Add any new shoppergroups
				$shoppergroups = explode('|', $this->shopper_group_name);
				$query = $db->getQuery(true);
				$query->insert('#__virtuemart_calc_shoppergroups');
				foreach ($shoppergroups as $shoppergroup) {
					$sid = $this->helper->getShopperGroupId($shoppergroup);
					$query->values('null, '.$this->_calcs->virtuemart_calc_id.', '.$sid);
				}
				$db->setQuery($query);
				$db->query();
				// Store the debug message
				$csvilog->addDebug(JText::_('COM_CSVI_CALC_SHOPPERGROUP_QUERY'), true);
			}

			// Process any states
			if (isset($this->country_name) || isset($this->country_2_code) || isset($this->country_3_code)) {
				// Remove any existing countries for the calc rule
				$query = $db->getQuery(true);
				$query->delete('#__virtuemart_calc_states');
				$query->where('virtuemart_calc_id = '.$this->_calcs->virtuemart_calc_id);
				$db->setQuery($query);
				$db->query();

				// Add any new countries
				if (isset($this->state_name)) $countries = explode('|', $this->state_name);
				else if (isset($this->state_2_code)) $countries = explode('|', $this->state_2_code);
				else if (isset($this->state_3_code)) $countries = explode('|', $this->state_3_code);
				$query = $db->getQuery(true);
				$query->insert('#__virtuemart_calc_states');
				foreach ($countries as $state) {
					if (isset($this->state_name)) $sid = $this->helper->getstateId($state);
					else if (isset($this->state_2_code)) $sid = $this->helper->getstateId(null, $state);
					else if (isset($this->state_3_code)) $sid = $this->helper->getstateId(null, null, $state);
					$query->values('null, '.$this->_calcs->virtuemart_calc_id.', '.$sid);
				}
				$db->setQuery($query);
				$db->query();
				// Store the debug message
				$csvilog->addDebug(JText::_('COM_CSVI_CALC_STATE_QUERY'), true);
			}
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_CALC_NOT_ADDED', $this->_calcs->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_CALC_QUERY'), true);

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
		$this->_calcs = $this->getTable('calcs');
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
		$this->_calcs->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}
}
?>