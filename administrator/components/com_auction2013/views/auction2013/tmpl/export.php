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
</style>
	<h2 style="margin-bottom:0;">Выберите БД-источник экспорта данных:</h2>
	<?=$this->source_db_boxes_html?>
    <h2>Выберите раздел: &nbsp; <?=$this->html_sections?></h2>
	<? //var_dump($this->categories_data); die();
	if($cats=$this->categories_data){
	/*	0 => array
	  'category_id' => string '283' (length=3)
	  'category_name' => string 'Восточное искусство' (length=37)
	  'count' => string '1' (length=1)
		1 => array
	  'category_id' => string '337' (length=3)
	  'category_name' => string 'Декоративно-прикладное искусство' (length=62)
	  'count' => string '0' (length=1)
	*/
		
		/*	337 => string '337' (length=3)
  			273 => string '273' (length=3)
  			282 => string '282' (length=3)
		*/
		$active_cats=$this->active_categories;
		//var_dump($this->active_categories);//die(); ?>    
<div>
<form action="<?php echo JRoute::_('index.php?option=com_auction2013&view=auction2013&layout=export'); ?>" method="post" name="adminForm" id="adminForm">
    <?	foreach($cats as $n=>$data):
			$catid=$data['category_id'];
			if($active_cats && in_array($catid,$active_cats)) $catnames[$catid]=$data['category_name'];
		endforeach;
		// выбирали категоии, получили данные:
		if($this->active_categories):
			$source_prods=$this->section_products;
			//var_dump($source_prods);
			if(count($source_prods)):?>
        <h4 style="padding:6px 10px; margin:8px 0; background-color: #FF6; display:inline-block; clear:both;">Получено <a id="show_recs" href="javascript:location.href='#tbl_recs'" title="Показать таблицу экспортированных записей">записей</a>: <?=count($source_prods)?></h4>
			<?	$filename=Export::createCSV($catnames,$source_prods);?>
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
        $catid=$data['category_id'];
			if($active_cats
			   && in_array($catid,$active_cats)
			  ):?> class="come"<? 
			endif;
		?>><input id="category_id[<?=$data['category_id']?>]" name="category_id[<?=$data['category_id']?>]" type="checkbox" value="<?=$data['category_id']?>">&nbsp;<?=$data['category_name']?> (<span id="count_<?=$data['category_id']?>"><?=$data['count']?></span>) </label>&nbsp;
    <?	endforeach;
		if($this->section_products): 
			if(count($source_prods)):
				//var_dump($source_prods); ?>
    <h4><a name="tbl_recs">Экспортированные записи:</a></h4>
    <div style="width:100%;overflow:auto;">
    <table width="100%" id="tblRecs" border="1" rules="rows" bgcolor="#FFFFFF" style="display:<?="none"?>;">
			<?	// var_dump($source_prods); 
				foreach($source_prods as $i=>$data):
					if ($i) {
						$images=Export::getImagesToExport($data['id']);
						$im=0;
						//var_dump($images);
					}
					/*	i = 26 => 
					array
					  'auction_number' => string ''
					  'date_show' => string '1287349910'
					  'date_hide' => string '1303161110'
					  'date_start' => string ''
					  'date_stop' => string ''
					  'title' => string 'Образок «Святой Феодосий Черниговский»' 
					  'short_desc' => string ''
					  'desc' => string 'Финифть, металл, к. XIX в., 7х6 см' 
					  'category_id' => string '329'
					  'images' => string '3'
					  'id' => string '2985'
					*/?>
    	<tr<? if($i):?> title="Запись id <? echo $i; endif;?>">
				<?	if(!$i):	
						foreach ($source_prods[0] as $index=>$header):?>
       	  	<th class="come"><nobr><?=$header?></nobr></th>    	
		<?				endforeach;?>
		</tr>
        <tr>
				<?	endif;
					for($i=0,$len=count($source_prods[0]);$i<$len;$i++):
						$key=$source_prods[0][$i];
						$value=$data[$key];
						$rvalue=$value;?>
			<td>			
					<?	if($key=='img'):
							
							if($im!==false):
								if($images[$im]):
									echo $images[$im];
									$im++;
								else: $im=false;
								endif;
							endif;
						
						else:
							
							if(strstr($key,'date')&&$rvalue): //echo "date row(".gettype($rvalue)."): $rvalue<br>";
								if(is_int($rvalue)
								   ||preg_match("/\b[0-9]{10}\b/", $rvalue)
								  ):
								  	if(!is_int($rvalue))
										(int)$rvalue;
									echo date('Y-m-d H:i:s',$rvalue);
								else:
									if(preg_match("/\b[0-9]{2}\.[0-9]{2}\.[0-9]{4}\+[0-9]{2}:[0-9]{2}\b/", $rvalue)):
										$ardate=explode('+',$rvalue);
										$bDate=explode('.',$ardate[0]);
										echo // date:
											 $bDate[2].'-'.$bDate[1].'-'.$bDate[0].
											 // time:
											 ' '.$ardate[1].':00';
									else: 
										echo $rvalue;
									endif;
								endif;
							else:
								echo $rvalue;
							endif;
						endif;?>
            </td>
				<?	endfor;?>
        </tr>
			<?	endforeach;?>    
    </table>
    </div>
		<?	endif;	
		endif;?>
        <input type="hidden" name="section" value="<?=$this->section[0].':'.$this->section[1]?>" />
		
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
<?	}?>