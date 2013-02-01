<?php
/**
 * Replacements page
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 1760 2012-01-02 19:50:19Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_csvi&view=replacements'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th class="title"><?php echo JHtml::_('grid.sort', 'COM_CSVI_REPLACEMENT_NAME_LABEL', 'name', $listDirn, $listOrder); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_REPLACEMENT_FIND_LABEL'); ?></th>
				<th class="title"><?php echo JText::_('COM_CSVI_REPLACEMENT_REPLACE_LABEL'); ?></th>
				<th class="title"><?php echo JHtml::_('grid.sort', 'COM_CSVI_REPLACEMENT_METHOD_LABEL', 'method', $listDirn, $listOrder); ?></th>
			</tr>
		<thead>
		<tfoot>
			<tr>
				<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php 
        if (!empty($this->items)) { 
          foreach ($this->items as $i => $item) { ?>
          <tr class="row<?php echo $i % 2; ?>">
            <td align="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
            <td><?php 
                if ($item->checked_out) {
                  echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'replacements.');
                  echo $this->escape($item->name); 
                }
                else { ?>
                  <a href="<?php echo JRoute::_('index.php?option=com_csvi&task=replacement.edit&id='.(int) $item->id); ?>">
                    <?php echo $this->escape($item->name); ?>
                  </a>
                <?php } ?> 
            </td>
            <td><?php echo substr($item->findtext, 0, 100); if (strlen($item->findtext) > 100) echo '...';?></td>
            <td><?php echo substr($item->replacetext, 0, 100); if (strlen($item->replacetext) > 100) echo '...'; ?></td>
            <td><?php echo $item->method; ?></td>
          </tr>
        <?php } 
        } ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>