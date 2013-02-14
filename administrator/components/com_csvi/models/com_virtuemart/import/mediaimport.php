<?php
/**
 * Product files import
 *
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: mediaimport.php 2298 2013-01-29 11:38:39Z RolandD $
 */

defined( '_JEXEC' ) or die;

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
 * @package	CSVIVirtueMart
 * @todo 	Clarify folder structure
 * @todo 	Add product ID <---- important
 */
class CsviModelMediaimport extends CsviModelImportfile {

	// Private tables
	/** @var object contains the medias table */
	private $_medias = null;
	private $_product_medias = null;

	// Public variables
	/** @var integer contains the product ID of a product */
	public $virtuemart_media_id = null;
	/** @var integer vendor ID */
	public $virtuemart_vendor_id = null;
	public $file_title = null;
	public $file_description = null;
	public $file_meta = null;
	public $file_mimetype = null;
	public $file_is_product_image = null;
	public $file_is_downloadable = null;
	public $file_is_forSale = null;
	public $file_params = '';
	/** @var integer contains the FQDN for the image */
	public $file_url = null;
	/** @var integer contains the FQDN for the image */
	public $file_url_thumb = null;
	/** @var string the type of image */
	public $file_type = 'product';

	// Private variables
	/** @var bool contains whether or not the product file should be deleted */
	protected $media_delete = 'N';

	/**
	 * Constructor
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.4
	 */
	public function __construct() {
		parent::__construct();
		// Load the tables that will contain the data
		$this->_loadTables();
		$this->loadSettings();
		// Set some initial values
		$this->date = JFactory::getDate();
		$this->user = JFactory::getUser();
    }

	/**
	 * Here starts the processing
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Redo the validateInput
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getStart() {
		// Get the logger
		$jinput = JFactory::getApplication()->input;

		// Load the data
		$this->loadData();

		// Load the helper
		$this->helper = new Com_VirtueMart();

		// Get the logger
		$csvilog = $jinput->get('csvilog', null, null);

		$this->virtuemart_vendor_id = $this->helper->getVendorId();

		// Process data
		foreach ($this->csvi_data as $name => $value) {
			// Check if the field needs extra treatment
			switch ($name) {
				case 'published':
					switch ($value) {
						case 'n':
						case 'N':
						case '0':
							$value = 0;
							break;
						default:
							$value = 1;
							break;
					}
					$this->published = $value;
					break;
				case 'media_delete':
					$this->$name = strtoupper($value);
					break;
				default:
					$this->$name = $value;
					break;
			}
		}

		// All good
		return true;
	}

	/**
	 * Process each record and store it in the database
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function getProcessRecord() {
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		// Process the image
		$this->_processMedia();

		// Set some basic values
		if (!isset($this->modified_on)) {
			$this->_medias->modified_on = $this->date->toMySQL();
			$this->_medias->modified_by = $this->user->id;
		}

		// Find the media ID
		$this->_medias->file_url = $this->file_url;
		$this->_medias->check();
		$this->virtuemart_media_id = $this->_medias->virtuemart_media_id;

		// Do we need to delete a media file?
		if ($this->media_delete == 'Y') {
			$this->_deleteMedia();
		}
		else {
			// Check if the media exists
			if (empty($this->virtuemart_media_id)) {
				$this->_medias->created_on = $this->date->toMySQL();
				$this->_medias->created_by = $this->user->id;
			}

			// Bind all the data
			$this->_medias->bind($this);

			// Store the data
			if ($this->_medias->store()) {
				if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_MEDIAFILE'));
				else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_MEDIAFILE'));

				// Add a link to the product if the SKU is specified
				if (isset($this->product_sku)) {
					$this->_product_medias->virtuemart_media_id = $this->_medias->virtuemart_media_id;
					$this->_product_medias->virtuemart_product_id = $this->helper->getProductId();
					if (!$this->_product_medias->check()) {
						if ($this->_product_medias->store()) {
							if ($this->queryResult() == 'UPDATE') $csvilog->AddStats('updated', JText::_('COM_CSVI_UPDATE_MEDIAXREF'));
							else $csvilog->AddStats('added', JText::_('COM_CSVI_ADD_MEDIAXREF'));
						}
					}
				}

			}
			else $csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MEDIAFILE_NOT_ADDED', $this->_medias->getError()));

			// Store the debug message
			$csvilog->addDebug(JText::_('COM_CSVI_MEDIAFILE_QUERY'), true);
		}

		// Clean the tables
		$this->cleanTables();
	}

	/**
	 * Load the product files related tables
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _loadTables() {
		$this->_medias = $this->getTable('medias');
		$this->_product_medias = $this->getTable('product_medias');
	}

	/**
	 * Cleaning the product files related tables
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		protected
	 * @param
	 * @return
	 * @since 		3.0
	 */
	protected function cleanTables() {
		$this->_medias->reset();
		$this->_product_medias->reset();

		// Clean local variables
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}

