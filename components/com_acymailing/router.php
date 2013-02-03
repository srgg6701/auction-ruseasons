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

function AcymailingBuildRoute( &$query )
{
	$segments = array();

	if (isset($query['ctrl'])) {
		$segments[] = $query['ctrl'];
		unset( $query['ctrl'] );
		if (isset($query['task'])) {
			$segments[] = $query['task'];
			unset( $query['task'] );
		}
	}elseif(isset($query['view'])){
		$segments[] = $query['view'];
		unset( $query['view'] );
		if(isset($query['layout'])){
			$segments[] = $query['layout'];
			unset( $query['layout'] );
		}
	}

	if(!empty($query)){
		foreach($query as $name => $value){
			if(!in_array($name,array('option','Itemid','start','format','limitstart','no_html'))){
				$segments[] = $name.':'.$value;
				unset($query[$name]);
			}
		}
	}

	return $segments;
}

function AcymailingParseRoute( $segments )
{
	$vars = array();

	if(!empty($segments)){
		$i = 0;
		foreach($segments as $name){
			if(strpos($name,':')){
				list($arg,$val) = explode(':',$name);
				if(is_numeric($arg)) $vars['Itemid'] = $arg;
				else $vars[$arg] = $val;
			}else{
				$i++;
				if($i == 1) $vars['ctrl'] = $name;
				elseif($i == 2) $vars['task'] = $name;
			}
		}
	}

	return $vars;
}
