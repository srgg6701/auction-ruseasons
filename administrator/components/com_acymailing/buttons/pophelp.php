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

class JButtonPophelp extends JButton
{
	var $_name = 'Pophelp';


	function fetchButton( $type='Pophelp', $namekey = '', $id = 'pophelp' )
	{
		acymailing_loadMootools();

		$doc = JFactory::getDocument();
		$config =& acymailing_config();
		$level = $config->get('level');

		$url = ACYMAILING_HELPURL.$namekey.'&level='.$level;
		$iFrame = "'<iframe src=\'$url\' width=\'100%\' height=\'100%\' scrolling=\'auto\'></iframe>'";

		$js = "var openHelp = true; function displayDoc(){var box=$('iframedoc'); if(openHelp){box.innerHTML = ".$iFrame.";box.style.display = 'block';box.style.height = '0';}";
		$js .= "try{
					var fx = box.effects({duration: 1500, transition:
					Fx.Transitions.Quart.easeOut});
					if(openHelp){
						fx.start({'height': 400});
					}else{
						fx.start({'height': 0}).chain(function() {
							box.innerHTML='';
							box.setStyle('display','none');
						});
					}
				}catch(err){
					box.style.height = '400px';
					var myVerticalSlide = new Fx.Slide('iframedoc');
 					if(openHelp){
 						myVerticalSlide.hide().slideIn();
					}else{
						myVerticalSlide.slideOut().chain(function() {
						box.innerHTML='';
						box.setStyle('display','none');
					});
				}
			} openHelp = !openHelp; return false;}";
		$doc->addScriptDeclaration( $js );
		if(!ACYMAILING_J30)
			return '<a href="'.$url.'" target="_blank" onclick="return displayDoc();" class="toolbar"><span class="icon-32-help" title="'.JText::_('ACY_HELP',true).'"></span>'.JText::_('ACY_HELP').'</a>';
		return '<button class="btn btn-small" onclick="return displayDoc();"><i class="icon-help"></i> '.JText::_('ACY_HELP').'</button>';
	}

	function fetchId( $type='Pophelp', $html = '', $id = 'pophelp' )
	{
		return $this->_name.'-'.$id;
	}
}

class JToolbarButtonPophelp extends JButtonPophelp {}
