<?php
/**
 * Order import
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orderimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for order import
 *
* @package CSVI
 */
class CsviModelOrderimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_orders table */
	private $_orders = null;
	/** @var object contains the vm_orders table */
	private $_order_userinfos = null;
	/** @var object contains the vm_orders table */
	private $_order_items = null;
	/** @var object contains the vm_orders table */
	private $_order_histories = null;

	// Public variables
	/** @var string type of address */
	public $address_type = 'BT';
	/** @var int the ID of the order */
	public $virtuemart_order_id = null;
	/** @var int creation date of the order */
	public $created_on = null;
	/** @var int modification date of the order */
	public $modified_on = null;
	/** @var string unique identifier of the order */
	public $order_number = null;
	/** @var string currency used in the order */
	public $user_currency = null;
	/** @var string currency used in the order */
	public $user_currency_id = null;
	/** @var string currency used in the order */
	public $user_currency_rate = null;
	/** @var string shipping costs for the order */
	public $order_shipment = null;
	/** @var string tax on shipping costs for the order */
	public $order_shipment_tax = null;
	/** @var string coupon code used for the order */
	public $coupon_code = null;
	/** @var string unique identifier of the user */
	public $virtuemart_user_id = null;
	/** @var int unique identifier of the order user */
	public $virtuemart_order_userinfo_id = null;
	/** @var object details of the order user */
	public $userdetails = null;
	public $address_type_name = null;
	public $company = null;
	public $title = null;
	public $last_name = null;
	public $first_name = null;
	public $middle_name = null;
	public $phone_1 = null;
	public $phone_2 = null;
	public $fax = null;
	public $address_1 = null;
	public $address_2 = null;
	public $city = null;
	public $virtuemart_state_id = null;
	public $virtuemart_country_id = null;
	public $zip = null;
	public $email = null;
	public $state_name = null;
	public $state_2_code = null;
	public $state_3_code = null;
	public $ip_address = null;
	public $order_payment = null;
	public $order_payment_tax = null;
	public $comments = null;
	public $customer_note = null;
	public $order_pass = null;
	public $country_name = null;
	public $country_2_code = null;
	public $country_3_code = null;

	// Private variables
	/** @var bool set whether user data is added/updated or not */
	private $_updateuser = false;

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
				case 'customer_notified':
					$this->$name = (strtoupper($value) == 'N') ? 0 : 1;
					break;
				case 'order_status':
					$this->$name = $value;
					break;
				case 'order_status_name':
					$this->order_status_code = $this->helper->getOrderStatus($value);
					$this->order_status = $this->order_status_code;
					break;
				case 'order_total':
				case 'order_subtotal':
				case 'order_tax':
				case 'order_shipment':
				case 'order_shipment_tax':
				case 'order_payment':
				case 'order_payment_tax':
				case 'coupon_discount':
				case 'order_discount':
					$this->$name = $this->cleanPrice($value);
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
		$db = JFactory::getDBO();
		$csvilog = $jinput->get('csvilog', null, null);

		// Load the order user details
		if (!isset($this->virtuemart_user_id) && isset($this->email)) {
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__users');
			$query->where('email = '.$db->Quote($this->email));
			$db->setQuery($query);
			$this->virtuemart_user_id = $db->loadResult();
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_RETRIEVE_USER_ID'), true);
		}

		if (isset($this->virtuemart_user_id)) {
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__virtuemart_userinfos');
			$query->where('address_type = '.$db->Quote('BT'));
			$query->where('virtuemart_user_id = '.$this->virtuemart_user_id);
			$db->setQuery($query);
			$userdetails = $db->loadObject();
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_LOAD_USER_DETAILS'), true);
		}
		else {
			$csvilog->addDebug(JText::_('COM_CSVI_NOT_PROCESS_USER'));
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NOT_PROCESS_USER'));
			return false;
		}

		// Check if we have an order ID
		if (empty($this->virtuemart_order_id) && !empty($this->order_number)) {
			$query = $db->getQuery(true);
			$query->select('virtuemart_order_id');
			$query->from('#__virtuemart_orders');
			$query->where('order_number = '.$db->Quote($this->order_number));
			$db->setQuery($query);
			$this->virtuemart_order_id = $db->loadResult();
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_LOAD_ORDER_ID'), true);
		}

		// Load the order if there is an order_id
		if (empty($this->virtuemart_order_id)) {
			// Add a creating date if there is no order id
			$this->_orders->created_on = $this->date->toMySQL();
			$this->_orders->created_by = $this->user->id;

			// Create an order number if it is empty
			if (empty($this->order_number)) {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_CREATE_ORDER_NUMBER'));
				$this->order_number = substr(md5(session_id().(string)time().(string)$this->virtuemart_user_id), 0, 8);
			}
			else {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_NOT_CREATE_ORDER_NUMBER'));
			}

			// Create an order pass
			if (empty($this->order_pass)) {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_CREATE_ORDER_PASS'));
				$this->order_pass = 'p_'.substr(md5(session_id().(string)time().(string)$this->order_number), 0, 6);
			}

			// Check the user currency
			if (!isset($this->user_currency_id) && isset($this->user_currency)) {
				$query = $db->getQuery(true);
				$query->select('virtuemart_currency_id');
				$query->from('#__virtuemart_currencies');
				$query->where('currency_code_3 = '.$db->Quote($this->user_currency));
				$db->setQuery($query);
				$this->user_currency_id = $db->loadResult();
			}

			// Check the currency rate
			if (!isset($user->user_currency_rate)) {
				$user->user_currency_rate = 1;
			}

			// Check the order currency
			if (!isset($this->order_currency)) $this->_orders->order_currency = $this->user_currency_id;

			// Check the user info id
			//if (empty($this->virtuemart_order_id)) $this->virtuemart_order_userinfo_id = $userdetails->virtuemart_userinfo_id;

			// Check the pyament method ID
			if (!isset($this->virtuemart_paymentmethod_id)) {
				// Handle the payment method ID
				if (isset($this->payment_element)) {
					$query = $db->getQuery(true);
					$query->select('virtuemart_paymentmethod_id');
					$query->from('#__virtuemart_paymentmethods');
					$query->where('payment_element = '.$db->Quote($this->payment_element));
					$db->setQuery($query);
					$this->virtuemart_paymentmethod_id = $db->loadResult();
				}
				else $this->virtuemart_paymentmethod_id = 0;
			}

			// Check order payment
			if (!isset($this->order_payment) )$this->_orders->order_payment = 0;

			// Check order payment tax
			if (!isset($this->order_payment_tax)) $this->_orders->order_payment_tax = 0;

			// Check the order_shipping
			if (!isset($this->order_shipment)) $this->order_shipment = 0;

			// Check the order_shipping_tax
			if (!isset($this->order_shipment_tax)) $this->order_shipment_tax = 0;

			// Check the coupon_code
			if (!isset($this->coupon_code)) $this->coupon_code = '';

			// Check the customer note
			if (!isset($this->customer_note)) $this->customer_note = '';

			// Check the IP address
			if (!isset($this->ip_address)) $this->ip_address = $_SERVER['SERVER_ADDR'];

			// Check the ship_method_id
			if (!isset($this->virtuemart_shipmentmethod_id)) {
				if (isset($this->shipment_element)) {
					$query = $db->getQuery(true);
					$query->select('virtuemart_shipmentmethod_id');
					$query->from('#__virtuemart_shipmentmethods');
					$query->where('shipment_element = '.$db->Quote('shipment_element'));
					$db->setQuery($query);
					$this->virtuemart_shipmentmethod_id = $db->loadResult();
				}
				$this->virtuemart_shipmentmethod_id = '';
			}
		}

		// Add the modification date
		if (!isset($this->modified_on)) {
			$this->_orders->modified_on = $this->date->toMySQL();
			$this->_orders->modified_by = $this->user->id;
		}

		// Bind order data
		$this->_orders->bind($this);

		// Store the order
		if ($this->_orders->store()) {
			$csvilog->addDebug(JText::_('COM_CSVI_ORDER_QUERY'), true);
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_ORDER'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_ORDER'));
			$this->virtuemart_order_id = $this->_orders->virtuemart_order_id;
		}
		else {
			$csvilog->addDebug(JText::_('COM_CSVI_ORDER_QUERY'), true);
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_ORDER_NOT_ADDED', $this->_orders->getError()));

			// Clean the tables
			$this->cleanTables();
			return false;
		}

		// Store the user info
		if (!isset($this->virtuemart_order_userinfo_id)) {
			// Check if there is the requested address in the database
			$query = $db->getQuery(true);
			$query->select('virtuemart_order_userinfo_id');
			$query->from('#__virtuemart_order_userinfo');
			$query->where('address_type = '.$db->Quote($this->address_type));
			$query->where('virtuemart_order_id = '.$this->virtuemart_order_id);
			$db->setQuery($query);
			$this->virtuemart_order_userinfo_id = $db->loadResult();
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_LOAD_ORDER_INFO_ID'), true);
		}

		// Load the order info
		if ($this->virtuemart_order_userinfo_id) {
			$this->_order_userinfos->load($this->virtuemart_order_userinfo_id);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_LOAD_ORDER_INFO'), true);
			if (!isset($this->modified_on)) {
				$this->_order_userinfos->modified_on = $this->date->toMySQL();
				$this->_order_userinfos->modified_by = $this->user->id;
			}
		}

		if (!$this->virtuemart_order_userinfo_id || $this->_order_userinfos->virtuemart_user_id != $this->virtuemart_user_id) {
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_LOAD_USER_ORDER_INFO'));
			// Address type name
			if (!isset($this->address_type_name)) $this->address_type_name = $userdetails->address_type_name;

			// Company
			if (!isset($this->company)) $this->company = $userdetails->company;

			// Title
			if (!isset($this->title)) $this->title = $userdetails->title;

			// Last name
			if (!isset($this->last_name)) $this->last_name = $userdetails->last_name;

			// First name
			if (!isset($this->first_name)) $this->first_name = $userdetails->first_name;

			// Middle name
			if (!isset($this->middle_name)) $this->middle_name = $userdetails->middle_name;

			// Phone 1
			if (!isset($this->phone_1)) $this->phone_1 = $userdetails->phone_1;

			// Phone 2
			if (!isset($this->phone_2)) $this->phone_2 = $userdetails->phone_2;

			// Fax
			if (!isset($this->fax)) $this->fax = $userdetails->fax;

			// Address 1
			if (!isset($this->address_1)) $this->address_1 = $userdetails->address_1;

			// Address 2
			if (!isset($this->address_2)) $this->address_2 = $userdetails->address_2;

			// City
			if (!isset($this->city)) $this->city = $userdetails->city;

			// State
			if (!isset($this->virtuemart_state_id)) {
				if (isset($this->state_name) || isset($this->state_2_code) || isset($this->state_3_code)) {
					$query = $db->getQuery(true);
					$query->select('virtuemart_state_id');
					$query->from('#__virtuemart_states');
					if (isset($this->state_name)) $query->where('state_name = '.$db->Quote($this->state));
					else if (isset($this->state_2_code)) $query->where('state_2_code = '.$db->Quote($this->state_2_code));
					else if (isset($this->state_3_code)) $query->where('state_3_code = '.$db->Quote($this->state_3_code));
					$db->setQuery($query);
					$this->virtuemart_state_id = $db->loadResult();
				}
				else $this->virtuemart_state_id = $userdetails->virtuemart_state_id;
			}

			// Country
			if (!isset($this->virtuemart_country_id)) {
				if (isset($this->country_name) || isset($this->country_2_code) || isset($this->country_3_code)) {
					$this->virtuemart_country_id = $this->helper->getCountryId($this->country_name, $this->country_2_code, $this->country_3_code);
				}
				else $this->virtuemart_country_id = $userdetails->virtuemart_country_id;
			}

			// Zip
			if (!isset($this->zip)) $this->zip = $userdetails->zip;

			// Agreed
			if (!isset($this->agreed)) $this->agreed = 0;

			// Modified date
			if (!isset($this->modified_on)) {
				$this->_order_userinfos->modified_on = $this->date->toMySQL();
				$this->_order_userinfos->modified_by = $this->user->id;
			}

			// Created date
			if (!isset($this->created_on)) {
				$this->_order_userinfos->created_on = $this->date->toMySQL();
				$this->_order_userinfos->created_by = $this->user->id;
			}
		}

		// Bind the user uploaded data
		$this->_order_userinfos->bind($this);

		// Store the order user info
		if ($this->_order_userinfos->store($this)) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_ORDERUSER'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_ORDERUSER'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_ORDERUSER_NOT_ADDED', $this->_order_userinfos->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_ORDERUSER_QUERY'), true);

		// Check if the order has at least a billing address
		if ($this->address_type == 'ST') {
			// Check if there is the requested address in the database
			$query = $db->getQuery(true);
			$query->select('virtuemart_order_userinfo_id');
			$query->from('#__virtuemart_order_userinfos');
			$query->where('address_type = '.$db->Quote('BT'));
			$query->where('virtuemart_order_id = '.$this->virtuemart_order_id);
			$db->setQuery($query);
			$bt_order_info_id = $db->loadResult();

			// There is no BT address let's add one
			if (!$bt_order_info_id) {
				// Get all the fields from the user info table
				$q = "SHOW COLUMNS FROM #__virtuemart_userinfos";
				$db->setQuery($q);
				$user_fields_raw = $db->loadAssocList();

				$user_fields = array();
				foreach($user_fields_raw as $user_field) {
					$user_fields[] = $user_field['Field'];
				}

				$q = "SHOW COLUMNS FROM #__virtuemart_order_userinfos";
				$db->setQuery($q);
				$order_user_fields_raw = $db->loadAssocList();

				$order_user_fields = array();
				foreach($order_user_fields_raw as $user_field) {
					$order_user_fields[] = $user_field['Field'];
				}

				$copy_fields = array_intersect($order_user_fields, $user_fields);

				// Create the billing address entry
				$q = "INSERT INTO #__virtuemart_order_userinfos (".implode(',', $copy_fields).", virtuemart_order_id) (SELECT ".implode(',', $copy_fields).", ".$this->virtuemart_order_id." AS order_id FROM #__virtuemart_userinfos WHERE user_id = ".$this->virtuemart_user_id." AND address_type = 'BT')";
				$db->setQuery($q);
				$db->query();
				$csvilog->addDebug(JText::_('COM_CSVI_CREATE_BILLING_QUERY'), true);
			}
		}

		// Create an order history entry
		// Load the payment info
		if (isset($this->virtuemart_order_history_id)) {
			$this->_order_histories->load($this->virtuemart_order_history_id);
			if (!isset($this->modified_on)) {
				$this->_order_histories->modified_on = $this->date->toMySQL();
				$this->_order_histories->modified_by = $this->user->id;
			}
		}
		else {
			if (!isset($this->modified_on)) {
				$this->_order_histories->modified_on = $this->date->toMySQL();
				$this->_order_histories->modified_by = $this->user->id;
			}

			// Add a creating date if there is no product_id
			$this->_order_histories->created_on = $this->date->toMySQL();
			$this->_order_histories->created_by = $this->user->id;
			if (!isset($this->customer_notified)) $this->customer_notified = 0;

			// Comments
			$this->_order_histories->comments = '';
		}

		// Bind the payment data
		$this->_order_histories->bind($this);

		// Store the order history info
		if ($this->_order_histories->store($this)) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_ORDER_HISTORY'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_ORDER_HISTORY'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_ORDER_PAYMNET_NOT_ADDED', $this->_order_histories->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_ORDER_HISTORY_QUERY'), true);

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the order import related tables
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
		$this->_orders = $this->getTable('orders');
		$this->_order_userinfos = $this->getTable('order_userinfos');
		$this->_order_items = $this->getTable('order_items');
		$this->_order_histories = $this->getTable('order_histories');
	}

	/**
	 * Cleaning the order import related tables
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
		$this->_orders->reset();
		$this->_order_userinfos->reset();
		$this->_order_items->reset();
		$this->_order_histories->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}

	/**
	 * Formate a date to MySQL timestamp
	 *
	 * Format of the date is day/month/year or day-month-year.
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		use JDate
	 * @todo 		move to general function
	 * @see
	 * @access 		private
	 * @param
	 * @return 		string 	MySQL timestamp
	 * @since 		3.0
	 */
	private function _getMysqlDate() {
		$new_date = preg_replace('/-|\./', '/', $this->_datafield);
		$date_parts = explode('/', $new_date);
		if ((count($date_parts) == 3) && ($date_parts[0] > 0 && $date_parts[0] < 32 && $date_parts[1] > 0 && $date_parts[1] < 13 && (strlen($date_parts[2]) == 4))) {
			$old_date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
		}
		else $old_date = date('Y-m-d H:i:s', time());
		return $old_date;
	}
}
?>
