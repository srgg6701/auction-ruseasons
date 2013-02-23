<?php
/**
 * @version     1.0
 * @package     import CSV
 * @copyright   Copyright (C) webapps 2013. All rights reserved.
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
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function addProductMedia($virtuemart_product_id, $order){
		$db=JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$query->insert($db->quoteName('#__virtuemart_product_medias'));
		$query->columns(
					array( $db->quoteName('virtuemart_product_id'), 
						   $db->quoteName('virtuemart_media_id'),
						   $db->quoteName('ordering'),
						 )
				);
		$query->values( 
					$virtuemart_product_id . ', ' . 
					self::getLastId('virtuemart_media_id','virtuemart_medias',$db) .
					', '. $order
				);
		
		$db->setQuery($query);
		return $db->query();	
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public static function getLastId($id,$table,$db=false){
		$query="SELECT MAX($id) FROM #__".$table;
		if(!$db) $db=JFactory::getDBO();
		$db->setQuery($query);
		$last_id=$db->loadResult();
		
		echo "<div class=''>query = $query<br>last_id= ".$last_id."</div>";
		return $last_id; 
	}
	public static function import(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id=$user->id;
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
			
			//var_dump(JRequest::get('post'));
			/*	  'top_cat' => string '23, but does not matter here. See relations at virtuemart_category_categories, virtuemart_categories'
				  'virtuemart_category_id' => string '2'
				  'task' => string 'import'
				  'd8cbe8fbc1d088b324c57dff410d39da' => string '1'
				  'encoding' => string 'windows-1251'
				  'alt_encoding' => string ''
				  'option' => string 'com_auction2013'
			*/
			//var_dump($_FILES);
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
					//'virtuemart_product_price_id'=>array('0'),
					//'product_currency'=>array('131'),
					//'virtuemart_shoppergroup_id'=>array(''),
					//'basePrice'=>array('0'),
					//'product_tax_id'=>array('0'),
					//'product_discount_id'=>array('0'),
					//'product_price_publish_up'=>array(date('000-00-00 00:00:00')),
					//'product_price_publish_down'=>array(date('000-00-00 00:00:00')),
					//'product_override_price'=>array(''),
					//'price_quantity_start'=>array(''),
					//'price_quantity_end'=>array(''),
				);
			//"files/Bronze.csv";
			if (($handle = fopen($importfile, "r")) !== FALSE) {
				// synchronyze fields: file => tables:
				$arrFields=array(
							// #__virtuemart_products:
							'auction_number'=>'auction_number', 
							'contract_number'=>'contract_number',
							// дата начала аукциона
							'date_start'=>'auction_date_start',
							// дата окончания аукциона
							'date_stop'=>'auction_date_finish',
							// #__virtuemart_products_ru_ru:
							'title'=>'product_name', 
							'short_desc'=>'product_s_desc', 
							'desc'=>'product_desc', 
							// #__virtuemart_product_prices:
							// выставлять на сайте с/пд:
							'date_show'=>'product_available_date', 
							'date_hide'=>'product_available_date_closed',
							'price'=>'product_price' 
						);
				// go ahead!
				$data=array();
				$columns_names=array();
				$col_count=0;
				$imgExt=array('gif','jpg','png','wbmp');
				while (($cells = fgetcsv($handle, $max_length, ";")) !== FALSE) {
					$date_start=$date_stop=false; // containers for product_availability to calculate its value
					
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

								//echo "<div class=''>column_name= ".$column_name."</div>";
								switch($column_name){
									
									case 'date_show':
									case 'date_hide':
									case 'date_start':
									case 'date_stop':
										$dt=explode('.',$cell_content);
										$data[$data_index][$arrFields[$column_name]]=$dt[2].'-'.$dt[1].'-'.$dt[0];
									break;
									
									case 'price':
										$data[$data_index]['mprices']['product_price'][0]=$cell_content;
									break;
									
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
							if(!$images[$data_index])
								$images[$data_index]=array();
						}
					}
					$row_count++;
				}
                fclose($handle);
			}
			//
			//var_dump($images);//die();
			//echo "<hr><hr>";
			$adm_com_path=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart';
			defined('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', $adm_com_path);
			require_once $adm_com_path.DS.'helpers'.DS.'vmcontroller.php';			
			require_once $adm_com_path.DS.'helpers'.DS.'vmmodel.php';
			require_once $adm_com_path.DS.'tables'.DS.'products.php';
			require_once $adm_com_path.DS.'tables'.DS.'medias.php';
			require_once $adm_com_path.DS.'tables'.DS.'product_medias.php';

			$VmController=new VmController();
			$model = VmModel::getModel('Product','VirtueMartModel'); // VirtueMartModelProduct	

			// additional "static" fields:
			$arrDataToUpdate=array(
							'product_sku' => '',
							'product_weight' => '',
							'product_weight_uom' => 'KG',
							'product_length' => '0,0000',
							'product_width' => '0,0000',
							'product_height' => '0,0000',
							'product_lwh_uom' => 'M',
							'product_url' => '',
							'product_in_stock' => '1',
							'product_ordered' => '0',
							'low_stock_notification' => '0',
							'product_availability' => '',
							'product_special' => '0',
							'product_sales' => '0',
							'product_packaging' => '0,0000',
							'intnotes' => '',
							'metarobor' => '',
							'metaauthor' => '',
							'layout' => '0',
							'published' => '1',
							'created_on' => date('Y-m-d H:i:s'),
							'product_unit' => 'KG',
							'price_quantity_start'=>array(''),
							'price_quantity_end'=>array(''),
							'categories' => array($virtuemart_category_id),
						);?>
            <h4>Импортированные предметы:</h4>
		<?	var_dump($data); //die();
			echo "<hr><hr>";
			foreach($data as $i=>$data_stream){
				//var_dump($data_stream);//die();
				foreach($arrDataToUpdate as $field => $content)
					$data_stream[$field] = $content;

				$data_stream['mprices']['product_currency']=array('131');
				$data_stream['mprices']['product_override_price']=array('0,00000');
				$data_stream['mprices']['product_price_publish_up']=array('00.00.0000 0:00:00');
				$data_stream['mprices']['product_price_publish_down']=array('00.00.0000 0:00:00');				
				
				/*$test=true;
				if($test){
					foreach($images as $icount=>$pix){
						foreach($pix as $pcount=>$pic){
							//echo "<div class=''>PIC:</div>".$pic;
							//var_dump($pix);
							//echo '<hr>';
							$arrIm=explode('.',$pic);
							$pic_ext=array_pop($arrIm);
							$pic_name=implode('.',$arrIm);
							switch($pic_ext){
								// see above: $imgExt=array('gif','jpg','png','wbmp');
								case 'jpg':case 'jpeg':
									$mimetype='jpeg';
								break;
								case 'gif':
									$mimetype='gif';
								break;
								case 'png':
									$mimetype='png';
								break;
								case 'wbmp':
									$mimetype='x-windows-bmp';
								break;
							}
					echo '<div>image:<hr>
					file_mimetype = image/'.$mimetype.'<br>
					file_type = 	product<br>
					file_url = 		images/stories/virtuemart/product/'.$pic.'<br>
					file_url_thumb = images/stories/virtuemart/product/resized/'.$pic_name.'_90x90.'.$pic_ext.'<br>
					published =		1<br>
					created_on = 	'.date('Y-m-d H:i:s').'<br>
					created_by = 	'.$user_id.'
						</div><hr>';
						}
					}
				}else*/
				
				if( //$virtuemart_product_id = 35+$i
					$virtuemart_product_id = $VmController->import($model,$data_stream)
				){
				//if(1==1) { $virtuemart_product_id = '69';
					// add images:
					// #__virtuemart_medias, then #__virtuemart_product_medias
					echo "<BR><div class=''>IMAGES:</div>";
					var_dump($images[$i]);
					$arrMediaData[$i]=array('virtuemart_product_id' => $virtuemart_product_id);
					foreach($images[$i] as $icount=>$pic){
						$arrIm=explode('.',$pic);
						$pic_ext=array_pop($arrIm);
						switch($pic_ext){
							// see above: $imgExt=array('gif','jpg','png','wbmp');
							case 'jpg':case 'jpeg':
								$mimetype='jpeg';
							break;
							case 'gif':
								$mimetype='gif';
							break;
							case 'png':
								$mimetype='png';
							break;
							case 'wbmp':
								$mimetype='x-windows-bmp';
							break;
						}
						$arrMediaData[$i][]=array(
							'virtuemart_vendor_id' => '1',
							'file_title' => $pic, 
							'file_mimetype' => 'image/'.$mimetype,
							'file_type' => 'product',
							'file_url' => 'images/stories/virtuemart/product/'.$pic,
							'file_url_thumb' => 'images/stories/virtuemart/product/resized/'.$pic.'_90x90.'.$pic_ext,
							'file_params' => '',
							'published' => '1',
							'created_on' => date('Y-m-d H:i:s'),
							'created_by' => $user_id,
						);
					}
				}else
					echo "<div class=''>Не сохранено...</div>";
				$errors[]= $model->getErrors();
			/*foreach($data_stream as $key=>$data_string){
				$id = $VmController->import($model,$data_stream);
				//$model->store($data);
				if(!$id)
					echo "<div style='color:red'>Не выполнено...</div>";
				else "<div style='color:green'>Done!</div>";
				$errors[]= $model->getErrors();
			}*/
			}
			
			foreach($arrMediaData as $index => $mediadata){
				echo "<h1>record: ".$index."</h1><h2>Product id: $mediadata[virtuemart_product_id]</h2><div>";
				if(count($mediadata)>1){
					foreach($mediadata as $order => $media){
						$MediasTable = $model->getTable('medias'); //var_dump($MediasTable);
						$MediasTable->reset();
						//var_dump($media);
						echo "<div class=''>TABLE DATA SET START";
							if(is_array($media)){
								foreach($media as $field => $value){
									$MediasTable->set($field,$value);
									echo "<div class=''>set: ".$field." -> $value</div>";
								}
								$doit=true;
								if($doit) {
									$data_check_bind=array_values(array_flip($media));
									// Bind the data to the table
									if (!$MediasTable->bind($data_check_bind))
										echo $MediasTable->getError();
									// Check that the data is valid
									if (!$MediasTable->check())
										echo $MediasTable->getError();
									// Store the data in the table
									if (!$MediasTable->store(true))
										echo $MediasTable->getError();
									else
										self::addProductMedia($mediadata['virtuemart_product_id'], $order);
								}else echo "<div class=''>order: ".$order."</div>";
							}
						echo "<BR>TABLE DATA SET FINISH</div><hr>";
					}
				}else echo "<h3>NO images at all</h3>";
				
				echo "</div><hr>";
			}
			// $ProductMediasTable = $model->getTable('product_medias'); 
			//$MediasTable->set('virtuemart_vendor_id', '1');
			//$MediasTable->set('file_title', '');

						// var_dump($arrMediaData);
						
						/*foreach($arrMediaData as $tField => $tValue):
							$MediasTable->set($tField, $tValue);
						endforeach;*/
						// check fields' binding:
						
						// now let's try to push all the media stuff into #__virtuemart_product_medias!
						/*$pMedia=array(
								'virtuemart_product_id' => $virtuemart_product_id, 
								'virtuemart_media_id' => $last_id, 
								'ordering' => $pcount
							);
						var_dump($pMedia);
						$ProductMediasTable = $model->getTable('product_medias'); 
						$ProductMediasTable->reset();*/
						//self::addProductMedia($virtuemart_product_id, $pcount, $db);
						
						/*foreach($pMedia as $pmField => $pmValue):
							$ProductMediasTable->set($pmField, $pmValue);//
						endforeach;
						$data_check_bind2=array_values(array_flip($pMedia));
						// Bind the data to the table
						if (!$ProductMediasTable->bind($data_check_bind2))
							echo $ProductMediasTable->getError();
						// Check that the data is valid
						if (!$ProductMediasTable->check())
							echo $ProductMediasTable->getError();
						// Store the data in the table
						if (!$ProductMediasTable->store(true))
							echo $ProductMediasTable->getError();
						else 
							echo "<div class=''>Сохранено!</div>";*/
						//var_dump($ProductMediasTable);			
			
			var_dump($arrMediaData);
			if($errors) 
			foreach($errors as $error){
				foreach($error as $err):
					var_dump($err);
				endforeach;
			}
			
 			//
			echo('Импортировано: записей - '.($i+1).', изображений - '.$index);
			//
			die();
			//$redir = $this->redirectPath;
			//vmInfo($msg);
			//if(JRequest::getCmd('task') == 'apply'){
				//$redir .= '&task=edit&'.$this->_cidName.'[]='.$id;
			//} //else $this->display();
			//$this->setRedirect($redir, $msg,$type);
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