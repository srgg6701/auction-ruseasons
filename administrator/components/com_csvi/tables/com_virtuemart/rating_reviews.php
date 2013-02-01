<?php
/**
 * Virtuemart Product Rating Review table
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: rating_reviews.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package CSVI
 */
class TableRating_reviews extends JTable {

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
		parent::__construct('#__virtuemart_rating_reviews', 'virtuemart_rating_review_id', $db );
	}

	/**
	 * Check if there is already an existing review by the user
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
	 public function check() {
	 	// See if we already have review id
		if (empty($this->virtuemart__rating_review_id)) {
			$jinput = JFactory::getApplication()->input;
			$db = JFactory::getDBO();
			$csvilog = $jinput->get('csvilog', null, null);

			// Check if a record already exists in the database
			$q = "SELECT ".$this->_tbl_key."
				FROM ".$this->_tbl."
				WHERE virtuemart_product_id = '".$this->virtuemart_product_id."'
				AND created_by = ".$this->created_by;
			$db->setQuery($q);
			$db->query($q);
			$csvilog->addDebug(JText::_('COM_CSVI_CHECK_RATING_REVIEW_EXISTS'), true);
			if ($db->getAffectedRows() > 0) {
				$this->virtuemart_rating_id = $db->loadResult();
				return true;
			}
			else {
				// There is no entry yet, so we must insert a new one
				return false;
			}
		}
		// There is already a rating id
		else return true;
	 }

	/**
	 * Reset the keys including primary key
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
}
?>