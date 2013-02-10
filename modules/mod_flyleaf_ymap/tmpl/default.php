<?php
// элемент ymaps написан worstinme@ya.ru 
// спасибо можно отправить на 
// WM R138753723227 или про ЯД 41001613153284 
// ЯД пополняется только с других кошельков или счетов, пополнение через терминал невозможно

// в ближайшее время возможно выложу обновленную версию с дополнительными возможностями

defined('_JEXEC') or die;

$doc->addScript('http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=mod_flyleaf_ymap&lang=ru-RU');
$doc->addScriptDeclaration( $ymap ,'text/javascript');


?>
<div id="<?php echo $maps_id; ?>" class="ymaps<?php echo $moduleclass_sfx ?>"style="width:<?php echo $width ?>;height:<?php echo $height ?>;"><?php if ($ymap = '') echo "Неверно указаны или отсутствуют координаты метки"; ?></div>
