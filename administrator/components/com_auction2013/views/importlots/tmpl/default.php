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
window.onload=function(){
	document.getElementById('alt_encoding').onclick = function(){
        document.getElementById('encoding_alt').checked=true;
	};
    var inputTopCats=document.querySelectorAll('input[id^="top_cat_"]'); // online, fulltime, shop
    var hiddenRadios=document.querySelectorAll('div.hiddenRadios');
    //console.dir(hiddenRadios);
    for(var i in inputTopCats){
        var topSectionRadio = inputTopCats[i];
        if(topSectionRadio.id) { // патамушта может быть и функция
            //console.log(topSectionRadio);
            topSectionRadio.addEventListener('click', function (event) {
                for (var j in hiddenRadios) {
                    var catRadiosBlock=hiddenRadios[j];
                    //console.log('catRadiosBlock = '+catRadiosBlock);
                    var nativeId='top-' + event.currentTarget.getAttribute('data-top_cat_id');
                    //console.dir('currentTarget = '+event.currentTarget);
                    //console.log('nativeId = '+nativeId);
                    if (typeof catRadiosBlock=='object') {// патамушта может быть null
                        //console.log('catRadiosBlock is '+(typeof catRadiosBlock));
                        if(catRadiosBlock.id==nativeId)
                            catRadiosBlock.style.display = 'block';
                        else
                            catRadiosBlock.style.display = 'none';
                    }
                }
            });
        }
    }
    // обработка видимости блока с пояснением
    var csv_pattern = document.getElementById('csv_pattern');
    document.getElementById('check_flds').onclick=function(){
        csv_pattern.style.display=(csv_pattern.style.display=="none")? "block":"none";
    };

    // обработать коллекцию найденных элементов
    var handleObjects = function(objectsList,func){
        for(var i in objectsList){
            if(typeof objectsList[i]=='object'){
                objectsList[i].addEventListener('click', function(event){
                    for(var i2 in objectsList){
                        if(typeof objectsList[i2]=='object'){
                            func(objectsList[i2]);
                        }
                    }
                    func(event);
                });
            }
        }
    };
    // обработка радиокнопок ТОП-категорий
    var handleTopSections = function(obj){
            if(obj.id) obj.parentNode.className='top_section';
            else obj.currentTarget.className='top_section checked';
        },
        labelsTopSections=document.querySelectorAll('label input[name="top_cat"]');
    handleObjects(labelsTopSections,handleTopSections);

    // обработка радиокнопок вложенных категорий
    var handleLabelsRadios = function(obj){
            if(obj.id) obj.parentNode.removeAttribute('class');
            else obj.currentTarget.className='checked';
        },
        labelsVmRadios=document.querySelectorAll('label input[name="virtuemart_category_id"]');
    handleObjects(handleLabelsRadios,handleLabelsRadios);

    // обработка радиокнопок с выбором кодировки импортируемого файла
    var handleEncodingRadios = function(obj){
            if(obj.id) obj.parentNode.style['background'] = 'transparent';
            else obj.currentTarget.parentNode.style.background = '#CCC';
        },
        labelEncodingRadios=document.querySelectorAll('div#encodings label input[type="radio"]');
    handleObjects(labelEncodingRadios,handleEncodingRadios);
};
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
