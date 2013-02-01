<?php
/**
 * Import results view
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.result.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Import results view
 *
* @package CSVI
 */
class CsviViewProcess extends JView {

	/**
	 * Process view
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
	public function display($tpl = null) {
		$jinput = JFactory::getApplication()->input;
		// Load the settings
		$this->loadHelper('settings');
		$settings = new CsviSettings();

		if ($settings->get('log.log_store', 1)) {
			// Load the results from the log
			$this->logresult = $this->get('Stats', 'log');
			
			// Get the run time
			$session = JFactory::getSession();
			$runtime = $session->get('com_csvi.runtime');
			if ($runtime > 0) $runtime = time()-$runtime;
			$this->assignRef('runtime', JText::sprintf('COM_CSVI_RUNTIME_IMPORT', number_format($runtime/60, 2), $runtime));
			// Reset the run time
			$session->set('com_csvi.runtime', null);
			
			// Get the toolbar title
			JToolBarHelper::title(JText::_('COM_CSVI_'.$this->logresult['action'].'_RESULT'), 'csvi_'.$this->logresult['action'].'_48');
		}
		else $this->logresult = false;
		
		// Get the panel
		$this->loadHelper('panel');

		// Display it all
		parent::display($tpl);
	}
}
?>
