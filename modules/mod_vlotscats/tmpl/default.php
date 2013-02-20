<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
// get categories:
$lots=modVlotscatsHelper::getCategoriesData(true);
//var_dump($lots); die();
$router = $app->getRouter();
if($SefMode=$router->getMode()){
	$menu = JFactory::getApplication()->getMenu();
	$menus = $menu->getMenu();
	//var_dump($menus);die(); //
}
?>
<br/>
<?	$top_cats_menu_ids=AuctionStuff::getTopCatsMenuItemIds();	
	// get top categories aliases to substitute them as layouts:
	$top_cats_aliases=AuctionStuff::getTopCatsLayouts();
	//var_dump($top_cats_aliases);
	$a=0;
	// TODO: extract a whole link from the top cat menu params!
	// See data above: $top_cats_menu_ids
	$common_link_segment='index.php?option=com_virtuemart&view=category&virtuemart_category_id=';
foreach($lots as $top_cat_id => $array){
	//var_dump($array); //die();
	$top_cat_count=0;
	$andLayout='&layout='.$top_cats_aliases[$a];
	$sub_cats='
<ul>';
	// top cat layout (online, fulltime, shop)	
	foreach($array as $key=>$array_data):
		if ($key=='children'):
			foreach($array_data as $i=>$category_data):
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
	<li><a href="';
				
				$sub_cats.=$category_link;	
	$sub_cats.='">'.$category_data['category_name'].'</a> ('. $product_count .')<br>
	</li>';
			endforeach;
		endif;
	endforeach;
$sub_cats.='
</ul>';?>
<h3><? if ($test){?>Имя раздела категорий<? }

	/*	AHTUNG!!!
		Если ЧПУ отключены, добавить к ссылке $layout!
	*/
		$link=$common_link_segment.'0';
		if(!$SefMode)
			$link.=$andLayout;
		$link.='&Itemid='.$top_cats_menu_ids[$a];
		
		?><a href="<?=JRoute::_($link)?>"><?=$array['top_category_name']?></a>
	<span class="lots_count">(<?=$top_cat_count?>)</span>
</h3>
<?	echo $sub_cats;	
	$a++;
}
?>