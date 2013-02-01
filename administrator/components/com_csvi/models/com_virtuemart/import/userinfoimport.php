<?php
/**
 * User info import
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: userinfoimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for user info
 *
* @package CSVI
 * @todo 	format registerdate
 */
class CsviModelUserinfoimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the vm_user_info table */
	private $_userinfos = null;
	/** @var object contains the vm_shopper_vendor_xref table */
	private $_vmuser_shoppergroups = null;
	/** @var object contains the user table */
	private $_user = null;
	/** @var object contains the vmuser table */
	private $_vmusers = null;

	// Public variables
	/** @var integer contains the unique user ID string for a billing or shipping address */
	public $virtuemart_userinfo_id = null;
	/** @var integer contains the unique Joomla user ID */
	public $virtuemart_user_id = null;
	/** @var integer contains the unique shopper group ID */
	public $virtuemart_shoppergroup_id = null;
	/** @var integer contains the unique vendor ID */
	public $virtuemart_vendor_id = null;

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
	 * @todo		change cdate/mdate to use JDate
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

		// Process data
		foreach ($this->csvi_data as $name => $value) {
			// Check if the field needs extra treatment
			switch ($name) {
				case 'address_type':
					switch (strtolower($value)) {
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
	 * @todo		Add a beter text for MISSING_REQUIRED_FIELDS
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
	   	$userdata = array();
	   	jimport('joomla.user.helper');

		// See if we have a user_info_id
		if (empty($this->virtuemart_userinfo_id)) {
			// No user_info_id, maybe we have user_id, address_type and address_type_name
			if ((!isset($this->virtuemart_user_id) && (!isset($this->email))) || !isset($this->address_type) || !isset($this->address_type_name)) {
				// No way to identify what needs to be updated, set error and return
				$csvilog->AddStats('incorrect', JText::_('COM_CSVI_MISSING_REQUIRED_FIELDS'));
				return false;
			}
		}
		// We have a virtuemart_userinfo_id, do we have a virtuemart_user_id
		else {
			$query = $db->getQuery(true);
			$query->select('virtuemart_user_id');
			$query->from('#__virtuemart_userinfos');
			$query->where('virtuemart_userinfo_id = '.$db->Quote($this->virtuemart_userinfo_id));
			$db->setQuery($query);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_FIND_USER_ID_FROM_VM'), true);
			$this->virtuemart_user_id = $db->loadResult();
		}

		// Check for the user_info_id
		if (empty($this->virtuemart_userinfo_id)) {
			// See if we have a user_id or user_email
			if (!isset($this->virtuemart_user_id) && isset($this->email)) {
				// We have an e-mail address, find the user_id
				$query = $db->getQuery(true);
				$query->select('id');
				$query->from('#__users');
				$query->where('email = '.$db->Quote($this->email));
				$db->setQuery($query);
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_FIND_USER_ID_FROM_JOOMLA'), true);
				$this->virtuemart_user_id = $db->loadResult();
			}
			if ($this->virtuemart_user_id) {
				// if we have a user_id we can get the user_info_id
				$query = $db->getQuery(true);
				$query->select('virtuemart_userinfo_id');
				$query->from('#__virtuemart_userinfos');
				$query->where('virtuemart_user_id = '.$this->virtuemart_user_id);
				$query->where('address_type = '.$db->Quote($this->address_type));
				$query->where('address_type_name = '.$db->Quote($this->address_type_name));
				$db->setQuery($query);
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_FIND_USER_INFO_ID'), true);
				$this->virtuemart_userinfo_id = $db->loadResult();
			}
		}

		// If it is a new Joomla user but no username is set, we must set one
		if ((!isset($this->virtuemart_user_id) || !$this->virtuemart_user_id) && !isset($this->username)) {
			$userdata['username'] = $this->email;
		}
		// Set the username
		else if (isset($this->username)) $userdata['username'] = $this->username;

		// Check if we have an encrypted password
		if (isset($this->password_crypt)) {
			$userdata['password'] = $this->password_crypt;
		}
		else if (isset($this->password)) {
			// Check if we have an encrypted password
			$salt		= JUserHelper::genRandomPassword(32);
			$crypt		= JUserHelper::getCryptedPassword($this->password, $salt);
			$password	= $crypt.':'.$salt;
			$userdata['password'] = $password;
		}

		// No user id, need to create a user if possible
		if (!isset($this->virtuemart_user_id)
			&& isset($this->email)
			&& isset($this->password)) {

			// Set the creation date
			$date = JFactory::getDate();
			$userdata['registerDate'] = $date->toMySQL();
		}
		else if (!isset($this->virtuemart_user_id)
			&& (!isset($this->email)
			|| !isset($this->password))) {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_NO_NEW_USER_PASSWORD_EMAIL'));
			return false;
		}
		else {
			// Set the id
			$userdata['id'] = $this->virtuemart_user_id;
		}

		// Only store the Joomla user if there is an e-mail address supplied
		if (isset($this->email)) {
			// Set the name
			if (isset($this->name)) $userdata['name'] = $this->name;
			else {
				$fullname = false;
				if (isset($this->first_name)) $fullname .= $this->first_name.' ';
				if (isset($this->last_name)) $fullname .= $this->last_name;
				if (!$fullname) $fullname = $this->user_email;
				$userdata['name'] = trim($fullname);
			}

			// Set the email
			$userdata['email'] = $this->email;

			// Set if the user is blocked
			if (isset($this->block)) $userdata['block'] = $this->block;

			// Set the sendEmail
			if (isset($this->sendemail)) $userdata['sendEmail'] = $this->sendemail;

			// Set the registerDate
			if (isset($this->registerdate)) $userdata['registerDate'] = $this->registerdate;

			// Set the lastvisitDate
			if (isset($this->lastvisitdate)) $userdata['lastvisitDate'] = $this->lastvisitdate;

			// Set the activation
			if (isset($this->activation)) $userdata['activation'] = $this->activation;

			// Set the params
			if (isset($this->params)) $userdata['params'] = $this->params;

			// Check if we have a group ID
			if (!isset($this->group_id)) {
				$query = $db->getQuery(true);
				$query->select('id');
				$query->from('#__usergroups');
				$query->where($db->quoteName('title').' = '.$db->Quote($this->usergroup_name));
				$db->setQuery($query);
				$this->group_id = $db->loadResult();

				if (empty($this->group_id)) {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_NO_USERGROUP_FOUND', $this->usergroup_name));
					return false;
				}
			}

			// Bind the data
			$this->_user->bind($userdata);

			// Store/update the user
			if ($this->_user->store()) {
				$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_JOOMLA_USER_STORED'), true);
				// Get the new user ID
				$this->virtuemart_user_id = $this->_user->id;

				// Empty the usergroup map table
				$query = $db->getQuery(true);
				$query->delete('#__user_usergroup_map');
				$query->where('user_id = '.$this->virtuemart_user_id);
				$db->setQuery($query);
				$db->query();

				// Store the user in the usergroup map table
				$query = $db->getQuery(true);
				$query->insert('#__user_usergroup_map');
				$query->values($this->virtuemart_user_id.', '.$this->group_id);
				$db->setQuery($query);
				// Store the map
				if ($db->query()) {
					$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_JOOMLA_USER_MAP_STORED'), true);
				}
				else $csvilog->addDebug(JText::_('COM_CSVI_DEBUG_JOOMLA_USER_MAP_NOT_STORED'), true);
			}
			else $csvilog->addDebug(JText::_('COM_CSVI_DEBUG_JOOMLA_USER_NOT_STORED'), true);
		}
		else $csvilog->addDebug(JText::_('COM_CSVI_DEBUG_JOOMLA_USER_SKIPPED'));

		// Set the modified date as we are modifying the product
		if (!isset($this->modified_on)) {
			$this->_userinfos->modified_on = $this->date->toMySQL();
			$this->_userinfos->modified_by = $this->user->id;
		}

		// Bind the VirtueMart user data
		$this->_userinfos->bind($this);

		// Store the VirtueMart user info
		if ($this->_userinfos->store()) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_USERINFO'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_USERINFO'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_USERINFO_NOT_ADDED', $this->_userinfos->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_USERINFO_QUERY'), true);

		// See if there is any shopper group information to be stored
		// user_id, vendor_id, shopper_group_id, customer number
		// Get the user_id
		if (!isset($this->virtuemart_user_id) && isset($this->_userinfos->virtuemart_userinfo_id)) {
			$this->virtuemart_user_id = $_userinfos->virtuemart_user_id;
		}

		// Get the vendor_id
		if (empty($this->virtuemart_vendor_id) && isset($this->vendor_name)) {
			$query = $db->getQuery(true);
			$query->select('virtuemart_vendor_id');
			$query->from('#__virtuemart_vendors');
			$query->where('vendor_name = '.$db->Quote($this->vendor_name));
			$db->setQuery($query);
			$this->virtuemart_vendor_id = $db->loadResult();
			if (empty($this->vendor_id)) $this->virtuemart_vendor_id = $this->helper->getVendorId();
		}
		else $this->virtuemart_vendor_id = $this->helper->getVendorId();

		// Get the shopper_group_id
		if (empty($this->virtuemart_shoppergroup_id) && isset($this->shopper_group_name)) {
			$query = $db->getQuery(true);
			$query->select('virtuemart_shoppergroup_id');
			$query->from('#__virtuemart_shoppergroups');
			$query->where('shopper_group_name = '.$db->Quote($this->shopper_group_name));
			$db->setQuery($query);
			$this->virtuemart_shoppergroup_id = $db->loadResult();
			if (empty($this->virtuemart_shoppergroup_id)) $this->virtuemart_shoppergroup_id = $this->helper->getDefaultShopperGroupID();
		}
		else if (!isset($this->virtuemart_shoppergroup_id) && !isset($this->shopper_group_name)) $this->virtuemart_shoppergroup_id = $this->helper->getDefaultShopperGroupID();

		// Bind the shopper group data
		$this->_vmuser_shoppergroups->bind($this);
		$this->_vmuser_shoppergroups->check();
		if ($this->_vmuser_shoppergroups->store()) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_SHOPPER_GROUP'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_SHOPPER_GROUP'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_SHOPPER_GROUP_NOT_ADDED', $this->_vmuser_shoppergroups->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_SHOPPER_GROUP_QUERY'), true);

		// See if there is any vmusers entry
		$this->_vmusers->load($this->virtuemart_user_id);
		if (empty($this->_vmusers->virtuemart_user_id)) {
			if (!isset($this->user_is_vendor)) $this->user_is_vendor = 0;
			if (!isset($this->customer_number)) $this->customer_number = md5($userdata['username']);
			if (!isset($this->perms)) $this->perms = 'shopper';
			if (!isset($this->virtuemart_paymentmethod_id)) $this->virtuemart_paymentmethod_id = null;
			if (!isset($this->virtuemart_shipmentmethod_id)) $this->virtuemart_shipmentmethod_id = null;
			if (!isset($this->agreed)) $this->agreed = 0;
		}

		// Bind the data
		$this->_vmusers->bind($this);

		// Check the vmusers table
		if ($this->_vmusers->check()) {
			// Update the dates
			if (!isset($this->modified_on)) {
				$this->_vmusers->modified_on = $this->date->toMySQL();
				$this->_vmusers->modified_by = $this->user->id;
			}
		}
		else {
			$this->_vmusers->created_on = $this->date->toMySQL();
			$this->_vmusers->created_by = $this->user->id;
		}

		// Store the vmusers data
		if ($this->_vmusers->store()) {
			if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_VMUSERS'));
			else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_VMUSERS'));
		}
		else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_VMUSERS_NOT_ADDED', $this->_vmusers->getError()));

		// Store the debug message
		$csvilog->addDebug(JText::_('COM_CSVI_VMUSERS_QUERY'), true);

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the user info related tables
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
		$this->_userinfos = $this->getTable('userinfos');
		$this->_vmusers = $this->getTable('vmusers');
		$this->_vmuser_shoppergroups = $this->getTable('vmuser_shoppergroups');
		$this->_user = $this->getTable('users');
	}

	/**
	 * Cleaning the user info related tables
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
		$this->_userinfos->reset();
		$this->_vmusers->reset();
		$this->_vmuser_shoppergroups->reset();
		$this->_user->reset();

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