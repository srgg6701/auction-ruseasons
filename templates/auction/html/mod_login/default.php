<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php if($type == 'logout') : ?>


<div id="logIn_wrapper">
                    <div id="logIn">
<form action="index.php" method="post" name="login" id="form-login">
<?php if ($params->get('greeting')) : ?>
	<div>
	<?php if ($params->get('name')) : {
		echo JText::sprintf( 'HINAME', $user->get('name') );
	} else : {
		echo JText::sprintf( 'HINAME', $user->get('username') );
	} endif; ?>
	</div>
<?php endif; ?>
	<div align="center">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
	</div>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
 <div id="board">
                        <a href="http://auction-ruseasons.ru/sell.html">Позвольте вашим вещам<br />самореализоваться<br />Прием антиквариата на торги</a>
                        </div>
                 
</div></div>
<?php else : ?>
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

<div id="logIn_wrapper">
                    <div id="logIn">
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	<?php echo $params->get('pretext'); ?>	
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />
		<input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" />
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	
	<?php endif; ?>
	<input style="display:none;" type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" /> 


		<?php
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		
		
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>" style="float:left;">
			<?php echo JText::_('Забыли логин '); ?></a>
		<span style="float:left;"> &nbsp;/ </span>
			<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>" style="float:left; margin-left:2px;">
			<?php echo JText::_(' пароль?'); ?></a>
	
		
		<?php endif; ?>
	
	<?php echo $params->get('posttext'); ?>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
 <div id="board">
                        <a href="http://auction-ruseasons.ru/sell.html">Позвольте вашим вещам<br />самореализоваться<br />Прием антиквариата на торги</a>
                        </div>
</div></div>
<?php endif; ?>