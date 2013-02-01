<?php
/**
 * CSVI Form helper
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: panel.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$jinput = JFactory::getApplication()->input;
// Check if we are running cron, no need to show the panel
if (!$jinput->get('cron', false, 'bool')) {
	
	$helper = new CsviHelper();
	$buttons = $helper->getButtons();
	// Create the top slider
	$topmenu = '<div id="panel-container"><div id="slide-menu">
					<div id="top-panel"><div id="main">
		<div id="cpanel">';
	$topmenu .= $buttons->process;
	$topmenu .= $buttons->replacements;
	$topmenu .= $buttons->log;
	$topmenu .= $buttons->maintenance;
	$topmenu .= $buttons->availablefields;
	$topmenu .= '</div></div></div>
					<div id="sub-panel">
						<a href="#" id="toggle"><span id="shText">'.JText::_('COM_CSVI_SHOW_PANEL').'</span></a>
					</div>
				</div></div>';
	$topmenu = preg_replace("/(\s\s+)/", ' ', $topmenu);
	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		// Lets make the top panel toggle based on the click of the show/hide link
		jQuery("#sub-panel").live('click', function(){
			if (jQuery("#top-panel").is(":visible") == true) {
				// Toggle the bar up
				jQuery("#top-panel").hide();
			}
			else {
				// Toggle the bar up
				jQuery("#top-panel").show();
			}
			// Settings
			var el = jQuery("#shText");
			// Lets us know whats inside the element
			var state = jQuery("#shText").html();
			// Change the state
			state = (state == '<?php echo JText::_('COM_CSVI_SHOW_PANEL'); ?>' ? '<span id="shText"><?php echo JText::_('COM_CSVI_HIDE_PANEL'); ?></span>' : '<span id="shText"><?php echo JText::_('COM_CSVI_SHOW_PANEL'); ?></span>');
			// Finally change whats insdide the element ID
			el.replaceWith(state);
		}); // end sub panel click function
	
		// Add the slider to the top of the page
		jQuery('#border-top').prepend('<?php echo $topmenu; ?>');
	
	}); // end on DOM
	</script>
	<style type="text/css">
	#panel-container {
		position:relative;
		width: 655px;
		height:auto;
	}
	
	#slide-menu,#top-panel {
		width: 655px;
	
	
	}
	
	#slide-menu {
		position: fixed;
		margin: 0 0 0 -251px;
		left: 50%;
		z-index: 1000;
	}
	
	#top-panel {
		height: 125px;
		display: none;
		background: url(components/com_csvi/assets/images/panel_bg.png) bottom
		left no-repeat;
		padding-top: 14px;
		padding-left: 33px;
		
	}
	
	#sub-panel {
		text-align: center;
		position:absolute;
		bottom: -25px;
		right: 50px;
	}
	
	#sub-panel a {
	
		float: right;
		text-decoration: none;
		margin-right: 35px;
		font-weight: bold;
		width: 109px;
		height: 30px;
		line-height: 27px;
		background: url(components/com_csvi/assets/images/label_open.png) top left no-repeat; color:#333;
		text-align:center;
		color: #3d728e;
	
	}
	
	#sub-panel a:hover {
	
		background: url(components/com_csvi/assets/images/label_open_hover.png) top left no-repeat;
	}
	
	#sub-panel a span {
		padding-right: 8px;
		display: block;
		font-size: 11px;
		text-transform:uppercase;
	}
	
	#sub-panel_close {
		text-align: center;
		position:absolute;
		bottom: -25px;
		right: 50px;
	}
	
	#sub-panel_close a {
	
		float: right;
		text-decoration: none;
		margin-right: 35px;
		font-weight: bold;
		width: 109px;
		height: 30px;
		line-height: 27px;
		background: url(components/com_csvi/assets/images/label_close.png) top left no-repeat; color:#333;
		text-align:center;
		color:#3d728e;
	
	}
	
	#sub-panel_close a:hover {
	
		background: url(components/com_csvi/assets/images/label_close_hover.png) top left no-repeat;
	}
	
	#sub-panel_close a span {
		padding-right: 8px;
		display: block;
		font-size: 11px;
		text-transform:uppercase;
	}
	
	
	.face {
		border: solid 2px #a6c34e;
		margin-left: 10px;
		float: right;
	}
	
	:focus {
		outline: 0
	}
	</style>
<?php } ?>