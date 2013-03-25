<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>
<h2>Отметьте категории предметов, таблицы с которыми вы хотите очистить:</h2>
<?	$lots=$this->categories_data; 
$catsHTML=array();?>
<form action="<?php echo JRoute::_('index.php?option=com_auction2013'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div id="delete_products">
<table id="trash_products">
	<tr valign="top">
<?	
$cols=0;
foreach($lots as $top_cat_id => $categories){
	$cols++;?>
		<td>
    		<label class="header">
            	<h4><input name="jform[<?=$categories['top_category_layout']?>]" type="checkbox" value="<?=$categories['top_category_layout']?>"><?
	echo $categories['top_category_name'];?></h4>
    		</label>
            <input name="section[<?=$categories['top_category_layout']?>]" type="hidden" value="<?=$categories['top_category_name']?>">
            <div class="clearfix"></div>
            <div class="children">
<?	//var_dump($array);
	foreach($categories['children'] as $i=>$category){
		if((int)$category['product_count']){?>
		<label>
        	<input name="<?=$categories['top_category_layout']?>[<?=$category['virtuemart_category_id']?>]" type="checkbox" value="<?=$category['virtuemart_category_id']?>"><?=$category['category_name']?> (<?=$category['product_count']?>)
        </label>
<?
/*	'top_category_alias' => string 'onlajn-torgi' (length=12)
  	'top_category_name' => string 'Онлайн торги' (length=23)
  	'top_category_layout' => string 'online' (length=6)
  	'children' => 
		array
		  0 => 
			array
			  'virtuemart_category_id' => string '31' (length=2)
			  'category_name' => string 'Русская живопись' (length=31)
			  'alias' => string 'живопись-руси' (length=25)
			  'product_count' => string '0' (length=1)
		  1 => 
			array
			  'virtuemart_category_id' => string '32' (length=2)
			  'category_name' => string 'Советская живопись' (length=35)
			  'alias' => string 'sovetskaya-zhivopis2013-02-12-08-49-59_' (length=39)
			  'product_count' => string '0' (length=1)	*/
		}
	}?>
    		</div>
    	</td>
<?	
} ?>
	</tr>
</table>
</div>
<br/><br/>
<hr class="light"/>
<br/>
<?	foreach($catsHTML as $top_category_id=>$child_categories_html):?>
<div id="top-<?=$top_category_id?>" class="hiddenRadios" style="display:none;">
    <div class="radiocats">
    <?=$child_categories_html?>
	</div>
<br/><br/>
<hr class="light"/>
<br/>
</div>
<?	endforeach;?>
    	<input type="hidden" name="task" value="importlots.clear" />
		<? //<input type="hidden" name="boxchecked" value="0" /> ?>
		<?php echo JHtml::_('form.token'); ?>
</form>
<script>
$( function(){ 
	$('label.header input[type="checkbox"]')
		.click( function(){
			var childBoxes=$(this)
				.parents('td').find('input[type="checkbox"]');
			var childLabels=$(this)
				.parents('td').find('label');
			if(this.checked){
				$(childBoxes)
					.attr('checked',true);
				$(childLabels).attr('class','clearChecked');
				$(this).parents('label').attr('class','header');
			}else{
				$(childBoxes)
					.attr('checked',false);
				$(childLabels).removeClass('clearChecked');
			}
		}); 
	$('div.children input[type="checkbox"]')
		.click( function(){
			if(this.checked)
				$(this).parents('label').attr('class','clearChecked');
			else
				$(this).parents('label').removeClass('clearChecked');
		});
});
Joomla.submitbutton = function()
{
	var err=false;
	if(err){
		alert(err+'!');
		return false;
	}else{
		$('form#adminForm').submit();
	}
}
</script>
