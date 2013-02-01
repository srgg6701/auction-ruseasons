<?php
/**
 *
 * Template type editing page
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: edit.php 1760 2012-01-02 19:50:19Z RolandD $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

// Load some needed behaviors
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_csvi&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm"  id="item-form" class="form-validate">
<div class="width-100 fltlft">
	<fieldset class="adminform">
		<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?></li>
			<li><?php echo $this->form->getLabel('findtext'); ?>
			<?php echo $this->form->getInput('findtext'); ?></li>
			<li><?php echo $this->form->getLabel('replacetext'); ?>
			<?php echo $this->form->getInput('replacetext'); ?></li>
			<li><?php echo $this->form->getLabel('method'); ?>
			<?php echo $this->form->getInput('method'); ?></li>
		</ul>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>