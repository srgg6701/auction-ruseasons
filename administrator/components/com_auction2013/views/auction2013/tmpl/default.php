<?php
/**
 * @version     2.1.0
 * @package     com_auction2013
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
// Import CSS
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_auction2013/assets/css/auction2013.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_auction2013');
$saveOrder	= $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_auction2013'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

        <?php $allow_state=false;
			if($allow_state)
				if($state=$this->state):?>
                <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $state->get('filter.state'), true);?>
                </select>
          	<?php endif;?>      

		</div>
	</fieldset>
	<div class="clr"> </div>
	<?php $cnt=0;?>
  <table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /><?php ++$cnt;?>
				</th>

				<th>
				<?php echo JHtml::_('grid.sort',  'id', /*COM_AUCTION2013_LANG_DATA_NAME*/ 'a.id', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'field1_name', /*COM_AUCTION2013_LANG_DATA_NAME_1*/ 'a.field1_name', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'field2_name', /*COM_AUCTION2013_LANG_DATA_NAME_2*/ 'a.name', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'field3_name', /*COM_AUCTION2013_LANG_DATA_NAME_3*/ 'a.field3_name', $listDirn, $listOrder); ++$cnt;?>
				</th>
				<th>
				<?php echo JHtml::_('grid.sort',  'field4_name',/*COM_AUCTION2013_LANG_DATA_NAME_4*/ 'a.field4_name', $listDirn, $listOrder); ++$cnt;?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?=$cnt?>">
					<?php 
					if ($pgn=$this->pagination)
						echo $pgn->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
        <?php if ($items=$this->items){?>
		<tbody>
		<?php 
			foreach ($items as $i => $item) {
				$canCreate	= $user->authorise('core.create',		'com_auction2013');
				$canEdit	= $user->authorise('core.edit',			'com_auction2013');
				$canCheckin	= $user->authorise('core.manage',		'com_auction2013');
				$canChange	= $user->authorise('core.edit.state',	'com_auction2013');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>

				<td>
					<?php echo $item->id; ?>
				</td>
				<td>
				<?php 
				if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_auction2013&task=auction2013.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->field1); ?></a>
				<?php else :
					echo $this->escape($item->field1);
				endif; ?>
				</td>
				<td>
					<?php echo $item->field2; ?>
				</td>
				<td>
					<?php echo $item->field3; ?>
				</td>
				<td>
					<?php echo $item->field4; ?>
				</td>
			</tr>
	<?php 	} ?>
		</tbody>
	<?php }?>
  </table>
	<?php if(!$items):?>
    	<h4>Нет данных для отображения...</h4>
    <?php endif;?>
  <div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>