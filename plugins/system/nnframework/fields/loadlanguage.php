<?php
/**
 * Element: Load Language
 * Loads the English language file as fallback
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

class JFormFieldNN_LoadLanguage extends JFormField
{
	public $type = 'LoadLanguage';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		JHtml::_('behavior.mootools');
		JHtml::script('nnframework/script.min.js', false, true);

		$extension = $this->def('extension');
		$admin = $this->def('admin', 1);

		$path = $admin ? JPATH_ADMINISTRATOR : JPATH_SITE;
		// load the admin language file
		$lang = JFactory::getLanguage();
		if ($lang->getTag() != 'en-GB') {
			// Loads English language file as fallback (for undefined stuff in other language file)
			$lang->load($extension, $path, 'en-GB');
		}
		$lang->load($extension, $path, null, 1);

		return '';
	}

	function loadLanguage($extension, $admin = 1)
	{
		if ($extension) {
			if ($admin) {
				$path = JPATH_ADMINISTRATOR;
			} else {
				$path = JPATH_SITE;
			}
			$lang = JFactory::getLanguage();
			if ($lang->getTag() != 'en-GB') {
				// Loads English language file as fallback (for undefined stuff in other language file)
				$lang->load($extension, $path, 'en-GB');
			}
			$lang->load($extension, $path, null, 1);
		}
	}

	private function def($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
