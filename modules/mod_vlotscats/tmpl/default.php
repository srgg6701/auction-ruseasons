<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
$test=(JRequest::getVar('test'))? true:false;
$session =JFactory::getSession();
if(!$session->get('section_links')){?>
<script>location.reload();</script>
<? 
}

	//var_dump($section_links); die();
// get categories:
$lots=modVlotscatsHelper::getCategoriesData(true);
//var_dump($lots); die();
$router = $app->getRouter();

if($SefMode=$router->getMode()){
	$menu = JFactory::getApplication()->getMenu();
	$menus = $menu->getMenu();
	//var_dump(JRequest::get('get'));
	// var_dump($menus); die();
	// Не получим virtuemart_category_id в режиме ЧПУ при загрузке профайла предмета. Используем другой способ извлечения...
	if(!$loaded_category_id=JRequest::getVar('virtuemart_category_id')){
		/*array
		  'Itemid' => string '115' (length=3)
		  'option' => string 'com_virtuemart' (length=14)
		  'limitstart' => int 0
		  'limit' => string 'int' (length=3)
		  'view' => string 'productdetails' (length=14)
		  'virtuemart_product_id' => string '551' (length=3)
		  'virtuemart_category_id' => int 0*/
		$loaded_category_id=AuctionStuff::getCategoryIdByProductId(JRequest::getVar('virtuemart_product_id')); // 9
	}
	$top_layout=$menus[JRequest::getVar('Itemid')]->query['layout']; // shop, fulltime
}?>
<br/>
<?	$top_cats_menu_ids=AuctionStuff::getTopCatsMenuItemIds();	
	// get top categories aliases to substitute them as layouts:
	$top_cats_aliases=AuctionStuff::getTopCatsLayouts();
	// online, fulltime, shop
	$a=0;
	// TODO: extract a whole link from the top cat menu params!
	// See data above: $top_cats_menu_ids
	$common_link_segment='index.php?option=com_virtuemart&view=category&virtuemart_category_id=';
$section_links=array();
//
foreach($lots as $top_cat_id => $array){
	$section_links[$top_cats_aliases[$a]]=array();
	
	if($top_cats_aliases[$a]!='online'){
	
		
		//var_dump($array); //die();
		$top_cat_count=0; 
		$andLayout='&layout='.$top_cats_aliases[$a];
		$sub_cats='
	<ul>';
		// top cat layout (online, fulltime, shop)	
		foreach($array as $key=>$array_data):
			if ($key=='children'):
				foreach($array_data as $i=>$category_data):
					//var_dump($category_data);
					$product_count=(int)$category_data['product_count'];
					$top_cat_count+=$product_count;
	
					if ($test){?>Имя категории<? }
	
					$category_link=$common_link_segment.$category_data['virtuemart_category_id'];
					$category_link.='&Itemid='.$top_cats_menu_ids[$a];
	
					if($top_cats_aliases[$a]=='shop')
						$category_link.=$andLayout;
					
					// TODO: разобраться-таки с долбанным роутером!!!!!!!!
					// кто косячит - Joomla OR VirtueMart?!
					// КАСТРИРОВАТЬ П******стов!!!!!!
					if($SefMode){
						$category_link=JUri::base(); 
						if ($top_cats_aliases[$a]=='fulltime')
							$category_link.= $menus[$menus[$top_cats_menu_ids[$a]]->parent_id]->alias.'/';
						$category_link.= $menus[$top_cats_menu_ids[$a]]->alias.'/'.$category_data['alias'];
					}
					
					$sub_cats.='
		<li><a ';
					
					if ($loaded_category_id==$category_data['virtuemart_category_id'])
						 $sub_cats.=' style="color:brown;" ';

					$sub_cats.='href="';
					
					$sub_cats.=$category_link;	
					$section_links[$top_cats_aliases[$a]][$category_data['virtuemart_category_id']]=$category_link;
		
		$sub_cats.='">'.$category_data['category_name'].'</a> ('. $product_count .')<br>
		</li>';
				endforeach;
			endif;
		endforeach;
	$sub_cats.='
	</ul>';
	
	/*	AHTUNG!!!
		Если ЧПУ отключены, добавить к ссылке $layout!
	*/
		$link=$common_link_segment.'0';
		if(!$SefMode)
			$link.=$andLayout;
		$link.='&Itemid='.$top_cats_menu_ids[$a];
		
		if( !$top_layout 
			|| $top_cats_aliases[$a]==$top_layout
			|| !in_array($top_layout,$top_cats_aliases)
		  ) {
	?>
<h3><? 

	
			if ($test){?>Имя раздела категорий<? }
		
				?><a href="<?=JRoute::_($link)?>"><?=$array['top_category_name']?></a>
	<span class="lots_count">(<?=$top_cat_count?>)</span>
</h3>
			<?	echo $sub_cats;	
	
			}
	
		}
	
	
	$a++;
	
}
 
$session->set('section_links',$section_links);
//var_dump($section_links); die();?>