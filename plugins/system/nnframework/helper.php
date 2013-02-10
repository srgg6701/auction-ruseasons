<?php
/**
 * Plugin Helper File
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

/**
 * ...
 */
class plgSystemNNFrameworkHelper
{
	function __construct()
	{
		$url = JFactory::getApplication()->input->getString('url', '');
		require_once JPATH_PLUGINS . '/system/nnframework/helpers/functions.php';
		$func = new NNFrameworkFunctions;

		if ($url) {
			echo $func->getByUrl($url);
			die;
		}

		$file = JFactory::getApplication()->input->getString('file', '');

		// only allow files that have .inc.php in the file name
		if (!$file || (strpos($file, '.inc.php') === false)) {
			die;
		}

		$folder = JFactory::getApplication()->input->getString('folder', '');
		if ($folder) {
			$file = implode('/', explode('.', $folder)) . '/' . $file;
		}

		$allowed = array(
			'administrator/components/com_dbreplacer/dbreplacer.inc.php',
			'administrator/components/com_nonumbermanager/details.inc.php',
			'administrator/modules/mod_addtomenu/addtomenu.inc.php',
			'media/rereplacer/images/image.inc.php',
			'plugins/editors-xtd/articlesanywhere/articlesanywhere.inc.php',
			'plugins/editors-xtd/contenttemplater/contenttemplater.inc.php',
			'plugins/editors-xtd/modulesanywhere/modulesanywhere.inc.php',
			'plugins/editors-xtd/snippets/snippets.inc.php',
			'plugins/editors-xtd/sourcerer/sourcerer.inc.php'
		);

		if (!$file || (in_array($file, $allowed) === false)) {
			die;
		}

		jimport('joomla.filesystem.file');

		if (JFactory::getApplication()->isSite() && !JFactory::getApplication()->input->get('usetemplate')) {
			JFactory::getApplication()->setTemplate('../administrator/templates/bluestork');
		}

		$_REQUEST['tmpl'] = 'component';
		JFactory::getApplication()->input->set('option', '1');

		JFactory::getApplication()->set('_messageQueue', '');

		JFactory::getDocument()->addStyleSheet(JURI::root(true) . '/administrator/templates/bluestork/css/template.css');

		$file = JPATH_SITE . '/' . $file;

		$html = '';
		if (JFile::exists($file)) {
			ob_start();
			include $file;
			$html = ob_get_contents();
			ob_end_clean();
		}

		JFactory::getDocument()->setBuffer($html, 'component');

		JFactory::getApplication()->render();

		$html = JResponse::toString(JFactory::getApplication()->getCfg('gzip'));
		$html = preg_replace('#\s*<' . 'link [^>]*href="[^"]*templates/system/[^"]*\.css[^"]*"[^>]* />#s', '', $html);

		echo $html;

		die;
	}
}
