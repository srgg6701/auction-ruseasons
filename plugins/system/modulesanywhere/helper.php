<?php
/**
 * Plugin Helper File
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

require_once JPATH_PLUGINS . '/system/nnframework/helpers/protect.php';

/**
 * Plugin that places modules
 */
class plgSystemModulesAnywhereHelper
{
	function __construct(&$params)
	{
		$this->params = $params;
		$this->params->comment_start = '<!-- START: Modules Anywhere -->';
		$this->params->comment_end = '<!-- END: Modules Anywhere -->';
		$this->params->message_start = '<!--  Modules Anywhere Message: ';
		$this->params->message_end = ' -->';
		$this->params->protect_start = '<!-- START: MA_PROTECT -->';
		$this->params->protect_end = '<!-- END: MA_PROTECT -->';

		$tags = array();
		$tags[] = preg_quote($this->params->module_tag, '#');
		$tags[] = preg_quote($this->params->modulepos_tag, '#');
		if ($this->params->handle_loadposition) {
			$tags[] = 'loadposition';
		}
		$tags = '(' . implode('|', $tags) . ')';
		$this->params->tags = $tags;

		$bts = '((?:<p(?: [^>]*)?>)?)((?:\s*<br ?/?>)?\s*)';
		$bte = '(\s*(?:<br ?/?>\s*)?)((?:</p>)?)';
		$regex = '((?:\{div(?: [^\}]*)\})?)(\s*)'
			. '\{' . $tags . ' ((?:[^\}]*?\{[^\}]*?\})*[^\}]*?)\}'
			. '(\s*)((?:\{/div\})?)';
		$this->params->regex = '#' . $bts . $regex . $bte . '#s';
		$this->params->regex2 = '#' . $regex . '#s';

		$user = JFactory::getUser();
		$this->params->aid = $user->getAuthorisedViewLevels();
	}

	////////////////////////////////////////////////////////////////////
	// onContentPrepare
	////////////////////////////////////////////////////////////////////

	function onContentPrepare(&$article)
	{
		$message = '';


		if (isset($article->text)) {
			$this->processModules($article->text, 'articles', $message);
		}
		if (isset($article->description)) {
			$this->processModules($article->description, 'articles', $message);
		}
		if (isset($article->title)) {
			$this->processModules($article->title, 'articles', $message);
		}
		if (isset($article->created_by_alias)) {
			$this->processModules($article->created_by_alias, 'articles', $message);
		}
	}

	////////////////////////////////////////////////////////////////////
	// onAfterDispatch
	////////////////////////////////////////////////////////////////////

	function onAfterDispatch()
	{
		// PDF
		if (JFactory::getDocument()->getType() == 'pdf') {
			$buffer = JFactory::getDocument()->getBuffer('component');
			if (is_array($buffer)) {
				if (isset($buffer['component'], $buffer['component'][''])) {
					if (isset($buffer['component']['']['component'], $buffer['component']['']['component'][''])) {
						$this->replaceTags($buffer['component']['']['component'][''], 0);
					} else {
						$this->replaceTags($buffer['component'][''], 0);
					}
				} else if (isset($buffer['0'], $buffer['0']['component'], $buffer['0']['component'][''])) {
					if (isset($buffer['0']['component']['']['component'], $buffer['0']['component']['']['component'][''])) {
						$this->replaceTags($buffer['component']['']['component'][''], 0);
					} else {
						$this->replaceTags($buffer['0']['component'][''], 0);
					}
				}
			} else {
				$this->replaceTags($buffer);
			}
			JFactory::getDocument()->setBuffer($buffer, 'component');
			return;
		}

		// FEED
		if ((JFactory::getDocument()->getType() == 'feed' || JFactory::getApplication()->input->get('option') == 'com_acymailing') && isset(JFactory::getDocument()->items)) {
			for ($i = 0; $i < count(JFactory::getDocument()->items); $i++) {
				$this->onContentPrepare(JFactory::getDocument()->items[$i]);
			}
		}

		$buffer = JFactory::getDocument()->getBuffer('component');
		if (!empty($buffer)) {
			if (is_array($buffer)) {
				if (isset($buffer['component']) && isset($buffer['component'][''])) {
					$this->tagArea($buffer['component'][''], 'MODA', 'component');
				}
			} else {
				$this->tagArea($buffer, 'MODA', 'component');
			}
			JFactory::getDocument()->setBuffer($buffer, 'component');
		}
	}

