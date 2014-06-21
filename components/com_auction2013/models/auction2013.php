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
include_once JPATH_SITE.DS.'tests.php';
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
	function getItem()
	{
		if (!isset($this->_item))
		{
			if (!$this->_item) {
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				$query->select('*');
				$query->from('#__menu');
				$query->where('id = ' . (int)JRequest::getVar('Itemid'));
				$db->setQuery((string) $query);
				if (!$db->query()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
				$this->_item = $db->loadObject();
			}
		}
		return $this->_item;
	}

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
    /**
     * Добавить предмет в корзину юзера через VM
     */
    public function makePurchase($post) {
        /* ["option"]                   => "com_auction2013"
           ["task"]                     => "purchase"
           ["menuitemid"]               => "115"
            // на всякий случай массив, патаму что в VM так
           ["virtuemart_product_id"]    => array(1) {
                                            [0]=> "2708"
                                           }
           ["8dfc8567bfc27829cbc4328674ab6d74"]=> "1"   */
		//commonDebug(__FILE__,__LINE__,$post, true);
        $result=array();
        // Create and populate an object.
        $data = new stdClass();
        $data->user_id = JFactory::getUser()->id;
        $data->virtuemart_product_id=$post['virtuemart_product_id'][0];
        // status by default = 0
        // Insert the object into the user profile table.
        try{
            JFactory::getDbo()->insertObject('#__dev_shop_orders', $data);
            $result['msg']=JText::_('ОШИБКА... Заказ не оформлен');
            $result['type']='error';
        }catch(Exception $e){
            die('Error: '.$e->getMessage());
            $result['msg']=JText::_('ОШИБКА... Заказ не оформлен');
            $result['type']='error';
        }
        return $result;
    }
}
