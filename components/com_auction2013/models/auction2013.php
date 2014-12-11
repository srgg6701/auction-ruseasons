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
     * Добавить предмет в таблицу напоминаний
     * @package
     * @subpackage
     */
    public function  add_product_notify($product_name){
        //...
        $data = new stdClass();
        $data->user_id = JFactory::getUser()->id;
        $data->name=$product_name;
        try{
            JFactory::getDbo()->insertObject('#__dev_product_notify', $data);
            return 1;
        }catch(Exception $e){
            echo "Error: ";
            echo "<div>".$e->getMessage()."</div>";
        }
    }
    /**
     *Проверить активные аукционы и занести в данные в таблицу
     */
    public function check_active_auctions(){
        $test=true;
        /**
        Проверить предметы, даты/время закрытия торгов по которым не вышло
        за рамки текущего момента и которых нет в таблице #__dev_lots_active;
        Добавить их в таблицу. */
        $db = JFactory::getDbo();
        $query = "SELECT prods.virtuemart_product_id
FROM  #__virtuemart_products           AS prods,
      #__virtuemart_product_categories AS pcats
  WHERE product_available_date < NOW() AND auction_date_finish > NOW()
        AND prods.virtuemart_product_id NOT IN ( SELECT virtuemart_product_id FROM #__dev_lots_active )
        AND pcats.virtuemart_product_id = prods.virtuemart_product_id
        AND pcats.virtuemart_category_id IN (
    SELECT ccats.category_child_id
  FROM #__virtuemart_category_categories AS ccats,
       #__virtuemart_categories          AS cts
 WHERE category_parent_id IN
      ( SELECT cats_cats.category_child_id
          FROM #__virtuemart_category_categories AS cats_cats
    INNER JOIN #__virtuemart_categories          AS cats
               ON cats.virtuemart_category_id = cats_cats.category_child_id
                  AND cats_cats.category_parent_id = 0 )
   AND ccats.category_child_id = cts.virtuemart_category_id
   AND cts.category_layout = 'online' ) ";
        $db->setQuery($query);
        $results = $db->loadColumn();
        //testSQL($query,__FILE__, __LINE__, false);
        $insert="INSERT INTO #__dev_lots_active (virtuemart_product_id) VALUES ";
        foreach ($results as $i=>$virtuemart_product_id) {
            if($i) $insert.=",";
            $insert.="($virtuemart_product_id)";
        }
        //commonDebug(__FILE__,__LINE__,$insert); if($test) die();
        if($i){
            $db->setQuery($insert)->query();
            return $i;
        }else
            return 0;
    }
    /**
     * Проверить закрывающиеся аукционы - активные лоты, время торгов по которым вышло
     *    Выбираются только лоты, удовлетворяющие критериям:
     *      a)
     *      b) время торгов по предмету истекло
     *      c) максимальная ставка на предмет ПРЕВЫСИЛА резервную цену (в этом случае
     *         гарантированно имеется ставка от реального игрока, т.к. виртуальный
     *         игрок выставляет ставку РАВНУЮ резервной цене).
     * @package
     * @subpackage
     */
    public function getLotsToBeClosed($db,$test=false){
        $query = "SELECT  prods.                          virtuemart_product_id,
                                        bidder_id,
        bids.`value`                 AS 'max_value',
        TRUNCATE(prices.product_price,0)
                                     AS product_price,
        prods_ru.                       product_name,
        users.                          name,
        users.                          email,
        users.                          phone_number,
        users.                          phone2_number,
        product_available_date, auction_date_finish
      FROM #__virtuemart_products        AS prods
INNER JOIN #__virtuemart_product_prices  AS prices
           ON prices.virtuemart_product_id = prods.virtuemart_product_id
INNER JOIN #__dev_user_bids              AS bids
           ON bids.virtuemart_product_id = prods.virtuemart_product_id
              AND bids.bidder_id = ( SELECT bidder_id FROM #__dev_user_bids
                                      WHERE virtuemart_product_id = prods.virtuemart_product_id
                                  ORDER BY `value` DESC LIMIT 1 )
INNER JOIN #__virtuemart_products_ru_ru  AS prods_ru
           ON prods.virtuemart_product_id = prods_ru.virtuemart_product_id";
        $query.="
/*<span class='bg-yellow'>Если выберет id юзера -1, значит, максимальная ставка
  проставлена виртуальным игроком (автобид), предмет НЕ продан.</span>*/
 LEFT JOIN #__users                      AS users
           ON users.id  = bids.bidder_id
     WHERE auction_date_finish < NOW()
           /*<span class='bg-yellow'>Если есть хоть одна ставка</span>*/
           AND bids.`value` >= prices.product_price ";
            $query.="
            -- <span class='bg-orange'>ВЫБРАТЬ ЛОТЫ, которые:</span>
              -- <span class='bg-yellow'>отсутствуют среди проданных </span>
           AND prods.virtuemart_product_id NOT IN (
                        SELECT virtuemart_product_id
                          FROM #__dev_sold )
           AND  (   /* ... <span class='bg-yellow'>...И в проданных нет записи, добавленной позже или одновременно с
                    датой закрытия аукциона, что означает, что после закрытия торгов аукцин
                    повторно не назначался, т.о., у предмета остался статус непроданного.</span>*/
                    SELECT COUNT(*)
                      FROM #__dev_unsold
                     WHERE virtuemart_product_id = prods.virtuemart_product_id
                       AND `datetime` >= prods.auction_date_finish
                  ) = 0";
        // дата закрытия аукциона наступила и максимальная ставка выше стартовой цены
        $db->setQuery($query);
        $results = $db->loadAssocList();
        if($test) testSQL(array($results,$query), __FILE__, __LINE__, false, '', false);
        return $results;
    }
    /**
     * 1. Проверить закрывающиеся аукционы - активные лоты, время торгов по которым вышло
     *    - см. метод getLotsToBeClosed()
     * 2. ЕСЛИ лоты найдены:
     *    2.1. Определить победителей торгов и разослать им сообщения (включая реквизиты оплаты)
     *    2.2. id id предметов по закрывающимся торгам, удовлетворяющих заданным критериям,
     *         скопировать (вставить записи) в табл. проданных (#__dev_sold).
     *    2.3. Удалить соответствующие записи из таблицы активных аукционов (#__dev_lots_active)
     */
    public function check_closed_lots(){
        /**
         - true - только показ запросов CUD,
         - любой другой вещественный - и выполнение, и показ */
        $test = false;
        $db = JFactory::getDbo();
        $results = $this->getLotsToBeClosed($db,$test);
        if(count($results)){
            $common_path=dirname(__FILE__).'/../';
            // получить файл с реквизитами:
            $banking_details = file_get_contents($common_path.'banking_details.txt');
            require_once $common_path.'helpers/stuff.php';
            $messages=$ids_sold=$ids_unsold=array();
            $users = new Users();
            // разослать сообщения победителям
            foreach ($results as $i=>$info) {
                //commonDebug(__FILE__,__LINE__,$info);
                if($info['bidder_id']=='-1'){ // максимальную ставку проставил виртуальный игрок
                    // пополнить массив непроданных предметов
                    $ids_unsold[]=$info['virtuemart_product_id'];
                }else{
                    //commonDebug(__FILE__,__LINE__,$info);
                    $users->sendMessagesToUsers('Вы стали победителем аукциона!',
                        '<p>Здравствуйте, ' .$info['name'] . '!</p>
                                <p>Рады вам сообщить, что вы стали победителем
                                аукциона по предмету <b>' . $info['product_name'] . '</b>.</p>
                                <hr/>
                                <p>Цена предмета по итогам торгов: ' . $info['max_value'] . ' руб.</p>
                                <p><b>Реквизиты для оплаты:</b><br/><br/>' . nl2br($banking_details) . '.</p>',
                        $info['email']
                    );
                    $ids_sold[]=$info['virtuemart_product_id'];
                    $winner_phone = $info['phone_number'];
                    if(!$winner_phone)
                        $winner_phone= $info['phone2_number'];
                    elseif($winner_phone2= $info['phone2_number'])
                        $winner_phone.=", ".$winner_phone2;

                    $messages[] = 'Предмет: ' .     $info['product_name'] .
                        '<br> Cтартовая цена: ' .   $info['product_price'] .
                        '<br> Последняя ставка: ' . $info['max_value'] .
                        '<br> Имя победителя: ' .   $info['name'] .
                        '<br> Тел. победителя: ' .  $winner_phone .
                        '<br> Email победителя: ' . $info['email'];
                }
            }
            if(!empty($messages))
                $users->sendMessagesToUsers('Итоги аукциона',implode("<hr/>", $messages));
            // если есть проданные - добавить в #__dev_sold
            $this->changeLotsState($ids_sold,'sold',$db,$test);
            // если есть непроданные - добавить в #__dev_unsold
            $this->changeLotsState($ids_unsold,'unsold',$db,$test);
            return true;
        }
        return false;
    }
    /**
     * Добавить записи в табл. проданных/непроданных предметов
     * @package
     * @subpackage
     */
    public function changeLotsState($ids,$stat,$db,$test=false){
        if(!empty($ids)){
            //добавить предметы в таблицу проданных
            $queryIns = "INSERT INTO #__dev_" . $stat // sold/unsold
                ." (`virtuemart_product_id`,`section`) VALUES ("
                . implode(',1),(', $ids) . ",1)";
            /* ( 1,1),( 2 ,1),(3 ,1),()*/
            try{
                if($test)
                    showTestMessage($queryIns.'<hr/>', __FILE__, __LINE__);
                if($test!==true)
                    $db->setQuery($queryIns)->query();
            }catch(Exception $e){
                echo "<div>".$e->getMessage()."</div>";
            }
        }
        return true;
    }

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
            //testSQL($query, __FILE__, __LINE__, true, '', false);
            return $res;
        }catch (Exception $e){
            die($e->getMessage());
        }
    }
    /**
     *
     */
    function getImage($media_id){
        $db = JFactory::getDbo();
        $query = "SELECT
  #__virtuemart_medias.file_title
FROM #__virtuemart_medias
  INNER JOIN #__virtuemart_product_medias
    ON #__virtuemart_medias.virtuemart_media_id
  = #__virtuemart_product_medias.virtuemart_media_id
  WHERE #__virtuemart_product_medias.virtuemart_media_id = $media_id";
        $db->setQuery($query);
        return $db->loadResult();
    }
	/**
	 * Проверить предмет в списке наблюдения
	 *
	 * @return	object
	 */
	function getProductwWatchedItems($product_name)
	{
        $db = JFactory::getDbo();
        $query = "SELECT virtuemart_product_id, product_name
  FROM #__virtuemart_products_ru_ru
  WHERE product_name LIKE '%$product_name%'
        OR product_s_desc LIKE '%$product_name%'";
        $db->setQuery($query);
        $results = $db->loadAssoc();
        return $results;
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
        $test=false;
        //commonDebug(__FILE__,__LINE__,$post, true);
        /*["bids"]=>                    "900"
          ["14e429a6cd5c1d774d06539dce403129"]=> "1"
          ["option"]=>                  "com_auction2013"
          ["virtuemart_product_id"]=>   "2702"
          ["task"]=>                    "makeBid"
          ["Itemid"]=>                  "125"
          ["virtuemart_category_id"]=>  "33"  */
        $table_bids         = '#__dev_auction_rates';        // ставки
        $table_user_bids    = '#__dev_user_bids';   // заочные биды
        if(!$current_bidder_id)
            $current_bidder_id = JFactory::getUser()->id;

        $current_bidder_id=(int)$current_bidder_id;
        $virtuemart_product_id = $post['virtuemart_product_id'];
        // проверить, превышает ли ставка юзера текущий максимальный заочный бид
        $db = JFactory::getDbo();
        $query = "SELECT prods_ru.product_name,
( SELECT COUNT(*)
  FROM #__dev_auction_rates
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
INNER JOIN #__virtuemart_products_ru_ru  AS prods_ru
           ON prods_ru.virtuemart_product_id = prods.virtuemart_product_id
 LEFT JOIN $table_bids                   AS bids
           ON prods.virtuemart_product_id = bids.virtuemart_product_id
 LEFT JOIN $table_user_bids              AS user_bids
           ON user_bids.virtuemart_product_id = bids.virtuemart_product_id
              AND bids.bidder_user_id = user_bids.bidder_id
INNER JOIN #__virtuemart_product_prices  AS prices
           ON prices.virtuemart_product_id = prods.virtuemart_product_id
 LEFT JOIN #__dev_sales_price            AS sales_price
           ON sales_price.virtuemart_product_id = prods.virtuemart_product_id
     WHERE prods.virtuemart_product_id = $virtuemart_product_id";
        //
        try{
            $db->setQuery($query);
            $results = $db->loadAssoc();
            if($test){
                testSQL("Проверить, превышает ли ставка юзера текущий максимальный заочный бид <br>".$query,__FILE__, __LINE__);
                //commonDebug(__FILE__,__LINE__,$results);
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
                $step = AuctionStuff::getBidsStep($bid_counted_value); // returns int
                if($test) showTestMessage("<ul>
                                            <li>step: $step</li>
                                            <li>bid_counted_value: $bid_counted_value</li>
                                           </ul>", __FILE__, __LINE__, 'brown');
                // сохранить предыдущую ставку на случай отмены последней ставки (см. ниже)
                $last_bid_counted_value=$bid_counted_value;
                // расчитать величину текущей ставки
                $bid_counted_value+=$step;
                /**
                подставить id игрока - текущий/предыдущий в зависимости
                от чётности записи  */
                $bidder_in_loop_id=($turn%2>0)? $current_bidder_id:$previous_bidder_id; // статические значения, полученные из запроса
                /**
                УСЛОВИЯ ВЫХОДА ИЗ ЦИКЛА
                    1. текущий юзер - автобид и его рассчётная ставка больше резервной цены
                    2. текущий юзер, расчётная ставка находится в пределах выставляемого им ЗБ
                    и превысила ЗБ предыдущего (победителя) игрока */
                if($bidder_in_loop_id==-1&&$bid_counted_value>$min_price){
                    if($test){
                        showTestMessage("<h2>Будет выполнен выход из цикла</h2>", __FILE__, __LINE__, 'darkred');
                        showTestMessage("<ul>
                                        <li>bid_counted_value: $bid_counted_value</li>
                                        <li>min_price: $min_price</li>
                                        <li>step: $step</li>
                                        <li>bid_counted_value-min_price = ".($bid_counted_value-$min_price)."</li>
                                     </ul>", __FILE__, __LINE__);
                    }
                    /*  95:91*/
                    $diff=$bid_counted_value-$min_price;
                    if($diff==$step){
                        if($test) showTestMessage("<h2>Выход из цикла:</h2><div>Autobid, ставка больше минимальной цены.</div>", __FILE__, __LINE__, 'blue');
                        $break = -1; // отмена выставления ставки, выход из цикла
                    }else{
                        /**
                        назначение ставки, равной минимальной ставке;
                        значение должно быть равно максимальному автобиду   */
                        $bid_counted_value=$min_price;
                        $break=true; // выход из цикла
                    }
                }else{
                    if($bidder_in_loop_id==$current_bidder_id){ // ставка за текущего игрока
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
                        //if ($test) showTestMessage("Cтавка за ПРЕДЫДУЩЕГО игрока:", __FILE__, __LINE__, '#666');
                        /**
                        2. предыдущий игрок, расчётная ставка больше или равна входящей
                        и меньше ЗБ предыдущего игрока */
                        if( // ставка за предыдущего игрока
                            $current_max_bid > $bid_counted_value // максимальный ЗБ больше расчётной ставки
                            && $bid_counted_value >=$user_bid_value) { // расчётная ставка превысила входящую
                            $break=true;
                            //if($test) showTestMessage("Последняя ставка (проставляется за предыдущего игрока):",__FILE__,__LINE__,'violet');
                        }
                    }
                }
                if($test){
                    echo "<div";
                    if($bidder_in_loop_id==$current_bidder_id) {
                        echo " style='font-weight:bold'";
                        $usertype = 'Current user';
                    }
                    elseif($bidder_in_loop_id==$previous_bidder_id) {
                        echo " style='color:#666'";
                        $usertype = 'Previous user';
                    }
                    echo "></b>$usertype<br>ставка: $bid_counted_value</div>";
                    echo "<div>current_max_bid: $current_max_bid</div>";
                    echo "<ul>
                            <li>bidder_in_loop_id: $bidder_in_loop_id</li>
                            <li>current_bidder_id: $current_bidder_id</li>
                            <li>previous_bidder_id: $previous_bidder_id</li>
                          </ul>";
                }
                /**
                если не нужно отменять добавление ставки из-за превышения текущей
                последнего ЗБ юзера */
                if($break!==-1){ //
                    // добавить запись в таблицу ставок
                    $data->sum=$bid_counted_value;
                    $data->datetime=date('Y-m-d H:i:s');
                    $data->bidder_user_id = $bidder_in_loop_id;
                    try{
                        $db->insertObject($table_bids, $data);
                        if($test) showTestMessage("Добавлена запись в $table_bids, bidder_in_loop_id = $bidder_in_loop_id, sum = $bid_counted_value",__FILE__,__LINE__,'blue');
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
        if($test) showTestMessage("bids_count: ".$results['bids_count']."<br>svalue = ".$bid_counted_value."<br>min_price = ".$min_price."</hr>",__FILE__,__LINE__);
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
            if($test) showTestMessage("<b>Сделать ставку за виртуального игрока</b>", __FILE__, __LINE__, 'brown');
            return $this->makeUserBid($data,-1);
        }else{
            /**
             если максимальная ставка перешла к другому игроку, оповестить предыдущего */
            if( $previous_bidder_id
                && $bidder_in_loop_id!==$previous_bidder_id
                /**
                 previous_bidder Вася    10
                 current_bidder  Петя    15 bidder_in_loop Отослать Васе
                                 Вася    20 bidder_in_loop Отослать Пете   */
                || $turn > 2 // была обработана как минимум ещё 1 ставка //$bidder_in_loop_id==-1
              ){
                // выясним, которому из игроков отсылать письмо
                $user_id_to_mail=($bidder_in_loop_id==$previous_bidder_id)?
                                    $current_bidder_id : $previous_bidder_id;
                if($user_id_to_mail!=-1){
                    if($test) showTestMessage("<h2>Оповестить игрока о том, что его ставка бита</h2>", __FILE__, __LINE__, 'darkblue');
                    // разослать сообщения
                    require_once JPATH_COMPONENT.DS.'helpers'.DS.'stuff.php';
                    $users=new Users();
                    $message = "Ваша ставка на предмет $product_name ($current_max_sum руб.) бита.
                           <p>Текущая ставка: $bid_counted_value руб.</p>" ;
                    $users->sendMessagesToUsers(
                        "Изменение статуса участника торгов аукциона \"Русские сезоны\"",
                        $message,
                        $users->getUsersForMail(array($user_id_to_mail))
                    );
                }elseif($test)
                    showTestMessage("<h2>Оповещение не требуется, проиграл Автобид.</h2>", __FILE__, __LINE__, 'darkgreen');
            }elseif($test)
                showTestMessage("<h2>Оповещение не требуется</h2>", __FILE__, __LINE__, 'darkgreen');

            if($test){
                showTestMessage("bidder_in_loop_id (".          gettype($bidder_in_loop_id)."):             $bidder_in_loop_id<br>
                                 previous_bidder_id (". gettype($previous_bidder_id)."):    $previous_bidder_id<br>
                                 bid_counted_value (".  gettype($bid_counted_value)."):     $bid_counted_value<br>
                                 current_max_sum (".    gettype($current_max_sum)."):       $current_max_sum",
                    __FILE__, __LINE__, false);
            }
            if($test) die('<h1>return true</h1>');
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
            $result['type']='sold';
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
    /**
     * Удалить предмет из листа наблюдений
     * @package
     * @subpackage
     */
    public function remove_product_notify($id){
        //...
        $db = JFactory::getDbo();
        $query = "DELETE FROM #__dev_product_notify
         WHERE id = $id AND user_id = " . JFactory::getUser()->id;
        $db->setQuery($query);
        $results = $db->query();
        return $results;
    }

    /**
     * Комментарий
     * @package
     * @subpackage
     */
    public function testCron(){
        $common_path=dirname(__FILE__).'/../'; // com_auction2013
        // получить файл с реквизитами:
        $banking_details = file_get_contents($common_path.'banking_details.txt');
        require_once $common_path.'helpers/stuff.php';
        $messages=$ids=array();
        $users = new Users();
        // разослать сообщения
        $users->sendMessagesToUsers('Вы стали победителем аукциона!',
            '<p>Здравствуйте, это - тестовое соощение!</p>
             <p>Рады вам сообщить, что вы стали победителем
                аукциона по предмету <b>[название_предмета]</b>.</p>
             <hr/>
             <p>Цена предмета по итогам торгов: [цена_предмета] руб.</p>
             <p><b>Реквизиты для оплаты:</b><br/><br/>' . nl2br($banking_details) . '.</p>',
            NULL
        );
        return true;
    }

    /**
     * Извлечь предметы аукциона
     * @package
     * @subpackage
     */
    public function getProductsForAuction($auction_number, $img_dir){
        $query_count="SELECT COUNT(prod_ru.virtuemart_product_id)";
        $query_full="SELECT  prod_ru.virtuemart_product_id,
        prod.lot_number,
        ( SELECT COUNT(*) FROM #__virtuemart_product_medias
          WHERE virtuemart_product_id = prod_ru.virtuemart_product_id
        ) AS img_cnt,
        prod_ru.product_name AS title,
        currency_symbol,
        CONCAT( TRUNCATE(prices.product_price,0), ' - ', TRUNCATE(price2,0)) AS prices,
        prod_ru.product_s_desc,";
        // добавить извлечение ссылки
        $query_full.=(!JApplication::getRouter()->getMode())?
            "
  CONCAT('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=',
          prod_ru.virtuemart_product_id,
         '&virtuemart_category_id=',
         cats.virtuemart_category_id  ) AS href,"
            :
            "
  CONCAT( '".JURI::base()."аукцион/', ( SELECT alias
              FROM #__menu
             WHERE menutype = 'mainmenu'
               AND link LIKE '%=com_virtuemart%'
               AND link LIKE CONCAT( '%virtuemart_category_id=',(
                                      SELECT category_parent_id
                                        FROM #__virtuemart_category_categories
                                       WHERE category_child_id = cats.virtuemart_category_id)
                                  )
          ), '/', cats_ru.slug, '/', prod_ru.slug, '-detail' ) AS href,";
        $query_full.="
        ( SELECT
    REPLACE(file_url_thumb,'".$img_dir."','')
    FROM auc13_virtuemart_medias
     WHERE virtuemart_media_id = prods_media.virtuemart_media_id

  ) AS image";
        /*
  medias.file_url_thumb AS image "; */
        $query_common="
FROM #__virtuemart_products_ru_ru prod_ru
  INNER JOIN #__virtuemart_products prod
    ON prod_ru.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN #__virtuemart_product_categories cats
    ON cats.virtuemart_product_id = prod.virtuemart_product_id
  INNER JOIN #__virtuemart_categories_ru_ru cats_ru
    ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id

  LEFT JOIN #__virtuemart_product_prices AS prices
    ON prices.virtuemart_product_id = prod.virtuemart_product_id

  LEFT JOIN #__dev_sales_price AS prices2
    ON prices2.virtuemart_product_id = prod.virtuemart_product_id

  LEFT JOIN #__virtuemart_currencies AS currency
    ON virtuemart_currency_id = product_currency

  LEFT OUTER JOIN #__virtuemart_product_medias prods_media
    ON prod_ru.virtuemart_product_id = prods_media.virtuemart_product_id";

  /*"LEFT OUTER JOIN #__virtuemart_medias medias
    ON prods_media.virtuemart_media_id = medias.virtuemart_media_id";*/
        if(!JRequest::getVar('unlim'))
            $query_common.="
        WHERE auction_number = $auction_number";

        $db=JFactory::getDbo();
        // получить общее количество предметов
        $query=$query_count.$query_common;
        //testSQL($query, __FILE__, __LINE__, false, '', false);
        $db->setQuery($query);
        // сохранить общее количество предметов
        AuctionStuff::$prods_value=$db->loadResult();
        // получить предметы в соотвествии с лимитом страниц
        $query=$query_full.$query_common . AuctionStuff::getPagesLimit();
        //testSQL($query, __FILE__, __LINE__, false, '', false);
        $db->setQuery($query);
        $results = $db->loadObjectList(); // Result, loadAssoc, ArrayList, Column, Row, RowList
        $arr_products=array();
        foreach ($results as $result) {
            $product_id=$result->virtuemart_product_id;
            if(!isset($arr_products[$product_id])){
                $arr_products[$product_id]=array();
                $arr_products[$product_id]['lot_number']=$result->lot_number;
                $arr_products[$product_id]['title']=$result->title;
                $arr_products[$product_id]['currency_symbol']=$result->currency_symbol;
                $arr_products[$product_id]['prices']=$result->prices;
                $arr_products[$product_id]['product_s_desc']=$result->product_s_desc;
                $arr_products[$product_id]['href']=$result->href;
            }
            $arr_products[$product_id]['image'][]=$result->image;
        }
        /*
        [3826]=>
          array(7) {
            ["lot_number"]=> string(7) "1000287"
            ["title"]=> string(91) "Burgeois Eugene (1855-1909) «Пейзаж с деревьями у озера»."
            ["currency_symbol"]=> string(6) "руб"
            ["prices"]=> string(5) "0 - 0"
            ["product_s_desc"]=> string(112) "Холст, масло, без подписи, Франция, 2-я пол. ХIХ в., 55,5х38 см (сколы"
            ["href"]=> string(190) "http://localhost/auction-ruseasons/аукцион/магазин-антиквариата/raznoe/burgeois-eugene-1855-1909-laquo-пейзаж-с-деревьями-у-озера-raquo-detail"
            ["image"]=> array(3) {
                          [0]=> string(1) "0"
                          [1]=> string(25) "02_215_05_2.jpg_90x90.jpg"
                          [2]=> string(25) "02_215_05_3.jpg_90x90.jpg"
                        }
          }*/
        //commonDebug(__FILE__,__LINE__,$arr_products, true);
        return $arr_products;
    }
}
