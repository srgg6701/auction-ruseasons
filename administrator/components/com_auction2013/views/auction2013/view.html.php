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
	public $categories_data;
	public $fields;
	public $section=array();
	public $top_categories;
	
	protected $items;
	protected $pagination;
	protected $state;
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
		$this->top_categories=$this->getTopCategories();
		if($section=JRequest::getVar('section')){
			$this->section=explode(':',$section);
			$this->categories_data=Export::getCategoriesToExport();
			$post=JRequest::get('post');
			$this->products=$post['category_id'];

			// echo "<h1>\$section[".$this->section[1]."] = ".$this->section[0]."</h1>"; 
		}
	}		
}
