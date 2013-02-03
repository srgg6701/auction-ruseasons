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

class JButtonAcypopup extends JButton
{
	var $_name = 'Acypopup';


	function fetchButton( $type='Acypopup', $icon ='', $text='', $url = '', $width=640, $height=480)
	{
		if(!ACYMAILING_J30) {	$iconName = "icon-32-".$icon;}
		else{ $iconName = "icon-".$icon; }


		if(!ACYMAILING_J30) {
			JHTML::_('behavior.modal','a.modal');
			$html  = "<a id=\"a_$icon\" class=\"modal\" href=\"$url\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">\n";
			$html .= "<span class=\"$iconName\" title=\"$text\">\n</span>\n".$text."\n</a>\n";
			return $html;
		}
		$html = '<button class="btn btn-small modal" data-toggle="modal" data-target="#modal-'.$icon.'"><i class="'.$iconName.'"></i> '.$text.'</button>';
		$params['title']  = $text;
		$params['url']    = $url;
		$params['height'] = $height;
		$params['width']  = $width;
		$modalHtml = JHtml::_('bootstrap.renderModal', 'modal-'.$icon, $params);
		$html .= str_replace(
				array('id="modal-'.$icon.'"'),
				array('id="modal-'.$icon.'" style="width:'.($width+25).'px;height:'.($height+100).'px;margin-left:-'.(($width+20)/2).'px"'),
				$modalHtml
		);
		$html .= '<script>'."\r\n" . 'jQuery(document).ready(function(){jQuery("#modal-'.$icon.'").appendTo(jQuery(document.body));});'."\r\n".'</script>';
		return $html;
	}

	function fetchId( $type='Acypopup', $html = '', $id = 'Acypopup' )
	{
		return 'toolbar-'.$id;
	}
}

class JToolbarButtonAcypopup extends JButtonAcypopup {}
