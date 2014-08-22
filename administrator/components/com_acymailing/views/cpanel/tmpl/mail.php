<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="page-mail">
	<fieldset class="adminform" >
		<legend><?php echo JText::_( 'SENDER_INFORMATIONS' ); ?></legend>
		<table class="admintable" cellspacing="1">
			<tr>
				<td width="185" class="key">
					<?php echo acymailing_tooltip(JText::_('FROM_NAME_DESC'), JText::_('FROM_NAME'), '', JText::_('FROM_NAME')); ?>
				</td>
				<td>
					<input class="inputbox" type="text" name="config[from_name]" style="width:200px" value="<?php echo $this->escape($this->config->get('from_name')); ?>">
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo acymailing_tooltip(JText::_('FROM_ADDRESS_DESC'), JText::_('FROM_ADDRESS'), '', JText::_('FROM_ADDRESS')); ?>
				</td>
				<td>
					<input class="inputbox" type="text" name="config[from_email]" style="width:200px" value="<?php echo $this->escape($this->config->get('from_email')); ?>">
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo acymailing_tooltip(JText::_('REPLYTO_NAME_DESC'), JText::_('REPLYTO_NAME'), '', JText::_('REPLYTO_NAME')); ?>
				</td>
				<td>
					<input class="inputbox" type="text" name="config[reply_name]" style="width:200px" value="<?php echo $this->escape($this->config->get('reply_name')); ?>">
				</td>
			</tr>
			<tr>
				<td class="key">
				<?php echo acymailing_tooltip(JText::_('REPLYTO_ADDRESS_DESC'), JText::_('REPLYTO_ADDRESS'), '', JText::_('REPLYTO_ADDRESS')); ?>
				</td>
				<td>
					<input class="inputbox" type="text" name="config[reply_email]" style="width:200px" value="<?php echo $this->escape($this->config->get('reply_email')); ?>">
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo acymailing_tooltip(JText::_('BOUNCE_ADDRESS_DESC'), JText::_('BOUNCE_ADDRESS'), '', JText::_('BOUNCE_ADDRESS')); ?>
				</td>
				<td>
					<input class="inputbox" type="text" name="config[bounce_email]" style="width:200px" value="<?php echo $this->escape($this->config->get('bounce_email')); ?>">
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo acymailing_tooltip(JText::_('ADD_NAMES_DESC'), JText::_('ADD_NAMES'), '', JText::_('ADD_NAMES')); ?>
				</td>
				<td>
					<?php echo $this->elements->add_names; ?>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_( 'MAIL_CONFIG' ); ?></legend>
		<table width="100%">
		<tr>
		<td width="50%" valign="top">
			<table class="admintable" cellspacing="1">
				<tr>
					<td width="185" class="key">
						<?php echo acymailing_tooltip(JText::_('MAILER_METHOD_DESC'), JText::_('MAILER_METHOD'), '', JText::_('MAILER_METHOD')); ?>
					</td>
					<td>
					<?php $mailerMethod = $this->config->get('mailer_method','phpmail');
					if(!in_array($mailerMethod,array('smtp_com','elasticemail','smtp','qmail','sendmail','phpmail'))) $mailerMethod = 'phpmail';
					?>
