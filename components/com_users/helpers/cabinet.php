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
	function buildCabinet($logout_params,$layout=false,$user=false){
		
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
		<?	UserCabinet::$method();?>            	
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
	function layout_favorites(){?>
    <H1>FAVORITES</H1>
<?	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	function layout_bids(){?>
    <H1>MY BIDS</H1>
<?	}	
}
