<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.1.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php


class FileViewFile extends acymailingView
{
	function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$doc->addStyleSheet( ACYMAILING_CSS.'frontendedition.css' );

		JRequest::setVar('tmpl','component');

		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function css(){
		$file = JRequest::getCmd('file');
		if(!preg_match('#^([-A-Z0-9]*)_([-_A-Z0-9]*)$#i',$file,$result)){
			acymailing_display('Could not load the file '.$file.' properly');
			exit;
		}
		$type = $result[1];
		$fileName = $result[2];

		$content = JRequest::getString('csscontent');
		if(empty($content)) $content = file_get_contents(ACYMAILING_MEDIA.'css'.DS.$type.'_'.$fileName.'.css');

		if(strpos($fileName,'default') !== false){
			$fileName = 'custom'.str_replace('default','',$fileName);
			$i = 1;
			while(file_exists(ACYMAILING_MEDIA.'css'.DS.$type.'_'.$fileName.'.css')){
				$fileName = 'custom'.$i;
				$i++;
			}
		}

		$this->assignRef('content',$content);
		$this->assignRef('fileName',$fileName);
		$this->assignRef('type',$type);
	}


	function language(){

		$this->setLayout('default');

		$code = JRequest::getString('code');
		if(empty($code)){
			acymailing_display('Code not specified','error');
			return;
		}

		$file = new stdClass();
		$file->name = $code;
		$path = JLanguage::getLanguagePath(JPATH_ROOT).DS.$code.DS.$code.'.com_acymailing.ini';
		$file->path = $path;

		jimport('joomla.filesystem.file');
		$showLatest = true;
		$loadLatest = false;

		if(JFile::exists($path)){
			$file->content = JFile::read($path);
			if(empty($file->content)){
				acymailing_display('File not found : '.$path,'error');
			}
		}else{
			$loadLatest = true;
			acymailing_display(JText::_('LOAD_ENGLISH_1').'<br/>'.JText::_('LOAD_ENGLISH_2').'<br/>'.JText::_('LOAD_ENGLISH_3'),'info');
			$file->content = JFile::read(JLanguage::getLanguagePath(JPATH_ROOT).DS.'en-GB'.DS.'en-GB.com_acymailing.ini');
		}

		$custompath = JLanguage::getLanguagePath(JPATH_ROOT).DS.$code.DS.$code.'.com_acymailing_custom.ini';
		if(JFile::exists($custompath)){
			$file->customcontent = JFile::read($custompath);
		}

		if($loadLatest OR JRequest::getString('task') == 'latest'){
			$doc = JFactory::getDocument();
			$doc->addScript(ACYMAILING_UPDATEURL.'languageload&code='.JRequest::getString('code'));
			$showLatest = false;
		}elseif(JRequest::getString('task') == 'save') $showLatest = false;

		$this->assignRef('showLatest',$showLatest);
		$this->assignRef('file',$file);
	}

	function share(){
		$file = new stdClass();
		$file->name = JRequest::getString('code');
		$this->assignRef('file',$file);

	}
}
