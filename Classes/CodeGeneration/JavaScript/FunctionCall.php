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
 * Representing a Constructor call
 *
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionCall implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * Default Constructor
	 * 
	 * @param string $name
	 * @param array $parameters
	 */
	public function __construct($name = NULL, array $parameters = array()) {
		$this->name = $name;
		foreach ($parameters as $parameter) {
			if (!$parameter instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a parameter has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface',1264859988);
			}
		}
		$this->parameters = $parameters;
	}

	/**
	 * Sets the name of the function.
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Gets the name of the function.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets all parameters for the function.
	 * 
	 * @param array $parameters
	 * @return void
	 */
	public function setParameters(array $parameters) {
		foreach ($parameters as $parameter) {
			if (!$parameter instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('A parameter has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface', 1264859988);
			}
		}
		$this->parameters = $parameters;
	}

	/**
	 * Adds a parameter to the function.
	 * 
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $parameter
	 * @return void
	 */
	public function addParameter(Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $parameter) {
		$this->parameters[] = $parameter;
	}

	/**
	 * Gets the array containing all parameters.
	 * 
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @see Classes/CodeGeneration/JavaScript/Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface#build()
	 */
	public function build() {
		$js = $this->name . '(';
		foreach ($this->parameters as $parameter) {
			$js .= $parameter->build() . ',';
		}
		if (count($this->parameters) > 0) {
			$js = substr($js,0,-1);
		}
		$js .= ');' . "\n";
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