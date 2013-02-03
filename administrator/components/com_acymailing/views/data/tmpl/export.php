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
<?php if(JRequest::getString('tmpl') == 'component'){
?>
<fieldset>
	<div class="acyheader icon-48-acyexport" style="float: left;"><?php echo JText::_('ACY_EXPORT'); ?></div>
	<div class="toolbar" id="toolbar" style="float: right;">
		<a onclick="javascript:submitbutton('doexport')" href="#" ><span class="icon-32-acyexport" title="<?php echo JText::_('ACY_EXPORT',true); ?>"></span><?php echo JText::_('ACY_EXPORT'); ?></a>
	</div>
</fieldset>
<?php } ?>
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm" id="adminForm" >
	<table class="table" width="100%">
		<tbody>
			<tr>
				<td valign="top" width="50%">
					<fieldset class="adminform">
					<legend><?php echo JText::_( 'FIELD_EXPORT' ); ?></legend>
						<table class="adminlist table table-striped" cellpadding="1">
<?php
	$k = 0;
	if(!empty($this->fields)) {
		foreach($this->fields as $fieldName => $fieldType){
?>
							<tr class="<?php echo "row$k"; ?>">
								<td>
									<?php echo $fieldName ?>
								</td>
								<td align="center">
									<?php echo JHTML::_('acyselect.booleanlist', "exportdata[".$fieldName."]",'',in_array($fieldName,$this->selectedfields) ? 1 : 0); ?>
								</td>
							</tr>
<?php
			$k = 1-$k;
		}
	}
	if(!empty($this->otherfields)){

		foreach($this->otherfields as $fieldName){
?>
							<tr class="<?php echo "row$k"; ?>">
								<td>
									<?php echo $fieldName ?>
								</td>
								<td align="center">
									<?php echo JHTML::_('acyselect.booleanlist', "exportdataother[".$fieldName."]",'',in_array($fieldName,$this->selectedfields) ? 1 : 0); ?>
								</td>
							</tr>
<?php
			$k = 1-$k;
		}
	}

?>
							<tr class="<?php echo "row$k"; $k = 1-$k;?>">
								<td>
									<?php echo JText::_('EXPORT_FORMAT'); ?>
								</td>
								<td align="center">
									<?php echo $this->charset->display('exportformat',$this->config->get('export_format','UTF-8')); ?>
								</td>
							</tr>
							<tr class="<?php echo "row$k"; ?>">
								<td>
									<?php echo JText::_('ACY_SEPARATOR'); ?>
								</td>
								<td align="center">
<?php
	$values = array(
		JHTML::_('select.option', 'semicolon', JText::_('SEPARATOR_SEMICOLON')),
		JHTML::_('select.option', 'comma', JText::_('SEPARATOR_COMMA'))
	);
	$data = str_replace(array(';',','),array('semicolon','comma'), $this->config->get('export_separator',';'));
	if($data == 'colon') $data = 'comma';
	echo JHTML::_('acyselect.radiolist', $values, 'exportseparator', '', 'value', 'text', $data);
