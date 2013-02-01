<?php
/**
 * Akeeba Subscriptions Affiliate payments table
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: coupons.php 1764 2012-01-04 16:18:31Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVI
 */
class TableAffpayments extends JTable {

	/**
	 * Table constructor
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
	public function __construct($db) {
		parent::__construct('#__akeebasubs_affpayments', 'akeebasubs_affpayment_id', $db );
	}
	
	/**
	 * Reset the table fields, need to do it ourselves as the fields default is not NULL
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
	public function reset() {
		// Get the default values for the class from the table.
		foreach ($this->getFields() as $k => $v) {
			// If the property is not private, reset it.
			if (strpos($k, '_') !== 0) {
				$this->$k = NULL;
			}
		}
	}
	
	/**
	 * Check if a payment has already been made 
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
	public function check() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName($this->_tbl_key));
		$query->from($db->quoteName($this->_tbl));
		$query->where($db->quoteName('akeebasubs_affiliate_id').' = '.$db->quote($this->akeebasubs_affiliate_id));
		$query->where($db->quoteName('created_on').' = '.$db->quote($this->created_on));
		$db->setQuery($query);
		$id = $db->loadResult();
		if ($id > 0) return false;
		else return true;
	}
	
	/**
	 * Delete affiliate payments 
	 * 
	 * @copyright 
	 * @author 		RolandD
	 * @todo 
	 * @see 
	 * @access 		public
	 * @param 		int	$user_id	the ID of the affiliate
	 * @return 
	 * @since 		4.0
	 */
	public function delete($user_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->quoteName($this->_tbl));
		$query->where($db->quoteName('akeebasubs_affiliate_id').' = '.$db->quote($user_id));
		$db->setQuery($query);
		if ($db->query()) return true;
		else return false;
	}
}
?>
