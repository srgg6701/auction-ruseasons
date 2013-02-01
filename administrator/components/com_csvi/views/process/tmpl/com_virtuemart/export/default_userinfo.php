<?php
/**
 * Export userinfo
 *
 * @package		CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version		$Id: default_userinfo.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label"><?php echo $this->form->getLabel('userinfo_address', 'userinfo'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('userinfo_address', 'userinfo'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('vendors', 'userinfo'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('vendors', 'userinfo'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('permissions', 'userinfo'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('permissions', 'userinfo'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('userinfomdatestart', 'userinfo'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('userinfomdatestart', 'userinfo'); ?> <?php echo $this->form->getInput('userinfomdateend', 'userinfo'); ?></div></li>
	</ul>
</fieldset>
<div class="clr"></div>