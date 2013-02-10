<?php
/**
 * Element: ColorPicker
 * Displays a textfield with a color picker
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

jimport('joomla.form.formfield');

class JFormFieldNN_ColorPicker extends JFormField
{
	public $type = 'ColorPicker';

	protected function getInput()
	{
		$field = new nnFieldColorPicker;
		return $field->getInput($this->name, $this->id, $this->value, $this->element->attributes());
	}
}

class nnFieldColorPicker
{
	function getInput($name, $id, $value, $params)
	{
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->params = $params;

		JHtml::stylesheet('nnframework/colorpicker.min.css', false, true);
		JHtml::script('nnframework/colorpicker/color_functions.js', false, true);
		JHtml::script('nnframework/colorpicker/js_color_picker_v2.js', false, true);

		$this->value = strtoupper(preg_replace('#[^a-z0-9]#si', '', $this->value));
		$color = $this->value;
		if (!$color) {
			$color = 'DDDDDD';
		}

		$html = array();
		if ($this->def('inlist', 0) && $this->def('action')) {
			$html[] = '<input onclick="showColorPicker(this,this,\'' . addslashes($this->def('action')) . '\')" style="background-color:#' . $color . ';" type="text" name="' . $this->name . '" id="' . $this->name . $this->id . '" value="' . $this->value . '" class="nn_color nn_color_list" maxlength="6" size="1" />';
		} else {
			$html[] = '<fieldset id="' . $this->id . '" class="radio">';
			$html[] = '<label class="radio" for="' . $this->id . '" style="width:auto;min-width:0;padding-right:0;">#&nbsp;</label>';
			$html[] = '<input onclick="showColorPicker(this,this)" onchange="this.style.backgroundColor=\'#\'+this.value" style="background-color:#' . $color . ';" type="text" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '" class="nn_color" maxlength="6" size="8" />';
			$html[] = '</fieldset>';
		}

		return implode('', $html);
	}

	private function def($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
