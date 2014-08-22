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
jimport( 'joomla.html.parameter' );

class acymailingView extends JView{

}

class acymailingControllerCompat extends JController{

}

function acymailing_loadResultArray(&$db){
	return $db->loadResultArray();
}

function acymailing_loadMootools(){
	JHTML::_('behavior.mootools');
}

function acymailing_getColumns($table){
	$db = JFactory::getDBO();
	$allfields = $db->getTableFields($table);
	return reset($allfields);
}

function acymailing_getEscaped($value, $extra = false) {
	$db = JFactory::getDBO();
	return $db->getEscaped($value, $extra);
}

function acymailing_getFormToken() {
	return JUtility::getToken();
}

JHTML::_('select.booleanlist','acymailing');
if(!class_exists('acyParameter')){
	class acyParameter extends JParameter{}
}
class JHtmlAcyselect extends JHTMLSelect{}
