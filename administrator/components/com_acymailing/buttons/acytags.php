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

class JButtonAcytags extends JButton
{
	var $_name = 'Acytags';

	function fetchButton( $type='Acytags',$newstype = 'news')
	{
		$url = JURI::base()."index.php?option=com_acymailing&ctrl=tag&task=tag&tmpl=component&type=".$newstype;
		$top = 0;
		$left = 0;
		$width = 780;
		$height = 550;

		$text	= JText::_('TAGS');
		if(!ACYMAILING_J30)	$class	= "icon-32-tag";
		else $class	= "icon-14-tag";

		if(!ACYMAILING_J30) {
			JHTML::_('behavior.modal');
			$html	= "<a class=\"modal\" onclick=\"try{IeCursorFix();}catch(e){}\" href=\"$url\" rel=\"{handler: 'iframe', size: {x: $width, y: $height}}\">\n";
			$html .= "<span class=\"$class\" title=\"$text\"></span>$text</a>\n";
			return $html;
		}

		$html = '<button class="btn btn-small modal" data-toggle="modal" data-target="#modal-'.$type.'"><i class="'.$class.'"></i> '.$text.'</button>';
		$params['title']  = $text;
		$params['url']    = $url;
		$params['height'] = $height;
		$params['width']  = $width;
		$modalHtml = JHtml::_('bootstrap.renderModal', 'modal-'.$type, $params);
		$html .= str_replace(
				array('id="modal-'.$type.'"'),
				array('id="modal-'.$type.'" style="width:'.($width+20).'px;height:'.($height+90).'px;margin-left:-'.(($width+20)/2).'px"'),
				$modalHtml
		);
		$html .= '<script>'."\r\n" . 'jQuery(document).ready(function(){jQuery("#modal-'.$type.'").appendTo(jQuery(document.body));});'."\r\n".'</script>';
		return $html;
	}

	function fetchId($name)
	{
		return "toolbar-popup-Acytags";
	}
}

class JToolbarButtonAcytags extends JButtonAcytags {}
