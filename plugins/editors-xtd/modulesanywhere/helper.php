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

/**
 ** Plugin that places the button
 */
class plgButtonModulesAnywhereHelper
{
	function __construct(&$params)
	{
		$this->params = $params;
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function render($name)
	{
		$button = new JObject;

		if (JFactory::getApplication()->isSite()) {
			$enable_frontend = $this->params->enable_frontend;
			if (!$enable_frontend) {
				return $button;
			}
		}

		JHtml::_('behavior.modal');
		JHtml::stylesheet('modulesanywhere/button.min.css', false, true);

		$icon = 'modulesanywhere';
		if (!$this->params->button_icon) {
			$icon = 'blank blank_modulesanywhere';
		}

		$link = 'index.php?nn_qp=1'
			. '&folder=plugins.editors-xtd.modulesanywhere'
			. '&file=modulesanywhere.inc.php'
			. '&name=' . $name;

		$text_ini = strtoupper(str_replace(' ', '_', $this->params->button_text));
		$text = JText::_($text_ini);
		if ($text == $text_ini) {
			$text = JText::_($this->params->button_text);
		}

		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', trim($text));
		$button->set('name', $icon);
		$button->set('options', "{handler: 'iframe', size: {x:window.getSize().x-100, y: window.getSize().y-100}}");

		return $button;
	}
}
