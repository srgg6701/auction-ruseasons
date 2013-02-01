<?php
/**
 * Cron controller
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: cron.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Cron Controller
 *
 * @package    CSVI
 */
class CsviControllerCron extends JController {

	/**
	 * Prepare for cron line generation
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function cron() {
		// Store the form fields
		$jinput = JFactory::getApplication()->input;
		$data	= $jinput->post->get('jform', array(), 'array');
		$jinput->set('com_csvi.data', $data);

		// Create the view object
		$view = $this->getView('cron', 'html');

		// Standard model
		$view->setModel( $this->getModel( 'cron', 'CsviModel' ), true );

		// Now display the view
		$view->display();
	}
}
?>
