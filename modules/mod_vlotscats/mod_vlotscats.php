<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
require_once "helper.php";
require_once JPATH_SITE . DS . 'components' . DS . 'com_auction2013' . DS . 'helpers' . DS . 'stuff.php';
// ��������� ����� Helper (����������� JModuleHelper)
// �������� ���� � ����� ��� ���������� �������� (�� ��������� - tmpl/default.php)
// $params - object(JRegistry)

require modVlotscatsHelper::getLayoutPath('mod_vlotscats', $params->get('layout', 'default'));
if (JRequest::getVar('help')):
    ?>
    <ol>
        <li>���������� ����� ������ � ���������� <strong>/modules/</strong></li>
        <li>����� � ������ Extension <strong>Manager: Discover</strong></li>
        <li>ٸ������ <strong>Discover</strong></li>
        <li>���� ������ ���������, �������� <strong>Install</strong></li>
        <li>����� � ������ <strong>Module Manager: Modules</strong></li>
        <li>ٸ������ <strong>New</strong></li>
        <li>� ������������ ���� ������� ������������� ������.</li>
    </ol>
    <?php
 endif;
