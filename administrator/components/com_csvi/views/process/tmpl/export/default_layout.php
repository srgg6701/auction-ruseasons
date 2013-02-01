<?php
/**
 * Export file layout options
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_layout.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_EXPORT_LAYOUT_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label"><?php echo $this->form->getLabel('header', 'layout'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('header', 'layout'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('body', 'layout'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('body', 'layout'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('footer', 'layout'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('footer', 'layout'); ?></div></li>
	</ul>
</fieldset>
<div class="clr"></div>