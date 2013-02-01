<?php
/**
 * Subscriptions import
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
class CsviModelSubscriptionimport extends CsviModelImportfile {

	// Private tables
	private $_subscriptions = null;

	// Public variables
	public $akeebasubs_subscription_id = null;
	public $subscription_delete = false;

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
				case 'subscription_delete':
					switch ($value) {
						case 'y':
						case 'Y':
							$this->$name = 'Y';
							break;
						default:
							$this->$name = 'N';
							break;
					}
					break;
				case 'net_amount':
				case 'tax_amount':
				case 'gross_amount':
				case 'affiliate_commision':
				case 'prediscount_amount':
				case 'discount_amount':
					$this->$name = $this->cleanPrice($value);
					break;
				case 'publish_up':
				case 'publish_down':
					$this->$name = $this->convertDate($value);
					break;
				case 'state':
					$this->_subscriptions->state = $value;
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

		// See if we need to delete a subscription
		if ($this->subscription_delete == 'Y' && $this->akeebasubs_subscription_id) {
			$this->_deleteSubscription();
		}
		else {
			// Check if we have a user ID
			if (!isset($this->user_id)  && isset($this->username)) {
				$this->user_id = $this->helper->getUser($this->username);
			}
			
			// Check if we have a subscription title
			if (!isset($this->akeebasubs_level_id)  && isset($this->subscription_title)) {
				$this->akeebasubs_level_id = $this->helper->getSubscription($this->subscription_title);
			}
			
			// Add a creating date if there is no product_id
			if (empty($this->akeebasubs_subscription_id)) {
				$this->_subscriptions->created_on = $this->date->toMySQL();
			}
	
			// Bind the data
			$this->_subscriptions->bind($this);
	
			// Check the data
			$this->_subscriptions->check();
	
			// Store the data
			if ($this->_subscriptions->store()) {
				if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_SUBSCRIPTION'));
				else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_SUBSCRIPTION'));
			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_SUBSCRIPTION_NOT_ADDED', $this->_subscriptions->getError()));
	
			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_SUBSCRIPTION_QUERY'), true);
		}
		
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
		$this->_subscriptions = $this->getTable('subscriptions');
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
		$this->_subscriptions->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}
	
	/**
	 * Delete a subscription 
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
	private function _deleteSubscription() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		if ($this->_subscriptions->delete($this->akeebasubs_subscription_id)) {
			$csvilog->AddStats('deleted', JText::sprintf('COM_CSVI_SUBSCRIPTION_DELETED', $this->akeebasubs_subscription_id));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_SUBSCRIPTION_NOT_DELETED', $this->akeebasubs_subscription_id));
	}
	
}
?>