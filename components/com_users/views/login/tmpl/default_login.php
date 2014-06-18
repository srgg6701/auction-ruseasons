<?php	
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.noframes');

?>
<div align="center" class="login<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>
<?php $bold='';
	if($favorite_product_id=JRequest::getVar('virtuemart_product_id')):
		$bold=' style="font-weight:bold;"';
		// add to session
		// именно в сессию, чтобы не потерять после авторизации. Как только юзер будет авторизован - первым делом проверим, что там у него сохранилось и добавим в таблицу.
		$session = JFactory::getSession();
		$session->set('favorite_product_id',$favorite_product_id);?>
	<p<?=$bold?>>Чтобы добавить выбранный вами предмет в избранное,<br><?php else:?>Добро пожаловать! Чтобы получить доступ ко всем функциям аукциона,<?php endif;?> пожалуйста, введите ниже свой клиентский номер и пароль.</p>
<br><br>
	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif ; ?>

		<?php if($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image')!='')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif ; ?>
	<form id="sign-in" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post">
		<fieldset>
		<?php	foreach ($this->form->getFieldset('credentials') as $field):?>
				<?php if (!$field->hidden): ?>
					<div class="login-fields"><?php 
					switch($field->name){
						case 'username':
							echo "<div>Клиентский номер:</div>";
						break;
						case 'password':
							echo "<div>".$field->label.":</div>";
						break;
						default:
							echo $field->label; 
					}?>
					<?php echo $field->input; ?></div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<div class="login-fields">
				<label id="remember-lbl" for="remember"><?php //echo JText::_('JGLOBAL_REMEMBER_ME') ?></label>
				<input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"  alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" />
			</div>
			<?php endif; ?>
			<button type="submit" class="button buttonSandCool"><?php echo JText::_('Войти &gt; &gt;'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>
<div align="center">
	<ul class="buttons">
		<li>
        	<div>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('COM_USERS_LOGIN_RESET'); ?> Нажмите сюда</a>
            </div>
		</li>
	<?php $rem_login=false;
		if ($rem_login):?>	
        <li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
		</li>
	<?php endif;?>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
        	<div>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">Нет аккаунта? Зарегистрируйтесь!<?php //echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a>
            </div>
		</li>
		<?php endif; ?>
	</ul>
</div>
