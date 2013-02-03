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
if(version_compare(PHP_VERSION,'5.0.0','<')){
	echo '<p style="color:red">This version of AcyMailing does not support PHP4, it is time to upgrade your server to PHP5!</p>';
	exit;
}

if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php')){
	echo "Could not load Acy helper file";
	return;
}

if(defined('JDEBUG') AND JDEBUG) acymailing_displayErrors();

$taskGroup = JRequest::getCmd('ctrl',JRequest::getCmd('gtask','dashboard'));

$config =& acymailing_config();
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$cssBackend = $config->get('css_backend','default');
if(!empty($cssBackend)){
	$doc->addStyleSheet( ACYMAILING_CSS.'component_'.$cssBackend.'.css?v='.str_replace('.','',$config->get('version')));
}
if(JRequest::getCmd('tmpl') == 'component'){
	$doc->addStyleSheet( ACYMAILING_CSS.'frontendedition.css' );
}

$doc->addScript(ACYMAILING_JS.'acymailing_compat.js');

JHTML::_('behavior.tooltip');

$bar = JToolBar::getInstance('toolbar');
$bar->addButtonPath(ACYMAILING_BUTTON);

if($taskGroup != 'update' && !$config->get('installcomplete')){
	$url = acymailing_completeLink('update&task=install',false,true);
	echo "<script>document.location.href='".$url."';</script>\n";
	echo 'Install not finished... You will be redirected to the second part of the install screen<br/>';
	echo '<a href="'.$url.'">Please click here if you are not automatically redirected within 3 seconds</a>';
	return;
}



$action = JRequest::getCmd('task','listing');

if(JRequest::getString('tmpl') !== 'component' && !JRequest::getInt('hidemainmenu') && $config->get('menu_position','under') == 'above' && !in_array($action,array('add','edit','preview','savepreview','export','import','apply','doexport')) && !in_array($taskGroup,array('filter'))){
	$menuHelper = acymailing_get('helper.acymenu');
	echo $menuHelper->display($taskGroup);
}

if($taskGroup == 'config') $taskGroup = 'cpanel';

$currentuser = JFactory::getUser();
if($taskGroup != 'update' && ACYMAILING_J16 && !$currentuser->authorise('core.manage', 'com_acymailing')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
if($taskGroup == 'cpanel' && ACYMAILING_J16 && !$currentuser->authorise('core.admin', 'com_acymailing')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(!include(ACYMAILING_CONTROLLER.$taskGroup.'.php')){
	$app->redirect('index.php?option=com_acymailing');
	return;
}
$className = ucfirst($taskGroup).'Controller';
$classGroup = new $className();

JRequest::setVar( 'view', $classGroup->getName() );
$classGroup->execute( $action);
$classGroup->redirect();
if(JRequest::getString('tmpl') !== 'component'){
	echo acymailing_footer();
}