	////////////////////////////////////////////////////////////////////
	// onAfterRender
	////////////////////////////////////////////////////////////////////
	function onAfterRender()
	{
		// not in pdf's
		if (JFactory::getDocument()->getType() == 'pdf') {
			return;
		}

		$html = JResponse::getBody();
		if ($html == '') {
			return;
		}

		if (JFactory::getDocument()->getType() != 'html') {
			$this->replaceTags($html);
		} else {
			if (!(strpos($html, '<body') === false) && !(strpos($html, '</body>') === false)) {
				$html_split = explode('<body', $html, 2);
				$body_split = explode('</body>', $html_split['1'], 2);

				// only do stuff in body
				$this->protect($body_split['0']);
				$this->replaceTags($body_split['0']);

				$html_split['1'] = implode('</body>', $body_split);
				$html = implode('<body', $html_split);
			} else {
				$this->protect($html);
				$this->replaceTags($html);
			}
		}

		$this->cleanLeftoverJunk($html);
		$this->unprotect($html);

		JResponse::setBody($html);
	}

	function replaceTags(&$str)
	{
		if (!is_string($str) || $str == '') {
			return;
		}

		// COMPONENT
		if (JFactory::getDocument()->getType() == 'feed' || JFactory::getApplication()->input->get('option') == 'com_acymailing') {
			$s = '#(<item[^>]*>)#s';
			$str = preg_replace($s, '\1<!-- START: MODA_COMPONENT -->', $str);
			$str = str_replace('</item>', '<!-- END: MODA_COMPONENT --></item>', $str);
		}
		if (strpos($str, '<!-- START: MODA_COMPONENT -->') === false) {
			$this->tagArea($str, 'MODA', 'component');
		}

		$message = '';

		$components = $this->getTagArea($str, 'MODA', 'component');

		foreach ($components as $component) {
			$this->processModules($component['1'], 'components', $message);
			$str = str_replace($component['0'], $component['1'], $str);
		}

		// EVERYWHERE
		$this->processModules($str, 'other');
	}

	function tagArea(&$str, $ext = 'EXT', $area = '')
	{
		if ($str && $area) {
			$str = '<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->' . $str . '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
			if ($area == 'article_text') {
				$str = preg_replace('#(<hr class="system-pagebreak".*?/>)#si', '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->\1<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->', $str);
			}
		}
	}

	function getTagArea(&$str, $ext = 'EXT', $area = '')
	{
		$matches = array();
		if ($str && $area) {
			$start = '<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
			$end = '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
			$matches = explode($start, $str);
			array_shift($matches);
			foreach ($matches as $i => $match) {
				list($text) = explode($end, $match, 2);
				$matches[$i] = array(
					$start . $text . $end,
					$text
				);
			}
		}
		return $matches;
	}

	function processModules(&$string, $area = 'articles', $message = '')
	{

		if (preg_match('#\{' . $this->params->tags . '#', $string)) {
			jimport('joomla.application.module.helper');
			JPluginHelper::importPlugin('content');

			self::replace($string, $this->params->regex, $area, $message);
			self::replace($string, $this->params->regex2, $area, $message);
		}
	}

	function replace(&$string, $regex, $area = 'articles', $message = '')
	{
		if (@preg_match($regex . 'u', $string)) {
			$regex .= 'u';
		}

		$matches = array();
		$count = 0;

		$protects = array();
		while ($count++ < 10 && preg_match('#\{' . $this->params->tags . '#', $string) && preg_match_all($regex, $string, $matches, PREG_SET_ORDER) > 0) {
			foreach ($matches as $match) {
				if( !$this->processMatch($string, $match, $area, $message)) {
					$protected = $this->params->protect_start.base64_encode($match['0']).$this->params->protect_end;
					$string = str_replace($match['0'], $protected, $string);
					$protects[] = array($match['0'], $protected);
				}
			}
			$matches = array();
		}
		foreach ($protects as $protect) {
			$string = str_replace($protect['1'], $protect['0'], $string);
		}

	}

