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
class UserCabinet
{
	public function buildCabinet($logout_params,$layout=false,$user=false){
		
		require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
				
		if (!$user)
			$user=JFactory::getUser();
		
		if (!$layout)
			$layout='default';
		
		$method='layout_'.$layout;
		
		ob_start();?>
<div class="content_shell left private_room">
        <div id="your_order">
            <span class="text_highlight">Ваш клиентский № 
                <?=$user->get('username')?></span>
        </div>
        <!-- START LEFT COLUMN -->
        <div id="user_column">		
            <div class="content_box">
            	&nbsp;
            </div>
            <form id="formGoLogout" action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
			<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($logout_params); ?>" />
			<?php echo JHtml::_('form.token'); ?>
	</form>           
        </div>
            <!-- END LEFT COLUMN -->
            <!-- START CONTENT BLOCK -->
        <div id="content_column_wide">
            <div class="content_box">
            </div>
            <div class="content_box highlight_links">
        		<h2 class="title"><?
				
				switch($layout){
					case 'favorites':
						echo 'Избранное';
						$params=$user->id;
					break;
					case 'bids':
						echo 'Мои ставки';
					break;					
					case 'data': 
						echo 'Моя персональная информация';
						// $params=$user;
					break;					
					default: 
						echo 'Ваши лоты';
						$params=$user->id;
				}
				
				echo $section;?></h2>
		<?	UserCabinet::$method($params);?>            	
            </div>   
          </div>
            <!-- END CONTENT BLOCK -->
    </div>
<?		$cabinet=ob_get_contents();
		ob_clean();
		return $cabinet;
	}
/**
 * Проверяет добавление в избранное перед авторизацией. Если находит, заносит в таблицу, перенаправляет в избранное.
 * @package
 * @subpackage
 */
	function layout_default($user_id){ ?>
    <a href="#"><b>Ставки, сделанные Вами:</b></a>
    <span class="count_st_lt">&nbsp;[]</span>
    <div class="para">
        Заканчивающиеся торги в которых Вы лидируете: <?="[]"?>
    </div>              	
    <div class="para">
        Ваши предметы: <?="[]"?>
    </div>
<?	}
/**
 * @package
 * @subpackage
 */
	function layout_lots($user_id){
		// Проверить закрома:
		$session = JFactory::getSession();
		//echo "<div class=''>favorite_product_id= ".$session->get('favorite_product_id')."</div>"; die();
		if($virtuemart_product_id=$session->get('favorite_product_id')){
			// добавить запись в таблицу, перенаправить в Избранное:
			AuctionStuff::addToFavorites($virtuemart_product_id,$user_id);
			$app =JFactory::getApplication();
			$uMenus=AuctionStuff::getTopCatsMenuItemIds(	
						'usermenu',
						'profile',
						'favorites'
					);
			$redirect='index.php?option=com_users&view=profile&layout=favorites&Itemid='.$uMenus[0].'&added='.$virtuemart_product_id;
			//echo "<div class=''>redirect= ".$redirect."</div>";die();
			$app->redirect($redirect);
		}else{?>
    <H1>LOTS</H1>
<?		}
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_data(){
		 
		$user = JFactory::getUser();
		// АХТУНГ!!!!
		// Я НЕ ПОНИМАЮ (пока ещё)
		// ПОЧЕМУ? В JFactory::getUser()
		// ОНО ПОКАЗЫВАЕТ У ЮЗЕРА 
		// СТАРЫЕ ДАННЫЕ?!!!
		// ТЕ, КОТОРЫХ УЖЕ НЕТ В БД???????????
		// КАК СИЕ ПОНИМАТЬ???????????????????
		// Т.о. - вынужденное извращение:
		$query="SELECT * FROM #__users WHERE id = ".$user->id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$user=$db->loadObject(); 
		// перегрузили объект с юзером типо....
		
		// Построить поля ввода редактируемых данных или разместить данные в ячейках таблицы, в зависимости от текущего режима:
		function setField($data,$required=true){
			if(is_array($data)):
				$field=$data[0];
				$value=$data[1];
				$fType=(strstr($field,'password'))? 'password':'text';
				if ($field=='country_id'){
					$countries=AuctionStuff::getCountries();?>
    <select name="jform[country_id]" id="jform_country_id">
    			<?	foreach($countries as $id=>$name):?>
    	<option value="<?=$id?>"<?
						if($value==$id):
							?> selected<? 
						endif;
		?>><?=$name?></option>
                <?	endforeach;?>
    </select>
			<?	}else{?>
	<input type="<?=$fType?>" name="jform[<?=$field?>]" id="jform_<?=$field?>" value="<?
					if(JRequest::getVar($field)) $value=JRequest::getVar($field); 
					echo $value;?>"<?
					if( $field!="corpus_number"
						&& $field!="flat_office_number"
						&& !strstr($field,'password')
						//&& !strstr($field,'email')
					  ):?> class="required" required="required"<? 
					endif;?> size="30"<? 
					if($field=='username'||$field=='registerDate'):
						?> disabled<? 
					endif;?>>
			<?	}
			else:
				echo $data;
			endif;
		}
/*
Клиентский номер 	username
Имя					name
Отчество			middlename
Email				email
Телефон				phone_number			
Адрес				country_id, zip, city, street, house_number, corpus_number, flat_office_number			
Зарегистрирован		registerDate
*/		
		//var_dump($user); die();
		$countries=AuctionStuff::getCountries();
		$country=$countries[$user->country_id];
		$userData=array(
				'username'=>'Клиентский номер',
				'name'=>'Имя',
				'lastname'=>'Фамилия', 
				'middlename'=>'Отчество',
				'email1'=>'Email',
			);?>
<form style="display:inline-block;" id="member-profile" action="index.php?option=com_users&task=profile.save" method="post" class="form-validate" enctype="multipart/form-data">
		<?	$pAlCenter='';
			if($edit_mode=JRequest::getVar('mode')):
				$userData['email2']='Подтверждение e-mail';
				$userData['password1']='Новый пароль (опционально)';
				$userData['password12']='Подтверждение нового пароля';
				$pAlCenter=' align="center"';	
			endif;
			$userData=array_merge($userData,array(
							'phone_number'=>'Телефон',
							'Адрес'=>array(
										'zip'=>'Почтовый индекс',
										'country_id'=>'Страна проживания',
										'city'=>'Населённый пункт',
										'street'=>'Улица',
										'house_number'=>'№ дома',
										'corpus_number'=>'Корпус',
										'flat_office_number'=>'№ офиса',
									), 
							'registerDate'=>'Зарегистрирован')
						);?>
        <p<?=$pAlCenter?> style="margin-top:13px;">Ваши данные:</p>
        <table class="alignRight verticalTop">
        <?	foreach ($userData as $user_field=>$data_header){?>
		<?		if ($user_field!='Адрес'){?>
        	<tr>
            	<td nowrap><?=$data_header?>:</td>
        		<td>
				<?	$user_data=(!strstr($user_field,'email'))? 
						$user->$user_field:$user->email;
					
					if($user_field=='registerDate')
						$user_data=JHTML::_('date', $user_data, JText::_('DATE_FORMAT_LC2'));
				
					$field=($edit_mode=='edit')?
						array($user_field,$user_data):$user_data;
					setField($field,$edit_mode);?>
				</td>
			</tr>
			<?	}else{
					if(!$edit_mode){?>
            <tr>
            	<td nowrap><?=$user_field?>:</td>
                <td>
			<?			$i=0;						
						foreach ($data_header as $address_field=>$address_data):
							$set_data=($address_field=='country_id')?
								$country:$user->$address_field;
							if($i) echo ";<br>";
							echo '<nobr>'.$address_data.': '.$set_data.'</nobr>';
						$i++;
						endforeach;?>
            	</td>
            </tr>
			<?		}else{
						foreach ($data_header as $address_field=>$address_data):?>
            <tr>
            	<td nowrap><?=$address_data?>:</td>
        		<td><?	$user_data=(!strstr($address_field,'email'))? 
							$user->$address_field:$user->email;	
						setField(array($address_field,$user_data),true);?>
				</td>
            </tr>
					<?	endforeach;
					}?>
			<?	}
			}?>
		</table>
        <br>
        <hr size="1" color="#851719">
        <?	if ($edit_mode){?>
<input type="hidden" name="option" value="com_users">
<input type="hidden" name="task" value="profile.save">
<input type="hidden" name="return" value="option=com_users&view=profile&layout=data&Itemid=<?=JRequest::getVar('Itemid')?>">
		<?		echo JHtml::_('form.token');
				$btnType='submit';
				$btnValue='Сохранить данные!';
			}else{
				$btnType='button';
				$btnValue='Редактировать данные...';
			}?>
        <div<?=$pAlCenter?>>
        	<button type="<?=$btnType?>" class="buttonSandCool"<?
		if(!$edit_mode):
			?> onClick="location.href='index.php?option=com_users&view=profile&layout=data&Itemid=<?=JRequest::getVar('Itemid')?>&mode=edit'"<? 
		endif;?>><?=$btnValue?></button>
        </div>
</form>
<?	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_favorites($user_id){

		$favorites=AuctionStuff::getFavorites($user_id);
		if(!empty($favorites)){?>
            <form id="deleteFromFavorites" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.deleteFromFavorites'); ?>" method="post">
        <table id="tblFavorites" cellpadding="2" cellspacing="1">
        	<tr>
            	<th>Предмет</th>
            	<th>Цена</th>
            	<th>Начало</th>
            	<th>Окончание</th>
            	<th title="Дней, часов, минут"><div style='border-bottom:dotted 1px;'>Осталось</div></th>
            </tr>
		<?	$DateAndTime=new DateAndTime();
			foreach($favorites as $virtuemart_product_id => $product_data){?>
			<tr<?	
			if(JRequest::getVar('added')==$virtuemart_product_id){?> style="background-color:rgb(197, 226, 177);" <?	
			}?> valign="top">
            	<td><?
                $product_link = AuctionStuff::extractProductLink($product_data['virtuemart_category_id'],$product_data['slug'],$virtuemart_product_id);  
				?><a href="<? 
				
				echo $product_link; 
				
				?>"><?=$product_data['product_name']?></a>
                <button value="<?=$virtuemart_product_id?>">Удалить из избранного</button></td>
            	<td><?=substr($product_data['product_price'],0,strpos($product_data['product_price'],"."))?></td>
            	<td><?=JHTML::_('date', $product_data['auction_date_start'], JText::_('DATE_FORMAT_LC2'));?></td>
            	<td><?=JHTML::_('date', $product_data['auction_date_finish'], JText::_('DATE_FORMAT_LC2'));?></td>
            	<td><? 
				$delta=$DateAndTime->getDaysDiff($product_data['auction_date_start'],$product_data['auction_date_finish']);
				$s=0;
				foreach ($delta as $k=>$t){
					if($s) echo ($s==1)? "&nbsp;":":";
					if($k){
						if(strlen($t)<2) echo '0'; 
						echo $t;
					}
					$s++;
				}?></td>
            </tr>
		<?	}?>
        </table>
			<input type="hidden" id="virtuemart_product_id" name="virtuemart_product_id" value="" />
			<input type="hidden" name="option" value="com_auction2013" />
			<input type="hidden" name="task" value="auction2013.deleteFromFavorites" />
			<?php echo JHtml::_('form.token'); ?>
			</form>           
<script>
$(function(){
	$('table#tblFavorites button')
		.click( function(){
				var form=$('form#deleteFromFavorites');
				$('input#virtuemart_product_id',form).val($(this).val());
				//alert($('input#virtuemart_product_id',form).val());
				$(form).submit();
			});
});
</script>
	<?	}else{?>
        <p><b>У Вас нет избранных лотов.</b></p>
	<? 	}
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_bids(){?>
    <H1>MY BIDS</H1>
<?	}	
}
