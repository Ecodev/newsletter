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
class Tx_MvcExtjs_ViewHelpers_JsCode_StoreViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper {

	/**
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass
	 */
	protected $store;
	
	/**
	 * 
	 * @var Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config
	 */
	protected $config;
	
	/**
	 * Initializes the ViewHelper
	 * 
	 * @see Classes/ViewHelpers/Be/Tx_MvcExtjs_ViewHelpers_Be_AbstractJavaScriptCodeViewHelper#initialize()
	 */
	public function initialize() {
		parent::initialize();
		$this->config = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_Config();
		$this->store = new Tx_MvcExtjs_CodeGeneration_JavaScript_ExtJS_ExtendClass('defaultStoreName',
																				   'Ext.data.Store',
																					array(),
																					$this->config,
																					new Tx_MvcExtjs_CodeGeneration_JavaScript_Object(),
																					$this->extJsNamespace);
	}
	
	/**
	 * Renders the js code for a store, based on a domain model into the inline JS of your module.
	 * The store automatically loads its data via AJAX.
	 * 
	 * @param string $domainModel is used as variable name AND storeId for the generated store
	 * @param string $extensionName the EXT where the domainModel is located
	 * @param string $id choose a id for the created variable; default is $domainmodel . 'Store'
	 * @param string $name the name of the new class the is created
	 * @param string $reader the reader for the store
	 * @param string $writer the writer for the store
	 * @param string $proxy the proxy for the store
	 * @param string $data the data for the store
	 * @param boolean $autoSave
	 * @param boolean $restful 
	 * @param boolean $batch 
	 * @param boolean $autoLoad
	 * @return void
	 */
	public function render($domainModel = NULL,
						   $extensionName = NULL,
						   $id = NULL,
						   $name = NULL,
						   $reader = NULL,
						   $writer = NULL,
						   $proxy = NULL,
						   $data = NULL,
						   $autoSave = TRUE,
						   $restful = FALSE,
						   $batch = FALSE,
						   $autoLoad=FALSE) {

		if ($extensionName == NULL) {
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		}

		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;
			// Check if the given domain model class exists
		if (!class_exists($domainClassName)) {
			throw new Tx_Fluid_Exception('The Domain Model Class (' . $domainClassName . ') for the given domainModel (' . $domainModel . ') was not found', 1264069568);
		}
			// build up and set the name for the JS store variable
		$varNameStore = $domainModel . 'Store';
		if ($name === NULL) {
			$this->store->setName($varNameStore);
		} else {
			$this->store->setName($name);
		}
			// read the given config parameters into the Extjs Config Object
		if($id !== NULL) $this->config->set('storeId',$id);
		if($reader !== NULL) $this->config->setRaw('reader',$reader);
		if($writer !== NULL) $this->config->setRaw('writer',$writer);
		if($proxy !== NULL) $this->config->setRaw('proxy',$proxy);
		if($data !== NULL) $this->config->setRaw('data',$data);
		if ($autoSave) {
			$this->config->setRaw('autoSave','true');
		} else {
			$this->config->setRaw('autoSave','false');
		}
		if ($restful) {
			$this->config->setRaw('restful','true');
		} else {
			$this->config->setRaw('restful','false');
		}
		if ($batch) {
			$this->config->setRaw('batch','true');
		} else {
			$this->config->setRaw('batch','false');
		}
		if ($autoLoad) {
			$this->config->setRaw('autoLoad','true');
		} else {
			$this->config->setRaw('autoLoad','false');
		}
			// apply the configuration again
		$this->injectJsCode();
	}
	
	/**
	 * @see Classes/ViewHelpers/JsCode/Tx_MvcExtjs_ViewHelpers_JsCode_AbstractJavaScriptCodeViewHelper#injectJsCode()
	 */
	protected function injectJsCode() {
		$this->store->setConfig($this->config);
			// allow objects to be declared inside this viewhelper; they are rendered above
		$this->renderChildren();
			// add the code and write it into the inline section in your HTML head
		$this->jsCode->addSnippet($this->store);
		parent::injectJsCode();
	}
	

}
?>