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
 * Representing a JavaScript Object
 * Something sourrounded with {} and a bunch of elements inside, mostly something like name: value
 * Use ObjectElement to get the name: value notation
 * 
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_Object implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface {
	
	/**
	 * @var array
	 */
	protected $elements;
	
	/**
	 * Default constructor
	 * 
	 * @param array $elements
	 */
	public function __construct(array $elements = array()) {
		foreach ($elements as $element) {
			if (!$element instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a element has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface; given element is of type: ' . get_class($element),1264859989);
			}
		}
		$this->elements = $elements;
	}
	
	/**
	 * Sets the elements
	 * 
	 * @param array $elements
	 * @return void
	 */
	public function setElements($elements) {
		foreach ($elements as $element) {
			if (!$element instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
				throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a element has to implement Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface',1264859988);
			}
		}
		$this->elements = $elements;
	}
	
	/**
	 * Adds an element to the object
	 * 
	 * @param $element
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_Object
	 */
	public function addElement(Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface $element) {
		$this->elements[] = $element;
		return $this;
	}
	
	/**
	 * Gets the elements
	 * 
	 * @return array
	 */
	public function getElements() {
		return $this->elements;
	}
	
	/**
	 * @see Classes/CodeGeneration/JavaScript/Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface#build()
	 */
	public function build() {
		$js = '{';
		foreach ($this->elements as $element) {
			$js .= $element->build() . ', ';
		}
		if (count($this->elements) > 0) {
			$js = substr($js,0,-2);
		}
		$js .= '}';
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