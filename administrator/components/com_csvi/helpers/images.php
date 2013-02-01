<?php
/**
 * Image helper class
 *
 * @package		CSVI
 * @author 		Roland Dalmulder
 * @link 		http://www.csvimproved.com
 * @copyright 	Copyright (C) 2006 - 2013 RolandD Cyber Produksi. All rights reserved.
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: images.php 2275 2013-01-03 21:08:43Z RolandD $
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Image converter
 *
* @package CSVI
 */
class ImageHelper {

	/** @var array holds the mime types */
	private $_mime_types = array();
	/** @var array holds the image types */
	private $_image_types = array();
	/** @var array holds the mime types it found */
	private $_found_mime_type = array();
	/** @var array contains all the image data for processing */
	private $_imagedata = array();

	/**	@var int $bg_red 0-255 - red color variable for background filler */
	private $bg_red = 0;
	/**	@var int $bg_green 0-255 - green color variable for background filler */
	private $bg_green = 0;
	/** @var int $bg_blue 0-255 - blue color variable for background filler */
	private $bg_blue = 0;
	/**	@var int $maxSize 0-1 - true/false - should thumbnail be filled to max pixels */
	private $maxSize = false;
	/** @var string $file the original file */
	private $file = null;
	/** @var string $file_extension the extension of the original file */
	private $file_extension = null;
	/** @var string $file_out the name of the file to be created */
	private $file_out = null;
	/** @var string $file_out_extension the extension of the file to be created */
	public $file_out_extension = null;
	/** @var int $file_out_width the width of the file to be generated */
	private $file_out_width = 0;
	/** @var int $file_out_height the height of the file to be generated */
	private $file_out_height = 0;
	/** @var bool $rename set if the file should be renamed including the size of the image */
	private $rename = 0;

	/**
	* Construct the class and load some basic settings
	*
	* @author RolandD
	* @access public
	*/
	public function __construct() {
		$this->loadMimeTypes();
		$this->loadImageTypes();
	}

	/**
	 * Check if the given file is an image
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		add support for external files
	 * @see
	 * @access 		public
	 * @param 		string	$file	full path to file to check
	 * @param 		bool	$remote	true if the file to check is a remote file
	 * @return 		bool	true if file is image, false if file is not an image
	 * @since		3.0
	 */
	public function isImage($file, $remote=false) {
		$mime_type = $this->findMimeType($file, $remote);
		if ($mime_type) {
			foreach ($this->_image_types as $key => $type) {
				if ($type['mime_type'] == $mime_type) return true;
			}
		}
		// If we get here, no image type has been found
		return false;
	}

	/**
	 * Check a file for its mime type
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		string 	$filename	the full location of the file to check
	 * @param 		bool	$remote 	true if the file to check is a remote file
	 * @return 		string|bool 	return mime type if found | return false if no type found
	 * @since 		3.0
	 */
	public function findMimeType($filename, $remote=false) {
		jimport('joomla.filesystem.file');
		if (JFile::exists($filename) || $remote) {
			$handle = @fopen($filename, "r");
			if ($handle) {
				$string = fread($handle, 20);
				$max_length_found = 0;
				foreach ($this->_mime_types as $key => $type) {
					if (stripos(bin2hex($string), $type['signature'], 0) !== false) {
						if (strlen($type['signature']) > $max_length_found) {
							$max_length_found =  strlen($type['signature']);
							if (isset($type['mime_type'])) $this->_found_mime_type['mime_type'] = $type['mime_type'];
						}
					}
				}
				fclose($handle);
				if (isset($this->_found_mime_type['mime_type'])) return $this->_found_mime_type['mime_type'];
				else return false;
			}
			else {
				// Cannot open the image file, do a simple check
				switch (JFile::getExt($filename)) {
					case 'jpg':
					case 'jpeg':
						return 'image/jpeg';
						break;
					case 'png':
						return 'image/png';
						break;
					case 'gif':
						return 'image/gif';
						break;
					case 'bmp':
						return 'image/bmp';
						break;
					default:
						return false;
						break;
				}
			}
		}
		else return false;
	}