<?php
	if(!ACYMAILING_J30) {
?>
						<fieldset><legend style="font-size:13px;"><?php echo JText::_('SEND_SERVER'); ?></legend>
							<span><input type="radio" name="config[mailer_method]" onclick="updateMailer('phpmail')" value="phpmail" <?php if($mailerMethod == 'phpmail') echo 'checked="checked"'; ?> id="mailer_phpmail" /><label for="mailer_phpmail"> PHP Mail Function</label></span>
							<span><input type="radio" name="config[mailer_method]" onclick="updateMailer('sendmail')" value="sendmail" <?php if($mailerMethod == 'sendmail') echo 'checked="checked"'; ?> id="mailer_sendmail" /><label for="mailer_sendmail"> SendMail</label></span>
							<span><input type="radio" name="config[mailer_method]" onclick="updateMailer('qmail')" value="qmail" <?php if($mailerMethod == 'qmail') echo 'checked="checked"'; ?> id="mailer_qmail" /><label for="mailer_qmail"> QMail</label></span>
						</fieldset>
						<fieldset><legend style="font-size:13px;"><?php echo JText::_('SEND_EXTERNAL'); ?></legend>
							<span><input type="radio" name="config[mailer_method]" onclick="updateMailer('smtp')" value="smtp" <?php if($mailerMethod == 'smtp') echo 'checked="checked"'; ?> id="mailer_smtp" /><label for="mailer_smtp"> SMTP Server</label></span>
							<span><input type="radio" name="config[mailer_method]" onclick="updateMailer('smtp_com')" value="smtp_com" <?php if($mailerMethod == 'smtp_com') echo 'checked="checked"'; ?> id="mailer_smtp_com" /><label for="mailer_smtp_com"> SMTP.com</label></span>
							<span><input type="radio" name="config[mailer_method]" onclick="updateMailer('elasticemail')" value="elasticemail" <?php if($mailerMethod == 'elasticemail') echo 'checked="checked"'; ?> id="mailer_elasticemail" /><label for="mailer_elasticemail"> Elastic Email</label></span>
						</fieldset>
<?php
	} else {
		$values = array(
			'<fieldset style="padding:10px;"><legend style="font-size:13px;">'. JText::_('SEND_SERVER').'</legend>',
			JHTML::_('select.option', 'phpmail', 'PHP Mail Function'),
			JHTML::_('select.option', 'sendmail', 'SendMail'),
			JHTML::_('select.option', 'qmail', 'QMail'),
			'</fieldset><fieldset style="padding:10px;"><legend style="font-size:13px;">'.JText::_('SEND_EXTERNAL').'</legend>',
			JHTML::_('select.option', 'smtp', 'SMTP Server'),
			JHTML::_('select.option', 'smtp_com', 'SMTP.com'),
			JHTML::_('select.option', 'elasticemail', 'Elastic Email'),
			'</fieldset>'
		);
		echo JHTML::_('acyselect.radiolist',  $values, 'config[mailer_method]', 'onchange="updateMailer(this.value)"', 'value', 'text', $mailerMethod );
	}
