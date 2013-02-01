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
class CsviViewLogdetails extends JView {

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
		// Load the items to display
		$this->logmessage = $this->get('Items');
		$this->setModel(JModel::getInstance('log', 'CsviModel'));
		$this->logresult = $this->get('Stats', 'log');

		// Load the pagination
		$this->pagination = $this->get('Pagination');

		// Load the user state
		$this->state = $this->get('State');

		// Set the Run ID
		$jinput = JFactory::getApplication()->input;
		$this->run_id = $jinput->get('run_id', 0, 'int');

		// Set the actions
		$this->list['actions'] = $this->get('Actions');
		$this->list['results'] = $this->get('Results');

		// Get the panel
		$this->loadHelper('panel');

		// Add toolbar
		JToolBarHelper::title(JText::_('COM_CSVI_LOG_DETAILS'), 'csvi_logdetails_48');
		JToolBarHelper::custom('logdetails.cancel', 'csvi_cancel_32', 'csvi_cancel_32', JText::_('COM_CSVI_BACK'), false);

		// Display it all
		parent::display($tpl);
	}
}
?>
