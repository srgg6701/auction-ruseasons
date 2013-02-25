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
	public static function sreateForm($arrFields){		
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
	public static function pageHead (
								$section,
								$layout,
								$category_id,
								$slug=false,
								$pagination=false
							){?>
<div class="top_list">
    <h2><? echo $section;
	
		$app=&JFactory::getApplication();
		$session=&JFactory::getSession();
		$user=&JFactory::getUser();
		
		$results=1; //results,1-80
		
		$products_data=$session->get('products_data');
		$section_data=$products_data[$layout];
		$links=$session->get('section_links');
		//var_dump($links[$layout]); 
		$router = $app->getRouter();
		
		if($SefMode=$router->getMode()){
			if((int)$category_id>0){
				$link=$links[$layout][$category_id];
				$detail_link['base']=$link;
			}else{ 
				$menu = $app->getMenu();
				$menus = $menu->getMenu();
				$link=JUri::root();
				
				$top_alias=($layout!='shop')? 'route':'alias';
				$link.=$menus[JRequest::getVar('Itemid')]->$top_alias;
				$detail_link['base']=$link;
			}
			//var_dump($menus[JRequest::getVar('Itemid')]);
			//echo('link = '.$link); 
			$cab_link=($user->guest)?
				"index.php/component/auction2013/?layout=register"
				: 
				"index.php/component/users/?view=login";
			$prop_link="index.php/аукцион/predlozhit-predmet";
			//var_dump(JRequest::get('get'));
		}else{
	 		$link='index.php?option=com_virtuemart&view=category&layout='.$layout;
			$cab_link=($user->guest)? 
				"index.php?option=com_auction2013&layout=register"
				:
				"index.php?option=com_users&view=login";
			$prop_link="index.php?option=com_auction2013&view=auction2013&layout=proposal";
		}
		//var_dump($links);
		//var_dump($section_data); //die();
		if((int)$category_id>0){
			$cat=$section_data[$slug];
			$subcat="<div style='font-weight:200;font-size: 16px;
margin-top: 8px;'>".$cat['category_name']."</div>";
			$lots = $cat['product_count'];
		}else{
			$lots=$section_data['prod_count'];
			$detail_link['top']=true;
		}
		echo ". Лотов: ".$lots;?></h2>
	<div class="top_list_mn">
        <div class="your_cab">
            <a href="<?=$prop_link?>"> Прием на торги &gt;&gt; </a>
        </div>
        <div class="your_cab">
            <a href="<?=$cab_link?>"><?=($user->guest)? "Регистрация":"Ваш кабинет"?> &gt;&gt; </a>
        </div>	
    </div>
</div>    
<?		HTML::setVmPagination($SefMode,$link,$pagination);
		return ($SefMode)? $detail_link:true; 
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
/**
 * Построить правильную ссылку
 * @package
 * @subpackage
 */
	public static function setDetailedLink($product,$detail_link=false){
		if (is_array($detail_link)){
			$product->link=$detail_link['base'].'/';
			if($detail_link['top'])
				$product->link.=$product->category_name.'/';
			$product->link.=$product->slug.'-detail';
		}
		return $product->link;
	}

// 
}?>