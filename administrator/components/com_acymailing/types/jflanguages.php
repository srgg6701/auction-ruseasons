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

class jflanguagesType{
	var $onclick = '';
	var $id = 'jflang';

	function jflanguagesType(){
		$this->values = array();
		if(!ACYMAILING_J16 &&  @include_once( JPATH_SITE .DS. 'components' .DS. 'com_joomfish' .DS. 'helpers' .DS. 'defines.php' )){
			include_once(JOOMFISH_ADMINPATH .DS. 'classes' .DS. 'JoomfishManager.class.php');
			$jfManager = JoomFishManager::getInstance();
			$langActive = $jfManager->getActiveLanguages();
			$this->values[] = JHTML::_('select.option', '',JText::_('DEFAULT_LANGUAGE'));
			foreach($langActive as $oneLanguage){
				$this->values[] = JHTML::_('select.option', $oneLanguage->shortcode.','.$oneLanguage->id,$oneLanguage->name);
			}
		}
	}

	function display($map,$value = ''){
		if(empty($this->values)) return '';
		return JHTML::_('select.genericlist', $this->values, $map , 'size="1" '.$this->onclick, 'value', 'text', $value,$this->id);
	}
}
