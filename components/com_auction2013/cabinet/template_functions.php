<?

/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_default(){?>
    <a href="#"><b>Ставки сделанные Вами:</b></a>
    <span class="count_st_lt">&nbsp;2</span>
    <div class="para">
        Заканчивающиеся торги в которых Вы лидируете: 2
    </div>              	
    <div class="para">
        Ваши предметы: 8
    </div>
<?	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_lots(){?>
    <H1>LOTS</H1>
<?	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_data(){?>
    <H1>ACCOUNT DATA</H1>
<?	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_favorites($user_id){?>
    <H1>FAVORITES</H1>
<?		require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
		$favorites=AuctionStuff::getFavorites($user_id);
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
		<?	foreach($favorites as $virtuemart_product_id => $product_data){?>
			<tr>
            	<td><?=$product_data['product_name']?></td>
            	<td><?=$product_data['product_price']?></td>
            	<td><?=$product_data['auction_date_start']?></td>
            	<td><?=$product_data['auction_date_start']?></td>
            	<td><? //=?></td>
            </tr>
		<?	}?>
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


?>