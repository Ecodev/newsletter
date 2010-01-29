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
 * ExtJS Ext extended constructor
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ExtJS_ExtExtendConstructor {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $objectName;

	/**
	 * @var Tx_MvcExtjs_ExtJS_Object
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $variables;

	/**
	 * constructor for the ExtJS constructor object.
	 * 
	 * @param string $varName
	 * @param string $objectName
	 * @param array $config
	 * @param string $variables
	 */
	public function __construct($name = NULL, $objectName = NULL, Tx_MvcExtjs_ExtJS_Object $config = NULL, $variables = NULL) {
		$this->name = $name;
		$this->objectName = $objectName;
		if ($config != NULL) {
			$this->config = $config;
		} else {
			$this->config = Tx_MvcExtjs_ExtJS_Object::create();
		}
		$this->variables = $variables;
	}

	/**
	 * Returns a new Array.
	 * 
	 * @return Tx_MvcExtjs_ExtJS_ExtExtendConstructor
	 */
	public static function create() {
		return t3lib_div::makeInstance('Tx_MvcExtjs_ExtJS_ExtExtendConstructor');
	}

	/**
	 * Sets the name.
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Gets the name.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
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
	 * Gets the object's name.
	 * 
	 * @return string
	 */
	public function getObjectName() {
		return $this->objectName;
	}
	
	/**
	 * Sets the variables.
	 * 
	 * @param string $variables
	 * @return void
	 */
	public function setVariables($variables = '') {
		$this->variables = $variables;
	}

	/**
	 * Gets the variables.
	 * 
	 * @return string
	 */
	public function getVariables() {
		return $this->variables;
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
	public function addRawConfig($attribute, $value) {
		$this->config->setRaw($attribute, $value);
		return $this;
	}

	/**
	 * Builds the ExtJS code.
	 * 
	 * @return string JS code that calls the given constructor with the given config parameters
	 */
	public function build() {
		$jsOut = $this->name . ' = Ext.extend(' . $this->objectName . ', { ' . "\n";
		$jsOut .= "\t" . 'constructor: function(config) {' . "\n";
		
		$jsOut .= $this->variables;
		
		$jsOut .= "\t\t" . 'config = Ext.apply(';
		
		$jsOut .= $this->config->build();
		
		$jsOut .= ', config);' . "\n";
		
		$jsOut .= "\t\t" . $this->name . '.superclass.constructor.call(this, config);';

		$jsOut .= "\t" . '},' . "\n});\n";

		return $jsOut;
	}

}

?>