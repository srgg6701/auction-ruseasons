<?php
/**
 * @version     2.1.0
 * @package     com_auction2013
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;
// require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_auction2013'.DS.'tables'.DS.'table_name.php';
/**
 *auction2013 helper.
 */
class Auction2013Helper
{
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = JAccess::getActions('com_auction2013');

		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, 'com_auction2013'));
		}

		return $result;
	}
	
	public static function getImportFields(){
		return array(
				'auction_number'=>'Номер аукциона',
				'lot_number'=>'Номер лота',
				'contract_number'=>'Номер договора',
				'date_show'=>'Дата включения отображения предмета на сайте|дд.мм.гггг', // ?
				'date_hide'=>'Дата отключения отображения предмета на сайте|дд.мм.гггг', // ?				
				'date_start'=>'Дата/время начала периода торгов по предмету|дд.мм.гггг ЧЧ:ММ:CC', // ?
				'date_stop'=>'Дата/время окончания периода торгов по предмету|дд.мм.гггг ЧЧ:ММ:CC', // ?
				'title'=>'Название лота',
				'short_desc'=>'Краткое описание лота',
				'desc'=>'Описание лота',
				'price'=>'Стартовая цена|только цифры', // ?
				'sales_price'=>'Конечная цена|только цифры',
				'img <span style="font-weight:200;">(до 15-ти полей)</span>'=>'Имена файлов изображений &#8212; по одному в каждом поле.|имя.расширение',
			);
	}
	
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu()
	{
		//die('addSubmenu');
		$common_link_segment='index.php?option=com_auction2013&view=';
		JSubMenuHelper::addEntry(JText::_('Импорт предметов'),$common_link_segment.'importlots');
		JSubMenuHelper::addEntry(JText::_('Экспорт предметов'),$common_link_segment.'auction2013&layout=export');
	}	
}

