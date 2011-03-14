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
 * View helper which allows 
 *
 * = Examples =
 *
 * <mvcextjs:be.moduleContainer pageTitle="foo" enableJumpToUrl="false" enableClickMenu="false" loadPrototype="false" loadScriptaculous="false" scriptaculousModule="someModule,someOtherModule" loadExtJs="true" loadExtJsTheme="false" extJsAdapter="jQuery" enableExtJsDebug="true" addCssFile="{f:uri.resource(path:'styles/backend.css')}" addJsFile="{f:uri.resource('scripts/main.js')}">
 * 	<mvcextjs:includeDirectApi />
 * </mvcextjs:be.moduleContainer>
 *
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Dennis Ahrens <dennis.ahrens@fh-hannover.de>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id: IncludeInlineJsFromFileViewHelper.php 30242 2010-02-20 14:32:48Z xperseguers $
 */
class Tx_MvcExtjs_ViewHelpers_ExtDirectProviderViewHelper extends Tx_MvcExtjs_ViewHelpers_AbstractViewHelper {
	
	/**
	 * @var Tx_MvcExtjs_ExtDirect_Api
	 */
	protected $apiService;
	
	/**
	 * @see Classes/Core/ViewHelper/Tx_Fluid_Core_ViewHelper_AbstractViewHelper#initializeArguments()
	 */
	public function initializeArguments() {
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$this->apiService = $objectManager->create('Tx_MvcExtjs_MVC_ExtDirect_Api');
	}
	
	/**
	 * Generates a Ext.Direct API descriptor and adds it to the pagerenderer.
	 * Also calls Ext.Direct.addProvider() on itself (at js side).
	 * The remote API is directly useable.
	 * 
	 * @param string $name The name for the javascript variable.
	 * @param string $namespace The namespace the variable is placed.
	 * @param string $routeUrl You can specify a URL that acts as router.
	 * @param boolean $cache
	 * 
	 * @return void
	 */
	public function render($name = 'remoteDescriptor',
						   $namespace = 'Ext.ux.TYPO3.app',
						   $routeUrl = NULL,
						   $cache = TRUE
						   ) {
		
		if ($routeUrl === NULL) {
			$routeUrl = $this->controllerContext->getUriBuilder()->reset()->build() . '&Tx_MvcExtjs_ExtDirectRequest=1';
		}
		
		$api = $this->apiService->getApi($routeUrl,$namespace,$cache);
		
			// prepare output variable
		$jsCode = '';
		$descriptor = $namespace . '.' . $name;
			// build up the output
		$jsCode .= 'Ext.ns(\'' . $namespace . '\'); ' . "\n";
		$jsCode .= $descriptor . ' = ';
        $jsCode .= json_encode($api);
        $jsCode .= ";\n";
        $jsCode .= 'Ext.Direct.addProvider(' . $descriptor . ');' . "\n";
        	// add the output to the pageRenderer
        $this->pageRenderer->addExtOnReadyCode($jsCode,TRUE);
	}

}

?>