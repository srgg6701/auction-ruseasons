<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_SITE.DS.'tests.php';
/**
 * Users Route Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class AuctionStuff{
    static $andLayout = '&layout=';
    //static $bid_sums = NULL;
    static $common_link_segment = 'index.php?option=com_virtuemart&view=category&Itemid=';
    static $vm_category_id = '&virtuemart_category_id=';
    /**
 * Добавить предмет в избранное
 * @package
 * @subpackage
 */
	public static function addToFavorites($virtuemart_product_id,$user_id){
		//var_dump(JRequest::get('post')); die('addToFavorites');
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'tables'.DS.'product_favorites.php';
		$table = JTable::getInstance('Productfavorites','Auction2013Table');
		if (!AuctionStuff::getFavoritesCount($user_id,$virtuemart_product_id)) {
			$table->reset();
			$data=array(
					'virtuemart_product_id'=>$virtuemart_product_id,
					'user_id'=>$user_id,
					'datetime'=>date('Y-m-d H:i:s')
				);
			foreach($data as $field=>$value)
				$table->set($field,$value);
			// Check that the data is valid
			if ($table->check()){
				if (!$table->bind($data)){
				  echo "<div class=''>Ошибка связи полей таблицы...</div>";
				  // handle bind failure
				  echo $table->getError();
				}
				// Store the data in the table
				if (!$table->store(true))
				{	JError::raiseWarning(100, JText::_('Не удалось сохранить данные для предмета id '.$virtuemart_product_id.'...'));
					//$errors++;
				}else{
					$session = JFactory::getSession();
					$session->clear('favorite_product_id');
				}
			}else die("Данные не валидны...");
			return true;
		}else return 'exists'; // reserved meaning
	}
/**
 * Построить ссылку на соседний предмет
 * @package
 * @subpackage
 */
	public static function buildProdNeighborLink($neighborId,$category_link=false,$SefMode=false){
		return ($SefMode)?
			$category_link.'/'.AuctionStuff::getProdSlug($neighborId).'-detail'
				:
			JRoute::_('index.php?option=com_virtuemart&virtuemart_category_id='.JRequest::getVar('virtuemart_category_id').'&virtuemart_product_id='.$neighborId
				//$trinityIds[0]
			.'&Itemid='.JRequest::getVar('Itemid'));
	}
