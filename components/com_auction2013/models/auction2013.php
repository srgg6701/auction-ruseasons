<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_component_name
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

/**
 * Content Component Model
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since 1.5
 */
class Auction2013ModelAuction2013 extends JModelLegacy
{
	protected $_item;

	/**
	 * Model context string.
	 *
	 * @var		string
	 */

	/**
	 * Get the data for a layout.
	 *
	 * @return	object
	 */
	/*function &getItem()
	{
		if (!isset($this->_item))
		{
			$cache = JFactory::getCache('com_banners', '');

			$id = $this->getState('banner.id');

			$this->_item =  $cache->get($id);

			if ($this->_item === false) {
				// redirect to banner url
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				$query->select(
					'a.clickurl as clickurl,'.
					'a.cid as cid,'.
					'a.track_clicks as track_clicks'
					);
				$query->from('#__banners as a');
				$query->where('a.id = ' . (int) $id);

				$query->join('LEFT', '#__banner_clients AS cl ON cl.id = a.cid');
				$query->select('cl.track_clicks as client_track_clicks');

				$db->setQuery((string) $query);

				if (!$db->query()) {
					JError::raiseError(500, $db->getErrorMsg());
				}

				$this->_item = $db->loadObject();
				$cache->store($this->_item, $id);
			}
		}

		return $this->_item;
	}*/

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
}
