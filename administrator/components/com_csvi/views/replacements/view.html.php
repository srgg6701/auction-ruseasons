<?php
/**
 * Template types view
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 1764 2012-01-04 16:18:31Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Template types View
 *
* @package CSVI
 */
class CsviViewReplacements extends JView {

	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Template types display method
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
		// Load the template types
		$this->items = $this->get('Items');

		// Get the pagination
		$this->pagination = $this->get('Pagination');

		// Load the user state
		$this->state = $this->get('State');

		// Get the panel
		$this->loadHelper('panel');

		// Show the toolbar
		JToolBarHelper::title(JText::_('COM_CSVI_REPLACEMENTS'), 'csvi_replacement_48');
		JToolBarHelper::addNew('replacement.add');
		JToolBarHelper::editList('replacement.edit');
		JToolBarHelper::deleteList('', 'replacements.delete');
		//JToolBarHelper::help('about.html', true);

		// Display it all
		parent::display($tpl);
	}
}
?>