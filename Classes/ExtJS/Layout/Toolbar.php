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
 * Service to handle the module toolbar.
 *
 * @category    ExtJS
 * @package     TYPO3
 * @subpackage  tx_mvcextjs
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */

//http://cms.lionsbase.loc/typo3/sysext/t3skin/icons/gfx/savedok.gif
class Tx_Mvcextjs_ExtJS_Layout_Toolbar {
	
	/**
	 * @var Tx_MvcExtjs_ExtJS_Controller_ActionController
	 */
	protected $controller;
	
	/**
	 * @var string
	 */
	protected $pluginName;
	
	/**
	 * @var t3lib_SCbase
	 */
	protected $scBase;
	
	/**
	 * @var array
	 */
	protected $functionMenu = array();
	
	/**
	 * @var array
	 */
	protected $toolbarItems = array();
	
	/**
	 * @var array
	 */
	protected $buttons;
	
	/**
	 * Default constructor.
	 *
	 * @param Tx_MvcExtjs_ExtJS_Controller_ActionController $controller
	 * @param string $pluginName The name of the current plugin
	 * @param t3lib_SCbase $scBase
	 * @return void
	 */
	public function __construct(Tx_MvcExtjs_ExtJS_Controller_ActionController $controller, $pluginName, t3lib_SCbase $scBase) {
		$this->controller = $controller;
		$this->pluginName = $pluginName;
		$this->scBase = $scBase;
		
		$this->buttons = array(
			'VIEW' => array(
				'callback' => '',
				'icon'     => 'sysext/t3skin/icons/gfx/zoom.gif',
				'tooltip'  => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:cm.view'),
			),
			'EDIT' => array(
				'callback' => '',
				'icon'     => 'sysext/t3skin/icons/gfx/edit2.gif',
				'tooltip'  => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:cm.edit'),
			),
			'SAVE' => array(
				'callback' => '',
				'icon'     => 'sysext/t3skin/icons/gfx/savedok.gif',
				'tooltip'  => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:cm.save'),
			),
		);
	}
	
	/**
	 * Sets the function menu of a backend module.
	 *
	 * @param array $functionMenu
	 * @return void
	 */
	public function setFunctionMenu(array $functionMenu) {
		$this->functionMenu = $this->scBase->mergeExternalItems($this->pluginName, 'function', $functionMenu);
	}
	
	/**
	 * Sets a callback function when button 'VIEW' is clicked.
	 *
	 * @param string $callback
	 * @param string $tooltip
	 * @return void
	 */
	public function setButtonViewCallback($callback, $tooltip = '') {
		$this->buttons['VIEW']['callback'] = $callback;
		if ($tooltip) {
			$this->buttons['VIEW']['tooltip'] = $tooltip;
		}
	}
	
	/**
	 * Sets a callback function when button 'EDIT' is clicked.
	 *
	 * @param string $callback
	 * @param string $tooltip
	 * @return void
	 */
	public function setButtonEditCallback($callback, $tooltip = '') {
		$this->buttons['EDIT']['callback'] = $callback;
		if ($tooltip) {
			$this->buttons['EDIT']['tooltip'] = $tooltip;
		}
	}
	
	/**
	 * Sets a callback function when button 'SAVE' is clicked.
	 *
	 * @param string $callback
	 * @param string $tooltip
	 * @return void
	 */
	public function setButtonSaveCallback($callback, $tooltip = '') {
		$this->buttons['SAVE']['callback'] = $callback;
		if ($tooltip) {
			$this->buttons['SAVE']['tooltip'] = $tooltip;
		}
	}
	
	/**
	 * Adds a button to the toolbar.
	 *
	 * @param string $icon
	 * @param string $callback
	 * @param string $tooltip
	 * @return void
	 */
	public function addButton($icon, $callback, $tooltip = '') {
		if (substr($icon, 0, 4) === 'EXT:') {
			list($extKey, $local) = explode('/', substr($icon, 4), 2);
			$icon = t3lib_extMgm::extRelPath($extKey) . $local;
		}
		
		if (substr($tooltip, 0, 4) === 'LLL:') {
			$tooltip = $GLOBALS['LANG']->sL($tooltip);
		}
		
		$this->buttons[] = array(
			'icon'     => $icon,
			'callback' => $callback,
			'tooltip'  => $tooltip,
		);
	}
	
	/**
	 * Initializes all ExtJS elements that will be used when integrating the toolbar into a panel items collection.
	 *
	 * @param string $selfUrl An ExtJS variable containing module's self URL (not the URL itself!)
	 * @return void
	 */
	public function prepareToolbarRendering($selfUrl) {
		$this->prepareFunctionMenu($selfUrl);
		$this->prepareButtons();
	}
	
	/**
	 * Return a comma-separated list of toolbar items.
	 *
	 * @return string
	 */
	public function getToolbarItemList() {
		return implode(',', $this->toolbarItems);
	}
	
	/**
	 * Initializes the function menu combobox.
	 *
	 * @param string $selfUrl An ExtJS variable containing module's self URL (not the URL itself!)
	 * @return void
	 */
	protected function prepareFunctionMenu($selfUrl) {
		if (!count($this->functionMenu)) {
				// Early return
			return;
		}
		
		$menuEntries = array();
		foreach ($this->functionMenu as $id => $title) {
			$menuEntry = json_encode(array($id => $title));
			$menuEntry = preg_replace('/^{(.*)":"(.*)}/', '[\1","\2]', $menuEntry);
			$menuEntries[] = $menuEntry;
		}
		
		$this->controller->addJsInlineCode('
			var funcMenu = new Ext.form.ComboBox({
				triggerAction: "all",
				mode: "local",
				store: new Ext.data.ArrayStore({
					autoDestroy: true,
					fields: ["key", "title"],
					data: [' . implode(',', $menuEntries) . ']
				}),
				displayField: "title",
				readOnly: true,
				listeners:{
					select:function(combo, record, index) {
						jumpToUrl(' . $selfUrl . ' + "&SET[function]=" + record.data.key);
					}
				}
			});
		');
		
			// Select current function
		$set = t3lib_div::_GP('SET');
		if ($set) {
			$currentFunction = $this->functionMenu[$set['function']];
		}
		if (!$currentFunction) {
			$keys = array_keys($this->functionMenu);
			$currentFunction = $this->functionMenu[$keys[0]];
		}
		
		$this->controller->addJsInlineCode('
			funcMenu.setValue("' . str_replace('"', '\\"', $currentFunction) . '");
		');
		
		$this->toolbarItems[] = 'funcMenu';
	}
	
	/**
	 * Initializes the toolbar buttons.
	 *
	 * @return void
	 */
	protected function prepareButtons() {
		$addSeparator = count($this->toolbarItems) > 0;
		
		foreach ($this->buttons as $info) {
			if ($info['callback']) {
				if ($addSeparator) {
					$this->toolbarItems[] = '
						{
							xtype: "tbspacer"
						}
					';
					$addSeparator = false;
				}
				
				$this->toolbarItems[] = '
					{
						xtype: "tbbutton",
						cls: "x-btn-icon",
						icon: "'. $info['icon'] . '",
						tooltip: "' . $info['tooltip'] . '",
						tooltipType: "title",
						handler: function() { ' . $info['callback'] . ' }
					}
				';
			}
		}
	}
	
}
?>