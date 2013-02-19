<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
// get categories:
$lots=modVlotscatsHelper::getCategoriesData(true);?>
<br/>
<?	$top_cats_menu_ids=AuctionStuff::getTopCatsMenuItemIds();	
	// get top categories aliases to substitute them as layouts:
	$top_cats_aliases=AuctionStuff::getTopCatsLayouts();
	$a=0;
foreach($lots as $top_cat_id => $array){
	$top_cat_count=0;
	$sub_cats='
<ul>';
	foreach($array as $key=>$array_data):
		if ($key=='children'):
			foreach($array_data as $i=>$category_data):
				$product_count=(int)$category_data['product_count'];
				$top_cat_count+=$product_count;
				$sub_cats.='
	<li><a href="'.JRoute::_('index.php?option=com_').'">'.$category_data['category_name'].'</a> ('. $product_count .')</li>';
			endforeach;
		endif;
	endforeach;
$sub_cats.='
</ul>';?>
<h3>
	<a href="<?=JRoute::_('index.php?option=com_virtuemart&view=category&Itemid='.$top_cats_menu_ids[$a])?>"><?=$array['top_category_name']?></a>
	<span class="lots_count">(<?=$top_cat_count?>)</span>
</h3>
<?	echo $sub_cats;	
	$a++;
}?>