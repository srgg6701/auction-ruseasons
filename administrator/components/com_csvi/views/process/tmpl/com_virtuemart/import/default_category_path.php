<?php
/**
 * Import path options
 *
 * @package 	CSVI
 * @subpackage 	Export
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_category_path.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

?>
<fieldset>
	<legend><?php echo JText::_('COM_CSVI_IMPORT_PATH_OPTIONS'); ?></legend>
	<ul>
		<li><div class="option_label"><?php echo $this->form->getLabel('file_location_category_images', 'path'); ?></div>
			<div class="option_value"><?php echo $this->form->getInput('file_location_category_images', 'path'); ?></div>
			<div><?php echo sprintf(JText::_('COM_CSVI_SUGGESTED_PATH'), '<span id="pathsuggest_category_images">'.$this->config->get('media_category_path').'</span>');?> |
			<a href="#" onclick="document.getElementById('jform_path_file_location_category_images').value=document.getElementById('pathsuggest_category_images').innerHTML; return false;"><?php echo JText::_('COM_CSVI_PASTE');?></a> |
			<a href="#" onclick="document.getElementById('jform_path_file_location_category_images').value=''; return false;"><?php echo JText::_('COM_CSVI_CLEAR');?></a>
			</div></li>
	</ul>
</fieldset>
