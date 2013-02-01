<?php
/**
 * ICEcat helper
 *
 * @package 	CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: icecat.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Helper class to process ICEcat requests
* @package CSVI
 */
class IcecatHelper {

	/** @var object holds the XML parser */
	private $_xml_parser = null;

	/** @var string the XML data read from ICEcat */
	private $_data = false;

	/** @var array holds the data to process */
	private $_csvi_data = array();

	/** @var array holds the current open tags */
	private $_open_tags = array();

	/**
	 * Collect data to import
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Properly handle the die call
	 * @see
	 * @access 		public
	 * @param		string	$mpn		The MPN code to read
	 * @param		string	$mf_name	The manufacturer name
	 * @return 		array	ICEcat data
	 * @since 		3.0
	 */
	public function getData($mpn, $mf_name) {
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$csvilog = $jinput->get('csvilog', null, null);

		// Find the ICEcat ID
		$q = "SELECT product_id
			FROM ".$db->quoteName('#__csvi_icecat_index')." AS  i
			LEFT JOIN ".$db->quoteName('#__csvi_icecat_suppliers')." AS s
			ON s.supplier_id = i.supplier_id
			WHERE i.".$db->quoteName('prod_id')." = ".$db->Quote($mpn)."
			AND s.".$db->quoteName('supplier_name')." = ".$db->Quote($mf_name);
		$db->setQuery($q);
		$csvilog->addDebug(JText::_('COM_CSVI_FIND_ICECAT_ID'), true);
		$icecat_id = $db->loadResult();

		// See if we have a match, otherwise try to search more liberal
		if (!$icecat_id) {
			$q = "SELECT product_id
				FROM ".$db->quoteName('#__csvi_icecat_index')." AS  i
				LEFT JOIN ".$db->quoteName('#__csvi_icecat_suppliers')." AS s
				ON s.supplier_id = i.supplier_id
				WHERE i.".$db->quoteName('prod_id')." LIKE ".$db->Quote($mpn.'%')."
				AND s.".$db->quoteName('supplier_name')." = ".$db->Quote($mf_name);
			$db->setQuery($q);
			$csvilog->addDebug(JText::_('COM_CSVI_FIND_ICECAT_ID'), true);
			$icecat_id = $db->loadResult();
		}

		// See if we have an ICEcat ID
		if ($icecat_id) {
			// Setup the XML parser
			if ($this->_setupXmlParser()) {

				// Call ICEcat to get the data
				$this->_callIcecat($icecat_id);

				// See if we have any valid data
				if ($this->_data) {
					// Clean some data
					$this->_csvi_data = array();

					// Parse the XML data
					if (!xml_parse($this->_xml_parser, $this->_data, true)) {
						die(sprintf("XML error: %s at line %d\n",
									xml_error_string(xml_get_error_code($this->_xml_parser)),
									xml_get_current_line_number($this->_xml_parser)));
					}

					xml_parser_free($this->_xml_parser);
					return $this->_csvi_data;
				}
			}
			else return false;
		}
		else return false;
	}

