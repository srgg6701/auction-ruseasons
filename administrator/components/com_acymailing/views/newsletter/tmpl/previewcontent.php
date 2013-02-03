<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php if($this->mail->html){?>
<fieldset class="adminform" width="100%" id="htmlfieldset">
	<legend class="donotprint"><?php echo JText::_( 'HTML_VERSION' ); ?></legend>
	<div class="newsletter_body" ><?php echo $this->mail->body; ?></div>
</fieldset>
<?php } ?>
<fieldset class="adminform donotprint" id="textfieldset">
	<legend><?php echo JText::_( 'TEXT_VERSION' ); ?></legend>
	<?php echo nl2br($this->mail->altbody); ?>
</fieldset>
<?php if(!empty($this->mail->attachments)){?>
<fieldset class="newsletter_attachments donotprint"><legend><?php echo JText::_( 'ATTACHMENTS' ); ?></legend>
<table>
	<?php foreach($this->mail->attachments as $attachment){
			echo '<tr><td><a href="'.$attachment->url.'" target="_blank">'.$attachment->name.'</a></td></tr>';
	}?>
</table>
</fieldset>
<?php } ?>
<div class="clr"></div>
