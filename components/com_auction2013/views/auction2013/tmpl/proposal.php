<?php defined('_JEXEC') or die('Restricted access');
if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php 	
endif;

// выведем статью "Предложить предмет":
$article=AuctionStuff::getArticleContent(13);	
// var_dump($article);	
echo $article['introtext'];?>
<form method="post" id="proposal_form" name="proposal_form" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.sendApplication'); ?>" enctype="multipart/form-data">  
<?=AuctionStuff::createForm(
			array(	'name'=>array('Ваше имя',1),
					'city'=>array('Город',1),
					'email1'=>array('E-mail',1),
					'phone_number'=>array('Телефон 1'),
					'short_description'=>array('Краткое описание',1,false,'textarea'),
					'price_wiches'=>array('Пожелания по цене',0,false,'textarea'),
				)
			)?>
<br/>
<br/>
<div>
	<span class="req">*</span> Фото предмета в нескольких ракурсах (если имеется, фото подписи, марки или клейма).
</div>
<br/>
<div class="form_item">
  <div class="form_element cf_fileupload">
    <label style="width: 150px;" class="cf_label">Изображение 1</label>
    <input type="file" name="mail_file" id="mail_file" size="20" title="" class="cf_fileinput cf_inputbox">
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_fileupload">
    <label style="width: 150px;" class="cf_label">Изображение 2</label>
    <input type="file" name="mail_file2" id="mail_file2" size="20" title="" class="cf_fileinput cf_inputbox">
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_fileupload">
    <label style="width: 150px;" class="cf_label">Изображение 3</label>
    <input type="file" name="mail_file3" id="mail_file3" size="20" title="" class="cf_fileinput cf_inputbox">
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>
<!--<div class="form_item">
  <div class="form_element cf_textbox">
* графы, обязательные для заполнения
  <div class="cfclear">&nbsp;</div></div>
</div>-->
<div align="center">
    <input type="submit" name="sendApp" value="Отправить">
</div>
		<input type="hidden" name="option" value="com_auction2013" />
		<input type="hidden" name="task" value="auction2013.sendApplication" />
		<?php echo JHtml::_('form.token');?>        
</form>
<script>
jQuery(function($){
	$('form#proposal_form').submit( function(){
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
			var eMail=$('input#email1');
			var eMail2=$('input#email2');
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