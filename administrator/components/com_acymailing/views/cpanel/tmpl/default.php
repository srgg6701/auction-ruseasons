<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content" >
<div id="iframedoc"></div>
<form action="index.php" method="post" name="adminForm" autocomplete="off" id="adminForm" >
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="cpanel" />
	<?php echo JHTML::_( 'form.token' );

		if(strpos(JURI::root(),'https:') === false){
			echo '<div style="float:right;"><a style="border:0px;text-decoration:none;" href="'.ACYMAILING_REDIRECT.'update-acymailing-'.$this->config->get('level').'" title="Your version is not up to date... click here to download the latest version, you won\'t loose data during the update." target="_blank"><img src="'.ACYMAILING_UPDATEURL.'check&version='.$this->config->get('version').'&level='.$this->config->get('level').'&component=acymailing" /></a></div>';
		}

		echo $this->tabs->startPane('config_tab');

		echo $this->tabs->startPanel( JText::_( 'MAIL_CONFIG' ), 'config_mail');
		include(dirname(__FILE__).DS.'mail.php');
		echo $this->tabs->endPanel();

		echo $this->tabs->startPanel( JText::_( 'QUEUE_PROCESS' ), 'config_queue');
		include(dirname(__FILE__).DS.'queue.php');
		echo $this->tabs->endPanel();

		echo $this->tabs->startPanel( JText::_( 'SUBSCRIPTION' ), 'config_subscription');
		include(dirname(__FILE__).DS.'subscription.php');
		echo $this->tabs->endPanel();

		echo $this->tabs->startPanel( JText::_( 'INTERFACE' ), 'config_interface');
		include(dirname(__FILE__).DS.'interface.php');
		echo $this->tabs->endPanel();

		echo $this->tabs->startPanel( JText::_( 'SECURITY' ), 'config_security');
		include(dirname(__FILE__).DS.'security.php');
		echo $this->tabs->endPanel();

		if(file_exists(dirname(__FILE__).DS.'others.php')){
			echo $this->tabs->startPanel( JText::_( 'OTHERS' ), 'config_others');
			include(dirname(__FILE__).DS.'others.php');
			echo $this->tabs->endPanel();
		}
		if(acymailing_level(3)){
			echo $this->tabs->startPanel( JText::_( 'ACCESS_LEVEL' ), 'config_acl');
			include(dirname(__FILE__).DS.'acl.php');
			echo $this->tabs->endPanel();
		}

		echo $this->tabs->startPanel( JText::_( 'PLUGINS' ), 'config_plugins');
		include(dirname(__FILE__).DS.'plugins.php');
		echo $this->tabs->endPanel();

		echo $this->tabs->startPanel( JText::_( 'LANGUAGES' ), 'config_languages');
		include(dirname(__FILE__).DS.'languages.php');
		echo $this->tabs->endPanel();

		echo $this->tabs->endPane();
	?>

	<div class="clr"></div>

</form>
</div>
