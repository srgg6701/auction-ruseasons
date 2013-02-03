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

class testreceiverType{
	function testreceiverType(){
		$this->values = array();
		$user = JFactory::getUser();
		$this->values[] = JHTML::_('select.option', 'user',$user->username.' ( '.$user->email.' )');
		$this->values[] = JHTML::_('select.option', 'other',JText::_('OTHER'));
		if(!ACYMAILING_J16){
			$this->values[] = JHTML::_('select.option', '<OPTGROUP>',JText::_('ACY_GROUP'));
			$this->values[] = JHTML::_('select.option', 'gid_23',JText::_('MANAGER'));
			$this->values[] = JHTML::_('select.option', 'gid_24',JText::_('ADMINISTRATOR'));
			$this->values[] = JHTML::_('select.option', 'gid_25',JText::_('SUPER_ADMIN'));
			$this->values[] = JHTML::_('select.option', '</OPTGROUP>');
		}

		$js = "function updateReceiver(){";
			$js .= "receiver_type = window.document.getElementById('receiver_type').value;";
			$js .= "if(receiver_type == 'other') {window.document.getElementById('emailfield').style.display = 'block';}else{window.document.getElementById('emailfield').style.display = 'none';}";
		$js .= '}';
		$js .='window.addEvent(\'domready\', function(){ updateReceiver(); });';

		$js .= "function affectUser(idcreator,name,email){
			window.document.getElementById('test_email').value = email;
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );

	}

	function display($map,$value = ''){
		return JHTML::_('select.genericlist', $this->values, $map , 'size="1" onchange="updateReceiver()"', 'value', 'text', $value,'receiver_type');
	}

}
