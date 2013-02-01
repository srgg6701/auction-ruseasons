<?php
/**
 * Log view
 *
 * The logger needs to record several messages. These are:
 * - Successful imported records
 * - Failed imported records
 * - Status messages
 * - Warning messages
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Log View
 *
* @package CSVI
 */
class CsviViewLog extends JView {

	/**
	* Items to be displayed
	*/
	protected $items;

	/**
	* Pagination for the items
	*/
	protected $pagination;

	/**
	* User state
	*/
	protected $state;


	/**
	* Log view display method
	* @return void
	*/
	function display($tpl = null) {
		$jinput = JFactory::getApplication()->input;
		// Get the task
		$task = $jinput->get('task');
		// Get the log
		$model = $this->getModel('log');

		switch ($task) {
			case 'logreader':
				$this->logdetails = $this->get('Logfile');
				$this->logfile = $logfile = CSVIPATH_DEBUG.'/com_csvi.log.'.JRequest::getInt('run_id').'.php';
				break;
			default:
				// Load the logs
				$this->logentries = $this->get('Items');

				// Get the pagination
				$this->pagination = $this->get('Pagination');

				// Load the user state
				$this->state = $this->get('State');

				// Load the action types
				$actiontypes = $this->get('ActionTypes');
				$this->lists['actions'] = JHTML::_('select.genericlist', $actiontypes, 'filter_actiontype', '', 'value', 'text', JRequest::getWord('filter_actiontype'));

				// Get the panel
				$this->loadHelper('panel');

				// Add toolbar
				JToolBarHelper::title(JText::_('COM_CSVI_LOG'), 'csvi_log_48');
				JToolBarHelper::custom( 'logdetails.logdetails', 'csvi_logdetails_32', 'csvi_logdetails_32', JText::_('COM_CSVI_DETAILS'), true);
				JToolBarHelper::custom( 'log.remove', 'csvi_delete_32', 'csvi_delete_32', JText::_('COM_CSVI_DELETE'), true);
				JToolBarHelper::custom( 'log.remove_all', 'csvi_delete_32', 'csvi_delete_32', JText::_('COM_CSVI_DELETE_ALL'), false);
				// JToolBarHelper::help('log.html', true);
				break;
		}

		// Display it all
		parent::display($tpl);
	}
}
?>
