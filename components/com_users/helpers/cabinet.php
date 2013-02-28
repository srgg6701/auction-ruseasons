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
					break;					
					default: 
						echo 'Ваши лоты';
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
<?		require_once JPATH_BASE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
		$favorites=AuctionStuff::getFavorites($user_id);
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
        </table>
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
