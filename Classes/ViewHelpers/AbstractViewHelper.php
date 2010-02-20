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
 * a ViewHelper that holds a pageRenderer Object as instance variable
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
abstract class Tx_MvcExtjs_ViewHelpers_AbstractViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @var t3lib_PageRenderer
	 */
	protected $pageRenderer;

	/**
	 * @see typo3/sysext/fluid/Classes/Core/ViewHelper/Tx_Fluid_Core_ViewHelper_AbstractViewHelper#initialize()
	 */
	public function initialize() {
		if (TYPO3_MODE === 'BE') {
			$this->initializeBackend();
		} else {
			$this->initializeFrontend();
		}
	}

	/**
	 * Fetches the pageRenderer from the BE Context.
	 * 
	 * @return void
	 */
	protected function initializeBackend() {
		$this->pageRenderer = $this->getDocInstance()->getPageRenderer();
	}

	/**
	 * Fetches the pageRenderer from the FE Context.
	 * (not tested)
	 * 
	 * @return void
	 */
	public function initializeFrontend() {
		$GLOBALS['TSFE']->backPath = TYPO3_mainDir;
		$this->pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
	}

	/**
	 * Gets instance of template if exists or create a new one.
	 * Saves instance in viewHelperVariableContainer
	 *
	 * @return template $doc
	 */
	protected function getDocInstance() {
		if (!isset($GLOBALS['SOBE']->doc)) {
			$GLOBALS['SOBE']->doc = t3lib_div::makeInstance('template');
			$GLOBALS['SOBE']->doc->backPath = $GLOBALS['BACK_PATH'];
		}
		return $GLOBALS['SOBE']->doc;
	}

}

?>