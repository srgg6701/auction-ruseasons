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
 * Импорт данных предметов из .csv-файла. 
 * *********************************************************************
 * См. схему таблиц для импорта в _docs/структура и бизнес-процессы.xlsx 
 * *********************************************************************
 * @package
 * @subpackage
 */
	function import(){

		// for your safety, please, use condoms :)
		JRequest::checkToken() or jexit( 'Invalid Token save' );
		
		if(isset($_FILES)&&!empty($_FILES)){
			$common_data_fields=array(
					  'virtuemart_category_id',
					  'encoding',
					  'alt_encoding'
					);
			
			foreach($common_data_fields as $i=>$field)
				// $virtuemart_category_id, $encoding AND so on...
				${$field}=JRequest::getVar($field);
			
			var_dump(JRequest::get('post'));
			/*	  'top_cat' => string '23, but does not matter here. See relations at virtuemart_category_categories, virtuemart_categories'
				  'virtuemart_category_id' => string '2'
				  'task' => string 'import'
				  'd8cbe8fbc1d088b324c57dff410d39da' => string '1'
				  'encoding' => string 'windows-1251'
				  'alt_encoding' => string ''
				  'option' => string 'com_auction2013'
			*/
			var_dump($_FILES);
			/*   'import_file' => 
					array
					  'name' => string 'Bronze.csv'
					  'type' => string 'application/vnd.ms-excel'
					  'tmp_name' => string 'Z:\tmp\phpEBAF.tmp'
					  'error' => int 0
					  'size' => int 6371
			*/
			$files=$_FILES['import_file'];
			
			$max_length=false;
			$row_count = 0;
			if(!$enc_from=JRequest::getVar('encoding'))
				$enc_from=JRequest::getVar('alt_encoding');
			$enc_to="UTF-8";

			$importfile=$files['tmp_name'];
			$prices=array(
					'product_price'=>array('0'),
					'virtuemart_product_price_id'=>array('0'),
					'product_currency'=>array('131'),
					'virtuemart_shoppergroup_id'=>array(''),
					'basePrice'=>array('0'),
					'product_tax_id'=>array('0'),
					'product_discount_id'=>array('0'),
					'product_price_publish_up'=>array(date('000-00-00 00:00:00')),
					'product_price_publish_down'=>array(date('000-00-00 00:00:00')),
					'product_override_price'=>array(''),
					'price_quantity_start'=>array(''),
					'price_quantity_end'=>array(''),
				);
			//"files/Bronze.csv";
			if (($handle = fopen($importfile, "r")) !== FALSE) {
				// synchronyze fields: file => tables:
				$arrFields=array(
							// #__virtuemart_products:
							'auction_number'=>'auction_number', 
							'contract_number'=>'contract_number',
							'date_start'=>'product_available_date',
							'date_stop'=>'product_available_date_closed',
							// #__virtuemart_products_ru_ru:
							'title'=>'product_name', 
							'short_desc'=>'product_s_desc', 
							'desc'=>'product_desc', 
							// #__virtuemart_product_prices:
							'date_show'=>true, 
							'date_hide'=>true,
							'price'=>true 
						);
				
				// go ahead!
				$data=array();
				$columns_names=array();
				$col_count=0;
				$imgExt=array('gif','jpg','png','wbmp');
				while (($cells = fgetcsv($handle, $max_length, ";")) !== FALSE) {
					$date_start=$date_stop=false; // containers for product_availability to calculate its value
					
					//$data[$row_count]['mprices']=$prices;
					// $cells - колич. ячеек в строке
					for ($i=0, $j=count($cells); $i < $j; $i++) {
						
						$cell_content=$cells[$i];
						// если первая строка файла - собираем заголовки:
						if(!$row_count){ 
							// если поле не было добавлено ранее. 
							// нужно, чтобы предотвратить более одного добавления
							// картинок, т.к., остальные должны добавляться
							// ПОСЛЕ добавления записи в БД:
							if( !in_array($cell_content,$columns_names)
							    && $cell_content
								&& $cell_content != 'img'
							  ) { 
							  	// сформировать порядок полей в пришедшем файле:
								$columns_names[]=$cell_content;
								// $columns_names[0] = 'date_hide';
								// $columns_names[1] = 'contract_number';
								if(!$row_count)
									$col_count++;
							} 
							
						}else{
							
							$data_index=$row_count-1;
							
							if (isset($enc_from)&&isset($enc_to))
								$cell_content=iconv($enc_from,$enc_to,$cell_content);							
							
							// если не кончились уникальные заголовки:
							if($col_count>$i){
								
								//имя текущего столбца, в том порядке, в котором расположены в файле:
								$column_name=$columns_names[$i];

								switch($column_name){
									
									case 'price':
										$data[$data_index]['mprices']['product_price'][0]=$cell_content;
									break;
									
									case 'date_show':
										$data[$data_index]['mprices']['product_price_publish_up'][0]=$cell_content;
									break;
									
									case 'date_hide':
										$data[$data_index]['mprices']['product_price_publish_down'][0]=$cell_content;
									break;
									
									// save dates to diff them for product_availability later:
									//case 'date_start':
										//$date_start=$data[$data_index][$arrFields[$column_name]]=$cell_content;
									//break;
									
									//case 'date_start':
										//$date_stop=$data[$data_index][$arrFields[$column_name]]=$cell_content;
									//break;

									default:
										$data[$data_index][$arrFields[$column_name]]=$cell_content;
								}
								
							}else{
								// сформировать массив вторичных картинок:
								$picExt=array_pop(explode('.',$cell_content));
								if(in_array(strtolower($picExt),$imgExt)){
									$images[$data_index][]=$cell_content;
								}
							}
						}
					}
					echo "<hr>";
					$row_count++;
				}
                fclose($handle);
			}
			echo "<hr><hr>";
			//var_dump($columns_names); 
			var_dump($data[0]); 
			var_dump($images[0]);
			$adm_com_path=JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart';
			defined('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', $adm_com_path);
			require_once $adm_com_path.DS.'models'.DS.'product.php';			
			$model = VmModel::getModel('VirtueMart','ModelProduct'); // VirtueMartModelProduct
 			//var_dump($model);
			die('IMPORT is done!');
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