	function processMatch(&$string, &$match, $area = 'articles', $message = '')
	{
		$html = '';
		if ($message != '') {
			if ($this->params->place_comments) {
				$html = $this->params->message_start . $message . $this->params->message_end;
			}
		} else {
			if (count($match) < 10) {
				array_unshift($match, $match['0'], '');
				$match['2'] = '';
				array_push($match, '', '');
			}
			$p_start = $match['1'];
			$br1a = $match['2'];
			$div_start = $match['3'];
			$br2a = $match['4'];
			$type = trim($match['5']);
			$id = trim($match['6']);
			$br2a = $match['7'];
			$div_end = $match['8'];
			$br2b = $match['9'];
			$p_end = $match['10'];

			$type = trim($type);
			$id = trim($id);

			$chrome = '';
			$forcetitle = 0;

			$ignores = array();
			$overrides = array();

			$vars = str_replace('\|', '[:MA_BAR:]', $id);
			$vars = explode('|', $vars);
			$id = array_shift($vars);
			foreach ($vars as $var) {
				$var = trim(str_replace('[:MA_BAR:]', '|', $var));
				if (!$var) {
					continue;
				}
				if (strpos($var, '=') === false) {
					if ($this->params->override_style) {
						$chrome = $var;
					}
				} else {
					if ($type == $this->params->module_tag) {
						list($key, $val) = explode('=', $var, 2);
						$val = str_replace(array('\{', '\}'), array('{', '}'), $val);
						if ($key == 'style') {
							$chrome = $val;
						} else if (in_array($key, array('ignore_access', 'ignore_state', 'ignore_assignments', 'ignore_caching'))) {
							$ignores[$key] = $val;
						} else if ($key == 'showtitle') {
							$overrides['showtitle'] = $val;
							$forcetitle = $val;
						}
					}
				}
			}

			if (!$chrome) {
				$chrome = ($forcetitle) ? 'xhtml' : $this->params->style;
			}

			if ($type == $this->params->module_tag) {
				// module
				$html = $this->processModule($id, $chrome, $ignores, $overrides, $area);
				if ($html == 'MA_IGNORE') {
					return 0;
				}
			} else {
				// module position
				$html = $this->processPosition($id, $chrome);
			}

			if ($p_start && $p_end) {
				$p_start = '';
				$p_end = '';
			}

			$html = $br1a . $br2a . $html . $br2a . $br2b;

			if ($div_start) {
				$extra = trim(preg_replace('#\{div(.*)\}#si', '\1', $div_start));
				$div = '';
				if ($extra) {
					$extra = explode('|', $extra);
					$extras = new stdClass;
					foreach ($extra as $e) {
						if (!(strpos($e, ':') === false)) {
							list($key, $val) = explode(':', $e, 2);
							$extras->$key = $val;
						}
					}
					if (isset($extras->class)) {
						$div .= 'class="' . $extras->class . '"';
					}

					$style = array();
					if (isset($extras->width)) {
						if (is_numeric($extras->width)) {
							$extras->width .= 'px';
						}
						$style[] = 'width:' . $extras->width;
					}
					if (isset($extras->height)) {
						if (is_numeric($extras->height)) {
							$extras->height .= 'px';
						}
						$style[] = 'height:' . $extras->height;
					}
					if (isset($extras->align)) {
						$style[] = 'float:' . $extras->align;
					} else if (isset($extras->float)) {
						$style[] = 'float:' . $extras->float;
					}

					if (!empty($style)) {
						$div .= ' style="' . implode(';', $style) . ';"';
					}
				}
				$html = trim('<div ' . trim($div)) . '>' . $html . '</div>';

				$html = $p_end . $html . $p_start;
			} else {
				$html = $p_start . $html . $p_end;
			}

			$html = preg_replace('#((?:<p(?: [^>]*)?>\s*)?)((?:<br ?/?>)?\s*<div(?: [^>]*)?>.*?</div>\s*(?:<br ?/?>)?)((?:\s*</p>)?)#', '\3\2\1', $html);
			$html = preg_replace('#(<p(?: [^>]*)?>\s*)<p(?: [^>]*)?>#', '\1', $html);
			$html = preg_replace('#(</p>\s*)</p>#', '\1', $html);
		}

		if ($this->params->place_comments) {
			$html = $this->params->comment_start . $html . $this->params->comment_end;
		}

		$string = str_replace($match['0'], $html, $string);
		unset($match);
		return 1;
	}

	function processPosition($position, $chrome = 'none')
	{
		$renderer = JFactory::getDocument()->loadRenderer('module');

		$html = '';
		foreach (JModuleHelper::getModules($position) as $mod) {
			$html .= $renderer->render($mod, array('style' => $chrome));
		}
		return $html;
	}

