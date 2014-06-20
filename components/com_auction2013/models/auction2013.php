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
        // Дополнительные поля для модели VM:
        // <input type="hidden" name="view" value="cart"/>
        // task = add
        /*  ["option"]=> "com_auction2013"
            ["task"]=> "purchase"
            ["product_id"]=> "2708"
            ["4698319123368c7b56a8c213973a888f"]=>  "1" */
		$mainframe = JFactory::getApplication();
        $path = DS .   'components' .
                DS .   'com_virtuemart' .
                DS .   'helpers';
        require_once JPATH_ADMINISTRATOR .$path . DS .   'config.php';
        require_once JPATH_SITE .$path . DS .   'cart.php';
		$result=array();
        $result['msg']=JText::_('COM_VIRTUEMART_PRODUCT_NOT_ADDED_SUCCESSFULLY');
        $result['type']='error';
        if ($cart = VirtueMartCart::getCart()) {
            commonDebug(__FILE__, __LINE__, $post);
            // сохранить данные предмета в корзине и сразу же оформить как заказ:
            $success=true;
            // отключить ненужную проверку для избежания тупика:
            $cart->skip_stockhandle_checking=true;
            if ($cart->add($post['product_id'],$success)) {
                $sessionCart = JFactory::getSession()->get('vmcart', null, 'vm');
                commonDebug(__FILE__, __LINE__, $sessionCart, true);
                $result['msg']='Заявка на покупку предмета оформлена';
				$result['type']='';
			} 
		}
        return $result;
    }
}