?>
								</td>
							</tr>
						</table>
					</fieldset>
					<?php if(empty($this->users)){ ?>
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'ACY_FILTERS' ); ?></legend>
						<table class="adminlist table table-striped" cellpadding="1">
							<tr class="row0">
								<td>
									<?php echo JText::_('EXPORT_SUB_LIST'); ?>
								</td>
								<td align="center">
									<?php echo JHTML::_('acyselect.booleanlist', "exportfilter[subscribed]",'onchange="if(this.value == 1){document.getElementById(\'exportlists\').style.display = \'block\'; }else{document.getElementById(\'exportlists\').style.display = \'none\'; }"',1,JText::_('Yes'),JText::_('No').' : '.JText::_('ALL_USERS')); ?>
								</td>
							</tr>
							<tr class="row1">
								<td>
									<?php echo JText::_('EXPORT_REGISTERED'); ?>
								</td>
								<td align="center">
									<?php echo JHTML::_('acyselect.booleanlist', "exportfilter[registered]",'',0,JText::_('JOOMEXT_YES'),JText::_('JOOMEXT_NO').' : '.JText::_('ALL_USERS')); ?>
								</td>
							</tr>
							<tr class="row0">
								<td>
									<?php echo JText::_('EXPORT_CONFIRMED'); ?>
								</td>
								<td align="center">
									<?php echo JHTML::_('acyselect.booleanlist', "exportfilter[confirmed]",'',0,JText::_('JOOMEXT_YES'),JText::_('JOOMEXT_NO').' : '.JText::_('ALL_USERS')); ?>
								</td>
							</tr>
							<tr class="row1">
								<td>
									<?php echo JText::_('EXPORT_ENABLED'); ?>
								</td>
								<td align="center">
									<?php echo JHTML::_('acyselect.booleanlist', "exportfilter[enabled]",'',0,JText::_('JOOMEXT_YES'),JText::_('JOOMEXT_NO').' : '.JText::_('ALL_USERS')); ?>
								</td>
							</tr>

						</table>
					</fieldset>
					<?php } ?>

				</td>
				<td valign="top">
					<fieldset class="adminform" id="exportlists">
					<?php if(empty($this->users)){ ?>
						<legend><?php echo JText::_( 'LISTS' ); ?></legend>
						<table class="adminlist table table-striped" cellpadding="1">
						<tbody>
						<?php
						$k = 0;
						foreach( $this->lists as $row){?>
							<tr class="<?php echo "row$k"; ?>">
								<td>
									<?php echo '<div class="roundsubscrib rounddisp" style="background-color:'.$row->color.'"></div>';
									$text = '<b>'.JText::_('ACY_ID').' : </b>'.$row->listid.'<br/>'.$row->description;
									echo acymailing_tooltip($text, $row->name, 'tooltip.png', $row->name);
									?>
								</td>
								<td align="center">
									<?php  echo JHTML::_('acyselect.booleanlist', "exportlists[".$row->listid."]",'',in_array($row->listid,$this->selectedlists) ? 1 : 0,JText::_('JOOMEXT_YES'),JText::_('JOOMEXT_NO'),'exportlists'.$row->listid); ?>
								</td>
							</tr>
							<?php
							$k = 1-$k;
						}

						if(count($this->lists)>3){
						$languages = array();
						?>
						<tr><td></td><td align="center" nowrap="nowrap">
									<script language="javascript" type="text/javascript">
										function updateStatus(selection){
											<?php foreach($this->lists as $row){
													$languages['all'][$row->listid] = $row->listid;
													if($row->languages == 'all') continue;
													$lang = explode(',',trim($row->languages,','));
													foreach($lang as $oneLang){
														$languages[strtolower($oneLang)][$row->listid] = $row->listid;
													}
											} ?>
											var selectedLists = new Array();
											<?php
											foreach($languages as $val => $listids){
												echo "selectedLists['$val'] = new Array('".implode("','",$listids)."'); ";
											}
											?>
											for(var i=0; i < selectedLists['all'].length; i++)
											{
												<?php
												if(ACYMAILING_J30){
													echo 'jQuery("label[for=exportlists"+selectedLists["all"][i]+"0]").click();';
												}
												?>
												window.document.getElementById('exportlists'+selectedLists['all'][i]+'0').checked = true;
											}
											if(!selectedLists[selection]) return;
											for(var i=0; i < selectedLists[selection].length; i++)
											{
												<?php
												if(ACYMAILING_J30){
													echo 'jQuery("label[for=exportlists"+selectedLists[selection][i]+"1]").click();';
												}
												?>
												window.document.getElementById('exportlists'+selectedLists[selection][i]+'1').checked = true;
											}
										}
									</script>
									<?php
										$selectList = array();
										$selectList[] = JHTML::_('select.option', 'none',JText::_('ACY_NONE'));
										foreach($languages as $oneLang => $values){
											if($oneLang == 'all') continue;
											$selectList[] = JHTML::_('select.option', $oneLang,ucfirst($oneLang));
										}
										$selectList[] = JHTML::_('select.option', 'all',JText::_('ACY_ALL'));
										echo JHTML::_('acyselect.radiolist', $selectList, "selectlists" , 'onclick="updateStatus(this.value);"', 'value', 'text');
									?>
								</td></tr>
						<?php } ?>
						</tbody>
						</table>
						<?php }else{ ?>
						<legend><?php echo JText::_( 'USERS' ); ?></legend>
						<table class="adminlist table table-striped" cellpadding="1">
						<?php
						$k = 0;
						foreach( $this->users as $row){?>
							<tr class="<?php echo "row$k"; ?>">
								<td><?php echo $row->name; ?></td>
								<td><?php echo $row->email; ?></td>
							</tr>
						<?php $k = 1-$k;}

						if(count($this->users) >= 10){?>
						<tr class="<?php echo "row$k"; ?>">
							<td>...</td><td>...</td>
						</tr>
						<?php } ?>
						</table>
						<?php } ?>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>" />
	<input type="hidden" name="sessionvalues" value="<?php echo empty($this->users) ? 0 : JRequest::getInt('sessionvalues'); ?>" />
	<input type="hidden" name="sessionquery" value="<?php echo empty($this->users) ? 0 : JRequest::getInt('sessionquery'); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
