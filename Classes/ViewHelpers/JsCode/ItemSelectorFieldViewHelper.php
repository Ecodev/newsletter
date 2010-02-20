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
 * View helper which allows you to set up a columnDefinition.
 * F.e. a GridPanel based on a domain model.
 * 
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <mvcextjs:be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <mvcextjs:be.moduleContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" enableExtJsDebug="true" addCssFile="{f:uri.resource(path:'styles/backend.css')}" addJsFile="{f:uri.resource('scripts/main.js')}">
 * 	<mvcextjs:Be.IncludeColumnDefinition />
 * </mvcextjs:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_JsCode_ItemSelectorFieldViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper {

	/**
	 * the variable as js object that represents the returned field class definition
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass
	 */
	protected $extend;

	/**
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_Array
	 */
	protected $multiSelects;

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	protected $config;

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionCall
	 */
	protected $xTypeRegistration;

	/**
	 * @see Classes/ViewHelpers/JsCode/Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper#initialize()
	 */
	public function initialize() {
		parent::initialize();
		$this->config = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config();
		$this->multiSelects = new Tx_MvcExtjs_CodeGeneration_JavaScript_Array();

		$this->config->set('xtype','itemselector')
					 ->set('imagePath','../typo3conf/ext/mvc_extjs/Resources/Public/Images/');
		$this->extend = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass(
			'itemselectorvariablename',
			'Ext.ux.form.ItemSelector',
			array(),
			$this->config,
			new Tx_MvcExtjs_CodeGeneration_JavaScript_Object(),
			$this->extJsNamespace
		);
		$this->xTypeRegistration = new Tx_MvcExtjs_CodeGeneration_JavaScript_FunctionCall('Ext.reg', array());
	}

	/**
	 * Renders the JS code for a store, based on a domain model into the inline JS of your module
	 * the store automatically loads its data via AJAX.
	 * 
	 * @param string $name is used as variable name AND storeId for the generated store
	 * @param string $extensionName
	 * @param string $fromMultiSelect
	 * @param string $toMultiSelect
	 * @return void
	 */
	public function render($domainModel = NULL,
						   $extensionName = NULL,
						   $fromMultiSelect = NULL,
						   $toMultiSelect = NULL) {

		if ($extensionName == NULL) {
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		}

		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;
		$varName = $domainModel . 'ItemSelector';
		$xTypeName = strtolower($varName);
			// Check if the given domain model class exists
		if (!class_exists($domainClassName)) {
			throw new Tx_Fluid_Exception('The Domain Model Class (' . $domainClassName . ') for the given domainModel (' . $domainModel . ') was not found', 1264069568);
		}

		$this->xTypeRegistration->addParameter(new Tx_MvcExtjs_CodeGeneration_JavaScript_QuotedValue($xTypeName));
		$this->xTypeRegistration->addParameter(new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet($this->extJsNamespace . '.' . $varName));

		$this->extend->setName($varName);

		$this->multiSelects->addElement(new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet($fromMultiSelect));
		$this->multiSelects->addElement(new Tx_MvcExtjs_CodeGeneration_JavaScript_Snippet($toMultiSelect));

		$this->injectJsCode();
	}

	/**
	 * @see Classes/ViewHelpers/JsCode/Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper#injectJsCode()
	 */
	protected function injectJsCode() {
		$this->renderChildren();

		$this->config->setRaw('multiselects', $this->multiSelects);
		$this->extend->setConfig($this->config);

		$this->jsCode->addSnippet($this->extend);
		$this->jsCode->addSnippet($this->xTypeRegistration); 
		parent::injectJsCode();
	}

}

?>