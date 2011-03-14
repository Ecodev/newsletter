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
class Tx_MvcExtjs_ViewHelpers_Json_FormReadResponseViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders a JSON object based on a given Tx_Extbase_DomainObject_AbstractEntity.
	 * 
	 * @param Tx_Extbase_DomainObject_AbstractEntity $object
	 * @param boolean $success
	 * @param array $excludeProperties
	 * 
	 * @return string
	 */
	public function render(Tx_Extbase_DomainObject_AbstractEntity $object, $success = TRUE, array $excludeProperties = array()) {
		$properties = Tx_Extbase_Reflection_ObjectAccess::getAccessibleProperties($object);
		foreach ($excludeProperties as $propertyName) {
			unset($properties[$propertyName]);
		}
		$properties['className'] = get_class($object);
		$returnArray = array(
			'success' => $success,
			'data' => $properties
		);
		return json_encode($returnArray);
	}

}
?>