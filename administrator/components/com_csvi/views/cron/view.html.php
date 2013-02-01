<?php
/**
 * Cron view
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
 * Cron View
 *
* @package CSVI
 */
class CsviViewCron extends JView {

	/**
	 * Cron features
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
	public function display($tpl = null) {

		// Load the posted data
		$jinput = JFactory::getApplication()->input;
		$option = $jinput->get('option');
		$data = $jinput->get($option.'.data', array(), 'array');
		$from = $jinput->get('from');

		// The basics of the cronline
		$this->cronline = 'php "'.JPATH_COMPONENT_ADMINISTRATOR.'/helpers/cron.php" username="" passwd="" ';

		// Construct the correct cron
		switch ($from) {
			case 'process':
				if (!empty($data)) {
					// Load the template handler
					$this->loadHelper('template');

					// Construct the cronline
					$this->cronline .= $this->get('CronLine');
				}
				else $this->cronline = JText::_('COM_CSVI_NO_CRON_DATA_FOUND');
				JToolBarHelper::custom('process', 'csvi_process_32.png', 'csvi_process_32.png', JText::_('COM_CSVI_PROCESS'), false);
				break;
			case 'maintenance':
				// Construct the cronline
				$this->cronline .= $this->get('CronLineMaintenance');
				JToolBarHelper::custom('maintenance', 'csvi_maintenance_32.png', 'csvi_maintenance_32.png', JText::_('COM_CSVI_MAINTENANCE'), false);
				break;
		}

		// Get the panel
		$this->loadHelper('panel');

		// Show the toolbar
		JToolBarHelper::title(JText::_('COM_CSVI_CRON'), 'csvi_cron_48');

		// Display it all
		parent::display($tpl);
	}
}
?>