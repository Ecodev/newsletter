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
abstract class Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper extends Tx_MvcExtjs_ViewHelpers_AbstractViewHelper {

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_Code
	 */
	protected $jsCode;

	/**
	 * Should the code be rendered as extjs.onReady code
	 * or should it be added as "normal" inline code.
	 * 
	 * @var boolean
	 */
	protected $extOnReady;

	/**
	 * The namespace used in the JS code.
	 * @var string
	 */
	protected $extJsNamespace;

	/**
	 * @see typo3/sysext/fluid/Classes/Core/ViewHelper/Tx_Fluid_Core_ViewHelper_AbstractViewHelper#initialize()
	 */
	public function initialize() {
		parent::initialize();
		$extOnReady = FALSE;
		$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		$controllerName = $this->controllerContext->getRequest()->getControllerName();
		$this->extJsNamespace = $extensionName . '.' . $controllerName;
		$this->jsCode = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_Code', $this->extJsNamespace);
	}

	/**
	 * Writes all JS code related to the ViewHelper into the pageRenderer.
	 * Use this function to build your JavaScript code
	 * 
	 * @return void
	 */
	protected function injectJsCode() {
		if ($this->extOnReady) {
			$this->pageRenderer->addExtOnReadyCode($this->jsCode->build());
		} else {
			$this->pageRenderer->addJsInlineCode($this->extJsNamespace . ' written with ' . get_class($this) . ' at ' . microtime(), $this->jsCode->build());
		}
	}

}

?>