<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Подключить функции тестирования:
require_once JPATH_SITE.'/tests.php'; 
//  commonDebug, testSQL
/**
 * Users Route Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
class UserCabinet
{
    /** Данные кабинета: 
        - Текст ссылки
        - Класс ссылки
        - Заголовок раздела
        - Параметр объекта JUser, передаваемого методу генерации HTML-раздела   */
    static $cabinet_menu = array(
					'lots'=>        array("Ваш кабинет","H2",           "Ваши лоты", 'id'),
					'favorites'=>   array("Избранное",  "first-point",  false, 'id'),
					'bids'=>        array("Мои ставки", false,          false, 'id'),
                    'purchases'=>   array("Мои Покупки",    false,          false, 'id'),
					'data'=>        array("Настройки",  false,          "Моя персональная информация", true)
            );
	/**
     * Инициализировать построение кабинета юзера (common html; вызывается по умолчанию).
     * Вызвать метод построения соответствующего шаблона для юзера (layout_[template_name])
     //todo: Заменить на singleton! 
     */
    public function initUserCabinet($JUser,$logout_params,$layout=false){
		
		$session = JFactory::getSession();
		// клацали "Купить":
		if($product_id_purchasing=$session->get('product_id_purchasing')){
			$redirect='index.php?option=com_auction2013&layout=application&virtuemart_product_id='.$product_id_purchasing;
			//die($redirect);
			$session->clear('product_id_purchasing');
			JFactory::getApplication()->redirect($redirect);
		}
		require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
		if (!$layout||$layout==='default'){
            $default_section = UserCabinet::$cabinet_menu;
            reset($default_section);
            $layout=key($default_section);
        }
		
		$method='layout_'.$layout;
        //commonDebug(__FILE__, __LINE__, $method, true);				
		ob_start();?>
<div class="content_shell left private_room">
        <div id="your_order">
            <span class="text_highlight">Ваш клиентский № 
                <?=$JUser->get('username')?></span>
        </div>  
        <!-- START LEFT COLUMN -->
        <div id="user_column">		
            <div class="content_box" id="user_menu_box">
        <?php   UserCabinet::buildUserMenu(); ?>
            </div>
            <form id="formGoLogout" action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
			<button type="submit" class="buttonSandCool" style="margin: 16px auto;"><?php echo JText::_('JLOGOUT'); ?></button>
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
        		<h2 class="title"><?php
                $cabinet_data = UserCabinet::$cabinet_menu[$layout];
                echo ($cabinet_data[2])? $cabinet_data[2]:$cabinet_data[0];
                if($cabinet_data[3]){
                    $params=($cabinet_data[3]===true)?
                        $JUser:$JUser->$cabinet_data[3];
                }?></h2>
		<?php UserCabinet::$method($params);?>            	
            </div>   
        </div>
            <!-- END CONTENT BLOCK -->
    </div>
<?php   $cabinet=ob_get_contents();
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
<?php }
/**
 * Ставки
 * @package
 * @subpackage
 */
	function layout_lots($user_id){
		// Проверить закрома:
		//echo "<div class=''>favorite_product_id= ".$session->get('favorite_product_id')."</div>"; die();
		if($virtuemart_product_id=JFactory::getSession()->get('favorite_product_id')){
			// добавить запись в таблицу, перенаправить в Избранное:
			AuctionStuff::addToFavorites($virtuemart_product_id,$user_id);
			$uMenus=AuctionStuff::getTopCatsMenuItemIds(	
						'usermenu',
						'profile',
						'favorites'
					);
			$redirect='index.php?option=com_users&view=profile&layout=favorites&Itemid='
                        . $uMenus[0] . '&added=' . $virtuemart_product_id;
			//echo "<div class=''>redirect= ".$redirect."</div>";die();
			JFactory::getApplication()->redirect($redirect);
		}else{?>
    <H1>LOTS</H1>
<?php   }
	}	
