<?php defined('_JEXEC') or die('Restricted access');?>
<h2 class="title thinBrownHeader">Задать вопрос по лоту<?php if (JRequest::getVar('result')=='thanx'){
				$result='thanx';
			?>: <b>Ваш вопрос принят!</b><?php }
?></h2>
<?php if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php 	
endif;
if($result=='thanx'){?>
<br>
<br>
<h2 align="center">Спасибо за вопрос!</h2>
<br>
<hr>
<br>
<h4 align="center">Мы постараемся ответить на него в самое ближайшее время!</h4>
<br>
<br>
<br>
<?php }else{
// выведем статью "Задать вопрос по лоту":
$article=AuctionStuff::getArticleContent(22);	
// var_dump($article);	
echo $article['introtext'];?>
<form method="post" id="ask_form" name="ask_form" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.askQuestion'); ?>" enctype="multipart/form-data"> 

	<h4>Название лота: <?php $lot_id=JRequest::getVar('lot_id');
	$prod_array=AuctionStuff::getSingleProductData($lot_id,'p_ru_ru.product_name');
	echo $prod_array['product_name'];?></h4>
 
<?=AuctionStuff::createForm(
			array(	'name'=>array('Ваше имя',1),
					'email'=>array('E-mail',1),
					'phone_number'=>array('Телефон'),
					'comments'=>array('Ваш комментарий',1,false,'textarea'),
				)
			)?>
<br/>
<div align="center">
    <input type="submit" name="sendApp" value="Отправить сообщение">
    <input type="reset" value="Очистить">
        <input type="hidden" name="option" value="com_auction2013" />
        <input type="hidden" name="task" value="auction2013.askQuestion" />
        <?php echo JHtml::_('form.token');?>
        <br>
        <input type="hidden" name="lot_id" value="<?=$lot_id?>" />
        <textarea name="lot_name" style="display:none;"><?=$prod_array['product_name']?></textarea>
    <button type="button">Назад к лоту</button>
</div>
</form>
<script>
jQuery(function($){
	$('form#ask_form').submit( function(){
		var errs=0;
		$('[required]').each( function(index,element){
			console.info($(element).val());
			if (!$(element).val()){
				$(element).css({
					backgroundColor:'#FF6',
					border:'solid 1px #999',
					width: '211px',
					marginLeft: '2px'
				}); // console.info(element.tagName.toUpperCase());
				errs++;
			}
		}); 
		if(errs>0){
			alert('Не все поля заполнены/выбраны.');
			return false;
		}else{
			var eMess='';
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var eMail=$('input#email');
			var emailValue=$(eMail).val();
			if (!filter.test(emailValue)) {
				eMess='* Емэйл введён некорректно или отсутствует!';
				$(eMail).css('background-color','#FC0');
				errs++;
			}
			if(errs>0){
				alert(eMess);
				return false;
			}
		}
	});
});
</script>
<?php }?>