<?php
/**
 * @version     2.1.0
 * @package     com_collector1
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');
// подключить главный контроллер компонента:
require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'controller.php';
/**
 * Customer_orders controller class.
 */
class Auction2013ControllerImportlots extends JControllerForm
{
    function __construct() {
        parent::__construct();
    }
	public function display($view=false)
	{	$view=$this->prepareView('importlots');
		$view->display(); 
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function import(){
		
		die('IMPORT!');
		
		$file="files/Bronze.csv";

		$max_length=false;
		$row_count = 0;
		$enc_from="windows-1251";
		$enc_to="UTF-8";
		
		if (($handle = fopen($file, "r")) !== FALSE) {?>
			<table style="border:solid 1px #CCCCCC;" rules="rows">
		<?	while (($data = fgetcsv($handle, $max_length, ";")) !== FALSE) {?>
				<tr>
			<?	$num = count($data);
				for ($c=0; $c < $num; $c++) {
					if (isset($enc_from)&&isset($enc_to))
						$data[$c]=iconv($enc_from,$enc_to,$data[$c]);
					
					$tag=(!$row_count)? 'th':'td';
					echo  "<{$tag} nowrap>".$data[$c]. "</{$tag}>";
				}?>
				</tr>
			<?	$row_count++;
			}
			fclose($handle);?>
			</table>
		<?
		}	
	}
/**
 * Подготовить данные представления
 * @package
 * @subpackage
 */
	public function prepareView($layout=false,$dview=false){
		$view=$this->getView('importlots', 'html' ); 
		$model=$this->getModel('Item'); 
		$view->setModel($model,true);
		$view->setLayout($layout);
		return $view; 
	}
}