<?php
/**
 * Import file view
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
 * Import file View
 *
* @package CSVI
 */
class CsviViewImportFile extends JView {

	/**
	 * Handle the import
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

		// Get the template name
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$this->template_name = $template->get('template_name');

		// Toolbar
		$jinput->set('hidemainmenu', 1);
		JToolBarHelper::title(JText::_( 'COM_CSVI_IMPORTING' ), 'csvi_import_48');
		JToolBarHelper::custom('process.cancelimport', 'csvi_cancel_32', 'csvi_cancel_32', JText::_('COM_CSVI_CANCEL'), false);
		JToolBarHelper::custom('importfile.doimport', 'csvi_import_32', 'csvi_import_32', JText::_('COM_CSVI_IMPORT'), false);

		// Display it all
		parent::display($tpl);
	}
}
?>