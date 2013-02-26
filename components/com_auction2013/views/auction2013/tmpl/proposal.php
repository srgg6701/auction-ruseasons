<?
defined('_JEXEC') or die('Restricted access');
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
<? $old=false;
if ($old){?>  
<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">* Город  </label>
    <input type="text" name="city" id="city" title="" size="30" maxlength="150" class="cf_inputbox required validate-alpha" required>
    <label for="city" style="width:190px;float: right;" class="cf_label"><em></em></label>

 </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">* Ваше имя </label>
    <input type="text" name="name" id="name" title="" size="30" maxlength="150" class="cf_inputbox required validate-alpha" required>
    <label for="name" style="width: 190px;float: right;" class="cf_label"><em></em> </label>

 </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">* Ваш e-mail</label>
    <input type="text" name="posta" id="posta" title="" size="30" maxlength="150" class="cf_inputbox required" required>
      <label for="posta" style="width: 190px;float: right;" class="cf_label"><em></em> </label>

  </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_textbox">
    <label style="width: 150px;" class="cf_label">Телефон</label>
    <input type="text" name="phone" id="phone" title="" size="30" maxlength="150" class="cf_inputbox">
  
  </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_textarea">
    <label style="width: 150px;" class="cf_label">* Краткое описание (материал, наличие подписи, марки или клейма, размер и др.) </label>
    <textarea name="short_description" cols="30" title="" id="short_description" rows="3" class="cf_inputbox required" required></textarea>
    <label for="tikstik" style="width: 190px;float: right;" class="cf_label"> <em></em> </label>

  </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_textarea">
    <label style="width: 150px;" class="cf_label">Пожелания по цене</label>
    <textarea name="coast" cols="30" title="" id="coast" rows="3" class="cf_inputbox"></textarea>
    
  </div>
  <div class="cfclear">&nbsp;</div>
</div>
<div class="form_item">
  <div class="form_element cf_textbox">
* Фото предмета в нескольких ракурсах (если имеется, фото подписи, марки или клейма)
  <div class="cfclear">&nbsp;</div>
  </div>
</div>
<?	}?>

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