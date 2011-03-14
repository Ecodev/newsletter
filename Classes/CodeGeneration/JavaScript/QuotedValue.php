<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dennis Ahrens <dennis.ahrens@googlemail.com>
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
 * JavaScript Code Snippet
 * Representing a quoted value (a string in JavaScript)
 *
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_QuotedValue implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface {

	/**
	 * @var string
	 */
	protected $value;

	/**
	 * Whether the output should be quoted with " instead of '
	 * @var boolean
	 */
	protected $doubleQuote;

	/**
	 * Default constructor.
	 * 
	 * @param string $value
	 * @param boolean $doubleQuote
	 */
	public function __construct($value, $doubleQuote = FALSE) {
		$this->value = $value;
		$this->doubleQuote = $doubleQuote;
	}

	/**
	 * Sets the value.
	 * 
	 * @param string $value
	 * @return void
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * Gets the value.
	 * 
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @see Classes/CodeGeneration/JavaScript/Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface#build()
	 */
	public function build() {
		if (!$this->doubleQuote) {
			return '\'' . addslashes($this->value) . '\'';
		} else {
			return '"' . addslashes($this->value) . '"';
		}
	}

	/**
	 * Wraps build() as __toString()
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->build();
	}

}

?>