	/**
	 * Load mime type signatures
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		void
	 * @since 		3.0
	 */
	private function LoadMimeTypes() {
		$this->_mime_types[0]['signature'] = '474946383761';
		$this->_mime_types[1]['signature'] = '424D';
		$this->_mime_types[2]['signature'] = '4D5A';
		$this->_mime_types[3]['signature'] = '504B0304';
		$this->_mime_types[4]['signature'] = 'D0CF11E0A1B11AE1';
		$this->_mime_types[5]['signature'] = '0100000058000000';
		$this->_mime_types[6]['signature'] = '03000000C466C456';
		$this->_mime_types[7]['signature'] = '3F5F0300';
		$this->_mime_types[8]['signature'] = '1F8B08';
		$this->_mime_types[9]['signature'] = '28546869732066696C65';
		$this->_mime_types[10]['signature'] = '0000010000';
		$this->_mime_types[11]['signature'] = '4C000000011402';
		$this->_mime_types[12]['signature'] = '25504446';
		$this->_mime_types[13]['signature'] = '5245474544495434';
		$this->_mime_types[14]['signature'] = '7B5C727466';
		$this->_mime_types[15]['signature'] = 'lh';
		$this->_mime_types[16]['signature'] = 'MThd';
		$this->_mime_types[17]['signature'] = '0A050108';
		$this->_mime_types[18]['signature'] = '25215053';
		$this->_mime_types[19]['signature'] = '2112';
		$this->_mime_types[20]['signature'] = '1A02';
		$this->_mime_types[21]['signature'] = '1A03';
		$this->_mime_types[22]['signature'] = '1A04';
		$this->_mime_types[23]['signature'] = '1A08';
		$this->_mime_types[24]['signature'] = '1A09';
		$this->_mime_types[25]['signature'] = '60EA';
		$this->_mime_types[26]['signature'] = '41564920';
		$this->_mime_types[27]['signature'] = '425A68';
		$this->_mime_types[28]['signature'] = '49536328';
		$this->_mime_types[29]['signature'] = '4C01';
		$this->_mime_types[30]['signature'] = '303730373037';
		$this->_mime_types[31]['signature'] = '4352555348';
		$this->_mime_types[32]['signature'] = '3ADE68B1';
		$this->_mime_types[33]['signature'] = '1F8B';
		$this->_mime_types[34]['signature'] = '91334846';
		$this->_mime_types[35]['signature'] = '3C68746D6C3E';
		$this->_mime_types[36]['signature'] = '3C48544D4C3E';
		$this->_mime_types[37]['signature'] = '3C21444F4354';
		$this->_mime_types[38]['signature'] = '100';
		$this->_mime_types[39]['signature'] = '5F27A889';
		$this->_mime_types[40]['signature'] = '2D6C68352D';
		$this->_mime_types[41]['signature'] = '20006040600';
		$this->_mime_types[42]['signature'] = '00001A0007800100';
		$this->_mime_types[43]['signature'] = '00001A0000100400';
		$this->_mime_types[44]['signature'] = '20006800200';
		$this->_mime_types[45]['signature'] = '00001A0002100400';
		$this->_mime_types[46]['signature'] = '5B7665725D';
		$this->_mime_types[47]['signature'] = '300000041505052';
		$this->_mime_types[48]['signature'] = '1A0000030000';
		$this->_mime_types[49]['signature'] = '4D47582069747064';
		$this->_mime_types[50]['signature'] = '4D534346';
		$this->_mime_types[51]['signature'] = '4D546864';
		$this->_mime_types[52]['signature'] = '000001B3';
		$this->_mime_types[53]['signature'] = '0902060000001000B9045C00';
		$this->_mime_types[54]['signature'] = '0904060000001000F6055C00';
		$this->_mime_types[55]['signature'] = '7FFE340A';
		$this->_mime_types[56]['signature'] = '1234567890FF';
		$this->_mime_types[57]['signature'] = '31BE000000AB0000';
		$this->_mime_types[58]['signature'] = '1A00000300001100';
		$this->_mime_types[59]['signature'] = '7E424B00';
		$this->_mime_types[60]['signature'] = '504B0304';
		$this->_mime_types[61]['signature'] = '89504E470D0A';
		$this->_mime_types[62]['signature'] = '6D646174';
		$this->_mime_types[63]['signature'] = '6D646174';
		$this->_mime_types[64]['signature'] = '52617221';
		$this->_mime_types[65]['signature'] = '2E7261FD';
		$this->_mime_types[66]['signature'] = 'EDABEEDB';
		$this->_mime_types[67]['signature'] = '2E736E64';
		$this->_mime_types[68]['signature'] = '53495421';
		$this->_mime_types[69]['signature'] = '53747566664974';
		$this->_mime_types[70]['signature'] = '1F9D';
		$this->_mime_types[71]['signature'] = '49492A';
		$this->_mime_types[72]['signature'] = '4D4D2A';
		$this->_mime_types[73]['signature'] = '554641';
		$this->_mime_types[74]['signature'] = '57415645666D74';
		$this->_mime_types[75]['signature'] = 'D7CDC69A';
		$this->_mime_types[76]['signature'] = '4C000000';
		$this->_mime_types[77]['signature'] = '504B3030504B0304';
		$this->_mime_types[78]['signature'] = 'FF575047';
		$this->_mime_types[79]['signature'] = 'FF575043';
		$this->_mime_types[80]['signature'] = '3C3F786D6C';
		$this->_mime_types[81]['signature'] = 'FFFE3C0052004F004F0054005300540055004200';
		$this->_mime_types[82]['signature'] = '3C21454E54495459';
		$this->_mime_types[83]['signature'] = '5A4F4F20';
		$this->_mime_types[84]['signature'] = 'FFD8FFFE';
		$this->_mime_types[85]['signature'] = 'FFD8FFE0';
		$this->_mime_types[86]['signature'] = 'FFD8FFE1';
		$this->_mime_types[87]['signature'] = 'FFD8FFDB';
		$this->_mime_types[88]['signature'] = '474946383961';

		// Extensions
		$this->_mime_types[0]['extension'] = '.gif';
		$this->_mime_types[1]['extension'] = '.bmp';
		$this->_mime_types[2]['extension'] = '.exe;.com;.386;.ax;.acm;.sys;.dll;.drv;.flt;.fon;.ocx;.scr;.lrc;.vxd;.cpl;.x32';
		$this->_mime_types[3]['extension'] = '.zip';
		$this->_mime_types[4]['extension'] = '.doc;.xls;.xlt;.ppt;.apr';
		$this->_mime_types[5]['extension'] = '.emf';
		$this->_mime_types[6]['extension'] = '.evt';
		$this->_mime_types[7]['extension'] = '.gid;.hlp;.lhp';
		$this->_mime_types[8]['extension'] = '.gz';
		$this->_mime_types[9]['extension'] = '.hqx';
		$this->_mime_types[10]['extension'] = '.ico';
		$this->_mime_types[11]['extension'] = '.lnk';
		$this->_mime_types[12]['extension'] = '.pdf';
		$this->_mime_types[13]['extension'] = '.reg';
		$this->_mime_types[14]['extension'] = '.rtf';
		$this->_mime_types[15]['extension'] = '.lzh';
		$this->_mime_types[16]['extension'] = '.mid';
		$this->_mime_types[17]['extension'] = '.pcx';
		$this->_mime_types[18]['extension'] = '.eps';
		$this->_mime_types[19]['extension'] = '.ain';
		$this->_mime_types[20]['extension'] = '.arc';
		$this->_mime_types[21]['extension'] = '.arc';
		$this->_mime_types[22]['extension'] = '.arc';
		$this->_mime_types[23]['extension'] = '.arc';
		$this->_mime_types[24]['extension'] = '.arc';
		$this->_mime_types[25]['extension'] = '.arj';
		$this->_mime_types[26]['extension'] = '.avi';
		$this->_mime_types[27]['extension'] = '.bz;.bz2';
		$this->_mime_types[28]['extension'] = '.cab';
		$this->_mime_types[29]['extension'] = '.obj';
		$this->_mime_types[30]['extension'] = '.tar;.cpio';
		$this->_mime_types[31]['extension'] = '.cru;.crush';
		$this->_mime_types[32]['extension'] = '.dcx';
		$this->_mime_types[33]['extension'] = '.gz;.tar;.tgz';
		$this->_mime_types[34]['extension'] = '.hap';
		$this->_mime_types[35]['extension'] = '.htm;.html';
		$this->_mime_types[36]['extension'] = '.htm;.html';
		$this->_mime_types[37]['extension'] = '.htm;.html';
		$this->_mime_types[38]['extension'] = '.ico';
		$this->_mime_types[39]['extension'] = '.jar';
		$this->_mime_types[40]['extension'] = '.lha';
		$this->_mime_types[41]['extension'] = '.wk1;.wks';
		$this->_mime_types[42]['extension'] = '.fm3';
		$this->_mime_types[43]['extension'] = '.wk3';
		$this->_mime_types[44]['extension'] = '.fmt';
		$this->_mime_types[45]['extension'] = '.wk4';
		$this->_mime_types[46]['extension'] = '.ami';
		$this->_mime_types[47]['extension'] = '.adx';
		$this->_mime_types[48]['extension'] = '.nsf;.ntf';
		$this->_mime_types[49]['extension'] = '.ds4';
		$this->_mime_types[50]['extension'] = '.cab';
		$this->_mime_types[51]['extension'] = '.mid';
		$this->_mime_types[52]['extension'] = '.mpg;.mpeg';
		$this->_mime_types[53]['extension'] = '.xls';
		$this->_mime_types[54]['extension'] = '.xls';
		$this->_mime_types[55]['extension'] = '.doc';
		$this->_mime_types[56]['extension'] = '.doc';
		$this->_mime_types[57]['extension'] = '.doc';
		$this->_mime_types[58]['extension'] = '.nsf';
		$this->_mime_types[59]['extension'] = '.psp';
		$this->_mime_types[60]['extension'] = '.zip';
		$this->_mime_types[61]['extension'] = '.png';
		$this->_mime_types[62]['extension'] = '.mov';
		$this->_mime_types[63]['extension'] = '.qt';
		$this->_mime_types[64]['extension'] = '.rar';
		$this->_mime_types[65]['extension'] = '.ra;.ram';
		$this->_mime_types[66]['extension'] = '.rpm';
		$this->_mime_types[67]['extension'] = '.au';
		$this->_mime_types[68]['extension'] = '.sit';
		$this->_mime_types[69]['extension'] = '.sit';
		$this->_mime_types[70]['extension'] = '.z';
		$this->_mime_types[71]['extension'] = '.tif;.tiff';
		$this->_mime_types[72]['extension'] = '.tif;.tiff';
		$this->_mime_types[73]['extension'] = '.ufa';
		$this->_mime_types[74]['extension'] = '.wav';
		$this->_mime_types[75]['extension'] = '.wmf';
		$this->_mime_types[76]['extension'] = '.lnk';
		$this->_mime_types[77]['extension'] = '.zip';
		$this->_mime_types[78]['extension'] = '.wpg';
		$this->_mime_types[79]['extension'] = '.wp';
		$this->_mime_types[80]['extension'] = '.xml';
		$this->_mime_types[81]['extension'] = '.xml';
		$this->_mime_types[82]['extension'] = '.dtd';
		$this->_mime_types[83]['extension'] = '.zoo';
		$this->_mime_types[84]['extension'] = '.jpeg;.jpe;.jpg';
		$this->_mime_types[85]['extension'] = '.jpeg;.jpe;.jpg';
		$this->_mime_types[86]['extension'] = '.jpeg;.jpe;.jpg';
		$this->_mime_types[87]['extension'] = '.jpeg;.jpe;.jpg';
		$this->_mime_types[88]['extension'] = '.gif';

		// Descriptions
		$this->_mime_types[0]['description'] = 'GIF 87A';
		$this->_mime_types[1]['description'] = 'Windows Bitmap';
		$this->_mime_types[2]['description'] = 'Executable File ';
		$this->_mime_types[3]['description'] = 'Zip Compressed';
		$this->_mime_types[4]['description'] = 'MS Compound Document v1 or Lotus Approach APR file';
		$this->_mime_types[5]['description'] = 'xtended (Enhanced) Windows Metafile Format';
		$this->_mime_types[6]['description'] = 'Windows NT/2000 Event Viewer Log File';
		$this->_mime_types[7]['description'] = 'Windows Help File';
		$this->_mime_types[8]['description'] = 'GZ Compressed File';
		$this->_mime_types[9]['description'] = 'Macintosh BinHex 4 Compressed Archive';
		$this->_mime_types[10]['description'] = 'Icon File';
		$this->_mime_types[11]['description'] = 'Windows Link File';
		$this->_mime_types[12]['description'] = 'Adobe PDF File';
		$this->_mime_types[13]['description'] = 'Registry Data File';
		$this->_mime_types[14]['description'] = 'Rich Text Format File';
		$this->_mime_types[15]['description'] = 'Lzh compression file';
		$this->_mime_types[16]['description'] = 'Musical Instrument Digital Interface MIDI-sequention Sound';
		$this->_mime_types[17]['description'] = 'PC Paintbrush Bitmap Graphic';
		$this->_mime_types[18]['description'] = 'Adobe EPS File';
		$this->_mime_types[19]['description'] = 'AIN Archive File';
		$this->_mime_types[20]['description'] = 'ARC/PKPAK Compressed 1';
		$this->_mime_types[21]['description'] = 'ARC/PKPAK Compressed 2';
		$this->_mime_types[22]['description'] = 'ARC/PKPAK Compressed 3';
		$this->_mime_types[23]['description'] = 'ARC/PKPAK Compressed 4';
		$this->_mime_types[24]['description'] = 'ARC/PKPAK Compressed 5';
		$this->_mime_types[25]['description'] = 'ARJ Compressed';
		$this->_mime_types[26]['description'] = 'Audio Video Interleave (AVI)';
		$this->_mime_types[27]['description'] = 'Bzip Archive';
		$this->_mime_types[28]['description'] = 'Cabinet File';
		$this->_mime_types[29]['description'] = 'Compiled Object Module';
		$this->_mime_types[30]['description'] = 'CPIO Archive File';
		$this->_mime_types[31]['description'] = 'CRUSH Archive File';
		$this->_mime_types[32]['description'] = 'DCX Graphic File';
		$this->_mime_types[33]['description'] = 'Gzip Archive File';
		$this->_mime_types[34]['description'] = 'HAP Archive File';
		$this->_mime_types[35]['description'] = 'HyperText Markup Language 1';
		$this->_mime_types[36]['description'] = 'HyperText Markup Language 2';
		$this->_mime_types[37]['description'] = 'HyperText Markup Language 3';
		$this->_mime_types[38]['description'] = 'ICON File';
		$this->_mime_types[39]['description'] = 'JAR Archive File';
		$this->_mime_types[40]['description'] = 'LHA Compressed';
		$this->_mime_types[41]['description'] = 'Lotus 123 v1 Worksheet';
		$this->_mime_types[42]['description'] = 'Lotus 123 v3 FMT file';
		$this->_mime_types[43]['description'] = 'Lotus 123 v3 Worksheet';
		$this->_mime_types[44]['description'] = 'Lotus 123 v4 FMT file';
		$this->_mime_types[45]['description'] = 'Lotus 123 v5';
		$this->_mime_types[46]['description'] = 'Lotus Ami Pro';
		$this->_mime_types[47]['description'] = 'Lotus Approach ADX file';
		$this->_mime_types[48]['description'] = 'Lotus Notes Database/Template';
		$this->_mime_types[49]['description'] = 'Micrografix Designer 4';
		$this->_mime_types[50]['description'] = 'Microsoft CAB File Format';
		$this->_mime_types[51]['description'] = 'Midi Audio File';
		$this->_mime_types[52]['description'] = 'MPEG Movie';
		$this->_mime_types[53]['description'] = 'MS Excel v2';
		$this->_mime_types[54]['description'] = 'MS Excel v4';
		$this->_mime_types[55]['description'] = 'MS Word';
		$this->_mime_types[56]['description'] = 'MS Word 6.0';
		$this->_mime_types[57]['description'] = 'MS Word for DOS 6.0';
		$this->_mime_types[58]['description'] = 'Notes Database';
		$this->_mime_types[59]['description'] = 'PaintShop Pro Image File';
		$this->_mime_types[60]['description'] = 'PKZIP Compressed';
		$this->_mime_types[61]['description'] = 'PNG Image File';
		$this->_mime_types[62]['description'] = 'QuickTime Movie';
		$this->_mime_types[63]['description'] = 'Quicktime Movie File';
		$this->_mime_types[64]['description'] = 'RAR Archive File';
		$this->_mime_types[65]['description'] = 'Real Audio File';
		$this->_mime_types[66]['description'] = 'RPM Archive File';
		$this->_mime_types[67]['description'] = 'SoundMachine Audio File';
		$this->_mime_types[68]['description'] = 'Stuffit v1 Archive File';
		$this->_mime_types[69]['description'] = 'Stuffit v5 Archive File';
		$this->_mime_types[70]['description'] = 'TAR Compressed Archive File';
		$this->_mime_types[71]['description'] = 'TIFF (Intel)';
		$this->_mime_types[72]['description'] = 'TIFF (Motorola)';
		$this->_mime_types[73]['description'] = 'UFA Archive File';
		$this->_mime_types[74]['description'] = 'Wave Files';
		$this->_mime_types[75]['description'] = 'Windows Meta File';
		$this->_mime_types[76]['description'] = 'Windows Shortcut (Link File)';
		$this->_mime_types[77]['description'] = 'WINZIP Compressed';
		$this->_mime_types[78]['description'] = 'WordPerfect Graphics';
		$this->_mime_types[79]['description'] = 'WordPerfect v5 or v6';
		$this->_mime_types[80]['description'] = 'XML Document';
		$this->_mime_types[81]['description'] = 'XML Document (ROOTSTUB)';
		$this->_mime_types[82]['description'] = 'XML DTD';
		$this->_mime_types[83]['description'] = 'ZOO Archive File';
		$this->_mime_types[84]['description'] = 'JPG Graphic File';
		$this->_mime_types[85]['description'] = 'JPG Graphic File';
		$this->_mime_types[86]['description'] = 'JPG Graphic File';
		$this->_mime_types[87]['description'] = 'JPG Graphic File';
		$this->_mime_types[88]['description'] = 'GIF 89A';

		// Mime descriptions
		$this->_mime_types[0]['mime_type'] = 'image/gif';
		$this->_mime_types[1]['mime_type'] = 'image/bmp';
		$this->_mime_types[2]['mime_type'] = '';
		$this->_mime_types[3]['mime_type'] = '';
		$this->_mime_types[4]['mime_type'] = '';
		$this->_mime_types[5]['mime_type'] = '';
		$this->_mime_types[6]['mime_type'] = '';
		$this->_mime_types[7]['mime_type'] = '';
		$this->_mime_types[8]['mime_type'] = '';
		$this->_mime_types[9]['mime_type'] = '';
		$this->_mime_types[10]['mime_type'] = '';
		$this->_mime_types[11]['mime_type'] = '';
		$this->_mime_types[12]['mime_type'] = 'application/pdf';
		$this->_mime_types[13]['mime_type'] = '';
		$this->_mime_types[14]['mime_type'] = '';
		$this->_mime_types[15]['mime_type'] = '';
		$this->_mime_types[16]['mime_type'] = '';
		$this->_mime_types[17]['mime_type'] = '';
		$this->_mime_types[18]['mime_type'] = '';
		$this->_mime_types[19]['mime_type'] = '';
		$this->_mime_types[20]['mime_type'] = '';
		$this->_mime_types[21]['mime_type'] = '';
		$this->_mime_types[22]['mime_type'] = '';
		$this->_mime_types[23]['mime_type'] = '';
		$this->_mime_types[24]['mime_type'] = '';
		$this->_mime_types[25]['mime_type'] = '';
		$this->_mime_types[26]['mime_type'] = '';
		$this->_mime_types[27]['mime_type'] = '';
		$this->_mime_types[28]['mime_type'] = '';
		$this->_mime_types[29]['mime_type'] = '';
		$this->_mime_types[30]['mime_type'] = '';
		$this->_mime_types[31]['mime_type'] = '';
		$this->_mime_types[32]['mime_type'] = '';
		$this->_mime_types[33]['mime_type'] = '';
		$this->_mime_types[34]['mime_type'] = '';
		$this->_mime_types[35]['mime_type'] = '';
		$this->_mime_types[36]['mime_type'] = '';
		$this->_mime_types[37]['mime_type'] = '';
		$this->_mime_types[38]['mime_type'] = '';
		$this->_mime_types[39]['mime_type'] = '';
		$this->_mime_types[40]['mime_type'] = '';
		$this->_mime_types[41]['mime_type'] = '';
		$this->_mime_types[42]['mime_type'] = '';
		$this->_mime_types[43]['mime_type'] = '';
		$this->_mime_types[44]['mime_type'] = '';
		$this->_mime_types[45]['mime_type'] = '';
		$this->_mime_types[46]['mime_type'] = '';
		$this->_mime_types[47]['mime_type'] = '';
		$this->_mime_types[48]['mime_type'] = '';
		$this->_mime_types[49]['mime_type'] = '';
		$this->_mime_types[50]['mime_type'] = '';
		$this->_mime_types[51]['mime_type'] = '';
		$this->_mime_types[52]['mime_type'] = '';
		$this->_mime_types[53]['mime_type'] = '';
		$this->_mime_types[54]['mime_type'] = '';
		$this->_mime_types[55]['mime_type'] = '';
		$this->_mime_types[56]['mime_type'] = '';
		$this->_mime_types[57]['mime_type'] = '';
		$this->_mime_types[58]['mime_type'] = '';
		$this->_mime_types[59]['mime_type'] = '';
		$this->_mime_types[60]['mime_type'] = '';
		$this->_mime_types[61]['mime_type'] = 'image/png';
		$this->_mime_types[62]['mime_type'] = '';
		$this->_mime_types[63]['mime_type'] = '';
		$this->_mime_types[64]['mime_type'] = '';
		$this->_mime_types[65]['mime_type'] = '';
		$this->_mime_types[66]['mime_type'] = '';
		$this->_mime_types[67]['mime_type'] = '';
		$this->_mime_types[68]['mime_type'] = '';
		$this->_mime_types[69]['mime_type'] = '';
		$this->_mime_types[70]['mime_type'] = '';
		$this->_mime_types[81]['mime_type'] = '';
		$this->_mime_types[82]['mime_type'] = '';
		$this->_mime_types[83]['mime_type'] = '';
		$this->_mime_types[84]['mime_type'] = 'image/jpeg';
		$this->_mime_types[85]['mime_type'] = 'image/jpeg';
		$this->_mime_types[86]['mime_type'] = 'image/jpeg';
		$this->_mime_types[87]['mime_type'] = 'image/jpeg';
		$this->_mime_types[88]['mime_type'] = 'image/gif';
	}

