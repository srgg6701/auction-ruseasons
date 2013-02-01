<?php
/**
 * Template types model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: templatetypes.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.modellist' );

/**
 * Template types Model
 *
* @package CSVI
 */
class CsviModelTemplatetypes extends JModelList {

	/** @var Set the context */
	var $_context = 'com_csvi.templatetypes';

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
			$config['filter_fields'] = array('template_type_name', 'template_type', 'component');
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
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// List state information.
		// Controls the query ORDER BY
		parent::populateState('template_type_name', 'asc');
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
		$query->select('*');
		$query->from('#__csvi_template_types');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		return $query;
	}

	/**
	 * Load the template types for a given selection
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		$action	the import or export option
	 * @param		$component the component
	 * @return 		array of available template types
	 * @since 		3.5
	 */
	public function loadTemplateTypes($action, $component) {
		$db = JFactory::getDbo();
		$q = "SELECT t.template_type_name
			FROM `#__csvi_template_types` AS t
			WHERE t.template_type = ".$db->Quote($action)."
			AND t.component = ".$db->Quote($component);
		$db->setQuery($q);
		$types = $db->loadResultArray();

		// Get translations
		$trans = array();
		foreach ($types as $type) {
			$trans[$type] = JText::_('COM_CSVI_'.strtoupper($type));
		}
		return $trans;
	}
}
?>