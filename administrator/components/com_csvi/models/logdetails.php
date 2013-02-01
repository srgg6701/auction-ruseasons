<?php
/**
 * Log details model
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: logdetails.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.modellist' );

/**
 * Log details Model
 *
* @package CSVI
 */
class CsviModelLogdetails extends JModelList {

	var $_context = 'com_csvi.logdetails';

	/**
	 * Constructor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		array	An optional associative array of configuration settings.
	 * @return
	 * @since 		1.0
	 */
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array('d.line', 'd.status', 'd.result');
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

		// Load the filter state
		$this->setState('filter.action', $app->getUserStateFromRequest($this->_context.'.filter.action', 'filter_action', false, 'word'));
		$this->setState('filter.result', $app->getUserStateFromRequest($this->_context.'.filter.result', 'filter_result', false, 'word'));

		// List state information.
		// Controls the query ORDER BY
		parent::populateState('d.line', 'asc');
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
		$jinput = JFactory::getApplication()->input;
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Get the Run ID
		$run_id = $jinput->get('run_id', 0, 'int');

		// Select the required fields from the table.
		$query->select('d.line, d.description, d.status, d.log_id, d.result');
		$query->from('#__csvi_log_details AS d');

		// Add all the filters
		$filters = array();
		if ($run_id) {
			$query->leftJoin('#__csvi_logs AS l ON l.id = d.log_id');
			$query->where('l.run_id = '.$run_id);
		}
		if ($this->getState('filter.action')) $filters[] = $db->quoteName('status').' = '.$db->Quote($this->getState('filter.action'));
		if ($this->getState('filter.result')) $filters[] = $db->quoteName('result').' = '.$db->Quote($this->getState('filter.result'));

		if (!empty($filters)) {
		// Add the clauses to the query.
			$query->where('('.implode(' AND ', $filters).')');
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		return $query;
	}

	/**
	 * Get the actions available for the current log
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array	of available actions
	 * @since 		3.0
	 */
	public function getActions() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$run_id = $jinput->get('run_id', 0, 'int');
		$q = "SELECT CONCAT('COM_CSVI_', UPPER(".$db->quoteName('status').")) AS ".$db->Quote('text').", ".$db->quoteName('status')." as ".$db->Quote('value')."
			FROM ".$db->quoteName('#__csvi_log_details')."
			WHERE log_id IN (SELECT id FROM #__csvi_logs WHERE run_id = ".$run_id.")
			GROUP by ".$db->quoteName('value');
		$db->setQuery($q);
		$actions = $db->loadObjectList();
		$showall = JHtml::_('select.option', '', JText::_('COM_CSVI_SELECT_ACTION'), 'value', 'text');
		array_unshift($actions, $showall);
		return JHtml::_('select.genericlist', $actions, 'filter_action',  '', 'value', 'text', $jinput->get('filter_action'), false, true);
	}

	/**
	 * Get the results available for the current log
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array	of available results
	 * @since 		3.0
	 */
	public function getResults() {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$run_id = $jinput->get('run_id', 0, 'int');
		$q = "SELECT CONCAT('COM_CSVI_', UPPER(".$db->quoteName('result').")) AS ".$db->Quote('text').", ".$db->quoteName('result')." as ".$db->Quote('value')."
			FROM ".$db->quoteName('#__csvi_log_details')."
			WHERE log_id IN (SELECT id FROM #__csvi_logs WHERE run_id = ".$run_id.")
			GROUP by ".$db->quoteName('result');
		$db->setQuery($q);
		$results = $db->loadObjectList();
		$showall = JHtml::_('select.option', '', JText::_('COM_CSVI_SELECT_RESULT'), 'value', 'text');
		array_unshift($results, $showall);
		return JHtml::_('select.genericlist', $results, 'filter_result',  '', 'value', 'text', $jinput->get('filter_result'), false, true);
	}
}
?>