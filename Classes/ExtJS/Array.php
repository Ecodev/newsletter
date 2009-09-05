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
 * ExtJS array.
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ExtJS_Array {
	
	/** 
	 * @var array
	 */
	protected $items = array();
	
	/**
	 * Returns a new Array.
	 * 
	 * @return Tx_MvcExtjs_ExtJS_Array
	 */
	public static function create() {
		return t3lib_div::makeInstance('Tx_MvcExtjs_ExtJS_Array');
	}
	
	/**
	 * Adds an item.
	 * 
	 * @param mixed $item
	 * @return Tx_MvcExtjs_ExtJS_Array the current Array to allow method chaining
	 */
	public function add($item) {
		$this->items[] = $item;
		return $this;
	}
	
	/**
	 * Builds the ExtJS array.
	 * 
	 * @return string The ExtJS code representing this array
	 */
	public function build() {
		$extjsItems = array();
		foreach ($this->items as $item) {
			$extjsItems[] = ($item instanceof Tx_MvcExtjs_ExtJS_Object) ? $item->build() : $item;
		}
		
		return sprintf('[ %s ]', implode(",\n", $extjsItems));
	}
	
}
?>