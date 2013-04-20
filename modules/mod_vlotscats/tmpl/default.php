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
	if($test){
		var_dump(JRequest::get('get'));
		/* очные торги:
		/аукцион 
		/очные-торги 
		/антикварное-оружие 
		/лот-266-кинжал-в-ножнах-кард-1-я-половина-xix-в-detail
		
		  'Itemid' => string '126' (length=3)
		  'option' => string 'com_virtuemart' (length=14)
		  'limitstart' => int 0
		  'limit' => string 'int' (length=3)
		  'view' => string 'productdetails' (length=14)
		  'virtuemart_product_id' => string '1717' (length=4)
		  'virtuemart_category_id' => string '52' (length=2) */
		var_dump($menus); //die();
		/*	
		array
		  101 => 
			object(stdClass)[108]
			  public 'id' => string '101' (length=3)
			  public 'menutype' => string 'mainmenu' (length=8)
			  public 'title' => string 'Главная' (length=14)
			  public 'alias' => string 'home' (length=4)
			  public 'note' => string '' (length=0)
			  public 'route' => string 'home' (length=4)
			  public 'link' => string 'index.php?option=com_content&view=article&id=1' (length=46)
			  public 'type' => string 'component' (length=9)
			  public 'level' => string '1' (length=1)
			  public 'language' => string '*' (length=1)
			  public 'browserNav' => string '0' (length=1)
			  public 'access' => string '1' (length=1)
			  public 'params' => 
				object(JRegistry)[178]
				  protected 'data' => 
					object(stdClass)[179]
					  ...
			  public 'home' => string '1' (length=1)
			  public 'img' => string '' (length=0)
			  public 'template_style_id' => string '0' (length=1)
			  public 'component_id' => string '22' (length=2)
			  public 'parent_id' => string '1' (length=1)
			  public 'component' => string 'com_content' (length=11)
			  public 'tree' => 
				array
				  0 => string '101' (length=3)
			  public 'query' => 
				array
				  'option' => string 'com_content' (length=11)
				  'view' => string 'article' (length=7)
				  'id' => string '1' (length=1)
		  101 =>
		  object(stdClass)[110]
			  public 'id' => string '113' (length=3)
			  public 'menutype' => string 'mainmenu' (length=8)
			  public 'title' => string 'Аукцион' (length=14)
			  public 'alias' => string 'аукцион' (length=14)
			  ...
			  ... 	*/
	}
	// Не получим virtuemart_category_id в режиме ЧПУ при загрузке профайла предмета. Используем другой способ извлечения...
	if(!$loaded_category_id=JRequest::getVar('virtuemart_category_id')){
		$loaded_category_id=AuctionStuff::getCategoryIdByProductId(JRequest::getVar('virtuemart_product_id')); // 9
	}
	$top_layout=$menus[JRequest::getVar('Itemid')]->query['layout']; // shop, fulltime
}?>
<br/>
<?	$top_cats_menu_ids=AuctionStuff::getTopCatsMenuItemIds();	
	// get top categories aliases to substitute them as layouts:
	$top_cats_aliases=AuctionStuff::getTopCatsLayouts(); 
	// var_dump($top_cats_aliases); //die();
	// online, fulltime, shop
	$a=0;
	// TODO: extract a whole link from the top cat menu params!
	// See data above: $top_cats_menu_ids
	$common_link_segment='index.php?option=com_virtuemart&view=category&virtuemart_category_id=';
$section_links=array();
//
foreach($lots as $top_cat_id => $array){
	//if ($array){}
	$section_links[$top_cats_aliases[$a]]=array();
	
	if($top_cats_aliases[$a]!='online'){
		if($test){
			echo "<div class=''>LINE: ".__LINE__."</div>";
				echo "<hr><div class=''>top_cats_menu_ids:</div>";
			var_dump($top_cats_menu_ids);
				echo "<hr><div class=''>top_cats_aliases:</div>";
			var_dump($top_cats_aliases);
				// echo "<hr><div class=''>array:</div>";
			//var_dump($array);
			echo "<hr>";
		}
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
		
			// die();
		if( !$top_layout 
			|| $top_cats_aliases[$a]==$top_layout
			|| !in_array($top_layout,$top_cats_aliases)
		  ) {
			// test as JRequest::getVar('test')
			if ($test){
				?><h4>Имя раздела категорий (LINE: <?=__LINE__?>)</h4><?  
				echo "<div style='border: solid 1px #ccc; padding:10px; background-color:lightskyblue;display:inline-block'>top_layout= ".$top_layout."</div>";
				echo "<div style='border: solid 1px #ccc; padding:10px; background-color:#eee;display:inline-block'>top_cats_aliases[$a]= ".$top_cats_aliases[$a]."</div>";
				echo "<div style='border: solid 1px #ccc; padding:10px; background-color:lightyellow;display:inline-block'>top_cats_aliases:";
				var_dump($top_cats_aliases);
				echo "</div>";
			
			}else{?><h3><a href="<?=JRoute::_($link)?>"><?=$array['top_category_name']?></a>
	<span class="lots_count">(<?=$top_cat_count?>)</span>
</h3>
			<?	echo $sub_cats;	
			}
		}
	}
	
	$a++;

}
if ($test) die(); 
$session->set('section_links',$section_links);
//var_dump($section_links); die();?>