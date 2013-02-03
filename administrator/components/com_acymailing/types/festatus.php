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

class festatusType{
	function festatusType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '-1', JText::_('JOOMEXT_NO') );
		$this->values[] = JHTML::_('select.option', '1', JText::_('JOOMEXT_YES') );
	}

	function display($map,$value){
		static $i = 0;
		$value = (int) $value;
		$value = ($value >= 1) ? 1 : -1;
		return JHTML::_('acyselect.radiolist', $this->values, $map , 'class="radiobox btn-primary" size="1"', 'value', 'text', (int) $value,'status'.$i++);
	}

}
