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
include_once JPATH_COMPONENT.DS.'helpers'.DS.'stuff.php';
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
        $table_bids         = '#__dev_bids';        // ставки
        $table_user_bids    = '#__dev_user_bids';   // заочные биды
        // проверить, превышает ли ставка юзера текущий максимальный заочный бид
        $db = JFactory::getDbo();
        $query = "SELECT TRUNCATE(prices.product_price,0) AS price,
                       MAX(sum) AS 'current_max_sum',
                       MAX(value) AS 'current_max_bid',
              bids.bidder_user_id AS 'previous_bidder_id',
  TRUNCATE((UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60,1)
                                  AS 'minutes_rest',
  FROM_UNIXTIME(UNIX_TIMESTAMP(prods.auction_date_finish)+5*60) AS 'plus5min',
          DATE_FORMAT(prods.auction_date_finish,'%h:%i') AS 'expired'
     FROM #__virtuemart_products AS prods
LEFT JOIN $table_bids AS bids
          ON prods.virtuemart_product_id = bids.virtuemart_product_id
LEFT JOIN $table_user_bids AS user_bids
          ON user_bids.virtuemart_product_id = bids.virtuemart_product_id
             AND bids.bidder_user_id = user_bids.bidder_id
INNER JOIN #__virtuemart_product_prices AS prices
          ON prices.virtuemart_product_id = prods.virtuemart_product_id
WHERE prods.virtuemart_product_id = " . $post['virtuemart_product_id'];
        /*SELECT MAX(sum) AS 'current_max_bid',
  TRUNCATE((UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60,1)
                                  AS 'minutes_rest',
  FROM_UNIXTIME(UNIX_TIMESTAMP(prods.auction_date_finish)+5*60) AS 'plus5min',
          DATE_FORMAT(prods.auction_date_finish,'%h:%i') AS 'expired'
     FROM #__virtuemart_products AS prods
LEFT JOIN #__dev_bids AS bids
          ON prods.virtuemart_product_id = bids.virtuemart_product_id
WHERE prods.virtuemart_product_id = */
        //
        try{
            $db->setQuery($query);
            $results = $db->loadAssoc();
            if($test){
                testSQL($query,__FILE__, __LINE__);
                commonDebug(__FILE__,__LINE__,$results);
            }
            $minutes_rest = floatval($results['minutes_rest']);
            // если торги закончились
            if($minutes_rest<=0)
                return array('expired',$results['expired']);
            else{
                $price = (int)$results['price'];
                $current_max_bid    = (int)$results['current_max_bid'];
                $current_max_sum    = (int)$results['current_max_sum'];
                $previous_bidder_id = (int)$results['previous_bidder_id'];
                if(!$current_max_bid) $current_max_bid = 0;
                $plus5min = $results['plus5min'];
            }
        }catch(Exception $e){
            echo "<div>Ошибка проверки максимальной ставки:</div>";
            die("<div>".$e->getMessage()."</div>");
        }
        if($test) {
            echo "<pre>" . __file__ . ':' . __line__ . '<br>';
            echo '$post[bids]: ';
            var_dump((int)$post['bids']);
            echo '$current_max_bid:';
            var_dump($current_max_bid);
            echo "</pre>";
            //die();
        }
        $bidder_user_id = JFactory::getUser()->id;
        $step = AuctionStuff::getBidsStep($post['bids']);
        // проверить, нет ли уже такой записи:
        // todo: прояснить вопрос - нужна ли эта проверка вообще, т.к. выше оно проверяет, является ли входящая ставка больше последней добавленной
        $query = "SELECT COUNT(*)
  FROM $table_bids AS bids
 WHERE virtuemart_product_id = ".$post['virtuemart_product_id']."
   AND bidder_user_id = $bidder_user_id
   AND sum = ( SELECT sum FROM $table_bids      AS bids2,
                               $table_user_bids AS ubids
                WHERE ubids.bid_id = bids.id
                  AND ubids.value = ".$post['bids'].")";
        $db->setQuery($query);
        $result = $db->loadResult();
        if(!$test)
            if($result) return true; // делаем вид, что просто добавили запись
        else{
            testSQL($query,__FILE__, __LINE__);
            commonDebug(__FILE__,__LINE__,$result);
            if($result) die('Запись уже существует');
            else echo('Запись новая');
        }
        /**
            добавить запись в таблицу ставок
            см. файл ставки.xlsx и схему Bids в https://www.draw.io/?#G0B1Hmj89fLFA6UDlnbE9lUk4yaWM */
        $user_bid_value = (int)$post['bids']; // назначить по умолчанию
        // будем добавлять записи
        $db = JFactory::getDbo();
        $data = new stdClass();
        $data->virtuemart_product_id=$post['virtuemart_product_id'];
        //
        if($current_max_bid){ // ставки уже были
            // проставить диапазон ставок от текущей последней до предыдущего максимального ЗБ
            $svalue = $current_max_sum;
            $turn = 1;
            $break=false;
            while($svalue<=$current_max_bid){
                /**
                    подставить id игрока - текущий/предыдущий в зависимости
                    от чётности записи  */
                $bidder_id=($turn%2)? $bidder_user_id:$previous_bidder_id;
                $data->bidder_user_id = $bidder_id;
                // расчитать величину текущей ставки
                $svalue+=$step;
                /**
                    если расчётная ставка превысила входящую, - выставить ставку за
                    предыдущего игрока (цикл работает в рамках его ЗБ) */
                if($svalue>$user_bid_value){
                    //echo "<div style='color:orange; font-weight: bold;'>Последняя ставка (проставляется за предыдущего игрока):</div>";
                    $data->bidder_user_id = $bidder_user_id;
                    $break=true;
                }elseif($svalue==$current_max_bid&&$turn%2){
                    /**
                    если расчётная ставка сравнялась с текущим макс. бидом
                    и приходится на текущего игрока, записать ставку на него */
                    $data->bidder_user_id = $previous_bidder_id;
                    $break=true;
                    //echo "<div style='color:violet; font-weight: bold;'>Последняя ставка (==макс. бид предыдущего игрока):</div>";
                }
                //echo "<div";if($turn%2) echo " style='font-weight:bold'";echo ">ставка: $svalue</div>";
                // добавить запись в таблицу ставок
                $data->sum=$svalue;
                $data->datetime=date('Y-m-d H:i:s');
                try{
                    $db->insertObject($table_bids, $data);
                    // try it here!
                }catch(Exception $e){
                    echo "<div>Ошибка добавления ставки(".__LINE__."):</div>";
                    echo "<div>".$e->getMessage()."</div>";
                }
                $turn++;
                if($break) break;
            }
            /*echo "<div style='color:blue'>current_max_bid: $current_max_bid</div>";
            echo "<div style='color:red'>user_bid_value: $user_bid_value</div>";
            die(__file__.': '.__line__);*/
            /*$user_bid_value = ($user_bid_value>$current_max_bid)? // бид юзера больше всех текущих?
                $current_max_bid // взять за базовое значение текущий максимальный бид
                : $current_max_sum; // взять за базовое значение текущую максимальную ставку
            $user_bid_value+=$step; // увеличить на шаг торгов*/
        }else{
            $data->sum=$price;
            $data->datetime=date('Y-m-d H:i:s');
            try{
                $db->insertObject($table_bids, $data);
                // try it here!
            }catch(Exception $e){
                echo "<div>Ошибка добавления ставки(".__LINE__."):</div>";
                echo "<div>".$e->getMessage()."</div>";
            }
        }
        // добавить/обновить запись в таблице бидов
        // проверить, есть ли уже запись для данного игрока и предмета
        $query = "SELECT id FROM #__dev_user_bids
             WHERE bidder_id = " . $bidder_user_id . "
               AND virtuemart_product_id = " . $post['virtuemart_product_id'];
        $db->setQuery($query);
        // если есть - обновить
        if($record_id=$db->loadResult()){
            $update = new stdClass();
            $update->id = $record_id; // pkey
            $update->value=$post['bids'];
            try{
                echo "<div>Ошибка обновления бида(".__LINE__."):</div>";
                $db->updateObject($table_user_bids, $update, 'id');
            }catch(Exception $e){
                echo "<div>".$e->getMessage()."</div>";
            }
        }else{ // добавить
            $insert = new stdClass();
            $insert->virtuemart_product_id=$post['virtuemart_product_id'];
            $insert->bidder_id = $bidder_user_id;
            $insert->value=$post['bids'];
            try{
                $db->insertObject($table_user_bids, $insert);
            }catch(Exception $e){
                echo "<div>Ошибка добавления бида(".__LINE__."):</div>";
                die("<div>".$e->getMessage()."</div>");
            }
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
            //die('Error: '.$e->getMessage());
            $result['msg']=JText::_('ОШИБКА... Заказ не оформлен');
            $result['type']='error';
        }
        return $result;
    }
}
