<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>	 <div id="newsletterparams">
	<?php echo $this->tabs->startPane( 'mail_tab');?>
		<?php echo $this->tabs->startPanel(JText::_( 'LISTS' ), 'mail_receivers');?>
		<br style="font-size:1px"/>
		<?php if(empty($this->lists)){
				echo JText::_('LIST_CREATE');
			}else{
				echo JText::_('LIST_RECEIVERS');
		?>
		<table id="receiverstable" class="adminlist table table-striped table-hover" cellpadding="1" width="100%">
			<thead>
				<tr>
					<th class="title">
						<?php echo JText::_('LIST_NAME'); ?>
					</th>
					<th class="title">
						<?php echo JText::_('LIST_RECEIVE'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
		<?php
				$k = 0;
				$filter_list = JRequest::getInt( 'filter_list');
				if(empty($filter_list)) $filter_list=JRequest::getInt('listid');
				$i = 0;
				$selectedLists = explode(',',JRequest::getString('listids'));
				foreach($this->lists as $row){
		?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo '<div class="roundsubscrib rounddisp" style="background-color:'.$row->color.'"></div>'; ?>
						<?php
						$text = '<b>'.JText::_('ACY_ID').' : </b>'.$row->listid;
						$text .= '<br/>'.$row->description;
						echo acymailing_tooltip($text, $row->name, 'tooltip.png', $row->name);
						?>
					</td>
					<td align="center" nowrap="nowrap" style="text-align:center">
						<?php echo JHTML::_('acyselect.booleanlist', "data[listmail][".$row->listid."]" , '',(bool) ($row->mailid OR (empty($row->mailid) AND empty($this->mail->mailid) AND $filter_list == $row->listid) OR (empty($this->mail->mailid) AND count($this->lists) == 1) OR (in_array($row->listid,$selectedLists))),JText::_('JOOMEXT_YES'),JText::_('JOOMEXT_NO'),$row->listid.'listmail'); ?>
					</td>
				</tr>
		<?php
					$k = 1-$k;
					$i++;
				}
				if(count($this->lists)>3){
					$languages = array();
			?>
			<tr><td></td><td align="center" nowrap="nowrap" style="text-align:center">
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
										echo 'jQuery("label[for="+selectedLists["all"][i]+"listmail0]").click();';
									}
									?>
									window.document.getElementById(selectedLists['all'][i]+'listmail0').checked = true;
								}
								if(!selectedLists[selection]) return;
								for(var i=0; i < selectedLists[selection].length; i++)
								{
									<?php
									if(ACYMAILING_J30){
										echo 'jQuery("label[for="+selectedLists[selection][i]+"listmail1]").click();';
									}
									?>
									window.document.getElementById(selectedLists[selection][i]+'listmail1').checked = true;
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
		<?php if(acymailing_level(2) && acymailing_isAllowed($this->config->get('acl_lists_filter','all'))) include_once(dirname(__FILE__).DS.'filters.php'); ?>

		<?php } echo $this->tabs->endPanel(); ?>
 	<?php echo $this->tabs->startPanel(JText::_( 'ATTACHMENTS' ), 'mail_attachments');?>
		<br style="font-size:1px"/>
		<?php if(!empty($this->mail->attach)){?>
		<fieldset class="adminform" id="attachmentfieldset">
		<legend><?php echo JText::_( 'ATTACHED_FILES' ); ?></legend>
			<?php
					foreach($this->mail->attach as $idAttach => $oneAttach){
						$idDiv = 'attach_'.$idAttach;
						echo '<div id="'.$idDiv.'">'.$oneAttach->filename.' ('.(round($oneAttach->size/1000,1)).' Ko)';
						echo $this->toggleClass->delete($idDiv,$this->mail->mailid.'_'.$idAttach,'mail');
				echo '</div>';
					}
		?>
		</fieldset>
		<?php } ?>
		<div id="loadfile">
			<input type="file" style="width:160px" name="attachments[]" />
		</div>
		<a href="javascript:void(0);" onclick='addFileLoader()'><?php echo JText::_('ADD_ATTACHMENT'); ?></a>
			<?php echo JText::sprintf('MAX_UPLOAD',$this->values->maxupload);?>
		<?php echo $this->tabs->endPanel(); echo $this->tabs->startPanel(JText::_( 'SENDER_INFORMATIONS' ), 'mail_sender');?>
		<br style="font-size:1px"/>
		<table width="100%" class="paramlist admintable" id="senderinformationfieldset">
			<tr>
					<td class="paramlist_key">
						<label for="fromname"><?php echo JText::_( 'FROM_NAME' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input class="inputbox" id="fromname" type="text" name="data[mail][fromname]" style="width:200px" value="<?php echo $this->escape(@$this->mail->fromname); ?>" />
					</td>
				</tr>
			<tr>
					<td class="paramlist_key">
						<label for="fromemail"><?php echo JText::_( 'FROM_ADDRESS' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input class="inputbox" id="fromemail" type="text" name="data[mail][fromemail]" style="width:200px" value="<?php echo $this->escape(@$this->mail->fromemail); ?>" />
					</td>
				</tr>
				<tr>
				<td class="paramlist_key">
					<label for="replyname"><?php echo JText::_( 'REPLYTO_NAME' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input class="inputbox" id="replyname" type="text" name="data[mail][replyname]" style="width:200px" value="<?php echo $this->escape(@$this->mail->replyname); ?>" />
					</td>
				</tr>
				<tr>
				<td class="paramlist_key">
					<label for="replyemail"><?php echo JText::_( 'REPLYTO_ADDRESS' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input class="inputbox" id="replyemail" type="text" name="data[mail][replyemail]" style="width:200px" value="<?php echo $this->escape(@$this->mail->replyemail); ?>" />
					</td>
			</tr>
		</table>

<?php echo $this->tabs->endPanel();
		echo $this->tabs->startPanel(JText::_( 'META_DATA' ), 'mail_metadata');?>
		<br style="font-size:1px"/>
		<table width="100%" class="paramlist admintable" id="metadatatable">
			<tr>
					<td class="paramlist_key">
						<label for="metakey"><?php echo JText::_( 'META_KEYWORDS' ); ?></label>
					</td>
					<td class="paramlist_value">
						<textarea id="metakey" name="data[mail][metakey]" rows="5" cols="30" ><?php echo @$this->mail->metakey; ?></textarea>
					</td>
				</tr>
			<tr>
					<td class="paramlist_key">
						<label for="metadesc"><?php echo JText::_( 'META_DESC' ); ?></label>
					</td>
					<td class="paramlist_value">
						<textarea id="metadesc" name="data[mail][metadesc]" rows="5" cols="30" ><?php echo @$this->mail->metadesc; ?></textarea>
					</td>
				</tr>
		</table>
<?php
	echo $this->tabs->endPanel();
	echo $this->tabs->endPane(); ?>
	</div>
