<?php
/**
 * Coupons import
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: couponimport.php 1764 2012-01-04 16:18:31Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for coupons
 *
 * Main processor for importing coupons
 *
 * @package CSVI
 * @todo 	Check vendor ID
 */
class CsviModelCouponimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_coupons table */
	private $_coupons = null;

	// Public variables
	/** @var integer contains the coupon ID */
	public $akeebasubs_coupon_id = null;
	public $user = null;
	public $username = null;

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
		$this->csviuser = JFactory::getUser();
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
		// Load the data
		$this->loadData();
		
		// Load the helper
		$this->helper = new Com_Akeebasubs();

		// Get the logger
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);

		// Process data
		foreach ($this->csvi_data as $name => $value) {
			// Check if the field needs extra treatment
			switch ($name) {
				case 'enabled':
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
				case 'value':
					$this->$name = $this->cleanPrice($value);
					break;
				case 'publish_up':
				case 'publish_down':
					$this->$name = $this->convertDate($value);
					break;
				default:
					$this->$name = $value;
				break;
			}
		}

		// All good
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

		// Check if we have a user ID
		if (!isset($this->user)  && isset($this->username)) {
			$this->user = $this->helper->getUser($this->username);
		}
		
		// Check if we have a subscription title
		if (!isset($this->subscriptions)  && isset($this->subscription_title)) {
			$this->subscriptions = $this->helper->getSubscription($this->subscription_title);
		}
		
		// Set some basic values
		if (!isset($this->modified_on)) {
			$this->_coupons->modified_on = $this->date->toMySQL();
			$this->_coupons->modified_by = $this->csviuser->id;
		}

		// Add a creating date if there is no product_id
		if (empty($this->akeebasubs_coupon_id)) {
			$this->_coupons->created_on = $this->date->toMySQL();
			$this->_coupons->created_by = $this->csviuser->id;
		}

		// Bind the data
		$this->_coupons->bind($this);

		// Check the data
		$this->_coupons->check();

		// Store the data
		if ($this->_coupons->store()) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_COUPON'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_COUPON'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_COUPON_NOT_ADDED', $this->_coupons->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_COUPON_QUERY'), true);

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the coupon related tables
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
		$this->_coupons = $this->getTable('coupons');
	}

	/**
	 * Cleaning the coupon related tables
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
		$this->_coupons->reset();

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