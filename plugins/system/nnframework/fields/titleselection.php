<?php
/**
 * Element: TitleSelection
 * Displays Title with checkbox
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

class JFormFieldNN_TitleSelection extends JFormField
{
	public $type = 'TitleSelection';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		require_once __DIR__ . '/toggler.php';
		$toggler = new nnFieldToggler;

		$this->value = (int) $this->value;
		$label = $this->def('label');
		$param_name = $this->def('name');

		$html = array();

		if ($label) {
			$label = NNText::html_entity_decoder(JText::_($label));

			$html[] = '<div style="clear: both;"></div>';

			$class = 'nn_panel nn_panel_title nn_panel_toggle';
			$checked = '';
			if ($this->value === 1) {
				$class .= ' nn_panel_include';
				$checked = ' checked="checked"';
			} else if ($this->value === 2) {
				$class .= ' nn_panel_exclude';
				$checked = ' checked="checked"';
			}
			$html[] = '<div class="' . $class . '"><label class="nn_block nn_title" for="cb_' . $param_name . '">';
			$html[] = '<input id="' . $this->id . '" name="' . $this->name . '" value="1" type="checkbox" class="checkbox"'
				. ' onclick="nnScripts.setToggleTitleClass(this, this.checked, 2);"' . $checked . '>';
			$html[] = $label;
			$html[] = '<div style="clear: both;"></div>';
			$html[] = '</label></div>';

			$html[] = $toggler->getInput(array('div' => 1, 'param' => $param_name, 'value' => '1,2'));
			$html[] = '<div class="nn_panel nn_panel"><div class="nn_block">';

			$html[] = '<ul class="adminformlist"><li>';
		} else {
			$html[] = '<div style="clear: both;"></div>';
			$html[] = '</li></ul>';

			$html[] = '<div style="clear: both;"></div>';
			$html[] = '</div></div>';

			$html[] = $toggler->getInput(array('div' => 1));
		}

		return implode($html);
	}

	private function def($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
