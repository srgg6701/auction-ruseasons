<?php
/**
 * Template types model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: templatetypes.php 1764 2012-01-04 16:18:31Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.modellist' );

/**
 * Template types Model
 *
* @package CSVI
 */
class CsviModelReplacements extends JModelList {

	/** @var Set the context */
	var $_context = 'com_csvi.replacements';

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
	 * @since 		4.0
	 */
	public function __construct() {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array('name', 'method');
		}

		parent::__construct($config);
	}


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access		protected
	 * @param
	 * @return		void
	 * @since		4.0
	 */
	protected function populateState() {
		// List state information.
		// Controls the query ORDER BY
		parent::populateState('name', 'asc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return		object the query to execute
	 * @since 		4.0
	 */
	protected function getListQuery() {
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('r.*, u.name AS editor');
		$query->from('#__csvi_replacements AS r');
		
		// Join the user table
		$query->leftJoin('#__users AS u ON u.id = r.checked_out');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		return $query;
	}
}
?>