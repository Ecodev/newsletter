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
 * Utilities to handle Extbase objects with ExtJS
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ExtJS_Utility {

	/**
	 * Encodes an array of objects to be used by JSON later on.
	 * 
	 * @param array $objects
	 * @return array
	 */
	public static function encodeArrayForJSON(array $objects) {
		$arr = array();
		
		foreach ($objects as $object) {
			$arr[] = self::encodeObjectForJSON($object);
		}
		
		return $arr;
	}
	
	/**
	 * Encodes an object to be used by JSON later on.
	 *
	 * @param mixed $object
	 * @return array
	 */
	public static function encodeObjectForJSON($object) {
		if ($object instanceof DateTime) {
			return $object->format('r');
		} elseif (!($object instanceof Tx_Extbase_DomainObject_AbstractEntity)) {
			return $object;
		}
		
		$arr = array();
		
		$rc = new ReflectionClass(get_class($object));
		$properties = $rc->getProperties();
		
		foreach ($properties as $property) {
			$propertyGetterName = 'get' . ucfirst($property->name);
			
			if (method_exists($object, $propertyGetterName)) {
				$value = call_user_method($propertyGetterName, $object);
				if (is_array($value)) {
					$value = self::encodeArrayForJSON($value);
				} elseif (is_object($value)) {
					$value = self::encodeObjectForJSON($value);
				}
				$arr[$property->name] = $value;
			}
		}
		
		return $arr;
	}
	
	/**
	 * Returns an Ext.data.JsonReader for objects of
	 * class $class.
	 *
	 * @param string $class
	 * @param object $obj
	 * @return string
	 */
	public static function getJSONReader($class, $obj = NULL) {
		$jsonReader = 'new Ext.data.JsonReader({
			fields: [ %s ],
			root: "results",
			totalProperty: "totalItems",
			id: "uid"
		})';
		
		$fields = array();
		
		$rc = new ReflectionClass($class);
		if ($obj) {
			if (!is_a($obj, $class)) {
				die('Object is not a ' . $class);
			}
			$object = $obj;
		} else {
			$object = t3lib_div::makeInstance($class);
		}
		$properties = $rc->getProperties();
		
		foreach ($properties as $property) {
			$propertyGetterName = 'get' . ucfirst($property->name);
			
			if (method_exists($object, $propertyGetterName)) {
				$type = self::getMethodReturnType($object, $propertyGetterName);
				if ($type) {
					$fields[] = sprintf('{name: "%s", type: "%s"}', $property->name, $type);
				} else {
					$fields[] = sprintf('"%s"', $property->name);
				}
			}
		}
		
		return sprintf($jsonReader, join(',', $fields));
	}
	
	/**
	 * Returns the return type of an object method.
	 * EXPERIMENTAL
	 * 
	 * @param object $object
	 * @param string $methodName
	 * @return string Empty string if type could not be determined
	 */
	private static function getMethodReturnType($object, $methodName) {
		$method = new ReflectionMethod($object, $methodName);
		$phpDoc = $method->getDocComment();
		
		$type = '';
		if (preg_match('/@return\\s+(\\w+)/', $phpDoc, $matches)) {
			switch ($matches[1]) {
				case 'string':
					$type = 'string';
					break;
				case 'int':
				case 'integer':
					$type = 'int';
					break;
				case 'float':
					$type = 'float';
					break;
				case 'bool':
				case 'boolean':
					$type = 'boolean';
					break;
				case 'DateTime':
					$type = 'date';
					break;
			}
		}
		
		return $type;
	}
	
	/**
	 * Returns a JSON-encoded array to be consumed by ExtJS.
	 *
	 * @param array $a
	 * @return string
	 */
	public static function getJSON(array $a) {
		return json_encode(array(
			'totalItems' => count($a),
			'results' => $a,
		));
	}
	
}
?>