	/**
	 * Load known image types
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		void
	 * @since 		3.0
	 */
	private function loadImageTypes() {
		$this->_image_types[0]['mime_type'] = 'image/gif';
		$this->_image_types[1]['mime_type'] = 'image/bmp';
		$this->_image_types[2]['mime_type'] = 'image/png';
		$this->_image_types[3]['mime_type'] = 'image/jpeg';
		$this->_image_types[4]['mime_type'] = 'image/jpeg';
		$this->_image_types[5]['mime_type'] = 'image/gif';
	}

	/**
	 * Convert/Resize an image
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		array	$thumb_file_details	contains all the variables for creating a new image
	 * @return 		mixed	filename of created file if file has been created / false if file has not been created
	 * @since 		3.0
	 */
	public function convertImage($file_details) {
		// Set all details
		foreach ($file_details as $type => $value) {
			switch ($type) {
				case 'maxsize':
					if ($value) $this->maxSize = true;
					else $this->maxSize = false;
					break;
				case 'bgred':
					if ($file_details['bgred'] >= 0 || $file_details['bgred'] <= 255) $this->bg_red = $file_details['bgred'];
					else $this->bg_red = 0;
					break;
				case 'bggreen':
					if($file_details['bggreen'] >= 0 || $file_details['bggreen'] <= 255) $this->bg_green = $file_details['bggreen'];
					else $this->bg_green = 0;
					break;
				case 'bgblue':
					if($file_details['bgblue'] >= 0 || $file_details['bgblue'] <= 255) $this->bg_blue = $file_details['bgblue'];
					else $this->bg_blue = 0;
					break;
				default:
					$this->$type = $value;
					break;
			}
		}
		if ($this->newImgCreate()) {
			return $this->file_out;
		}
		else return false;
	}

