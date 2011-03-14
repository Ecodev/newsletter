<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 *
 *
 * @version $Id$
 * @package MvcExtjs
 * @subpackage ViewHelpers/Json
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_MvcExtjs_ViewHelpers_Json_ObjectViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper implements Tx_Fluid_Core_ViewHelper_Facets_ChildNodeAccessInterface { 
	
	/**
	 * An array of Tx_Fluid_Core_Parser_SyntaxTree_AbstractNode
	 * @var array
	 */
	protected $childNodes = array();

	/**
	 * @var Tx_Fluid_Core_Rendering_RenderingContext
	 */
	protected $renderingContext;

	/**
	 * Setter for ChildNodes - as defined in ChildNodeAccessInterface
	 *
	 * @param array $childNodes Child nodes of this syntax tree node
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function setChildNodes(array $childNodes) {
		$this->childNodes = $childNodes;
	}

	/**
	 * Sets the rendering context which needs to be passed on to child nodes
	 *
	 * @param Tx_Fluid_Core_Rendering_RenderingContext $renderingContext the renderingcontext to use
	 */
	public function setRenderingContext(Tx_Fluid_Core_Rendering_RenderingContext $renderingContext) {
		$this->renderingContext = $renderingContext;
	}
	
	/**
	 * Renders flashMessages into the response.
	 *
	 * @param object $ref
	 * @return string
	 * @author Dennis Ahrens <dennis.ahrens@fh-hannover.de>
	 */
	public function render($ref, array $columns = array()) {
		$responseArray = array();
		$objectArray = Tx_MvcExtjs_ExtJS_Utility::encodeObjectForJSON($ref, $columns);
		
		$responseArray = array_merge($responseArray,$objectArray);
		// TODO: merging objects responded from other ViewHelpers.
		/*foreach ($this->childNodes as $childNode) {
			if ($childNode instanceof Tx_Fluid_Core_Parser_SyntaxTree_ViewHelperNode) {
				$childNode->setRenderingContext($this->renderingContext);
				$jsonData = $childNode->evaluate();
				$data = json_decode($jsonData,true);
				if ($data === NULL) {
					throw new Tx_MvcExtjs_ExtJS_Exception('The ' . $childNode->getViewHelperClassName() . ' nested inside the Json/ArrayViewHelper returned invalid json: ' . $jsonData,1277980165);
				}
				$responseArray[] = $data;
				t3lib_div::sysLog('data: ' . print_r($data,true),'MvcExtjs',0);
			}
		}*/
		t3lib_div::sysLog('responseArray: ' . print_r($responseArray,true),'MvcExtjs',0);
		return json_encode($responseArray);
	}
}

?>
