<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
?>
<div class="profile<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; 
if (JRequest::getVar('show_default')){?>
<?php echo $this->loadTemplate('core'); ?>

<?php echo $this->loadTemplate('params'); ?>

<?php echo $this->loadTemplate('custom'); ?>

<?php if (JFactory::getUser()->id == $this->data->id) : ?>
<a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>">
	<?php echo JText::_('COM_USERS_Edit_Profile'); ?></a>
<?php endif; 
}else{	?>
	<div class="content_shell left private_room">
        <div id="your_order">
            <span class="text_highlight">Ваш клиентский № 
                100105</span>
        </div>
        <!-- START LEFT COLUMN -->
        <div id="user_column">		
            <div class="content_box">
                <h2 class="title">
                    <a href="index.php?a=4">Ваш кабинет</a></h2>
                <ul>
                    <li class="my_account_links_inactive">
                        <a href="index.php?a=4&amp;b=10" class="user_links">Избранное</a>
                    </li>
                    <li class="my_account_links_inactive">
                        <a href="index.php?a=4&amp;b=21" class="user_links">Мои ставки</a>
                    </li>
                    <li class="my_account_links_inactive">
                        <a href="<?=JRoute::_('index.php?option=com_users&view=profile&layout=data', false)?>">Настройки аккаунта</a>
                    </li>
                    <li class="my_account_links_inactive">
                        <a href="index.php?a=17"><span style="color:#841113">Выйти</span></a>
                  </li>
                </ul>
            </div>
        </div>
            <!-- END LEFT COLUMN -->
            
            <!-- START CONTENT BLOCK -->
        <div id="content_column_wide">
            <div class="content_box">
            </div>
            <div class="content_box highlight_links">
                <h2 class="title">Ваши лоты</h2>
				<a href="#"><b>Ставки сделанные Вами:</b></a>
                <span class="count_st_lt">&nbsp;2</span>
            	<div class="para">Заканчивающиеся торги в которых Вы лидируете: 2</div>              	<div class="para">Ваши предметы: 8</div>
            </div>   
          </div>
            <!-- END CONTENT BLOCK -->
    </div>
<?
}?>
</div>
