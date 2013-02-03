<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div class="acymailing_module<?php echo $params->get('moduleclass_sfx')?>" id="acymailing_module_<?php echo $formName; ?>">
<?php
	if(!empty($mootoolsIntro)) echo '<p class="acymailing_mootoolsintro">'.$mootoolsIntro.'</p>'; ?>
	<div class="acymailing_mootoolsbutton">
		<?php
		 	$link = "rel=\"{handler: 'iframe', size: {x: ".$params->get('boxwidth',250).", y: ".$params->get('boxheight',200)."}}\" class=\"modal acymailing_togglemodule\"";
		 	$href=acymailing_completeLink('sub&task=display&formid='.$module->id,true);
		?>
		<p><a <?php echo $link; ?> id="acymailing_togglemodule_<?php echo $formName; ?>" href="<?php echo $href;?>"><?php echo $mootoolsButton ?></a></p>
	</div>
</div>
