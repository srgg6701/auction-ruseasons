<?php
/**
 * Import file cron view
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.cron.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Import file View
 *
* @package CSVI
 */
class CsviViewImportFile extends JView {
	
	/**
	 * Cron import view 
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
		$jinput = JFactory::getApplication()->input;
		if (!$jinput->get('error', false, 'bool')) {
			// Process the data
			$this->get('ProcessData');
		}
		
		// Assign the data
		$this->assignRef('logresult', $this->get('Stats', 'log')); 
		
		// Display it all
		parent::display($tpl);
	}
}
?>