<?php
/**
 * Maintenance view
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.json.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Maintenance View
 *
* @package CSVI
 */
class CsviViewMaintenance extends JView {

	/**
	 * Handle the JSON calls for maintenance
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.3
	 */
	function display($tpl = null) {
		$jinput = JFactory::getApplication()->input;
		$task = strtolower($jinput->get('task'));
		switch ($task) {
			case 'icecatindex':
			case 'updateavailablefields':
				JToolBarHelper::custom('cancelimport', 'csvi_cancel_32', 'csvi_cancel_32', JText::_('COM_CSVI_CANCEL'), false);
				// Display it all
				parent::display($tpl);
				break;
			case 'icecatsingle':
				$this->get('IcecatSingle');
				$result['view'] = '';
				// Get the number of records processed
				$result['records'] = $jinput->get('linesprocessed', 0, 'int');
				if ($jinput->get('finished', false, 'bool')) {
					$result['process'] = false;
					$result['url'] = JURI::root().'administrator/index.php?option='.$jinput->get('option').'&task=logdetails.logdetails&run_id[]='.$jinput->get('run_id', 0, 'int');
				}
				else {
					$result['process'] = true;
				}
				// Output the results
				echo json_encode($result);
				break;
			case 'updateavailablefieldssingle':
				$continue = $this->get('AvailableFieldsSingle', 'availablefields');
				$result['view'] = '';
				// Get the number of records processed
				$result['table'] = $jinput->get('updatetable', '', 'string');
				if (!$continue) {
					$result['process'] = false;
					$result['url'] = JURI::root().'administrator/index.php?option='.$jinput->get('option').'&task=logdetails.logdetails&run_id='.$jinput->get('run_id', 0, 'int');

					// Store the log results
					$this->get('finishProcess');
				}
				else {
					$result['process'] = true;
				}
				// Output the results
				echo json_encode($result);
				break;
		}
	}
}
?>
