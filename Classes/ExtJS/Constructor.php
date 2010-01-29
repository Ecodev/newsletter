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
 * ExtJS constructor
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ExtJS_Constructor {

	/**
	 * @var string
	 */
	protected $varName;

	/**
	 * @var string
	 */
	protected $objectName;

	/**
	 * @var Tx_MvcExtjs_ExtJS_Object
	 */
	protected $config;

	/**
	 * @var array
	 */
	protected $additionalParameters;

	/**
	 * Constructor for the extjs constructor object.
	 * 
	 * @param string $varName
	 * @param string $objectName
	 * @param array $config
	 */
	public function __construct($varName = NULL, $objectName = NULL, Tx_MvcExtjs_ExtJS_Object $config = NULL, array $additionalParameters = array()) {
		$this->varName = $varName;
		$this->objectName = $objectName;
		if ($config != NULL) {
			$this->config = $config;
		} else {
			$this->config = Tx_MvcExtjs_ExtJS_Object::create();
		}
		$this->additionalParameters = $additionalParameters;
	}

	/**
	 * Returns a new Array.
	 * 
	 * @return Tx_MvcExtjs_ExtJS_Constructor
	 */
	public static function create() {
		return t3lib_div::makeInstance('Tx_MvcExtjs_ExtJS_Constructor');
	}

	/**
	 * Sets the variable name.
	 * 
	 * @param string $varName
	 * @return void
	 */
	public function setVarName($varName) {
		$this->varName = $varName;
	}

	/**
	 * Gets the variable name.
	 *  
	 * @return string
	 */
	public function getVarName() {
		return $this->varName;
	}

	/**
	 * Sets the object's name.
	 * 
	 * @param string $objectName
	 * @return void
	 */
	public function setObjectName($objectName) {
		$this->objectName = $objectName;
	}

	/**
	 * Gets the object's name 
	 * @return string
	 */
	public function getObjectName() {
		return $this->objectName;
	}

	/**
	 * Adds a configuration value.
	 * 
	 * @param string $attribute
	 * @param mixed $value
	 * @return Tx_MvcExtjs_ExtJS_Constructor to allow method chaining
	 */
	public function addConfig($attribute, $value) {
		$this->config->set($attribute, $value);
		return $this;
	}

	/**
	 * Adds a raw configuration value.
	 * 
	 * @param string $attribute
	 * @param mixed $value
	 * @return Tx_MvcExtjs_ExtJS_Constructor to allow method chaining
	 */
	public function addRawConfig($attribute,$value) {
		$this->config->setRaw($attribute,$value);
		return $this;
	}

	/**
	 * Adds an additional parameter.
	 *  
	 * @param mixed $parameter
	 * @return void
	 */
	public function addAdditionalParameter($parameter) {
		$this->additionalParameters[] = $parameter;
	}

	/**
	 * Builds the ExtJS code.
	 * 
	 * @return string JS code that calls the given constructor with the given config parameters
	 */
	public function build() {
		$jsOut = 'var ' . $this->varName . ' = new ' . $this->objectName . "(";
		$jsOut .= $this->config->build();
		$jsOut .= $this->buildAdditionalParameters();
		$jsOut .= ");\n";
		return $jsOut;
	}

	/**
	 * Builds code for the additional parameters.
	 *  
	 * @return string
	 */
	private function buildAdditionalParameters() {
		$jsOut = '';
		foreach($this->additionalParameters as $parameter) {
			if (is_string($parameter)) {
				$jsOut .= $parameter;
			}
		}
		return $jsOut;
	}

}

?>