?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo acymailing_tooltip(JText::_('ENCODING_FORMAT_DESC'), JText::_('ENCODING_FORMAT'), '', JText::_('ENCODING_FORMAT')); ?>
					</td>
					<td>
						<?php echo $this->elements->encoding_format; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo acymailing_tooltip(JText::_('CHARSET_DESC'), JText::_('CHARSET'), '', JText::_('CHARSET')); ?>
					</td>
					<td>
						<?php echo $this->elements->charset; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo acymailing_tooltip(JText::_('WORD_WRAPPING_DESC'), JText::_('WORD_WRAPPING'), '', JText::_('WORD_WRAPPING')); ?>
					</td>
					<td>
						<input class="inputbox" type="text" name="config[word_wrapping]" style="width:50px" value="<?php echo $this->config->get('word_wrapping',0) ?>">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php
						$defaultHostName = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost.localdomain';
						echo acymailing_tooltip(JText::_('HOSTNAME_DESC').'<br/><br/>'.JText::_('FIELD_DEFAULT').' : '.$defaultHostName, JText::_('HOSTNAME'), '', JText::_('HOSTNAME')); ?>
					</td>
					<td>
						<input class="inputbox" type="text" name="config[hostname]" style="width:160px" value="<?php echo $this->escape($this->config->get('hostname')); ?>">
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo acymailing_tooltip(JText::_('EMBED_IMAGES_DESC'), JText::_('EMBED_IMAGES'), '', JText::_('EMBED_IMAGES')); ?>
					</td>
					<td>
						<?php echo $this->elements->embed_images; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo acymailing_tooltip( JText::_('EMBED_ATTACHMENTS_DESC'), JText::_('EMBED_ATTACHMENTS'), '', JText::_('EMBED_ATTACHMENTS')); ?>
					</td>
					<td>
						<?php echo $this->elements->embed_files; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo acymailing_tooltip(JText::_('MULTIPLE_PART_DESC'), JText::_('MULTIPLE_PART'), '', JText::_('MULTIPLE_PART')); ?>
					</td>
					<td>
						<?php echo $this->elements->multiple_part; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo acymailing_tooltip(JText::_('ACY_DKIM_DESC'), JText::_('ACY_DKIM'), '', JText::_('ACY_DKIM')); ?>
					</td>
					<td>
						<?php echo $this->elements->dkim; ?>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<fieldset class="adminform" id="sendmail_config" style="display:none">
				<legend>SendMail</legend>
				<table class="admintable" cellspacing="1" >
					<tr>
						<td width="185" class="key">
							<?php echo acymailing_tooltip(JText::_('SENDMAIL_PATH_DESC'), JText::_('SENDMAIL_PATH'), '', JText::_('SENDMAIL_PATH')); ?>
						</td>
						<td>
							<input class="inputbox" type="text" name="config[sendmail_path]" style="width:160px" value="<?php echo $this->config->get('sendmail_path','/usr/sbin/sendmail') ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
			<fieldset class="adminform" id="smtp_config" style="display:none">
				<legend><?php echo JText::_( 'SMTP_CONFIG' ); ?></legend>
				<table class="admintable" cellspacing="1">
					<tr>
						<td width="185" class="key">
							<?php echo acymailing_tooltip(JText::_('SMTP_SERVER_DESC'), JText::_('SMTP_SERVER'), '', JText::_('SMTP_SERVER')); ?>
						</td>
						<td>
							<input class="inputbox" type="text" name="config[smtp_host]" style="width:160px" value="<?php echo $this->escape($this->config->get('smtp_host')); ?>" />
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo acymailing_tooltip(JText::_('SMTP_PORT_DESC'), JText::_('SMTP_PORT'), '', JText::_('SMTP_PORT')); ?>
						</td>
						<td>
							<input class="inputbox" type="text" name="config[smtp_port]" style="width:50px" value="<?php echo $this->escape($this->config->get('smtp_port')); ?>" />
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo acymailing_tooltip(JText::_('SMTP_SECURE_DESC'), JText::_('SMTP_SECURE'), '', JText::_('SMTP_SECURE')); ?>
						</td>
						<td>
							<?php echo $this->elements->smtp_secured; ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo acymailing_tooltip(JText::_('SMTP_ALIVE_DESC'), JText::_('SMTP_ALIVE'), '', JText::_('SMTP_ALIVE')); ?>
						</td>
						<td>
							<?php echo $this->elements->smtp_keepalive; ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo acymailing_tooltip(JText::_('SMTP_AUTHENT_DESC'), JText::_('SMTP_AUTHENT'), '', JText::_('SMTP_AUTHENT')); ?>
						</td>
						<td>
							<?php echo $this->elements->smtp_auth; ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo acymailing_tooltip(JText::_('USERNAME_DESC'), JText::_('ACY_USERNAME'), '', JText::_('ACY_USERNAME')); ?>
						</td>
						<td>
							<input class="inputbox" type="text" name="config[smtp_username]" style="width:200px" value="<?php echo $this->escape($this->config->get('smtp_username')); ?>" />
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo acymailing_tooltip(JText::_('SMTP_PASSWORD_DESC'), JText::_('SMTP_PASSWORD'), '', JText::_('SMTP_PASSWORD')); ?>
						</td>
						<td>
							<input class="inputbox" autocomplete="off" type="password" name="config[smtp_password]" style="width:200px" value="<?php echo $this->escape($this->config->get('smtp_password')); ?>" />
						</td>
					</tr>
				</table>
				<?php echo $this->toggleClass->toggleText('guessport','','config',JText::_('ACY_GUESSPORT')); ?>
			</fieldset>
			<fieldset class="adminform" id="smtp_com_config" style="display:none">
				<legend>SMTP.com</legend>
				<?php echo JText::sprintf('SMTP_DESC','SMTP.com'); ?>
				<table class="admintable" cellspacing="1" >
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('ACY_USERNAME'); ?>
						</td>
						<td>
							<input class="inputbox" type="text" name="config[smtp_com_username]" style="width:160px" value="<?php echo $this->config->get('smtp_com_username','') ?>" />
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('SMTP_PASSWORD'); ?>
						</td>
						<td>
							<input class="inputbox" autocomplete="off" type="password" name="config[smtp_com_password]" style="width:160px" value="<?php echo $this->config->get('smtp_com_password','') ?>" />
						</td>
					</tr>
				</table>
				<?php echo JText::_('NO_ACCOUNT_YET').' <a href="'.ACYMAILING_REDIRECT.'smtp_com" target="_blank" >'.JText::_('CREATE_ACCOUNT').'</a>'; ?>
				<?php echo '<br/><a href="'.ACYMAILING_REDIRECT.'smtp_services" target="_blank">'.JText::_('TELL_ME_MORE').'</a>'; ?>
			</fieldset>
			<fieldset class="adminform" id="elasticemail_config" style="display:none">
				<legend>Elastic Email</legend>
				<?php echo JText::sprintf('SMTP_DESC','Elastic Email'); ?>

				<table class="admintable" cellspacing="1" >
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('ACY_USERNAME'); ?>
						</td>
						<td>
							<input class="inputbox" type="text" name="config[elasticemail_username]" style="width:160px" value="<?php echo $this->config->get('elasticemail_username','') ?>" />
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							API Key
						</td>
						<td>
							<input class="inputbox" autocomplete="off" type="password" name="config[elasticemail_password]" style="width:160px" value="<?php echo $this->config->get('elasticemail_password','') ?>" />
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('SMTP_PORT'); ?>
						</td>
						<td>
							<?php
							$elasticPort = array();
							$elasticPort[] = JHTML::_('select.option', '25', 25 );
							$elasticPort[] = JHTML::_('select.option', '2525', 2525 );
							$elasticPort[] = JHTML::_('select.option', 'rest', 'REST API' );
							echo JHTML::_('acyselect.radiolist',   $elasticPort, 'config[elasticemail_port]', 'size="1" ', 'value', 'text',$this->config->get('elasticemail_port','rest'));
							?>
						</td>
					</tr>
				</table>
				<?php echo JText::_('NO_ACCOUNT_YET').' <a href="'.ACYMAILING_REDIRECT.'elasticemail" target="_blank" >'.JText::_('CREATE_ACCOUNT').'</a>'; ?>
				<?php echo '<br/><a href="'.ACYMAILING_REDIRECT.'smtp_services" target="_blank">'.JText::_('TELL_ME_MORE').'</a>'; ?>
			</fieldset>
