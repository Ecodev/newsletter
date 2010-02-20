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
 * Representing a config object for Extjs
 * Just a more handable version of the JavaScript Object Class
 *
 * @category    CodeGeneration_JavaScript
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config extends Tx_MvcExtjs_CodeGeneration_JavaScript_Object {
	
	/**
	 * Adds a config parameter
	 * The given value will be single quoted
	 * Returns itself to allow method-chaining
	 * 
	 * @param string $name
	 * @param string $value
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	public function set($name,$value) {
		$configElement = new Tx_MvcExtjs_CodeGeneration_JavaScript_ObjectElement($name,new Tx_MvcExtjs_CodeGeneration_JavaScript_QuotedValue($value));
		$this->addElement($configElement);
		return $this;
	}
	
	/**
	 * Adds a raw config parameter
	 * The given value will be used raw - without quoting
	 * It's possible to set every object implementing Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface as value
	 * Returns itself to allow method-chaining
	 * 
	 * @param string $name
	 * @param mixed $value string or something that implements Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	public function setRaw($name,$value) {
		if (!is_string($value) && !$value instanceof Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface) {
			throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('only string or a object of type Tx_MvcExtjs_CodeGeneration_JavaScript_SnippetInterface are allowed as RawConfig',1264872938);
		}
		if (is_string($value)) {
			$value = new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet($value);
		}
		$configElement = new Tx_MvcExtjs_CodeGeneration_JavaScript_ObjectElement($name,$value);
		$this->addElement($configElement);
		return $this;
	}
	
}

?>