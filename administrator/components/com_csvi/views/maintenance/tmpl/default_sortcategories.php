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
 * @version 	$Id: default_icecat.php 1760 2012-01-02 19:50:19Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<div class="width-80 fltlft">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_CSVI_SORTCATEGORIES_LABEL'); ?></legend>
<ul>
	<!-- ICEcat Location -->
	<li>
		<label class="hasTip" title="<?php echo JText::_('COM_CSVI_LANGUAGE_LABEL'); ?> :: <?php echo JText::_('COM_CSVI_LANGUAGE_DESC'); ?>"><?php echo JText::_('COM_CSVI_LANGUAGE_LABEL'); ?></label>
			<?php echo JHtml::_('select.genericlist', $this->languages, 'language'); ?>
	</li>
</ul>
</fieldset>
</div>
<div class="clr"></div>