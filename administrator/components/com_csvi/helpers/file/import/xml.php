<?php
/**
 * XML file processor class
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: xml.php 2275 2013-01-03 21:08:43Z RolandD $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class XmlFile extends CsviFile {

	/** @var bool  Indicates whether a record from an XML file is ready for extraction */
	private $_xml_cache = false;

	/** @var array  Contains the data found in the latest XML record read */
	private $_xml_data = array();

	/** @var array  Contains the list of fields found in the latest XML record read */
	private $_xml_fields = array();

	/** @var array  Contains details extracted from the XML file map */
	private $_xml_schema = array();

	/** @var array  Contains the list of valid record types (node name) in the input XML file */
	private $_xml_records = array();

	/** @var integer Internal line pointer */
	public $linepointer = 0;

	/**
	 * Construct the class and its settings
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Load the column headers from a file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		bool	true if we loaded the column header | false if the column headers are not loaded
	 * @since 		3.0
	 */
	public function loadColumnHeaders() {
		// XML has no column headers
		if ($this->_getXmlFields() == true) {
			$jinput = JFactory::getApplication()->input;
			$jinput->set('columnheaders', $this->_xml_fields);
			return true;
		}
		else {
			/**
			 * Note: clearing the value when no fields found causes a problem when processing
			 * XML files with the 'use headers' option set because the function is called after the
			 * end of file has been reached. When an empty set of fields is returned, the program
			 * fails to terminate properly and loops endlessly through the import continuation page.
			 */
			return false;
		}
	}

	/**
	 * Get the file position
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		int	current position in the file
	 * @since 		3.0
	 */
	public function getFilePos() {
		return $this->linepointer;
	}

	/**
	 * Set the file position
	 *
	 * To be able to set the file position correctly, the XML reader needs to be at the start of the file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		int	$pos	the position to move to
	 * @return 		int	current position in the file
	 * @since 		3.0
	 */
	public function setFilePos($pos) {
		// Close the XML reader
		$this->closeFile(false);
		// Open a new XML reader
		$this->processFile();
		// Move the pointer to the specified position
		return $this->_skipXmlRecords($pos);
	}

	/**
	 * Close the file
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
	public function closeFile($removefolder=true) {
		$this->data->close();
		$this->_xml_cache = false;
		$this->_closed = true;
		parent::closeFile($removefolder);
	}

	/**
	 * Read the next line in the file
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return 		array	with the line of data read | false if data cannot be read
	 * @since 		3.0
	 */
	public function readNextLine() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$csvilog->addDebug('Reading next line');
		if ($this->_getXmlData() == true) {
			$this->linepointer++;
			return $this->_xml_data;
		}
		else {
			return false;
		}
	}

	/**
	 * Process the file to import
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		3.0
	 */
	public function processFile() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Use a streaming approach to support large files
		$this->data = new XMLReader();
		$this->fp = $this->data->open($this->filename);
		if ($this->fp == false) {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_ERROR_XML_READING_FILE'));
			return false;
		}
		// File opened successfully - Prepare the arrays needed to read data from the XML file
		$this->fp = $this->_getXmlSchema();

		if ($this->fp == false) {
			$this->closeFile();
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_XML_INVALID_TABLE'));
			return false;
		}

		return true;
	}

	/**
	 * Searches an XML file for a series of nodes representing a complete record. The name(s) of the nodes representing records
	 * must be supplied (via the 'xml_nodes_map' field) but the list of fields can be supplied in three ways:
	 * 1 No fields defined in the 'xml_nodes_map' field and the 'use headers' option set to 'off'. Despite the 'use headers' being set
	 *   to 'off', the field nodes in the first record are used to configure the whole run. This requires that all fields be present in
	 *   every record, the node names match the field names and that all of the 'field' nodes are one level below the 'record' node in
	 *   the XML hierarchy.
	 * 2 No fields defined in the 'xml_nodes_map' field and the 'use headers' option set to 'on'. This causes the 'headers to be read
	 *   from the XML nodes for every record. This is suitable if not all of the fields are populated for every record in the XML file
	 *   but is slower than option 1.
	 * 3 Fields defined in the 'xml_nodes_map' field. The 'use headers' option is ignored. This gives the flexibility to set up a map
	 *   so that an XML hierarchy can be modelled, node names to be mapped to field names and also allows fields to be extracted from
	 *   node attributes.
	 *
	 * It is possible that the current node when the function is called is the start of the next record.
	 * Valid 'record' node names are held in the $this->_xml_records array.
	 * Valid 'field' node and attribute names are held in the $this->_xml_schema array.
	 * The arrays $this->_xml_data and $this->_xml_fields are populated with data and field names respectively. To prevent SQL errors
	 * when writing the data to the table, only fields found in the array $this->_supported_fields are included. This function could be
	 * extended.
	 *
	 * @copyright
	 * @author 		doorknob
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		bool - indicates whether the array $this->_xml_data was populated.
	 * @since 		3.0
	 */
	private function _getXmlFieldsAndData() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Initialise the arrays that will receive the data and field list from the next record
		$this->_xml_data = array();
		$this->_xml_fields = array();
		// Validation checking disabled because a DTD (or RELAX NG) schema is required and is usually not available
		$validate_xml = false;
		if( $validate_xml == true ) {
			// Note: When the DTD is external, this must be done before the first read()
			$this->data->setParserProperty(XMLReader::VALIDATE, true);
		}
		/**
		 * Search for the start of the next record but only read the file if the next record is not already current
		 * Must be the correct element type (start of node) and in the list of valid record nodes.
		 * Note: Self closing notes are accepted because they may contain data in the attributes
		 */
		if (!$this->_isXmlRecordNode($this->data->nodeType, $this->data->name)) {
			// Either no current node or the current node is not the start of a record
			while( $this->data->read() ) {
				if( $validate_xml == true ) {
					$this->fp = $this->data->isValid();
					if( $this->fp == false ){
						$this->closeFile();
						$csvilog->AddStats('incorrect', JText::_('COM_CSVI_XML_FILE_INVALID'));
						return false;
					}
				}
				if( $this->_isXmlRecordNode( $this->data->nodeType, $this->data->name ) ) {
					// Record node found
					break;
				}
			}
		}
		// Check whether a record was found
		if ($this->_isXmlRecordNode($this->data->nodeType, $this->data->name)) {
			// Start of a valid record
			$self_closing = $this->data->isEmptyElement;
			$record_name = strtolower($this->data->name);
			// Extract any attributes, if defined
			if (isset($this->_xml_schema[$record_name]['attrs']) ) {
				// Record level attributes are defined as fields
				while( $this->data->moveToNextAttribute() ) {
					// Check each attribute to determine whether the value should be extracted
					if( isset($this->_xml_schema[$record_name]['attrs'][strtolower($this->data->name)]) ) {
						$this->_xml_fields[] = $this->_xml_schema[$record_name]['attrs'][strtolower($this->data->name)];
						$this->_xml_data[] = $this->data->value;
					}
				}
			}
			if ($self_closing == false) {
				// Now search for group/field nodes
				$xml_path = array();
				/**
				 * Note: $this->data->next() is used rather than $this->data->read() when the readInnerXML() function has been used
				 * to extract the contents of a node. Because the contect extracted may contain other nodes,$this->data->next() is
				 * used to skip to the closing node.
				 * readInnerXML() is used in case the data contains html tags but it doesn't move the file pointer.
				 * Subsequently using $this->data->next() forces the pointer to skip past any HTML tags that have already been read.
				 * The value does not need to be maintained between between calls to this function because readInnerXML() is
				 * never used immediately before exiting the function.
				 */
				$use_read = true;
				while ($use_read == true ? $this->data->read() : $this->data->next() ) {
					// XML error detection
					if ($validate_xml == true) {
						$this->fp = $this->data->isValid();
						if( $this->fp == false ){
							$this->closeFile();
							$csvilog->AddStats('incorrect', JText::_('COM_CSVI_XML_FILE_INVALID'));
							return false;
						}
					}
					// Default to a reading a single node in the next loop
					$use_read = true;
					// Searching for a group or field node
					if ($this->data->nodeType == XMLReader::ELEMENT) {
						// New node found
						$self_closing = $this->data->isEmptyElement;
						if (empty($this->_xml_schema)) {
							// Template fields being used to control the data extraction
							if ($this->_isXmlFieldNameValid($this->data->name)) {
								// Node corresponding to a supported field found - extract the data
								$this->_xml_fields[] = strtolower($this->data->name);
								$xmldata = $this->data->readInnerXML();
								$xmldata = str_ireplace(array('<![CDATA[',']]>'), '', $xmldata);
								$this->_xml_data[] = $xmldata;
								// At the next read, skip to the next node at this level
								$use_read = false;
							}
							else {
								// A node has been found that does not match the available fields list
								$csvilog->addDebug(JText::sprintf('COM_CSVI_XML_NODE_NOT_MATCH_FIELD', $this->data->name));
							}
						}
						else {
							// The user defined map is being used to control the data extraction
							$current_path = $this->_getXmlNodePath($xml_path, strtolower($this->data->name));

							// The node may have attributes that map to fields, regardless of the type of node
							if (isset($this->_xml_schema[$record_name]['nodes'][$current_path]['attrs'])) {
								// Node has attributes that are defined as fields
								while ($this->data->moveToNextAttribute()) {
									// Check each attribute to determine whether the value should be extracted
									if( isset($this->_xml_schema[$record_name]['nodes'][$current_path]['attrs'][strtolower($this->data->name)]) ) {
										$this->_xml_fields[] = $this->_xml_schema[$record_name]['nodes'][$current_path]['attrs'][strtolower($this->data->name)];
										$xmldata = $this->data->readInnerXML();
										$xmldata = str_ireplace(array('<![CDATA[',']]>'), '', $xmldata);
										$this->_xml_data[] = $xmldata;
										// At the next read, skip to the next node at this level
										$use_read = false;
									}
								}
							}
							if (empty($this->_xml_schema[$record_name]['nodes'][$current_path]['field'])) {
								// This node is not defined as a field
								if (isset($this->_xml_schema[$record_name]['nodes'][$current_path]['field'])) {
									// This is a 'group' node - add it to the path unless it is self closing
									if( $self_closing == false ) {
										$this->_pushXmlNodePath( $xml_path, $this->data->name );
									}
								}
								else {
									// Unknown node found - The file is not mapped correctly and the run cannot continue
									$csvilog->addDebug(JText::sprintf('COM_CSVI_XML_UNMAPPED_NODE', $this->data->name));
									$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_XML_UNDEFINED_NODE', $this->data->name));
									return false;
								}
							}
							else {
								// This node is defined as a field - extract the data
								$this->_xml_fields[] = $this->_xml_schema[$record_name]['nodes'][$current_path]['field'];
								$xmldata = $this->data->readInnerXML();
								$xmldata = str_ireplace(array('<![CDATA[',']]>'), '', $xmldata);
								$this->_xml_data[] = $xmldata;

								// At the next read, skip to the next node at this level
								$use_read = false;
							}
						}
					}
					else if ($this->data->nodeType == XMLReader::END_ELEMENT) {
						// Searching for the end of the record or the end of a group node
						if( strtolower($this->data->name) == $record_name ) {
							// End of record found
							break;
						} else {
							// End of node - Only valid case is the end of a group node
							if( !empty($this->_xml_schema) && !empty($xml_path) && $xml_path[count($xml_path)-1] == strtolower($this->data->name) ) {
								// End of group node found - pop it from the stack
								$this->_popXmlNodePath( $xml_path );
							} else {
								// Unknown end of node - error
								$csvilog->addDebug(JText::sprintf('COM_CSVI_XML_UNEXPECTED_END_NODE', $this->data->name));
							}
						}
					}
				}
			}
		}
		$this->_xml_cache = !empty($this->_xml_data);
		return $this->_xml_cache;
	}

	/**
	 * Returns a boolean value to indicate whether the specified node is a new XML record type.
	 *
	 * @copyright
	 * @author		doorknob
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		bool	true if record is a node | false if record is not a node
	 * @since 		3.0
	 */
	private function _isXmlRecordNode( $node_type, $node_name ) {
		return ($node_type == XMLReader::ELEMENT && in_array(strtolower($node_name), $this->_xml_records));
	}

	/**
	* Returns the list of data values found in the most recently read XML record. If the getXmlFields() function has been
	* called since the last call of this function, the cache may be full and the data can be returned without reading the file.
	*
	* @return array - The list of data values from the most recently read XML record (empty when end of file is reached)
	*/
	private function _getXmlData() {
		$return = !empty($this->_xml_data);
		if( $this->_xml_cache == false ) {
			$return = $this->_getXmlFieldsAndData();
		}
		// Indicate that a new record will be required the next time this function is called
		$this->_xml_cache = false;
		return $return;
	}

	/**
	* Returns the list of fields found in the most recently read XML record. If the cache is empty, the next record is
	* read from the input file to ensure that the headers correspond with the data (in XML files, each record can have a
	* different set of 'headers'). Checking the status of the cache indicator also prevents records being skipped if this
	* function is called multiple times between records.
	*
	* @return array - The list of fields from the most recently read XML record (empty when end of file is reached)
	*/
	private function _getXmlFields() {
		$return = !empty($this->_xml_fields);
		if ($this->_xml_cache == false) {
			$return = $this->_getXmlFieldsAndData();
		}
		return $return;
	}

	/**
	 * Skips through the XML file until the the required number 'record' nodes has been read
	 * Assume the file pointer is at the start of file
	 *
	 * @copyright
	 * @author		doorknob, RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		boolean	true if records are skipped | false if records are not skipped
	 * @since 		3.0
	 */
	private function _skipXmlRecords($pos) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Check whether the pointer needs to be moved
		if ($pos <= 0) return true;

		$count = 0;
		/**
		 * Note: When the DTD is external, this must be done before the first read()
		 * Validation not used because invalid files generate php errors when validated
		 */
		$validate_xml = false;
		if( $validate_xml == true ) {
			$this->data->setParserProperty(XMLReader::VALIDATE, true);
		}
		while ($this->data->read()) {
			// Validation checking disabled because a DTD (or RELAX NG) schema is required and is usually not available
			if( $validate_xml == true ) {
				$this->fp = $this->data->isValid();
				if( $this->fp == false ){
					$this->closeFile();
					$csvilog->AddStats('incorrect', JText::_('COM_CSVI_XML_INVALID'));
					return false;
				}
			}
			// Searching for a valid record - must be the start of a node and in the list of valid record types
			if( !$this->_isXmlRecordNode( $this->data->nodeType, $this->data->name ) ) {
				// Not found - try again
				continue;
			}
			else {
				// Found a valid record
				while( $this->_isXmlRecordNode( $this->data->nodeType, $this->data->name ) ) {
					// Node is a valid record type - skip to the end of the record
					$this->data->next();
					$count++;
					if( $count == $pos) {
						return true;
					}
				}
			}
		}
		// Hit EOF before skipping the required number of records
		return false;
	}

	/**
	* Build an array of xml nodes from the user defined map:
	*
	* $this->_xml_schema[record_node]['attrs'][attr_name] => field name
	* $this->_xml_schema[record_node]['nodes'][field_node_path]['attrs'][attr_name] => field name
	* $this->_xml_schema[record_node]['nodes'][field_node_path]['field'] => field name
	* Note: field_node_path is a comma separated list of node names below the record node
	*
	* @return bool returns true if all fine else false
	*/
	private function _getXmlSchema() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$xml_nodes_map = $template->get('xml_nodes_map', 'general', '', 'string', JREQUEST_ALLOWRAW);
		$xml_node_path = array(); // Single copy of this array that is passed by reference only
		$current_record = ''; // Single copy of this variable that is passed by reference only
		return $this->_getXmlNode($xml_nodes_map, $current_record, $xml_node_path);
	}

	/**
	* Process a node from the XML Map
	* It is permitted for the XML to just define one or more tables without fields (when the 'use headers' option is used)
	*
	* Note: Calls itself recursively to process a tree
	*
	* @return bool returns true if all fine else false
	*/
	private function _getXmlNode($node_content, $current_record, $xml_path) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$current_node = '';
		$xml_schema = new XMLReader();
		/**
		 * Add a wrapper to make the XML viable and ensure that self closing tags contain a space before the '/>'
		 * The XML may still be invalid but that's down to what the user entered
		 */
		$node_content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<da_root>".$node_content.'</da_root>';
		$xml_schema->XML($node_content);

		// XML file to table map is valid XML - construct the arrays used in file extraction
		$use_read = true;
		// The XML could only be validated against a DTD if the syntax of the XML used for the map is made more complex
		$validate_xml = false;
		if ($validate_xml == true) {
			// Note: When the DTD is external, the property value must be set before the first read()
			$xml_schema->setParserProperty(XMLReader::VALIDATE, true);
		}
		while($use_read ? $xml_schema->read() : $xml_schema->next()) {
			// Validation checking disabled because a DTD (or RELAX NG) schema is required.
			if ($validate_xml == true) {
				if( $xml_schema->isValid() == false ){
					$xml_schema->close();
					return false;
				}
			}

			// Default to a reading a single node in the next loop
			$use_read = true;
			// Ignore any node associated with the root
			if ($xml_schema->name == 'da_root' ) {
				continue;
			}

			// Process start elements
			if ($xml_schema->nodeType == XMLReader::ELEMENT) {
				$self_closing = $xml_schema->isEmptyElement;
				// Ready to add a new node - but only if the last node was closed
				if (!empty($current_node)) {
					$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_XML_NODE_UNCLOSED', $current_node));
					return false;
				}

				// A new node was found - Check whether this is a new record type
				if (empty($current_record)) {
					// New record type
					// Check for a self-closing node
					$self_closing = $xml_schema->isEmptyElement;
					$current_record = strtolower($xml_schema->name);
					$this->_xml_records[] = strtolower($current_record);

					// Store any attributes
					while ($xml_schema->moveToNextAttribute()) {
						// Note1: $xml_schema->hasValue only indicates whether the element can have a value, not whether it does
						// Note2: empty($xml_schema->value) always return true, regardless of the actual value
						$value = $xml_schema->value;
						if( !empty($value) ) {
							if( $this->_isXmlFieldNameValid($xml_schema->value) ) {
								$this->_xml_schema[$current_record]['attrs'][strtolower($xml_schema->name)] = trim($xml_schema->value);
							}
							else {
								$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_XML_FILE_MAP_NO_REFERENCE', $xml_schema->value));
								$xml_schema->close();
								return false;
							}
						}
					}
					// Check for a self-closing node
					if( $self_closing == true ) {
						$current_record = '';
					}
				}
				else {
					// New field type
					$current_node = strtolower($xml_schema->name);
					$current_path = $this->_getXmlNodePath($xml_path, $current_node);

					// Store any attributes
					while ($xml_schema->moveToNextAttribute()) {
						// Note1: $xml_schema->hasValue only indicates whether the element can have a value, not whether it does
						// Note2: empty($xml_schema->value) always return true, regardless of the actual value
						$value = $xml_schema->value;
						if( !empty($value) ) {
							if( $this->_isXmlFieldNameValid( $xml_schema->value ) ) {
								$this->_xml_schema[$current_record]['nodes'][$current_path]['attrs'][strtolower($xml_schema->name)] = trim($xml_schema->value);
							}
							else {
								$csvilog->AddStats('incorrect', JText::_('COM_CSVI_XML_FILE_MAP_NO_REFERENCE', $xml_schema->value));
								$xml_schema->close();
								return false;
							}
						}
					}
					$sub_node_content = $xml_schema->readInnerXML();

					// Check whether there are any lower level nodes
					if (strstr($sub_node_content, '<') === false) {
						/**
						 * Content has no embedded nodes - Assume a field name
						 * Note: An empty node gives a blank field name which indicates an unwanted node
						 * that is being mapped to prevent errors when processing the file
						 */
						if ($this->_isXmlFieldNameValid($sub_node_content)) {
							$this->_xml_schema[$current_record]['nodes'][$current_path]['field'] = trim($sub_node_content);
						}
						else {
							$this->_xml_schema[$current_record]['nodes'][$current_path]['field'] = '';
						}
					}
					else {
						// There are embedded nodes - go down another level
						// Indicate a 'group' node by storing an empty field name
						$this->_xml_schema[$current_record]['nodes'][$current_path]['field'] = '';
						// Push the node name to the path stack
						$this->_pushXmlNodePath($xml_path, $current_node);
						if( $this->_getXmlNode($sub_node_content, $current_record, $xml_path) == false ) {
							$xml_schema->close();
							return false;
						}
						// At the next read, skip to the next node at this level
						$use_read = false;
						// Close the node
						$current_node = '';
						// Pop the last item off the path stack
						$this->_popXmlNodePath($xml_path);
					}

					// Check for a self-closing node
					if ($self_closing == true) {
						$current_node = '';
					}
				}
			}
			// Process end elements
			else if( $xml_schema->nodeType == XMLReader::END_ELEMENT ) {
				// End of node found
				// Check for end of record
				if( !empty($current_record) && strtolower($xml_schema->name) == $current_record ) {
					// End of record detected
					$current_record = '';
				} // Check that the expected node was closed
				else if( !empty($current_node) && strtolower($xml_schema->name) == $current_node ) {
					// End of current node detected
					$current_node = '';
				}
			}
		}
		$xml_schema->close();

		// Node not terminated
		if (!empty($current_node) ) {
			$csvilog->AddStats('incorrect', JText::sprintf('COM_CSVI_XML_NODE_NOT_CLOSED', $current_node));
			return false;
		}
		if (empty($this->_xml_records) ) {
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_XML_NO_RECORDS_DEFINED'));
			return false;
		}
		return true;
	}

	/**
	* Create a string to represent the XML node hierarchy from the 'record' node to a 'field' node
	*
	* @return string
	*/
	private function _getXmlNodePath($xml_path, $node_name) {
		return implode(',', $xml_path).(empty($xml_path) ? '' : ',').$node_name;
	}

	/**
	* Determines whether a specific field name is included in the list of
	*
	* @return boolean
	*/
	private function _isXmlFieldNameValid($field_name) {
		return in_array(strtolower($field_name), $this->_supported_fields);
	}

	/**
	* Add a new entry to the XML node stack
	*/
	private function _pushXmlNodePath($xml_path, $node_name) {
		/**
		 * Note: The array index is made explicit because when a new row is added, the index value
		 * assigned is one greater than that previously assigned. When enries are being continuously
		 * added and removed, the automatically assigned index number becomes unpredictable
		 */
		$xml_path[count($xml_path)] = strtolower($node_name);
	}

	/**
	 * Remove the last entry from the XML node stack
	 *
	 * @copyright
	 * @author 		doorknob
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _popXmlNodePath($xml_path) {
		if( count($xml_path) > 1 ) {
			unset($xml_path[count($xml_path)-1]);
		} else {
			$xml_path = array();
		}
	}

	/**
	 * Sets the file pointer back to beginning
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
	public function rewind() {
		$this->linepointer = 0;
	}

	/**
	 * Advances the file pointer 1 forward
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		bool	$preview	True if called from the preview
	 * @return
	 * @since		3.0
	 */
	public function next($preview=false) {
		if (!$preview) $discard = $this->readNextLine();
	}
}
?>
