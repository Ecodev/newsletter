<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dennis Ahrens <dennis.ahrens@fh-hannover.de>
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
 * A ViewHelper which returns its input as a json-encoded string.
 * 
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id:
 */
class Tx_MvcExtjs_ViewHelpers_Json_StoreCreateResponseViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders a JSON response for a ExtJS CRUD store create request.
	 * 
	 * @param array $data Contains the data of the objects that were created
	 * @param string $message Sets a message for extjs - quicktips or something like that may use it DEFAULT: 'create successful'
	 * @param boolean $success Tells extjs that the call was successful or not
	 * @param array columns Defines a set of properties related to $data, that should be include. If $columns is empty (DEFAULT) all properties are included.
	 * @return string
	 */
	public function render(array $data = array(), $message = 'create successful', $success = TRUE, array $columns = array()) {
		$this->columns = $columns;
		$responseArray = array();
		$responseArray['message'] = $message;
		$responseArray['total'] = 1;
		$responseArray['success'] = $success;

		$dataArray = array();

		foreach ($data as $object) {
			$dataArray[] = $this->buildPropertyArray($object, $columns);
		}	

		$responseArray['data'] = $dataArray;

		return json_encode($responseArray);
	}

	/**
	 * Builds the property array based on a given object.
	 * Overload this function in your EXT ViewHelpers to get the answer you want to have.
	 * This is neccessary, if you have objects, that have relations to other objects f.e.
	 * 
	 * @param mixed $object
	 * @param array $columns Use this array to choose a set of properties, that should be include. If $columns is empty (DEFAULT) all properties are included
	 * @return array
	 */
	public function buildPropertyArray($object = NULL, array $columns = array()) {
		$objectArray = array();
		$properties = $object->_getProperties();
		foreach($properties as $name => $value) {
			if (count($columns) > 0 && !in_array($name, $columns)) {
					// Current property should not be returned
				continue;
			}
			if ($value instanceof DateTime) {
				$value = $value->format('c');
			}

			$objectArray[$name] = $value;
		}
		return $objectArray;
	}

}
?>