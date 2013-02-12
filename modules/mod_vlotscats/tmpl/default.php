<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
// get categories:
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
}?>