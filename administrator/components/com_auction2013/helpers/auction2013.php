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
				'date_show'=>'Дата включения отображения предмета на сайте', // ?
				'date_hide'=>'Дата отключения отображения предмета на сайте', // ?				
				'date_start'=>'Дата начала периода торгов по предмету', // ?
				'date_stop'=>'Дата окончания периода торгов по предмету', // ?
				'title'=>'Название лота',
				'short_desc'=>'Краткое описание лота',
				'desc'=>'Описание лота',
				'price'=>'Стартовая цена', // ?
				'sales_price'=>'Конечная цена',
				'img <span style="font-weight:200;">(до 15-ти полей)</span>'=>'Имена файлов изображений &#8212; по одному в каждом поле.',
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
	private function getParentIdQuery($parent_category_name){
		return "(  SELECT category_id FROM #__geodesic_categories WHERE category_name = '".$parent_category_name."'  )";
	}	
/**
 * Получить данные для экспорта
 * @package
 * @subpackage
 */
	function getDataToExport( 
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
  cats.category_id,
  prods.image AS 'images',
  prods.id";
  
  /*-- cats.category_name,
  -- prods.order_item_id,
  -- prods.item_type,
  -- prods.quantity,
  -- prods.auction_type,
  -- prods.price_plan_id,
  -- prods.seller,
  -- prods.live,
  -- prods.precurrency,
  -- prods.postcurrency,
  -- prods.duration,*/
  
  /*
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
		//echo "<div class=''>query= <pre>".str_replace("#_","auc13",$query)."</pre></div>"; //die();
		$db=JFactory::getDBO();
		$db->setQuery($query);
		$prods=$db->loadAssocList();
		$headers=$this->getActualFields();
		array_unshift($prods,$headers);
		return $prods;
	}
/**
 * Сформировать набор конечных столбцов для таблицы данных
 * @package
 * @subpackage
 */
	function getActualFields($imgs_count=15){
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
	function getImagesToExport($classified_id=false){
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
	function getCategoriesToExport($source_db,$section_name=false){
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
   ORDER BY category_name";
		// echo "<div class=''>query= <pre>".str_replace("#_","auc13",$query)."</pre></div>"; //die();
		$db=JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadAssocList(); 	
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	function createCSV($catnames,$source_prods){
		$filename='/_docs/get_csv/'.array_shift($catnames)."___".array_pop($catnames).'.csv';
		$filename=str_replace(" ","_",$filename);
		$winfilename=iconv("UTF-8","windows-1251",$filename);
			$make_file=false;
		//var_dump($source_prods); 
		if($make_file){
			$fp = fopen(JPATH_SITE.$winfilename, 'w');
			foreach ($source_prods as $fields):
				fputcsv($fp, $fields, ";");
			endforeach;
			fclose($fp);
		}
		return $filename;	
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	private function connect_db_old($source_db){
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