<?php
if(acymailing_level(1)){
?>
			<fieldset class="adminform" id="dkim_config" style="display:none">
				<legend><?php echo JText::_('ACY_DKIM'); ?></legend>
				<?php
					$domain = $this->config->get('dkim_domain','');
					if(empty($domain)){
						$domain = preg_replace(array('#^https?://(www\.)*#i','#^www\.#'),'',ACYMAILING_LIVE);
						$domain = substr($domain,0,strpos($domain,'/'));
					}

					if(($this->config->get('dkim_selector','acy') != 'acy' && $this->config->get('dkim_selector','acy') != '') || $this->config->get('dkim_passphrase','') != '' || JRequest::getInt('dkimletme')){
				?>
				<table class="admintable" cellspacing="1" >
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('DKIM_DOMAIN'); ?>
						</td>
						<td>
							<input class="inputbox" type="text" id="dkim_domain" name="config[dkim_domain]" style="width:160px" value="<?php echo $this->escape($domain); ?>" /> *
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('DKIM_SELECTOR'); ?>
						</td>
						<td>
							<input class="inputbox" type="text" id="dkim_selector" name="config[dkim_selector]" style="width:160px" value="<?php echo $this->escape($this->config->get('dkim_selector','acy')); ?>" /> *
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('DKIM_PRIVATE'); ?>
						</td>
						<td>
							<textarea cols="65" rows="16" id="dkim_private" style="width:460px;font-size:10px;" name="config[dkim_private]"><?php echo $this->config->get('dkim_private',''); ?></textarea> *
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('DKIM_PASSPHRASE'); ?>
						</td>
						<td>
							<input class="inputbox" type="text" id="dkim_passphrase" name="config[dkim_passphrase]" style="width:160px" value="<?php echo $this->escape($this->config->get('dkim_passphrase','')); ?>" />
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('DKIM_IDENTITY'); ?>
						</td>
						<td>
							<input class="inputbox" type="text" id="dkim_identity" name="config[dkim_identity]" style="width:160px" value="<?php echo $this->escape($this->config->get('dkim_identity','')); ?>" />
						</td>
					</tr>
					<tr>
						<td width="185" class="key">
							<?php echo JText::_('DKIM_PUBLIC'); ?>
						</td>
						<td>
							<textarea cols="65" rows="5" id="dkim_public" style="width:460px;font-size:10px;" name="config[dkim_public]"><?php echo $this->config->get('dkim_public',''); ?></textarea>
						</td>
					</tr>
				</table>
				<?php }else{
						if($this->config->get('dkim_private','') == '' ||$this->config->get('dkim_public','') == '' ){
							echo 'Please save your AcyMailing configuration page first';
							$doc = JFactory::getDocument();
							$doc->addScript('http://www.acyba.com/index.php?option=com_updateme&ctrl=generatedkim');
							?>
							<input type="hidden" id="dkim_private" name="config[dkim_private]" />
							<input type="hidden" id="dkim_public" name="config[dkim_public]" />

						<?php
						}else{
							$publicKey = trim(str_replace(array('acy._domainkey	IN	TXT	"','v=DKIM1;k=rsa;g=*;s=email;h=sha1;t=s;p=','-----BEGIN PUBLIC KEY-----','-----END PUBLIC KEY-----',"\n"),'',$this->config->get('dkim_public','')),'"');

							echo JText::sprintf('DKIM_CONFIGURE','<input class="inputbox" type="text" id="dkim_domain" name="config[dkim_domain]" style="width:120px;" value="'.$this->escape($domain).'" />'); ?><br/>
							<?php echo JText::_('DKIM_KEY') ?> <input type="text" readonly="readonly" onclick="select();" style="width:80px;font-size:10px;" value="acy_.domainkey" />
							<br/><?php echo JText::_('DKIM_VALUE') ?> <input type="text" readonly="readonly" onclick="select();" style="width:220px;font-size:10px;" value="v=DKIM1;k=rsa;g=*;s=email;h=sha1;t=s;p=<?php echo $this->escape($publicKey); ?>" />
							<br/><input type="checkbox" value="1" id="dkimletme" name="dkimletme"/> <label for="dkimletme"><?php echo JText::_('DKIM_LET_ME');?></label>
							<?php
						}
						echo '<br/>';
					} ?>
				<a href="http://www.acyba.com/index.php?option=com_content&amp;view=article&amp;catid=34:documentation-acymailing&amp;Itemid=30&amp;id=156:acymailing-dkim" target="_blank"><?php echo JText::_('ACY_HELP'); ?></a>
			</fieldset>
<?php
}
?>
		</td>
		</tr>
		</table>
	</fieldset>
</div>
