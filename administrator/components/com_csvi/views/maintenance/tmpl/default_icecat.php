<?php
/**
 * Maintenance page
 *
 * @package 	CSVI
 * @subpackage 	Maintenance
 * @todo
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default_icecat.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<div class="width-80 fltlft">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_CSVI_MAINTENANCE_ICECAT'); ?></legend>
<ul>
	<!-- ICEcat Location -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_ICECAT_LOCATION_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_ICECAT_LOCATION_DESC'); ?>"><?php echo JText::_('COM_CSVI_ICECAT_LOCATION_LABEL'); ?></label>
			<input type="text" name="icecatlocation" value="<?php echo CSVIPATH_TMP; ?>" size="75" />
	</li>
	<!-- ICEcat use GZIP -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_ICECAT_GZIP_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_ICECAT_GZIP_DESC'); ?>"><?php echo JText::_('COM_CSVI_ICECAT_GZIP_LABEL'); ?></label>
		<fieldset class="radio" id="jform_icecat_gzip">
		<?php echo JHtml::_('select.booleanlist', 'icecat_gzip', '', 1); ?>
		</fieldset>
	</li>
</ul>
</fieldset>
</div>
<div class="clr"></div>
<div class="width-80 fltlft">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_CSVI_MAINTENANCE_ICECAT_FILE'); ?></legend>
<ul>
	<!-- ICEcat Index -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_ICECAT_FILE_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_ICECAT_FILE_DESC'); ?>"><?php echo JText::_('COM_CSVI_ICECAT_FILE_LABEL'); ?></label>
		<input type="checkbox" name="icecat[]" value="icecat_index" checked="checked" />
	</li>
	<!-- ICEcat import type -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_DESC'); ?>"><?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_LABEL'); ?></label>
		<fieldset class="radio" id="jform_loadtype">
			<?php echo JHtml::_('select.booleanlist', 'loadtype', '', 0, JText::_('COM_CSVI_ICECAT_FILE_SINGLE'), JText::_('COM_CSVI_ICECAT_FILE_FULL')); ?>
		</fieldset>
	</li>
	<!-- ICEcat number of records per run -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_RECORDS_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_RECORDS_DESC'); ?>"><?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_RECORDS_LABEL'); ?></label>
		<input type="text" name="icecat_records" value="1000" />
	</li>
	<!-- ICEcat wait time -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_WAIT_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_WAIT_DESC'); ?>"><?php echo JText::_('COM_CSVI_ICECAT_FILE_LOAD_WAIT_LABEL'); ?></label>
		<input type="text" name="icecat_wait" value="5" />
	</li>
</ul>
</fieldset>
</div>
<div class="clr"></div>
<div class="width-80 fltlft">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_CSVI_MAINTENANCE_ICECAT_SUPPLIER'); ?></legend>
<ul>
	<!-- ICEcat Manufacturer -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_ICECAT_SUPPLIER_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_ICECAT_SUPPLIER_DESC'); ?>"><?php echo JText::_('COM_CSVI_ICECAT_SUPPLIER_LABEL'); ?></label>
		<input type="checkbox" name="icecat[]" value="icecat_supplier" checked="checked" />
	</li>
</ul>
</fieldset>
</div>
<div class="clr"></div>