/**
 * Извлечь ЧПУ-ссылку для категории из ранее сохранённого массива в сессии
 * @package
 * @subpackage
 */
	public static function extractCategoryLinkFromSession($virtuemart_category_id,$links=false){
		// todo: разобраться в целесообразности...
        $links = self::handleSessionCategoriesData();
        //commonDebug(__FILE__, __LINE__, $links, true);
        foreach($links as $layout=>$data){
            // если таки есть категория с таким id
            if(array_key_exists($virtuemart_category_id, $data['child_links'])){
                $link_type=(JApplication::getRouter()->getMode())?
                    'sef':'link';
                return $data['child_links'][$virtuemart_category_id][$link_type];
            }
        }
		return false;
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function extractProductLink(
                                    $virtuemart_product_id,
                                    $virtuemart_category_id,
                                    $Itemid ){
		return JRoute::_( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='
            . $virtuemart_product_id . '&virtuemart_category_id='
            . $virtuemart_category_id . '&Itemid='
            . $Itemid );
	}
/**
 * Получить контент статьи
 * @package
 * @subpackage
 */
	static public function getArticleContent($id){
		$query = "SELECT * FROM `#__content` WHERE id = ".$id;
		//  Load query into an object
		$db = JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssoc();
	}
    /**
     * Получить историю ставок по предмету
     */
    public static function getBidsHistory($virtuermart_prodict_id){
        $db = JFactory::getDbo();
        $query = "SELECT sum,
    DATE_FORMAT(datetime,\"%d.%m %H:%i\")
                      AS datetime,
                   users.username
        FROM `#__dev_bids` AS bids
   LEFT JOIN `#__users` AS users ON bids.bidder_user_id = users.id
 WHERE virtuemart_product_id = $virtuermart_prodict_id
  ORDER BY bids.id DESC";
        $db->setQuery($query);
        //testSQL($query,__FILE__,__LINE__);
        //commonDebug(__FILE__,__LINE__,$db->loadAssocList());
        return $db->loadAssocList();
    }
    /**
     * Получить шаг торгов для предмета
     */
    public static function getBidsStep($price) {
        commonDebug(__FILE__,__LINE__,$price);
        $price=(int)$price;
        // получить шаги выставления цен:
        $price_steps = json_decode(file_get_contents(JPATH_SITE . DS.
            'components' . DS .
            'com_auction2013' . DS .
            'price_ranges.json')   );
        $bid_step=0;
        $start=true;
        foreach ($price_steps as $range=>$step) {
            $ranges = explode('-', $range); //commonDebug(__FILE__,__LINE__,$range, false, true);
            // цена больше минимального порога или первая итерация
            if($price>(int)$ranges[0] || $start){ // 200 > 50
                if($ranges[1]=='*'||$price<=(int)$ranges[1]){ // 200 <= 200
                    $bid_step=(int)$step; showTestMessage('step: '.$step.', ranges[1]: '.$ranges[1],__FILE__,__LINE__,'brown');
                    break;
                }
            }
            $start = false;
        }   //commonDebug(__FILE__,__LINE__,$bid_step, true);
        if(!$bid_step)
            die("ОШИБКА: не получен шаг торгов.<hr>".__FILE__.':'.__LINE__);
        return $bid_step;
    }
    /**
     * Описание
     * @package
     * @subpackage
     */
    public static function getCategoryIdByProductId($virtuemart_product_id){
        $query="SELECT cats.virtuemart_category_id
FROM `#__virtuemart_categories` AS cats
  INNER JOIN `#__virtuemart_category_categories` AS  cat_cats
    ON cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN `#__virtuemart_product_categories` AS prod_cats
    ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id
   AND prod_cats.virtuemart_category_id = cat_cats.category_child_id
   AND prod_cats.virtuemart_product_id = ".$virtuemart_product_id;
        $db=JFactory::getDBO();
        $db->setQuery($query);
        return $db->loadResult();
    }
    /**
     * Получить страны
     * @package
     * @subpackage
     */
    public static function getCountries(){
        return array('7'=>'Россия','380'=>'Украина','375'=>'Белоруссия');
    }
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getCategoryNeighborhood($virtuemart_category_id){

		$queryGetParentId="SELECT cat_cats1.category_parent_id
    FROM `#__virtuemart_categories` AS cats1
      INNER JOIN `#__virtuemart_category_categories` AS cat_cats1
        ON cats1.virtuemart_category_id = cat_cats1.id
    WHERE cats1.virtuemart_category_id = ".$virtuemart_category_id;

		$queryGetYoungerCategory="SELECT MAX(cats2.virtuemart_category_id)
          FROM `#__virtuemart_categories` AS cats2
         WHERE cats2.virtuemart_category_id < ".$virtuemart_category_id;

		$queryGetOlderCategory="SELECT MIN(cats2.virtuemart_category_id)
          FROM `#__virtuemart_categories` AS cats2
         WHERE cats2.virtuemart_category_id > ".$virtuemart_category_id;

		$query="SELECT cats.virtuemart_category_id ".
	// , cat_cats.category_parent_id
"  FROM #__virtuemart_categories AS cats,
       #__virtuemart_category_categories AS cat_cats
 WHERE cat_cats.category_child_id = cats.virtuemart_category_id
  AND cat_cats.category_parent_id IN ( ".$queryGetParentId." )
  AND
    ( cats.virtuemart_category_id = ( ".$queryGetYoungerCategory." )
      OR cats.virtuemart_category_id = ".$virtuemart_category_id."
      OR cats.virtuemart_category_id = ( ".$queryGetOlderCategory." )
    )
  ORDER BY cats.virtuemart_category_id LIMIT 3
";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadResultArray();
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getFavorites($user_id=false){
		if(!$user_id){
			$user = JFactory::getUser();
			$user_id=$user->id;
		}
		$query="SELECT
  "."#__product_favorites.virtuemart_product_id,
  product_name,
  auction_date_start,
  auction_date_finish,
  product_price,
  slug,
  virtuemart_category_id
FROM #__virtuemart_products_ru_ru
  INNER JOIN #__product_favorites
    ON #__virtuemart_products_ru_ru.virtuemart_product_id = #__product_favorites.virtuemart_product_id
  INNER JOIN #__virtuemart_products
    ON #__virtuemart_products.virtuemart_product_id = #__product_favorites.virtuemart_product_id
   AND #__virtuemart_products.virtuemart_product_id = #__virtuemart_products_ru_ru.virtuemart_product_id
  INNER JOIN #__virtuemart_product_prices
    ON #__virtuemart_product_prices.virtuemart_product_id = #__virtuemart_products_ru_ru.virtuemart_product_id AND #__virtuemart_product_prices.virtuemart_product_id = #__virtuemart_products.virtuemart_product_id
  INNER JOIN #__virtuemart_product_categories
    ON #__product_favorites.virtuemart_product_id = #__virtuemart_product_categories.virtuemart_product_id

  WHERE user_id = ".$user_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$favors=$db->loadAssocList();
		foreach($favors as $i=>$rows){
			$virtuemart_product_id=$rows['virtuemart_product_id'];
			unset($rows['virtuemart_product_id']);
			$favorites[$virtuemart_product_id]=$rows;
			unset ($rows);
		}
		return $favorites;
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getFavoritesCount($user_id,$virtuemart_product_id=false){
		if(!$user_id){
			$user = JFactory::getUser();
			$user_id=$user->id;
		}
		$query="SELECT COUNT(id) FROM #__product_favorites
 WHERE ";
 		if ($virtuemart_product_id)
 			$query.=" virtuemart_product_id = ".$virtuemart_product_id."
   AND ";
   		$query.=" user_id = ".$user_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadResult();
	}
    /**
     * Получить актуальную цену лота - либо минимальную, либо текущую ставку
     */
    public static function getBidSum($data) {
        if(key_exists('product_price', $data))
            $product_price = $data['product_price'];
        elseif(key_exists('virtuemart_product_id', $data)){
            // получить минимальную стоимость предмета
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName('min_price'));
            $query->from($db->quoteName('#__dev_sales_price'));
            $query->where($db->quoteName('virtuemart_product_id') . ' = '. $data['virtuemart_product_id']);
            $db->setQuery($query);
            $product_price=$db->loadResult();
        }else
            die('<div class="error-text">Не получен virtuemart_product_id.</div>');
        if($max_bid_sum = self::getMaxBidSum($data['virtuemart_product_id']))
            if((int)$max_bid_sum>(int)$product_price)
                $product_price = $max_bid_sum;
        return $product_price;
    }
    /**
     * Получить последнюю ставку
     */
    public static function getMaxBidSum($virtuemart_product_id){
        // получить запись из таблицы
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('MAX(sum)'));
        $query->from($db->quoteName('#__dev_bids'));
        $query->where($db->quoteName('virtuemart_product_id') . ' = '. $virtuemart_product_id);
        $db->setQuery($query);
        return $db->loadResult(); // Column, Row, Assoc[List], Object
    }
    /**
     * Получить все возможные значения ставок и бидов для рассчёта начальной ставки
     */
    public static function getBidsValues($virtuemart_product_id){
        //if(!AuctionStuff::$bid_sums){
            $db = JFactory::getDbo();
            // шаблоны для запроса
            $where_product_id = "WHERE virtuemart_product_id = $virtuemart_product_id ";
            $SelectMaxValueFromUserBids="SELECT MAX(`value`)
            FROM #__dev_user_bids
            $where_product_id ";
            $SelectMaxSumFromBids="SELECT MAX(sum)  FROM #__dev_bids
    $where_product_id";
            $bidder_id = JFactory::getUser()->id;
            // начальная стоимость предмета:
$query = "SELECT TRUNCATE(product_price,0)  AS  'price', ";
            // максимальный заочный бид текущего игрока по предмету:
$query.=" ( $SelectMaxValueFromUserBids
AND bidder_id = $bidder_id )                AS 'user_max_bid_value', ";
            // абсолютный максимальный заочный бид по предмету:
$query.=" ( $SelectMaxValueFromUserBids )   AS 'max_bid_value', ";
            // максимальная ставка по предмету:
$query.=" ( $SelectMaxSumFromBids )         AS 'max_sum', ";
            // максимальная ставка текущего игрока по предмету:
$query.=" ( $SelectMaxSumFromBids
AND bidder_user_id = $bidder_id )           AS 'max_user_sum'
     ";
$query.="
  FROM #__virtuemart_product_prices $where_product_id";
            $db->setQuery($query);
            $results = $db->loadAssoc();
            testSQL($query,__FILE__,__LINE__);
            //commonDebug(__FILE__,__LINE__,$results, true);
            /*

            */
            //AuctionStuff::$bid_sums = $results;
        //} // $bid_sums=AuctionStuff::$bid_sums=AuctionStuff::getBidsValues($virtuemart_product_id);
        return $results;//AuctionStuff::$bid_sums;
    }
    /**
     * Получить текущую минимальную ставку
     */
    public static function getMinBid($virtuemart_product_id){
        // получить начальное значение ставок
        $bid_sums=self::getBidsValues($virtuemart_product_id);
        commonDebug(__FILE__,__LINE__,$bid_sums);
        if(!$start_bid=(int)$bid_sums['max_sum']){ // ставок не было
            $start_bid=(int)$bid_sums['price']; // значитца - стартовая цена предмета
        }
        return $start_bid;
    }
    /**
 * Описание
 * @package
 * @subpackage
 */
    public static function getProductNeighborhood($virtuemart_product_id,$virtuemart_category_id){

        $qProdParentCategoryId="SELECT cat_cats1.category_parent_id
            FROM `#__virtuemart_category_categories` AS cat_cats1
           WHERE cat_cats1.category_child_id = ".$virtuemart_category_id;

        $qAllProdsInCategory="SELECT prods.virtuemart_product_id
 FROM `#__virtuemart_products` AS prods
  INNER JOIN `#__virtuemart_product_categories` AS prod_cats
          ON prods.virtuemart_product_id = prod_cats.virtuemart_product_id
  INNER JOIN `#__virtuemart_category_categories cat_cats`
          ON prod_cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN `#__virtuemart_categories` AS cats
          ON prod_cats.virtuemart_category_id = cats.virtuemart_category_id
  AND cats.virtuemart_category_id = cat_cats.id
WHERE cat_cats.category_parent_id = ( ".$qProdParentCategoryId."
                                    )
   AND prod_cats.virtuemart_category_id = ".$virtuemart_category_id;

        $qPrevProdId="
		SELECT MAX(prods1.virtuemart_product_id)
          FROM `#__virtuemart_products` AS prods1
         WHERE prods1.virtuemart_product_id < ".$virtuemart_product_id;

        $qNextProdId="SELECT MIN(prods2.virtuemart_product_id)
          FROM `#__virtuemart_products` AS prods2
         WHERE prods2.virtuemart_product_id > ".$virtuemart_product_id;

        $query="SELECT prods3.virtuemart_product_id
  FROM `#__virtuemart_products` AS prods3
 WHERE (
        prods3.virtuemart_product_id = ( ".$qPrevProdId."
                                       )
        OR
        prods3.virtuemart_product_id = ".$virtuemart_product_id."
        OR
        prods3.virtuemart_product_id = ( ".$qNextProdId."
                                       )
       )
  AND   prods3.virtuemart_product_id IN (
        ".$qAllProdsInCategory."
       ) ";

        $db=JFactory::getDBO();
        $db->setQuery($query);
        //testSQL($query,__FILE__,__LINE__,true);
        return $db->loadResultArray();
    }
