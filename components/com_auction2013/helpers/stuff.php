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
		}
		foreach($links as $layout=>$categories_links):
			if(array_key_exists($virtuemart_category_id,$categories_links)){
				$category_link=$categories_links[$virtuemart_category_id];
				break;
			}
		endforeach;
		return $category_link;
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
 * Получить slug продукта. В частности, чтобы дописать ссылку на предыдущий продукт в профайле текущего. 
 * @package
 * @subpackage
 */
	function getProdSlug($product_id){
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
	function getSingleProductData($product_id,$fields=false){
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
	function getCatProdCount(){
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
	public static function getTopCatsMenuItemIds(){
		$layouts=AuctionStuff::getTopCatsLayouts();
		$query_start="SELECT id 
  FROM  `#__menu` 
 WHERE  `menutype` =  'mainmenu'
   AND link REGEXP  '(^|/?|&|&amp;)layout=";
		$query_end="($|&|&amp;)'";
		$db = JFactory::getDBO();
		$ItemIds=array();
		foreach($layouts as $i=>$layout){
			$query=$query_start.$layout.$query_end;
			//echo "<div class=''>query= <pre>".$query."</pre></div>";
			$db->setQuery($query);
			$ItemId=$db->loadResult();
			$ItemIds[]=$ItemId;
			//echo "<div class=''>ItemId= ".$ItemId."</div>"; 
		}
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
		echo ". Лотов: ".$lots;?></h2>
<?		HTML::setCommonInnerMenu(array('user','take_lot'));?>    
</div>    
<?		HTML::setVmPagination($SefMode,$link,$pagination);
	}
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
								$SefMode = false,
								$link = false,
								$pagination = false
							){?>	
<div class="lots_listing">
	Лотов на странице: 
    <?	static $sef;
		static $lnk;
		static $pag;
		
		if($link) $lnk=$link;
		if($SefMode) $sef=$SefMode;
		if($pagination) $pag=$pagination->getPagesLinks();
		
		$arrLimits=array(15,30,60);
		
		foreach($arrLimits as $i=>$limit){
			if($sef){
				$go_limit=$lnk.'/?';
			}else{
				$go_limit=JRoute::_($lnk.'&');
			}?>
    <a href="<?=$go_limit.'limit='.$limit?>"><?=$limit?></a>
     &nbsp; 
	<?	}?>
    <div class="vmPag">
		<?=$pag?>
    </div>
</div>	
<?	}
// 
}?>