	/**
	 * Process start elements
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		optimize building for specific imports
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _startElement($parser, $tagname, $attribs) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$tagname = strtolower($tagname);
		if (count($this->_open_tags) >= 1) {
			$parent_tag = $this->_open_tags[(count($this->_open_tags) - 1)];
		}
		else $parent_tag = '';
		switch ($tagname) {
			case 'product':
				switch ($parent_tag) {
					case 'productrelated':
						// Related products
						if (!array_key_exists('related_products', $this->_csvi_data)) $this->_csvi_data['related_products'] = '';
						$this->_csvi_data['related_products'] .= $attribs['PROD_ID'].'|';
						break;
					default:
						// See if we have an error
						if (array_key_exists('CODE', $attribs) && $attribs['CODE'] == '-1') {
							$csvilog->addDebug(JText::sprintf('COM_CSVI_ICECAT_ERROR_XML', $attribs['ERRORMESSAGE']));
						}
						else {
							// Process the attributes
							// SKU
							$this->_csvi_data['product_sku'] = $attribs['PROD_ID'];
							// Name
							$this->_csvi_data['product_name'] = $attribs['NAME'];
							// Images
							$this->_csvi_data['file_url'] = $attribs['HIGHPIC'];
							$this->_csvi_data['file_url_thumb'] = $attribs['THUMBPIC'];
							// Release date comes int he form YYYY-MM-DD
							if (strpos($attribs['RELEASEDATE'], '-')) {
								list($year, $month, $day) = explode('-', $attribs['RELEASEDATE']);
								$this->_csvi_data['product_available_date'] = $day.'/'.$month.'/'.$year;
							}
						}
						break;
				}
				break;
			case 'productfeature':
				switch ($parent_tag) {
					default:
						$this->_csvi_data['pf'] = $attribs['PRESENTATION_VALUE'];
						break;
				}
				break;
			case 'name':
				switch ($parent_tag) {
					case 'category':
						// Category
						$this->_csvi_data['category_path'] = $attribs['VALUE'];
						break;
					case 'feature':
						$find = array(' ', '/');
						$feature = strtolower(str_replace($find, '_', $attribs['VALUE']));
						$csvilog->addDebug(JText::sprintf('COM_CSVI_ICECAT_FOUND_FEATURE', $feature));
						$this->_csvi_data[$feature] = $this->_csvi_data['pf'];
						$this->_csvi_data['pf'] = null;
						break;
				}
				break;
			case 'productpicture':
				if (!empty($attribs)) {
					// Process the attribs
					// <ProductPicture Pic="http://images.icecat.biz/img/gallery/525017_2053.jpg" PicHeight="480" PicWidth="600" ProductPicture_ID="702732" Size="15185" ThumbPic="http://images.icecat.biz/img/gallery_thumbs/525017_643.jpg" ThumbSize="2228"/>
					$this->_csvi_data['product_files_file_name'][] = $attribs['THUMBPIC'];
					$this->_csvi_data['product_files_file_url'][] = $attribs['PIC'];
					$this->_csvi_data['product_files_file_title'][] = basename($attribs['THUMBPIC']);
					$this->_csvi_data['product_files_file_published'] = 'Y';
				}
				break;
			case 'productdescription':
				if (isset($attribs['LONGDESC'])) $this->_csvi_data['product_desc'] = str_ireplace('\n', '<br />', $attribs['LONGDESC']);
				else $this->_csvi_data['product_desc'] = '';
				if (isset($attribs['SHORTDESC'])) $this->_csvi_data['product_s_desc'] = $attribs['SHORTDESC'];
				else $this->_csvi_data['product_s_desc'] = '';
				break;
			case 'shortsummarydescription':
				// $this->_csvi_data['product_s_desc'] = '';
				break;
			case 'longsummarydescription':
				// $this->_csvi_data['product_desc'] = '';
				break;
			case 'supplier':
				$this->_csvi_data['manufacturer_name'] = $attribs['NAME'];
				break;
			case 'productdescription':
				// <ProductDescription ID="650155" LongDesc="Add a parallel port to your desktop computer through a PCI expansion slot\n\n    * Up to 3 times faster than legacy ISA or on-board parallel ports providing fast and reliable parallel communication\n    * Supports SPP, EPP, ECP and BPP communication modes for maximum compatibility with your parallel peripherals\n    * Guaranteed compatibility with any PC running Windows®, Linux® or DOS® for simple integration into your application\n\nThe PCI1P value priced EPP/ECP parallel card adds one IEEE 1284 port to your PC, with data transfer speeds of up to 2.7 Mbps – up to 3 times faster than on-board parallel ports.\n\nInstallation is a breeze with plug and play support and drivers for Windows® 7, Vista, XP, ME, 2000, 98, 95, NT4, DOS® and Linux®. IRQ sharing and hot swapping capabilities guarantee convenient, hassle-free connections to any parallel peripheral.\n\nBacked by a StarTech.com lifetime warranty and free lifetime technical support." ManualPDFSize="0" ManualPDFURL="http://pdfs.icecat.biz/pdf/650155-20-manual.pdf" PDFSize="0" PDFURL="http://pdfs.icecat.biz/pdf/650155-4896.pdf" ShortDesc="Value 1 Port PCI Parallel Adapter Card" URL="http://eu.startech.com/product/PCI1P-Value-1-Port-EPPECP-Parallel-PCI-Card" WarrantyInfo="lifetime" langid="1"/>
				if (!empty($attribs['MANUALPDFURL'])) {
					$this->_csvi_data['product_files_file_name'][] = $attribs['MANUALPDFURL'];
					$this->_csvi_data['product_files_file_url'][] = $attribs['MANUALPDFURL'];
					$this->_csvi_data['product_files_file_title'][] = basename($attribs['MANUALPDFURL']);
				}
				if (!empty($attribs['PDFURL'])) {
					$this->_csvi_data['product_files_file_name'][] = $attribs['PDFURL'];
					$this->_csvi_data['product_files_file_url'][] = $attribs['PDFURL'];
					$this->_csvi_data['product_files_file_title'][] = basename($attribs['PDFURL']);
				}
				break;
			default:
				break;
		}

		// Add the tagname of the list of processing tags
		$this->_open_tags[] = $tagname;
	}

	/**
	 * Process end elements
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _endElement($parser, $tagname) {
		// Remove the current tag as we are done with it
		array_pop($this->_open_tags);
	}

	/**
	 * Process the inner data
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _characterData($parser, $data) {
		$current_tag = end($this->_open_tags);
		switch ($current_tag) {
			case 'shortsummarydescription':
				// $this->_csvi_data['product_s_desc'] .= $data;
				break;
			case 'longsummarydescription':
				// $this->_csvi_data['product_desc'] .= $data;
				break;
		}
	}

	/**
	 * Set up the XML parser
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _setupXmlParser() {
		$this->_xml_parser = xml_parser_create("UTF-8");
		xml_parser_set_option($this->_xml_parser, XML_OPTION_CASE_FOLDING, 1);
		xml_set_object($this->_xml_parser, $this);
		xml_set_element_handler($this->_xml_parser, "_startElement", "_endElement");
		xml_set_character_data_handler($this->_xml_parser, "_characterData");

		if ($this->_xml_parser) return true;
		else return false;
	}

	/**
	 * Request the data from ICEcat
	 *
	 * There are different URLs to get the data from:
	 *
	 * Open ICEcat users have access to:
	 * http://data.icecat.biz/export/freexml.int/INT/ for access to the standardized data files (QUALITY=ICECAT).
	 * The language-specific data-files are found here:
	 * http://data.icecat.biz/export/freexml.int/[code]/[product_id].xml, where [code] stands e.g. for NL, EN, FR, DE, IT, ES, DK etc.
	 *
	 * For the Full ICEcat subscribers, a separate directory structure is in place. The standardized files are located at:
	 * http://data.icecat.biz/export/level4/INT
	 * and the language dependent versions are found here:
	 * http://data.icecat.biz/export/level4/[code]/[product_id].xml, where [code] stands e.g. for NL, EN, FR, DE, IT, ES, DK, etc. For
	 *
	 * Products need to be matched to a product file found at http://data.icecat.biz/export/freexml/EN/
	 *
	 * an index file with references to all product data-sheets in ICEcat or Open ICEcat, also historical/obsolete products
	 * files.index.csv|xml or files.index.csv.gz|xml.gz
	 * a smaller index file with only references to the new or changed product data-sheets of the respective day
	 * daily.index.csv|xml or daily.index.csv.gz|xml.gz
	 * an index file with only the products that are currently on the market, as far as we can see that based on 100s  of distributor and reseller price files
	 * on_market.index.csv|xml or on_market.index.csv.gz|xml.gz)
	 * an index file with the products that are or were on the market for which we only have basic market data, but no complete data-sheet
	 * nobody.index.csv|xml or nobody.index.csv.gz|xml.gz
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo 		Check for gzip functionality to reduce filesize
	 * @see
	 * @access 		private
	 * @param 		string	$icecat_id	the ICEcat ID to retrieve
	 * @return
	 * @since 		3.0
	 */
	private function _callIcecat($icecat_id) {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);
		$settings = new CsviSettings();

