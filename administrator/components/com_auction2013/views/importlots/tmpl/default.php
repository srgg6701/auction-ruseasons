<?php

#########################################################
#														#
#			ВНИМАНИЕ! Перед импортом предметов 			#
#			необходимо убедиться в том, что их			#
#			категория уже создана (в VirtueMart'е)		#
#														#
#########################################################	

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>
<h2>Выберите родительский раздел для списка предметов:</h2>
<span style="font-size:15px;">(если вы не видите здесь нужную категорию, вам необходимо создать её в разделе <a href="?option=com_virtuemart&view=category">VirtueMart</a>.)</span>
<hr>
<br>
<?php
$lots=$this->categories_data;
//commonDebug(__FILE__,__LINE__,$lots, true);
$catsHTML=array();?>
<form action="<?php echo JRoute::_('index.php?option=com_auction2013'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div id="top_radios">
<?php
foreach($lots as $top_cat_id => $array){
    //commonDebug(__FILE__,__LINE__,$array);
    ?>
	<label class="top_section">
    	<input name="top_cat" id="top_cat_<?=$top_cat_id?>" data-top_cat_id="<?=$top_cat_id?>" type="radio" value="<?=$array['top_category_layout']?>"><?=$array['top_category_name']?> &nbsp; </label>
	<?php foreach($array as $key=>$array_data):
			if ($key=='children'):
				foreach($array_data as $i=>$category_data):
					$catsHTML[$top_cat_id].='	<label>
	<input name="virtuemart_category_id" type="radio" value="'.$category_data['virtuemart_category_id'].'">'.$category_data['category_name'].' &nbsp &nbsp </label>'; 
				endforeach;
			endif;
		endforeach;?>
	<?php }?>
</div>
<br/><br/>
<hr class="light"/>
<br/>
<?php foreach($catsHTML as $top_category_id=>$child_categories_html):?>
<div id="top-<?=$top_category_id?>" class="hiddenRadios" style="display:none;">
    <div class="radiocats">
    <?=$child_categories_html?>
	</div>
<br/><br/>
<hr class="light"/>
<br/>
</div>
<?php endforeach;?>
<p><img style="margin-left:-6px;" src="<?=JUri::root()?>administrator/templates/bluestork/images/admin/publish_y.png" width="16" height="16" align="absmiddle" /> <span id="check_flds" title="Щёлкните, чтобы увидеть набор/формат допустимых полей">Сверьтесь с названиями полей импортируемого файла</span></p>
<?php $av_fields=Auction2013Helper::getImportFields();?>
<div id="csv_pattern" style="overflow:auto;">
<h4 style="margin:auto auto 8px 4px;">Имя столбца, предназначение поля, обязательный (если указан) формат ввода данных <span style="font-weight:200;">(ЧЧ:ММ:CC &#8212; не обязательно для даты/времени)</span>:</h4>
<table id="make_fields_control">
	<tr>
<?php foreach($av_fields as $field=>$desc):?>
    	<td><?=$field?></td>
<?php endforeach;?>
    </tr>
    <tr bgcolor="#FFF">
<?php foreach($av_fields as $field=>$desc):?>
        <td><?php $adesc=explode("|",$desc);
		$av_fields[$field]=$adesc[1];
		echo $adesc[0];
		?></td>
<?php endforeach;?>
    </tr>
    <tr bgcolor="lightgoldenrodyellow">
<?php foreach($av_fields as $field=>$desc):?>
        <td><?=$desc?></td>
<?php endforeach;?>
    </tr>
</table>
</div>
Выберите файл для импорта данных (формат <b style="color:red" title="Commas Separated Values">.CSV</b>):
	<input id="import_file" name="import_file" type="file" required>
    	<input type="hidden" name="task" value="importlots.import" />
		<?php //<input type="hidden" name="boxchecked" value="0" /> ?>
		<?php echo JHtml::_('form.token'); ?>
        <br/>
        <br/>
        <div id="encodings">
        	<span style="padding-right:40px;">Кодировка файла:</span>
        	<label>
            	<input id="encoding_win" name="encoding" type="radio" value="windows-1251" checked> windows-1251
            </label>
        	<label>
            	<input id="encoding_utf" name="encoding" type="radio" value="utf-8" > utf-8
            </label>
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
			$('div#top-'+$(this).attr('data-top_cat_id')).fadeIn(200);
		});
	$('#check_flds')
		.click( function(){
			$('#csv_pattern').fadeToggle(200);
		});
	
	var labelsTopSection=$('label input[name="top_cat"]');
	$(labelsTopSection)
		.click( function(){
			$(labelsTopSection).parent('label').removeClass('checked');
			$(this).parent('label').addClass('checked');
		});
	var labelsVmRadios=$('label input[name="virtuemart_category_id"]');
	$(labelsVmRadios)
		.click( function(){
			$(labelsVmRadios).parent('label').removeAttr('class');
			$(this).parent('label').attr('class','checked');
		});
	var labelEncodingRadios=$('div#encodings label input[type="radio"]');
	$(labelEncodingRadios)
		.click( function(){
			$(labelEncodingRadios).parent('label').css('background','transparent');
			$(this).parent('label').css('background','#CCC');
		});
});
Joomla.submitbutton = function()
{
	var err=false;
	if(!document.querySelectorAll('input[id^="top_cat_"]:checked').length){
		/**
            С точки зрения отнесения категории предмета к родительской
			категории отметка данного чекбокса никакой роли не играет, 
			поскольку вышеуказанная принадлежность определяется по id 
			самой категории. Однако это необходимо для того, чтобы 
			юзер выбрал правильную секцию, которая, как раз, и 
			ограничивает диапазон id id категорий. */
		err='Вы не выбрали родительский раздел для списка предметов';
	}else{
		if(!document.querySelectorAll('input[name="virtuemart_category_id"]:checked').length){
			err='Вы не выбрали категорию предметов';
		}else{
			if(!document.getElementById('import_file').value){
				err='Вы не указали расположение импортируемого файла';
			}else if( document.querySelector('input[name="encoding"]:checked').value=='another'
                      && !document.querySelector('input[name="alt_encoding"]').value){
				err='Вы не указали имя альтернативной кодировки загружаемого файла';
			}
		}
	}
	if(err){
		alert(err+'!');
	}else{
		document.getElementById('adminForm').submit();
	}
}
</script>
