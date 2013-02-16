<?php
/**
*
* Lists all the categories in the shop
*
* @package	VirtueMart
* @subpackage Category
* @author RickG, jseros, RolandD, Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 6477 2012-09-24 14:33:54Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>
<h3>Внимание! &nbsp; &nbsp; <span style="font-weight:300;">Импортируемый файл должен быть в формате <b>.csv</b></span></h3>
<hr class="light"/>
<br/>
<p>Выберите родительский раздел для списка предметов:</p>
<?	$lots=$this->categories_data; 
$catsHTML=array();?>
<div id="top_radios">
<?
foreach($lots as $top_cat_id => $array){?>
	<label>
    	<input name="top_cat" id="top_cat_<?=$top_cat_id?>" type="radio" value="<?=$top_cat_id?>"><?=$array['top_category_name']?> &nbsp; 
    </label>
	<?	foreach($array as $key=>$array_data):
			if ($key=='children'):
				foreach($array_data as $i=>$category_data):
					$catsHTML[$top_cat_id].='	<label><input name="virtuemart_category_id" type="radio" value="'.$category_data['virtuemart_category_id'].'">'.$category_data['category_name'].' &nbsp &nbsp </label>'; 
				endforeach;
			endif;
		endforeach;?>
	<?
}?>
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
<form action="<?php echo JRoute::_('index.php?option=com_auction2013'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<p><img style="margin-left:-6px;" src="<?=JUri::root()?>administrator/templates/bluestork/images/admin/publish_y.png" width="16" height="16" align="absmiddle" /> <span id="check_flds">Сверьтесь с названиями полей импортируемого файла</span></p>
<? $av_fields=Auction2013Helper::getImportFields();?>
<table id="make_fields_control" rules="rows">
	<tr>
    	<th>Имя столба</th>
        <th>Предназначение поля</th>
    </tr>
<?	foreach($av_fields as $field=>$desc):?>
	<tr>
    	<td><?=$field?></td>
        <td><?=$desc?></td>
    </tr>
	
<?	endforeach;?>
</table>
Выберите файл для импорта данных:
	<input id="import_file" name="import_file" type="file" required>
    		<input type="hidden" name="task" value="importlots.import" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
        <br/>
        <br/>
        <div>
        	<span style="padding-right:40px;">Кодировка файла:</span>
        	<label>
            	<input name="encoding" type="radio" value="windows-1251" checked> windows-1251
            </label>
            &nbsp; &nbsp; 
            <label>
                <input name="encoding" type="radio" value="another">другая:</label> 
                <input name="alt_encoding" type="text">
        </div>
</form>
<script>
$( function(){ 
	$('input[id^="top_cat_"]')
		.click( function(){
			$('div.hiddenRadios').fadeOut(200);
			$('div#top-'+$(this).val()).fadeIn(200);
		});
	$('#check_flds')
		.click( function(){
			$('#make_fields_control').fadeToggle(200);
		});
});
Joomla.submitbutton = function(task)
{
	var err=false;
	if(!$('input[id^="top_cat_"]:checked').size()){
		err='Вы не выбрали родительский раздел для списка предметов';
	}else{
		if(!$('input[name="virtuemart_category_id"]:checked').size()){
			err='Вы не выбрали категорию предметов';
		}else{
			if(!$('input#import_file').val()){
				err='Вы не указали расположение импортируемого файла';
			}else if($('input[name="encoding"]:checked').val()=='another'&&!$('input[name="alt_encoding"]').val()){
				err='Вы не указали имя альтернативной кодировки загружаемого файла';
			}
		}
	}
	if(err){
		alert(err+'!');
		return false;
	}else{
		$('form#adminForm').submit();
	}
}
</script>
