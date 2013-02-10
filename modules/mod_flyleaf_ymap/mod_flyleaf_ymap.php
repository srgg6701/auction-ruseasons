<?php
// элемент ymaps написан worstinme@ya.ru 
// спасибо можно отправить на 
// WM R138753723227 или про ЯД 41001613153284 
// ЯД пополняется только с других кошельков или счетов, пополнение через терминал невозможно

// в ближайшее время возможно выложу обновленную версию с дополнительными возможностями

defined('_JEXEC') or die;
	$jertva = array ('\r','\t','\n','\0','\x0B','\r\n',chr(13),chr(10));
	$gumno = array ('','','','','',' ',' ',' ');
	
	$location 		= $params->get("location");
	$center 		= $params->get("center");	
	$zoom 			= $params->get("zoom");
	$map_type 		= $params->get("map_type");
	$width 			= $params->get("width");
	$height 		= $params->get("height");
	$iconContent 	= str_replace($jertva,$gumno,addslashes($params->get("icon")));	
	$hintContent 	= str_replace($jertva,$gumno,addslashes($params->get("hint")));	
	$iconStyle		= $params->get("iconstyle");	
	$balloonContent	= str_replace($jertva,$gumno,addslashes($params->get("balloon")));	
	$balloon	 	= str_replace($jertva,$gumno,addslashes($params->get("balloon_on")));	
	$control1 		= $params->get("control1");
	$control2 		= $params->get("control2");
	$control3 		= $params->get("control3");
	$control4 		= $params->get("control4");	
	$selfscript		= str_replace($jertva,$gumno,$params->get("selfscript"));	
	$map_api_bk 	= base64_decode($params->get("bklnk"));
	$moduleclass_sfx	= $params->get("moduleclass_sfx");
	$maps_id   	= 'ymaps-'.uniqid();
	$fun_id = 'init_'.uniqid();


	if (empty($width)) $width = "100%";
	if (empty($height)) $height = "300px";
		
		
	if (!empty($location)) 
	{
		
		$doc =& JFactory::getDocument();
		$content = "";
		
		if (empty($center)) $center = $location;
		if (empty($zoom)) $zoom = "12";
		switch ($map_type) 
		{
			case 'yandex#map' : break;
			case 'yandex#satellite' : break;
			case 'yandex#hybrid' : break;
			case 'yandex#publicMap' : break;
			case 'yandex#publicMapHybrid' : break;
			default: $map_type = "yandex#map";break;
		}
				
		if ($balloon && !empty($balloonContent) )
		{
			$placemark = "myMap.balloon.open(
                [".$location."], { 
					contentBody: '".$balloonContent."'
                }, {
                    closeButton: false
                });";
		} else {
			switch ($iconStyle) 
			{
				case 'twirl#lightblueStretchyIcon' : break;
				case 'twirl#whiteStretchyIcon' : break;
				case 'twirl#greenStretchyIcon' : break;
				case 'twirl#redStretchyIcon' : 	break;
				case 'twirl#yellowStretchyIcon': break;
				case 'twirl#nightStretchyIcon': break;
				case 'twirl#greyStretchyIcon': break;
				case 'twirl#darkblueStretchyIcon': break;
				case 'twirl#blueStretchyIcon': break;
				case 'twirl#orangeStretchyIcon': break;
				case 'twirl#darkorangeStretchyIcon': break;
				case 'twirl#pinkStretchyIcon': break;
				case 'twirl#violetStretchyIcon': break;
				default: $iconStyle = "twirl#blueStretchyIcon";	break;
			}
			$placemark = "myMap.geoObjects.add(new ymaps.Placemark([".$location."], 
				{ iconContent: '".$iconContent."',hintContent: '".$hintContent."',balloonContent: '".$balloonContent."' }, 
		 		{ preset: '".$iconStyle."' }
				));";	
		}
		$tools = "myMap.controls";
		if ($control1) $tools .= ".add('zoomControl')";
		if ($control2) $tools .= ".add('mapTools')";
		if ($control3) $tools .= ".add('typeSelector')";
		if ($control4) $tools .= ".add('trafficControl',{ top: 5, right:150 })";
		$tools .= ";";
		$tools2 = "ymaps.ready(function() {
				idElem = document.getElementById('blnk');
				idElem.parentNode.removeChild(idElem);});";
		$ymap = "ymaps.ready(".$fun_id.");\nfunction ".$fun_id." () { 
		var myMap = new ymaps.Map(\"".$maps_id."\", {center: [".$center."],zoom: ".$zoom.",type: \"".$map_type."\"});\n".$tools."\n".$placemark."\n".$selfscript."\n};";
			
	} else {
		$ymap = '';
	}
		
if (!empty($moduleclass_sfx)) $moduleclass_sfx = " ".(htmlspecialchars($params->get('moduleclass_sfx'))); 

require JModuleHelper::getLayoutPath('mod_flyleaf_ymap', $params->get('layout', 'default'));