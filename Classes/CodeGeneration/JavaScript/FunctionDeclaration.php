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
 * Representing the declaration of a function
 * 
 * function($parameters as csv) {
 * 		$content (some JS code)
 * }
 * 
 * Use the flags $inline and $brackets to get "()" and/or ";" added at the end of the declaration
 *
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionDeclaration implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface {

	/**
	 * @var array
	 */
	protected $parameters;
	
	/**
	 * The js code inside the function
	 * 
	 * @var array
	 */
	protected $content;

	/**
	 * If the declaration is inline we don't need a ending ";"
	 * 
	 * @var boolean
	 */
	protected $inline;

	/**
	 * !discussionable!
	 * Sometimes (don't know why - not as skilled in JS ;-) we need those brackets "()" at the end of the definition.
	 * Found this in Ext.onReady() declarations.
	 * 
	 * @var boolean
	 */
	protected $brackets;

	/**
	 * Default constructor.
	 * 
	 * @param array $parameters
	 * @param array $content
	 * @param boolean $inline
	 * @param boolean $brackets
	 */
	public function __construct(array $parameters = array(), array $content = array(), $inline = FALSE, $brackets = FALSE) {
		foreach ($parameters as $parameter) {
			if (!is_string($parameter)) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('A parameter to be of type string', 1264859988);
			}
		}

		$this->parameters = $parameters;
		foreach ($content as $snippet)
			if (!$snippet instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface)
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a content element has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface',1264859988);
		$this->content = $content;
		$this->inline = $inline;
		$this->brackets = $brackets;
	}

	/**
	 * Sets all parameters for the function.
	 * 
	 * @param array $parameters
	 * @return void
	 */
	public function setParameters($parameters) {
		foreach ($parameters as $parameter) {
			if (!is_string($parameter)) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a parameter has to be of type string',1264859988);
			}
		}
		$this->parameters = $parameters;
	}

	/**
	 * Adds a parameter to the function
	 * 
	 * @param string $parameter
	 * @return void
	 */
	public function addParameter($parameter) {
		$this->parameters[] = $parameter;
	}
	
	/**
	 * Gets an array with all parameters
	 * 
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}
	
	/**
	 * Sets all parameters for the function
	 * 
	 * @param string $parameters
	 * @return void
	 */
	public function setContent($content) {
		foreach ($content as $snippet) {
			if (!$snippet instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a content element has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface',1264859988);
			}
		}
		$this->content = $content;
	}
	
	/**
	 * Adds a parameter to the function
	 * 
	 * @param string $parameter
	 * @return void
	 */
	public function addSnippet($snippet) {
		$this->content[] = $snippet;
	}
	
	/**
	 * Gets an array with all parameters
	 * 
	 * @return array
	 */
	public function getContent() {
		return $this->content;
	}
	
	/**
	 * @see Classes/CodeGeneration/JavaScript/Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface#build()
	 */
	public function build() {
		$js = 'function(';
		foreach ($this->parameters as $parameter) {
			$js .= $parameter . ',';
		}
		if (count($this->parameters) > 0) {
			$js = substr($js,0,-1);
		}
		$js .= ') {' . "\n";
		foreach ($this->content as $snippet) {
			$js .= $snippet->build();
		}
		$js .= '}';
		if ($this->brackets) $js .= '()';
		if (!$this->inline) $js .= ';';
		return $js;
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