<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><style type="text/css">
#acymailingcpanel div.icon {
	border:1px solid #F0F0F0;
	color:#666666;
	display:block;
	float:left;
	text-decoration:none;
	vertical-align:middle;
	width:100%;
	float:left;
	margin-bottom:5px;
	margin-right:5px;
	text-align:center;
	width: 100%;
}

#acymailingcpanel ul{
	padding-left:10px;
}

#acymailingcpanel div.icon:hover {
	-moz-background-clip:border;
	-moz-background-inline-policy:continuous;
	-moz-background-origin:padding;
	background:#F9F9F9 none repeat scroll 0 0;
	border-color:#EEEEEE #CCCCCC #CCCCCC #EEEEEE;
	border-style:solid;
	border-width:1px;
	color:#0B55C4;
	cursor:pointer;
}

#acymailingcpanel span {
	display:block;
	text-align:center;
}

#acymailingcpanel img {
	margin:0 auto;
	padding:10px 0;
}

</style>
<div id="acy_content" >
	<div id="iframedoc"></div>
<table class="adminform" width="100%">
	<tr>
		<td width="45%" valign="top">
			<div id="acymailingcpanel">
				<?php
					foreach($this->buttons as $oneButton){
						echo $oneButton;
					}
					?>
			</div>
		</td>
		<td valign="top">
			<?php
			if(acymailing_isAllowed($this->config->get('acl_subscriber_manage','all'))){
				include(dirname(__FILE__).DS.'userstats.php');
			}

			if(acymailing_isAllowed($this->config->get('acl_subscriber_manage','all')) || acymailing_isAllowed($this->config->get('acl_statistics_manage','all'))){
				if(strpos(JURI::root(),'https:') === false){
					echo '<div style="float:right;"><a style="border:0px;text-decoration:none;" href="'.ACYMAILING_REDIRECT.'update-acymailing-'.$this->config->get('level').'" title="Your version is not up to date... click here to download the latest version, you won\'t loose data during the update." target="_blank"><img src="'.ACYMAILING_UPDATEURL.'check&version='.$this->config->get('version').'&level='.$this->config->get('level').'&component=acymailing" /></a></div>';
				}

				echo $this->tabs->startPane( 'dash_tab');

				if(acymailing_isAllowed($this->config->get('acl_subscriber_manage','all'))){
					echo $this->tabs->startPanel( JText::_( 'USERS' ), 'dash_users');
					include(dirname(__FILE__).DS.'users.php');
					echo $this->tabs->endPanel();
				}

				if(acymailing_isAllowed($this->config->get('acl_statistics_manage','all'))){
					echo $this->tabs->startPanel( JText::_( 'STATISTICS' ), 'dash_stats');
					include(dirname(__FILE__).DS.'stats.php');
					echo $this->tabs->endPanel();
				}

				echo $this->tabs->endPane();
			}
			?>
		</td>
	</tr>
</table>
</div>
