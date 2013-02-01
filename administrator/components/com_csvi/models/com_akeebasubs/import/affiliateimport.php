<?php
/**
 * Affiliate import
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
 * Processor for affiliates
 *
 * Main processor for importing affiliates
 *
 * @package CSVI
 * @todo 	Check vendor ID
 */
class CsviModelAffiliateimport extends CsviModelImportfile {

	// Private tables
	private $_affiliates = null;
	private $_affpayments = null;

	// Public variables
	public $akeebasubs_affiliate_id = null;
	public $affiliate_delete = 'N';
	public $user_id = null;

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
				case 'commision':
				case 'amount':
					$this->$name = $this->cleanPrice($value);
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
		if (!isset($this->user_id)  && isset($this->username)) {
			$this->user_id = $this->helper->getUser($this->username);
		}
		if (!empty($this->user_id)) {
			// Bind the data
			$this->_affiliates->bind($this);
			
			// Check if the affiliate needs to be deleted
			if ($this->affiliate_delete == 'Y') {
				$this->_deleteAffiliate();
			}
			else {
				// Check the data
				$this->_affiliates->check();
		
				// Store the data
				if ($this->_affiliates->store()) {
					if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_AFFILIATE'));
					else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_AFFILIATE'));
				}
				else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_AFFILIATE_NOT_ADDED', $this->_affiliates->getError()));
		
				// Store the debug message
				$csvilog->addDebug(JText::_('COM_CSVI_AFFILIATE_QUERY'), true);
				
				// See if we have any affiliate payments to add
				if (isset($this->amount)) {
					if (!isset($this->created_on)) $this->created_on = $this->date->toMySQL();
					
					// Bind the data
					$this->akeebasubs_affiliate_id = $this->_affiliates->akeebasubs_affiliate_id;
					$this->_affpayments->bind($this);
					
					// Check the data
					if ($this->_affpayments->check()) {
						// Store the data
						if ($this->_affpayments->store()) {
							if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_AFFILIATEPAY'));
							else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_AFFILIATEPAY'));
						}
						else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_AFFILIATEPAY_NOT_ADDED', $this->_affpayments->getError()));
						
						// Store the debug message
						$csvilog->addDebug(JText::_('COM_CSVI_AFFILIATEPAY_QUERY'), true);
					}
				}
			}

		}
		
		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the affiliate related tables
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
		$this->_affiliates = $this->getTable('affiliates');
		$this->_affpayments = $this->getTable('affpayments');
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
		$this->_affiliates->reset();
		$this->_affpayments->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}
	
	/**
	 * Delete an affiliate 
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
	private function _deleteAffiliate() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Check the data
		if ($this->_affiliates->check()) {
			if ($this->_affiliates->delete()) {
				$csvilog->AddStats('deleted', JText::sprintf('COM_CSVI_AFFILIATES_DELETED', $this->_affiliates->akeebasubs_affiliate_id));
				
				// Delete all payments
				if ($this->_affpayments->delete($this->user_id)) {
					$csvilog->AddStats('deleted', JText::sprintf('COM_CSVI_AFFILIATESPAY_DELETED', $this->user_id));
				}
				else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_AFFILIATESPAY_NOT_DELETED', $this->user_id));
			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_AFFILIATES_NOT_DELETED', $this->_affiliates->akeebasubs_affiliate_id));
		}
	}
}
?>