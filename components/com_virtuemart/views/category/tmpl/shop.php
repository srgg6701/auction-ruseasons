<?php	
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6556 2012-10-17 18:15:30Z kkmediaproduction $
 */

//vmdebug('$this->category',$this->category);
vmdebug ('$this->category ' . $this->category->category_name);
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
JHTML::_ ('behavior.modal');
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";

$document = JFactory::getDocument ();
$document->addScriptDeclaration ($js);
/*$edit_link = '';
if(!class_exists('Permissions')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'permissions.php');
if (Permissions::getInstance()->check("admin,storeadmin")) {
	$edit_link = '<a href="'.JURI::root().'index.php?option=com_virtuemart&tmpl=component&view=category&task=edit&virtuemart_category_id='.$this->category->virtuemart_category_id.'">
		'.JHTML::_('image', 'images/M_images/edit.png', JText::_('COM_VIRTUEMART_PRODUCT_FORM_EDIT_PRODUCT'), array('width' => 16, 'height' => 16, 'border' => 0)).'</a>';
}

echo $edit_link; */
if (empty($this->keyword)) {
	?>
<div class="category_description">
	<?php echo $this->category->category_description; ?>
</div>
<?php
}?>
<h1>Все предметы магазина</h1>
<?
