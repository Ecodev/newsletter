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
 * 	<mvcextjs:includeColumnDefinition />
 * </mvcextjs:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_JsCode_ColumnDefinitionViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper {

	/**
	 * The variable as js object that represents the returned column definition
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_Variable
	 */
	protected $columnVariable;

	/**
	 * Renders the JS code for a store, based on a domain model into the inline JS of your module
	 * 
	 * @param string $name is used as variable name AND storeId for the generated store
	 * @param string $extensionName
	 * @param array $columns
	 * @return void
	 */
	public function render($domainModel = NULL,
						   $extensionName = NULL,
						   array $columns = array(),
						   array $hiddenColumns = array(),
						   array $specialRenderer = array()) {

		if ($extensionName == NULL) {
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		}

		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;
		$varName = $domainModel . 'Columns';

			// Check if the given domain model class exists
		if (!class_exists($domainClassName)) {
			throw new Tx_Fluid_Exception('The Domain Model Class (' . $domainClassName . ') for the given domainModel (' . $domainModel . ') was not found', 1264069568);
		}
			// Create the js object
		$columnArray = new Tx_MvcExtjs_CodeGeneration_JavaScript_Array();

		$rClass = t3lib_div::makeInstance('Tx_Extbase_Reflection_ClassReflection', $domainClassName);
		$rProperties = $rClass->getProperties();

		foreach ($rProperties as $rProperty) {
			$columnDef = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config();
			// TODO: fetch label from TCA?
			$columnDef->set('header', $rProperty->getName())
					  ->set('dataIndex', $rProperty->getName())
					  ->setRaw('sortable', 'true');
			if (isset($specialRenderer[$rProperty->getName()])) { 
				$columnDef->setRaw('renderer', $specialRenderer[$rProperty->getName()]);
			}
			if (in_array($rProperty->getName(), $hiddenColumns)) {
				$columnDef->setRaw('hidden', 'true');
			} else {
				$columnDef->setRaw('hidden', 'false');
			}
			$columnArray->addElement($columnDef);
		}

		$this->columnVariable = t3lib_div::makeInstance('Tx_MvcExtjs_CodeGeneration_JavaScript_Variable', $this->extJsNamespace . '.' . $varName, $columnArray);

		$this->jsCode->addSnippet($this->columnVariable); 
		$this->injectJsCode();
	}

}
?>