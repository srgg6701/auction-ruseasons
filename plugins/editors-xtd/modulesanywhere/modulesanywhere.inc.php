<?php
/**
 * Popup page
 * Displays a list with modules
 *
 * @package         Modules Anywhere
 * @version         3.2.2
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2012 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

$user = JFactory::getUser();
if ($user->get('guest')) {
	JError::raiseError(403, JText::_("ALERTNOTAUTH"));
}

require_once JPATH_PLUGINS . '/system/nnframework/helpers/parameters.php';
$parameters = NNParameters::getInstance();
$params = $parameters->getPluginParams('modulesanywhere');

if (JFactory::getApplication()->isSite()) {
	if (!$params->enable_frontend) {
		JError::raiseError(403, JText::_("ALERTNOTAUTH"));
	}
}

$class = new plgButtonModulesAnywherePopup;
$class->render($params);

class plgButtonModulesAnywherePopup
{
	function render(&$params)
	{
		$app = JFactory::getApplication();

		// load the admin language file
		$lang = JFactory::getLanguage();
		if ($lang->getTag() != 'en-GB') {
			// Loads English language file as fallback (for undefined stuff in other language file)
			$lang->load('plg_editors-xtd_modulesanywhere', JPATH_ADMINISTRATOR, 'en-GB');
			$lang->load('plg_system_modulesanywhere', JPATH_ADMINISTRATOR, 'en-GB');
		}
		$lang->load('plg_editors-xtd_modulesanywhere', JPATH_ADMINISTRATOR, null, 1);
		$lang->load('plg_system_modulesanywhere', JPATH_ADMINISTRATOR, null, 1);
		// load the content language file
		$lang->load('com_modules', JPATH_ADMINISTRATOR);

		// Initialize some variables
		$db = JFactory::getDBO();
		$option = 'modulesanywhere';

		$filter_order = $app->getUserStateFromRequest($option . 'filter_order', 'filter_order', 'm.position', 'string');
		$filter_order_Dir = $app->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', '', 'string');
		$filter_state = $app->getUserStateFromRequest($option . 'filter_state', 'filter_state', '', 'string');
		$filter_position = $app->getUserStateFromRequest($option . 'filter_position', 'filter_position', '', 'string');
		$filter_type = $app->getUserStateFromRequest($option . 'filter_type', 'filter_type', '', 'string');
		$filter_search = $app->getUserStateFromRequest($option . 'filter_search', 'filter_search', '', 'string');
		$filter_search = JString::strtolower($filter_search);

		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest('modulesanywhere_limitstart', 'limitstart', 0, 'int');

		$where[] = 'm.client_id = 0';

		// used by filter
		if ($filter_position) {
			if ($filter_position == 'none') {
				$where[] = 'm.position = ""';
			} else {
				$where[] = 'm.position = ' . $db->q($filter_position);
			}
		}
		if ($filter_type) {
			$where[] = 'm.module = ' . $db->q($filter_type);
		}
		if ($filter_search) {
			$where[] = 'LOWER( m.title ) LIKE ' . $db->q('%' . $db->escape($filter_search, true) . '%', false);
		}
		if ($filter_state != '') {
			$where[] = 'm.published = ' . $filter_state;
		}

		$where = implode(' AND ', $where);

		if ($filter_order == 'm.ordering') {
			$orderby = 'm.position, m.ordering ' . $filter_order_Dir;
		} else {
			$orderby = $filter_order . ' ' . $filter_order_Dir . ', m.ordering ASC';
		}

		// get the total number of records
		$query = $db->getQuery(true);
		$query->select('COUNT(DISTINCT m.id)');
		$query->from('#__modules AS m');
		$query->join('LEFT', '#__users AS u ON u.id = m.checked_out');
		$query->join('LEFT', '#__viewlevels AS g ON g.id = m.access');
		$query->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id');
		$query->where($where);
		$db->setQuery($query);
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);

		$query = $db->getQuery(true);
		$query->select('m.*, u.name AS editor, g.title AS groupname, MIN( mm.menuid ) AS pages');
		$query->from('#__modules AS m');
		$query->join('LEFT', '#__users AS u ON u.id = m.checked_out');
		$query->join('LEFT', '#__viewlevels AS g ON g.id = m.access');
		$query->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id');
		$query->where($where);
		$query->group('m.id');
		$query->order($orderby);
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}

		// get list of Positions for dropdown filter
		$query = $db->getQuery(true);
		$query->select('m.position AS value, m.position AS text');
		$query->from('#__modules as m');
		$query->where('m.client_id = 0');
		$query->where('m.position != ""');
		$query->group('m.position');
		$query->order('m.position');
		$db->setQuery($query);
		$positions = $db->loadObjectList();
		array_unshift($positions, $options[] = JHtml::_('select.option', 'none', ':: ' . JText::_('JNONE') . ' ::'));
		array_unshift($positions, JHtml::_('select.option', '', JText::_('COM_MODULES_OPTION_SELECT_POSITION')));
		$lists['position'] = JHtml::_('select.genericlist', $positions, 'filter_position', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $filter_position);

		// get list of Types for dropdown filter
		$query = $db->getQuery(true);
		$query->select('e.element AS value, e.name AS text');
		$query->from('#__extensions as e');
		$query->where('e.client_id = 0');
		$query->where('type = ' . $db->q('module'));
		$query->join('LEFT', '#__modules as m ON m.module = e.element AND m.client_id = e.client_id');
		$query->where('m.module IS NOT NULL');
		$query->group('e.element, e.name');
		$db->setQuery($query);
		$types = $db->loadObjectList();
		$lang = JFactory::getLanguage();
		foreach ($types as $i => $type) {
			$extension = $type->value;
			$source = JPATH_SITE . '/modules/' . $extension;
			$lang->load($extension . '.sys', JPATH_SITE, null, false, false)
				|| $lang->load($extension . '.sys', $source, null, false, false)
				|| $lang->load($extension . '.sys', JPATH_SITE, $lang->getDefault(), false, false)
				|| $lang->load($extension . '.sys', $source, $lang->getDefault(), false, false);
			$types[$i]->text = JText::_($type->text);
		}
		JArrayHelper::sortObjects($types, 'text', 1, true, $lang->getLocale());
		array_unshift($types, JHtml::_('select.option', '', JText::_('COM_MODULES_OPTION_SELECT_MODULE')));
		$lists['type'] = JHtml::_('select.genericlist', $types, 'filter_type', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $filter_type);

		// state filter
		$states = array();
		$states[] = JHtml::_('select.option', '', JText::_('JOPTION_SELECT_PUBLISHED'));
		$states[] = JHtml::_('select.option', '1', JText::_('JPUBLISHED'));
		$states[] = JHtml::_('select.option', '0', JText::_('JUNPUBLISHED'));
		$states[] = JHtml::_('select.option', '-2', JText::_('JTRASHED'));
		$lists['state'] = JHtml::_('select.genericlist', $states, 'filter_state', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $filter_state);

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['filter_search'] = $filter_search;

		$this->outputHTML($params, $rows, $pageNav, $lists);
	}

	function outputHTML(&$params, &$rows, &$page, &$lists)
	{
		$tag = explode(',', $params->module_tag);
		$tag = trim($tag['0']);
		$postag = explode(',', $params->modulepos_tag);
		$postag = trim($postag['0']);

		JHtml::_('behavior.tooltip');

		JHtml::stylesheet('nnframework/popup.min.css', false, true);
		?>
		<div style="margin: 0;">
			<form action="" method="post" name="adminForm" id="adminForm">
				<fieldset>
					<div style="float: left">
						<h1><?php echo JText::_('MODULES_ANYWHERE'); ?></h1>
					</div>
					<div style="float: right">
						<div class="button2-left">
							<div class="blank hasicon cancel">
								<a rel="" onclick="window.parent.SqueezeBox.close();" href="javascript://"
									title="<?php echo JText::_('JCANCEL') ?>"><?php echo JText::_('JCANCEL') ?></a>
							</div>
						</div>
					</div>
				</fieldset>

				<p><?php echo html_entity_decode(JText::_('MA_CLICK_ON_ONE_OF_THE_MODULES_LINKS'), ENT_COMPAT, 'UTF-8'); ?></p>

				<table class="adminform" cellspacing="2" style="width:auto;float:left;margin-right:10px;">
					<?php if ($params->override_style && (count(explode(',', $params->styles)) > 1 || $params->styles != $params->style)) : ?>
						<tr>
							<th>
								<?php echo JText::_('MA_MODULE_STYLE'); ?>:
							</th>
							<td>
								<?php
								$style = JFactory::getApplication()->input->get('style');
								if (!$style) {
									$style = $params->style;
								}
								?>
								<select name="style" class="inputbox">
									<?php foreach (explode(',', $params->styles) as $s) : ?>
										<option <?php echo ($s == $style) ? 'selected="selected"' : ''; ?> value="<?php echo $s; ?>"><?php echo $s; ?><?php echo ($s == $params->style) ? ' *' : ''; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					<?php endif; ?>
					<tr>
						<th>
							<span class="hasTip"
								title="<?php echo JText::_('COM_MODULES_FIELD_SHOWTITLE_LABEL') . '::' . JText::_('COM_MODULES_FIELD_SHOWTITLE_DESC'); ?>">
								<?php echo JText::_('COM_MODULES_FIELD_SHOWTITLE_LABEL'); ?>:
							</span>
						</th>
						<td>
							<select name="showtitle" class="inputbox">
								<option value=""><?php echo JText::_('JDEFAULT'); ?></option>
								<option value="0"><?php echo JText::_('JNO'); ?></option>
								<option value="1"><?php echo JText::_('JYES'); ?></option>
							</select>
						</td>
					</tr>
				</table>


				<div style="clear:both;"></div>

				<table class="adminform" cellspacing="1">
					<tbody>
						<tr>
							<td>
								<?php echo JText::_('Filter'); ?>:
								<input type="text" name="filter_search" id="filter_search" value="<?php echo $lists['filter_search']; ?>"
									class="text_area" onchange="this.form.submit();" />
								<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
								<button onclick="
								document.getElementById( 'filter_search' ).value='';
								document.getElementById( 'filter_position' ).value='0';
								document.getElementById( 'filter_type' ).value='0';
								document.getElementById( 'filter_state' ).value='';
								this.form.submit();"><?php echo JText::_('Reset'); ?></button>
							</td>
							<td style="text-align:right;">
								<?php
								echo $lists['position'];
								echo $lists['type'];
								echo $lists['state'];
								?>
							</td>
						</tr>
					</tbody>
				</table>

				<table class="adminlist" cellspacing="1">
					<thead>
						<tr>
							<th nowrap="nowrap" width="1%">
								<?php echo JHtml::_('grid.sort', 'ID', 'm.id', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
							<th class="title">
								<?php echo JHtml::_('grid.sort', 'Module Name', 'm.title', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
							<th nowrap="nowrap" width="7%">
								<?php echo JHtml::_('grid.sort', 'Position', 'm.position', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
							<th nowrap="nowrap" width="7%">
								<?php echo JHtml::_('grid.sort', 'Published', 'm.published', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
							<th nowrap="nowrap" width="1%">
								<?php echo JHtml::_('grid.sort', 'Order', 'm.ordering', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
							<th nowrap="nowrap" width="7%">
								<?php echo JHtml::_('grid.sort', 'Access', 'groupname', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
							<th nowrap="nowrap" width="5%">
								<?php echo JHtml::_('grid.sort', 'Pages', 'pages', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
							<th nowrap="nowrap" width="10%" class="title">
								<?php echo JHtml::_('grid.sort', 'Type', 'm.module', @$lists['order_Dir'], @$lists['order']); ?>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="8">
								<?php echo $page->getListFooter(); ?>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<?php
						$k = 0;
						for ($i = 0, $n = count($rows); $i < $n; $i++) {
							$row =& $rows[$i];

							if ($row->published) {
								$img = 'tick_l.png';
								$alt = JText::_('Published');
							} else {
								$img = 'publish_x_l.png';
								$alt = JText::_('Unpublished');
							}
							?>
							<tr class="<?php echo "row$k"; ?>">
								<td align="right">
									<?php echo '<label class="hasTip" title="' . JText::_('MA_USE_ID_IN_TAG') . '::{module ' . $row->id . '}"><a href="javascript://" onclick="modulesanywhere_jInsertEditorText( \'' . $row->id . '\' )">' . $row->id . '</a></label>'; ?>
								</td>
								<td>
									<?php echo '<label class="hasTip" title="' . JText::_('MA_USE_TITLE_IN_TAG') . '::{module ' . htmlspecialchars($row->title) . '}"><a href="javascript://" onclick="modulesanywhere_jInsertEditorText( \'' . addslashes(htmlspecialchars($row->title)) . '\' )">' . htmlspecialchars($row->title) . '</a></label>'; ?>
									<?php if (!empty($row->note)) : ?>
										<p class="smallsub">
											<?php echo JText::sprintf('JGLOBAL_LIST_NOTE', htmlspecialchars($row->note)); ?></p>
									<?php endif; ?>
								</td>
								<td align="center">
									<?php if ($row->position) : ?>
										<?php echo '<label class="hasTip" title="' . JText::_('MA_USE_MODULE_POSITION_TAG') . '::{modulepos ' . $row->position . '}"><a href="javascript://" onclick="modulesanywhere_jInsertEditorText( \'' . $row->position . '\', 1 )">' . $row->position . '</a></label>'; ?>
									<?php else: ?>
										<?php echo ':: ' . JText::_('JNONE') . ' ::'; ?>
									<?php endif; ?>
								</td>
								<td style="text-align:center;">
									<img src="<?php echo JURI::root(true) . '/media/nnframework/images/' . $img; ?>"
										width="16" height="16" border="0" alt="<?php echo $alt; ?>'" />
								</td>
								<td align="center">
									<?php echo $row->ordering; ?>
								</td>
								<td align="center"><?php echo JText::_($row->groupname); ?></td>
								<td align="center">
									<?php
									if (is_null($row->pages)) {
										echo JText::_('JNONE');
									} else if ($row->pages < 0) {
										echo JText::_('COM_MODULES_ASSIGNED_VARIES_EXCEPT');
									} else if ($row->pages > 0) {
										echo JText::_('COM_MODULES_ASSIGNED_VARIES_ONLY');
									} else {
										echo JText::_('JALL');
									}
									?>
								</td>
								<td>
									<?php echo $row->module ? $row->module : JText::_('User'); ?>
								</td>
							</tr>
							<?php
							$k = 1 - $k;
						}
						?>
					</tbody>
				</table>
				<input type="hidden" name="name" value="<?php echo JFactory::getApplication()->input->getString('name', 'text'); ?>" />
				<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
			</form>
			<?php
			if (JFactory::getApplication()->isAdmin()) {
				$user = JFactory::getUser();
				if ($user->authorise('core.admin', 1)) {
					echo '<em>' . str_replace('<a ', '<a target="_blank" ', html_entity_decode(JText::_('MA_SETTINGS'))) . '</em>';
				}
			}
			?>
		</div>
		<script type="text/javascript">
			function modulesanywhere_jInsertEditorText(id, modulepos)
			{
				f = document.getElementById('adminForm');
				if (modulepos) {
					str = '{<?php echo $postag; ?> ' + id + '}';
				} else {
					str = '{<?php echo $tag; ?> ' + id;
					<?php if ($params->override_style && (count(explode(',', $params->styles)) > 1 || $params->styles != $params->style)) : ?>
					var style = f.style.options[f.style.selectedIndex].value.trim();
					if (style && style != '<?php echo $params->style; ?>') {
						str += '|' + style;
					}
					<?php endif; ?>
					var showtitle = f.showtitle.options[f.showtitle.selectedIndex].value.trim();
					if (showtitle === '0' || showtitle === '1') {
						str += '|showtitle=' + showtitle;
					}
					str += '}';
				}


				window.parent.jInsertEditorText(str, '<?php echo JFactory::getApplication()->input->getString('name', 'text'); ?>');
				window.parent.SqueezeBox.close();
			}

			function toggleDiv()
			{
				if (document.getElementById('div_enable').checked) {
					document.getElementById('div_enable_div').style.display = 'block';
				} else {
					document.getElementById('div_enable_div').style.display = 'none';
				}
			}
			window.addEvent('domready', function() { toggleDiv(); });
		</script>
	<?php
	}
}
