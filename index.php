<?php //
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
//strstr($_SERVER['HTTP_HOST'],"localhost")
error_reporting ( E_ALL ^ E_NOTICE );
//error_reporting ( E_ERROR | E_WARNING );
//error_reporting ( E_ERROR ^ E_NOTICE );
//ini_set ( 'display_errors', true );
//ini_set ( 'html_errors', false );
//ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );
// Set flag that this is a parent file.
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

if (file_exists(dirname(__FILE__) . '/defines.php')) {
	include_once dirname(__FILE__) . '/defines.php';
}

if (!defined('_JDEFINES')) {
	define('JPATH_BASE', dirname(__FILE__));
	require_once JPATH_BASE.'/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('site');

// Initialise the application.
$app->initialise();

// Mark afterIntialise in the profiler.
JDEBUG ? $_PROFILER->mark('afterInitialise') : null;

//TEST:
// Стек вызовов:
function setDebugTrace( $file,
						$method = false,
						$line = false,
						$condition = false,
						$note = false,
						$backtrace = false
					  ){
	
	$session = JFactory::getSession();
	
	if ( JRequest::getVar('option')=='com_virtuemart'
		 && JRequest::getVar('view')=='category'
		 && JRequest::getVar('layout')=='shop'
		 //&& JRequest::getVar('virtuemart_category_id')=='1'
		 //&& JRequest::getVar('Itemid')=='115'
		 && !JRequest::getVar('sclear')
	   ){
		if ($method&&strstr($method,"::")){
			$function='CLASS::METHOD';
		}else
			$function='Function';
		$dOut=$session->get('test_output');
		
		if ($backtrace){
			ob_start();
			debug_print_backtrace();
			$get_backtrace=ob_get_contents();
			ob_clean();
		}
		
		if (is_object($note)||is_array($note)){
			$new_note=$note;
			$note='';
			ob_start();
			foreach($new_note as $key=>$val){
				echo '<div>['.$key.']=>'.$val.'</div>';
			}
			$note=ob_get_contents();
			ob_clean();
		}
		
		$tempData=array(
					'FILE'=>$file,
					$function=>$method,
					'condition'=>$condition,
					'LINE'=>$line,
					'NOTE'=>$note,
					'BACKTRACE'=>$get_backtrace
				  );
		
		if(isset($dOut)){
			
			$skip=false;
			
			for($i=0,$j=count($dOut);$i<$j;$i++) {
				
				if ( $dOut[$i]['FILE']==$file
					 && $dOut[$i][$function]==$method
					 && $dOut[$i]['condition']==$condition
					 && $dOut[$i]['LINE']==$line
				   ){
					$skip=true;
					break;
				}
			}
		}
		
		if (!$skip){
			$dOut[]=$tempData;
			$session->set('test_output',$dOut);
		}
	}else
		$session->clear('test_output');
}
//TEST
if(JRequest::getVar('sclear')) {
	$session = JFactory::getSession();
	$session->clear('section_links');
	$session->clear('products_data');
	echo "<h1>SESSION IS CLEAR!</h1>";
}
// Route the application.
$app->route();

// Mark afterRoute in the profiler.
JDEBUG ? $_PROFILER->mark('afterRoute') : null;

// Dispatch the application.
$app->dispatch();

//TEST:
function showDebugTrace(){
	echo "<div><b>Request:</b></div>";
	var_dump(JRequest::get('get'));
	$session = JFactory::getSession();
	$test_output = $session->get('test_output');
	var_dump($test_output);
	$session->clear('test_output');
	echo "<div>Check clear:</div>";
	$test_output = $session->get('test_output');
	var_dump($test_output);
}
//TEST

// Mark afterDispatch in the profiler.
JDEBUG ? $_PROFILER->mark('afterDispatch') : null;

// Render the application.
$app->render();

// Mark afterRender in the profiler.
JDEBUG ? $_PROFILER->mark('afterRender') : null;

// Return the response.
echo $app;