/**
 * Получить slug продукта. В частности, чтобы дописать ссылку на предыдущий продукт в профайле текущего.
 * @package
 * @subpackage
 */
	public static function getProdSlug($product_id){
		//
		$query="SELECT slug
 FROM `#__virtuemart_products_ru_ru`
INNER JOIN `#__virtuemart_products`
   ON `#__virtuemart_products_ru_ru`.virtuemart_product_id = `#__virtuemart_products`.virtuemart_product_id
WHERE `#__virtuemart_products`.virtuemart_product_id = ".$product_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadResult();
	}
/**
 * Получить покупки - все/выбранного юзера
 */
    public static function getPurchases($params=array()){//$user_id=false
        $virtuemart_product_id = $subquery = $user_id = '';
        $query="SELECT ";
        if(!empty($params)){
            $subquery=array();
            if(isset($params['user_id'])){
                $user=JFactory::getUser();
                if($params['user_id']){
                    if($params['user_id']===true){ // юзер в своём кабинете
                        if(!$user->guest)
                            $subquery[]="user_id = ".$user->id;
                    }
                }
            }
            if(isset($params['virtuemart_product_id'])){
                /** публичный раздел, неизвестно, заавторизован ли юзер */
                if($params['user_id']=='?') {
                    $user_id = ($user->guest)? "
        'unknown'":"
        IF(orders.   user_id<>".$user->id.",0,orders.user_id)";
                    $user_id.=" AS user_id,";
                }
                if($params['virtuemart_product_id'])
                    $subquery[]= "prod_ru.  virtuemart_product_id = " . $params['virtuemart_product_id'];
            }else
                $virtuemart_product_id='prod_ru.  virtuemart_product_id,';

            $query.= $virtuemart_product_id;
        }else{
            $query.= "prod_ru.  virtuemart_product_id,
        users.id AS user_id, ";
        }

        $query.="
        cats_ru.  category_name,
TRUNCATE
      ( prices.   min_price, 0)
               AS price,
        prod_ru.  product_s_desc,
        prod_ru.  product_desc,
        prod_ru.  product_name,
        cats_ru.  virtuemart_category_id,
        cats_ru.  category_name,
        prod_ru.  slug,
        $user_id
        orders.   status,
DATE_FORMAT(
        orders.event_datetime,'%d.%m.%Y %H:%i') AS 'datetime',
        users.    name,
        users.    middlename,
        users.    lastname,
        users.    username
FROM #__virtuemart_products_ru_ru  prod_ru
  INNER JOIN #__dev_shop_orders    orders
          ON prod_ru.virtuemart_product_id = orders.virtuemart_product_id
   LEFT JOIN #__virtuemart_product_categories prod_cats
          ON prod_cats.virtuemart_product_id = orders.virtuemart_product_id

   LEFT JOIN #__virtuemart_categories        cats
          ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id

   LEFT JOIN #__virtuemart_categories_ru_ru  cats_ru
          ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id

  INNER JOIN #__users              users
          ON orders.user_id = users.id
  INNER JOIN #__dev_sales_price    prices
          ON prices.virtuemart_product_id = orders.virtuemart_product_id";
        if(!empty($subquery))
            $query.="
  WHERE " . implode(" AND ", $subquery);
        $query.="
  ORDER BY orders.id DESC";
        //testSQL($query); //die();
        return JFactory::getDbo()->setQuery($query)->loadAssocList();
    }
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getSingleProductData($product_id,$fields=false){
		if(!$fields) $fields='*';
		$query="SELECT {$fields}
FROM #__virtuemart_products AS p
  INNER JOIN #__virtuemart_products_ru_ru AS p_ru_ru
    ON p.virtuemart_product_id = p_ru_ru.virtuemart_product_id
  INNER JOIN #__virtuemart_product_prices AS p_prices
    ON p_prices.virtuemart_product_id = p_ru_ru.virtuemart_product_id AND p_prices.virtuemart_product_id = p.virtuemart_product_id
WHERE p.virtuemart_product_id = ".$product_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssoc();
	}
