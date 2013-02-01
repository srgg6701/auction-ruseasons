<?php
/**
 *
 * Replacement view
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 1764 2012-01-04 16:18:31Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
jimport('joomla.application.component.view');

/**
 * Generates the replacement editing screen
 *
 * @package CSVI
 * @author 	RolandD
 * @since 	1.0
 */
class CsviViewReplacement extends JView {

	/**
	 * Items to be displayed
	 */
	protected $item;

	/**
	 * Form for editing
	 */
	protected $form;

	/**
	 * User state
	 */
	protected $state;

	/**
	 * Show a product edit screen
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string $tpl template file to use
	 * @return 		void
	 * @since 		1.0
	 */
	public function display($tpl = null) {

		// Load the data
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Toolbar for product editing
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return 		void
	 * @since 		1.0
	 */
	protected function addToolbar() {
		// Hide the mainmenu
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		JToolBarHelper::title(JText::_('COM_CSVI_PAGE_'.($checkedOut ? 'VIEW_REPLACEMENT' : ($isNew ? 'ADD_REPLACEMENT' : 'EDIT_REPLACEMENT'))), 'csvi_replacement_48');
		if (!$checkedOut) {
			JToolBarHelper::apply('replacement.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('replacement.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('replacement.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// If an existing item, can save to a copy.
		if (!$isNew) {
			JToolBarHelper::custom('replacement.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('replacement.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('replacement.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
?>