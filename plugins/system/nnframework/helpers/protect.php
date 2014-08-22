<?php
/**
 * NoNumber Framework Helper File: Protect
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
 * Functions
 */
class NNProtect
{
	/* check if page should be protected for certain extensions
	*/
	public static function isProtectedPage($ext = '', $hastags = 0)
	{
		// return if disabled via url
		// return if current page is raw format
		// return if current page is NoNumber QuickPage
		// return if current page is a JoomFish or Josetta page
		return (
			($ext && JFactory::getApplication()->input->get('disable_' . $ext))
				|| JFactory::getApplication()->input->get('format') == 'raw'
				|| ($hastags
				&& (
					JFactory::getApplication()->input->getInt('nn_qp', 0)
						|| in_array(JFactory::getApplication()->input->get('option'), array('com_joomfishplus', 'com_josetta'))
				))
		);
	}

	/* check if page is an admin page
	*/
	public static function isAdmin($block_login = 0)
	{
		$options = array('com_acymailing');
		if ($block_login) {
			$options[] = 'com_login';
		}
		return (
			JFactory::getApplication()->isAdmin()
				&& !in_array(JFactory::getApplication()->input->get('option'), $options)
				&& JFactory::getApplication()->input->get('task') != 'preview'
		);
	}

	/* check if page is an edit page
	*/
	public static function isEditPage()
	{
		$option = JFactory::getApplication()->input->get('option');
		// always return false for these components
		if(in_array($option, array('com_rsevents','com_rseventspro')) ) {
			return 0;
		}

		$task = JFactory::getApplication()->input->get('task');
		$view = JFactory::getApplication()->input->get('view');
		if (!(strpos($task, '.') === false)) {
			$task = explode('.', $task);
			$task = array_pop($task);
		}
		if (!(strpos($view, '.') === false)) {
			$view = explode('.', $view);
			$view = array_pop($view);
		}
		return (
			in_array($task, array('edit', 'form', 'submission'))
				|| in_array($view, array('edit', 'form'))
				|| in_array(JFactory::getApplication()->input->get('do'), array('edit', 'form'))
				|| in_array(JFactory::getApplication()->input->get('layout'), array('edit', 'form', 'write'))
				|| in_array(JFactory::getApplication()->input->get('option'), array('com_contentsubmit', 'com_cckjseblod'))
		);
	}

	/* the regular expression to mach the edit form
	*/
	public static function getFormRegex($regex_format = 0)
	{
		$regex = '(<' . 'form [^>]*(id|name)="(adminForm|postform|submissionForm|default_action_user)")';

		if ($regex_format) {
			$regex = '#' . $regex . '#si';
		}

		return $regex;
	}

	/* protect complete adminForm (to prevent articles from being created when editing articles and such)
	*/
	public static function protectForm(&$string, $tags = array(), $protected = array())
	{
		if (!self::isEditPage()) {
			return;
		}

		if (!is_array($tags)) {
			$tags = array($tags);
		}

		if (empty ($protected)) {
			$protected = array();
			foreach ($tags as $i => $tag) {
				$protected[$i] = base64_encode($tag);
			}
		}

		$string = preg_replace(self::getFormRegex(1), '<!-- TMP_START_EDITOR -->\1', $string);
		$string = explode('<!-- TMP_START_EDITOR -->', $string);

		foreach ($string as $i => $str) {
			if (!empty($str) != '' && fmod($i, 2)) {
				$pass = 0;
				foreach ($tags as $tag) {
					if (!(strpos($str, $tag) === false)) {
						$pass = 1;
						break;
					}
				}
				if ($pass) {
					$str = explode('</form>', $str, 2);
					// protect tags only inside form fields
					if (preg_match_all('#(<textarea[^>]*>.*?<\/textarea>|<input[^>]*>)#si', $str['0'], $matches, PREG_SET_ORDER) > 0) {
						foreach ($matches as $match) {
							$field = str_replace($tags, $protected, $match['0']);
							$str['0'] = str_replace($match['0'], $field, $str['0']);
						}
					}
					$string[$i] = implode('</form>', $str);
				}
			}
		}

		$string = implode('', $string);
	}

	/* replace any protected tags to original
	*/
	public static function unprotectForm(&$string, $tags = array(), $protected = array())
	{
		if (!is_array($tags)) {
			$tags = array($tags);
		}

		if (empty ($protected)) {
			$protected = array();
			foreach ($tags as $i => $tag) {
				$protected[$i] = base64_encode($tag);
			}
		}

		$string = str_replace($protected, $tags, $string);
	}
}
