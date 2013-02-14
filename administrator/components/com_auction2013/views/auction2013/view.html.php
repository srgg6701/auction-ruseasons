<?php	
/**
 * @version     2.1.0
 * @package     com_auction2013
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of application.
 */
class Auction2013ViewAuction2013 extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	public $fields;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{	
		// call: /joomla/application/component/view.php
		// there will be required the model that been set here by default
		// further it will call:
			// model()->getState() 
			// model()->getItems()
		// check model to ensure there they are!
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		// 
		$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar($this->_layout);
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar($layout=false)
	{	
		require_once JPATH_COMPONENT . '/helpers/auction2013.php';
		
		//var_dump($this); // die();
		$state	= $this->get('State');
		//var_dump($state); 
		$canDo = Auction2013Helper::getActions($state->get('filter.category_id'));
		$user = JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_AUCTION2013'), 'auction.png');
		if (count($user->getAuthorisedCategories('com_auction2013', 'core.create')) > 0)
		{
			JToolBarHelper::addNew('сontroller_to_add_single_item_name.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolBarHelper::editList('сontroller_to_edit_single_item_name.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			if ($this->state->get('filter.state') != 2)
			{
				JToolBarHelper::divider();
				JToolBarHelper::publish('сontrollers_to_publish_items.publish', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::unpublish('сontrollers_to_unpublish_items.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($this->state->get('filter.state') != -1)
			{
				JToolBarHelper::divider();
				if ($this->state->get('filter.state') != 2)
				{
					JToolBarHelper::archiveList('сontrollers_to_archive_items.archive');
				}
				elseif ($this->state->get('filter.state') == 2)
				{
					JToolBarHelper::unarchiveList('сontrollers_to_publish_items.publish');
				}
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::checkin('сontrollers_to_checkin_items.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'сontrollers_to_delete_items.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolBarHelper::trash('сontrollers_to_trash_items.trash');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_auction2013');
			JToolBarHelper::divider();
		}
		JToolBarHelper::help('JHELP_COMPONENTS_VIEW_NAME_LAYOUT_NAME');
	}
}
