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
 * Creates a request and dispatches it to the backend controller which was
 * specified by {TBD}, FlexForm and returns the content to the v4 framework.
 *
 * @category    Extbase
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class Tx_MvcExtjs_BackendDispatcher extends Tx_Extbase_Dispatcher {
	
	/**
	 * Calls an Extbase Backend module.
	 *
	 * @param string $module 
	 * @return void
	 */
	public function callModule($module) {
		if (!isset($GLOBALS['TBE_EXTBASE_MODULES'][$module])) {
			die('No configuration found for module ' . $module);
		}
		
		$config = $GLOBALS['TBE_EXTBASE_MODULES'][$module];
		
			// Check permissions and exit if the user has no permission for entry
		$GLOBALS['BE_USER']->modAccess($config, true);
		if (t3lib_div::_GP('id')) {
				// check page access
			$id = t3lib_div::_GP('id');
			$permClause = $GLOBALS['BE_USER']->getPagePermsClause(true);
			$access = is_array(t3lib_BEfunc::readPageAccess($id, $permClause));
			if (!$access) {
				t3lib_BEfunc::typo3PrintError('No Access', 'You don\'t have access to this page', 0);
			}
		}
		
			// Resolve the controller/action to use
		list($controller, $action) = $this->resolveControllerAction($module);
		
		echo $this->transfer($module, $controller, $action);
	}
	
	/**
	 * Resolves the controller and action to use for current call.
	 * This takes into account any function menu that has being called. 
	 *
	 * @param string $module
	 * @return array Array containing the controller and the action to use for current call
	 */
	private function resolveControllerAction($module) {
		$config = $GLOBALS['TBE_EXTBASE_MODULES'][$module];
		
			// Extract dispatcher settings from request
		$argumentPrefix = strtolower('tx_' . $config['extensionName'] . '_' . $config['name']);
		$dispatcherParams = t3lib_div::_GP($argumentPrefix);
		
			// Extract module settings from its registration in ext_tables.php
		$controllers = array_keys($config['controllerActions']);
		$defaultController = array_shift($controllers);
		$actions = t3lib_div::trimExplode(',', $config['controllerActions'][$defaultController], true);
		$defaultAction = $actions[0];
		
			// Determine the controller and action to use
		$controller = $defaultController;
		if (isset($dispatcherParams['controller'])) {
			$requestedController = $dispatcherParams['controller'];
			if (in_array($requestedController, $controllers)) {
				$controller = $requestedController;
			}
		}
		$action = $defaultAction;
		if (isset($dispatcherParams['action'])) {
			$requestedAction = $dispatcherParams['action'];
			$controllerActions = t3lib_div::trimExplode(',', $config['controllerActions'][$controller], true);
			if (in_array($requestedAction, $controllerActions)) {
				$action = $requestedAction;
			}
		}
		
			// Allow function dispatcher to override controller/action
		$set = t3lib_div::_GP('SET');
		if ($set) {
			$currentFunction = $set['function'];
			
			$matches = array();
			if (preg_match('/^(.*)->(.*)$/', $currentFunction, $matches)) {
				$controller = $matches[1];
				$action = $matches[2];
			} else {
					// Support for external plain-old module rendering
				$functions = $GLOBALS['TBE_MODULES_EXT'][$module]['MOD_MENU']['function'];
				if (isset($functions[$currentFunction])) {
					$controller = $defaultController;
					$action = 'extObj';
				}
			}
		}
		
		return array($controller, $action);
	}
	
	/**
	 * Transfers the request to an Extbase backend module, calling
	 * a given controller/action.
	 *
	 * @param string $module
	 * @param string $controller
	 * @param string $action
	 * @return string The module rendered view
	 */
	private function transfer($module, $controller, $action) {
		 $config = $GLOBALS['TBE_EXTBASE_MODULES'][$module];
		 
		 $extbaseConfiguration = array(
			'userFunc' => 'tx_extbase_dispatcher->dispatch',
			'pluginName' => $module,
			'extensionName' => $config['extensionName'],
			'enableAutomaticCacheClearing' => 1,
			'controller' => $controller,
			'action' => $action,
			'switchableControllerActions.' => array()
		);
		
		$i = 1;
		foreach ($config['controllerActions'] as $controller => $actions) {
				// Add an "extObj" action for the default controller to handle external
				// SCbase modules which add function menu entries
			if ($i == 1) {
				$actions .= ',extObj'; 
			}
			$extbaseConfiguration['switchableControllerActions.'][$i++ . '.'] = array(
				'controller' => $controller,
				'actions' => $actions,
			);
		}
				
			// BACK_PATH is the path from the typo3/ directory from within the
			// directory containing the controller file. We are using mod.php dispatcher
			// and thus we are already within typo3/ because we call typo3/mod.php
		$GLOBALS['BACK_PATH'] = '';
		
		return $this->dispatch('Here comes Extbase BE Module', $extbaseConfiguration);
	}
	
}
?>