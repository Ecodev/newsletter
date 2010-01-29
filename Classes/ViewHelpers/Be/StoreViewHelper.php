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
 * 	<mvcextjs:Be.IncludeStore domainModel="yourModelName" actions="{read:'yourActionForFetchingTheRecords',update:'yourActionForUpdatingRecords'}" controller="yourController" extensionName="yourExtensionName" />
 * </mvcextjs:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_Be_StoreViewHelper extends Tx_Fluid_ViewHelpers_Be_AbstractBackendViewHelper {

	/**
	 * @var string
	 */
	private $varNameReader;

	/**
	 * @var string
	 */
	private $varNameProxy;

	/**
	 * @var string
	 */
	private $varNameWriter;

	/**
	 * @var string
	 */
	private $varNameStore;

	/**
	 * the namespace used in the js code
	 * @var string
	 */
	protected $extJsNamespace;

	/**
	 * Renders the js code for a store, based on a domain model into the inline JS of your module.
	 * The store automatically loads its data via AJAX.
	 * 
	 * @param string $name is used as variable name AND storeId for the generated store
	 * @param array $actions
	 * @param string $controller
	 * @param string $extensionName
	 * @param string $storeId choose a id for the created variable; default is domainmodel. 'Store'
	 * @return void
	 */
	public function render($domainModel = NULL, array $actions = array(), $controller = NULL, $extensionName = NULL, $storeId = NULL) {
		$doc = $this->getDocInstance();
		$pageRenderer = $doc->getPageRenderer();
		$jsOut = '';

		if ($extensionName == NULL) {
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		}

		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;

		$controllerName = $this->controllerContext->getRequest()->getControllerName();
		$this->extJsNamespace = $extensionName . '.' . $controllerName;

			// Check if the given domain model class exists
		if (!class_exists($domainClassName)) {
			throw new Tx_Fluid_Exception('The Domain Model Class (' . $domainClassName . ') for the given domainModel (' . $domainModel . ') was not found', 1264069568);
		}

			// Define names for the JS Variables
		$this->varNameReader = strtolower($domainClassName) . '_reader';
		$this->varNameWriter = strtolower($domainClassName) . '_writer';
		$this->varNameProxy = strtolower($domainClassName) . '_proxy';
		$this->varNameStore = $this->extJsNamespace . '.' . $domainModel . 'Store';
		
		if ($storeId ==  NULL) {
			$storeId = $domainModel . 'Store';
		}

		$dataMap = new Tx_Extbase_Persistence_Mapper_DataMap($domainClassName);
		$columnMaps = $dataMap->getColumnMaps();

		$variables = $this->renderReaderVariable($domainClassName);
		$variables .= $this->renderWriterVariable();
		$variables .= $this->renderProxyVariable($controller, $actions);
		$jsOut .= $this->renderStoreVariable($storeId,$variables);

		$pageRenderer->addJsInlineCode($this->varNameStore, $jsOut);
	}

	/**
	 * Renders the reader variable for the store.
	 * 
	 * @param string $class
	 * @return string JS Code containing a Ext.data.JsonStore Variable
	 */
	private function renderReaderVariable($class) {
		$jsConstructor = Tx_MvcExtjs_ExtJS_Constructor::create();
		$jsConstructor->setVarName($this->varNameReader);
		$jsConstructor->setObjectName('Ext.data.JsonReader');

			// Make config
		$jsConstructor->addConfig('totalProperty', 'total')
					  ->addConfig('successProperty', 'success')
					  ->addConfig('idProperty', 'uid')
					  ->addConfig('root', 'data');

		$fieldArray = Tx_MvcExtjs_ExtJS_Utility::getFieldsArray($class);
		$jsConstructor->addRawConfig('fields', $fieldArray);
		$jsOut = $jsConstructor->build();
		return $jsOut;
	}

	/**
	 * Renders the proxy variable for the store.
	 * 
	 * $actions has to look like this:
	 * $actions = array(
	 * 'extjsApiCall' => 'yourAction',
	 * );
	 * 
	 * Supported extjsApiCalls are:
	 *  - read
	 *  - update
	 *  - new
	 *  - destroy
	 * 
	 * @param string $controller the ajax controller
	 * @param array $actions the actions for the controller associated with the apiCall from extjs
	 * @return string JS Code containing a Ext.data.HttpProxy Variable
	 */
	private function renderProxyVariable($controller = NULL, array $actions = array()) {
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$jsConstructor = Tx_MvcExtjs_ExtJS_Constructor::create();
		$jsConstructor->setVarName($this->varNameProxy);
		$jsConstructor->setObjectName('Ext.data.HttpProxy');

		$apiObject = Tx_MvcExtjs_ExtJS_Object::create();
		foreach ($actions as $apiCall => $action) {
			switch ($apiCall) {
				case 'read':
				case 'new':
				case 'update':
				case 'destroy':
					$uri = $uriBuilder->reset()->uriFor($action,array('format' => 'json'), $controller);
					$apiObject->set($apiCall, $uri);
					break;
				default:
					throw new Tx_Fluid_Exception('The extjs HttpProxy-API only knows about read, new, update and destroy, your value: ' . $apiCall . ' is not supported',1264095568);
			}
		}
		$jsConstructor->addRawConfig('api',$apiObject);
		$jsOut = $jsConstructor->build();
		return $jsOut;
	}

	/**
	 * Renders the writer variabke used by the store.
	 * 
	 * @return string JS Code containing a Ext.data.JsonWriter Variable
	 */
	private function renderWriterVariable() {
		$jsConstructor = Tx_MvcExtjs_ExtJS_Constructor::create();
		$jsConstructor->setVarName($this->varNameWriter);
		$jsConstructor->setObjectName('Ext.data.JsonWriter');
		$jsConstructor->addRawConfig('encode', 'true')
					  ->addRawConfig('writeAllFields', 'false');
		$jsOut = $jsConstructor->build();
		return $jsOut;
	}

	/**
	 * Renders the store variable that could be referenced in your JS code.
	 * 
	 * @param string $storeId the id for the Store - default is the name of the domainModel
	 * @return string JS Code containing a Ext.data.HttpProxy Variable
	 */
	private function renderStoreVariable($storeId = NULL,$variables = '') {
		$jsExtExtendConstructor = Tx_MvcExtjs_ExtJS_ExtExtendConstructor::create();
		$jsExtExtendConstructor->setName($this->varNameStore);
		$jsExtExtendConstructor->setObjectName('Ext.data.Store');

		$jsExtExtendConstructor->setVariables($variables);

		$jsExtExtendConstructor->addConfig('id', $storeId)
					  ->addRawConfig('proxy', $this->varNameProxy)
					  ->addRawConfig('reader', $this->varNameReader)
					  ->addRawConfig('writer', $this->varNameWriter);
		$jsOut = $jsExtExtendConstructor->build();
    	//$jsOut .= $this->varNameStore . ".load();\n";
		return $jsOut;
	}

}
?>