/**
 * Извлечь Layouts разделов аукциона, чтобы разобраться с роутером и проч.
 * @package
 * @subpackage
 */
	public static function getTopCatsLayouts($array=NULL){
        $query = "SELECT ";
        if($array)
            $query.= "
  cats.virtuemart_category_id,";

        $query.= "
  LEFT((
  SUBSTRING(menu.link,
    LOCATE('&layout=', menu.link)+8)
            ), LOCATE('&virtuemart_category_id=', menu.link)-LOCATE('&layout=', menu.link)-8
  ) AS 'layout'
FROM #__virtuemart_categories AS cats
  INNER JOIN #__virtuemart_category_categories AS cats_cats
    ON cats.virtuemart_category_id = cats_cats.category_child_id
  INNER JOIN #__menu AS menu
    ON menu.link LIKE CONCAT('%&virtuemart_category_id=',cats.virtuemart_category_id)
WHERE cats_cats.category_parent_id = 0";
            $db = JFactory::getDbo();
            $db->setQuery($query);
        if($array){ // здесь получим ids топовых категорий
            if($array===true) {
                $layouts=array();
                foreach($db->loadAssocList() as $i=>$data)
                    $layouts[$data['virtuemart_category_id']]=$data['layout'];
                return $layouts;
            }else
                return $db->loadColumn();
            /*  loadAssoc[List]
                loadObject[List]
                loadArray
                loadColumn
                loadRow[List] */
        }else
            return $db->loadColumn();
            //array('online','fulltime','shop');
	}
