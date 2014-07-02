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
     * Проверить доступность предмета
     */
    function checkItemAccessibility($virtuemart_product_id){
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = "SELECT COUNT(*) FROM #__dev_shop_orders WHERE virtuemart_product_id = ".$virtuemart_product_id;
        $db->setQuery($query);
        try{
            $res=$db->loadResult();
            //echo "<div>res = $res</div>";
            //testSQL($query);die();
            return $res;
        }catch (Exception $e){
            die($e->getMessage());
        }
    }
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
     * Сделать ставку
     */
    public function makeBid($post){
        commonDebug(__FILE__,__LINE__,$post, true);
        /*  ["bid_sum"]     => "125000"
            ["bid_agree"]   => "on"
            ["b379e7a91e6a5b624c9a828be80dc8ac"]=> "1"
            ["option"]      => "com_auction2013"
            ["task"]        => "makeBid"
            ["virtuemart_product_id"]=> "2772"  */
        // получить резервную цену
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('min_price'));
        $query->from($db->quoteName('#__dev_sales_price'));
        $query->where($db->quoteName('virtuemart_product_id') . ' = '. $post['virtuemart_product_id']);
        $db->setQuery($query);
        if((int)$post['bid_sum']>(int)$db->loadResult()){
            $table_bids = '#__dev_bids';
            $bidder_user_id = JFactory::getUser()->id;
            // проверить, не выставил ли кто-нибудь из юзеров бОльшую ставку:
            $query = $db->getQuery(true);
            $query->select($db->quoteName('MAX(sum)>=').$post['bid_sum']);
            $query->from($db->quoteName($table_bids));
            $query->where($db->quoteName('virtuemart_product_id') . ' = '. $post['virtuemart_product_id']);
            $db->setQuery($query);
            try{
                if((int)$db->loadResult())
                    return 'too_low';
            }catch(Exception $e){
                echo "<div>Ошибка проверки максимальной ставки:</div>";
                die("<div>".$e->getMessage()."</div>");
            }
            $data = new stdClass();
            $data->virtuemart_product_id=$post['virtuemart_product_id'];
            $data->bidder_user_id = $bidder_user_id;
            $data->sum=$post['bid_sum'];
            $data->datetime=date('Y-m-d H:i:s');
            try{
                JFactory::getDbo()->insertObject($table_bids, $data);
                return true;
            }catch(Exception $e){
                echo "<div>Ошибка добавления ставки:</div>";
                die("<div>".$e->getMessage()."</div>");
            }
        }else{
            return false;
        }
    }
    /**
     * Добавить предмет в корзину юзера через VM
     */
    public function makePurchase($post) {
        $result=array();
        // если предмет кем-то только что куплен:
        if($this->checkItemAccessibility($post['virtuemart_product_id'][0])){
            $result['msg']=JText::_('Предмет недоступен...');
            $result['type']='warning';
            return $result;
        }
        /* ["option"]                   => "com_auction2013"
           ["task"]                     => "purchase"
           ["menuitemid"]               => "115"
            // на всякий случай массив, патаму что в VM так
           ["virtuemart_product_id"]    => array(1) {
                                            [0]=> "2708"
                                           }
           ["8dfc8567bfc27829cbc4328674ab6d74"]=> "1"   */
		//commonDebug(__FILE__,__LINE__,$post, true);
        // Create and populate an object.
        $data = new stdClass();
        $data->user_id = JFactory::getUser()->id;
        $data->virtuemart_product_id=$post['virtuemart_product_id'][0];
        $data->event_datetime = date('Y-m-d H:i:s');
        // status by default = 0
        // Insert the object into the user profile table.
        try{
            JFactory::getDbo()->insertObject('#__dev_shop_orders', $data);
            $result['msg']=JText::_('Заказ оформлен');
            $result['type']='notice';

            if($_SERVER['HTTP_HOST']!='localhost'){
                // отправить сообщение админам:
                // get all admin users
                $query = 'SELECT name, email, sendEmail' .
                    ' FROM #__users' .
                    ' WHERE sendEmail=1';

                $db=JFactory::getDbo();
                $db->setQuery( $query );
                $rows = $db->loadObjectList();
                $emailBodyAdmin="Поступила новая заявка на приобретение предмета в вашем магазине.";
                $rname = "Русские Сезоны";
                // Send mail to all superadministrators id
                foreach( $rows as $row )
                {
                    JFactory::getMailer()->sendMail(
                        "noreply@auction-ruseasons.ru",
                        "Магазин антиквариата \"$rname\"",
                        $row->email,
                        "Новый заказ на покупку предмета в магазине \"$rname\"",
                        $emailBodyAdmin );
                }
            }
        }catch(Exception $e){
            die('Error: '.$e->getMessage());
            $result['msg']=JText::_('ОШИБКА... Заказ не оформлен');
            $result['type']='error';
        }
        return $result;
    }
}
