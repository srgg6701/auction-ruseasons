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
    public $bid=false;

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
        $query = "SELECT COUNT(*) FROM #__dev_shop_orders WHERE virtuemart_product_id = $virtuemart_product_id";
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
     * См. иллюстрации в файле "ставки.xlsx"
     */
    public function makeUserBid( $post,
                             $current_bidder_id=NULL // может передаваться 'id' "виртуального игрока"
                           ){
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
        if(!$current_bidder_id)
            $current_bidder_id = JFactory::getUser()->id;
        $virtuemart_product_id = $post['virtuemart_product_id'];
        // проверить, превышает ли ставка юзера текущий максимальный заочный бид
        $db = JFactory::getDbo();
        $query = "SELECT prods_ru.product_name,
( SELECT COUNT(*)
  FROM #__dev_bids
 WHERE virtuemart_product_id = $virtuemart_product_id )
                                   AS 'bids_count',
  TRUNCATE(prices.product_price,0) AS 'price',
            sales_price.              `min_price`,
                       MAX(sum)    AS 'current_max_sum',
                       MAX(value)  AS 'current_max_bid',
              ( SELECT bidder_id FROM #__dev_user_bids
    WHERE virtuemart_product_id = $virtuemart_product_id
    ORDER BY `value` DESC LIMIT 1)
                AS 'previous_bidder_id',
  TRUNCATE((UNIX_TIMESTAMP(prods.auction_date_finish)-UNIX_TIMESTAMP(NOW()))/60,1)
                                  AS 'minutes_rest',
  FROM_UNIXTIME(UNIX_TIMESTAMP(prods.auction_date_finish)+5*60) AS 'plus5min',
          DATE_FORMAT(prods.auction_date_finish,'%h:%i') AS 'expired'
     FROM #__virtuemart_products        AS prods
INNER JOIN #__virtuemart_products_ru_ru AS prods_ru
          ON prods_ru.virtuemart_product_id = prods.virtuemart_product_id
LEFT JOIN $table_bids                   AS bids
          ON prods.virtuemart_product_id = bids.virtuemart_product_id
LEFT JOIN $table_user_bids              AS user_bids
          ON user_bids.virtuemart_product_id = bids.virtuemart_product_id
             AND bids.bidder_user_id = user_bids.bidder_id
INNER JOIN #__virtuemart_product_prices AS prices
          ON prices.virtuemart_product_id = prods.virtuemart_product_id
LEFT JOIN #__dev_sales_price            AS sales_price
          ON sales_price.virtuemart_product_id = prods.virtuemart_product_id
WHERE prods.virtuemart_product_id = $virtuemart_product_id";
        //
        try{
            $db->setQuery($query);
            $results = $db->loadAssoc();
            if($test){
                testSQL($query,__FILE__, __LINE__);
                commonDebug(__FILE__,__LINE__,$results);
            }
        }catch(Exception $e){
            echo "<div>Ошибка проверки максимальной ставки:</div>";
            die("<div>".$e->getMessage()."</div>");
        }
        $minutes_rest = floatval($results['minutes_rest']);
        // если торги закончились
        if($minutes_rest<=0){
            $return = array('expired',$results['expired']);
            if($test){
                showTestMessage("Торги просрочены",__FILE__,__LINE__);
                commonDebug(__FILE__,__LINE__,$return);
            }
            else
                return $return;
        }else{
            $price              = (int)$results['price'];
            $min_price          = (int)$results['min_price'];
            $current_max_bid    = (int)$results['current_max_bid'];
            $current_max_sum    = (int)$results['current_max_sum'];
            $previous_bidder_id = (int)$results['previous_bidder_id'];
            $product_name       = $results['product_name'];
            $user_bid_value     = (int)$post['bids']; // назначить по умолчанию

            if(!$price)             $price = 0;
            if(!$min_price)         $min_price = 0;
            if(!$current_max_bid)   $current_max_bid = 0;
            if(!$current_max_sum)   $current_max_sum = 0;

            $plus5min = $results['plus5min'];
            if($test) {
                echo "<pre>" . __FILE__ . ':' . __LINE__ . '<br>';
                echo '$post[bids]: ';
                var_dump($user_bid_value);
                echo '$current_max_bid:';
                var_dump($current_max_bid);
                echo "</pre>"; // die('line: '.__LINE__);
            }
        }
        // проверить, нет ли уже такой записи:
        // todo: прояснить вопрос - нужна ли эта проверка вообще, т.к. выше оно проверяет, является ли входящая ставка больше последней добавленной
        $query = "SELECT COUNT(*)
  FROM $table_bids AS bids
 WHERE virtuemart_product_id = ".$virtuemart_product_id."
   AND bidder_user_id = $current_bidder_id
   AND sum = ( SELECT sum FROM $table_bids      AS bids2,
                               $table_user_bids AS ubids
                WHERE ubids.bid_id = bids.id
                  AND ubids.value = $user_bid_value)";
        $db->setQuery($query);
        $result = $db->loadResult();
        if(!$test){
            if($result) return true; // делаем вид, что просто добавили запись
        }else{
            //testSQL($query,__FILE__, __LINE__);
            //commonDebug(__FILE__,__LINE__,$result);
            if($result) die('Запись уже существует');
            else echo('<div>Запись новая</div>');
        }
        // будем добавлять записи
        $db = JFactory::getDbo();
        $data = new stdClass();
        $data->virtuemart_product_id=$virtuemart_product_id;
        /**
        добавить запись в таблицу ставок
        см. файл ставки.xlsx и схему Bids в https://www.draw.io/?#G0B1Hmj89fLFA6UDlnbE9lUk4yaWM */
        if($current_max_bid){ // ставки уже были
            // ставка юзера не превысила последнюю, - вернуть его назад с предупреждением
            if($user_bid_value<=$current_max_sum) {
                if($test) showTestMessage("Ставка игрока меньше последней.", __FILE__, __LINE__);
                return false;
            }
            /**
             * проставить диапазон ставок от текущей последней до предыдущего максимального ЗБ
             * ВНИМАНИЕ! Возможна ситуация, когда входящая ставка не будет укладываться в реальный
             * шаг торгов. Ниже она приводится к нему присвоением начальному значению последней максимальной ставке
            */
            $bid_counted_value = $current_max_sum; // инициализировать начальную ставку
            $turn = 1;
            $break=false;
            while(true){ // цикл прерывается по условию; в конце - подстраховочный ограничитель в 250 итераций
                // todo: убрать ограничитель итераций $turn:250
                /** 
                 получить шаг торгов (рассчитывается на основе текущей) 
                 ставки, а не начальной стоимости товара. */
                $step = AuctionStuff::getBidsStep($bid_counted_value);
                // сохранить предыдущую ставку на случай отмены последней ставки (см. ниже)
                $last_bid_counted_value=$bid_counted_value;
                // расчитать величину текущей ставки
                $bid_counted_value+=$step;
                /**
                подставить id игрока - текущий/предыдущий в зависимости
                от чётности записи  */
                $bidder_id=($turn%2>0)? $current_bidder_id:$previous_bidder_id;
                /**
                 УСЛОВИЯ ВЫХОДА ИЗ ЦИКЛА
                 1. текущий юзер, расчётная ставка находится в пределах выставляемого им ЗБ
                    и превысила ЗБ предыдущего (победителя) игрока */
                if($bidder_id==$current_bidder_id){ // ставка за текущего игрока
                    if ($test) showTestMessage("Cтавка за ТЕКУЩЕГО игрока:", __FILE__, __LINE__, '#333');
                    if($bid_counted_value>=$current_max_bid) { // расчётная ставка больше или равна текущему макс. биду
                        $break = true;
                        if ($test) showTestMessage("Последняя ставка (проставляется за текущего игрока):", __FILE__, __LINE__, 'orange');
                    }
                    /**
                    текущая расчётная ставка больше его максимальной (т.е., - ЗБ) */
                    if($bid_counted_value>$user_bid_value){
                        $break = -1; // отмена выставления ставки, выход из цикла
                        /**
                        обратное присваивание значения последней ставки; нужно на случай,
                        если входящая ставка находится вне диапазона реально допустимых
                        ставок (например - 610, когда возможны только 600 и 620. В данном
                        случае величина ставки будет снижена до 600).
                        Ситуация может возникнуть, если юзер отправил заявку после того,
                        как шаг торгов изменился (другой игрок выставил собственную заявку).
                        Данное значение ниже будет использовано для изменения записи в таблице ЗБ */
                        $bid_counted_value=$last_bid_counted_value;
                        if ($test) showTestMessage("Ставка (<b>$bid_counted_value</b>) превысила входящий бид (<b>$post[bids]</b>) текущего юзера и будет отменена.", __FILE__, __LINE__, 'red');
                    }
                }else{
                    if ($test) showTestMessage("Cтавка за ПРЕДЫДУЩЕГО игрока:", __FILE__, __LINE__, '#666');
                    /**
                     2. предыдущий игрок, расчётная ставка больше или равна входящей
                        и меньше ЗБ предыдущего игрока */
                    if( // ставка за предыдущего игрока
                        $current_max_bid > $bid_counted_value // максимальный ЗБ больше расчётной ставки
                        && $bid_counted_value >=$user_bid_value){ // расчётная ставка превысила входящую
                        $break=true;
                        if($test) showTestMessage("Последняя ставка (проставляется за предыдущего игрока):",__FILE__,__LINE__,'violet');
                    }
                }
                if($test){
                    echo "<div";
                    if($bidder_id==$current_bidder_id) {
                        echo " style='font-weight:bold'";
                        $usertype = 'Current user';
                    }
                    elseif($bidder_id==$previous_bidder_id) {
                        echo " style='color:#666'";
                        $usertype = 'Previous user';
                    }
                    echo "></b>$usertype<br>ставка: $bid_counted_value</div>";
                    echo "<div>current_max_bid: $current_max_bid</div>";
                    echo "<div>bidder_id: $bidder_id</div>";
                }
                /**
                если не нужно отменять добавление ставки из-за превышения текущей
                последнего ЗБ юзера */
                if($break!==-1){ //
                    // добавить запись в таблицу ставок
                    $data->sum=$bid_counted_value;
                    $data->datetime=date('Y-m-d H:i:s');
                    $data->bidder_user_id = $bidder_id;
                    try{
                        $db->insertObject($table_bids, $data);
                        if($test) showTestMessage("Добавлена запись в $table_bids, bidder_id = $bidder_id, sum = $bid_counted_value",__FILE__,__LINE__,'blue');
                    }catch(Exception $e){
                        echo "<div>Ошибка добавления ставки(".__LINE__."):</div>";
                        die("<div>".$e->getMessage()."</div>");
                    }
                }
                $turn++;
                if($turn>250) die('<div class="error-text">Превышен лимит итераций</div>'.__FILE__.':'.__LINE__);
                if($break) break;
            }
            if($test){
                echo "<div style='color:blue'>current_max_bid: $current_max_bid</div>";
                echo "<div style='color:red'>user_bid_value: $user_bid_value</div>";
            }
        }else{ // ставок не было, эта - первая
            $bid_counted_value=$price; // нужно для проверки условия рекурсивного вызова функции
            $data->sum=$bid_counted_value;
            $data->bidder_user_id = $current_bidder_id;
            $data->datetime=date('Y-m-d H:i:s');
            try{
                $db->insertObject($table_bids, $data);
                if($test) showTestMessage("Добавлена запись в $table_bids",__FILE__,__LINE__,'blue');
            }catch(Exception $e){
                echo "<div>Ошибка добавления ставки(".__LINE__."):</div>";
                die("<div>".$e->getMessage()."</div>");
            }
        }
        /**
        добавить/обновить запись в таблице бидов
        проверить, есть ли уже запись для данного игрока и предмета */
        $query = "SELECT id FROM `#__dev_user_bids`
             WHERE bidder_id = $current_bidder_id
               AND virtuemart_product_id = " . $virtuemart_product_id;
        //testSQL($query,__FILE__,__LINE__, true);
        $db->setQuery($query);
        $record_id=$db->loadResult();
        if($test) {
            //showTestMessage('record_id: '.$record_id,__FILE__,__LINE__,'brown');
            //testSQL($query,__FILE__,__LINE__);
        }
        // если есть - обновить
        if($record_id){
            $update = new stdClass();
            $update->id = $record_id; // pkey
            // реальное значение, может находиться вне шага торгов
            $update->value=$user_bid_value;
            $update->datetime=date('Y-m-d H:i:s');
            try{
                $db->updateObject($table_user_bids, $update, 'id');
                if($test) showTestMessage("Обновлена запись в $table_user_bids",__FILE__,__LINE__,'green');
            }catch(Exception $e){
                echo "<div>Ошибка обновления бида(".__LINE__."):</div>";
                echo "<div>".$e->getMessage()."</div>";
            }
        }else{ // добавить
            $insert = new stdClass();
            $insert->virtuemart_product_id=$virtuemart_product_id;
            $insert->bidder_id = $current_bidder_id;
            $insert->value=$user_bid_value;
            $insert->datetime=date('Y-m-d H:i:s');
            try{
                $db->insertObject($table_user_bids, $insert);
                if($test) showTestMessage("Добавлена запись в $table_user_bids",__FILE__,__LINE__,'violet');
            }catch(Exception $e){
                echo "<div>Ошибка добавления бида(".__LINE__."):</div>";
                die("<div>".$e->getMessage()."</div>");
            }
        }
        // если до окончания торгов осталось меньше 5 мин, продлить
        if($minutes_rest<5){
            $object = new stdClass();
            $object->virtuemart_product_id = $virtuemart_product_id;
            $object->auction_date_finish = $plus5min;
            try{
                $db->updateObject('#__virtuemart_products', $object, 'virtuemart_product_id');
                if($test) showTestMessage("Оновлена запись в #__virtuemart_products",__FILE__,__LINE__);
            }catch(Exception $e){
                echo "<div>Ошибка обновления времени окончания аукциона:</div>";
                echo "<div>".$e->getMessage()."</div>";
            }
        }
        /* если ставка была первая и есть резервная цена, которая выше последней
              ставки, сделать ставку от лица виртуального игрока */
        if($test) showTestMessage("bids_count: ".$results['bids_count']."<br>svalue = ".$bid_counted_value."<br>min_price = ".$min_price,__FILE__,__LINE__);
        if( // при вызове метода ставок не было
            !(int)$results['bids_count']
            // И последняя рассчитанная ставка меньше минимальной (резервной цены)
            && $bid_counted_value < $min_price // sales_price.`min_price`
          ) {
            if($test) {
                showTestMessage('<h4>'.$bid_counted_value.' &lt; '.$min_price.'<br>makeBid($data,-1)</h4>', __FILE__, __LINE__);
            }
            $data=array(
                'virtuemart_product_id'=>$virtuemart_product_id,
                'bids'=>$min_price
            );
            // выставляем ставку за виртуального игрока
            return $this->makeUserBid($data,-1);
        }else{
            /**
             если максимальная ставка изменилась и перешла к другому
             игроку, оповестить предыдущего */
            if($bidder_id!==$previous_bidder_id && $bid_counted_value>$current_max_sum){
                // разослать сообщения
                require_once JPATH_COMPONENT.DS.'helpers'.DS.'stuff.php';
                $users=new Users();
                $message = "Ваша ставка на предмет $product_name бита. Текущая ставка: $bid_counted_value руб." ;
                $users->sendMessagesToUsers(
                    "Изменение статуса участника торгов аукциона \"Русские сезоны\"",
                    $message,
                    $users->getUsersForMail(array($previous_bidder_id))
                );
            }
            if($test) die('return true');
            return true;
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
        $db=JFactory::getDbo();
        $data = new stdClass();
        $data->user_id = JFactory::getUser()->id;
        $data->virtuemart_product_id=$post['virtuemart_product_id'][0];
        $data->event_datetime = date('Y-m-d H:i:s');
        // status by default = 0
        // Insert the object into the user profile table.
        try{
            $db->insertObject('#__dev_shop_orders', $data);
            $result['msg']=JText::_('Заказ оформлен');
            $result['type']='notice';
            require_once JPATH_COMPONENT.DS.'helpers'.DS.'stuff.php';
            $admins=new Users();
            $admins->sendMessagesToUsers(
                "Новый заказ на покупку предмета в магазине \"Русские сезоны\"",
                "Поступила новая заявка на приобретение предмета в вашем магазине."
            );
        }catch(Exception $e){
            //die('Error: '.$e->getMessage());
            $result['msg']=JText::_('ОШИБКА... Заказ не оформлен');
            $result['type']='error';
        }
        return $result;
    }
}