	/**
	 * Delete a media and its references
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _deleteMedia() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Delete the product
		if ($this->_medias->delete($this->virtuemart_media_id)) {
			$db = JFactory::getDbo();

			// Delete product reference
			$query = $db->getQuery(true);
			$query->delete('#__virtuemart_product_medias');
			$query->where('virtuemart_media_id = '.$this->virtuemart_media_id);
			$db->setQuery($query);
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_DELETE_PRODUCT_XREF'), true);
			$db->query();

			$csvilog->AddStats('deleted', JText::sprintf('COM_CSVI_MEDIA_DELETED', $this->virtuemart_media_id));
		}
		else {
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_MEDIA_NOT_DELETED', $this->virtuemart_media_id));
		}

		return true;
	}

	/**
	 * Process media files
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		4.0
	 */
	private function _processMedia() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);
		// Check if any image handling needs to be done
	if ($template->get('process_image', 'image', false)) {
			if (!is_null($this->file_url)) {
				// Image handling
				$imagehelper = new ImageHelper;

				// Verify the original image
				if ($imagehelper->isRemote($this->file_url)) {
					$original = $this->file_url;
					$remote = true;
					if ($template->get('save_images_on_server', 'image')) {
						switch ($this->file_type) {
							case 'category':
								$base = $template->get('file_location_category_images', 'path');
								break;
							default:
								$base = $template->get('file_location_product_images', 'path');
							break;
						}
					}
					else $base = '';
					$full_path = $base;
				}
				else {
					// Create the full file_url path
					switch ($this->file_type) {
						case 'category':
							$base = $template->get('file_location_category_images', 'path');
							break;
						default:
							$base = $template->get('file_location_product_images', 'path');
							break;
					}

					// Check if the image contains the image path
					$dirname = dirname($this->file_url);
					if (strpos($base, $dirname) !== false) {
						$image = basename($this->file_url);
					}
					$original = $base.$this->file_url;
					$remote = false;

					// Get subfolders
					$path_parts = pathinfo($original);
					$full_path = $path_parts['dirname'].'/';

					$csvilog->addDebug(JText::sprintf('COM_CSVI_CREATED_FILE_URL', $original));
					$remote = false;
				}

				// Generate image names
				$file_details = $imagehelper->ProcessImage($original, $full_path);

				// Process the file details
				if ($file_details['exists'] && $file_details['isimage']) {
					$media = array();
					$this->file_title = ($this->file_title) ? $this->file_title : $this->file_url;
					$this->file_description = ($this->file_description) ? $this->file_description : $this->file_url;
					$this->file_meta = ($this->file_meta) ? $this->file_meta : $this->file_url;

					$this->file_mimetype = $file_details['mime_type'];
					$this->file_type = $this->file_type;
					$this->file_is_product_image = ($this->file_type == 'product') ? 1 : 0;
					$this->file_is_downloadable = ($this->file_is_downloadable) ? $this->file_is_downloadable : 0;
					$this->file_is_forSale = ($this->file_is_forSale) ? $this->file_is_forSale : 0;
					$this->file_url = (empty($file_details['output_path'])) ? $file_details['output_name'] : $file_details['output_path'].$file_details['output_name'];

					// Create the thumbnail
					if ($template->get('thumb_create', 'image')) {
						// Get the subfolder structure
						$thumb_path = str_ireplace($base, '', $full_path);
						if (empty($this->file_url_thumb)) $this->file_url_thumb = 'resized/'.$thumb_path.basename($this->file_url);
						if (!$remote) $original = $this->file_url;
						$this->file_url_thumb = $imagehelper->createThumbnail($original, $base, $this->file_url_thumb);
					}
					else if (empty($this->file_url_thumb)) $this->file_url_thumb = $this->file_url;
				}
			}
		}
	}
}