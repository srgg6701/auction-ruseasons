<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Menu List Model for Menus.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class Auction2013ModelAuction2013 extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array	An optional associative array of configuration settings.
	 *
	 * @see		JController
	 * @since   1.6
	 * Также указывает столбцы сортировки данных
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{	
			$config['filter_fields'] = array(
				'id', 'a.id',
				'abstract', 'a.abstract',
			);
		}
		parent::__construct($config);
	}
	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{			

		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();
		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
		// Add the items to the internal cache.
		$this->cache[$store] = $items;
		return $this->cache[$store];
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select all fields from the table.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from($db->quoteName('#__auction2013').' AS a');
		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('filter_order')) . ' ' . $db->getEscaped($this->getState('filter_order_Dir', 'ASC')));
		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * Также имеет отношение к сортировке отображения данных. 
	 * См. здесь: http://docs.joomla.org/Adding_sortable_columns_to_a_table_in_a_component
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		if(!$filter_order=JRequest::getCmd('filter_order'))
			$filter_order='id';
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);
		// List state information.
		parent::populateState('a.id', 'asc');
	}
}
