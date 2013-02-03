<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2012 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
defined('_JEXEC') or die('RESTRICTED');

// Load class dependencies
wfimport('editor.libraries.classes.plugin');
wfimport('editor.libraries.classes.browser');

class WFMediaManager extends WFEditorPlugin {

    /**
     * @access  public
     */
    public function __construct($config = array()) {
        // Call parent
        //parent::__construct();
        
        if (!array_key_exists('type', $config)) {
            $config['type'] = 'manager';
        }
        
        if (!array_key_exists('layout', $config)) {
            $config['layout'] = 'manager';
        }
        
        if (!array_key_exists('view_path', $config)) {
            $config['view_path'] = WF_EDITOR_LIBRARIES . '/views/plugin';
        }
        
        if (!array_key_exists('template_path', $config)) {
            $config['template_path'] = WF_EDITOR_LIBRARIES . '/views/plugin/tmpl';
        }

        // Call parent
        parent::__construct($config);
        
        // update properties
        $this->setProperties($this->getConfig());

        // initialize the browser
        $browser = $this->getBrowser();
    }

    /**
     * Get the File Browser instance
     * @access public
     * @return object WFBrowserExtension
     */
    public function getBrowser() {
        static $browser;

        if (!is_object($browser)) {
            $browser = WFFileBrowser::getInstance($this->getProperties());
        }

        return $browser;
    }

    /**
     * Display the plugin
     * @access public
     */
    public function display() {
        $view = $this->getView();
        $browser = $this->getBrowser();

        parent::display();

        $document = WFDocument::getInstance();

        if ($document->get('standalone') == 1 && !JRequest::getWord('element', '')) {
            $browser = $this->getBrowser();
            $browser->removeButton('file', 'insert');
        }

        $browser->display();

        $browser->set('position', $this->getParam('editor.browser_position', 'bottom'));
        $view->assignRef('browser', $browser);
    }

    /**
     * Get the configuration
     * @access private
     * @return array
     */
    private function getConfig() {
        $filesystem = $this->getParam('filesystem.name', 'joomla', '', 'string', false);        
        $filetypes  = $this->getParam('extensions', $this->get('_filetypes', 'images=jpg,jpeg,png,gif'));

        $config = array(
            'dir' => $this->getParam('dir', '', '', 'string', false),
            'filesystem' => $filesystem,
            'filetypes' => $filetypes,
            'upload' => array(
                'runtimes' => $this->getParam('editor.upload_runtimes', array('html5', 'flash', 'silverlight'), '', 'array', false),
                'chunk_size' => null,
                'max_size' => $this->getParam('max_size', 1024, '', 'string', false),
                'validate_mimetype' => $this->getParam('validate_mimetype', 1),
                'add_random' => $this->getParam('editor.upload_add_random', 0)
            ),
            'folder_tree' => $this->getParam('editor.folder_tree', 1),
            'list_limit' => $this->getParam('editor.list_limit', 'all'),
            'use_cookies' => $this->getParam('editor.use_cookies', true),
            'features' => array(
                'upload' => $this->getParam('upload', 1),
                'folder' => array(
                    'create' => $this->getParam('folder_new', 1),
                    'delete' => $this->getParam('folder_delete', 1),
                    'rename' => $this->getParam('folder_rename', 1),
                    'move' => $this->getParam('folder_move', 1)
                ),
                'file' => array(
                    'delete' => $this->getParam('file_delete', 1),
                    'rename' => $this->getParam('file_rename', 1),
                    'move' => $this->getParam('file_move', 1)
                )
            ),
            'websafe_mode' => $this->getParam('editor.websafe_mode', 'utf-8'),
            'websafe_spaces' => $this->getParam('editor.websafe_allow_spaces', 0)
        );

        return $config;
    }

    /**
     * @see WFEditorPlugin::getSettings()
     */
    function getSettings($settings = array()) {
        return parent::getSettings($settings);
    }

}

?>