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
 * @version 	$Id: waitinglistimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Main processor for importing waitinglists
 *
* @package CSVI
 */
class CsviModelWaitinglistimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_waiting_list table */
	private $_waitingusers = null;

	// Public variables
	/** @var integer contains the waiting list ID for an entry */
	public $virtuemart_waitinguser_id = null;
	public $virtuemart_product_id = null;
	public $virtuemart_user_id = null;
	public $username = false;

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

		$this->virtuemart_product_id = $this->helper->getProductId();

		// Process data
		foreach ($this->csvi_data as $name => $value) {
			// Check if the field needs extra treatment
			switch ($name) {
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

		// Get the user ID
		if (empty($this->virtuemart_user_id)) {
			$this->virtuemart_user_id = $this->_getUserId();
			if (empty($this->virtuemart_user_id)) {
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_WAITINGLIST_NO_USER_FOUND'));
				return false;
			}
		}

		if (empty($this->virtuemart_product_id)) {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_WAITINGLIST_NO_PRODUCT_FOUND'));
			return false;
		}

		if ($this->virtuemart_product_id && $this->virtuemart_user_id && $this->notify_email) {
			// Bind the data
			$this->_waitingusers->bind($this);

			// Check the data
			$this->_waitingusers->check();

			// Set the modified date as we are modifying the product
			if (!isset($this->modified_on)) {
				$this->_waitingusers->modified_on = $this->date->toMySQL();
				$this->_waitingusers->modified_by = $this->user->id;
			}

			if (empty($this->_waitingusers->virtuemart_waitinguser_id)) {
				$this->_waitingusers->created_on = $this->date->toMySQL();
				$this->_waitingusers->created_by = $this->user->id;
			}

			// Store the data
			if ($this->_waitingusers->store()) {
				if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_WAITINGLIST'));
				else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_WAITINGLIST'));
			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_WAITINGLIST_NOT_ADDED', $this->_waitingusers->getError()));

			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_WAITINGLIST_QUERY'), true);
		}
		else {
			 $csvilog->AddStats('incorrect', JText::_('COM_CSVI_WAITINGLIST_NO_USER_PRODUCT_ID'));
		}

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
		$this->_waitingusers = $this->getTable('waitingusers');
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
		$this->_waitingusers->reset();

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
	private function _getUserId() {
		if (isset($this->username)) {
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id');
			$query->from('#__users');
			$query->where('username = '.$db->Quote($this->username));
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