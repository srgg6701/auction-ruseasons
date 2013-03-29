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
    public function __construct() {
        parent::__construct();
    }
	public function display($view=false)
	{	$view=$this->prepareView('importlots');
		$view->display(); 
	}
/**
 * Обработать текст названия, сохранить как slug
 * @package
 * @subpackage
 */
	public function handleSlug($slug,&$words,&$allwords){
		$noquote=mb_ereg_replace("&quot;","",$slug);
		$handled=mb_ereg_replace("[^A-Za-zА-Яа-я0-9\.,\-\s]","", $noquote);
		$handled=mb_ereg_replace(" ","-",$handled);
		if($key=array_search($handled,$allwords)!==false){
			if(!isset($words[$handled])){ 
				$words[$handled]=1;
			}else{ 
				$words[$handled]+=1;
			}
			$handled=$handled.'-'.$words[$handled];
		}
		$allwords[]=$handled;	
		return $handled;
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public function clear(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$data	= JRequest::get('post');
		$sections=$data['section'];
		/*'section' => 
			array
			  'online' => string 'Онлайн торги' (length=23)
			  'fulltime' => string 'Очные торги' (length=21)
			  'shop' => string 'Магазин' (length=14)*/
		
		/*'jform' => 
			array
			  'fulltime' => string 'fulltime' (length=8)
			  'shop' => string 'shop' (length=4)
		  'fulltime' => 
			array
			  24 => string '24' (length=2)
			  25 => string '25' (length=2)
			  26 => string '26' (length=2)
			  27 => string '27' (length=2)
			  28 => string '28' (length=2)
			  29 => string '29' (length=2)
			  30 => string '30' (length=2)
		  'shop' => 
			array
			  1 => string '1' (length=1)
			  2 => string '2' (length=1)
			  51 => string '51' (length=2)
			  3 => string '3' (length=1)
			  50 => string '50' (length=2)
			  4 => string '4' (length=1)
			  5 => string '5' (length=1)
			  6 => string '6' (length=1)
			  7 => string '7' (length=1)
			  8 => string '8' (length=1)
			  9 => string '9' (length=1)
			  10 => string '10' (length=2)
			  11 => string '11' (length=2)
			  12 => string '12' (length=2)
			  13 => string '13' (length=2)
			  14 => string '14' (length=2)
			  16 => string '16' (length=2)
			  15 => string '15' (length=2)
			  19 => string '19' (length=2)
			  20 => string '20' (length=2)
		*/
		$model	= $this->getModel('Auction2013');
		$ok=0;
		foreach($sections as $layout=>$name){
			if(!empty($data[$layout])){
				// var_dump($data[$layout]); 
				// $data[$layout] == category_id
				if($model->deleteProducts($data[$layout]))
					$ok++;
			}
		}
		$message=($ok)? 'Данные успешно удалены.':'Не обнаружено данных для удаления...';
		$this->setRedirect('index.php?option=com_auction2013&view=importlots&layout=clear', $message);	
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
	private function addProductMedia($virtuemart_product_id, $order, $test=false){
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
					$this->getLastId('virtuemart_media_id','virtuemart_medias',$db) .
					', '. $order
				); //if ($test) echo "<div style='color:brown'><pre>addProductMedia query= ".$query."</pre></div>";
	 // $db->setQuery($query);
		$db->setQuery(str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query));
		if(!$db->execute())
			return array('query', $query);
		else return true;	
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	private function addSalesRecord($virtuemart_product_id,$sales_price){
		$db=JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$query->insert($db->quoteName('#__dev_sales_price'));
		$query->columns(
					array( $db->quoteName('virtuemart_product_id'), 
						   $db->quoteName('sales_price'),
						 )
				);
		$query->values( 
					$virtuemart_product_id . ', ' . $sales_price
				); //if ($test) echo "<div style='color:brown'><pre>addProductMedia query= ".$query."</pre></div>";
	 // $db->setQuery($query);
		$db->setQuery(str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query));
		if(!$db->execute())
			return array('query', $query);
		else return true;	
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	public function getLastId($id,$table,$db=false){
		$query="SELECT MAX($id) FROM #__".$table;
		if(!$db) $db=JFactory::getDBO();
		$db->setQuery($query);
		$last_id=$db->loadResult();
		//echo "<div class=''>query = $query<br>last_id= ".$last_id."</div>";
		return $last_id; 
	}
	// Импорт товара. Добавляет данные в таблицы:
	// #__virtuemart_products				
	// #__virtuemart_products_ru_ru	
	// #__virtuemart_product_prices
	// #__virtuemart_product_categories				
	// #__virtuemart_medias
	// #__virtuemart_product_medias
	// TODO: прояснить таки момент с недобавлением картинок с неуникальными URL:
	// - при отработке данного скрипта записи в 2 последние таблицы просто не добавляются. Какие параметры и как можно использовать, чтобы были возможны другие варианты, те, что доступны при управлении изображениями товара через интерфейс VirtueMart'а?
	
	public function import(){
		
		$test=false;

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
			$prices=array('product_price'=>array('0'));
			
			//"files/Bronze.csv";
			if (($handle = fopen($importfile, "r")) !== FALSE) {
				// synchronyze fields: file => tables:
				$arrFields=array(
							// #__virtuemart_products:
							'auction_number'=>'auction_number', 
							'lot_number'=>'lot_number',
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
							'price'=>'product_price', 
							// #__virtuemart_orders:
							'sales_price'=>'sales_price'
						);
				// go ahead!
				$data=array();
				$columns_names=array();
				$col_count=0;
				$imgExt=array('gif','jpg','png','wbmp');
				// извлечь данные из импортируемого файла во временный:
				while (($cells = fgetcsv($handle, $max_length, ";")) !== FALSE) {
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
								if(!$data[$data_index]['slug'])
									$data[$data_index]['slug']=$this->handleSlug($cell_content,$words,$allwords);
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
										$data[$data_index][$arrFields[$column_name]]=trim($dt[2]).'-'.trim($dt[1]).'-'.trim($dt[0]);
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
			} // //var_dump($images);//die(); //echo "<hr><hr>";
			
			$adm_com_path=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart';
			defined('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', $adm_com_path);
			require_once $adm_com_path.DS.'helpers'.DS.'vmcontroller.php';			
			require_once $adm_com_path.DS.'helpers'.DS.'vmmodel.php';
			require_once $adm_com_path.DS.'tables'.DS.'products.php';
			require_once $adm_com_path.DS.'tables'.DS.'medias.php';
			require_once $adm_com_path.DS.'tables'.DS.'product_medias.php';

			$VmController=new VmController();
			$model = VmModel::getModel('Product','VirtueMartModel'); // VirtueMartModelProduct	

			//
			if(!$model = VmModel::getModel('Product','VirtueMartModel'))
				echo "<div class=''>Не создан экземпляр Модели продукта \$model; line: ".__LINE__."</div>"; 

			// additional "static" fields:
			$arrDataToUpdate=array(
							'product_sku' => '',
							// 'slug' => '',
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
							'metarobot' => '',
							'metaauthor' => '',
							'layout' => '0',
							'published' => '1',
							'created_on' => date('Y-m-d H:i:s'),
							'product_unit' => 'KG',
							'price_quantity_start'=>array(''),
							'price_quantity_end'=>array(''),
							'categories' => array($virtuemart_category_id),
						); /*?>
            <h4>Импортированные предметы:</h4>
			<?*/	// var_dump($data); //die(); echo "<hr><hr>";
			foreach($data as $i=>$data_stream){ //var_dump($data_stream);//die();
				foreach($arrDataToUpdate as $field => $content)
					$data_stream[$field] = $content;

				$data_stream['mprices']['product_currency']=array('131');
				$data_stream['mprices']['product_override_price']=array('0,00000');
				$data_stream['mprices']['product_price_publish_up']=array('0000-00-00 0:00:00');
				$data_stream['mprices']['product_price_publish_down']=array('0000-00-00 0:00:00');				
				$data_stream['mprices']['product_price_publish_down']=array('0000-00-00 0:00:00');				
				//$virtuemart_product_id = 35+$i	
				if($virtuemart_product_id=$VmController->import($model,$data_stream)){
					// var_dump($data_stream);
					if($data_stream['sales_price'])
						if(!$this->addSalesRecord($virtuemart_product_id,$data_stream['sales_price']))
							echo "<div><b style='color:red'>ОШИБКА!</b>
						Не добавлена запись в таблицу #__dev_sales_price...</div>";
					// add images:
					// #__virtuemart_medias, then #__virtuemart_product_medias
					//echo "<BR><div class=''>IMAGES:</div>";
					//var_dump($images[$i]);
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
				}//else echo "<div class=''>Не сохранено...</div>";
				$errors[]= $model->getErrors();
			}
			if($test&&empty($data))
				echo "<div class=''>\$data is empty, line: ".__LINE__."</div>";
				
			$all_pix_count=0;
			foreach($arrMediaData as $index => $mediadata){

				if(count($mediadata)>1){
					foreach($mediadata as $order => $media){
						if(!$MediasTable = $model->getTable('medias'))
							echo "<div class=''>Не создан экземпляр класса $MediasTable; line: ".__LINE__."</div>"; 
						
						$MediasTable->reset();

						if(is_array($media)){
							foreach($media as $field => $value){
								$MediasTable->set($field,$value);
							}
							$doit=true;
							if($doit) {
								$data_check_bind=array_values(array_flip($media));
								if(!empty($media)){
									// Bind the data to the table
									if (!$MediasTable->bind($data_check_bind)){
										echo $MediasTable->getError();
										echo "<div class=''>Bind error; line: ".__LINE__."</div>";
									}
									// Check that the data is valid
									if (!$MediasTable->check()){
										echo $MediasTable->getError();
										echo "<div class=''>Check error; line: ".__LINE__."</div>";
										// Store the data in the table
									}
									if (!$MediasTable->store(true)){
										echo $MediasTable->getError();
										$imgErrors[].="<div>Не сохранена запись в таблице Medias.</div>";
									}else{
										
										if($test)
											echo "<div style='color:blue;'>Добавлена запись в таблицу Medias; line: ".__LINE__."</div>";
										
										$result=$this->addProductMedia($mediadata['virtuemart_product_id'], $order+1, $test);
										if($test)
											echo "<div style='color:violet'>result = ".$result.", line: ".__LINE__."</div>";
										if($result===true){
											$all_pix_count++;
											if($test)
												echo "<div style='color:blue;'>Добавлена запись в таблицу Product Medias; line: ".__LINE__."</div>";
										}else{
											if(is_array($result)){
												if(!$mediadata['virtuemart_product_id'])
													$imgErrors[].="<div>Не получен id предмета.</div>";
												else 
													$imgErrors[].="<div>Id предмета: ".$mediadata['virtuemart_product_id']."</div>";
												
												$imgErrors[].= "<hr>
													<h5>Не добавлены данные:</h5>";
												$lErr='';
												foreach($media as $field => $value)
													$lErr.= "<div class=''>{$field}: ".$value."</div>";
												$imgErrors[].= $lErr. "<hr>
												<b>Текст запроса:</b> 
												<div>
													<pre>".$result[1]."</pre>
												</div>
												<hr>";
											}else{
												echo "<div style='color:orange'>\$result is not an array! It is ".gettype($result).", {$result}</div>";
											}
										}
									}
								}elseif ($test)
									echo "<div style='color:666;'>\$media array is empty; line: ".__LINE__."</div>";
							}
						}elseif($test){
							if ($order!="virtuemart_product_id"){
								echo "<div class=''>\$media is not an array; type: ".gettype($media).";, line: ".__LINE__."</div>"; 
								echo "<br><br>\$mediadata:"; var_dump($mediadata); 
								echo "<hr>line: ".__LINE__."</div>";
							}
						}
					}
				}//else echo "<h3>NO images at all</h3>";		
				//echo "</div><hr>";
			}
			if (empty($arrMediaData)&&$test)
				echo "<div style='color:navy'>\$arrMediaData is empty, line: ".__LINE__."</div>";
				
			if($errors) 
			foreach($errors as $error){
				foreach($error as $err):
					var_dump($err);
				endforeach;
			}
			$msg='Импортировано '.($i+1).' записей.';
			$redir='index.php?option=com_auction2013';
			//
			if(count($imgErrors)||$test){
				echo('Импортировано: записей - '.($i+1).', изображений - '.$all_pix_count);
				if(count($imgErrors)) {
					echo "<hr><h5 style='color:red'>Ошибки добавления записей в табл. #__virtuemart_product_medias:</h5>";
					
					foreach($imgErrors as $e=>$ms)
						echo $imgErrors[$e];	
				}
				die('<h4><a href="'.JRoute::_($redir).'">Вернуться на страницу импорта.</a></h4>');
			}else 
				$this->setRedirect(JRoute::_($redir), $msg);
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