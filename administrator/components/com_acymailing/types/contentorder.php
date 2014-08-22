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

class contentorderType{
	var $onclick = 'updateTag();';
	function contentorderType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', "|order:id,DESC",JText::_('ACY_ID'));
		$this->values[] = JHTML::_('select.option', "|order:ordering,ASC",JText::_('ACY_ORDERING'));
		$this->values[] = JHTML::_('select.option', "|order:created,DESC",JText::_('CREATED_DATE'));
		$this->values[] = JHTML::_('select.option', "|order:modified,DESC",JText::_('MODIFIED_DATE'));
		$this->values[] = JHTML::_('select.option', "|order:title,ASC",JText::_('FIELD_TITLE'));
		$this->values[] = JHTML::_('select.option', "|order:rand",JText::_('ACY_RANDOM'));
	}

	function display($map,$value){
		return JHTML::_('select.genericlist', $this->values, $map , 'size="1" style="width:150px;" onchange="'.$this->onclick.'"', 'value', 'text', (string) $value);
	}

}
