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
class Tx_MvcExtjs_ViewHelpers_Be_ColumnDefinitionViewHelper extends Tx_Fluid_ViewHelpers_Be_AbstractBackendViewHelper {

	/**
	 * The namespace used in the JS code
	 * @var string
	 */
	protected $extJsNamespace;

	/**
	 * Renders the JS code for a store, based on a domain model into the inline JS of your module
	 * the store automatically loads its data via AJAX.
	 * 
	 * @param string $name is used as variable name AND storeId for the generated store
	 * @param string $extensionName
	 * @param string $varName choose a name for the created variable; default is domainmodel . 'Columns'
	 * @param array $columns
	 * @return void
	 */
	public function render($domainModel = NULL, $extensionName = NULL, $varName = NULL, array $columns = array()) {
		$doc = $this->getDocInstance();
		$pagerenderer = $doc->getPageRenderer();

		if ($extensionName == NULL) {
			$extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();
		}

		$controllerName = $this->controllerContext->getRequest()->getControllerName();
		$this->extJsNamespace = $extensionName . '.' . $controllerName;

		$domainClassName = 'Tx_' . $extensionName . '_Domain_Model_' . $domainModel;
		if ($varName == NULL) {
			$varName = $domainModel . 'Columns';
		}

		$jsOut = $this->extJsNamespace . '.' . $varName . ' = ';

			// Check if the given domain model class exists
		if (!class_exists($domainClassName)) {
			throw new Tx_Fluid_Exception('The Domain Model Class (' . $domainClassName . ') for the given domainModel (' . $domainModel . ') was not found', 1264069568);
		}
		$columnArray = Tx_MvcExtjs_ExtJS_Array::create();

		$rClass = t3lib_div::makeInstance('Tx_Extbase_Reflection_ClassReflection', $domainClassName);
		$rProperties = $rClass->getProperties();

		foreach ($rProperties as $rProperty) {
			$columnDef = Tx_MvcExtjs_ExtJS_Object::create();
			// TODO: fetch label from TCA?
			$columnDef->set('header', $rProperty->getName())
					  ->set('dataIndex', $rProperty->getName())
					  ->setRaw('sortable', 'true');
			$columnArray->add($columnDef);
		}

		$jsOut .= $columnArray->build(); 

		$pagerenderer->addJsInlineCode($domainClassName, $jsOut);
	}

}

?>