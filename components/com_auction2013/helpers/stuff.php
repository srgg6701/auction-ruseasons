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
	public static function sreateForm($arrFields){		
		ob_start();
		foreach($arrFields as $value=>$fieldArray){?>
			<div>
				<label for="<?=$value?>"><?
				if (isset($fieldArray[1])){
					?><span class="req">*</span><? 
					$req=' required';
				}else{
					$req='';
				}
				echo $fieldArray[0];?>:</label>	
		<?	if($value=='country_id'){?>
				<select id="country" name="jform[country_id]"<?=$req?>>
                    <option value="none">Выберите страну</option>
			<?	$countries=AuctionStuff::getCountries();
				foreach($countries as $code=>$country):?>
					<option value="<?=$code?>"><?=$country?></option>
			<?	endforeach;?>		
			</select>
		<?	}else{
				if (isset($fieldArray[3])):
					if($fieldArray[3]=='textarea'):
                		echo "<".$fieldArray[3]." id=\"{$value}\" name=\"jform[".$value."]\"";
						if(isset($fieldArray[4]))
							echo $fieldArray[4];
						echo $req."></".$fieldArray[3].">";
					endif;
            	else:?>
                <input type="<?=(strstr($value,"password"))? "password":"text"?>" autocomplete="off" maxlength="50" size="30" value="<?
					if ($getValue=JRequest::getVar($value))
						echo $getValue;
					elseif(JRequest::getVar('test')){
						switch($value){
							case 'email1': case 'email2':
								echo 'test@email.com';
							break;
							case 'password1': case 'password2':
								echo 'history';
							break;
							default:
								echo $fieldArray[0];
						}
					}
				?>" name="jform[<?=$value?>]" id="<?=$value?>"<?=$req?>>					
        	<?	endif;
				if(isset($fieldArray[2])) 
					echo $fieldArray[2];
			}?>
			</div>
	<?	}
		$fields=ob_get_contents();
		ob_clean();
		return $fields;
	}
}?>