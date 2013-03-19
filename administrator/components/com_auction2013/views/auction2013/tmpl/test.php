<?php
/**
 * @version     2.1.0
 * @package     com_auction2013
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
// Import CSS
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_auction2013/assets/css/auction2013.css');
$user	= JFactory::getUser();
$userId	= $user->get('id');?>
<style>
button[type="submit"]{
	display:block; 
	margin:4px 0 10px 0; 
	padding:6px 10px;
}
table tr td:not(:last-child){
	border-right:dashed 1px #999;
}
th{
	padding:4px;
}
div.imgBlock{
	max-width:120px;
	overflow:hidden;
	white-space:nowrap;
}
label{
	background-color: #CAF1A7;
	border-radius: 4px;
	display: inline-block;
	padding-bottom: 4px;
	margin-bottom: 4px;
	padding: 4px 8px 4px 2px;
	white-space: nowrap;
}
label.checked{
	background-color:#489136;
	color:#FFF;
}
.come{
	background-color:#FFCC00;
}
</style>
	<!--<h2>Экспорт предметов</h2>-->
<div>
<form action="<?php echo JRoute::_('index.php?option=com_auction2013&view=auction2013&layout=test'); ?>" method="post" name="adminForm" id="adminForm">
    <!--<h4>Предметы и изображения <span style="font-weight:200;">(http://auction-ruseasons.ru/items_images/)</span>:</h4>-->
    <?	$post=JRequest::get('post');
		$cats=Test::getCategoriesToExport();
		foreach($cats as $n=>$data):
			$catid=$data['category_id'];
			if(isset($post['category_id'])&&in_array($catid,$post['category_id']))
				$catnames[$catid]=$data['category_name'];
		endforeach;
		if(isset($post['category_id'])):
			$source_prods=Test::getDataToExport($post['category_id']);
			if(count($source_prods)):?>
        <h4 style="padding:6px 10px; margin:8px 0; background-color: #FF6; display:inline-block; clear:both;">Получено <a id="show_recs" href="javascript:location.href='#tbl_recs'" title="Показать таблицу экспортированных записей">записей</a>: <?=count($source_prods)?></h4>
			<?	$filename='/_docs/get_csv/'.array_shift($catnames)."___".array_pop($catnames).'.csv';
				$filename=str_replace(" ","_",$filename);
				$winfilename=iconv("UTF-8","windows-1251",$filename);
				$fp = fopen(JPATH_SITE.$winfilename, 'w');
				foreach ($source_prods as $fields):
					fputcsv($fp, $fields);
				endforeach;
				fclose($fp);?>
            <h4 style="margin-bottom:14px; font-weight:200;">
            	Данные успешно экспортированы и сохранены в <a href="<?=JUri::root().$filename?>" title="Просмотреть контент">файле</a>: 
            	<span style="padding:8px; background-color:#FFFF99;"><?=JPATH_SITE.$filename?></span>
            </h4>
		<?	else:?>
    <h4>Данных не обнаружено...</h4>
		<?	endif;?>
    <hr>
	<?	endif;?>
    <h4>
    	Выберите категорию предметов для синхронизации данных <span style="font-weight:100">(будет выбрано записей: <span id="recs">0</span>)</span>:
    </h4>
    <?	foreach($cats as $n=>$data):?>
        <label<?
        	if( isset($source_prods)
			    && in_array($data['category_id'],$post['category_id'])
			  ):?> class="come"<? 
			endif;
		?>><input id="category_id[<?=$data['category_id']?>]" name="category_id[<?=$data['category_id']?>]" type="checkbox" value="<?=$data['category_id']?>">&nbsp;<?=$data['category_name']?> (<span id="count_<?=$data['category_id']?>"><?=$data['count']?></span>) </label>&nbsp;
    <?	endforeach;
		if($post['category_id']): 
			if(count($source_prods)):?>
    <h4><a name="tbl_recs">Экспортированные записи:</a></h4>
    <table id="tblRecs" border="1" rules="rows" style="display:<?="none"?>;">
	<?			// var_dump($source_prods); 
				foreach($source_prods as $i=>$data):?>
    	<tr title="Запись id <?=($i+1)?>">
				<?	if(!$i):	
						foreach ($source_prods[0] as $field=>$th):?>
       	  	<th class="come"><nobr><?=$field?></nobr></th>    	
		<?				endforeach;?>
		</tr>
        <tr>
				<?	endif;
					foreach ($data as $key=>$value):
						$rvalue=(strstr($key,"desc"))? substr($value,0,100):$value;?>
            <td>
            	<div<?
				// to export data use just $value, not $rvalue!
            			if(strstr($key,"optf")):?> style="max-width:40px;overflow:hidden;"<? endif;
					?>><?
						if($key=='images'):
							$images=Test::getImagesToExport($data['id']);
							if(!empty($images)):
								foreach($images as $m=>$imgs):
									foreach($imgs as $field=>$img):
										if($field=='full_filename'||$field=='thumb_filename'):?>
                    <div title="<?=$field?>" class="imgBlock"><?=$img?></div>
									<?	endif;	
									endforeach;	
								endforeach;
							endif;
						else:
							unset($images);
							if($key=='ends'||$key=='date'):
						?><nobr><?=date('Y-m-d',$rvalue)?></nobr><?
							else:
								echo $rvalue;
							endif;
						endif;
			?></div></td>
				<?	endforeach;?>
        </tr>
			<?	endforeach;?>    
    </table>
		<?	endif;	
		endif;?>
	  <?php echo JHtml::_('form.token'); ?>
</form>
</div>
<script>
$( function(){
	var tCount,category_id;
	var countBlock=parseInt($('span#recs').text());
	$('input[id^="category_id"]').click( function(){
			category_id=$(this).val();
			tCount=parseInt($('span#count_'+category_id).text());
			if(this.checked==true){
				countBlock+=tCount;
				$(this).parent('label').attr('class','checked');	
			}else{
				countBlock-=tCount;
				$(this).parent('label').removeAttr('class');
			}
			$('span#recs').text(countBlock);
		});
	$('a#show_recs').click(function(){
		$('table#tblRecs').fadeToggle(500);	
	});
});
Joomla.submitbutton = function()
{
		$('form#adminForm').submit();
}
</script>