	function processModule($id, $chrome = 'none', $ignores = array(), $overrides = array(), $area = 'articles')
	{
		require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';

		$ignore_access = isset($ignores['ignore_access']) ? $ignores['ignore_access'] : $this->params->ignore_access;
		$ignore_state = isset($ignores['ignore_state']) ? $ignores['ignore_state'] : $this->params->ignore_state;
		$ignore_assignments = isset($ignores['ignore_assignments']) ? $ignores['ignore_assignments'] : $this->params->ignore_assignments;
		$ignore_caching = isset($ignores['ignore_caching']) ? $ignores['ignore_caching'] : $this->params->ignore_caching;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('m.*');
		$query->from('#__modules AS m');
		$query->where('m.client_id = 0');
		if (is_numeric($id)) {
			$query->where('m.id = ' . (int) $id);
		} else {
			$query->where('m.title = ' . $db->q(NNText::html_entity_decoder($id)));
		}
		if (!$ignore_access) {
			$query->where('m.access IN (' . implode(',', $this->params->aid) . ')');
		}
		if (!$ignore_state) {
			$query->where('m.published = 1');
			$query->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id');
			$query->where('e.enabled = 1');
		}
		if (!$ignore_assignments) {
			$date = JFactory::getDate();
			$now = $date->toSql();
			$nullDate = $db->getNullDate();
			$query->where('(m.publish_up = ' . $db->q($nullDate) . ' OR m.publish_up <= ' . $db->q($now) . ')');
			$query->where('(m.publish_down = ' . $db->q($nullDate) . ' OR m.publish_down >= ' . $db->q($now) . ')');
		}
		$query->order('m.ordering');
		$db->setQuery($query);
		$module = $db->loadObject();

		if ($module && function_exists('plgSystemAdvancedModulesPrepareModuleList')) {
			$module->published = 1;
			$modules = array($module->id => $module);
			plgSystemAdvancedModulesPrepareModuleList($modules);
			$module = array_shift($modules);
			if (!$module->published && !$ignore_assignments) {
				$module = 0;
			}
		}

		if (empty($module)) {
			if ($this->params->place_comments) {
				$message = JText::_('MA_OUTPUT_REMOVED_NOT_PUBLISHED');
				return $this->params->message_start . $message . $this->params->message_end;
			} else {
				return '';
			}
		}

		//determine if this is a custom module
		$module->user = (substr($module->module, 0, 4) == 'mod_') ? 0 : 1;

		// set style
		$module->style = $chrome;

		if (($area == 'articles' && !$ignore_caching) || !empty($overrides)) {
			$json = ($module->params && substr(trim($module->params), 0, 1) == '{');
			if ($json) {
				$params = json_decode($module->params);
			} else {
				// Old ini style. Needed for crappy old style modules like swMenuPro
				$params = JRegistryFormat::getInstance('INI')->stringToObject($module->params);
			}

			// override module parameters
			if (!empty($overrides)) {
				foreach ($overrides as $key => $val) {
					if (isset($module->{$key})) {
						$module->{$key} = $val;
					} else {
						if ($val && $val['0'] == '[' && $val[strlen($val) - 1] == ']') {
							$val = json_decode('{"val":' . $val . '}');
							$val = $val->val;
						} else if (isset($params->{$key}) && is_array($params->{$key})) {
							$val = explode(',', $val);
						}
						$params->{$key} = $val;
					}
				}
				if ($json) {
					$module->params = json_encode($params);
				} else {
					$registry = new JRegistry;
					$registry->loadObject($params);
					$module->params = $registry->toString('ini');
				}
			}
		}

		if (isset($module->access) && !in_array($module->access, $this->params->aid)) {
			if ($this->params->place_comments) {
				$message = JText::_('MA_OUTPUT_REMOVED_ACCESS');
				return $this->params->message_start . $message . $this->params->message_end;
			} else {
				return '';
			}
		}

		$document = clone(JFactory::getDocument());
		$document->_type = 'html';
		$renderer = $document->loadRenderer('module');
		$html = $renderer->render($module, array('style' => $chrome, 'name' => ''));

		// don't return html on article level when caching is set
		if (
			$area == 'articles'
			&& !$ignore_caching
			&& (
				(isset($params->cache) && !$params->cache)
				|| (isset($params->owncache) && !$params->owncache) // for stupid modules like RAXO that mess about with default params
			)
		) {
			return 'MA_IGNORE';
		}

		return $html;
	}

	function protect(&$str)
	{
		NNProtect::protectForm($str, array('{' . $this->params->module_tag, '{' . $this->params->modulepos_tag, '{loadposition'));
	}

	function unprotect(&$str)
	{
		NNProtect::unprotectForm($str, array('{' . $this->params->module_tag, '{' . $this->params->modulepos_tag, '{loadposition'));
	}

	function cleanLeftoverJunk(&$str)
	{
		if (!(strpos($str, '{/' . $this->params->module_tag . '}') === false)) {
			$regex = $this->params->regex;
			if (@preg_match($regex . 'u', $str)) {
				$regex .= 'u';
			}
			$str = preg_replace($regex, '', $str);
		}
		$str = preg_replace('#<\!-- (START|END): MODA_[^>]* -->#', '', $str);
		if (!$this->params->place_comments) {
			$str = str_replace(
				array(
					$this->params->comment_start, $this->params->comment_end,
					htmlentities($this->params->comment_start), htmlentities($this->params->comment_end),
					urlencode($this->params->comment_start), urlencode($this->params->comment_end)
				), '', $str
			);
			$str = preg_replace('#' . preg_quote($this->params->message_start, '#') . '.*?' . preg_quote($this->params->message_end, '#') . '#', '', $str);
		}
	}
}
