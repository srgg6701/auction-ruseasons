<?php
/**
 * Maintenance view
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.raw.php 2275 2013-01-03 21:08:43Z RolandD $
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
		$task = strtolower(JRequest::getWord('task'));
		switch ($task) {
			case 'icecatsettings':
				echo $this->loadTemplate('icecat');
				break;
			case 'sortcategories':
				$this->languages = $this->get('Languages');
				echo $this->loadTemplate('sortcategories');
				break;
		}
	}
}
?>
