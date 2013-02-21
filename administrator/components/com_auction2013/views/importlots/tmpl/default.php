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
<form action="<?php echo JRoute::_('index.php?option=com_auction2013'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div id="top_radios">
<?
foreach($lots as $top_cat_id => $array){?>
	<label>
    	<input name="top_cat" id="top_cat_<?=$top_cat_id?>" type="radio" value="<?=$top_cat_id?>, but does not matter here. See relations at virtuemart_category_categories, virtuemart_categories"<? 
	if(strstr($array['top_category_name'],"Онлайн торги"))
		{?> disabled title="Опция в разработке"<? }?>><?=$array['top_category_name']?> &nbsp; </label>
	<?	foreach($array as $key=>$array_data):
			if ($key=='children'):
				foreach($array_data as $i=>$category_data):
					$catsHTML[$top_cat_id].='	<label>
	<input name="virtuemart_category_id" type="radio" value="'.$category_data['virtuemart_category_id'].'">'.$category_data['category_name'].' &nbsp &nbsp </label>'; 
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
<p><img style="margin-left:-6px;" src="<?=JUri::root()?>administrator/templates/bluestork/images/admin/publish_y.png" width="16" height="16" align="absmiddle" /> <span id="check_flds">Сверьтесь с названиями полей импортируемого файла</span></p>
<? $av_fields=Auction2013Helper::getImportFields();?>
<table id="make_fields_control">
	<tr>
    	<th>Имя столбца:</th>
<?	foreach($av_fields as $field=>$desc):?>
    	<td><?=$field?></td>
<?	endforeach;?>
    </tr>
    <tr bgcolor="#FFF">
        <th>Предназначение поля:</th>
<?	foreach($av_fields as $field=>$desc):?>
        <td><?=$desc?></td>
<?	endforeach;?>
    </tr>
</table>
Выберите файл для импорта данных:
	<input id="import_file" name="import_file" type="file" required>
    	<input type="hidden" name="task" value="importlots.import" />
		<? //<input type="hidden" name="boxchecked" value="0" /> ?>
		<?php echo JHtml::_('form.token'); ?>
        <br/>
        <br/>
        <div>
        	<span style="padding-right:40px;">Кодировка файла:</span>
        	<label>
            	<input id="encoding" name="encoding" type="radio" value="windows-1251" checked> windows-1251
            </label>
            &nbsp; &nbsp; 
            <label>
                <input id="encoding_alt" name="encoding" type="radio" value="another">другая:</label> 
                <input id="alt_encoding" name="alt_encoding" type="text">
        </div>
</form>
<script>
$( function(){  
	$('input#alt_encoding').click( function(){
			$('input#encoding_alt').attr('checked',true);
		});
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
Joomla.submitbutton = function()
{
	var err=false;
	if(!$('input[id^="top_cat_"]:checked').size()){
		/*	С точки зрения отнесения категории предмета к родительской
			категории отметка данного чекбокса никакой роли не играет, 
			поскольку вышеуказанная принадлежность определяется по id 
			самой категории. Однако это необходимо для того, чтобы 
			юзер выбрал правильную секцию, которая, как раз, и 
			ограничивает диапазон id id категорий.
		*/
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
