<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.'/helpers/stuff.php';
 
// Get an instance of the controller prefixed by component name
$controller = JController::getInstance('Auction2013'); 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
// Redirect if set by the controller
$controller->redirect();