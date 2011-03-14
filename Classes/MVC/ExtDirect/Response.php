<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Jochen Rau <jochen.rau@typoplanet.de>
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
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
 * A web specific response implementation
 * 
 * TODO: When extbase request handling is made dont extend Tx_Extbase_MVC_Web_Response
 * but extend Tx_Extbase_MVC_Response
 *
 * @package Extbase
 * @subpackage MVC\Web
 * @version $ID:$
 * @scope prototype
 * @api
 */
class Tx_MvcExtjs_MVC_ExtDirect_Response extends Tx_Extbase_MVC_Web_Response {
	
	/**
	 * @var int
	 */
	protected $tid;
	
	/**
	 * @var string
	 */
	protected $controllerName;
	
	/**
	 * @var string
	 */
	protected $controllerActionName;
	
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * Default constructor.
	 * Build up the response based on the request.
	 * 
	 * @param Tx_MvcExtjs_MVC_ExtDirect_Request $request
	 * @return unknown_type
	 */
	public function __construct(Tx_MvcExtjs_MVC_ExtDirect_Request $request) {
		$this->tid = $request->getTransactionId();
		$this->controllerName = $request->getControllerName() . 'Controller';
		$this->controllerActionName = $request->getControllerActionName() . 'Action';
		$this->type = $request->getType();
	}
	
	/**
	 * Sets the Transaction Id.
	 * 
	 * @param int $tid
	 * @return void
	 */
	public function setTransactionId($tid) {
		$this->tid = $tid;
	}
	
	/**
	 * Gets the Transaction Id.
	 * 
	 * @return int
	 */
	public function getTransactionId() {
		return $this->tid;
	}
	
	/**
	 * Sets the controller name.
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setControllerName($name) {
		$this->controllerName = $name;
	}
	
	/**
	 * Gets the controller name.
	 * 
	 * @return string
	 */
	public function getControllerName() {
		return $this->controllerName;
	}
	
	/**
	 * Sets the action name.
	 * 
	 * @param string $action
	 * @return void
	 */
	public function setControllerActionName($action) {
		$this->controllerActionName = $action;
	}
	
	/**
	 * Gets the action name.
	 * 
	 * @return string
	 */
	public function getControllerActionName() {
		return $this->controllerActionName;
	}
	
	/**
	 * Sets the type.
	 * 
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Gets the type.
	 * 
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Returns the Ext.Direct response content.
	 * 
	 * @see typo3/sysext/extbase/Classes/MVC/Tx_Extbase_MVC_Response#getContent()
	 */
	public function getContent() {
		$response = array();
		$response['tid'] = $this->tid;
		$response['type'] = $this->type;
		$response['action'] = $this->controllerName;
		$response['method'] = $this->controllerActionName;
			// decode the encoded answers from the ViewHelpers...
		$result = json_decode($this->content);
		if ($result === NULL) {
			throw new Tx_MvcExtjs_ExtJS_Exception('The action result (content) is no valid json string: ' . $this->content,1277980165);
		}
		$response['result'] = $result;
		return json_encode($response);
	
	}
	
	
	
	
}
?>