class Export{
/**
 * Получить подзапрос извлечения id родительской категории (id выбранной секции)
 * @package
 * @subpackage
 */
	public function getParentIdQuery($parent_category_name){
		return "(  SELECT category_id FROM #__geodesic_categories WHERE category_name = '".$parent_category_name."'  )";
	}	
/**
 * Обработать дату, представленную в виде числа
 * @package
 * @subpackage
 */
	public function handleDateFormatInteger($rvalue){
		if(is_int($rvalue)
			   ||preg_match("/\b[0-9]{10}\b/", $rvalue)
			){
			if(!is_int($rvalue))
				(int)$rvalue;
			return date('Y-m-d H:i:s',$rvalue);
		}else
			return false;
	}
/**
 * Обработать дату, представленную в виде datetime без секунд
 * @package
 * @subpackage
 */
	public function handleDateFormatDate($rvalue){
		if(preg_match("/\b[0-9]{2}\.[0-9]{2}\.[0-9]{4}\+[0-9]{1,2}:[0-9]{1,2}[:0-9]{0,3}\b/", $rvalue)){
			$ardate=explode('+',$rvalue); // 10.02.2012 9:12
			$bDate=explode('.',$ardate[0]); // 10 02 2012
			$rTime=explode(":",$ardate[1]);
			for($i=0;$i<3;$i++){
				$tsegment=$rTime[$i];
				if(!$tsegment)
					$tsegment='00';
				if(strlen($tsegment)<2)
					$tsegment='0'.$tsegment;
				$rTime[$i]=$tsegment;
			}
			return // date:
				$bDate[2].'-'.$bDate[1].'-'.$bDate[0].
			 // time: //9:12
				' '.implode(":",$rTime);
		}else
			return false;
	}

/**
 * Проверить формат данных, по возможности - исправить.
 * @package
 * @subpackage
 */
	public function handleDataFormat($i,$key,$rvalue){

		if(strstr($key,'date')&&$rvalue){ //echo "date row(".gettype($rvalue)."): $rvalue<br>";
			/*if(is_int($rvalue)
			   ||preg_match("/\b[0-9]{10}\b/", $rvalue)
			){
				if(!is_int($rvalue))
					(int)$rvalue;
				return date('Y-m-d H:i:s',$rvalue);
			}
			
			else*/
			if(!$rvalue=$this->handleDateFormatInteger($rvalue)){
				/*if(preg_match("/\b[0-9]{2}\.[0-9]{2}\.[0-9]{4}\+[0-9]{2}:[0-9]{2}\b/", $rvalue)){
					$ardate=explode('+',$rvalue);
					$bDate=explode('.',$ardate[0]);
					return // date:
						$bDate[2].'-'.$bDate[1].'-'.$bDate[0].
					 // time:
						' '.$ardate[1].':00';
				}else*/
				if(!$rvalue=$this->handleDateFormatDate($rvalue)){ 
					// сохраним данные проблемной строки:
					$this->wrong[]=$tblHeaders[$j].':'.$i+1;
					return '<a name="'.$tblHeaders[$j].':'.$i.'" style="color:red">'.$rvalue.'</a>';
				}
			}
		}
		return $rvalue;
	}

/**
 * Получить данные для экспорта
 * @package
 * @subpackage
 */
	public function getDataToExport( 
							$source_db,
							$parent_category_name=false,
							$categories_ids=false
						){
		//echo "<div class=''>getDataToExport:: source_db= ".$source_db."</div>";
		$this->connect_db_old($source_db);
		// see method getImportFields() to control fields set
		// получить данные
		// ВНИМАНИЕ! Набор столбцов для таблицы с данными формируется методом getActualFields()
		$query="SELECT
  REPLACE(prods.optional_field_1,'%B9','') AS 'auction_number',
  prods.optional_field_5 AS 'lot_number',
  prods.optional_field_6 AS 'contract_number',
  prods.date AS 'date_show',
  prods.ends AS 'date_hide',
  REPLACE(prods.optional_field_3, '%3A',':') AS 'date_start',
  REPLACE(prods.optional_field_4, '%3A',':') AS 'date_stop',
  prods.title,
  '' AS 'short_desc',
  prods.description AS 'desc',
  prods.current_bid AS 'price',
  prods.final_price AS 'sales_price', 
  prods.image AS 'images',
  prods.id";
  /*-- cats.category_name,
  -- cats.category_id,
  -- prods.order_item_id,
  -- prods.item_type,
  -- prods.quantity,
  -- prods.auction_type,
  -- prods.price_plan_id,
  -- prods.seller,
  -- prods.live,
  -- prods.precurrency,
  -- prods.postcurrency,
  -- prods.duration,
  
  prods.optional_field_2 AS 'optf-1',
  prods.optional_field_1 AS 'optf-2',
  prods.optional_field_3 AS 'optf-3',
  prods.optional_field_4 AS 'optf-4',
  prods.optional_field_5 AS 'optf-5'";*/

  		$query.="
FROM #__geodesic_classifieds_cp prods
  INNER JOIN #__geodesic_categories cats
    ON prods.category = cats.category_id";
		
		if($parent_category_name)
			$query.="   
        AND cats.parent_id = ".$this->getParentIdQuery($parent_category_name);
		if($categories_ids){
			$query.="
	WHERE cats.category_id IN (";
			$j=0;
			foreach($categories_ids as $i=>$id){
				if($j)
					$query.=",";
				$query.=$id;
				$j++;
			}
			$query.=")";
		}
		$query.="
ORDER BY cats.category_name, prods.title";
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$prods=$db->loadAssocList();
		//echo "<div class=''>query(".count($prods).")= <pre>".str_replace("#_","auc13",$query)."</pre></div>"; //die();
		$headers=$this->getActualFields();
		array_unshift($prods,$headers);
		return $prods;
	}
/**
 * Сформировать набор конечных столбцов для таблицы данных
 * @package
 * @subpackage
 */
	public function getActualFields($imgs_count=15){
		$row_fields_set=Auction2013Helper::getImportFields();
		/*'auction_number'=>'Номер аукциона',
		'lot_number'=>'Номер лота',
		'contract_number'=>'Номер договора',
		'date_show'=>'Дата включения отображения предмета на сайте', // ?
		'date_hide'=>'Дата отключения отображения предмета на сайте', // ?				
		'date_start'=>'Дата начала периода торгов по предмету', // ?
		'date_stop'=>'Дата окончания периода торгов по предмету', // ?
		'title'=>'Название лота',
		'short_desc'=>'Краткое описание лота',
		'desc'=>'Описание лота',
		'price'=>'Стартовая цена', // ?
		'sales_price'=>'Конечная цена',
		'img <span style="font-weight:200;">(до 15-ти полей)</span>'=>'Имена файлов изображений &#8212; по одному в каждом поле.',*/
		// модифицировать исходный массив для соответствия с полями CSV-файла:
		array_pop($row_fields_set);
		$fields=array_flip($row_fields_set);
		$fields=array_values($fields);
		for($i=0;$i<$imgs_count;$i++)
			$fields[]='img';
		//var_dump($fields); die();
		return $fields;
	}	
/**
 * Получить изображения предмета
 * @package
 * @subpackage
 */
	public function getImagesToExport($classified_id=false){
		$query="SELECT full_filename FROM #__geodesic_classifieds_images_urls";
		if($classified_id)
			$query.="	
	WHERE classified_id = ".$classified_id;
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadResultArray(); 	
	}	
/**
 * Получить список категорий выбранной секции
 * @package
 * @subpackage
 */
	public function getCategoriesToExport($source_db,$section_name=false){
		$this->connect_db_old($source_db);		
		$query="SELECT cats.category_id, 
  category_name,
  ( SELECT COUNT(*) FROM #__geodesic_classifieds_cp
      WHERE category = cats.category_id
  ) AS 'count' 
	FROM #__geodesic_categories cats";
		if($section_name)
			$query.=" 
 WHERE cats.parent_id = ".$this->getParentIdQuery($section_name);
		
		$query.=" 
   ORDER BY category_name"; // echo "<div class=''>query= <pre>".str_replace("#_","auc13",$query)."</pre></div>"; //die();
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 	
	}
/**
 * Создать и сохранить CSV-файл 
 * @package
 * @subpackage
 */
	public function createCSV($catnames,$source_prods,$section){
		//var_dump(JRequest::get('post')); die('section='.$section);		
		$filename='/_docs/csv_saved/['.$section.']'.array_shift($catnames)."___".array_pop($catnames).'.csv';
		$filename=str_replace(" ","_",$filename);
		$winfilename=iconv("UTF-8","windows-1251",$filename);
			$make_file=true;
		$headers_count=count($source_prods[0]);
		if($make_file){
			$fp = fopen(JPATH_SITE.$winfilename, 'w');
			foreach ($source_prods as $i=>$fields){
				// строки:
				if($i){
					if(!isset($images)){
						$images=$this->getImagesToExport($fields['id']);
						// var_dump($images);
					}
					$im=0;
					// ячейки:
					$data=array();
					foreach($fields as $key=>$content){
						/*	'auction_number' => string '' (length=0)
							'lot_number' => string '00100' (length=5)
							'contract_number' => string '01/143/09' (length=9)
							'date_show' => string '1303374294' (length=10)
							'date_hide' => string '1587391800' (length=10)
							'date_start' => string '' (length=0)
							'date_stop' => string '' (length=0)
							'title' => string 'Миниатюра «Девушка в красной шали»' (length=64)
							'short_desc' => string '' (length=0)
							'desc' => string 'Живопись на кости, 1820-е годы, 7.7х5.5 см, рамка дерево, металл, 14.2х12 см' (length=119)
							'price' => string '0' (length=1)
							'sales_price' => string '0' (length=1)
							'category_id' => string '313' (length=3)
							================================================
							'images' => string '2' (length=1)
							'id' => string '1429' (length=4)	*/
						$im++;
						if(strstr($key,'date') && $content){
							if(!$data[$key]=$this->handleDateFormatInteger($content))
								if(!$data[$key]=$this->handleDateFormatDate($content))
									$data[$key]='';
						}else{
							if($key=='images'){
								$icount=0;
								while($im<$headers_count){
									$data['img'.$icount]=($images[$icount])?
										$images[$icount]:'';
										$icount++;
									$im++;
								}
							}
							$data[$key]=iconv("UTF-8","windows-1251",$content);
						}
					}
					unset($images);
				}else{
					$data=$fields;
				}
				unset($data['id']);
				unset($data['images']);
				fputcsv($fp, $data, ";"); 
				$i++;
			}
			fclose($fp);
		}
		return $filename;	
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	public function connect_db_old($source_db){
		if($source_db=='auctionru_ruse'){
			$host=(strstr($_SERVER['HTTP_HOST'],"localhost"))? '77.222.56.121':'localhost';
			$dsn = 'mysql:dbname='.$source_db.';host='.$host;
			$user = 'auctionru_ruse';
			$password = 'Ytxbnfnm2012';
			try {
				$dbh = new PDO($dsn, $user, $password);
				echo "<h1>Подключение к auctionru_ruse выполнено!</h1>";
			} catch (PDOException $e) {
				echo 'Подключение не удалось: ' . $e->getMessage();
			}
		} //echo('<h1>connect_db_old: '.$source_db.'</h1>');
	}	
}
