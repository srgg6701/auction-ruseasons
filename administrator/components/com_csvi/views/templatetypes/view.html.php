<?php
/**
 * Template types view
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
 * Template types View
 *
* @package CSVI
 */
class CsviViewTemplatetypes extends JView {

	/**
	* Items to be displayed
	*/
	protected $items;

	/**
	* Pagination for the items
	*/
	protected $pagination;

	/**
	* User state
	*/
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
		$this->templatetypes = $this->get('Items');

		// Get the pagination
		$this->pagination = $this->get('Pagination');

		// Load the user state
		$this->state = $this->get('State');

		// Get the panel
		$this->loadHelper('panel');

		// Show the toolbar
		JToolBarHelper::title(JText::_('COM_CSVI_TEMPLATETYPES'), 'csvi_templates_48');
		JToolBarHelper::custom('templatetype.add', 'new.png', 'new_f2.png','JTOOLBAR_NEW', false);
		JToolBarHelper::custom('templatetype.edit', 'edit.png', 'edit_f2.png','JTOOLBAR_EDIT', true);
		//JToolBarHelper::help('about.html', true);

		// Display it all
		parent::display($tpl);
	}
}
?>