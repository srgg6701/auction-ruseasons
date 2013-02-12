<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
$VirtuemartViewCategory=modVlotscatsHelper::getVMitems();

$old=false;
if (!$old):
	foreach($VirtuemartViewCategory->categories as $i=>$cat){
		$header=$VirtuemartViewCategory->escape($cat->category_name);
		if(!$item_count=$VirtuemartViewCategory->catmodel->countProducts($cat->virtuemart_category_id))
			$item_count = '0';
		if($cat->category_parent_id == '0'):?>
    <h3>
		<a href="<?=JRoute::_('index.php?option=com_'.$link)?>"><?=$header?></a>
		<span class="lots_count">(<?=$item_count?>)</span>
	</h3>
	<?	endif;
			echo $header.'<br>';
		//echo  'count: '..'<hr>';
		// else 
	} 
	var_dump($VirtuemartViewCategory->categories);
	die();

endif;
if ($old){
	$arrLots=array(
				array(
				'header'=>array('Онлайн торги','#'),
				'sections'=>array(
						'Русская живопись',
						'Советская живопись',
						'Миниатюры',
						'Иконы',
						'Фарфор, керамика',
						'Фарфор,керамика после 1917 г.',
						'Стекло, хрусталь',
						'Художественная бронза, чугун, шпиатр и пр.',
						'Часы',
						'Предметы интерьера',
						'Серебро',
						'Ювелирные изделия',
						'Восточное искусство',
						'Мебель',
						'Карты, гравюры, литографии',
						'Книги, разное',
						'Марки, открытки',
						'Ткани, прочее...',
						'Разное' 
					)),
				array(	
				'header'=>array('Очные торги','virtuemart&view=category&virtuemart_category_id=0'),
				'sections'=>array(
						'Живопись, графика',
						'Иконы',
						'Декоративно-прикладное искусство',
						'Ювелирные изделия',
						'Предметы интерьера',
						'Мебель',
						'Предметы коллекционирования'
					)),
				array(
				'header'=>array('Магазин','virtuemart&view=category&virtuemart_category_id=0'),
				'sections'=>array(
						'Русская живопись',
						'Западноевропейская живопись',
						'Советская живопись',
						'Миниатюры',
						'Иконы',
						'Фарфор, керамика',
						'Фарфор,керамика после 1917 г.',
						'Стекло, хрусталь',
						'Художественная бронза, чугун, шпиатр и пр.',
						'Часы',
						'Предметы интерьера',
						'Серебро',
						'Ювелирные изделия',
						'Восточное искусство',
						'Мебель',
						'Карты, гравюры, литографии',
						'Книги, разное',
						'Марки, открытки',
						'Ткани, прочее...',
						'Разное' 
					))	
			);
	
	
	foreach($arrLots as $i=>$dbl_array):
			$header=$dbl_array['header'][0];
			$link=$dbl_array['header'][1];?>
	<h3>
		<a href="<?=JRoute::_('index.php?option=com_'.$link)?>"><?=$header?></a>
		<span class="lots_count">(x)</span>
	</h3>
	<?	foreach($dbl_array['sections'] as $j=>$subsection):?>
	<ul>
			<li><a href="<?=JRoute::_('index.php?option=com_'.$link)?>"><?=$subsection?></a> (x)</li>
	</ul>
	<?	endforeach;
	endforeach;
}?>