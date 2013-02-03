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

class listsType{
	function listsType(){

		$listClass = acymailing_get('class.list');
		$this->data = $listClass->getLists('listid');

		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0', JText::_('ALL_LISTS') );
		foreach($this->data as $onelist){
			$this->values[] = JHTML::_('select.option', $onelist->listid, $onelist->name );
		}
	}

	function display($map,$value,$js = true){
		$onchange = $js ? 'onchange="document.adminForm.limitstart.value=0;document.adminForm.submit( );"' : '';
		return JHTML::_('select.genericlist',   $this->values, $map, 'class="inputbox" style="max-width:200px" size="1" '.$onchange, 'value', 'text', (int) $value,str_replace(array('[',']'),array('_',''),$map) );
	}

	function getData(){
		return $this->data;
	}
}
