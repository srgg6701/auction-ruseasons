<?php
/**
 * Import system limit options
 *
 * @package 	CSVI
 * @subpackage 	Import
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_limit.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_IMPORT_LIMIT_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label_long"><?php echo $this->form->getLabel('use_system_limits', 'limit'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('use_system_limits', 'limit'); ?></div></li>
		<li><div class="option_label_long"><?php echo $this->form->getLabel('max_execution_time', 'limit'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('max_execution_time', 'limit'); ?></div><div>
			<?php echo JText::_('COM_CSVI_DEFAULT'); ?>: <?php echo intval(ini_get('max_execution_time')); ?></div></li>
		<li><div class="option_label_long"><?php echo $this->form->getLabel('memory_limit', 'limit'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('memory_limit', 'limit'); ?></div><div>
			<?php echo JText::_('COM_CSVI_DEFAULT'); ?>: <?php echo intval(ini_get('memory_limit')); ?></div></li>
		<li><div class="option_label_long"><?php echo $this->form->getLabel('post_max_size', 'limit'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('post_max_size', 'limit'); ?></div><div>
			<?php echo JText::_('COM_CSVI_DEFAULT'); ?>: <?php echo intval(ini_get('post_max_size')); ?></div></li>
		<li><div class="option_label_long"><?php echo $this->form->getLabel('upload_max_filesize', 'limit'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('upload_max_filesize', 'limit'); ?></div><div>
			<?php echo JText::_('COM_CSVI_DEFAULT'); ?>: <?php echo intval(ini_get('upload_max_filesize')); ?></div></li>
	</ul>
</fieldset>
<div class="clr"></div>