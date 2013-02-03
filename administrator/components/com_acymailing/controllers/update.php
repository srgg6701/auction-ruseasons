<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class UpdateController extends acymailingController{

	function __construct($config = array()){
		parent::__construct($config);
		$this->registerDefaultTask('update');
	}

	function listing(){
		return $this->update();
	}

	function install(){
		acymailing_increasePerf();

		$newConfig = new stdClass();
		$newConfig->installcomplete = 1;
		$config = acymailing_config();

		if(!$config->save($newConfig)){
			$db= JFactory::getDBO();
			echo '<h2>The installation failed, some tables are missing, we will try to create them now...</h2>';

			$queries = file_get_contents(ACYMAILING_BACK.'tables.sql');
			$queriesTable = explode("CREATE TABLE",$queries);

			$success = true;
			foreach($queriesTable as $oneQuery){
				$oneQuery = trim($oneQuery);
				if(empty($oneQuery)) continue;
				$db->setQuery("CREATE TABLE ".$oneQuery);
				if(!$db->query()){
					echo '<br/><br/><span style="color:red">Error creating table : '.$db->getErrorMsg().'</span><br/>';
					$success = false;
				}else{
					echo '<br/><span style="color:green">Table successfully created</span>';
				}
			}

			if($success){
				echo '<h2>Please install again AcyMailing via the Joomla Extensions manager, the tables are now created so the installation will work</h2>';
			}else{
				echo '<h2>Some tables could not be created, please fix the above issues and then install again AcyMailing.</h2>';
			}
			return;
		}


		$updateHelper = acymailing_get('helper.update');
		$updateHelper->initList();
		$updateHelper->installTemplates();
		$updateHelper->installNotifications();
		$updateHelper->installMenu();
		$updateHelper->installExtensions();
		$updateHelper->installBounceRules();
		$updateHelper->addUpdateSite();
		$updateHelper->fixMenu();

		acymailing_setTitle('AcyMailing','acymailing','dashboard');

		$this->_iframe(ACYMAILING_UPDATEURL.'install');

	}

	function update(){

		$config = acymailing_config();
		if(!acymailing_isAllowed($config->get('acl_config_manage','all'))){
			acymailing_display(JText::_('ACY_NOTALLOWED'),'error');
			return false;
		}

		acymailing_setTitle(JText::_('UPDATE_ABOUT'),'acyupdate','update');

		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Link', 'cancel', JText::_('ACY_CLOSE'), acymailing_completeLink('dashboard') );

		return $this->_iframe(ACYMAILING_UPDATEURL.'update');
	}

	function _iframe($url){

		$config = acymailing_config();
		$url .= '&version='.$config->get('version').'&level='.$config->get('level').'&component=acymailing';
?>
				<div id="acymailing_div">
						<iframe allowtransparency="true" scrolling="auto" height="455px" frameborder="0" width="100%" name="acymailing_frame" id="acymailing_frame" src="<?php echo $url; ?>">
						</iframe>
				</div>
<?php
	}
}