/**
 * Данные юзера
 * @package
 * @subpackage
 */
	function layout_data($user){		 
		// Построить поля ввода редактируемых данных или разместить данные в ячейках таблицы, в зависимости от текущего режима:
		function setField($data,$required=true){
            
			if(is_array($data)):
				//commonDebug(__FILE__, __LINE__, $data, true);
                $field=$data[0];
				$value=$data[1];
				$fType=(strstr($field,'password'))? 'password':'text';
				if ($field=='country_id'){
					$countries=AuctionStuff::getCountries();?>
    <select name="jform[country_id]" id="jform_country_id">
    			<?php foreach($countries as $id=>$name):?>
    	<option value="<?=$id?>"<?php if($value==$id):
							?> selected<?php endif;
		?>><?=$name?></option>
                <?php endforeach;?>
    </select>
			<?php }else{ ?>
	<input type="<?=$fType?>" name="jform[<?=$field?>]" id="jform_<?=$field?>" value="<?php 
					if(JRequest::getVar($field)) $value=JRequest::getVar($field); 
					echo $value;?>"<?php 
					if( $field!="corpus_number"
						&& $field!="flat_office_number"
						&& !strstr($field,'password')
						//&& !strstr($field,'email')
					  ):?> class="required" required="required"<?php 
					endif;?> size="30"<?php 
                    if($field=='username'||$field=='registerDate'):
						?> disabled<?php 
                    endif;?>>
			<?php }
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
		<?php 
			$pAlCenter='';
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
							'registerDate'=>'Дата регистрации')
						);?>
        <p<?=$pAlCenter?> style="margin-top:13px;">Ваши данные:</p>
        <table class="alignRight verticalTop">
        <?php foreach ($userData as $user_field=>$data_header){?>
		<?php if ($user_field!='Адрес'){?>
        	<tr>
            	<td nowrap><?=$data_header?>:</td>
        		<td>
				<?php $user_data=(!strstr($user_field,'email'))? 
						$user->$user_field:$user->email;
					
					if($user_field=='registerDate')
						$user_data=JHTML::_('date', $user_data, JText::_('DATE_FORMAT_LC2'));
				
					$field=($edit_mode=='edit')?
						array($user_field,$user_data):$user_data;
					setField($field,$edit_mode);?>
				</td>
			</tr>
			<?php }else{
					if(!$edit_mode){?>
            <tr>
            	<td nowrap><?=$user_field?>:</td>
                <td>
			<?php $i=0;						
						foreach ($data_header as $address_field=>$address_data):
							$set_data=($address_field=='country_id')?
								$country:$user->$address_field;
							if($i) echo ";<br>";
							echo '<nobr>'.$address_data.': '.$set_data.'</nobr>';
						$i++;
						endforeach;?>
            	</td>
            </tr>
			<?php }else{
						foreach ($data_header as $address_field=>$address_data):?>
            <tr>
            	<td nowrap><?=$address_data?>:</td>
        		<td><?php $user_data=(!strstr($address_field,'email'))? 
							$user->$address_field:$user->email;	
						setField(array($address_field,$user_data),true);?>
				</td>
            </tr>
					<?php 
						endforeach;
					}?>
			<?php }
			}?>
		</table>
        <br>
        <hr size="1" color="#851719">
        <?php if ($edit_mode){?>
<input type="hidden" name="option" value="com_users">
<input type="hidden" name="task" value="profile.save">
<input type="hidden" name="return" value="option=com_users&view=profile&layout=data&Itemid=<?=JRequest::getVar('Itemid')?>">
		<?php echo JHtml::_('form.token');
				$btnType='submit';
				$btnValue='Сохранить данные!';
			}else{
				$btnType='button';
				$btnValue='Редактировать данные...';
			}?>
        <div<?=$pAlCenter?>>
        	<button type="<?=$btnType?>" class="buttonSandCool"<?php 
			if(!$edit_mode):
			?> onClick="location.href='index.php?option=com_users&view=profile&layout=data&Itemid=<?=JRequest::getVar('Itemid')?>&mode=edit'"<?php endif;?>><?=$btnValue?></button>
        </div>
