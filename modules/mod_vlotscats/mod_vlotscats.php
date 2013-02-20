<?php
// No direct access
defined('_JEXEC') or die('Restricted access'); 
require_once dirname(__FILE__).DS."helper.php";
require_once JPATH_SITE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
// загрузить класс Helper (расширяющий JModuleHelper)
// получить путь к файлу для размещения контента (по умолчанию - tmpl/default.php)
// $params - object(JRegistry)
require modVlotscatsHelper::getLayoutPath('mod_vlotscats', $params->get('layout', 'default'));
if (JRequest::getVar('help')):?>
<ol>
  <li>Разместить файлы модуля в директории <strong>/modules/</strong></li>
  <li>Зайти в раздел Extension <strong>Manager: Discover</strong></li>
  <li>Щёлкнуть <strong>Discover</strong></li>
  <li>Если модуль обнаружен, щёлкнуть <strong>Install</strong></li>
  <li>Зайти в раздел <strong>Module Manager: Modules</strong></li>
  <li>Щёлкнуть <strong>New</strong></li>
  <li>В раскрывшемся окне выбрать установленный модуль.</li>
</ol><?
endif;?>
