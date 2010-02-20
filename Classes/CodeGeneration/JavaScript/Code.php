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
 * JavaScript Code Object
 * Every kind of JavaScript-Code can be added here
 *
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_Code implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface {

	/**
	 * @var mixed string or FALSE
	 */
	protected $namespace;

	/**
	 * A set of code snippets that are assigned to the code.
	 * 
	 * @var array
	 */
	protected $snippets;

	/**
	 * Default constructor.
	 * 
	 * @param mixed $namespace
	 */
	public function __construct($namespace = FALSE) {
		$this->snippets = array();
		if ($namespace !== FALSE && !is_string($namespace)) {
			throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('The namespace for a JavaScript code must be FALSE or of type string', 1264865353);
		}
		$this->namespace = $namespace;
	}

	/**
	 * Adds a code snippet to the code.
	 * 
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $snippet
	 * @return void
	 */
	public function addSnippet(Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $snippet) {
		$this->snippets[] = $snippet;
	}

	/**
	 * Builds up a string containing the js code
	 * 
	 * @return string js code
	 */
	public function build() {
		$js = ($this->namespace) ? 'Ext.ns(\'' . $this->namespace . '\');' . "\n" : '';
		foreach ($this->snippets as $snippet) {
			$js .= $snippet->build() . "\n";
		}
		return $js;
	}

	/**
	 * Wraps build() as __toString().
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->build();
	}

}

?>