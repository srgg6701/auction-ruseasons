<?php
/**
 *
 * Template type editing page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: edit.php 2275 2013-01-03 21:08:43Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load some needed behaviors
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_csvi&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm"  id="item-form" class="form-validate">
<div class="width-100 fltlft">
	<fieldset class="adminform">
		<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('template_type_name'); ?>
			<?php echo $this->form->getInput('template_type_name'); ?></li>
			<li><?php echo $this->form->getLabel('template_type'); ?>
			<?php echo $this->form->getInput('template_type'); ?></li>
			<li><?php echo $this->form->getLabel('component'); ?>
			<?php echo $this->form->getInput('component'); ?></li>
			<li><?php echo $this->form->getLabel('options'); ?>
			<?php echo $this->form->getInput('options'); ?></li>
			<li><?php echo $this->form->getLabel('url'); ?>
			<?php echo $this->form->getInput('url'); ?></li>
		</ul>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>