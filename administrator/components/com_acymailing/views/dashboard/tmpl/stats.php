<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><br  style="font-size:1px;" />
<div id="dash_stats">
	<table class="adminlist table table-striped table-hover" cellpadding="1">
		<thead>
			<tr>
				<th class="title">
					<?php echo JText::_('JOOMEXT_SUBJECT'); ?>
				</th>
				<th class="title titledate">
					<?php echo JText::_('SEND_DATE'); ?>
				</th>
				<th class="title titletoggle">
					<?php echo JText::_( 'OPEN' );?>
				</th>
				<th class="title titletoggle">
					<?php echo JText::_( 'SENT_HTML' );?>
				</th>
				<th class="title titletoggle">
					<?php echo JText::_( 'SENT_TEXT' );?>
				</th>
				<th class="title titletoggle">
					<?php echo JText::_( 'FAILED' );?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$k = 0;
				foreach($this->stats as $oneStat){
					$row =& $oneStat;
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<a href="<?php echo acymailing_completeLink('stats&task=detaillisting&filter_mail='.$row->mailid); ?>"><?php echo $row->subject; ?></a>
					</td>
					<td align="center" style="text-align:center">
						<?php echo acymailing_getDate($row->senddate); ?>
					</td>
					<td align="center" style="text-align:center">
						<?php echo empty($row->senthtml) ? '' : ((int)($row->openunique / $row->senthtml*100)).'%'; ?>
					</td>
					<td align="center" style="text-align:center">
						<?php echo $row->senthtml; ?>
					</td>
					<td align="center" style="text-align:center">
						<?php echo $row->senttext; ?>
					</td>
					<td align="center" style="text-align:center">
						<?php echo $row->fail; ?>
					</td>
				</tr>
			<?php
					$k = 1-$k;
				}
			?>
		</tbody>
	</table>
</div>
