<?php
/**
 * @package		Joomla.Site
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Class to create and parse routes for the site application
 *
 * @package		Joomla.Site
 * @subpackage	Application
 * @since		1.5
 */
class JRouterSite extends JRouter
{
	/**
	 * Parse the URI
	 *
	 * @param	object	The URI
	 *
	 * @return	array
	 */
	public function parse(&$uri)
	{
		$vars = array();

		// Get the application
		$app = JApplication::getInstance('site');

		if ($app->getCfg('force_ssl') == 2 && strtolower($uri->getScheme()) != 'https') {
			//forward to https
			$uri->setScheme('https');
			$app->redirect((string)$uri);
		}

		// Get the path
		$path = $uri->getPath();

		// Remove the base URI path.
		$path = substr_replace($path, '', 0, strlen(JURI::base(true)));

		// Check to see if a request to a specific entry point has been made.
		if (preg_match("#.*?\.php#u", $path, $matches)) {

			// Get the current entry point path relative to the site path.
			$scriptPath = realpath($_SERVER['SCRIPT_FILENAME'] ? $_SERVER['SCRIPT_FILENAME'] : str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']));
			$relativeScriptPath = str_replace('\\', '/', str_replace(JPATH_SITE, '', $scriptPath));

			// If a php file has been found in the request path, check to see if it is a valid file.
			// Also verify that it represents the same file from the server variable for entry script.
			if (file_exists(JPATH_SITE.$matches[0]) && ($matches[0] == $relativeScriptPath)) {

				// Remove the entry point segments from the request path for proper routing.
				$path = str_replace($matches[0], '', $path);
			}
		}

		// Identify format
		if ($this->_mode == JROUTER_MODE_SEF) {
			if ($app->getCfg('sef_suffix') && !(substr($path, -9) == 'index.php' || substr($path, -1) == '/')) {
				if ($suffix = pathinfo($path, PATHINFO_EXTENSION)) {
					$vars['format'] = $suffix;
				}
			}
		}

		//Set the route
		$uri->setPath(trim($path , '/'));

		$vars += parent::parse($uri);

		return $vars;
	}

	public function build($url,$show=false)
	{
		$uri = parent::build($url,$show); 
		
		if ($show=='JRoute:_')
			echo "<h1 style='color:red'>url = $url<hr>uri = ".$uri."</h1>";
		// Get the path data
		$route = $uri->getPath();
		
		if ($show){
			echo "<div class=''>url = ".$url."</div><hr>";
			echo "<div class=''>route = ".$route."</div><hr>";
		}

		//Add the suffix to the uri
		if ($this->_mode == JROUTER_MODE_SEF && $route) {
			$app = JApplication::getInstance('site');

			if ($app->getCfg('sef_suffix') && !(substr($route, -9) == 'index.php' || substr($route, -1) == '/')) {
				if ($format = $uri->getVar('format', 'html')) {
					$route .= '.'.$format;
					$uri->delVar('format');
				}
			}

			if ($app->getCfg('sef_rewrite')) {
				//Transform the route
				if ($route == 'index.php')
				{
					$route = '';
				}
				else
				{
					$route = str_replace('index.php/', '', $route);
				}
			}
		}

		//Add basepath to the uri
		$uri->setPath(JURI::base(true).'/'.$route);
		/*if ($show){
			echo "<div class=''>line: ".__LINE__."</div>";
			echo "<div class=''><B>BUILD:</B></div>";
			echo "<div class=''>base= ".JURI::base(true)."</div>";
			echo "<div class=''>route = ".$route."</div>";
			var_dump($uri);
			if ($_GET['stop']) die();
		}*/
		return $uri;
	}

	protected function _parseRawRoute(&$uri)
	{
		$vars	= array();
		$app	= JApplication::getInstance('site');
		$menu	= $app->getMenu(true);

		//Handle an empty URL (special case)
		if (!$uri->getVar('Itemid') && !$uri->getVar('option')) {
			$item = $menu->getDefault(JFactory::getLanguage()->getTag());
			if (!is_object($item)) {
				// No default item set
				return $vars;
			}

			//Set the information in the request
			$vars = $item->query;

			//Get the itemid
			$vars['Itemid'] = $item->id;

			// Set the active menu item
			$menu->setActive($vars['Itemid']);

			return $vars;
		}

		//Get the variables from the uri
		$this->setVars($uri->getQuery(true));

		//Get the itemid, if it hasn't been set force it to null
		$this->setVar('Itemid', JRequest::getInt('Itemid', null));

		// Only an Itemid  OR if filter language plugin set? Get the full information from the itemid
		if (count($this->getVars()) == 1 || ( $app->getLanguageFilter() && count( $this->getVars()) == 2 )) {

			$item = $menu->getItem($this->getVar('Itemid'));
			if ($item !== NULL && is_array($item->query)) {
				$vars = $vars + $item->query;
			}
		}

		// Set the active menu item
		$menu->setActive($this->getVar('Itemid'));

		return $vars;
	}

	protected function _parseSefRoute(&$uri)
	{
		$vars	= array();
		$app	= JApplication::getInstance('site');
		$menu	= $app->getMenu(true);
		$route	= $uri->getPath();

		// Remove the suffix
		if ($this->_mode == JROUTER_MODE_SEF)
		{
			if ($app->getCfg('sef_suffix'))
			{
				if ($suffix = pathinfo($route, PATHINFO_EXTENSION))
				{
					$route = str_replace('.' . $suffix, '', $route);
				}
			}
		}

		// Get the variables from the uri
		$vars = $uri->getQuery(true);

		// Handle an empty URL (special case)
		if (empty($route)) {
			// If route is empty AND option is set in the query, assume it's non-sef url, and parse apropriately
			if (isset($vars['option']) || isset($vars['Itemid'])) {
				return $this->_parseRawRoute($uri);
			}

			$item = $menu->getDefault(JFactory::getLanguage()->getTag());
			// if user not allowed to see default menu item then avoid notices
			if(is_object($item)) {
				//Set the information in the request
				$vars = $item->query;

				//Get the itemid
				$vars['Itemid'] = $item->id;

				// Set the active menu item
				$menu->setActive($vars['Itemid']);
			}
			return $vars;
		}

		/*
		 * Parse the application route
		 */
		$segments	= explode('/', $route);
		if (count($segments) > 1 && $segments[0] == 'component')
		{
			$vars['option'] = 'com_'.$segments[1];
			$vars['Itemid'] = null;
			$route = implode('/', array_slice($segments, 2));
		}
		else
		{
			//Need to reverse the array (highest sublevels first)
			$items = array_reverse($menu->getMenu());

			$found 				= false;
			$route_lowercase 	= JString::strtolower($route);
			$lang_tag 			= JFactory::getLanguage()->getTag();

			foreach ($items as $item) {
				//sqlsrv  change
				if(isset($item->language)){
					$item->language = trim($item->language);
				}
				$length = strlen($item->route); //get the length of the route
				if ($length > 0 && JString::strpos($route_lowercase.'/', $item->route.'/') === 0 && $item->type != 'menulink' && (!$app->getLanguageFilter() || $item->language == '*' || $item->language == $lang_tag)) {
					// We have exact item for this language
					if ($item->language == $lang_tag) {
						$found = $item;
						break;
					}
					// Or let's remember an item for all languages
					elseif (!$found) {
						$found = $item;
					}
				}
			}

			if (!$found) {
				$found = $menu->getDefault($lang_tag);
			}
			else {
				$route = substr($route, strlen($found->route));
				if ($route) {
					$route = substr($route, 1);
				}
			}

			$vars['Itemid'] = $found->id;
			$vars['option'] = $found->component;
		}

		// Set the active menu item
		if (isset($vars['Itemid'])) {
			$menu->setActive( $vars['Itemid']);
		}

		// Set the variables
		$this->setVars($vars);

		/*
		 * Parse the component route
		 */
		if (!empty($route) && isset($this->_vars['option'])) {
			$segments = explode('/', $route);
			if (empty($segments[0])) {
				array_shift($segments);
			}

			// Handle component	route
			$component = preg_replace('/[^A-Z0-9_\.-]/i', '', $this->_vars['option']);

			// Use the component routing handler if it exists
			$path = JPATH_SITE . '/components/' . $component . '/router.php';

			if (file_exists($path) && count($segments)) {
				if ($component != "com_search") { // Cheap fix on searches
					//decode the route segments
					$segments = $this->_decodeSegments($segments);
				} else {
					// fix up search for URL
					$total = count($segments);
					for ($i=0; $i<$total; $i++) {
						// urldecode twice because it is encoded twice
						$segments[$i] = urldecode(urldecode(stripcslashes($segments[$i])));
					}
				}

				require_once $path;
				$function = substr($component, 4).'ParseRoute';
				$function = str_replace(array("-", "."), "", $function);
				$vars =  $function($segments);

				$this->setVars($vars);
			}
		} else {
			//Set active menu item

			if ($item = $menu->getActive()) {
				$vars = $item->query;
			}
		}

		return $vars;
	}

	protected function _buildRawRoute(&$uri)
	{
	}

	protected function _buildSefRoute(&$uri,$show=false)
	{
		// Get the route
		$route = $uri->getPath(); // index.php
		//if ($show=='JRouter::build') echo "<h1 style='color:lime'>route= ".$route."</h1>";

		// Get the query data
		$query = $uri->getQuery(true);
		/*	array
			  'option' => string 'com_virtuemart' (length=14)
			  'view' => string 'productdetails' (length=14)
			  'virtuemart_product_id' => string '1' (length=1)
			  'virtuemart_category_id' => string '1' (length=1)
			  'Itemid' => string '115' (length=3)
				
			if ($show=='JRouter::build') var_dump($query);
		*/
		
		if (!isset($query['option'])) {
			return;
		}

		$app	= JApplication::getInstance('site');
		$menu	= $app->getMenu(); // JMenuSite
		//if ($show=='JRouter::build') echo "<h1 style='color:lime'>menu= ".$menu."</h1>";
		
		/*
		 * Build the component route
		 */
		$component	= preg_replace('/[^A-Z0-9_\.-]/i', '', $query['option']); // com_virtuemart
		//if ($show=='JRouter::build') echo "<h1 style='color:lime'>component= ".$component."</h1>";
		$tmp		= '';
		// Use the component routing handler if it exists
		$path = JPATH_SITE . '/components/' . $component . '/router.php'; //Z:\home\localhost\www\~auction.test/components/com_virtuemart/router.php
		//if ($show=='JRouter::build') echo "<h1 style='color:lime'>path= ".$path."</h1>";
		
		// Use the custom routing handler if it exists
		if (file_exists($path) && !empty($query)) {
			require_once $path;
			$function	= substr($component, 4).'BuildRoute'; // virtuemartBuildRoute
			//if ($show=='JRouter::build') echo "<h1 style='color:lime'>function= ".$function."</h1>";
			$function   = str_replace(array("-", "."), "", $function); // virtuemartBuildRoute
			//if ($show=='JRouter::build') echo "<h1 style='color:lime'>function= ".$function."</h1>";
			$parts		= $function($query);
			/*	array
				  0 => string 'магазин/русская-живопись' (length=46)
				  1 => string 'kartina-repina-pro-rep-detail' (length=29)
				if ($show=='JRouter::build') var_dump($parts);
  			*/
			// encode the route segments
			if ($component != 'com_search') {
				// Cheep fix on searches
				$parts = $this->_encodeSegments($parts);
				/* 	see above	
					if ($show=='JRouter::build') var_dump($parts);
				*/
			} else {
				// fix up search for URL
				$total = count($parts);
				for ($i = 0; $i < $total; $i++)
				{
					// urlencode twice because it is decoded once after redirect
					$parts[$i] = urlencode(urlencode(stripcslashes($parts[$i])));
				}
			}

			$result = implode('/', $parts); // магазин/русская-живопись/kartina-repina-pro-rep-detail
			//if ($show=='JRouter::build') echo "<h1 style='color:lime'>result= ".$result."</h1>";
			
			$tmp	= ($result != "") ? $result : '';
		}

		/*
		 * Build the application route
		 */
		$built = false;
		if (isset($query['Itemid']) && !empty($query['Itemid'])) {
			$item = $menu->getItem($query['Itemid'],'JRouter::_buildSefRoute');
			// 		JMenuSite->getItem(115);
			//if ($show=='JRouter::build') var_dump($item);
			/*	object(stdClass)[154]
				  public 'id' => string '126' (length=3)
				  public 'menutype' => string 'mainmenu' (length=8)
				  public 'title' => string 'Очные торги' (length=21)
				  public 'alias' => string 'торги-на-месте' (length=26)
				  public 'note' => string '' (length=0)
				  public 'route' => string 'auction/торги-на-месте' (length=34)
				  public 'link' => string 'index.php?option=com_virtuemart&view=category&layout=fulltime&virtuemart_category_id=0' (length=86)
				  public 'type' => string 'component' (length=9)
				  public 'level' => string '2' (length=1)
				  public 'language' => string '*' (length=1)
				  public 'browserNav' => string '0' (length=1)
				  public 'access' => string '1' (length=1)
				  public 'params' => 
					object(JRegistry)[182]
					  protected 'data' => 
						object(stdClass)[183]
						  public 'menu-anchor_title' => string '' (length=0)
						  public 'menu-anchor_css' => string '' (length=0)
						  public 'menu_image' => string '' (length=0)
						  public 'menu_text' => int 1
						  public 'page_title' => string '' (length=0)
						  public 'show_page_heading' => int 0
						  public 'page_heading' => string '' (length=0)
						  public 'pageclass_sfx' => string '' (length=0)
						  public 'menu-meta_description' => string '' (length=0)
						  public 'menu-meta_keywords' => string '' (length=0)
						  public 'robots' => string '' (length=0)
						  public 'secure' => int 0
				  public 'home' => string '0' (length=1)
				  public 'img' => string '' (length=0)
				  public 'template_style_id' => string '0' (length=1)
				  public 'component_id' => string '10004' (length=5)
				  public 'parent_id' => string '113' (length=3)
				  public 'component' => string 'com_virtuemart' (length=14)
				  public 'tree' => 
					array
					  0 => string '113' (length=3)
					  1 => string '126' (length=3)
				  public 'query' => 
					array
					  'option' => string 'com_virtuemart' (length=14)
					  'view' => string 'category' (length=8)
					  'layout' => string 'fulltime' (length=8)
					  'virtuemart_category_id' => string '0' (length=1)
	  	*/
			if (is_object($item) && $query['option'] == $item->component) {
				if (!$item->home || $item->language!='*') {
					$tmp = !empty($tmp) ? $item->route.'/'.$tmp : $item->route;
				}
				$built = true;
			}
		}

		if (!$built) {
			$tmp = 'component/'.substr($query['option'], 4).'/'.$tmp;
		}

		if ($tmp) {
			$route .= '/'.$tmp;
		}
		elseif ($route=='index.php') {
			$route = '';
		}

		// Unset unneeded query information
		if (isset($item) && $query['option'] == $item->component) {
			unset($query['Itemid']);
		}
		unset($query['option']);

		//Set query again in the URI
		$uri->setQuery($query);
		$uri->setPath($route);
	}

	protected function _processParseRules(&$uri)
	{
		// Process the attached parse rules
		$vars = parent::_processParseRules($uri);

		// Process the pagination support
		if ($this->_mode == JROUTER_MODE_SEF) {
			$app = JApplication::getInstance('site');

			if ($start = $uri->getVar('start')) {
				$uri->delVar('start');
				$vars['limitstart'] = $start;
			}
		}

		return $vars;
	}

	protected function _processBuildRules(&$uri)
	{
		// Make sure any menu vars are used if no others are specified
		if (($this->_mode != JROUTER_MODE_SEF) && $uri->getVar('Itemid') && count($uri->getQuery(true)) == 2) {

			$app	= JApplication::getInstance('site');
			$menu	= $app->getMenu();

			// Get the active menu item
			$itemid = $uri->getVar('Itemid');
			$item = $menu->getItem($itemid);

			if ($item) {
				$uri->setQuery($item->query);
			}
			$uri->setVar('Itemid', $itemid);
		}

		// Process the attached build rules
		parent::_processBuildRules($uri);

		// Get the path data
		$route = $uri->getPath();

		if ($this->_mode == JROUTER_MODE_SEF && $route) {
			$app = JApplication::getInstance('site');

			if ($limitstart = $uri->getVar('limitstart')) {
				$uri->setVar('start', (int) $limitstart);
				$uri->delVar('limitstart');
			}
		}

		$uri->setPath($route);
	}

	protected function _createURI($url)
	{
		//Create the URI
		$uri = parent::_createURI($url);

		// Set URI defaults
		$app	= JApplication::getInstance('site');
		$menu	= $app->getMenu();

		// Get the itemid form the URI
		$itemid = $uri->getVar('Itemid');

		if (is_null($itemid)) {
			if ($option = $uri->getVar('option')) {
				$item  = $menu->getItem($this->getVar('Itemid'));
				if (isset($item) && $item->component == $option) {
					$uri->setVar('Itemid', $item->id);
				}
			} else {
				if ($option = $this->getVar('option')) {
					$uri->setVar('option', $option);
				}

				if ($itemid = $this->getVar('Itemid')) {
					$uri->setVar('Itemid', $itemid);
				}
			}
		} else {
			if (!$uri->getVar('option')) {
				if ($item = $menu->getItem($itemid)) {
					$uri->setVar('option', $item->component);
				}
			}
		}

		return $uri;
	}
}