/**
 * Получить ItemIds меню с layout-ами аукциона в Virtuemart'е:
    ["shop"]        => "115"
    ["online"]      => "125"
    ["fulltime"]    => "126"
 * Чтобы выяснить, какая из 3-х секций загружена:
 *  1) вызывать метод: $topItem = getTopCatsMenuItemIds('main или mainmenu', false, алиас_секции);
 *      результат: array([алиас_секции]=>Itemid)
 *  2) выполнить проверку: (int)$topItem[алиас_секции]===(int)$Itemid
       JRequest::getVar('Itemid');
 * @package
 * @subpackage
 */
	public static function getTopCatsMenuItemIds(
										$menutype='mainmenu',
										$view=false,
										$layout=false
									){
		$query_start="SELECT `#__menu`.id ";
        $table2='';
        if($menutype==='main'){
            $menutype='mainmenu';
            $query_start.=', cats.category_layout';
            $table2=', #__virtuemart_categories cats';
        }
        $query_start.="
  FROM  `#__menu`".$table2."
 WHERE  `menutype` =  '".$menutype."'";

		if ($view)
			$query_start.="
   AND link REGEXP  '(^|/?|&|&amp;)view=".$view."($|&|&amp;)'";

		$query_start.="
   AND link REGEXP  '(^|/?|&|&amp;)layout=";
		$query_end="($|&|&amp;)'";
		$db = JFactory::getDBO();
		if(!$layout){
			$layouts=AuctionStuff::getTopCatsLayouts();
		}else{
			$layouts[0]=$layout;
		}
		$ItemIds=array();
		foreach($layouts as $i=>$layout){
            $query=$query_start.$layout.$query_end;
            if($table2)
                $query.="
            AND  cats.category_layout = '".$layout."'
        LIMIT 1";
			// echo "<div><b>file:</b> ".__FILE__."<br>line: <span style='color:green'>".__LINE__."</span></div>";
            // echo "<div class=''>query= <pre>".$query."</pre></div>";
			$db->setQuery($query);
            if($table2){
                $ItemId=$db->loadAssoc();
                $ItemIds[$ItemId['category_layout']]=$ItemId['id'];
            }else{
                $ItemId=$db->loadResult();
                $ItemIds[]=$ItemId;
            } //echo "<div class=''>ItemId= ".$ItemId."</div>";
		}//die();
		return $ItemIds;
	}
    /**
     * Получить лоты юзера; указать его статус игрока.
     */
    public static function getUserLots($user_id=NULL){
        if(!$user_id) // не получили user_id
            $user_id = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $selectMax="SELECT MAX(sum)
      FROM `#__dev_bids`
     WHERE virtuemart_product_id = prod.virtuemart_product_id ";
        $selMaxValue = "SELECT MAX(`value`)
      FROM `#__dev_user_bids`";
        $query = "SELECT DISTINCT prod.virtuemart_product_id,
       prod_ru_ru.product_name       AS  'item_name',
       DATE_FORMAT(prod.auction_date_finish, '%d.%m.%Y %H:%i')
                                     AS 'auction_date_finish',
  ( $selectMax
           AND bidder_user_id = bids.bidder_user_id
  )                                  AS  'user_max_lot',
  ( $selMaxValue
     WHERE virtuemart_product_id = prod.virtuemart_product_id
  )                                  AS  'absolute_max_lot',
  ( $selMaxValue AS uBids
     WHERE virtuemart_product_id = prod.virtuemart_product_id
       AND bidder_id = $user_id )
                                     AS  'user_max_bid',
  ( $selectMax
  )                                  AS  'max_bid',
  prod_cats.                             virtuemart_category_id
        FROM #__virtuemart_products                AS prod
  INNER JOIN #__virtuemart_products_ru_ru          AS prod_ru_ru
          ON prod.virtuemart_product_id = prod_ru_ru.virtuemart_product_id
   LEFT JOIN #__virtuemart_product_categories      AS prod_cats
              ON prod_cats.virtuemart_product_id    = prod.virtuemart_product_id
  INNER JOIN #__dev_bids                           AS bids
              ON bids.virtuemart_product_id         = prod.virtuemart_product_id
  WHERE     bids.bidder_user_id = " . $user_id ."
  ORDER BY  bids.id DESC ";
        //testSQL($query,__FILE__, __LINE__);
        $db->setQuery($query);
        $results = $db->loadAssocList();
        return $results;
    }
//shop'
/**
 * Generate HTML form
 * @package
 * @subpackage
 */
	public static function createForm($arrFields){
		ob_start();
		foreach($arrFields as $value=>$fieldArray){?>
			<div>
				<label for="<?=$value?>"><?php if (isset($fieldArray[1])){
					?><span class="req">*</span><?php $req=' required';
				}else{
					$req='';
				}
				echo $fieldArray[0];?>:</label>
		<?php if($value=='country_id'){?>
				<select id="country" name="jform[country_id]"<?=$req?>>
                    <option value="none">Выберите страну</option>
			<?php $countries=AuctionStuff::getCountries();
				foreach($countries as $code=>$country):?>
					<option value="<?=$code?>"><?=$country?></option>
			<?php endforeach;?>
			</select>
		<?php }else{
				if (isset($fieldArray[3])):
					if($fieldArray[3]=='textarea'):
                		echo "<".$fieldArray[3]." id=\"{$value}\" name=\"jform[".$value."]\"";
						if(isset($fieldArray[4]))
							echo $fieldArray[4];
						echo $req."></".$fieldArray[3].">";
					endif;
            	else:?>
                <input type="<?=(strstr($value,"password"))? "password":"text"?>" autocomplete="off" maxlength="50" size="30" value="<?php if ($getValue=JRequest::getVar($value))
						echo $getValue;
					elseif(JRequest::getVar('test')){
						switch($value){
							case 'email1': case 'email2':
								echo 'test@email.com';
							break;
							case 'password1': case 'password2':
								echo 'history';
							break;
							default:
								echo $fieldArray[0];
						}
					}
				?>" name="jform[<?=$value?>]" id="<?=$value?>"<?=$req?>>
        	<?php endif;
				if(isset($fieldArray[2]))
					echo $fieldArray[2];
			}?>
			</div>
	<?php }
		$fields=ob_get_contents();
		ob_clean();
		return $fields;
	}
