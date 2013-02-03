<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acymodifyform">
<?php if($this->values->show_page_heading){ ?>
<h1 class="contentheading<?php echo $this->values->suffix; ?>"><?php echo $this->values->page_heading; ?></h1>
<?php } ?>
<?php if(!empty($this->introtext)){ echo '<span class="acymailing_introtext">'.$this->introtext.'</span>'; } ?>
<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm" id="adminForm" <?php if(!empty($this->fieldsClass->formoption)) echo $this->fieldsClass->formoption; ?> >
	<fieldset class="adminform">
		<legend><span><?php echo JText::_( 'USER_INFORMATIONS' ); ?></span></legend>
		<table cellspacing="1" align="center" width="100%" id="acyuserinfo">
		<?php if(acymailing_level(3)){
				foreach($this->extraFields as $fieldName => $oneExtraField) {
					echo '<tr id="tr'.$fieldName.'"><td width="150" class="key">'.$this->fieldsClass->getFieldName($oneExtraField).'</td><td>';
					if(in_array($fieldName,array('name','email')) AND !empty($this->subscriber->userid)){echo $this->subscriber->$fieldName; }
					else{echo $this->fieldsClass->display($oneExtraField,@$this->subscriber->$fieldName,'data[subscriber]['.$fieldName.']'); }
					echo '</td></tr>';
				}
			}else{ ?>
			<tr id="trname">
				<td width="150" class="key">
					<label for="field_name">
					<?php echo JText::_( 'JOOMEXT_NAME' ); ?>
					</label>
				</td>
				<td>
				<?php
				if(empty($this->subscriber->userid)){
						echo '<input type="text" name="data[subscriber][name]" id="field_name" class="inputbox" style="width:200px;" value="'.$this->escape(@$this->subscriber->name).'" />';
				}else{
					echo $this->subscriber->name;
				}
				?>
				</td>
			</tr>
			<tr id="tremail">
				<td class="key">
					<label for="field_email">
					<?php echo JText::_( 'JOOMEXT_EMAIL' ); ?>
					</label>
				</td>
				<td>
					<?php
					if(empty($this->subscriber->userid)){
						echo '<input class="inputbox" type="text" name="data[subscriber][email]" id="field_email" style="width:200px;" value="'.$this->escape(@$this->subscriber->email).'" />';
					}else{
						echo $this->subscriber->email;
					}
					?>
				</td>
			</tr>
			<tr id="trhtml">
				<td class="key">
					<?php echo JText::_( 'RECEIVE' ); ?>
				</td>
				<td>
					<?php echo JHTML::_('acyselect.booleanlist', "data[subscriber][html]" , '',$this->subscriber->html,JText::_('HTML'),JText::_('JOOMEXT_TEXT'),'user_html'); ?>
				</td>
			</tr>
		<?php }
			?>
		</table>
	</fieldset>
	<?php if($this->displayLists){?>
	<fieldset class="adminform">
		<legend><span><?php echo JText::_( 'SUBSCRIPTION' ); ?></span></legend>
		<table cellspacing="1" align="center" width="100%" id="acyusersubscription">
			<thead>
				<tr>
					<th  nowrap="nowrap" align="center" width="150">
					<?php echo JText::_( 'SUBSCRIBE' );?>
					</th>
					<th  nowrap="nowrap" align="center">
					<?php echo JText::_( 'LIST' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$k = 0;
				foreach($this->subscription as $row){
					if(empty($row->published) OR !$row->visible) continue;
					?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center" valign="top" class="acystatus">
						<span><?php echo $this->status->display("data[listsub][".$row->listid."][status]",@$row->status); ?></span>
					</td>
					<td valign="top">
						<div class="list_name"><?php echo $row->name ?></div>
						<div class="list_description"><?php echo $row->description ?></div>
					</td>
				</tr>
				<?php
					$k = 1 - $k;
				} ?>
			</tbody>
		</table>
	</fieldset>
	<?php } ?>
	<br/>
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="savechanges" />
	<input type="hidden" name="ctrl" value="user" />
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="subid" value="<?php echo $this->subscriber->subid; ?>" />
	<?php if(JRequest::getCmd('tmpl') == 'component'){ ?><input type="hidden" name="tmpl" value="component" /><?php } ?>
	<input type="hidden" name="key" value="<?php echo $this->subscriber->key; ?>" />
	<p class="acymodifybutton">
		<input class="button btn btn-primary" type="submit" onclick="return checkChangeForm();" value="<?php echo empty($this->subscriber->subid) ? JText::_('SUBSCRIBE',true) : JText::_('SAVE_CHANGES',true)?>"/>
	</p>
</form>
<?php if(!empty($this->finaltext)){ echo '<span class="acymailing_finaltext">'.$this->finaltext.'</span>'; } ?>
</div>
