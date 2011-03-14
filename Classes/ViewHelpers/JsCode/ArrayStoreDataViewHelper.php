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
 * View helper which allows you to include an ExtJS Store based on the object notation
 * of a domain model
 * Note: This feature is experimental!
 * Note: You MUST wrap this Helper with <mvcextjs:be.moduleContainer>-Tags
 *
 * = Examples =
 *
 * <mvcextjs:be.moduleContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="true" loadScriptaculous="false" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="prototype" enableExtJsDebug="true">
 * 	<mvcextjs:jsCode.ArrayStoreDataViewHelper domainModel="yourModelName" actions="{read:'yourActionForFetchingTheRecords',update:'yourActionForUpdatingRecords'}" controller="yourController" extensionName="yourExtensionName" />
 * </mvcextjs:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_JsCode_ArrayStoreDataViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper {

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_Array
	 */
	protected $array;

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_Variable
	 */
	protected $arrayVariable;

	/**
	 * Initializes the ViewHelper.
	 * 
	 * @see Classes/ViewHelpers/Be/Tx_MvcExtjs_ViewHelpers_Be_AbstractJavaScriptCodeViewHelper#initialize()
	 */
	public function initialize() {
		parent::initialize();
		$this->array = new Tx_MvcExtjs_CodeGeneration_JavaScript_Array();
		$this->arrayVariable = new Tx_MvcExtjs_CodeGeneration_JavaScript_Variable('name',NULL,false,$this->extJsNamespace);
	}

	/**
	 * Renders the js code for a store, based on a domain model into the inline JS of your module.
	 * The store automatically loads its data via AJAX.
	 * 
	 * @param string $domainModel is used as variable name AND storeId for the generated store
	 * @param string $extensionName the EXT where the domainModel is located
	 * @param array $data the that should be prepared as data array for a store
	 * @return void
	 */
	public function render($domainModel = NULL,
						   $extensionName = NULL,
						   array $data = array()) {

		if ($extensionName == NULL)
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();

		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;
			// Check if the given domain model class exists
		if (!class_exists($domainClassName)) {
			throw new Tx_Fluid_Exception('The Domain Model Class (' . $domainClassName . ') for the given domainModel (' . $domainModel . ') was not found', 1264069568);
		}
			// Vuild up and set the for the JS store variable
		$varNameStore = $domainModel . 'ArrayData';
		$this->arrayVariable->setName($varNameStore);

		$dataArray = array();
		foreach ($data as $object) {
			$dataArray[] = $this->convertObjectToArray($object);
		}
		$this->array->setElements($dataArray);

		$this->injectJsCode();
	}

	/**
	 * Converts the given object into an array that can be read by an Ext.data.ArrayStore that was created with 
	 * the Tx_MvcExtjs_ViewHelpers_JsCode_ArrayStoreViewHelper.
	 * If you have written the ArrayStore by yourself in pure JS code, have a look at the ArrayStoreViewHelper
	 * and how it is configured by default.
	 * 
	 * @param mixed $object
	 * @return array
	 */
	protected function convertObjectToArray($object) {
		$objectArray = new Tx_MvcExtjs_CodeGeneration_JavaScript_Array;
		$properties = $object->_getProperties();

		foreach($properties as $name => $value) {
			if (count($columns) > 0 && !in_array($name, $columns)) {
					// Current property should not be returned
				continue;
			}
			if ($value instanceof DateTime) {
				$value = $value->format('c');
			}

			}

			$objectArray->addElement(new Tx_MvcExtjs_CodeGeneration_JavaScript_QuotedValue($value));
		}
		return $objectArray;
	}

	/**
	 * @see Classes/ViewHelpers/JsCode/Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper#injectJsCode()
	 */
	protected function injectJsCode() {
		$this->arrayVariable->setValue($this->array);
			// Allow objects to be declared inside this viewhelper; they are rendered above
		$this->renderChildren();
			// Add the code and write it into the inline section in your HTML head
		$this->jsCode->addSnippet($this->arrayVariable);
		parent::injectJsCode();
	}

}
?>