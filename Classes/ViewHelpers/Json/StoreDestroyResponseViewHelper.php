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
class Tx_MvcExtjs_ViewHelpers_Json_StoreDestroyResponseViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders a JSON response for a ExtJS CRUD store destroy request.
	 * TODO: the ViewHelpers name is to specific.
	 * 
	 * @param string $message
	 * @param boolean $success
	 * @return string
	 */
	public function render($message = 'default message', $success = TRUE) {
		$responseArray = array();
		$responseArray['message'] = $message;
		$responseArray['success'] = $success;
		return json_encode($responseArray);
	}

}
?>