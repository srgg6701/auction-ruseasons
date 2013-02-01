<?php
/**
 * Order item import
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orderitemimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for order items import
 *
* @package CSVI
 */
class CsviModelOrderitemimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_orders table */
	private $_order_items = null;

	// Public variables
	/** @var int contains the order ID */
	public $virtuemart_order_id = null;
	/** @var int contains the unique orders item database ID */
	public $virtuemart_order_item_id = null;
	/** @var string contains the product SKU */
	public $product_sku = null;
	/** @var int contains the product ID */
	public $virtuemart_product_id = null;
	/** @var int contains the product SKU for the order */
	public $order_item_sku = null;

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
				case 'product_price':
					$this->product_item_price = $this->cleanPrice($value);
					break;
				case 'product_final_price':
					$this->$name = $this->cleanPrice($value);
					 break;
				case 'product_sku':
					$this->order_item_sku = $value;
					$this->product_sku = $value;
					break;
				case 'product_name':
					$this->order_item_name = $value;
					break;
				case 'created_on':
					$this->cdate = $this->convertDate($value);
					break;
				case 'modified_on':
					$this->mdate = $this->convertDate($value);
					break;
				case 'address_type':
					switch (strtolower($name)) {
						case 'shipping address':
						case 'st':
							$this->$name = 'ST';
							break;
						case 'billing address':
						case 'bt':
						default:
							$this->$name = 'BT';
							break;
					}
					break;
				case 'order_status_name':
					$this->order_status = $this->helper->getOrderStatus($value);
					break;
				default:
					$this->$name = $value;
					break;
			}
		}

		// Check if we have an order ID
		if (!isset($this->virtuemart_order_id)) {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_ORDER_ID_FOUND'));
			return false;
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
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		// Check for product ID
		if (!isset($this->virtuemart_product_id) && isset($this->product_sku)) {
			$this->virtuemart_product_id = $this->helper->getProductId();

			if (empty($this->virtuemart_product_id)) {
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_PRODUCT_ID_FOUND'));
				return false;
			}
		}
		else if (isset($this->virtuemart_product_id) && !isset($this->product_sku)) {
			$query = $db->getQuery(true);
			$query->select('product_sku');
			$query->from('#__virtuemart_products');
			$query->where('virtuemart_product_id = '.$this->virtuemart_product_id);
			$db->setQuery($query);
			$this->order_item_sku = $db->loadResult();
		}
		else if (!isset($this->virtuemart_product_id) && !isset($this->product_sku)) {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_PRODUCT_ID_OR_SKU'));
			return false;
		}

		// Set the modified date as we are modifying the product
		if (!isset($this->modified_on)) {
			$this->_order_items->modified_on = $this->date->toMySQL();
			$this->_order_items->modified_by = $this->user->id;
		}

		// Check if there is an existing order item
		$query = $db->getQuery(true);
		$query->select('virtuemart_order_item_id');
		$query->from('#__virtuemart_order_items');
		$query->where('virtuemart_order_id = '.$this->virtuemart_order_id);
		$query->where('virtuemart_product_id = '.$this->virtuemart_product_id);
		$query->where('virtuemart_vendor_id = '.$this->virtuemart_vendor_id);
		$db->setQuery($query);
		$this->virtuemart_order_item_id = $db->loadResult();

		if (empty($this->virtuemart_order_item_id) && !isset($this->created_on)) {
			$this->_order_items->created_on = $this->date->toMySQL();
			$this->_order_items->created_by = $this->user->id;
		}
		else {
			$this->_order_items->load($this->virtuemart_order_item_id);
		}

		// Bind the data
		$this->_order_items->bind($this);

		// Check if we have a product name
		if (empty($this->_order_items->order_item_name)) {
			$query = $db->getQuery(true);
			$query->select('product_name');
			$query->from('#__virtuemart_products_'.$template->get('language', 'general'));
			$query->where('virtuemart_product_id = '.$this->virtuemart_product_id);
			$db->setQuery($query);
			$this->_order_items->order_item_name = $db->loadResult();
		}

		// Store the data
		if ($this->_order_items->store()) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_ORDER_ITEM'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_ORDER_ITEM'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_ORDER_ITEM_NOT_ADDED', $this->_order_items->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_ORDER_ITEM_QUERY'), true);

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the order item related tables
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
		$this->_order_items = $this->getTable('order_items');
	}

	/**
	 * Cleaning the order item related tables
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
		$this->_order_items->reset();

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
