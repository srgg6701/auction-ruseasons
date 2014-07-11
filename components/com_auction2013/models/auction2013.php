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
        $test=true;
        //commonDebug(__FILE__,__LINE__,$post, true);
        /*["bids"]=>                    "900"
          ["14e429a6cd5c1d774d06539dce403129"]=> "1"
          ["option"]=>                  "com_auction2013"
          ["virtuemart_product_id"]=>   "2702"
          ["task"]=>                    "makeBid"
          ["Itemid"]=>                  "125"
          ["virtuemart_category_id"]=>  "33"  */
        $table_bids = '#__dev_bids';
        // проверить, превышает ли ставка юзера минимально допустимую
        $db = JFactory::getDbo();
        $query = "SELECT MAX(sum) AS current_max_bid,
  TRUNCATE((UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60,1)
                                  AS 'minutes_rest',
  FROM_UNIXTIME(UNIX_TIMESTAMP(prods.auction_date_finish)+5*60) AS 'plus5min',
          DATE_FORMAT(prods.auction_date_finish,'%h:%i') AS 'expired'
     FROM #__dev_bids AS bids
LEFT JOIN #__virtuemart_products AS prods
          ON prods.virtuemart_product_id = bids.virtuemart_product_id
WHERE prods.virtuemart_product_id = " . $post['virtuemart_product_id'];
        try{
            $db->setQuery($query);
            $results = $db->loadAssoc();
            $minutes_rest = floatval($results['minutes_rest']);
            // если торги закончились
            if($minutes_rest<=0)
                return array('expired',$results['expired']);
            else{
                $current_max_bid = (int)$results['current_max_bid'];
                if(!$current_max_bid) $current_max_bid = 0;
                $plus5min = $results['plus5min'];
            }
        }catch(Exception $e){
            echo "<div>Ошибка проверки максимальной ставки:</div>";
            die("<div>".$e->getMessage()."</div>");
        }
        if($test) testSQL($query);
        // если входящая ставка больше текущей максимальной:
        if((int)$post['bids']>$current_max_bid){
            $bidder_user_id = JFactory::getUser()->id;
            /* todo: 1. разобраться с разнесением и отображением ЗБ и ставок
                     2. проверять время окончания аукциона, возвращать метку
            */
            $data = new stdClass();
            $data->virtuemart_product_id=$post['virtuemart_product_id'];
            $data->bidder_user_id = $bidder_user_id;
            $data->sum=$post['bids'];
            $data->datetime=date('Y-m-d H:i:s');
            try{
                JFactory::getDbo()->insertObject($table_bids, $data);
            }catch(Exception $e){
                echo "<div>Ошибка добавления ставки:</div>";
                die("<div>".$e->getMessage()."</div>");
            }
            // если до окончания торгов осталось меньше 5 мин, продлить
            if($minutes_rest<5){
                try{
                    $object = new stdClass();
                    $object->virtuemart_product_id = $post['virtuemart_product_id'];
                    $object->auction_date_finish = $plus5min;
                    JFactory::getDbo()->updateObject('#__virtuemart_products', $object, 'virtuemart_product_id');
                }catch(Exception $e){
                    echo "<div>Ошибка обновления времени окончания аукциона:</div>";
                    echo "<div>".$e->getMessage()."</div>";
                }
            }
            return true;
        }else{
            if($test) {
                echo "<div>Входящий бид (".(int)$post['bids'].")
                НЕ больше текущей ставки (".$current_max_bid.")</div>";
            }
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
