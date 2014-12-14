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
	// Overwriting JView display method
	function display($tpl = null) 
	{	// Get the view data FROM model.
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');
		$this->item		= $this->get('Item');
        if($this->getLayout()=='auctions'){
            $this->section_header='Аукцион № '.$this->auction_number;
        }
        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// установить суффикс страницы. 
		// Поскольку не статья, - процедура специфична: 
		$clear_params=str_replace('"','',$this->item->params);
		$pg_params=explode(",",$clear_params);
		foreach($pg_params as $couple){
			$arrPar=explode(":",$couple);
			if($arrPar[0]=='pageclass_sfx'){
				$this->params->pageclass_sfx=$arrPar[1];
				break;
			}
		}
		// Assign data to the view
		$this->prepareDocument();
		//Escape strings for HTML output
		// Display the view
		parent::display($tpl);
	}
	protected function prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$user		= JFactory::getUser();
		$login		= $user->get('guest') ? true : false;
		$title 		= null;
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', $login ? JText::_('JLOGIN') : JText::_('JLOGOUT'));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
