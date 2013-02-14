<?php
/**
*
* Lists all the categories in the shop
*
* @package	VirtueMart
* @subpackage Category
* @author RickG, jseros, RolandD, Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 6477 2012-09-24 14:33:54Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');?>
<h3>Внимание! &nbsp; &nbsp; <span style="font-weight:300;">Импортируемый файл должен быть в формате <b>.csv</b></span></h3>

<form action="<?php echo JRoute::_('index.php?option=com_auction2013'); ?>" method="post" name="adminForm" id="adminForm">

Выберите файл для импорта данных:
	<input name="import_file" type="file">
    		<input type="hidden" name="task" value="importlots.import" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
        <button type="submit">Импортировать!</button>
</form>
