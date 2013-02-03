<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>   <div>
  <?php echo $this->tabs->startPane( 'mail_tab');?>
  	<?php echo $this->tabs->startPanel(JText::_('INFOS'),'mail_infos');?>
  		<br style="font-size:1px"/>
	  	<table class="paramlist admintable" width="100%">
		<tr>
			<td class="paramlist_key">
				<label for="subject">
					<?php echo JText::_( 'JOOMEXT_SUBJECT' ); ?>
				</label>
			</td>
			<td>
				<input type="text" name="data[mail][subject]" id="subject" class="inputbox" style="width:80%" value="<?php echo $this->escape(@$this->mail->subject); ?>" />
			</td>
		</tr>
		<tr>
			<td class="paramlist_key">
				<?php echo JText::_( 'SEND_HTML' ); ?>
			</td>
			<td>
				<?php echo JHTML::_('acyselect.booleanlist', "data[mail][html]" , 'onchange="updateAcyEditor(this.value)"',$this->mail->html); ?>
			</td>
		</tr>
	</table>
  	<?php echo $this->tabs->endPanel(); ?>
 	<?php echo $this->tabs->startPanel(JText::_( 'ATTACHMENTS' ), 'mail_attachments');?>
 	<br style="font-size:1px"/>
    <?php if(!empty($this->mail->attach)){?>
		<fieldset class="adminform">
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
		<table width="100%" class="paramlist admintable">
			<tr>
		    	<td class="paramlist_key">
		    		<?php echo JText::_( 'FROM_NAME' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" id="fromname" name="data[mail][fromname]" style="width:200px" value="<?php echo $this->escape($this->mail->fromname); ?>" />
		    	</td>
		    </tr>
			<tr>
		    	<td class="paramlist_key">
		    		<?php echo JText::_( 'FROM_ADDRESS' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" id="fromemail" name="data[mail][fromemail]" style="width:200px" value="<?php echo $this->escape($this->mail->fromemail); ?>" />
		    	</td>
		    </tr>
		    <tr>
				<td class="paramlist_key">
					<?php echo JText::_( 'REPLYTO_NAME' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" id="replyname" name="data[mail][replyname]" style="width:200px" value="<?php echo $this->escape($this->mail->replyname); ?>" />
		    	</td>
		    </tr>
		    <tr>
				<td class="paramlist_key">
					<?php echo JText::_( 'REPLYTO_ADDRESS' ); ?>
		    	</td>
		    	<td class="paramlist_value">
		    		<input class="inputbox" type="text" id="replyemail" name="data[mail][replyemail]" style="width:200px" value="<?php echo $this->escape($this->mail->replyemail); ?>" />
		    	</td>
			</tr>
		</table>

<?php echo $this->tabs->endPanel(); echo $this->tabs->endPane(); ?>
  </div>
