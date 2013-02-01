<?php
/**
 * Install view
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
 * Install View
 *
* @package CSVI
 */
class CsviViewInstall extends JView {

	/**
	 * Display the installation screen
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
	public function display($tpl = null) {
		// Load the stylesheet
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'administrator/components/com_csvi/assets/css/install.css');

		// Load the installed version
		$this->selectversion = $this->get('Version');
		$this->newversion = CSVI_VERSION;

		// Options of extra tasks to do during installation
		$this->installoptions = array();
		$this->installoptions[] = JHtml::_('select.option', 'availablefields', JText::_('COM_CSVI_UPDATEAVAILABLEFIELDS_LABEL'));
		$this->installoptions[] = JHtml::_('select.option', 'sampletemplates', JText::_('COM_CSVI_INSTALLDEFAULTTEMPLATES_LABEL'));

		// Show the toolbar
		JToolBarHelper::title(JText::_('COM_CSVI_INSTALL'), 'csvi_install_48');
		//JToolBarHelper::help('install.html', true);

		// Display it all
		parent::display($tpl);
	}
}
?>