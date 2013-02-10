<?php
/**
 * Main Plugin File
 * Does all the magic!
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

if (JFactory::getApplication()->isAdmin()) {
	// load the NoNumber Framework language file
	$lang = JFactory::getLanguage();
	if ($lang->getTag() != 'en-GB') {
		// Loads English language file as fallback (for undefined stuff in other language file)
		$lang->load('plg_system_nnframework', JPATH_ADMINISTRATOR, 'en-GB');
	}
	$lang->load('plg_system_nnframework', JPATH_ADMINISTRATOR, null, 1);
}

if (JFactory::getApplication()->isSite() && JFactory::getApplication()->input->get('option') == 'com_search') {
	$classes = get_declared_classes();
	if (!in_array('SearchModelSearch', $classes) && !in_array('SearchModelSearch', $classes)) {
		require_once JPATH_PLUGINS . '/system/nnframework/helpers/search.php';
	}
}

/**
 * Plugin that loads Framework
 */
class plgSystemNNFramework extends JPlugin
{
	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		if (JFactory::getApplication()->isSite()) {
			return;
		}

		$template = JFactory::getApplication()->getTemplate();
		if ($template == 'adminpraise3') {
			JHtml::stylesheet('nnframework/ap3.min.css', false, true);
		}
		if (in_array(
			JFactory::getApplication()->input->get('option'),
			array(
				'com_advancedmodules',
				'com_contenttemplater',
				'com_nonumbermanager',
				'com_rereplacer',
				'com_snippets',
			)
		)
		) {
			JFactory::getDocument()->addScriptDeclaration('var is_nn = 1;');
		}
	}

	function onAfterRoute()
	{
		if (!JFactory::getApplication()->input->getInt('nn_qp', 0)) {
			return;
		}

		// Include the Helper
		require_once JPATH_PLUGINS . '/system/nnframework/helper.php';
		$this->helper = new plgSystemNNFrameworkHelper;
	}
}
