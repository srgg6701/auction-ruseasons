<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content" >
<div id="iframedoc"></div>
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=stats" method="post" name="adminForm" id="adminForm" >
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JOOMEXT_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->escape($this->pageInfo->search);?>" class="text_area" />
				<button class="btn" onclick="document.adminForm.limitstart.value=0;this.form.submit();"><?php echo JText::_( 'JOOMEXT_GO' ); ?></button>
				<button class="btn" onclick="document.adminForm.limitstart.value=0;document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'JOOMEXT_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->filterMail; ?>
			</td>
		</tr>
	</table>

	<table class="adminlist table table-striped table-hover" cellspacing="1" align="center">
		<thead>
			<tr>
				<th class="title titlenum">
					<?php echo JText::_( 'ACY_NUM' );?>
				</th>
				<th class="title titledate">
					<?php echo JHTML::_('grid.sort',   JText::_( 'FIELD_DATE' ), 'a.date', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_( 'ACY_USER'), 'c.email', $this->pageInfo->filter->order->dir,$this->pageInfo->filter->order->value ); ?>
				</th>
				<th class="title">
				<?php echo JHTML::_('grid.sort',   JText::_( 'ACY_DETAILS' ), 'a.data', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $this->pagination->getListFooter(); ?>
					<?php echo $this->pagination->getResultsCounter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			$i = 0;
			foreach($this->rows as $row){
		?>
			<tr class="<?php echo "row$k"; ?>" >
				<td align="center" valign="top">
				<?php echo $i+1; ?>
				</td>
				<td align="center" valign="top">
					<?php echo acymailing_getDate($row->date); ?>
				</td>
				<td align="center">
					<?php
						$text = '<b>'.JText::_('ACY_NAME').' : </b>'.$row->name;
						$text .= '<br/><b>'.JText::_('ACY_ID').' : </b>'.$row->subid;
						echo acymailing_tooltip( $text, $row->email, '', $row->email);
					?>
				</td>
				<td valign="top">
					<?php
						$data = explode("\n",$row->data);
						foreach($data as $value){
							if(!strpos($value,'::')){ echo $value; continue;}
							list($part1,$part2) = explode("::",$value);
							if(empty($part2)) continue;
							if(preg_match('#^[A-Z_]*$#',$part2)) $part2 = JText::_($part2);
							echo '<b>'.JText::_($part1).' : </b>'.$part2.'<br />';
						}
					?>
				</td>
			</tr>
		<?php
				$k = 1-$k;$i++;
			}
		?>
	</tbody>
</table>

	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" />
	<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
	<input type="hidden" name="tmpl" value="component" />
</form>
</div>
