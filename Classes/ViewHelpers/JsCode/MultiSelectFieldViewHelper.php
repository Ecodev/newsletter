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
class Tx_MvcExtjs_ViewHelpers_JsCode_MultiSelectFieldViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper {

	/**
	 * The variable as js object that represents the returned field class definition
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass
	 */
	protected $extend;

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	protected $config;

	public function initialize() {
		parent::initialize();
		$this->config = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config();

		$this->extend = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass(
			'multiselectvariablename',
			'Ext.ux.form.MultiSelect',
			array(),
			$this->config,
			new Tx_MvcExtjs_CodeGeneration_JavaScript_Object(),
			$this->extJsNamespace
		);
	}

	/**
	 * Renders the JS code for a MultiSelect Field, based on a domain model into the inline JS of your module.
	 * 
	 * @param string $name is used as variable name AND storeId for the generated store
	 * @param string $extensionName
	 * @param string $name
	 * @param string $store
	 * @param string $width
	 * @param string $height
	 * @param string $displayField
	 * @param string $valueField
	 * @param string $legend
	 * @param int $minSelections
	 * @param int $maxSelections
	 * @param string $minSelectionsText
	 * @param string $maxSelectionsText
	 * @return unknown_type
	 */
	public function render($domainModel = NULL,
						   $extensionName = NULL,
						   $name = NULL,
						   $store = NULL,
						   $width = '200',
						   $height = '120',
						   $displayField = 'name',
						   $valueField = 'uid',
						   $legend = 'Select multiple Values',
						   $minSelections = NULL,
						   $maxSelections = NULL,
						   $minSelectionsText = NULL,
						   $maxSelectionsText = NULL) {

		if ($extensionName == NULL) {
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		}

		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;
		$varName = $domainModel . 'MultiSelect';
			// Check if the given domain model class exists
		if (!class_exists($domainClassName)) {
			throw new Tx_Fluid_Exception('The Domain Model Class (' . $domainClassName . ') for the given domainModel (' . $domainModel . ') was not found', 1264069568);
		}

		if ($name !== NULL) {
			$this->extend->setName($name);
		} else {
			$this->extend->setName($varName);
		}

		if ($store == NULL) {
			throw new Tx_MvcExtjs_CodeGeneration_JavaScript_Exception('a multiselect field needs a store',1265886143);
		} else {
			$this->config->setRaw('store',$store);
		}

		if ($width !== NULL) $this->config->setRaw('width', $width);
		if ($height !== NULL) $this->config->setRaw('height', $height);
		if ($displayField !== NULL) $this->config->set('displayField', $displayField);
		if ($valueField !== NULL) $this->config->set('valueField', $valueField);
		if ($legend !== NULL) $this->config->set('legend', $legend);
		if ($minSelections !== NULL) $this->config->setRaw('minSelections', $minSelections);
		if ($maxSelections !== NULL) $this->config->setRaw('maxSelections', $maxSelections);
		if ($minSelectionsText !== NULL) $this->config->set('minSelectionsText', $minSelectionsText);
		if ($maxSelectionsText !== NULL) $this->config->set('maxSelectionsText', $maxSelectionsText);

		$this->injectJsCode();
	}

	/**
	 * @see Classes/ViewHelpers/JsCode/Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper#injectJsCode()
	 */
	protected function injectJsCode() {
		$this->renderChildren();
		$this->extend->setConfig($this->config);
		$this->jsCode->addSnippet($this->extend);
		parent::injectJsCode();
	}

}

?>