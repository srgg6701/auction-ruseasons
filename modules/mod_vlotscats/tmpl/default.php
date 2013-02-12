<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
// get categories:
$lots=modVlotscatsHelper::getTopCatCounts();
foreach($lots as $top_cat_id => $array){
	ob_start();?>
<ul>
<?	$top_cat_count=0;
	foreach($array as $key=>$array_data):
		if ($key=='children'):
			foreach($array_data as $i=>$category_data):?>
	<li><a href="<?=JRoute::_('index.php?option=com_')?>"><?=$category_data['category_name']?></a> (<? 
			$product_count=(int)$category_data['product_count'];
			echo $product_count;
			$top_cat_count+=$product_count;
		?>)</li>
	<?		//var_dump($category_data);
			endforeach;
		endif;
	endforeach;?>
</ul>
<? 	$sub_cats=ob_get_contents();
	ob_clean();?>
<h3>
	<a href="<?=JRoute::_('index.php?option=com_')?>"><?=$array['top_category_name']?></a>
	<span class="lots_count">(<?=$top_cat_count?>)</span>
</h3>
<?	echo $sub_cats;	
}

die();

$VirtuemartViewCategory=modVlotscatsHelper::getVMitems();
echo "<br/>";

foreach($VirtuemartViewCategory->categories as $i=>$cat){
	$header=$VirtuemartViewCategory->escape($cat->category_name);
	if(!$item_count=$VirtuemartViewCategory->catmodel->countProducts($cat->virtuemart_category_id))
		$item_count = '0';
	if($cat->category_parent_id == '0'):?>
<h3>
	<a href="<?=JRoute::_('index.php?option=com_'.$link)?>"><?=$header?></a>
	<span class="lots_count">(<?=$item_count?>)</span>
</h3>
<?	else:?>
<ul>
	<li><a href="<?=JRoute::_('index.php?option=com_'.$link)?>"><?=$header?></a> (<?=$item_count?>)</li>
</ul>
<?	endif;
}die();?>