<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="config_plugins">
	<table width="100%"><tr><td width="50%" valign="top">
	<fieldset class="adminform">
		<legend><?php echo JText::_('PLUG_TAG') ?></legend>
		<table class="adminlist table table-striped table-hover" cellpadding="1">
			<thead>
				<tr>
					<th class="title titlenum">
						<?php echo JText::_( 'ACY_NUM' );?>
					</th>
					<th class="title">
						<?php echo JText::_('ACY_NAME'); ?>
					</th>
					<th class="title titletoggle">
						<?php echo JText::_('ENABLED'); ?>
					</th>
					<th class="title titleid">
						<?php echo JText::_( 'ACY_ID' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$k = 0;

					for($i = 0,$a = count($this->plugins);$i<$a;$i++){
						$row =& $this->plugins[$i];

						$publishedid = 'published_'.$row->id;
				?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
						<?php echo $i+1 ?>
						</td>
						<td>
							<a target="_blank" href="<?php echo !ACYMAILING_J16 ? 'index.php?option=com_plugins&amp;view=plugin&amp;client=site&amp;task=edit&amp;cid[]=' : 'index.php?option=com_plugins&amp;task=plugin.edit&amp;extension_id='; echo $row->id?>" ><?php echo $row->name; ?></a>
						</td>
						<td align="center">
							<span id="<?php echo $publishedid ?>" class="loading"><?php echo $this->toggleClass->toggle($publishedid,$row->published,'plugins') ?></span>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
				<?php
						$k = 1-$k;
					}
				?>
			</tbody>
		</table>
	</fieldset>
	</td><td valign="top">
	<fieldset class="adminform">
		<legend><?php echo JText::_('PLUG_INTE') ?></legend>
		<table class="adminlist table table-striped table-hover" cellpadding="1">
			<thead>
				<tr>
					<th class="title titlenum">
						<?php echo JText::_( 'ACY_NUM' );?>
					</th>
					<th class="title">
						<?php echo JText::_('ACY_NAME'); ?>
					</th>
					<th class="title titletoggle">
						<?php echo JText::_('ENABLED'); ?>
					</th>
					<th class="title titleid">
						<?php echo JText::_( 'ACY_ID' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$k = 0;

					for($i = 0,$a = count($this->integrationplugins);$i<$a;$i++){
						$row =& $this->integrationplugins[$i];

						$publishedid = 'published_'.$row->id;
				?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
						<?php echo $i+1 ?>
						</td>
						<td>
							<a target="_blank" href="<?php echo !ACYMAILING_J16 ? 'index.php?option=com_plugins&amp;view=plugin&amp;client=site&amp;task=edit&amp;cid[]=' : 'index.php?option=com_plugins&amp;task=plugin.edit&amp;extension_id='; echo $row->id?>"><?php echo $row->name; ?></a>
						</td>
						<td align="center">
							<span id="<?php echo $publishedid ?>" class="spanloading"><?php echo $this->toggleClass->toggle($publishedid,$row->published,'plugins') ?></span>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
				<?php
						$k = 1-$k;
					}
				?>
			</tbody>
		</table>
	</fieldset>
	<br/><a class="downloadmore" style="margin-left:20px" href="http://www.acyba.com/download/plugins-modules.html" target="_blank"><?php echo JText::_('MORE_PLUGINS'); ?></a>
	</td></tr></table>
</div>
