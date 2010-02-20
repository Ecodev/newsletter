<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009-2010 Xavier Perseguers <typo3@perseguers.ch>
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
				$value = call_user_func(array($object, $propertyGetterName));
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
	 * @param array $columns array of columns/properties to be returned from the class $class 
	 * @return string
	 */
	public static function getJSONReader($class, $obj = NULL, $columns = array()) {
		$jsonReader = 'new Ext.data.JsonReader({
			fields: [ %s ],
			root: "results",
			totalProperty: "totalItems",
			id: "uid"
		})';

			// uid should always be returned
		if (count($columns) > 0 && !in_array('uid', $columns)) {
			$columns[] = 'uid';
		}

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
			if (count($columns) > 0 && !in_array($property->name, $columns)) {
					// Current property should not be returned
				continue;
			}

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

		return sprintf($jsonReader, implode(',', $fields));
	}

	/**
	 * creates a Tx_MvcExtjs_ExtJS_Array Object filled up with field configurations based on the given $class
	 * EXPERIMENTAL
	 * 
	 * @param string $class the class u like to fetch the fieldsArray for
	 * @param mixed $obj an instance of this class
	 * @param array $columns the columns u like to fetch an empty array will fetch all available properties
	 * @param array $additionalGetters use this array to fetch informations from methods that will not really work on properties f.e. data that is calculated on the base of two other properties
	 * @return Tx_MvcExtjs_CodeGeneration_JavaScript_Array this object will produce your JS Code when calling build() on it.
	 */
	public static function getFieldsArray($class, $obj = NULL, array $columns = array(), array $additionalGetters = array()) {
		$fields = new Tx_MvcExtjs_CodeGeneration_JavaScript_Array();
		$rc = new ReflectionClass($class);
		if ($obj) {
			if (!is_a($obj, $class)) {
				throw new Tx_MvcExtjs_ExtJS_Exception('Object is not a ' . $class);
			}
			$object = $obj;
		} else {
			$object = t3lib_div::makeInstance($class);
		}
		$properties = $rc->getProperties();
		foreach ($properties as $property) {
			if (count($columns) > 0 && !in_array($property->name, $columns)) {
					// Current property should not be returned
				continue;
			}

			$propertyGetterName = 'get' . ucfirst($property->name);
			$field = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config;

			if (method_exists($object, $propertyGetterName)) {
				$type = self::getMethodReturnType($object, $propertyGetterName);
				if ($type == 'date') {
					$field->set('name', $property->name)
					      ->set('type', $type)
					      ->set('dateFormat','c');
				} else if($type) {
					$field->set('name', $property->name)
					      ->set('type', $type);
				} else {
					$field->set('name', $property->name);
				}
				$fields->addElement($field);
			}
		}
		foreach ($additionalGetters as $propertyGetterName) {
			$field = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config;

			if (method_exists($object, $propertyGetterName)) {
				$type = self::getMethodReturnType($object, $propertyGetterName);
				if ($type) {
					$field->set('name', $property->name)
					      ->set('type', $type);
				} else {
					$field->set('name', $property->name);
				}
				$fields->addElement($field);
			}
		}
		return $fields;
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

	/**
	 * Encodes a html snippet in order to include it in an ExtJS declaration.
	 *  
	 * @param $html
	 * @return string
	 */
	public static function encodeInlineHtml($html) {
		$html = str_replace(array('"', "\n"), array('\\"', '\\n'), $html);

		return '"' . $html . '"';
	}

	/**
	 * Returns an array of ExtJS form elements associated to an Extbase action.
	 * 
	 * @param Tx_Extbase_MVC_Request $request
	 * @param string $action
	 * @return array Array of Tx_MvcExtjs_ExtJS_FormElement
	 */
	public static function getExtbaseFormElements(Tx_Extbase_MVC_Request $request, $action) {
		return array(
			Tx_MvcExtjs_ExtJS_FormElement::create($request)
				->setXType('hidden')
				->setObjectModelField('__referer', 'extensionName')
				->set('value', $request->getControllerExtensionName()),

			Tx_MvcExtjs_ExtJS_FormElement::create($request)
				->setXType('hidden')
				->setObjectModelField('__referer', 'controllerName')
				->set('value', $request->getControllerName()),

			Tx_MvcExtjs_ExtJS_FormElement::create($request)
				->setXType('hidden')	
				->setObjectModelField('__referer', 'actionName')
				->set('value', $action),
		);
	}

}
?>