<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dennis Ahrens <dennis.ahrens@googlemail.com>
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
 * Builds a Ext.Direct web request.
 *
 * @package MvcExtjs
 * @subpackage MVC\Web
 * @version $ID:$
 *
 * @scope prototype
 */
class Tx_MvcExtjs_MVC_ExtDirect_RequestBuilder {

	/**
	 * This is a unique key for a plugin (not the extension key!)
	 *
	 * @var string
	 */
	protected $pluginName = 'plugin';

	/**
	 * The name of the extension (in UpperCamelCase)
	 *
	 * @var string
	 */
	protected $extensionName = 'MvcExtjs';

	/**
	 * The default controller name
	 *
	 * @var string
	 */
	protected $defaultControllerName = 'Default';

	/**
	 * The default action of the default controller
	 *
	 * @var string
	 */
	protected $defaultActionName = 'index';

	/**
	 * The allowed actions of the controller. This actions can be called via Ext.Direct
	 *
	 * @var array
	 */
	protected $allowedControllerActions;
	
	/**
	 * @var Tx_Extbase_Reflection_Service
	 */
	protected $reflectionService;
	
	/**
	 * Initializes the DirectRequestBuilder.
	 * 
	 * @param array $configuration
	 * @param Tx_Extbase_Reflection_Service $reflectionService
	 * @return void
	 */
	public function initialize($configuration) {
		if (!empty($configuration['pluginName'])) {
			$this->pluginName = $configuration['pluginName'];
		}
		if (!empty($configuration['extensionName'])) {
			$this->extensionName = $configuration['extensionName'];
		}
		if (!empty($configuration['controller'])) {
			$this->defaultControllerName = $configuration['controller'];
		} elseif (is_array($configuration['switchableControllerActions'])) {
			$firstControllerActions = current($configuration['switchableControllerActions']);
			$this->defaultControllerName = $firstControllerActions['controller'];
		}
		if (!empty($configuration['action'])) {
			$this->defaultActionName = $configuration['action'];
		} elseif (is_array($configuration['switchableControllerActions'])) {
			$firstControllerActions = current($configuration['switchableControllerActions']);
			$this->defaultActionName = array_shift(t3lib_div::trimExplode(',', $firstControllerActions['actions'], TRUE));
		}
		$allowedControllerActions = array();
		if (is_array($configuration['switchableControllerActions'])) {
			foreach ($configuration['switchableControllerActions'] as $controllerConfiguration) {
				$controllerActions = t3lib_div::trimExplode(',', $controllerConfiguration['actions']);
				foreach ($controllerActions as $actionName) {
					$allowedControllerActions[$controllerConfiguration['controller']][] = $actionName;
				}
			}
		}
		$this->allowedControllerActions = $allowedControllerActions;
		$this->reflectionService = t3lib_div::makeInstance('Tx_Extbase_Reflection_Service');
	}

