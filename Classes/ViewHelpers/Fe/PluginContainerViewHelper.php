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
 * View helper which allows you to create ExtBase-based modules in the style of
 * TYPO3 default modules.
 * Note: This feature is experimental!
 *
 * = Examples =
 *
 * <code title="Simple">
 * <mvcextjs:fe.pluginContainer> your additional viewhelpers inside </mvcextjs:fe.pluginContainer>
 * </code>
 *
 * Output:
 * "your module content" wrapped with propper head & body tags.
 * Default backend CSS styles and JavaScript will be included
 *
 * <code title="All options">
 * {namespace mvcextjs=Tx_MvcExtjs_ViewHelpers}
 * <mvcextjs:fe.pluginContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" enableExtJsDebug="true">your module content</mvcextjs:fe.pluginContainer>
 * </code>
 *
 * @category    ViewHelpers
 * @package     MvcExtjs
 * @subpackage  ViewHelpers_Be
 * @author      Bastian Waidelich <bastian@typo3.org>
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_Fe_PluginContainerViewHelper extends Tx_MvcExtjs_ViewHelpers_AbstractViewHelper {


	/**
	 * Renders the module into a given div container
	 *
	 * @param string $divContainerId title tag of the module. Not required by default, as BE modules are shown in a frame
	 * @param boolean $loadPrototype specifies whether to load prototype library. Defaults to TRUE
	 * @param boolean $loadScriptaculous specifies whether to load scriptaculous libraries. Defaults to FALSE
	 * @param string  $scriptaculousModule additionales modules for scriptaculous
	 * @param boolean $loadExtJs specifies whether to load ExtJS library. Defaults to FALSE
	 * @param boolean $loadExtJsTheme whether to load ExtJS "grey" theme. Defaults to FALSE
	 * @param string  $extJsAdapter load alternative adapter (ext-base is default adapter)
	 * @param boolean $enableExtJsDebug if TRUE, debug version of ExtJS is loaded. Use this for development only
	 * @see t3lib_PageRenderer
	 */
	public function render($divContainerId = 'plugin',
						   $loadPrototype = TRUE,
						   $loadScriptaculous = FALSE,
						   $scriptaculousModule = '',
						   $loadExtJs = TRUE,
						   $loadExtJsTheme = TRUE,
						   $extJsAdapter = 'prototype',
						   $enableExtJsDebug = FALSE) {
		$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		$controllerName = $this->controllerContext->getRequest()->getControllerName();
		$this->extJsNamespace = $extensionName . '.' . $controllerName;
		
		if ($loadPrototype) {
			$this->pageRenderer->loadPrototype();
		}
		if ($loadScriptaculous) {
			$this->pageRenderer->loadScriptaculous($scriptaculousModule);
		}
		if ($loadExtJs) {
			$this->pageRenderer->loadExtJS(TRUE, $loadExtJsTheme, $extJsAdapter);
			if ($enableExtJsDebug) {
				$this->pageRenderer->enableExtJsDebug();
			}
		}
		
		$jsNS  = "\n" . 'Ext.ns(\'' . $this->extJsNamespace . '\');' . "\n";

		$this->pageRenderer->addJsInlineCode('extjs Namespace for the Plugin',$jsNS);

		$this->renderChildren();

		$output .= '<div id="' . $divContainerId . '"></div>';
		return $output;
	}
}
?>