		// Construct the URL
		$url = ($settings->get('icecat.ice_advanced')) ? 'http://data.icecat.biz/export/level4/' : 'http://data.icecat.biz/export/freexml.int/';
		// The language to use
		$url .= $settings->get('icecat.ice_lang').'/';
		// The ID to retrieve
		$url .= $icecat_id.'.xml';
		$csvilog->addDebug(JText::sprintf('COM_CSVI_CALL_ICECAT_URL', $url));

		// Initialise the curl call
		$curl = curl_init();

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $settings->get('icecat.ice_username').":".$settings->get('icecat.ice_password'));

		// grab URL and pass it to the browser
		$this->_data = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);
	}

	/**
	 * Supported ICEcat languages
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
	public function supportdLanguages() {
		$codes = array();
		$codes[] = 'INT'; // - International standardized version of a data-sheet. When QUALITY = ICEcat language independent values.
		$codes[] = 'EN'; // Standard or UK English
		$codes[] = 'US'; // US English
		$codes[] = 'NL'; // Dutch
		$codes[] = 'FR'; // French
		$codes[] = 'DE'; // German
		$codes[] = 'IT'; // Italian
		$codes[] = 'ES'; // Spanish
		$codes[] = 'DK'; // Danish
		$codes[] = 'RU'; // Russian
		$codes[] = 'PT'; // Portuguese
		$codes[] = 'ZH'; // Chinese (simplified)
		$codes[] = 'SE'; // Swedish
		$codes[] = 'PL'; // Polish
		$codes[] = 'CZ'; // Czech
		$codes[] = 'HU'; // Hungarian
		$codes[] = 'FI'; // Finnish
		$codes[] = 'NO'; // Norwegian
		$codes[] = 'TR'; // Turkish
		$codes[] = 'BG'; // Bulgarian
		$codes[] = 'KA'; // Georgian
		$codes[] = 'RO'; // Romanian
		$codes[] = 'SR'; // Serbian
		$codes[] = 'JA'; // Japanese
		$codes[] = 'UK'; // Ukrainian
		$codes[] = 'CA'; // Catalan
		$codes[] = 'HR'; // Croatian

		return $codes;
	}
}
?>
