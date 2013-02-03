<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><script language="JavaScript" type="text/javascript">
		 function statsusers(){
		var dataTable = new google.visualization.DataTable();
		dataTable.addRows(<?php echo count($this->statsusers); ?>);

		dataTable.addColumn('date');
		dataTable.addColumn('number','<?php echo JText::_('USERS',true); ?>');

		<?php
		$i = count($this->statsusers)-1;
		foreach($this->statsusers as $oneResult){
			echo "dataTable.setValue($i, 0, new Date(".substr($oneResult->subday,0,4).",".intval(substr($oneResult->subday,5,2) - 1).",".substr($oneResult->subday,8,2).")); ";
			echo "dataTable.setValue($i, 1, ".intval(@$oneResult->total)."); ";
			if($i-- == 0) break;
		}
		?>

		var vis = new google.visualization.ColumnChart(document.getElementById('statsusers'));
		var options = {
			width:document.documentElement.clientWidth/2,
			height: 300,
			legend:'none',
			hAxis: { format: 'dd MMM' }
		};

		vis.draw(dataTable, options);
	}
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(statsusers);

</script>
<div id="statsusers" style="text-align:center;margin-bottom:20px"></div>
