<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
//\auction-ruseasons\components\com_auction2013\helpers\stuff.php
//\auction-ruseasons/administrator\components\com_auction2013\helpers\stuff.php
require_once JPATH_SITE.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'stuff.php';
// Load the view framework
if(!class_exists('VmView'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmview.php');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */

require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'helpers'.DS.'auction2013.php';

class VirtuemartViewOrders_shop extends VmView {

	function display($tpl = null) {
        include_once JPATH_SITE.DS.'tests.php';
        $this->purchases = new vmAuctionHTML();
        $this->loadHelper('html');
		$this->SetViewTitle( 'ORDER');
		$curTask = JRequest::getWord('task');
        $model = VmModel::getModel();
        $pagination = $model->getPagination();
        $this->assignRef('pagination', $pagination);
        parent::display($tpl);
	}
}

