<?php
/*************************************************************** 
*  Copyright notice 
* 
*  (c) 2006-2008 Daniel Schledermann <daniel@schledermann.net> 
*  All rights reserved 
* 
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify 
*  it under the terms of the GNU General Public License as published by 
*  the Free Software Foundation; either version 2 of the License, or 
*  (at your option) any later version. 
* 
*  The GNU General Public License can be found at 
*  http://www.gnu.org/copyleft/gpl.html. 
* 
*  This script is distributed in the hope that it will be useful, 
*  but WITHOUT ANY WARRANTY; without even the implied warranty of 
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
*  GNU General Public License for more details. 
* 
*  This copyright notice MUST APPEAR in all copies of the script! 
***************************************************************/


/**
 * Base class for construction plaintext for mails.
 *
 * @abstract
 */

class tx_tcdirectmail_plain {
	/**
	 * Indicate how the class handles html-content. Can be either "src" og "url"
	 * "src" indicates that you wish to supply the html-code in the parameter.
	 * "url" indicates that you wish to provide a link the the html-code.
	 */

	var $fetchMethod = 'src'; /* Can be either "src" og "url" */

	/**
	 * Factory for plaintext converter objects.
	 *
	 * @static
	 * @param   array      Page record
	 * @param   string      Base url, if any, used in the plaintext.
	 * @return   object      plain text object to use.
	 */
	function loadPlain($pageRecord, $baseUrl) {
		$obj = new $pageRecord['tx_tcdirectmail_plainconvert'];

		if (is_subclass_of($obj, 'tx_tcdirectmail_plain')) {
			$obj->record = $pageRecord;
			$obj->baseUrl = $baseUrl;

			return $obj;
		} else {
			die ("$class is not a subclass of tx_tcdirectmail_plain");
		}
	}

	/**
	* Apply html to the plaintext converter.
	*
	* @param   string      Html to convert.
	* @return   void
	*/
	function setHtml($var) {
		die ('Implement setHtml-method');
	}

	/**
	* Get the plaintext
	*
	* @return   string      The converted text.
	*/
	function getPlainText() {
		return $this->plainText;
	}
}

?>