	/**
	 * Builds a Ext.Direct web request object from the raw HTTP information the configuration and the given
	 * Ext.Direct request informations.
	 * Note: Ext.Direct tells about action->method. Extbase knows about controller->action.
	 * 
	 * @param array $requestData
	 * @return Tx_MvcExtjs_MVC_Web_DirectRequest The web request as an object
	 */
	public function build($requestData) {	
		$controllerName = str_replace('Controller','',$requestData['action']);
		$actionName = str_replace('Action','',$requestData['method']);
		$parameters = $requestData['data'];
		$tid = $requestData['tid'];
			
		if (is_string($controllerName) && array_key_exists($controllerName, $this->allowedControllerActions)) {
			$controllerName = filter_var($controllerName, FILTER_SANITIZE_STRING);
			$allowedActions = $this->allowedControllerActions[$controllerName];
			if (is_string($actionName) && is_array($allowedActions) && in_array($actionName, $allowedActions)) {
				$actionName = filter_var($actionName, FILTER_SANITIZE_STRING);
			} else {
				$actionName = $this->defaultActionName;
			}
		} else {
			$controllerName = $this->defaultControllerName;
			$actionName = $this->defaultActionName;
		}				
		
		$request = t3lib_div::makeInstance('Tx_MvcExtjs_MVC_ExtDirect_Request');
		$request->setPluginName($this->pluginName);
		$request->setControllerExtensionName($this->extensionName);
		$request->setControllerName($controllerName);
		$request->setControllerActionName($actionName);
		$request->setRequestURI(t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
		$request->setBaseURI(t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
		$request->setTransactionId($tid);
		$request->setType($requestData['type']);
		$request->setMethod((isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : NULL);

		if (is_string($parameters['format']) && (strlen($parameters['format']))) {
			$request->setFormat(filter_var($parameters['format'], FILTER_SANITIZE_STRING));
			unset($parameters['format']);
		}
		
		$actionParameter = $this->reflectionService->getMethodParameters($request->getControllerObjectName(),$request->getControllerActionName() . 'Action');
		
		if (is_array($parameters)) {
			foreach ($parameters as $argumentPosition => $incomingArgumentValue) {
				$argumentName = $this->resolveArgumentName($argumentPosition,$actionParameter);
				try {
					$argumentValue = $this->transformArgumentValue($incomingArgumentValue,$actionParameter[$argumentName]);
					$request->setArgument($argumentName, $argumentValue);
				} catch (Tx_MvcExtjs_ExtJS_Exception $e) {}
				//t3lib_div::sysLog('$argumentName: '.print_r($argumentName,true),'MVC_ExtJs',0);
				//t3lib_div::sysLog('$argumentValue: '.print_r($argumentValue,true),'MVC_ExtJs',0);
			}
		}
		return $request;
	}
	
	/**
	 * Transforms the incoming arguments from the Ext.Direct request into
	 * the argument syntax that is used by fluid and extbase.
	 * 
	 * @param mixed $incomingArgumentValueDescription
	 * @param array $actionParameterDescription
	 * @return mixed
	 */
	protected function transformArgumentValue($incomingArgumentValueDescription, $actionParameterDescription) {
		if (is_array($incomingArgumentValueDescription)) {
			if ($actionParameterDescription['type'] === 'array') {
				return $incomingArgumentValueDescription;
			}
			if ($actionParameterDescription['type'] === 'object' || $actionParameterDescription['class'] !== '') {
					// REFACTOR THIS! we handle store requests special here, by asking for data....
					// first:  eval if the object data is nested in data or not.
				$propertyIterationArray = array();
				if (isset($incomingArgumentValueDescription['data'])) {
					$propertyIterationArray = $incomingArgumentValueDescription['data'];
				} else {
					$propertyIterationArray = $incomingArgumentValueDescription;
				}
				if (count($propertyIterationArray) === 0) throw new Tx_MvcExtjs_ExtJS_Exception('we want to map an not existing argument', 1288187158); 
					// second: eval if a uid is given.
				if (isset($propertyIterationArray['uid'])) {
					$uid = (int) $propertyIterationArray['uid'];
					unset($propertyIterationArray['uid']);
				} else if(!is_array($propertyIterationArray)) {
					$uid = (int) $propertyIterationArray;
					$propertyIterationArray = array();
				} else {
					$uid = FALSE;
				}
				$argumentValueDescription = array();
				foreach ($propertyIterationArray as $propertyName => $propertyValue) {
					if ($propertyValue === NULL) {
						continue;
					} else if(is_array($propertyValue) && $propertyValue['uid']) {
						$argumentValueDescription[$propertyName]['__identity'] = $propertyValue['uid'];
					} else if(is_array($propertyValue) && !$propertyValue['uid'] && count($propertyValue) > 0) {
						foreach ($propertyValue as $propertyValueChild) {
								// we have a relation that has to be removed...
								// there is some bug when clearing this completely
							$hackedShitArray = array();
							if ($propertyValueChild['uid']) {
								$hackedShitArray['__identity'] = $propertyValueChild['uid'];
							}
							if ($propertyValueChild['deleted']) {
								$hackedShitArray['deleted'] = $propertyValueChild['deleted'];
							}
							$argumentValueDescription[$propertyName][] = $hackedShitArray;
						}	
					} else {
						$argumentValueDescription[$propertyName] = $propertyValue;
					}
				}
				
				if ($uid !== FALSE) {
					$argumentValueDescription['__identity'] = $uid;
				}
				return $argumentValueDescription;
				
			}
		} else {
			return $incomingArgumentValueDescription;
		}
	}
	
	/**
	 * Resolves the name of the argument.
	 * 
	 * @param int $argumentPosition
	 * @param array $actionParameters
	 * @return string
	 */
	protected function resolveArgumentName($argumentPosition, array $actionParameters) {
		foreach ($actionParameters as $argumentName => $argumentConfiguration) {
			if ($argumentPosition === $argumentConfiguration['position']) {
				return $argumentName;
			}
		}
		throw new Tx_Extbase_MVC_Exception_NoSuchArgument('Could not map a Ext.Direct argument to it\'s action. There is no argument expected for position ' . $argumentPosition,1277724391);
	}

}
?>