	/**
	 * Start creating the new image
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return 		boolean	true on success | false on failure
	 * @since 		3.0
	 */
	private function newImgCreate() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Clear the cache
		clearstatcache();

		switch(strtolower($this->mime_type)) {
			case 'image/gif':
				if( function_exists('imagecreatefromgif') ) {
					$orig_img = @imagecreatefromgif($this->file);
					break;
				}
				else return false;
			case 'image/jpg':
			case 'image/jpeg':
				if (function_exists('imagecreatefromjpeg')) {
					$orig_img = @imagecreatefromjpeg($this->file);
					break;
				}
				else {
					return false;
				}
				break;
			case 'image/png':
				if( function_exists('imagecreatefrompng') ) {
					$orig_img = @imagecreatefrompng($this->file);
					break;
				}
				else return false;
				break;
			default:
				return false;
				break;
		}
		if ($orig_img) {
			$csvilog->addDebug(JText::_('COM_CSVI_SAVING_NEW_IMAGE'));
			// Save the new image
			$img_resize = $this->NewImgSave($this->NewImgResize($orig_img));
			// Clean up old image
			ImageDestroy($orig_img);
		}
		else {
			$csvilog->addDebug(JText::_('COM_CSVI_CANNOT_READ_ORIGINAL_IMAGE'));
			$img_resize = false;
		}

