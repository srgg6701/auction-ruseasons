<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php


class TagViewTag extends acymailingView
{

	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function tag(){

		$doc = JFactory::getDocument();
		$doc->addStyleSheet( ACYMAILING_CSS.'frontendedition.css' );

		JPluginHelper::importPlugin('acymailing');

		$dispatcher = JDispatcher::getInstance();
		$tagsfamilies = $dispatcher->trigger('acymailing_getPluginType');
		$defaultFamily = reset($tagsfamilies);
		$app = JFactory::getApplication();
		$fctplug = $app->getUserStateFromRequest( ACYMAILING_COMPONENT.".tag", 'fctplug',$defaultFamily->function,'cmd' );

		ob_start();
		$defaultContents = $dispatcher->trigger($fctplug);
		$defaultContent = ob_get_clean();

		$js = 'function insertTag(){if(window.parent.insertTag(window.document.getElementById(\'tagstring\').value)) {acymailing_js.closeBox(true);}}';
		$js.= 'function setTag(tagvalue){window.document.getElementById(\'tagstring\').value = tagvalue;}';
		$js .='function showTagButton(){window.document.getElementById(\'insertButton\').style.display = \'inline\'; window.document.getElementById(\'tagstring\').style.display=\'inline\';}';
		$js .='function hideTagButton(){}';

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );


		$this->assignRef('fctplug',$fctplug);
		$type = JRequest::getString('type','news');
		$this->assignRef('type',$type);
		$this->assignRef('defaultContent',$defaultContent);
		$this->assignRef('tagsfamilies',$tagsfamilies);
		$app = JFactory::getApplication();
		$this->assignRef('app',$app);
		$ctrl = JRequest::getString('ctrl');
		$this->assignRef('ctrl',$ctrl);
	}
}