/**
 * Проверить наличие ранее сохранённых ссылок в сессии - извлечь или создать
 */
    public static function handleSessionCategoriesData($file=false, $line=false){
        static $cntr=1;

        $test=false;

        if($file&&$line&&$test){
            echo "<div>
                <div style='padding:10px; background-color:yellow'>call: <b>".__METHOD__."</b></div>
                <b>file:</b> ".$file."<br>line: <span style='color:green'>".$line."</span>
            </div>";
            echo "<h1 class='test' style='color:red;'>cntr = ".$cntr."</h1>"; //die();
        }
        /**
         * если метод вызывается впервые в течение загрузки страницы,
         * сгенерировать набор ссылок на категории/разделы_предметов
         * и сохранить в сессии, чтобы не вызывать процедуру генерации
         * повторно на случай, если метод будет вызыван снова (если потребуется
         * получить ссылки ещё раз).
         */
        if($cntr==1){
            //echo "<div>cntr=$cntr<b>file:</b> ".__FILE__."<br>line: <span style='color:green'>".__LINE__."</span></div>";
            $section_links = array(); // todo: разобраться с неиспользуемым ЗДЕСЬ параметром
            $top_cats_menu_ids = AuctionStuff::getTopCatsMenuItemIds('main');
            require_once JPATH_BASE.'/modules/mod_vlotscats/helper.php';
            $lots = modVlotscatsHelper::getCategoriesData(true);
            //commonDebug(__FILE__,__LINE__,$lots);
            $section_links = array();
            $sefMode = JApplication::getRouter()->getMode();
            foreach ($lots as $top_cat_id => $array){
                $top_alias = $array['top_category_layout']; // online, fulltime, shop
                $parentItemId = $top_cats_menu_ids[$top_alias];
                $common_link =  self::$common_link_segment .
                                //index.php?option=com_virtuemart&view=category&Itemid=
                                $parentItemId; // 115

                $andLayout = self::$andLayout . $top_alias;

                if (!$sefMode)
                    $common_link.=$andLayout;
                // index.php?option=com_virtuemart&view=category&Itemid=115&layout=shop

                $common_link.=self::$vm_category_id;
                // index.php?option=com_virtuemart&view=category&Itemid=115&layout=shop&&virtuemart_category_id=
                $products_count=0;
                $section_links[$top_alias] = array(
                                                'top_category_id'=>$top_cat_id,
                                                'category_name'=>$array['top_category_name'],
                                                'parent_link'=>$common_link .'0',
                                                'product_count'=>$products_count,
                                                // index.php?option=com_virtuemart&view=category&Itemid=115&layout=shop&&virtuemart_category_id=0
                                                'child_links'=>array() );
                foreach ($array as $key => $array_data){
                    if ($key == 'children'){
                        foreach ($array_data as $i => $category_data){
                            // index.php?option=com_virtuemart&view=category&Itemid=31&virtuemart_category_id=
                            $child_category_link =  self::$common_link_segment . $parentItemId
                                                    . self::$vm_category_id
                                                    . $category_data['virtuemart_category_id'];
                            //testLinks($child_category_link,__LINE__);
                            $section_links[$top_alias]['child_links'][$category_data['virtuemart_category_id']]['category_name'] = $category_data['category_name'];
                            // http://2013.auction-ruseasons.ru/index.php?option=com_virtuemart&view=category&virtuemart_category_id=26&Itemid=126
                            $section_links[$top_alias]['child_links'][$category_data['virtuemart_category_id']]['link'] = $child_category_link;
                            if($sefMode)
                                $section_links[$top_alias]['child_links'][$category_data['virtuemart_category_id']]['sef'] = JRoute::_($section_links[$top_alias]['parent_link']).'/'.$category_data['alias'];
                            $section_links[$top_alias]['child_links'][$category_data['virtuemart_category_id']]['product_count']=$category_data['product_count'];
                            // будем подставлять в ТОП-категорию
                            $products_count+=(int)$category_data['product_count'];
                        }
                    }
                }
                $section_links[$top_alias]['product_count']=$products_count;
            }
            JFactory::getSession()->set('section_links', $section_links);
            //commonDebug($file,$line,$section_links);
            $cntr++;
        }else{
            /*  если метод вызывается повторно в течение загрузки страницы и при
                этом ссылки уже были сгенерированы - извлечь их из сессии */
            //echo "<div>cntr=$cntr<b>file:</b> ".__FILE__."<br>line: <span style='color:green'>".__LINE__."</span></div>";
            if(!$section_links=JFactory::getSession()->get('section_links')){
                //die("Не получены ссылки предметов из сессии.<br>file: ".__FILE__."<br>".__METHOD__);
                return false;
            }
        }
        return $section_links;
    }
}
class HTML{
    /**
     * Построить историю ставок по предмету
     */
    public static function buildBidsHistory($history){
        //commonDebug(__FILE__,__LINE__,$history);
        $html = '<table id="tbl-bid-history" rules="rows" border="1">
        <tr>
            <th>Игрок</th>
            <th>Ставка</th>
            <th>Дата/время</th>
        </tr>';
		$user = JFactory::getUser();
		$username=($user->guest!=1)? $user->username:false;
        if(!empty($history)){
            //commonDebug(__FILE__,__LINE__,$bid_sums);
            foreach ($history as $i=>$record) {
                $bidder_name = ($record['username'])?
                    $record['username']:'Авто бид';
                $html.='<tr';
                if($username&&(int)$username==(int)$record['username'])
                    $html.=' class="bold"';
                $html.='>
            <td>' . $bidder_name . '</td>
            <td>' . $record['sum'].'</td>
            <td>' . $record['datetime'] . '</td>
        </tr>';
            }
        }else
            $html.='<tr><td colspan="3">Ставок нет.</td></tr>';
        $html.= '</table>';
        return $html;
    }
    /**
     * Построить список ставок
     * В качестве начальной ставки считает либо последнюю ставку, либо, если ставок
     * не было, стоимость предмета
     */
    public static function buildBidsSelect(
                                $virtuemart_product_id,
                                $history=null,
                                $steps = 80 ){
        // получить начальное значение ставок
        $bid=AuctionStuff::getMinBid($virtuemart_product_id);
        commonDebug(__FILE__,__LINE__,$bid);
        // получить шаг ставок
        $one_step = AuctionStuff::getBidsStep($bid);
        commonDebug(__FILE__,__LINE__,$one_step);
        if($history) $bid+=$one_step;
        $options = '';
        while($steps) {
            $options.="
            <option>$bid</option>
            ";
            $bid+=$one_step;
            $steps--;
        }
        return $options;
    }
/**
 * Получить HTML истории и списка выбора ставок
 */
    public static function getBidsHTML($virtuemart_product_id){
        $history      = AuctionStuff::getBidsHistory($virtuemart_product_id);
        $bids_history = self::buildBidsHistory($history);
        $options      = self::buildBidsSelect($virtuemart_product_id,$history);
        return array('bids_history'=>$bids_history, 'options'=>$options);
    }
    /**
     * Описание
     * @package
     * @subpackage
     */
    public static function innerMenu($content_type,$link,$obj=false){?>
        <div class="your_cab">
            <a href="<?=$link?>"><?php $lts=' &lt; &lt; '; // todo: разобраться с неиспользуемым параметром
                $gts=' &gt;&gt; ';
                switch($content_type){
                    case 'user':
                        if(!$obj)
                            $obj = JFactory::getUser();
                        echo($obj->guest)? "Регистрация":"Ваш кабинет";
                        echo $gts;
                        break;
                    case 'take_lot':
                        echo "Прием на торги";
                        echo $gts;
                        break;
                    case 'ask_about_lot':
                        echo "Задать вопрос по лоту";
                        break;
                }?></a>
        </div>
    <?php }
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function pageHead (
								$section, // todo: разобраться с неиспользуемым параметром
								$layout,
								$slug=false, // todo: разобраться с неиспользуемым параметром
								$pagination=false
							){
		$category_id=JRequest::getVar('virtuemart_category_id');
        $session=&JFactory::getSession();
        $sections_data=$session->get('section_links');
        //commonDebug(__FILE__, __LINE__, func_get_args());
        $category_data=$sections_data[$layout];
        //commonDebug(__FILE__, __LINE__, $category_data, false);
        //echo "<div>category_id = ".$category_id."</div>";
        // todo: разобраться с неиспользуемым параметром
        $section_data=$category_data[$layout]; // layout: shop, online...
        ?>
<div class="top_list">
    <h2><div class="weak"><?php
        $lots = "Лотов";
        // раздел вложенной категории
        if($category_data['top_category_id']!=$category_id){
			$category_data = $category_data['child_links'][$category_id];
            ?><span style="color:#456;"><?php
                echo $category_data['category_name'];
            ?>.</span>
        <?php
		}else {
            $lots.=" всего";
            //echo $section; // ТОП категория
        }
        ?><span style="white-space:nowrap"><?php
                echo $lots;?>: <?php
                echo $category_data['product_count'];?></span>
        </div>
    </h2>
<?php HTML::setCommonInnerMenu(array('user','take_lot'));?>
</div>
<?php $arrMenus=self::setBaseLink($layout);//
		//var_dump($arrMenus);
		//echo "<div class=''>arrMenus['base']= ".$arrMenus['base']."<br>layout = $layout</div>";
		HTML::setVmPagination($arrMenus['base'],$pagination);
	}

