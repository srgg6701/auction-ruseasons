<?	require 'template_functions.php'; 

//	,
//	false,
//	$user=false

$logout_params=$this->params->get('logout_redirect_url', $this->form->getValue('return'));
$layout=$this->getLayout();


if (!$user)
	$user=JFactory::getUser();

if (!$layout)
	$layout='default';

$method='layout_'.$layout;

?>
<div style="padding-left:380px;">
	<div class="content_shell left private_room">
        <div id="your_order">
            <span class="text_highlight">Ваш клиентский № 
                <?=$user->get('username')?></span>
        </div>
        <!-- START LEFT COLUMN -->
        <!--<div id="user_column">		
            <div class="content_box">
            	&nbsp;
            </div>
            <form id="formGoLogout" action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
			<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($logout_params); ?>" />
			<?php echo JHtml::_('form.token'); ?>
	</form>           
        </div>-->
        
            <!--<form id="formGoLogout" action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
			<button type="submit" class="button"><?php echo JText::_('JLOGOUT'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($logout_params); ?>" />
			<?php echo JHtml::_('form.token'); ?>
	</form>-->           
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
					break;					
					default: 
						echo 'Ваши лоты';
				}
				
				//echo $section;?></h2>
                
		<?	//$method($params);
		
require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
		$favorites=AuctionStuff::getFavorites($user->id);
		//var_dump($favorites); 
		/*?>
	<table>
    	<tr>
    		<th>HEADER</th>
        </tr>
	<?	foreach($favorites as $virtuemart_product_id => $product_data){
			//echo "<div class=''>virtuemart_product_id= ".$virtuemart_product_id."</div>";
			foreach($product_data as $key => $value)
				echo "<div class=''>key => ".$value."</div>";
		}?>
    </table>
	<? 	*/	
	//$favorites=array();
		//if($go) 
		if(!empty($favorites)){?>
        <table>
        	<tr>
            	<th>Предмет</th>
            	<th>Цена</th>
            	<th>Начало</th>
            	<th>Окончание</th>
            	<th>Осталось</th>
            </tr>
		<?	//if (1>2)
			foreach($favorites as $virtuemart_product_id => $product_data){?>
			<tr>
            	<td><?=$product_data['product_name']?></td>
            	<td><?=$product_data['product_price']?></td>
            	<td><?=$product_data['auction_date_start']?></td>
            	<td><?=$product_data['auction_date_start']?></td>
            	<td><? //=?></td>
            </tr>
		<?	}?>
        </table>
	<?	}else{?>
        <p><b>У Вас нет избранных лотов.</b></p>
	<? 	}
		
		
		
		?><!--<table cellspacing="0" cellpadding="0">
      <tr>
        <td>№02</td>
        <td>02/188/03</td>
        <td>Фигурка «Гордон сеттер с уткой».</td>
        <td>Венская бронза. Бронза, литье, раскраска. Вена, Австрия, н. ХХ в. Размер    высота 7.5 см., длина 14 см. ( небольшие потертости красочного слоя)</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/200/01</td>
        <td>Блюдо декоративное «Медуза Горгона» в стиле Ренессанс.</td>
        <td>Чугун, литье, рельеф, полировка, Россия, к. XIX в., завод Э. Ферстера    (Санкт-Петербург) и Кусинский завод (Урал), клейма на обороте «Кус.З» и    «Завод Э.Ферстер СПетербург», диаметр 53,7 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/202/04</td>
        <td>Фигурка «Дон Кихот».</td>
        <td>Чугун, литье, СССР, Каслинский завод, 1989 г., Клейма: Касли, 1989 г.    Высота 21 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/204/17</td>
        <td>Ваза для фруктов с фигурой Музы.</td>
        <td>Металл, серебрение, Россия (?), н. ХХ в., на книге в руках Музы    дарственная надпись «На память от Паши и Бориса» 19 IX/XII 16, высота 44,5    см, диаметр 33,5 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/205/02</td>
        <td>Парные подсвечники «Змея и лягушка», «Змея и птичье гнездо».</td>
        <td>Бронза, золочение, патина, к. ХIХ в., высота 26 см (на одном утрачена    деталь, очевидно, фигурка птички)</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/206/02</td>
        <td>Настольное украшение «Ботинок с дырой».</td>
        <td>Металл, серебрение, Западная Европа, н. ХХ в., высота 6 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/206/04</td>
        <td>Композиция «Птичка над гнездом».</td>
        <td>Бронза, литьё, золочение, Западная Европа, ХХ в., высота 6 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/206/21</td>
        <td>Лапоть.</td>
        <td>Бронза, литье, чеканка, Россия, к. XIX - н. ХХ вв., длина 12.5 см, высота    3.5 см</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/207/08</td>
        <td>Колокольчик.</td>
        <td>Бронза, литье, золочение, Россия(?), 2-я пол. XIX в., высота 11 см</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/208/01</td>
        <td>Скульптура «Олень».</td>
        <td>Бронза, патинирование, подпись в литье «C.Cardet», Западная Европа, к.    XIX в., высота 32,8 см, длина 20,5 см. (утрачены 2 крепежных винта и 1 гайка)</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/208/04</td>
        <td>Настольное украшение «Рак на камне».</td>
        <td>Бронза, патинирование, Западная Европа, к. XIX в. - н. ХХ вв., высота 6,5    см, длина 20,5 см. (крепежный винт сломан)</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/208/06</td>
        <td>Рамка для фотографии.</td>
        <td>Металл, смальта, мозаика, Западная Европа, к. XIX - н. ХХ вв., размер    12,5х10 см</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/212/05</td>
        <td>Подчасник.</td>
        <td>Бронза, золочение, бархат, 2-я пол. XIX в., утраты на передних ножках,    высота 15 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/213/01</td>
        <td>Настольное украшение «Медведь».</td>
        <td>Бронза, патинирование, Россия, Санкт-Петербург, фабрика К. Верфеля,    клеймо: «C.F. Woerffel.» к. XIX - н. ХХ вв., высота 10 см, длина 17,5 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/214/06</td>
        <td>Орехокол в виде дамских ножек.</td>
        <td>Бронза, Западная Европа, к. XIX - н. ХХ вв., длина 13,5 см.</td>
      </tr>
      <tr>
        <td>№02</td>
        <td>02/215/01</td>
        <td>Рамка для фотографии.</td>
        <td>Бронза, золочение, стекло, к. XIX - н. ХХ вв., размер 27х15,7 см.</td>
      </tr>
  </table>-->            	
            </div>   
          </div>
            <!-- END CONTENT BLOCK -->
    </div>
<!---->
</div>