<?
defined('_JEXEC') or die('Restricted access');
	if ($this->params->get('show_page_heading')) : 
	
	?><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<h1 class="title">Регистрация</h1>
<?
// выведем статью "Предложить предмет":
$article=AuctionStuff::getArticleContent(19);	
// var_dump($article);	
echo $article['introtext'];?>
<form id="registration_form" method="post" action="http://auction.auction-ruseasons.ru/register.php?b=1">
		<div class="divider"></div>
		
								
				
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
		
					<div class="row_odd">
				<label class="required" for="c[firstname]">Имя *:</label>	
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[firstname]" id="c[firstname]">
							</div>
							
							<div class="row_odd">
				<label class="field_label" for="c[optional_field_1]">Отчество:</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[optional_field_1]" id="c[optional_field_1]">
								
							</div>
							
					<div class="row_odd">
				<label class="required" for="c[lastname]">Фамилия *:</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[lastname]" id="c[lastname]">
								
							</div>
					<div class="row_odd">
				<label class="field_label" for="c[company_name]">Наименование фирмы:</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[company_name]" id="c[company_name]">
								
							</div>
							
						<div class="row_odd">
				<label class="field_label" for="">Страна:</label>
				
															<select style="width: 206px;" class="field" id="c[country]" name="c[country]">
					<option value="none">Выберите страну</option>
					<option value="Белоруссия">Белоруссия</option>
					<option value="Россия">Россия</option>
					<option value="Украина">Украина</option>
					
			</select>
	
	
								
							</div>	
							
								<div class="row_odd">
				<label class="required" for="c[zip]">Индекс: *</label>
				
									<input type="text" class="field" maxlength="12" size="30" value="" name="c[zip]" id="c[zip]">
								
							</div>
							
					<div class="row_odd">
				<label class="required" for="c[city]">Город: *</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[city]" id="c[city]">
								
							</div>
			
			<div class="row_odd">
				<label class="required" for="c[optional_field_2]">Улица: *</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[optional_field_2]" id="c[optional_field_2]">
								
							</div>
			
			<div class="row_odd">
				<label class="required" for="c[optional_field_3]">Дом: *</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[optional_field_3]" id="c[optional_field_3]">
								
							</div>
			
			<div class="row_odd">
				<label class="field_label" for="c[optional_field_4]">Корпус:</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[optional_field_4]" id="c[optional_field_4]">
								
							</div>
			
			
			
				<div class="row_odd">
				<label class="field_label" for="c[optional_field_5]">Квартира (офис): *</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[optional_field_5]" id="c[optional_field_5]">
								
							</div>
			
					<div class="row_odd">
				<label class="required" for="c[phone]">Телефон1: *</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[phone]" id="c[phone]">
								<label>Пример ввода: +7 921 7530347 </label>
							</div>
					<div class="row_odd">
				<label class="field_label" for="c[phone_2]">Телефон 2:</label>
				
									<input type="text" class="field" maxlength="50" size="30" value="" name="c[phone_2]" id="c[phone_2]">
								
							</div>
					<div class="row_odd">
				<label class="required" for="c[email]">E-mail: *</label>
				
									<input type="text" class="field" maxlength="100" size="30" value="" name="c[email]" id="c[email]">
								
							</div>
					<div class="row_odd">
				<label class="required" for="c[email_verifier]">Повторите E-mail: *</label>
				
									<input type="text" class="field" maxlength="100" size="30" value="" name="c[email_verifier]" id="c[email_verifier]">
								
							</div>
		<div class="row_odd" style="display: none;">
			<label class="required" for="username">Имя пользователя: *</label>
			<input type="text" class="field" maxlength="12" value="200204" size="15" name="c[username]" id="username">
		</div>
		
		<div class="row_odd">
			<label class="required" for="password">Пароль *</label>
			<input type="password" class="field" maxlength="12" size="30" name="c[password]" id="password">
											<label>Длина пароля не менее 6 символов </label>

					</div>	
		<div class="row_odd">
			<label class="required" for="password_confirm">Повторите пароль: *</label>
			<input type="password" class="field" maxlength="12" size="30" name="c[password_confirm]" id="password_confirm">
		</div>
		
		
				
		<div class="row_odd" style="margin-top:10px;">
		<br>
			Регистрируясь на нашем сайте, Вы принимаете наши <a href="index.php?a=28&amp;b=147">Правила</a>.
		</div>
		
		<div class="center">
			<input type="submit" class="button" value="Зарегистрироваться" name="submit">
		</div>
		
	</form>