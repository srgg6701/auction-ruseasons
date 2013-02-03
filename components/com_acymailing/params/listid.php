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
JHTML::_('behavior.modal','a.modal');
if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php')){
	echo 'This module can not work without the AcyMailing Component';
}

if(!ACYMAILING_J16){
	class JElementListid extends JElement
	{
		function fetchElement($name, $value, &$node, $control_name)
		{
			$listType = acymailing_get('type.lists');
			array_shift($listType->values);

			return $listType->display($control_name.'[listid]',(int) $value,false);
		}
	}
}else{
	class JFormFieldListid extends JFormField
	{
		var $type = 'listid';

		function getInput() {

			$listType = acymailing_get('type.lists');
			array_shift($listType->values);

			return $listType->display($this->name,(int) $this->value,false);
		}
	}
}
