<?php
/**
 * Default page for CSVI
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>

<div id="main">
	<div id="cpanel">
		<?php echo $this->cpanel_images->process; ?>
		<?php echo $this->cpanel_images->replacements; ?>
		<?php echo $this->cpanel_images->log; ?>
		<?php echo $this->cpanel_images->maintenance; ?>
		<br class="clear" />
		<?php echo $this->cpanel_images->availablefields; ?>
		<?php echo $this->cpanel_images->settings; ?>
		<?php echo $this->cpanel_images->about; ?>
		<?php echo $this->cpanel_images->help; ?>
		<?php echo $this->cpanel_images->install; ?>
		<br class="clear" />
		<?php echo LiveUpdate::getIcon(); ?>
	</div>
</div>
