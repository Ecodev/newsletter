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
 * <newsletter:fe.pluginContainer> your additional viewhelpers inside </newsletter:fe.pluginContainer>
 * </code>
 *
 * Output:
 * "your module content" wrapped with propper head & body tags.
 * Default backend CSS styles and JavaScript will be included
 *
 * <code title="All options">
 * {namespace newsletter=Tx_Newsletter_ViewHelpers}
 * <newsletter:fe.pluginContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" enableExtJsDebug="true">your module content</newsletter:fe.pluginContainer>
 * </code>
 *
 * @category    ViewHelpers
 * @package     Newsletter
 * @subpackage  ViewHelpers_Be
 * @author      Bastian Waidelich <bastian@typo3.org>
 * @author      Dennis Ahrens <dennis.ahrens@googlemail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Tx_Newsletter_ViewHelpers_Fe_PluginContainerViewHelper extends Tx_Newsletter_ViewHelpers_AbstractViewHelper {

	/**
	 * Renders the module into a given div container.
	 *
	 * @param string  $divContainerId title tag of the module. Not required by default, as BE modules are shown in a frame
	 * @param boolean $loadPrototype specifies whether to load prototype library. Defaults to TRUE
	 * @param boolean $loadScriptaculous specifies whether to load scriptaculous libraries. Defaults to FALSE
	 * @param string  $scriptaculousModule additionales modules for scriptaculous
	 * @param boolean $loadExtJs specifies whether to load ExtJS library. Defaults to FALSE
	 * @param boolean $loadExtJsTheme whether to load ExtJS "grey" theme. Defaults to FALSE
	 * @param string  $extJsAdapter load alternative adapter (ext-base is default adapter)
	 * @param boolean $concatenate specifies if the loaded jsFiles should be concatenated into one file. Defaults to TRUE
	 * @param boolean $compressJs specifies wether to compress the js. Defaults TRUE
	 * @param boolean $compressCss specifies wether to compress the css. Defaults TRUE
	 * @param boolean $enableExtJsDebug if TRUE, debug version of ExtJS is loaded. Use this for development only
	 * @param string  $extCorePath specifies a path for the ExtCore default NULL (uses the path set in the t3lib_PageRenderer)
	 * @param string  $extJsPath specifies a path for the ExtJS default NULL (uses the path set in the t3lib_PageRenderer)
	 * @see t3lib_PageRenderer
	 */
	public function render($divContainerId = 'plugin',
						   $loadPrototype = TRUE,
						   $loadScriptaculous = FALSE,
						   $scriptaculousModule = '',
						   $loadExtJs = TRUE,
						   $loadExtJsTheme = TRUE,
						   $extJsAdapter = '',
						   $concatenate = TRUE,
						   $compressJs = TRUE,
						   $compressCss= TRUE,
						   $enableExtJsDebug = FALSE,
						   $extCorePath = NULL,
						   $extJsPath = NULL) {
		$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		$controllerName = $this->controllerContext->getRequest()->getControllerName();
		$this->extJsNamespace = $extensionName . '.' . $controllerName;

		if ($loadPrototype === TRUE) {
			$this->pageRenderer->loadPrototype();
		}
		if ($loadScriptaculous === TRUE) {
			$this->pageRenderer->loadScriptaculous($scriptaculousModule);
		}
		if ($extCorePath !== NULL) {
			$this->pageRenderer->setExtCorePath($extCorePath);
		}
		if ($extJsPath !== NULL) {
			$this->pageRenderer->setExtJsPath($extJsPath);
		}
		if ($loadExtJs === TRUE) {
			$this->pageRenderer->loadExtJS(TRUE, $loadExtJsTheme, $extJsAdapter);
			if ($enableExtJsDebug === TRUE) {
				$this->pageRenderer->enableExtJsDebug();
			}
		}

		$jsNS  = "\n" . 'Ext.ns(\'' . $this->extJsNamespace . '\');' . "\n";

		$this->pageRenderer->addJsInlineCode('extjs Namespace for the Plugin',$jsNS);

		$this->renderChildren();

		if ($compressJs === TRUE) {
			$this->pageRenderer->enableCompressJavaScript();
		}
		if ($compressCss === TRUE) {
			$this->pageRenderer->enableCompressCss();
		}
		if ($concatenate === TRUE) {
			$this->pageRenderer->enableConcatenateFiles();
		}

		$output .= '<div id="' . $divContainerId . '"></div>';
		return $output;
	}
}
?>