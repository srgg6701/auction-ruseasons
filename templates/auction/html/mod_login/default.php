<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div id="logIn_wrapper">
    <div id="logIn">
<?php	 
		if($type == 'logout') : ?>
    
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
    <?php if ($params->get('greeting')) : ?>
    <div>
    <?php echo 'Клиентский номер: '. $user->get('username');?>
    </div>
    <?php endif; ?>
    <div align="left" style="margin-top:8px;">
    <input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'Выйти'); ?>" />
    </div>	    
    	<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
    </form>
<?php 	else : ?>
	<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
            $lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
            $langScript = 	'var JLanguage = {};'.
                            ' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
                            ' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
                            ' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
                            ' var modlogin = 1;';
            $document = &JFactory::getDocument();
            $document->addScriptDeclaration( $langScript );
            JHTML::_('script', 'openid.js');
          endif; ?>
	<form action="<?php echo JRoute::_( 'index.php?option=com_users&task=user.login', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	<?php echo $params->get('pretext'); ?>	
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" autocomplete="off" placeholder="Клиентский номер" required />
		<input id="modlgn_passwd" type="password" name="password" class="inputbox" size="18" alt="password" autocomplete="off" placeholder="пароль" required />
	<?php if(JPluginHelper::isEnabled('system', 'remember')) :
			// плагин: Система - запомнить меня. ?>
	<a href="<?php echo JRoute::_( 'index.php?option=com_users&view=remind' ); ?>" style="float:left;">
			<?php echo JText::_('Забыли логин '); ?></a>
		<span style="float:left;"> &nbsp;/ </span>
			<a href="<?php echo JRoute::_( 'index.php?option=com_users&view=reset' ); ?>" style="float:left; margin-left:2px;">
			<?php echo JText::_(' пароль?'); ?></a>
	<?php endif; ?>
    	<input style="float:left;" type="submit" name="Submit" class="button" value="<?php echo JText::_('Войти') ?>" />
		<?php
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
			<div style="padding: 1px; margin-left: 66px;"><a href="<?=JRoute::_('index.php?option=com_users&view=registration')?>">Регистрация </a></div>
		<?php endif; ?>
	<?php echo $params->get('posttext'); ?>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
<?php 	endif; ?>
 	<div id="board">
    	<a href="index.php?option=com_content&view=article&id=18:prijom-antikvariata-na-torgi&catid=2:uncategorised">Позвольте вашим вещам<br />самореализоваться<br />Прием антиквариата на торги</a>
    </div>
  </div>
</div>