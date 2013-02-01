<?php
/**
 * Templatetypes page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_csvi&view=templatetypes'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->templatetypes); ?>);" />
				</th>
				<th class="title"><?php echo JHtml::_('grid.sort', 'COM_CSVI_TEMPLATE_TYPE_NAME', 'template_type_name', $listDirn, $listOrder); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_TEMPLATE_TYPE_DESC'); ?></th>
				<th class="title"><?php echo JHtml::_('grid.sort', 'COM_CSVI_COMPONENT_NAME', 'component', $listDirn, $listOrder); ?></th>
				<th class="title"><?php echo JHtml::_('grid.sort', 'COM_CSVI_TEMPLATE_PROCESS', 'template_type', $listDirn, $listOrder); ?></th>
			</tr>
		<thead>
		<tfoot>
			<tr>
				<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->templatetypes as $i => $template) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td align="center">
						<?php echo JHtml::_('grid.id', $i, $template->id); ?>
					</td>
					<td>
						<?php
						if (!empty($template->url)) echo JHtml::_('link', JRoute::_($template->url), JText::_('COM_CSVI_'.$template->template_type_name), 'target="_blank"');
						else echo JText::_('COM_CSVI_'.$template->template_type_name);
						?>
					</td>
					<td><?php echo JText::_('COM_CSVI_'.strtoupper($template->template_type_name).'_DESC'); ?></td>
					<td>
						<?php echo JHtml::_('link', JRoute::_('index.php?option='.$template->component), $template->component, 'target="_blank"'); ?>
					</td>
					<td><?php echo JText::_('COM_CSVI_'.strtoupper($template->template_type)); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="templatetypes.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>