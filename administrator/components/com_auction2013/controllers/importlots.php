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
		
		if(isset($_FILES)&&!empty($_FILES)){
			$data=array(
					  'virtuemart_category_id',
					  'encoding',
					  'alt_encoding'
					);
			
			foreach($data as $i=>$field)
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
			$enc_from="windows-1251";
			$enc_to="UTF-8";

			$importfile=$files['tmp_name'];
			//"files/Bronze.csv";
			if (($handle = fopen($importfile, "r")) !== FALSE) {?>
				<table style="border:solid 1px #CCCCCC;" rules="rows">
			<?	while (($data = fgetcsv($handle, $max_length, ";")) !== FALSE) {
			
			
			/*	ПОЛЯ РАЗМЕЩЕНИЯ ДАННЫХ:
				-----------------------
				#__virtuemart_products:
					
					***virtuemart_product_id	: AUTO_INCREMENT

						virtuemart_vendor_id - 1
						product_parent_id	 - 0
						product_weight_uom	 - KG (dumb, I know...)
						product_lwh_uom		 - M (the same thing)

					product_available_date			: date_start
					product_available_date_closed	: date_stop
					auction_number					: auction_number
					contract_number					: contract_number
						
						product_unit - KG (see above)
						product_params - min_order_level=""|max_order_level=""|product_box=""|
						published	- 1
						created_on	- date('Y-m-d H:i:s');
						created_by	- user_id
						
				#__virtuemart_products_ru_ru
						
					virtuemart_product_id - #__virtuemart_products.last_insert_id
					
					product_s_desc	: short description
					product_desc	: sescription
					product_name	: title
				
				#__virtuemart_product_prices
				
					***virtuemart_product_price_id	: AUTO_INCREMENT
					
					virtuemart_product_id - #__virtuemart_products.last_insert_id
					
					product_price	: price
					
						product_currency - 131
						created_on 		 - user_id
				
				#__virtuemart_product_categories
				
					***id	: AUTO_INCREMENT
					
					virtuemart_product_id - #__virtuemart_products.last_insert_id
					
					virtuemart_category_id	: $virtuemart_category_id
				
				#__virtuemart_medias

					***virtuemart_media_id	: AUTO_INCREMENT
					
					virtuemart_vendor_id - 1
					
					// Установить:
					file_mimetype
					file_type 
				*/
				
				/*	
					file_url	: [URL]/img
					
					published	- 1
					created_on	- date('Y-m-d H:i:s');
					created_by	- user_id

				#__virtuemart_product_medias
				
					***id	: AUTO_INCREMENT
					
					virtuemart_product_id - #__virtuemart_products.last_insert_id
					virtuemart_media_id - #__virtuemart_medias.last_insert_id
					ordering 			- в соответствии с порядком добавления, начиная с 1 
			*/ 			?>
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
		<?	}
			die('IMPORT!');
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