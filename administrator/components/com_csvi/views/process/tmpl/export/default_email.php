<?php
/**
 * Export e-mail options
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_email.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_EXPORT_EMAIL_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label"><?php echo $this->form->getLabel('export_email_addresses', 'email'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('export_email_addresses', 'email'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('export_email_addresses_cc', 'email'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('export_email_addresses_cc', 'email'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('export_email_addresses_bcc', 'email'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('export_email_addresses_bcc', 'email'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('export_email_subject', 'email'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('export_email_subject', 'email'); ?></div></li>
	</ul>
	<div class="clr"></div>
	<?php echo $this->form->getLabel('export_email_body', 'email'); ?>
	<div class="clr"></div>
	<?php echo $this->form->getInput('export_email_body', 'email'); ?>
</fieldset>
<div class="clr"></div>