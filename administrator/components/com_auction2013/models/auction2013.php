<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Menu List Model for Menus.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class Auction2013ModelAuction2013 extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array	An optional associative array of configuration settings.
	 *
	 * @see		JController
	 * @since   1.6
	 * Также указывает столбцы сортировки данных
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{	
			$config['filter_fields'] = array(
				'id', 'a.id',
				'abstract', 'a.abstract',
			);
		}
		parent::__construct($config);
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	public function deleteProducts($category_id){
		// get id id products to delete:
		if($products_ids=$this->getProductsIdsConnected($category_id)){
			// var_dump($products_ids); // die();
			/*	Универсальный запрос обнаружения 
				НЕПУСТЫХ таблиц virtuemart'а,
				содержащих поля с id продукта:
				==============================
				SELECT DISTINCT
				  TABLES.TABLE_NAME,
				  TABLES.DATA_LENGTH,
				  COLUMNS.COLUMN_NAME --, COLUMNS.TABLE_NAME,
				FROM information_schema.TABLES
				  INNER JOIN information_schema.COLUMNS
					ON TABLES.TABLE_NAME = COLUMNS.TABLE_NAME
				WHERE TABLES.TABLE_SCHEMA = "auctionru_2013" 
				  AND TABLES.TABLE_NAME LIKE '%virtuemart%' 
				  AND TABLES.DATA_LENGTH > 0 
				  AND ( COLUMNS.COLUMN_NAME LIKE '%product_id%' 
						OR 
						COLUMNS.COLUMN_NAME LIKE '%products_id%'
					  ) ORDER BY TABLES.DATA_LENGTH DESC
			
				РЕЗУЛЬТАТ:
				==============================
				auc13_virtuemart_product_categories			
				auc13_virtuemart_product_customfields
				auc13_virtuemart_product_medias
				auc13_virtuemart_product_prices
				auc13_virtuemart_product_manufacturers
				auc13_virtuemart_product_shoppergroups
				auc13_virtuemart_products
				auc13_virtuemart_products_ru_ru
				=======================================
			*/		
			$queries=array(
					'product_categories',
					'product_customfields',
					'product_manufacturers',
					'product_medias',
					'product_prices',
					'product_shoppergroups',
					'products',
					'products_ru_ru',
			);
	
			$inIds=" virtuemart_product_id IN (".implode(",",$products_ids).") ";
			foreach($queries as $i=>$vm_table){	
				$tbl='#__virtuemart_'.$vm_table;		
				$db	= JFactory::getDBO();
				if ($vm_table=='product_medias'){
					$query = $db->getQuery(true);
					$query->select("virtuemart_media_id"); 
					$query->from($db->quoteName($tbl));
					$query->where($inIds);
					$db->setQuery($query); // а иначе вытащит старый запрос!
					//echo "<blockquote>GET MEDIA IDs query[".__LINE__."]= ".$query."</blockquote>";
					$media_ids=$db->loadResultArray();
				}
				$query	= $db->getQuery(true);
				$query->delete();
				$query->from($db->quoteName($tbl));
				$query->where($inIds);
				$db->setQuery((string) $query);
				//echo "<div class=''>query[".__LINE__."]= ".$query."</div>";
				if (!$db->query())
					JError::raiseError(500, $db->getErrorMsg());
			}
			if (is_array($media_ids)&&!empty($media_ids)){
				// var_dump($media_ids);
				$db	= JFactory::getDBO();
				$query	= $db->getQuery(true);
				$query->delete();
				$query->from($db->quoteName('#__virtuemart_medias'));
				$query->where(" virtuemart_media_id IN (".implode(",",$media_ids).") ");
				$db->setQuery((string) $query);
				//echo "<hr><div class=''>query[".__LINE__."]= ".$query."</div>";
				if (!$db->query())
					JError::raiseError(500, $db->getErrorMsg());
			}
			return true;
		}else
			return false;
		//var_dump($products_ids); 
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	private function getProductsIdsConnected($categories){
		// Create a new query object.
        $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select fields from the table.
		$query->select("PRODS.virtuemart_product_id"); 
		$query->from($db->quoteName('#__virtuemart_products').' as PRODS');
		$query->join('INNER', $db->quoteName('#__virtuemart_product_categories') . ' AS CATS ON PRODS.virtuemart_product_id = CATS.virtuemart_product_id');
		$query->where('CATS.virtuemart_category_id IN ('.implode(",",$categories).')');
		// Add the list ordering clause.
		$db->setQuery($query); // а иначе вытащит старый запрос!
		// echo "<div class=''>query= <pre>".str_replace("#_","auc13",$query)."</pre></div>";
		$result=$db->loadResultArray();
		return $result;  
	}

	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{			

		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();
		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
		// Add the items to the internal cache.
		$this->cache[$store] = $items;
		return $this->cache[$store];
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select all fields from the table.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from($db->quoteName('#__auction2013').' AS a');
		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('filter_order')) . ' ' . $db->getEscaped($this->getState('filter_order_Dir', 'ASC')));
		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * Также имеет отношение к сортировке отображения данных. 
	 * См. здесь: http://docs.joomla.org/Adding_sortable_columns_to_a_table_in_a_component
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		if(!$filter_order=JRequest::getCmd('filter_order'))
			$filter_order='id';
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');
        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);
		// List state information.
		parent::populateState('a.id', 'asc');
	}
}
