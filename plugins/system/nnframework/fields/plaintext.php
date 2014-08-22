<?php
/**
 * Element: PlainText
 * Displays plain text as element
 *
 * @package         NoNumber Framework
 * @version         13.2.2
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2012 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';

/**
 * PlainText Element
 */
class JFormFieldNN_PlainText extends JFormField
{
	public $type = 'PlainText';

	protected function getLabel()
	{
		$this->params = $this->element->attributes();
		$label = $this->prepareText($this->def('label'));
		if ($this->def('description') || $this->value) {
			if ($this->def('description') && $this->value) {
				$class = ' class="hasTip"';
				$title = ' title="' . $label . '::' . $this->prepareText($this->def('description')) . '"';
				return '<label' . $class . $title . '>' . $label . '</label>';
			}
			return '<label>' . $label . '</label>';
		}
		return $label;
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$description = (trim($this->value) != '') ? trim($this->value) : $this->def('description');
		$description = $this->prepareText($description);

		if ($description != '' && $this->def('label') || $this->value) {
			// display as label if there is more than just a description
			return '<fieldset class="radio"><label>' . $description . '</label></fieldset>';
		}
		return $description;
	}

	private function prepareText($str = '')
	{
		if ($str == '') {
			return '';
		}

		// variables
		$v1 = JText::_($this->def('var1'));
		$v2 = JText::_($this->def('var2'));
		$v3 = JText::_($this->def('var3'));
		$v4 = JText::_($this->def('var4'));
		$v5 = JText::_($this->def('var5'));

		$str = JText::sprintf(JText::_($str), $v1, $v2, $v3, $v4, $v5);
		$str = trim(NNText::html_entity_decoder($str));
		$str = str_replace('&quot;', '"', $str);
		$str = str_replace('span style="font-family:monospace;"', 'span class="nn_code"', $str);

		return $str;
	}

	private function def($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
