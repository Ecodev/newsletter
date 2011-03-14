<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * View helper which renders the flash messages (if there are any) as an json
 * array into the Ext.Direct answer.
 * 
 * Use the JavaScript class Ext.ux.TYPO3.MvcExtjs.DirectFlashMessageDispatcher
 * to fetch the messages inside your JavaScript code.
 *
 *
 * @version $Id$
 * @package MvcExtjs
 * @subpackage ViewHelpers/Json
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_MvcExtjs_ViewHelpers_Json_FlashMessagesViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders flashMessages into the response.
	 *
	 * @return string rendered Flash Messages, if there are any in json format.
	 * @author Dennis Ahrens <dennis.ahrens@fh-hannover.de>
	 */
	public function render() {
		$flashMessages = $this->controllerContext->getFlashMessageContainer()->getAllAndFlush();
		if (is_array($flashMessages) && count($flashMessages) > 0) {
			$responseArray['flashMessages'] = array();
			foreach ($flashMessages as $flashMessage) {
				if ($flashMessage instanceof Tx_Extbase_MVC_Controller_FlashMessage) {
					$flashMessageArray = array(
						'message' => $flashMessage->getMessage(),
						'type' => $flashMessage->getType(),
						'tstamp' => $flashMessage->getTime()->format('U')
					);
					$responseArray['flashMessages'][] = $flashMessageArray;
				} else {
					$flashMessageArray = array(
						'message' => $flashMessage,
						'type' => 'notice',
						'tstamp' => time()
					);
					$responseArray['flashMessages'][] = $flashMessageArray;
				}
			}
		}
		
		return json_encode($responseArray);
	}
}

?>
