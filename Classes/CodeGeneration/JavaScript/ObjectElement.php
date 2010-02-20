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
 * Representing a element of a JavaScript object
 * 
 * f.e.
 * name: 'value'
 * or
 * name: function(param1,param2) { some.jscode(); }
 * 
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_ObjectElement implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface {
	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface
	 */
	protected $value;
	
	/**
	 * Default constructor
	 * 
	 * @param string $name
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $value
	 */
	public function __construct($name = NULL, Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $value = NULL) {
		$this->name = $name;
		$this->value = $value;
	}
	
	/**
	 * Sets the name
	 * 
	 * @param $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Gets the name
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;	
	}
	
	/**
	 * Sets the value
	 * 
	 * @param Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $value
	 * @return void
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * Gets the value
	 * 
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * @see Classes/CodeGeneration/JavaScript/Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface#build()
	 */
	public function build() {
		if (!is_string($this->name) || $this->name == '') {
			throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('building a object element without a name will cause errors in javascript',1264954481);
		}
		$js = '';
		$js .= $this->name . ': ' . $this->value->build();
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