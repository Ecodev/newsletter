<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Xavier Perseguers <typo3@perseguers.ch>
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
 * ExtJS object.
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ExtJS_Object {

	/** 
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Returns a new Object.
	 * 
	 * @return Tx_MvcExtjs_ExtJS_Object
	 */
	public static function create() {
		return t3lib_div::makeInstance('Tx_MvcExtjs_ExtJS_Object');
	}

	/**
	 * Sets a value attribute to this object and takes care to properly quote the value.
	 * 
	 * @param string $attribute
	 * @param mixed $value
	 * @return Tx_MvcExtjs_ExtJS_Object the current Object to allow method chaining
	 */
	public function set($attribute, $value) {
		$this->attributes[$attribute] = is_numeric($value) ? $value : Tx_MvcExtjs_ExtJS_Utility::encodeInlineHtml($value);
		return $this;
	}

	/**
	 * Sets a raw value attribute to this object.
	 * 
	 * @param string $attribute
	 * @param mixed $value
	 * @return Tx_MvcExtjs_ExtJS_Object the current Object to allow method chaining
	 */
	public function setRaw($attribute, $value) {
		$this->attributes[$attribute] = $value;
		return $this;
	}

	/**
	 * Builds the ExtJS object.
	 * 
	 * @return string The ExtJS code representing this object
	 */
	public function build() {
		$extjsAttributes = array();
		foreach ($this->attributes as $key => $value) {
			if($value instanceof Tx_MvcExtjs_ExtJS_Array || $value instanceof Tx_MvcExtjs_ExtJS_Object) {
				$value = $value->build();
			}
			$extjsAttributes[] = sprintf('%s : %s', $key, $value);
		}

		return sprintf('{ %s }', implode(', ', $extjsAttributes));
	}

}
?>