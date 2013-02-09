<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users Route Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class AuctionStuff{
/**
 * Получить контент статьи
 * @package
 * @subpackage
 */
	static public function getArticleContent($id){
		$query = "SELECT * FROM #__content WHERE id = ".$id;
		//  Load query into an object
		$db = JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssoc();
	}
/**
 * Получить страны
 * @package
 * @subpackage
 */
	function getCountries(){
		return array('7'=>'Россия','380'=>'Украина','375'=>'Белоруссия');	
	}
/**
 * Generate HTML form
 * @package
 * @subpackage
 */
	public static function sreateForm(){		
		$arrFields=array(
				'name'=>array('Имя',1),
				'middlename'=>array('Отчество'),
				'lastname'=>array('Фамилия',1),
				'company_name'=>array('Наименование фирмы'),
				'country_id'=>array('Страна',1),
				'zip'=>array('Индекс',1),
				'city'=>array('Город',1),
				'street'=>array('Улица',1),
				'house_number'=>array('Дом',1),
				'corpus_number'=>array('Корпус'),
				'flat_office_number'=>array('Квартира (офис)',1),
				'phone_number'=>array('Телефон 1',1,'Пример ввода: +7 987 6543210'),
				'phone2_number'=>array('Телефон 2'),
				'email'=>array('E-mail',1),
				'email2'=>array('Повторите e-mail',1),
				'password'=>array('Пароль',1,'Длина пароля не менее 6 символов'),
				'password2'=>array('Повторите пароль',1)
			);
		ob_start();
		foreach($arrFields as $value=>$fieldArray){?>
			<div>
				<label for="<?=$value?>"><?
				echo $fieldArray[0];
				if (isset($fieldArray[1])){
					?><span class="req">*</span><? 
					$req=' required';
				}else{
					$req='';
				}?>:</label>	
		<?	if($value=='country_id'){?>
				<select id="country" name="contry"<?=$req?>>
                    <option value="none">Выберите страну</option>
			<?	$countries=AuctionStuff::getCountries();
				foreach($countries as $code=>$country):?>
					<option value="<?=$country?>"><?=$country?></option>
			<?	endforeach;?>		
			</select>
		<?	}else{?>
                <input type="text" maxlength="50" size="30" value="<?
                if ($getValue=JRequest::getVar($value))
					echo $getValue;
				?>" name="<?=$value?>" id="<?=$value?>"<?=$req?>>					
        <?		if(isset($fieldArray[2])) 
					echo $fieldArray[2];
			}?>
			</div>
	<?	}
		$fields=ob_get_contents();
		ob_clean();
		return $fields;
	}
}?>