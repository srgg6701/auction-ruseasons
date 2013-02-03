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

class listcreatorType{
	function listcreatorType(){

		$db = JFactory::getDBO();

		$db->setQuery('SELECT COUNT(*) as total,userid FROM #__acymailing_list WHERE `type` = "list" AND `userid` > 0 GROUP BY userid');
		$allusers = $db->loadObjectList('userid');

		$allnames = array();
		if(!empty($allusers)){
			$db->setQuery('SELECT name,id FROM #__users WHERE id IN ('.implode(',',array_keys($allusers)).') ORDER BY name ASC');
			$allnames = $db->loadObjectList('id');
		}

		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0', JText::_('ALL_CREATORS') );
		foreach($allnames as $userid => $oneCreator){
			$this->values[] = JHTML::_('select.option', $userid, $oneCreator->name.' ( '.$allusers[$userid]->total.' )' );
		}
	}

	function display($map,$value){
		return JHTML::_('select.genericlist',   $this->values, $map, 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', (int) $value );
	}
}
