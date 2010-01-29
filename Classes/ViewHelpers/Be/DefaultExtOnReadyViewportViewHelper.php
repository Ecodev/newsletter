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
 * View helper which allows you to include inline JS code into a module Container.
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <mvcextjs:be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <mvcextjs:be.moduleContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" enableExtJsDebug="true" addCssFile="{f:uri.resource(path:'styles/backend.css')}" addJsFile="{f:uri.resource('scripts/main.js')}">
 * 	<mvcextjs:Be.defaultExtOnReadyViewport layout="border"  />
 * </mvcextjs:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_Be_DefaultExtOnReadyViewportViewHelper extends Tx_Fluid_ViewHelpers_Be_AbstractBackendViewHelper {

	/**
	 * The namespace used in the js code
	 * @var string
	 */
	protected $extJsNamespace;

	/**
	 * The render method of the viewhelper.
	 * It checks the parameters for correctness and adds Ext.onReady() Code into your header.
	 * 
	 * @param string $layout the layout for the viewport container; default = fit;
	 * @param array $items
	 * @return void
	 */
	public function render($layout = 'fit', array $items = array()) {
			// Fetch the DocInstance and the renderer
		$doc = $this->getDocInstance();
		$pagerenderer = $doc->getPageRenderer();
			// Set some usefull variables
		$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		$controllerName = $this->controllerContext->getRequest()->getControllerName();
		$this->extJsNamespace = $extensionName . '.' . $controllerName;
			// Check if the layout is valid
		switch ($layout) {
			case 'fit':
			case 'border':
			case 'absolute':
			case 'accordion':
			case 'card':
			case 'column':
			case 'form':
			case 'hbox':
			case 'menu':
			case 'table':
			case 'toolbar':
			case 'vbox':
			case 'anchor':
			case 'auto':
				break;
			default:
				throw new Tx_MvcExtjs_ExtJS_Exception('The given layout (' . $layout . ') is not supported by extjs',1264270609 );
		}
			// Build the JS Code
		$jsOut = $this->buildJSCode($layout,$items);
			// Add the JS Code
		$pagerenderer->addExtOnReadyCode($jsOut);
	}

	/**
	 * Vuilds JS code for a viewport.
	 * 
	 * @param string $layout the layout for the viewport
	 * @param array $items the items the viewport will contain
	 * @return string JS Code for Ext.onReady()
	 */
	private function buildJSCode($layout = NULL, array $items = array()) {
		$moduleVariableName = $this->extJsNamespace . '.viewport';

		$jsOut  = 'Ext.ns("' . $this->extJsNamespace . '");' . "\n";
		$jsOut .= $moduleVariableName . ' = function() {' . "\n";
		$jsOut .= "\t" . 'return {' . "\n";
		$jsOut .= "\t\t" . 'init: function() {' . "\n";
		$jsOut .= "\t\t\t";

		$viewport = Tx_MvcExtjs_ExtJS_Constructor::create();
		$viewport->setVarName('viewport');
		$viewport->setObjectName('Ext.Viewport');
		$viewport->addConfig('layout',$layout);
		$viewport->addRawConfig('renderTo','Ext.getBody()');

		$jsItems = Tx_MvcExtjs_ExtJS_Array::create();
		foreach ($items as $item) {
			$jsItems->add($item);
		}
		$viewport->addRawConfig('items', $jsItems);

		$jsOut .= $viewport->build();

		$jsOut .= "\t\t}\n\t}\n}();\n";
		$jsOut .= $moduleVariableName . '.init();' . "\n";

		return $jsOut;
	}

}

?>