    /**
     * Описание
     * @package
     * @subpackage
     * layout = shop | fulltime | online
     */
    public static function setBaseLink($layout){
        $category_id=JRequest::getVar('virtuemart_category_id');
        $Itemid=JRequest::getVar('Itemid');

        $app=&JFactory::getApplication();
        $session=&JFactory::getSession();
        $user=&JFactory::getUser(); // todo: разобраться с неиспользуемым параметром
        $links=$session->get('section_links');
        $router = $app->getRouter();
        if($SefMode=$router->getMode()){
            if((int)$category_id>0){
                $detail_link['base']=$links[$layout][$category_id];
            }else{
                $menu = $app->getMenu();
                $menus = $menu->getMenu();
                $top_alias=($layout!='shop')? 'route':'alias';
                $detail_link['base']=JUri::root().$menus[$Itemid]->$top_alias;
                $detail_link['top']=true;
            }
            return $detail_link;
        }else return false;
    }
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function setCommonInnerMenu($params=false,$params_xtra=false){
		//commonDebug(__FILE__, __LINE__, $params);
        //commonDebug(__FILE__, __LINE__, $params_xtra);
		$session=&JFactory::getSession(); // todo: разобраться с неиспользуемым параметром
		$user=&JFactory::getUser();
		$pre_link='index.php?option=com_';
		if(in_array('user',$params)){
			$cab_link=($user->guest)?
				$pre_link."auction2013&layout=register"
				:
				$pre_link."users&view=login";
		}
		if(in_array('take_lot',$params)){
			//Itemid=127
			$prop_link=$pre_link."auction2013&layout=proposal";
		}
		if(in_array('ask_about_lot',$params)){
			$ask_link=$pre_link."auction2013&view=auction2013&layout=askaboutlot&lot_id=".$params_xtra['ask_about_lot'];
		}?>

	<div class="top_list_mn">
    <?php // расположить в обратном порядке, ибо float:right
		if(isset($prop_link)) HTML::innerMenu('take_lot',JRoute::_($prop_link,false));
		if(isset($ask_link)) HTML::innerMenu('ask_about_lot',JRoute::_($ask_link,false));
		if(isset($cab_link)) HTML::innerMenu('user',JRoute::_($cab_link,false),$user);?>
    </div>
<?php   //commonDebug(__FILE__, __LINE__, $prop_link.", ".$ask_link.", ".$cab_link, true);
    }
/**
 * Построить правильную ссылку
 * @package
 * @subpackage
 */
	public static function setDetailedLink($product,$layout){
		$detail_link=HTML::setBaseLink($layout);
		if (is_array($detail_link)){
			$product->link=$detail_link['base'].'/';
			if($detail_link['top'])
				$product->link.=$product->category_name.'/';
			$product->link.=$product->slug.'-detail';
		}
		return $product->link;
	}

/**
 * Описание
 * @package
 * @subpackage
 */
	public static function setVmPagination(
								$link = false,
								$pagination = false
							){?>
<div class="lots_listing">
	Лотов на странице:
    <?php $router = JFactory::getApplication()->getRouter();
		static $lnk;
		static $pag;
		if($link)
			$lnk=$link;
		if(!$lnk){
			$lnk='';
			if(!$router->getMode()){
				$arrQlink=JRequest::get('get');
				foreach ($arrQlink as $key=>$segment):
					if($key!='limit'){
						if ($lnk!='')
							$lnk.='&';
						$lnk.=$key.'='.$segment;
					}
				endforeach;
				$lnk='index.php?'.$lnk;
				//echo "<div class=''>lnk= ".$lnk."</div>";
				//option=com_virtuemart&view=category&virtuemart_category_id=6&Itemid=115&layout=shop
			}
		}

		if($pagination) $pag=$pagination->getPagesLinks();

		$arrLimits=array(15,30,60);


		foreach($arrLimits as $i=>$limit){?>
    <a href="<?php if($router->getMode()){
				echo $lnk.'/?limit='.$limit;
			}else{
				echo JRoute::_($lnk.'&limit='.$limit);

			}?>"><?=$limit?></a>
     &nbsp;
	<?php }?>
    <div class="vmPag">
		<?=$pag?>
    </div>
</div>
<?php }
}
class DateAndTime{
	private $datetime;
/**
 * Получить и сохранить как член класса массив из 2-х массивов - даты и времени
 * @package
 * @subpackage
 */
	function __construct($row_datetime=false){
		if(!$row_datetime)
			$row_datetime=date('Y-m-d H:i:s');
		$this->datetime=$this->splitDateToArrays($row_datetime);
	}
/**
 * Разбить дату и время на массивы даты и времени
 * @package
 * @subpackage
 */
	function splitDateToArrays($row_datetime){
		$dt=explode(" ",$row_datetime);
		return array( 'date'=>explode("-",$dt[0]),
					  'time'=>explode(":",$dt[1])
					);
	}
/**
 * Получить массив даты/времени с ключами для простой подстановки значений
 * @package
 * @subpackage
 */
	function getYmdHisArray(){
		$datetime=$this->datetime;
		$date=$datetime['date'];
		$time=$datetime['time'];
		return array(
					'y'=>$date[0],
					'm'=>$date[1],
					'd'=>$date[2],
					'h'=>$time[0],
					'i'=>$time[1],
					's'=>$time[2]
				);
	}
/**
 * Получить массив даты/времени как mktime
 * @package
 * @subpackage
 */
	function getMkTime($date_start=false){
		$datetime=($date_start)?
			$this->splitDateToArrays($date_start):$this->datetime;
		$date=$datetime['date'];
		$time=$datetime['time'];
		$mktime=mktime( (int)$time[0], // h
						(int)$time[1], // i
						(int)$time[2], // s
						(int)$date[1], // (n)m
						(int)$date[2], // (j)d
						(int)$date[0]  // (Y)y
					  );
		return $mktime;
	}
/**
 * Получить разницу дат
 * @return array
 * @package
 * @subpackage
 */
	function getDaysDiff($date_start,$date_finish=false){
		// получить дату начала отсчёта как mktime:
		$mktime_start=$this->getMkTime($date_start,true);
		// получить метку времени конца отсчёта:
		$mktime_finish=($date_finish)?
			$this->getMkTime($date_finish,true):time();
		// получить разницу в секундах:
		$mktime_delta=$mktime_finish-$mktime_start;
		$delta=array();
		$days=60*60*24;
		$hours=60*60;
		$minutes=60;
		// остаток дней:
		$delta['дней']=floor($mktime_delta/$days);
		$seconds_in_days=$delta['дней']*$days;
		// остаток часов:
		$calc_hours=$mktime_delta-$seconds_in_days;
		$delta['часов']=floor($calc_hours/$hours); // 2 h
		$seconds_in_hours=$delta['часов']*$hours;
		// остаток минут:
		$seconds_in_minutes=$mktime_delta-$seconds_in_hours-$seconds_in_days;
		$delta['минут']=floor($seconds_in_minutes/$minutes);
		return $delta;
	}
/**
 * Получить разницу между датами в секундах
 */
    public static function getDelta($t){
        /*$date = new DateTime();
        $now = $date->getTimestamp();*/
        $now = time();
        //commonDebug(__FILE__,__LINE__,$now); // 1405510471
        $timestamp = strtotime($t);
        //echo "<div>t = $t; $now - $timestamp</div>";
        $diff = $now-$timestamp; // timestamp diff
        return $diff;
    }

    /**
     * Конвертировать дату формата YYYY-mm-dd H:i:s в dd.mm H:i
     */
    static public function setShortDate($date=NULL){
        if($date) echo date("d.m H:i",strtotime($date));
    }
}