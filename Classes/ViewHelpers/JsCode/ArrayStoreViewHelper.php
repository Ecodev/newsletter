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
 * 	<mvcextjs:jsCode.ArrayStore domainModel="yourModelName" controller="yourController" extensionName="yourExtensionName" />
 * </mvcextjs:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ViewHelpers_JsCode_ArrayStoreViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_StoreViewHelper {

	/**
	 * Initializes the ViewHelper
	 * Sets the class of the store to Ext.data.ArrayStore
	 * 
	 * @see Classes/ViewHelpers/Be/Tx_MvcExtjs_ViewHelpers_Be_AbstractJavaScriptCodeViewHelper#initialize()
	 */
	public function initialize() {
		parent::initialize();
		$this->store->setClass('Ext.data.ArrayStore');
	}
	
	/**
	 * Renders the js code for a array store, based on a domain model into the inline JS of your module.
	 * This Store wants it's data as parameter - the parameter is a JS variable which must exist, when this code is added to the
	 * pagerenderer. Create the variable by using the Tx_MvcExtjs_ViewHelpers_JsCode_ArrayStoreDataViewHelper or provide it as pure
	 * JS file included to your module or plugin.
	 * 
	 * @param string $domainModel is used as variable name AND storeId for the generated store
	 * @param string $extensionName the EXT where the domainModel is located
	 * @param string $id choose a id for the created variable; default is $domainmodel . 'Store'
	 * @param string $name the name of the created JS constructor. Notice the additional namespace, which is used by default.
	 * @param string $writer the writer for the store
	 * @param string $proxy the proxy for the store
	 * @param string $data the data for the store
	 * @param boolean $autoSave
	 * @param boolean $restful 
	 * @param boolean $batch
	 * @param boolean $autoLoad
	 * @param string $idProperty
	 * @return void
	 */
	public function render($domainModel = NULL,
						   $extensionName = NULL,
						   $id = NULL,
						   $name = NULL,
						   $writer = NULL,
						   $proxy = NULL,
						   $data = NULL,
						   $autoSave = TRUE,
						   $restful = FALSE,
						   $batch = FALSE,
						   $autoLoad = FALSE,
						   $idProperty = 'uid') {
		if ($extensionName === NULL)
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;
			// build up and set the for the JS store variable
		$varNameReader = $domainModel . 'JsonReader';
		
		if ($idProperty != NULL) $this->config->set('idProperty',$idProperty);
		
		$fields = Tx_MvcExtjs_ExtJS_Utility::getFieldsArray($domainClassName);
		$this->config->setRaw('fields',$fields);
		
		parent::render($domainModel,$extensionName,$id,$name,NULL,$writer,$proxy,$data,$autoSave,$restful,$batch,$autoLoad);
	}

}
?>