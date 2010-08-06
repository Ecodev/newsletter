<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Fabien Udriot <fabien.udriot@ecodev.ch>
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
 * A ViewHelper which returns its input as a json-encoded string.
 *
 * = Examples =
 * 
 * <f:json>{anyArray}</f:json>
 * 
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_Newsletter
 * @author      Fabien Udriot <fabien.udriot@ecodev.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_Newsletter_ViewHelpers_JsonStoreViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Render a json-encoded string.
	 *
	 * @param string $tableName: the model name to be given, will be used
	 *							 for fetching information about the fields' type (int, string, etc...)
	 * @param array $metaData: give a list of fields as well as information about the field type like int, string, date ...
	 * @param boolean $indent: should the output be well formatted
	 * @return string 
	 */
	public function render($tableName = '', $metaData = array(), $indent = FALSE) {
		$items = $this->renderChildren();

		// Finds out keys finishing by "_formatted"...
		// and deduct the view helper name
		$viewHelpers = array();
		if (count($items) > 0) {
			$keys = array_keys($items[0]);
			foreach ($keys as $key) {
				if (strpos($key, '_formatted')) {
					$viewHelper = str_replace('_formatted', '', $key);
					$viewHelperParts = explode('_', $viewHelper);
					$viewHelperParts = array_map('ucfirst', $viewHelperParts);
					$viewHelpers[$key] = 'Tx_Newsletter_ViewHelpers_Format_' . implode('', $viewHelperParts) . 'ViewHelper';
				}
			}
		}

		// Loop around the found viewhelpers...
		// and calls the right view helper
		if (count($viewHelpers) > 0) {
			foreach ($viewHelpers as $keyName => $viewHelperName) {
				$objectHelper = t3lib_div::makeInstance($viewHelperName);
				foreach ($items as &$item) {
					$item[$keyName] = $objectHelper->render($item);
				}
			}
		}

		// Defines list of fields
		$datasource['metaData'] = $metaData;
		$datasource['total'] = count($items);
		$datasource['records'] = $items;
		$datasource['success'] = TRUE;
		$viewHelperName = 'debug';
		$json = json_encode($datasource);
		if ($indent) {
			$json = $this->indentJSON($json);
		}
		return $json;
	}

	/**
	 * Indent JSON code.
	 * Original version from http://recurser.com/articles/2008/03/11/format-json-with-php/
	 * Changes:
	 * - "$i < $strLen" instead of "$i <= $strLen"
	 * - Use $json as an Array instead of using substr()
	 * - Cache the current $indention for better performance
	 *  Check for escaped sequenzes ("Strings")
	 *  Parameters $indentStr and $newLine as arguments
	 *  Parameter $json as reference argument
	 */
	public  function indentJSON(& $json, $indentStr = "  ", $newLine = "\n") {
		$result     = "";    // Resulting string
		$indention  = "";    // Current indention after newline
		$pos        = 0;     // Indention width
		$escaped    = false; // FALSE or escape character
		$strLen     = strlen($json);

		for($i = 0; $i < $strLen; $i++) {
			// Grab the next character in the string
			$char = $json[$i];

			if ($escaped) {
				if ($escaped == $char) {
					// End of escaped sequence
					$escaped = false;
				}

				$result .= $char;
				if ($char == "\\" && $i + 1 < $strLen) {
					// Next character will NOT end this sequence
					$result .= $json[++$i];
				}

				continue;
			}

			if ($char == '"' || $char == "'") {
				// Escape this string
				$escaped = $char;
				$result .= $char;
				continue;
			}

			// If this character is the end of an element,
			// output a new line and indent the next line
			if($char == '}' || $char == ']') {
				$indention = str_repeat($indentStr, --$pos);
				$result .= $newLine . $indention;
			}

			// Add the character to the result string
			$result .= $char;

			// If the last character was the beginning of an element,
			// output a new line and indent the next line
			if ($char == ',' || $char == '{' || $char == '[') {
				if ($char == '{' || $char == '[') {
					$indention = str_repeat($indentStr, ++$pos);
				}
				$result .= $newLine . $indention;
			}
		}

		return $result;
	}

}
?>