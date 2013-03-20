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

jimport('joomla.application.component.view');

/**
 * View class for a list of application.
 */
class Auction2013ViewAuction2013 extends JView
{
	public $active_categories; //array
	public $categories_data;
	public $fields;
	public $html_sections;
	public $section=array();
	public $section_products;
	public $source_db_boxes_html;
	public $top_categories;

	protected $items;
	protected $pagination;
	protected $state;
	
	private $source_db;
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{	
		// call: /joomla/application/component/view.php
		// there will be required the model that been set here by default
		// further it will call:
			// model()->getState() 
			// model()->getItems()
		// check model to ensure there they are!
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		// 
		$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		if($this->_layout=='export')
			$this->handleExport();
		$this->addToolbar($this->_layout);
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar($layout=false)
	{	
		$user = JFactory::getUser();
		JToolBarHelper::title(JText::_('Экспорт данных предметов аукциона'), 'csv.png');
		JToolBarHelper::custom('', 'publish', '', JText::_('Экспортировать!'), false);
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	private function getTopCategories(){
		return modVlotscatsHelper::getTopCategories(true);
	}
/**
 * Описание
 * @package
 * @subpackage
 */
	private function handleExport(){
		require_once JPATH_COMPONENT . '/helpers/auction2013.php';
		require_once JPATH_SITE.DS.'modules'.DS.'mod_vlotscats'.DS.'helper.php';	
		//var_dump(JRequest::get('post'));		
		$this->source_db=JRequest::getVar('db_name');
		$this->top_categories=$this->getTopCategories();
		if($section=JRequest::getVar('section')){
			$this->section=explode(':',$section);
			$Export=new Export;
			// массив чекбоксов post, приходящий с отправкой формы
			$this->active_categories=JRequest::getVar('category_id');
			//echo "<div class=''>active_categories:</div>";
			//var_dump(JRequest::getVar('category_id'));
			//Получить список категорий выбранной секции:
			$this->categories_data=$Export->getCategoriesToExport($this->source_db, $this->section[0]);
			$post=JRequest::get('post');
			$this->products=$post['category_id'];
			$this->section_products=$Export->getDataToExport($this->source_db,$this->section[0],$this->active_categories); // echo "<h1>handleExport:<br>\$section[".$this->section[1]."] = ".$this->section[0]."</h1>"; 
		}
		$this->chooseDb();
		// Выберите раздел:   
		// Онлайн торги, Очные торги, Магазин -
		$this->chooseSectionBlock();
	}	
/**
 * Описание
 * @package
 * @subpackage
 */
	private function chooseSectionBlock(){
		//var_dump($this->section); //die();
		ob_start();?>
	<?	foreach($this->top_categories as $i=>$data):?>
	<a href="javascript:void()" onclick="return setSectionValue('<?=$data['category_name'].':'.$data['virtuemart_category_id']?>');"><? 	
			if($this->section[1]!=$data['virtuemart_category_id']):
				echo $data['category_name'];
					
			else:?><span style="color:#000;">[ <?=$data['category_name']?> ]</span><?	
			endif;?></a> &nbsp;
	<?	endforeach;
		$this->html_sections=ob_get_contents();	
		ob_clean();	
	}
	
	private function chooseDb(){
		ob_start();?>
      <form method="post" name="export_start" id="export_start" action="index.php?option=com_auction2013&view=auction2013&layout=export">  
		  <div id="dbs" style="padding:8px;display:inline-block">
        	<input name="db_name" id="db_auctionru_ruse" type="radio" value="auctionru_ruse"<?
		if($this->source_db=='auctionru_ruse'){?> checked<? }?>>
        auctionru_ruse (<span style="color:brown">старый</span> сайт, префикс таблиц &mdash; <b>geodesic</b>)
        <br>
        	<input name="db_name" id="db_auctionru_2013" type="radio" value="auctionru_2013"<?
		if($this->source_db=='auctionru_2013'){?> checked<? }?>>
        auctionru_2013 (<span style="color:navy">новый</span> сайт, префикс таблиц &mdash; <b>auc13</b>)
        </div>
    	<input id="active_section" name="section" type="hidden" value="">        
	</form>
<script>
function setSectionValue(section_value){
	document.getElementById('active_section').value=section_value;
	if(!$('input[name="db_name"]:checked').size()){
		alert('Вы не указали источник данных экспорта.');
		$('#dbs').css('background-color','#FC6');
		return false;
	}else
		$('form#export_start').submit();
}
</script>    
<?		$this->source_db_boxes_html=ob_get_contents();
		ob_clean();
	}
}

