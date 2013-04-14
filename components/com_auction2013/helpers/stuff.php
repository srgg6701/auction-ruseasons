<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users Route Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class AuctionStuff{
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
				{	JError::raiseWarning(100, JText::_('Не удалось сохранить данные для id '.$pk.'...'));
					$errors++;
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
		if(!$links){
			$session=JFactory::getSession();
			$links=$session->get('section_links');
			//var_dump($links); echo('links?!');
		}
		foreach($links as $layout=>$categories_links):
			if(array_key_exists($virtuemart_category_id,$categories_links)){
				$category_link=$categories_links[$virtuemart_category_id];
				//echo "<div class=''>category_link= ".$category_link."</div>";
				break;
			}
		endforeach;
		return $category_link;
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function extractProductLink($virtuemart_category_id,$slug,$virtuemart_product_id=false){
		$app=&JFactory::getApplication();
		$router = $app->getRouter();
		if($SefMode=$router->getMode()){
			//echo "<div class=''>category_link= ".AuctionStuff::extractCategoryLinkFromSession($virtuemart_category_id)."/$slug-detail</div>";
			return AuctionStuff::extractCategoryLinkFromSession($virtuemart_category_id).'/'.$slug.'-detail';
		}else{
			return JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id='.$virtuemart_category_id).'&virtuemart_product_id='.$virtuemart_product_id;	
		}
	}
/**
 * Получить контент статьи
 * @package
 * @subpackage
 */
	static public function getArticleContent($id){
		$query = "SELECT * FROM #__content WHERE id = ".$id;
		//  Load query into an object
		$db = JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssoc();
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
    FROM #__virtuemart_categories AS cats1
      INNER JOIN #__virtuemart_category_categories AS cat_cats1
        ON cats1.virtuemart_category_id = cat_cats1.id
    WHERE cats1.virtuemart_category_id = ".$virtuemart_category_id;
		
		$queryGetYoungerCategory="SELECT MAX(cats2.virtuemart_category_id)
          FROM #__virtuemart_categories AS cats2
         WHERE cats2.virtuemart_category_id < ".$virtuemart_category_id;
		
		$queryGetOlderCategory="SELECT MIN(cats2.virtuemart_category_id)
          FROM #__virtuemart_categories AS cats2
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
  #__product_favorites.virtuemart_product_id,
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
 * Описание
 * @package
 * @subpackage
 */
	public static function getProductNeighborhood($virtuemart_product_id,$virtuemart_category_id){
		
		$qProdParentCategoryId="SELECT cat_cats1.category_parent_id
            FROM #__virtuemart_category_categories cat_cats1
           WHERE cat_cats1.category_child_id = ".$virtuemart_category_id;
		
		$qAllProdsInCategory="SELECT prods.virtuemart_product_id
 FROM #__virtuemart_products prods
  INNER JOIN #__virtuemart_product_categories prod_cats
          ON prods.virtuemart_product_id = prod_cats.virtuemart_product_id
  INNER JOIN #__virtuemart_category_categories cat_cats
          ON prod_cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN #__virtuemart_categories cats
          ON prod_cats.virtuemart_category_id = cats.virtuemart_category_id 
  AND cats.virtuemart_category_id = cat_cats.id
WHERE cat_cats.category_parent_id = ( ".$qProdParentCategoryId." 
                                    )
   AND prod_cats.virtuemart_category_id = ".$virtuemart_category_id;
   		
		$qPrevProdId="
		SELECT MAX(prods0.virtuemart_product_id)
          FROM #__virtuemart_products prods0
         WHERE prods0.virtuemart_product_id < ".$virtuemart_product_id;		
		
		$qNextProdId="SELECT MIN(prods00.virtuemart_product_id)
          FROM #__virtuemart_products prods00
         WHERE prods00.virtuemart_product_id > ".$virtuemart_product_id;
		 
		$query="SELECT prods_.virtuemart_product_id
  FROM #__virtuemart_products prods_
 WHERE (
        prods_.virtuemart_product_id = ( ".$qPrevProdId." 
                                       )     
        OR
        prods_.virtuemart_product_id = ".$virtuemart_product_id."
        OR
        prods_.virtuemart_product_id = ( ".$qNextProdId." 
                                       )
       ) 
  AND   prods_.virtuemart_product_id IN ( 
        ".$qAllProdsInCategory." 
       ) ";
  		
		$db=JFactory::getDBO();
		$db->setQuery($query);
		//echo "<div class=''><pre>".$query."</pre></div>"; var_dump($db->loadResultArray());die();
		return $db->loadResultArray();
	}

/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getProductPrices($virtuemart_product_id){
		// Create a new query object.
        $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select fields from the table.
		$query->select("price_quantity_start,  price_quantity_end"); 
		$query->from($db->quoteName('#__virtuemart_product_prices'));
		$query->where('virtuemart_product_id = '.$virtuemart_product_id);
		$db->setQuery($query); // а иначе вытащит старый запрос!
		//echo "<div class=''><pre>query: ".str_replace("#_","auc13",$query)."</pre></div>";
		$result=$db->loadRow();
		if(!$result[0]) $result[0]='?';
		if(!$result[1]) $result[1]='?';
		return $result;  
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function writeProductPrices($virtuemart_product_id){
		$prices=self::getProductPrices($virtuemart_product_id);
		echo $prices[0].' - '.$prices[1];
	}
	
/**
 * Получить slug продукта. В частности, чтобы дописать ссылку на предыдущий продукт в профайле текущего. 
 * @package
 * @subpackage
 */
	public static function getProdSlug($product_id){
		// 
		$query="SELECT slug
 FROM #__virtuemart_products_ru_ru
INNER JOIN #__virtuemart_products
   ON #__virtuemart_products_ru_ru.virtuemart_product_id = #__virtuemart_products.virtuemart_product_id
WHERE #__virtuemart_products.virtuemart_product_id = ".$product_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadResult(); 
	}

/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getCategoryIdByProductId($virtuemart_product_id){
		$query="SELECT cats.virtuemart_category_id
FROM #__virtuemart_categories cats
  INNER JOIN #__virtuemart_category_categories cat_cats
    ON cats.virtuemart_category_id = cat_cats.category_child_id
  INNER JOIN #__virtuemart_product_categories prod_cats
    ON cats.virtuemart_category_id = prod_cats.virtuemart_category_id 
   AND prod_cats.virtuemart_category_id = cat_cats.category_child_id
   AND prod_cats.virtuemart_product_id = ".$virtuemart_product_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadResult(); 
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
	public static function getTopCatsLayouts(){
		return array('online','fulltime','shop');
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getCatProdCount(){
		/*$query="SELECT 
		-- cats.virtuemart_category_id, 
        -- cats_ru.category_name,
        -- cats_ru.slug AS 'alias',
        (   SELECT count(p.virtuemart_product_id)
              FROM `#__virtuemart_products` AS p,
                   `#__virtuemart_product_categories` AS pc
             WHERE pc.`virtuemart_category_id` = cats.virtuemart_category_id
               AND p.`virtuemart_product_id` = pc.`virtuemart_product_id`
               AND p.`published` = '1'
               AND p.`product_in_stock` > 0
        ) AS 'product_count'
   FROM #__virtuemart_categories AS cats
   LEFT JOIN #__virtuemart_categories_ru_ru AS cats_ru 
     ON cats_ru.virtuemart_category_id = cats.virtuemart_category_id
   LEFT JOIN #__virtuemart_category_categories AS cat_cats 
     ON cat_cats.id = cats.virtuemart_category_id
  WHERE cats.`published` = '1'
        AND cats_ru.slug = '".$alias."'
        AND cat_cats.category_parent_id = ".$category_parent_id." 
  ORDER BY cat_cats.category_parent_id,cats.ordering";		
*/	
	}

/**
 * Получить ItemIds меню с layout-ами аукциона в Virtuemart'е
 * @package
 * @subpackage
 */
	public static function getTopCatsMenuItemIds(
										$menutype='mainmenu',
										$view=false,
										$layout=false
									){
		$query_start="SELECT id 
  FROM  `#__menu` 
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
			//echo "<div class=''>query= <pre>".$query."</pre></div>";
			$db->setQuery($query);
			$ItemId=$db->loadResult();
			$ItemIds[]=$ItemId;
			//echo "<div class=''>ItemId= ".$ItemId."</div>"; 
		}//die();
		return $ItemIds;
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
				<label for="<?=$value?>"><?
				if (isset($fieldArray[1])){
					?><span class="req">*</span><? 
					$req=' required';
				}else{
					$req='';
				}
				echo $fieldArray[0];?>:</label>	
		<?	if($value=='country_id'){?>
				<select id="country" name="jform[country_id]"<?=$req?>>
                    <option value="none">Выберите страну</option>
			<?	$countries=AuctionStuff::getCountries();
				foreach($countries as $code=>$country):?>
					<option value="<?=$code?>"><?=$country?></option>
			<?	endforeach;?>		
			</select>
		<?	}else{
				if (isset($fieldArray[3])):
					if($fieldArray[3]=='textarea'):
                		echo "<".$fieldArray[3]." id=\"{$value}\" name=\"jform[".$value."]\"";
						if(isset($fieldArray[4]))
							echo $fieldArray[4];
						echo $req."></".$fieldArray[3].">";
					endif;
            	else:?>
                <input type="<?=(strstr($value,"password"))? "password":"text"?>" autocomplete="off" maxlength="50" size="30" value="<?
					if ($getValue=JRequest::getVar($value))
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
        	<?	endif;
				if(isset($fieldArray[2])) 
					echo $fieldArray[2];
			}?>
			</div>
	<?	}
		$fields=ob_get_contents();
		ob_clean();
		return $fields;
	}
}

class HTML{
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
		$user=&JFactory::getUser();
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
	public static function pageHead (
								$section,
								$layout,
								$slug=false,
								$pagination=false
							){
		$category_id=JRequest::getVar('virtuemart_category_id');
?>
<div class="top_list">
    <h2><? echo $section;
		$session=&JFactory::getSession();
		$products_data=$session->get('products_data');
		$section_data=$products_data[$layout];
		if((int)$category_id>0){
			$cat=$section_data[$slug];
			$subcat="<div style='font-weight:200;font-size: 16px;
margin-top: 8px;'>".$cat['category_name']."</div>";
			$lots = $cat['product_count'];
		}else{
			$lots=$section_data['prod_count'];
		}
		echo ". <div class=\"weak\">Лотов: ".$lots."</div>";?></h2>
<?		HTML::setCommonInnerMenu(array('user','take_lot'));?>    
</div>    
<?		
		$arrMenus=self::setBaseLink($layout);//
		//var_dump($arrMenus); 
		//echo "<div class=''>arrMenus['base']= ".$arrMenus['base']."<br>layout = $layout</div>";
		HTML::setVmPagination($arrMenus['base'],$pagination);
	}

/**
 * Описание
 * @package
 * @subpackage
 */
	public static function innerMenu($content_type,$link,$obj=false){?>
        <div class="your_cab">
            <a href="<?=$link?>"><?
		$lts=' &lt; &lt; ';
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
<?	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function setCommonInnerMenu($params=false,$params_xtra=false){
		
		$session=&JFactory::getSession();
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
    <?	// расположить в обратном порядке, ибо float:right
		if(isset($prop_link)) HTML::innerMenu('take_lot',JRoute::_($prop_link,false));
		if(isset($ask_link)) HTML::innerMenu('ask_about_lot',JRoute::_($ask_link,false));
		if(isset($cab_link)) HTML::innerMenu('user',JRoute::_($cab_link,false),$user);?>
    </div>
<?	}

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
    <?	
		$router = JFactory::getApplication()->getRouter();
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
    <a href="<?
			if($router->getMode()){
				echo $lnk.'/?limit='.$limit;
			}else{
				echo JRoute::_($lnk.'&limit='.$limit);
				
			}?>"><?=$limit?></a>
     &nbsp; 
	<?	}?>
    <div class="vmPag">
		<?=$pag?>
    </div>
</div>	
<?	}
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
}