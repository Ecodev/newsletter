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
class Tx_MvcExtjs_ViewHelpers_JsCode_GroupingStoreViewHelper extends Tx_MvcExtjs_ViewHelpers_JsCode_StoreViewHelper {

	/**
	 * Initializes the ViewHelper.
	 * 
	 * @see Classes/ViewHelpers/Be/Tx_MvcExtjs_ViewHelpers_Be_AbstractJavaScriptCodeViewHelper#initialize()
	 */
	public function initialize() {
		parent::initialize();
		$this->store->setClass('Ext.data.GroupingStore');
	}

	/**
	 * Renders the js code for a store, based on a domain model into the inline JS of your module.
	 * The store automatically loads its data via AJAX.
	 * 
	 * @param string $domainModel is used as variable name AND storeId for the generated store
	 * @param string $extensionName the EXT where the domainModel is located
	 * @param string $id choose a id for the created variable; default is $domainmodel . 'Store'
	 * @param string $name 
	 * @param string $reader the reader for the store
	 * @param string $writer the writer for the store
	 * @param string $proxy the proxy for the store
	 * @param string $data the data for the store
	 * @param boolean $autoSave
	 * @param boolean $restful 
	 * @param boolean $batch 
	 * @param string $autoLoad
	 * @param string $groupField
	 * @param string $sortInfo has to be a config object
	 * @param boolean $groupOnSort
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
						   $autoLoad = NULL,
						   $groupField = NULL,
						   $sortInfo = NULL,
						   $groupOnSort = NULL) {

		if ($groupField !== NULL) {
			$this->config->set('groupField', $groupField);
		}
		if ($sortInfo !== NULL) {
			$this->config->setRaw('sortInfo', $sortInfo);
		}
		if ($groupOnSort !== NULL) {
			$this->config->setRaw('groupOnSort', $groupOnSort ? 'true' : 'false');
		}
		parent::render($domainModel, $extensionName, $id, $name, $reader, $writer, $proxy, $data, $autoSave, $restful, $batch, $autoLoad);
	}

}
?>