		if ($img_resize) return true;
		else return false;
	}

	/**
	 * Resize the image
	 *
	 * Includes function ImageCreateTrueColor and ImageCopyResampled which are available only under GD 2.0.1 or higher !
	 *
	 * @copyright
	 * @author		RolandD
	 * @todo		Fix docbloc
	 * @see
	 * @access 		private
	 * @param 		$orig_img
	 * @return
	 * @since 		3.0
	 */
	private function NewImgResize($orig_img) {
		$orig_size = getimagesize($this->file);

		$maxX = $this->file_out_width;
		$maxY = $this->file_out_height;

		if ($orig_size[0] < $orig_size[1]) {
			$this->file_out_width = $this->file_out_height* ($orig_size[0]/$orig_size[1]);
			$adjustX = ($maxX - $this->file_out_width)/2;
			$adjustY = 0;
		}
		else {
			$this->file_out_height = $this->file_out_width / ($orig_size[0]/$orig_size[1]);
			$adjustX = 0;
			$adjustY = ($maxY - $this->file_out_height)/2;
		}

		while ($this->file_out_width < 1 || $this->file_out_height < 1) {
			$this->file_out_width*= 2;
			$this->file_out_height*= 2;
		}

		// See if we need to create an image at maximum size
		if ($this->maxSize) {
			if (function_exists("imagecreatetruecolor")) $im_out = imagecreatetruecolor($maxX,$maxY);
			else $im_out = imagecreate($maxX,$maxY);

			if ($im_out) {
				// Need to image fill just in case image is transparent, don't always want black background
				$bgfill = imagecolorallocate( $im_out, $this->bg_red, $this->bg_green, $this->bg_blue );

				if (function_exists("imageAntiAlias")) imageAntiAlias($im_out,true);
				imagealphablending($im_out, false);

				if (function_exists("imagesavealpha")) imagesavealpha($im_out,true);

				if (function_exists( "imagecolorallocatealpha")) $transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);

				if (function_exists("imagecopyresampled")) ImageCopyResampled($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
				else ImageCopyResized($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
			}
			else return false;
		}
		else {
			if (function_exists("imagecreatetruecolor")) $im_out = ImageCreateTrueColor($this->file_out_width,$this->file_out_height);
			else $im_out = imagecreate($this->file_out_width,$this->file_out_height);

			if ($im_out) {
				if (function_exists("imageAntiAlias")) imageAntiAlias($im_out,true);
				imagealphablending($im_out, false);

				if (function_exists("imagesavealpha")) imagesavealpha($im_out,true);
				if (function_exists("imagecolorallocatealpha")) $transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);

				if (function_exists("imagecopyresampled")) ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
				else ImageCopyResized($im_out, $orig_img, 0, 0, 0, 0, $this->file_out_width, $this->file_out_height,$orig_size[0], $orig_size[1]);
			}
			else return false;
		}

		return $im_out;
	}

	/**
	 * Save the new image
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		Add check if destination folder exists
	 * @todo		Fix docbloc
	 * @see
	 * @access 		private
	 * @param 		$new_img
	 * @return
	 * @since 		3.0
	 */
	private function NewImgSave($new_img) {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		// Lets see if we need to rename the output file since we know the sizes
		switch(strtolower($this->file_out_extension)) {
			case "gif":
				if (strtolower(substr($this->file_out,strlen($this->file_out)-4,4)) != ".gif") $this->file_out .= ".gif";
				return @imagegif($new_img, $this->file_out);
				break;
			case "jpg":
				if (strtolower(substr($this->file_out,strlen($this->file_out)-4,4)) != ".jpg") $this->file_out .= ".jpg";
				return @imagejpeg($new_img, $this->file_out, 100);
				break;
			case "jpeg":
				if (strtolower(substr($this->file_out,strlen($this->file_out)-5,5)) != ".jpeg") $this->file_out .= ".jpeg";
				return @imagejpeg($new_img, $this->file_out, 100);
				break;
			case "png":
				if (strtolower(substr($this->file_out,strlen($this->file_out)-4,4)) != ".png") $this->file_out .= ".png";
				return @imagepng($new_img,$this->file_out);
				break;
			default:
				$csvilog->addDebug(JText::_('COM_CSVI_NO_FILE_EXTENSION'));
				return false;
				break;
		}
	}

	/**
	 * Process an image
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param 		string	$name	full path and name of the image
	 * @param		string	$path	the destination location of the image
	 * @param		string	$output_name	name of the output image
	 * @return
	 * @since 		3.0
	 */
	public function processImage($name, $output_path, $output_name=null) {
		// Set up variables
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);

		// Cleanup
		$base = JPath::clean(JPATH_SITE, '/');
		if (!empty($output_path)) $output_path = JPath::clean($output_path, '/');

		$this->_imagedata = array();
		$this->_imagedata['base'] = $base;
		if ($this->isRemote($name)) {
			$this->_imagedata['name'] = $name;
			$this->_imagedata['isremote'] = true;
		}
		else {
			$this->_imagedata['name'] = $base.'/'.JPath::clean($name, '/');
			$this->_imagedata['isremote'] = false;
		}
		$this->_imagedata['output_path'] = $output_path;
		$this->_imagedata['output_name'] = (empty($output_name)) ? basename($name) : $output_name;
		$this->_imagedata['extension'] = JFile::getExt($name);
		$this->_imagedata['exists'] = false;
		$this->_imagedata['isimage'] = false;
		$this->_imagedata['mime_type'] = null;

		// Load externals
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		// See if we need to handle a remote file
		if ($this->_imagedata['isremote']) {
			$csvilog->addDebug('Process remote file: '.$this->_imagedata['name']);
			if (CsviHelper::fileExistsRemote($this->_imagedata['name'])) {
				$this->_imagedata['exists'] = true;
				// Check if this is an image or not
				if ($this->isImage($this->_imagedata['name'], true)) {
					$this->_imagedata['isimage'] = true;
				}
			}
			else {
				$csvilog->addDebug('Remote file does not exist: '.$this->_imagedata['name']);
				$this->_imagedata['exists'] = false;
			}
		}
		else if (JFile::exists($this->_imagedata['name'])) {
			$csvilog->addDebug('Process file: '.$this->_imagedata['name']);
			$this->_imagedata['exists'] = true;
			// Check if this is an image or not
			if ($this->isImage($this->_imagedata['name'])) {
				$this->_imagedata['isimage'] = true;
			}
		}
		else {
			// File does not exist
			$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_FILE_NOT_FOUND', $this->_imagedata['name']));
			return $this->_imagedata;
		}

		// Process if it is an image
		if ($this->_imagedata['isimage']) {
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_PROCESS_IMAGE'));

			// Clean up the images first
			$this->_cleanupImage();

			// Convert the full image
			if ($this->_imagedata['convert']) $this->_convertImage();

			// Save the remote images on the server
			if ($this->_imagedata['isremote'] && $template->get('save_images_on_server', 'image')) {
				// Sanitize filename
				$this->_imagedata['output_name'] = $this->_cleanFilename($this->_imagedata['output_name']);
				$from = $this->_imagedata['name'];
				$to = $this->_imagedata['base'].'/'.$this->_imagedata['output_path'].$this->_imagedata['output_name'];
				$csvilog->addDebug('Store remote file on server '.$from.' --> '.$to);
				if (JFile::exists($to)) JFile::delete($to);
				JFile::move($from, $to);
			}
			// Remove temporary file
			else if ($this->_imagedata['isremote']) {
				JFile::delete($this->_imagedata['name']);
			}

			// Check if any images need to be renamed
			$this->_renameImage();

			// Check if the full image needs to be resized
			$this->_resizeFullimage();

			// Convert images
			$this->_imageTypeCheck();
		}
		else {
			if ($this->_imagedata['exists']) {
				$csvilog->addDebug('COM_CSVI_DEBUG_FILE_IS_NOT_IMAGE'.' '.$name);

			// Non image details
			$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_PROCESS_NON_IMAGE'));
			$this->collectFileDetails();
		}
		}

		return $this->_imagedata;
	}

	/**
	 * Check if a file is a remote file or not
	 *
	 * Remote images can be located on an HTTP location or an FTP location
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see 		processImage()
	 * @access 		private
	 * @param 		$path 	string	the full path to check
	 * @return		bool 	true if file is remote | false if file is not remote
	 * @since 		3.0
	 */
	public function isRemote($path) {
		if (substr(strtolower($path), 0, 4) == 'http') return true;
		else if (substr(strtolower($path), 0, 3) == 'ftp') return true;
		else return false;
	}

	/**
	 * Collect file details for non-image files
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param
	 * @return
	 * @since 		2.3.10
	 */
	public function collectFileDetails() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$this->_imagedata['mime_type'] = $this->findMimeType($this->_imagedata['name']);
		$this->_imagedata['isimage'] = 0;
	}

	/**
	 * Create a thumbnail image
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		public
	 * @param		string 	$original		the full path and name of the large image
	 * @param		string	$output_path	the path to store the thumbnail
	 * @param		string	$output_name	the name of the thumbnail
	 * @return
	 * @since 		4.0
	 */
	public function createThumbnail($original, $output_path, $output_name) {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);
		$base = JPath::clean(JPATH_SITE, '/');

		// Make sure the thumbnail is the same file type as the full image
		if ($template->get('thumb_check_filetype', 'image') && JFile::getExt($original) != JFile::getExt($output_name)) {
			$output_name = JFile::stripExt($output_name).'.'.JFile::getExt($original);
		}

		$output_name = $this->_setCase($output_name);

		// Check if the original is an external image
		if (!$this->isRemote($original)) {
			$original = $base.'/'.$original;
			$file_exists = JFile::exists($original);
			$remote = false;
		}
		else {
			$file_exists = CsviHelper::fileExistsRemote($original);
			$remote = true;
		}


		// Check if thumbsize is greater than 0
		if ($template->get('thumb_width', 'image') >= 1 && $template->get('thumb_height', 'image') >= 1) {
			// Check if the image folders exists
			$thumb_folder = JPATH_SITE.'/'.$output_path.dirname($output_name);
			if (!JFolder::exists($thumb_folder)) {
				$csvilog->addDebug(JText::sprintf('COM_CSVI_CREATE_THUMB_FOLDER', $thumb_folder));
				JFolder::create($thumb_folder);
			}
			// Check if the target thumb exists, if yes delete it
			if (JFile::exists($base.'/'.$output_path.$output_name)) JFile::delete($base.'/'.$output_path.$output_name);
			// Check if the original file exists
			$csvilog->addDebug(JText::sprintf('COM_CSVI_CHECK_ORIGINAL', $original));
			if ($file_exists) {
				// Collect all thumbnail details
				$thumb_file_details = array();
				$thumb_file_details['file'] = $original;
				$thumb_file_details['file_extension'] = JFile::getExt($original);
				$thumb_file_details['file_out'] = $base.'/'.$output_path.$output_name;
				$thumb_file_details['maxsize'] = 0;
				$thumb_file_details['bgred'] = 255;
				$thumb_file_details['bggreen'] = 255;
				$thumb_file_details['bgblue'] = 255;
				$thumb_file_details['file_out_width'] = $template->get('thumb_width', 'image');
				$thumb_file_details['file_out_height'] = $template->get('thumb_height', 'image');
				$thumb_file_details['file_out_extension'] = JFile::getExt($output_name);
				$thumb_file_details['mime_type'] = $this->findMimeType($original, $remote);

				// We need to resize the image and Save the new one (all done in the constructor)
				$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_CREATING_A_THUMBNAIL', $original, $thumb_file_details['file_out']));
				$new_img = $this->convertImage($thumb_file_details);

				// Check if an image was created
				if ($new_img) {
					// Get the details of the thumb image
					if (JFile::exists($new_img)) {
						$csvilog->addDebug(JText::_('COM_CSVI_DEBUG_THUMB_CREATED'));
						return $output_path.$output_name;
					}
					else {
						$csvilog->addDebug(JText::_('COM_CSVI_THUMBNAIL_NOT_CREATED'));
						return false;
					}
				}
				else {
					$csvilog->addDebug(JText::_('COM_CSVI_THUMBNAIL_NOT_CREATED'));
					return false;
				}
			}
			else {
				$csvilog->addDebug(JText::sprintf('COM_CSVI_FILE_DOES_NOT_EXIST_NOTHING_TO_DO', $original));
				$csvilog->AddStats('nofiles', JText::sprintf('COM_CSVI_FILE_DOES_NOT_EXIST_NOTHING_TO_DO', $original));
				return false;
			}
		}
		else {
			$csvilog->addDebug(JText::_('COM_CSVI_THUMBNAIL_SIZE_TOO_SMALL'));
			$csvilog->AddStats('incorrect', JText::_('COM_CSVI_THUMBNAIL_SIZE_TOO_SMALL'));
			return false;
		}
	}
	/**
	 * Clean up the full image
	 *
	 * Clean up the image from any incorrect paths
	 *
	 * Minimum requirement is PHP 5.2.0
	 *
	 * [full_image] => Array
     *  (
     *      [isremote] => 1
     *      [exists] => 1
     *      [isimage] => 1
     *      [name] => R05-01 -- R05-01 (700).jpg
     *      [filename] => R05-01 -- R05-01 (700)
     *      [extension] => jpg
     *      [folder] => http://csvi3
     *      [output_name] => R05-01 -- R05-01 (700).jpg
     *      [output_filename] => R05-01 -- R05-01 (700)
     *      [output_extension] => jpg
     *      [output_folder] => http://csvi3
     *      [mime_type] => image/jpeg
     *  )
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo		handle image paths included in the image name
	 * @todo		not delete the old image, it might be referenced by another product
	 * @see 		http://www.php.net/pathinfo
	 * @access 		private
	 * @param
	 * @return
	 * @since 		3.0
	 */
	private function _cleanupImage() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		if ($this->_imagedata['isremote'] && $template->get('save_images_on_server', 'image')) {
			// Collect remote file information
			$local_image = CSVIPATH_TMP.'/'.$this->_cleanFilename(basename($this->_imagedata['name']));

			// Store the remote image
			if ($this->_storeRemoteImage($this->_imagedata['name'], $local_image)) {
				$csvilog->addDebug('Remote file stored: '.$this->_imagedata['name'].' --> '.$local_image);
				// Update full image information
				$this->_imagedata['name'] = $local_image;

				// Get the mime type
				$mime_type = $this->findMimeType($local_image);
			}
			else {
				$csvilog->AddStats('nofiles', JText::sprintf('COM_CSVI_REMOTE_FILE_NOT_FOUND', $this->_imagedata['name']));
				$csvilog->addDebug(JText::sprintf('COM_CSVI_REMOTE_FILE_NOT_FOUND', $this->_imagedata['name']));
			}
		}
		else if ($this->_imagedata['isremote']) {
			$mime_type = $this->findMimeType($this->_imagedata['name'], true);
			$this->_imagedata['output_path'] = dirname($this->_imagedata['name']).'/';
		}
		else if (!$this->_imagedata['isremote']) {
			$mime_type = $this->findMimeType($this->_imagedata['name']);
		}

		// Set the mime type
		$csvilog->addDebug('Mime type found: '.$mime_type);
		$this->_imagedata['mime_type'] = $mime_type;

		// Validate extension against mime type
		$type = '';
		$ext = '';
		list($type, $ext) = explode('/', $mime_type);
		if ($ext == 'jpeg') $ext = 'jpg';
		// Get the extension of the target image name
		$output_ext = JFile::getExt($this->_imagedata['output_name']);
		if ($ext != strtolower($output_ext)) {
			// Fix up the new names
			$basename = basename($this->_imagedata['name'], $this->_imagedata['extension']);
			$to = dirname($this->_imagedata['name']).'/'.$basename.$ext;

			// Set the new output name
			//$this->_imagedata['output_name'] = JFile::stripExt($this->_imagedata['name']).$ext;

			$csvilog->addDebug('Renaming full image because bad extension: '.$this->_imagedata['name'].' --> '.$to);

			// Rename the file
			if (JFile::exists($this->_imagedata['name'])) {
				if (!JFile::move($this->_imagedata['name'], $to)) return false;
				else {
					$this->_imagedata['name'] = $to;
				}
			}
		}

		// Check for a valid extenion
		if (empty($this->_imagedata['extension']) && $type == 'image') {
			$this->_imagedata['extension'] = $ext;
		}

		// Set a new extension if the image needs to be converted
		$convert_type = $template->get('convert_type', 'image');
		if ($convert_type != 'none' && $convert_type != $this->_imagedata['extension']) {
			$this->_imagedata['output_name'] = JFile::stripExt(basename($this->_imagedata['name'])).'.'.$convert_type;
			$this->_imagedata['convert'] = true;
		}
		else $this->_imagedata['convert'] = false;

		// Set the file case
		$this->_imagedata['output_name'] = $this->_setCase($this->_imagedata['output_name']);

		// Add some debug info
		$csvilog->addDebug('Full name original: '.$this->_imagedata['name']);
		$csvilog->addDebug('Full name target: '.$this->_imagedata['output_path'].$this->_imagedata['output_name']);
	}


	/**
	 * Store a remote image on the local server
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		$remote_image 	string	the url of the remote image
	 * @param 		$local_image	string	the full path and file name of the image to store
	 * @return 		bool true if remote file was locally written | false if remote file was not locally written
	 * @since
	 */
	private function _storeRemoteImage($remote_image, $local_image) {
		return JFile::write($local_image, JFile::read($remote_image));
	}

	/**
	 * Convert the full image to another type
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
	private function _convertImage() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		// Let's see if the extensions are the same
		if ($this->_imagedata['convert'] && !$this->_imagedata['isremote']) {
			// Collect the image details
			$file_details = array();
			$file_details['file'] = $this->_imagedata['name'];
			$file_details['file_extension'] = JFile::getExt($this->_imagedata['name']);
			$file_details['file_out'] = $this->_imagedata['base'].$this->_imagedata['output_path'].'/'.$this->_imagedata['output_name'];
			$file_details['maxsize'] = 0;
			$file_details['bgred'] = 255;
			$file_details['bggreen'] = 255;
			$file_details['bgblue'] = 255;
			$new_sizes = getimagesize($this->_imagedata['name']);
			$file_details['file_out_width'] = $new_sizes[0];
			$file_details['file_out_height'] = $new_sizes[1];
			$file_details['file_out_extension'] = JFile::getExt($this->_imagedata['output_name']);
			$file_details['mime_type'] = $this->_imagedata['mime_type'];

			// We need to resize the image and Save the new one (all done in the constructor)
			$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_CONVERT_IMAGE', $file_details['file'], $file_details['file_out']));
			$new_img = $this->convertImage($file_details);
			if ($new_img) {
				$csvilog->addDebug(JText::sprintf('COM_CSVI_IMAGE_CONVERTED', $file_details['file']));
				// See if we need to keep the old image
				if (!$template->get('keep_original', 'image') && JFile::exists($file_details['file'])) JFile::delete($file_details['file']);
				// We have a new name, so refresh the info
				$this->_imagedata['name'] = dirname($this->_imagedata['name']).'/'.$this->_imagedata['output_name'];
				$this->_imagedata['mime_type'] = $this->findMimeType($this->_imagedata['base'].$this->_imagedata['output_path'].'/'.$this->_imagedata['output_name']);
				return true;
			}
			else {
				$csvilog->addDebug(JText::_('COM_CSVI_IMAGE_NOT_CONVERTED'));
				return false;
			}
		}
	}



	/**
	 * Rename image
	 *
	 * Rename an image, any existing file will be deleted
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
	private function _renameImage() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		if (!$this->_imagedata['isremote']
			&& $template->get('auto_generate_image_name', 'image')
			&& (basename($this->_imagedata['name']) != $this->_imagedata['output_name'])
			&& $template->get('convert_type', 'image') == 'none') {
			$from = $this->_imagedata['name'];
			if (JFile::exists($from)) {
				$to = $this->_imagedata['base'].'/'.$this->_imagedata['output_path'].$this->_imagedata['output_name'];
				$csvilog->addDebug(JText::sprintf('COM_CSVI_RENAME_FULL_FILE', $from, $to));
				// Delete existing target image
				if (JFile::exists($to)) JFile::delete($to);

				// Check if the user wants to keep the original
				if ($template->get('keep_original', 'image')) {
					// Rename the image
					JFile::copy($from, $to);
				}
				else {
					// Rename the image
					JFile::move($from, $to);
				}
			}
			else {
				$csvilog->addDebug(JText::sprintf('COM_CSVI_RENAME_FULL_FILE_NOT_FOUND', $from));
			}
		}
	}

	/**
	 * Check if we need to convert the final image based on mime type
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param
	 * @return
	 * @since 		2.3.7
	 */
	private function _imageTypeCheck() {
		$jinput = JFactory::getApplication()->input;
		$csvilog = $jinput->get('csvilog', null, null);
		$template = $jinput->get('template', null, null);

		// Get the output mime-type
		$output_ext = JFile::getExt($this->_imagedata['output_name']);

		// Check if the mime-type is different and if so, convert image
		if (!$this->_imagedata['isremote'] && JFile::exists($this->_imagedata['name']) && !stristr($this->_imagedata['mime_type'], $output_ext)) {
			$file_details = array();
			$file_details['file'] = $this->_imagedata['name'];
			$file_details['file_extension'] = JFile::getExt($this->_imagedata['name']);
			$file_details['maxsize'] = 0;
			$file_details['bgred'] = 255;
			$file_details['bggreen'] = 255;
			$file_details['bgblue'] = 255;
			$file_details['file_out'] = $this->_imagedata['base'].'/'.$this->_imagedata['output_path'].$this->_imagedata['output_name'];
			$new_sizes = getimagesize($this->_imagedata['name']);
			$file_details['file_out_width'] = $new_sizes[0];
			$file_details['file_out_height'] = $new_sizes[1];
			$file_details['file_out_extension'] = $output_ext;
			$file_details['mime_type'] = $this->_imagedata['mime_type'];

			/* We need to resize the image and Save the new one (all done in the constructor) */
			$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_CONVERT_TYPE_CHECK', $file_details['file'], $file_details['file_out']));
			$new_img = $this->convertImage($file_details);

			if ($new_img) $csvilog->addDebug(JText::sprintf('COM_CSVI_IMAGE_CONVERTED', $file_details['file']));
			else $csvilog->addDebug(JText::sprintf('COM_CSVI_IMAGE_NOT_CONVERTED', $file_details['file']));
		}
		// We have a remote image, update the mime type since we can't convert images on remote servers
		else if ($this->_imagedata['isremote']) {
			$mime_type = $this->findMimeType($this->_imagedata['output_path'].$this->_imagedata['output_name'], true);
			if ($mime_type) $this->_imagedata['mime_type'] = $mime_type;
			else $csvilog->addDebug(JText::_('COM_CSVI_CANNOT_FIND_REMOTE_MIMETYPE'));
		}
	}

	/**
	 * Clean filename
	 *
	 * Cleans up a filename and replaces non-supported characters with an underscore
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		$value	string	the value to clean
	 * @return 		string	the cleaned up value
	 * @since 		3.0
	 */
	private function _cleanFilename($value) {
		return (string) preg_replace('/[^A-Z0-9_\.-\s]/i', '_', $value);
	}

	/**
	 * Change the case of any given string
	 *
	 * @copyright
	 * @author 		RolandD
	 * @todo
	 * @see
	 * @access 		private
	 * @param 		string	$name	the string to be case changed
	 * @return 		string	the case changed string
	 * @since 		3.0
	 */
	private function _setCase($name) {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		// Set the case if needed
		switch ($template->get('change_case', 'image')) {
			case 'lcase':
				return strtolower($name);
				break;
			case 'ucase':
				return strtoupper($name);
				break;
			case 'ucfirst':
				return ucfirst($name);
				break;
			case 'ucwords':
				return ucwords($name);
				break;
			default:
				return $name;
				break;
		}
	}

	/**
	 * Resize a large image
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
	private function _resizeFullImage() {
		$jinput = JFactory::getApplication()->input;
		$template = $jinput->get('template', null, null);
		$csvilog = $jinput->get('csvilog', null, null);

		// Check if we need to resize the full image
		if ($template->get('full_resize', 'image')) {
			// Get the current size
			$checkfile = $this->_imagedata['base'].'/'.$this->_imagedata['output_path'].$this->_imagedata['output_name'];
			if (JFile::exists($checkfile)) {
				$cur_size = getimagesize($checkfile);
				if ($cur_size[0] > $template->get('full_width', 'image') || $cur_size[1] > $template->get('full_height', 'image')) {
					// Resize the image
					$file_details = array();
					$file_details['file'] = $checkfile;
					$file_details['file_extension'] = JFile::getExt($checkfile);
					$file_details['rename'] = 0;
					$file_details['file_out'] = $checkfile;
					$file_details['maxsize'] = 0;
					$file_details['bgred'] = 255;
					$file_details['bggreen'] = 255;
					$file_details['bgblue'] = 255;
					$file_details['file_out_width'] = $template->get('full_width', 'image');
					$file_details['file_out_height'] = $template->get('full_height', 'image');
					$file_details['file_out_extension'] = JFile::getExt($checkfile);
					$file_details['mime_type'] = $this->_imagedata['mime_type'];

					// We need to resize the image and Save the new one (all done in the constructor)
					$csvilog->addDebug(JText::sprintf('COM_CSVI_DEBUG_RESIZE_IMAGE', $file_details['file'], $cur_size[1].'x'.$cur_size[0], $template->get('full_height', 'image').'x'.$template->get('full_width', 'image')));
					$new_img = $this->convertImage($file_details);

					if ($new_img) $csvilog->addDebug(JText::_('COM_CSVI_FULL_IMAGE_RESIZED'));
				}
			}
		}
	}
}
?>
