<?php
/**
 * Export file view
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Export file View
 *
* @package CSVI
 */
class CsviViewExportfile extends JView {

	/**
	 * Export file display method
	 * 
	 * @copyright 
	 * @author 		RolandD
	 * @todo 
	 * @see			CsviModelExportfile::getProcessData() 
	 * @access 		public
	 * @param 
	 * @return 
	 * @since 		4.0
	 */
	public function display($tpl = null) {
		$jinput = JFactory::getApplication()->input;
		// Process the export data
		$result = $this->get('ProcessData');
		
		if (!$jinput->get('cron', false, 'bool')) {
			// Load the results
			$logresult = $this->get('Stats', 'log');
			$this->assignRef('logresult', $logresult);
	
			// Load the run ID
			$jinput = JFactory::getApplication()->input;
			$csvilog = $jinput->get('csvilog', null, null);
			$this->assignRef('run_id', $csvilog->getId());
		}
		
		// Display it all
		parent::display($tpl);
	}
}
?>