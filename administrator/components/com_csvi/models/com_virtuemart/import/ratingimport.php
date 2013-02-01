<?php
/**
 * Product reviews import
 *
 * @package		CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: ratingimport.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product reviews
 *
* @package CSVI
 */
class CsviModelRatingimport extends CsviModelImportfile {

	// Private tables
	private $_ratings = null;
	private $_rating_reviews = null;
	private $_rating_votes = null;

	// Public variables
	public $virtuemart_product_id =  null;
	public $lastip = null;
	public $created_on = null;

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
		// Load the data
		$this->loadData();

		// Get the logger
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);

		// Load the helper
		$this->helper = new Com_VirtueMart();

		// Get the product ID
		$this->virtuemart_product_id = $this->helper->getProductId();

		// Process data
		foreach ($this->csvi_data as $name => $value) {
			// Check if the field needs extra treatment
			switch ($name) {
				case 'published':
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
				default:
					$this->$name = $value;
					break;
			}
		}

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
		// Get the imported values
		$csvilog = $jinput->get('csvilog', null, null);
		$db = JFactory::getDBO();

		// Check if there is a product ID
		if (!empty($this->virtuemart_product_id)) {

			// Find the user ID for the username
			if (isset($this->username)) {
				$q = "SELECT id FROM #__users WHERE username = ".$db->Quote($this->username);
				$db->setQuery($q);
				$this->created_by = $db->loadResult();
			}

			// Set some basic values
			if (is_null($this->lastip)) $this->lastip = $_SERVER['SERVER_ADDR'];
			if (is_null($this->created_on)) $this->created_on = $this->date->toMySQL();
			// Set the modified date as we are modifying the product
			if (!isset($this->modified_on)) {
				$this->modified_on = $this->date->toMySQL();
				$this->modified_by = $this->user->id;
			}

			// Bind the data
			$this->_rating_reviews->bind($this);

			// Store the rating reviews
			if ($this->_rating_reviews->store()) {
				if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_PRODUCT_REVIEW'));
				else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_PRODUCT_REVIEW'));

				// Store the rating votes
				$this->_rating_votes->bind($this);
				if ($this->_rating_votes->store($this)) {

					// Update product votes
					$vote = new stdClass();
					$vote->virtuemart_product_id = $this->virtuemart_product_id;
					$vote->created_on = $this->created_on;
					$vote->created_by = $this->created_by;
					$vote->modified_on = $this->modified_on;
					$vote->modified_by = $this->modified_by;

					// Check if an entry already exist
					$query = $db->getQuery(true);
					$query->select('virtuemart_rating_id');
					$query->from('#__virtuemart_ratings');
					$query->where('virtuemart_product_id = '.$this->virtuemart_product_id);
					$db->setQuery($query);
					$vote->virtuemart_rating_id = $db->loadResult();
					// Vote exists
					if ($vote->virtuemart_rating_id > 0) {
						// Get all the votes
						$q = "SELECT vote FROM #__virtuemart_rating_votes WHERE virtuemart_product_id = ".$this->virtuemart_product_id;
						$db->setQuery($q);
						$ratings = $db->loadResultArray();

						// Create the new totals
						$vote->ratingcount = count($ratings);
						$vote->rates = array_sum($ratings);
						$vote->rating = $vote->rates / $vote->ratingcount;

					}
					// Vote does not exist
					else {
						$vote->rates = $this->vote;
						$vote->rating = $this->vote;
						$vote->ratingcount = 1;
					}

					// Store the ratings
					$this->_ratings->bind($vote);
					$this->_ratings->check();
					$this->_ratings->store();
				}
			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_PRODUCT_REVIEW_NOT_ADDED', $this->_rating_reviews->getError()));

			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_PRODUCT_REVIEW_QUERY'), true);
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_PRODUCT_REVIEW_NO_PRODUCT_ID'));
		}

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the reviews related tables
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
		$this->_ratings = $this->getTable('ratings');
		$this->_rating_reviews = $this->getTable('rating_reviews');
		$this->_rating_votes = $this->getTable('rating_votes');
	}

	/**
	 * Cleaning the product related tables
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
		$this->_ratings->reset();
		$this->_rating_reviews->reset();
		$this->_rating_votes->reset();

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
