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
 * <mvcextjs:fe.pluginContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" enableExtJsDebug="true" addCssFile="{f:uri.resource(path:'styles/backend.css')}" addJsFile="{f:uri.resource('scripts/main.js')}">
 * 	<mvcextjs:fe.defaultExtOnReadyPanel layout="border"  />
 * </mvcextjs:fe.pluginContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_Fe_DefaultExtOnReadyPanelViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper {

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_Variable
	 */
	protected $startup;

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ConstructorCall
	 */
	protected $panel;

	/**
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	protected $panelConfig;

	/**
	 * (non-PHPdoc)
	 * @see Classes/ViewHelpers/Be/Tx_MvcExtjs_ViewHelpers_Be_AbstractJavaScriptCodeViewHelper#initialize()
	 */
	public function initialize() {
		parent::initialize();
		$this->extOnReady = TRUE;
		$this->startup = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_Variable', 'plugin', NULL, FALSE, $this->extJsNamespace);
		$this->panelConfig = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config');
		$this->panel = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Constructor', 'panel', 'Ext.Panel', $this->panelConfig, array(), FALSE);
		$this->startupCall = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionCall', $this->extJsNamespace . '.plugin.init');
	}

	/**
	 * The render method of the viewhelper.
	 * It checks the parameters for correctness and adds Ext.onReady() Code into your header.
	 * 
	 * @param string $layout the layout for the viewport container; default = fit;
	 * @param array $items
	 * @param string $renderTo
	 * @return void
	 */
	public function render($layout = 'fit', array $items = array(), $renderTo = 'plugin') {
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
				throw new Tx_MvcExtjs_ExtJS_Exception('The given layout (' . $layout . ') is not supported by extjs', 1264270609);
		}

			// Prepare itemArray for the Panel
		$itemArray = new Tx_MvcExtjs_CodeGeneration_JavaScript_Array();
		foreach ($items as $item) {
			$itemArray->addElement($item);
		}

		$this->renderTo = $renderTo;
		$this->panelConfig->set('layout', $layout)
						  ->set('renderTo', $renderTo)
						  ->setRaw('items', $itemArray)
						  ->setRaw('width', '500')
						  ->setRaw('height', '600');

			// Build up the need JS Code Contexts
		$this->startUp();

		$this->jsCode->addSnippet($this->startup);
		$this->jsCode->addSnippet($this->startupCall);
		$this->injectJsCode();
	}

	/**
	 * Sets up the startup variable.
	 * 
	 * @param $snippet
	 * @return void
	 */
	protected function startUp() {
		$this->panel->setConfig($this->panelConfig);
		$returnStatement = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet', 'return ');
		$objectDefinition = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_Object');
		$objectInitFunction = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionDeclaration', array(), array($this->panel /*,$panelRenderCall*/), TRUE);
		$objectInitElement = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_ObjectElement', 'init', $objectInitFunction);
		$objectDefinition->addElement($objectInitElement);
		$value = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionDeclaration', array(), array($returnStatement, $objectDefinition), FALSE, TRUE);
		$this->startup->setValue($value);
	}

}

?>