</form>
<?php }	
/**
 * Избранное
 * @package
 * @subpackage
 */
	function layout_favorites($user_id){

		$favorites=AuctionStuff::getFavorites($user_id);
		if(!empty($favorites)){?>
            <form id="deleteFromFavorites" action="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.deleteFromFavorites'); ?>" method="post">
        <table id="tblFavorites" class="cabinet" cellpadding="2" cellspacing="1">
        	<tr>
            	<th>Предмет</th>
            	<th>Цена</th>
            	<th>Начало</th>
            	<th>Окончание</th>
            	<th title="Дней, часов, минут"><div style='border-bottom:dotted 1px;'>Осталось</div></th>
            </tr>
		<?php $DateAndTime=new DateAndTime();
			foreach($favorites as $virtuemart_product_id => $product_data){?>
			<tr<?php 
			if(JRequest::getVar('added')==$virtuemart_product_id){?> style="background-color:rgb(197, 226, 177);" <?php }?> valign="top">
            	<td><?php $product_link = AuctionStuff::extractProductLink($product_data['virtuemart_category_id'],$product_data['slug']); //,$virtuemart_product_id
				?><a href="<?php echo $product_link; 
				
				?>"><?=$product_data['product_name']?></a>
                <button value="<?=$virtuemart_product_id?>">Удалить из избранного</button></td>
            	<td><?=substr($product_data['product_price'],0,strpos($product_data['product_price'],"."))?></td>
            	<td><?=JHTML::_('date', $product_data['auction_date_start'], JText::_('DATE_FORMAT_LC2'));?></td>
            	<td><?=JHTML::_('date', $product_data['auction_date_finish'], JText::_('DATE_FORMAT_LC2'));?></td>
            	<td><?php 
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
		<?php }?>
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
	<?php }else{?>
        <p><b>У Вас нет избранных лотов.</b></p>
	<?php }
	}	
/**
 * Заявки на аукционе
 * @package
 * @subpackage
 */
	function layout_bids($user_id){?>
    <H1>Мои лоты</H1>
<?php }
    /**
     * Покупки (заявленные, закрытые)
     * @package
     * @subpackage
     */
    function layout_purchases($user_id){
        if($purchases=AuctionStuff::getPurchases(true)){
			?>
        <table class="cabinet border">
        	<tr>
            	<th>#</th>
            	<th>Наименование</th>
                <th>Категория</th>
            	<th>Цена</th>
            	<th>Статус</th>
            </tr>
		<?php //commonDebug(__FILE__,__LINE__,$purchases);
			foreach ($purchases as $i=>$data_array) {
			?>
        	<tr>
            	<td><?php echo $i+1;?></td>
            	<td><a href="<?php echo AuctionStuff::extractProductLink($data_array['virtuemart_category_id'],$data_array['slug']);?>"><?php echo $data_array['product_name'];?></a></td>
                <td><?php echo $data_array['category_name'];?></td>
            	<td align="right"><?php echo $data_array['price'];?></td>
            	<td nowrap><?php echo ((int)$data_array['status'])? 'Приобретено':'<span>На оформлении</span>';?></td>
            </tr>
		<?php		
			}?>
		</table>
		<?php
		}else{?>
        <h4>Покупок нет.</h4>
		<?php 
		}
		
    }
    /**
 * Построить меню юзера
 */
    static public function buildUserMenu() {?>
    <ul class="menu" id="usermenu-container" style="display: block;">
            <?php
        foreach(self::$cabinet_menu as $tmpl=>$header):?>
        <li>
            <a<?php
        if($header[1]):?> class="<?php echo $header[1];?>"<?php endif;
        ?> href="index.php?option=com_users&view=cabinet&layout=<?php echo $tmpl;?>"><?php echo $header[0];?></a>
        </li>
            <?php
        endforeach;?>
    </ul>    
    <?php
    }
}
