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
class Tx_MvcExtjs_ViewHelpers_Json_StoreUpdateResponseViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders a JSON response for a ExtJS CRUD store read request.
	 * 
	 * @param array $data DEPRECATED use $object or $objects instead!
	 * @param object $object
	 * @param array $objects
	 * @param string $message
	 * @param boolean $success
	 * @return string
	 */
	public function render(array $data = NULL, $object = NULL, array $objects = array(), $message = 'default message', $success = TRUE, array $columns = array()) {
		$responseArray = array();
		$responseArray['message'] = $message;
		$responseArray['total'] = count($objects);
		$responseArray['success'] = $success;
			// while $data is still available, check that it is not used together with $object or $objects
		if ($data !== NULL && ($object !== NULL || $objects !== NULL)) {
			throw new Tx_MvcExtjs_ExtJS_Exception('$data should not be used together with $object or $objects',1277981799);
		}
		if (is_array($data)) {
			$responseArray['data'] = $data;
		} else if ($object !== NULL) {
			$responseArray['data'] = Tx_MvcExtjs_ExtJS_Utility::encodeObjectForJSON($object, $columns);
		} else {
			$responseArray['data'] = array();
			foreach ($objects as $object) {
				$responseArray['data'][] = Tx_MvcExtjs_ExtJS_Utility::encodeObjectForJSON($object, $columns);
			}
		}

		return json_encode($responseArray);
	}

}
?>