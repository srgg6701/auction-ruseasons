<?php defined('_JEXEC') or die('Restricted access');
// die('pageclass_sfx')?>
<div class="item-page<?php echo $this->params->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<h2 class="title thinBrownHeader">Регистрация</h2>
<?php // выведем статью "Предложить предмет":
$article=AuctionStuff::getArticleContent(19);	
echo $article['introtext'];?>
	<form id="registration_form" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.registerOnAuction'); ?>" method="post" class="form-validate">
		<div class="divider"></div>
<?php $dWidth='642px';
	if(JRequest::getVar('err')=='wrong_captcha'):?>
		<div align="center" style="background:#FFFF99; padding:10px; border:solid 2px #FFCC00;width:<?=$dWidth?>;margin-bottom:10px;">
        	Неправильно указан контрольный код (CAPTCHA).
        </div>
<?php endif;
	// JRequest::getVar('test') - substitutes test values
	echo AuctionStuff::createForm(
			array(	'name'=>array('Имя',1),
					'middlename'=>array('Отчество'),
					'lastname'=>array('Фамилия',1),
					'company_name'=>array('Наименование фирмы'),
					'country_id'=>array('Страна',1),
					'zip'=>array('Индекс',1),
					'city'=>array('Город',1),
					'street'=>array('Улица',1),
					'house_number'=>array('Дом',1),
					'corpus_number'=>array('Корпус'),
					'flat_office_number'=>array('Квартира (офис)',1,'Укажите 0 (ноль), если живете в частном доме'),
					'phone_number'=>array('Телефон 1',1,'Пример ввода: +7 987 6543210'),
					'phone2_number'=>array('Телефон 2'),
					'email1'=>array('E-mail',1),
					'email2'=>array('Повторите e-mail',1),
					'password1'=>array('Пароль',1,'Длина пароля не менее 6 символов'),
					'password2'=>array('Повторите пароль',1)
				)
			);?>
		<div align="center" style="width:<?=$dWidth?>;">
<?php require_once JPATH_SITE.DS.'components'.DS.'com_auction2013'.DS.'third_party'.DS.'recaptchalib.php';
  	$publickey = "6Lest90SAAAAAA8qVb_-_ShDWrEjq433w9-egFbA"; // you got this from the signup page
  	echo recaptcha_get_html($publickey);	
?>    
		<br>Регистрируясь на нашем сайте, Вы принимаете наши <a href="index.php/pravila/pravila-registraciji-uchastnikov-torgov">Правила</a>.
		<div align="center">
			<input type="submit" class="button buttonSand" value="Зарегистрироваться" name="submit">
		</div>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="registration.registerOnAuction" />
		<?php echo JHtml::_('form.token');?>        
		</div>
	</form>
</div>
<script>
jQuery(function($){
	$('form#registration_form').submit( function(){
		var errs=0;
		$('[required]').each( function(index,element){
			console.info($(element).val());
			if ((element.tagName.toUpperCase()!='SELECT'
				  && !$(element).val())
				|| $(element).val()=='none'
			   ) {
				$(element).css({
					backgroundColor:'#FF6',
					border:'solid 1px #999',
					width: '211px',
					marginLeft: '2px'
				}); 
				// console.info(element.tagName.toUpperCase());
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
			}else if(emailValue!=$(eMail2).val()){
				eMess='* Емэйл и его подтверждение не совпадают!';
				$(eMail2).css('background-color','#FC0');
				errs++;
			}
			if($('input#password1').val()!=$('input#password2').val()){
				if (errs) eMess+='\n'; 
				eMess+='* Пароль и его подтверждение не совпадают!';
				$('input#password2').css('background-color','#FC0');
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