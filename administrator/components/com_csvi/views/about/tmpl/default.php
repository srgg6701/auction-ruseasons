<?php
/**
 * About page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @todo
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<table class="adminlist">
	<thead>
		<tr>
			<th width="650"><?php echo JText::_('COM_CSVI_FOLDER'); ?></th>
			<th><?php echo JText::_('COM_CSVI_FOLDER_STATUS'); ?></th>
			<th><?php echo JText::_('COM_CSVI_FOLDER_OPTIONS'); ?></th>
		</tr>
	<thead>
	<tfoot>
	</tfoot>
	<tbody>
		<?php
			$i = 1;
			foreach ($this->folders as $name => $access) { ?>
			<tr>
				<td><?php echo $name; ?></td>
				<td><?php if ($access) { echo '<span class="writable">'.JText::_('COM_CSVI_WRITABLE').'</span>'; } else { echo '<span class="not_writable">'.JText::_('COM_CSVI_NOT_WRITABLE').'</span>'; } ?>
				<td><?php if (!$access) { ?>
					<form action="index.php?option=com_csvi&view=about">
						<input type="button" class="button" onclick="Csvi.createFolder('<?php echo $name; ?>', 'createfolder<?php echo $i; ?>'); return false;" name="createfolder" value="<?php echo JText::_('COM_CSVI_FOLDER_CREATE'); ?>"/>
					</form>
					<div id="createfolder<?php echo $i;?>"></div><?php } ?>
				</td>
			</tr>
		<?php $i++;
			} ?>
	</tbody>
</table>
<div class="clr"></div>
<table class="adminlist">
	<thead>
		<tr>
			<th><?php echo JText::_('COM_CSVI_ABOUT_SETTING'); ?></th>
			<th><?php echo JText::_('COM_CSVI_ABOUT_VALUE'); ?></th>
		</tr>
	</thead>
	<tfoot></tfoot>
	<tbody>
		<tr>
			<td><?php echo JText::_('COM_CSVI_ABOUT_DISPLAY_ERRORS'); ?></td>
			<td><?php echo (ini_get('display_errors')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO'); ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_CSVI_ABOUT_MAGIC_QUOTES'); ?></td>
			<td><?php echo (ini_get('magic_quotes')) ? JText::_('COM_CSVI_YES') : JText::_('COM_CSVI_NO'); ?></td>
		<tr>
			<td><?php echo JText::_('COM_CSVI_ABOUT_PHP'); ?></td>
			<td><?php echo PHP_VERSION; ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_CSVI_ABOUT_JOOMLA'); ?></td>
			<td><?php echo JVERSION; ?></td>
		</tr>
	</tbody>
</table>
<div class="clr"></div>
<br />
<table class="adminlist">
<tr><td colspan="2"><?php echo JHtml::_('image', JURI::base().'components/com_csvi/assets/images/csvi_about_32.png', JText::_('COM_CSVI_ABOUT')); ?></td></tr>
<tbody>
<tr><th>Name:</th><td>CSVI</td></tr>
<tr><th>Version:</th><td>4.5.3.2</td></tr>
<tr><th>Coded by:</th><td>RolandD Cyber Produksi</td></tr>
<tr><th>Contact:</th><td>contact@csvimproved.com</td></tr>
<tr><th>Support:</th><td><?php echo JHTML::_('link', 'http://www.csvimproved.com/', 'CSVI Homepage', 'target="_blank"'); ?></td></tr>
<tr><th>Copyright:</th><td>Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.</td></tr>
<tr><th>License:</th><td><?php echo JHtml::_('link', 'http://www.gnu.org/licenses/gpl-3.0.html', 'GNU/GPL v3'); ?></td></tr>
</tbody>
</table>