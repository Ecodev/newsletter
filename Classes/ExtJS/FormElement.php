<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Xavier Perseguers <typo3@perseguers.ch>
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
 * ExtJS form element.
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_ExtJS_FormElement extends Tx_MvcExtjs_ExtJS_Object {

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * Returns a new FormElement.
	 * 
	 * @param Tx_Extbase_MVC_Request $request
	 * @return Tx_MvcExtjs_ExtJS_FormElement
	 */
	public static function create(Tx_Extbase_MVC_Request $request) {
		$formElement = t3lib_div::makeInstance('Tx_Mvcextjs_ExtJS_FormElement');
		$formElement->setRequest($request);
		return $formElement;
	}

	/**
	 * Sets the controller.
	 * 
	 * @param Tx_Extbase_MVC_Request $rqeuest
	 * @return Tx_MvcExtjs_ExtJS_FormElement the current FormElement to allow method chaining
	 */
	public function setRequest(Tx_Extbase_MVC_Request $request) {
		$this->namespace = strtolower('tx_' . $request->getControllerExtensionName() . '_' . $request->getControllerName());
		return $this;
	}

	/**
	 * Sets the element's xtype.
	 * 
	 * @param string $xtype
	 * @return Tx_MvcExtjs_ExtJS_FormElement the current FormElement to allow method chaining
	 */
	public function setXType($xtype) {
		return $this->set('xtype', $xtype);
	}

	/**
	 * Sets the Extbase object model object's field associated to the FormElement.
	 *  
	 * @param string $objectName
	 * @param string $field
	 * @return Tx_MvcExtjs_ExtJS_FormElement the current FormElement to allow method chaining
	 */
	public function setObjectModelField($objectName, $field) {
		return $this->set('name', sprintf('%s[%s][%s]', $this->namespace, $objectName, $field));
	}

}
?>