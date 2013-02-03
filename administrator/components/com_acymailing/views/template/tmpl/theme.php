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
div.templatearea{
	float:left;
	border:1px solid #ccc;
	margin:5px;
	padding:5px;
	cursor:pointer;
	height:230px;
	width:205px;
}

div.templatearea:hover{
	background-color : #FFFFDD;
	border :2px solid #ccc;
	margin : 4px;
}
div.templatetitle{
	font-size:11pt;
	background-color:#eeeeee;
	color:#000066;
	padding:2px;
	font-style:italic;
	margin-bottom:5px;
}

div.templatedescription{
	text-align:center;
}
</style>
<form action="index.php?tmpl=component&amp;option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm" id="adminForm" >
<?php if($this->pageInfo->elements->total > $this->pageInfo->elements->page){ ?>
	<table class="adminlist table table-striped table-hover" cellpadding="1">
		<thead>
			<tr>
				<td style="text-align:center;">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</thead>
	</table>
<?php }
if(empty($this->pageInfo->limit->start)){?>
			<div class="templatearea emptytemplate" onclick="applyTemplate(0);">
					<div class="templatetitle"><?php echo JText::_('ACY_NONE'); ?></div>
					<div style="display:none" id="stylesheet_0"></div>
					<div style="display:none" id="htmlcontent_0"><br/></div>
					<div style="display:none" id="textcontent_0"></div>
					<div style="display:none" id="subject_0"></div>
					<div style="display:none" id="replyname_0"></div>
					<div style="display:none" id="replyemail_0"></div>
					<div style="display:none" id="fromname_0"></div>
					<div style="display:none" id="fromemail_0"></div>
			</div>
			<?php
}
				for($i = 0,$a = count($this->rows);$i<$a;$i++){
					$row =& $this->rows[$i];
			?>
				<div class="templatearea" onclick="applyTemplate(<?php echo $row->tempid?>);">
						<div class="templatetitle"><?php echo $row->name; ?></div>
						<div class="templatedescription"><?php echo acymailing_absoluteURL($row->description);	?></div>
						<div style="display:none" id="stylesheet_<?php echo $row->tempid;?>"><?php echo $row->stylesheet;?></div>
						<div style="display:none" id="htmlcontent_<?php echo $row->tempid;?>"><?php echo acymailing_absoluteURL($row->body);?></div>
						<div style="display:none" id="textcontent_<?php echo $row->tempid;?>"><?php echo $row->altbody;?></div>
						<div style="display:none" id="subject_<?php echo $row->tempid;?>"><?php echo $row->subject;?></div>
						<div style="display:none" id="replyname_<?php echo $row->tempid;?>"><?php echo $row->replyname;?></div>
						<div style="display:none" id="replyemail_<?php echo $row->tempid;?>"><?php echo $row->replyemail;?></div>
						<div style="display:none" id="fromname_<?php echo $row->tempid;?>"><?php echo $row->fromname;?></div>
						<div style="display:none" id="fromemail_<?php echo $row->tempid;?>"><?php echo $row->fromemail;?></div>
				</div>
			<?php
				}
			?>
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="theme" />
	<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
