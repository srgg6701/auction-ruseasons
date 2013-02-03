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

class JButtonDirectprint extends JButton
{
	var $_name = 'Directprint';


	function fetchButton( $type='Directprint', $namekey = '', $id = 'directprint' )
	{

		$doc = JFactory::getDocument();
		$doc->addStyleSheet( ACYMAILING_CSS.'acyprint.css','text/css','print' );

		if(!ACYMAILING_J30) {
			return '<a href="#" onclick="window.print();return false;" class="toolbar"><span class="icon-32-acyprint" title="'.JText::_('ACY_PRINT',true).'"></span>'.JText::_('ACY_PRINT').'</a>';
		}

		return '<button class="btn btn-small" onclick="window.print();return false;"><i class="icon-14-acyprint"></i> '.JText::_('ACY_PRINT',true).'</button>';

	}


	function fetchId( $type='Directprint', $html = '', $id = 'directprint' )
	{
		return $this->_name.'-'.$id;
	}
}

class JToolbarButtonDirectprint extends JButtonDirectprint {}
