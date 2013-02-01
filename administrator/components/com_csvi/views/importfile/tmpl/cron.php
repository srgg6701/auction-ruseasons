<?php
/**
 * Cron result page
 *
 * @package 	CSVI
 * @subpackage 	Cron
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: cron.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$jinput = JFactory::getApplication()->input;
$csvilog = $jinput->get('csvilog', null, null);

// Display any messages there are
if (!empty($csvilog->logmessage)) echo $csvilog->logmessage;
else {
	echo JText::sprintf('COM_CSVI_RESULTS_FOR', $csvilog->getFilename())."\n";
	echo str_repeat("=", (strlen(JText::_('COM_CSVI_RESULTS_FOR'))+strlen($csvilog->getFilename())+1))."\n";
	if (!empty($this->logresult['result'])) {
		echo JText::_('COM_CSVI_TOTAL')."\t\t".JText::_('COM_CSVI_RESULT')."\t\t".JText::_('COM_CSVI_STATUS')."\n";
		foreach ($this->logresult['result'] as $result => $log) {
			echo $log->total_result."\t\t".$log->result."\t\t".JText::_('COM_CSVI_'.$log->status)."\n";
		}
	}
	else echo JText::_('COM_CSVI_NO_RESULTS_FOUND')."\n";
}
?>