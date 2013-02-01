<?php
/**
 * Import page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_source.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_IMPORT_SOURCE_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label"><?php echo $this->form->getLabel('source', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('source', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('import_file', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('import_file', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('local_csv_file', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('local_csv_file', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('urlfile', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('urlfile', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('ftphost', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('ftphost', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('ftpport', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('ftpport', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('ftpusername', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('ftpusername', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('ftppass', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('ftppass', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('ftproot', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('ftproot', 'general'); ?></div></li>
		<li><div class="option_label"><?php echo $this->form->getLabel('ftpfile', 'general'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('ftpfile', 'general'); ?></div></li>
	</ul>
</fieldset>
<div class="clr"></div>
<script type="text/javascript">
// Show the correct source
jQuery(window).load(function(){ Csvi.showSource(document.adminForm.jform_general_source.value)});
</script>