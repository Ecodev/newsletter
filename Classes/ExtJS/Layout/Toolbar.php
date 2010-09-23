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
	 * @var array
	 */
	protected $userItems = array();

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
				'text'     => '',
			),
			'EDIT' => array(
				'callback' => '',
				'icon'     => 'sysext/t3skin/icons/gfx/edit2.gif',
				'tooltip'  => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:cm.edit'),
				'text'     => '',
			),
			'SAVE' => array(
				'callback' => '',
				'icon'     => 'sysext/t3skin/icons/gfx/savedok.gif',
				'tooltip'  => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:cm.save'),
				'text'     => '',
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
	public function setButtonViewCallback($callback, $tooltip = '', $text = '') {
		$this->buttons['VIEW']['callback'] = $callback;
		if ($tooltip) {
			$this->buttons['VIEW']['tooltip'] = $tooltip;
		}
		$this->buttons['VIEW']['text'] = $text;
	}

	/**
	 * Sets a callback function when button 'EDIT' is clicked.
	 *
	 * @param string $callback
	 * @param string $tooltip
	 * @return void
	 */
	public function setButtonEditCallback($callback, $tooltip = '', $text = '') {
		$this->buttons['EDIT']['callback'] = $callback;
		if ($tooltip) {
			$this->buttons['EDIT']['tooltip'] = $tooltip;
		}
		$this->buttons['EDIT']['text'] = $text;
	}

	/**
	 * Sets a callback function when button 'SAVE' is clicked.
	 *
	 * @param string $callback
	 * @param string $tooltip
	 * @return void
	 */
	public function setButtonSaveCallback($callback, $tooltip = '', $text = '') {
		$this->buttons['SAVE']['callback'] = $callback;
		if ($tooltip) {
			$this->buttons['SAVE']['tooltip'] = $tooltip;
		}
		$this->buttons['SAVE']['text'] = $text;
	}

	/**
	 * Adds a button to the toolbar.
	 *
	 * @param string $icon
	 * @param string $callback
	 * @param string $tooltip
	 * @return void
	 */
	public function addButton($icon, $callback, $tooltip = '', $text = '') {
		if (substr($icon, 0, 4) === 'EXT:') {
			list($extKey, $local) = explode('/', substr($icon, 4), 2);
			$icon = t3lib_extMgm::extRelPath($extKey) . $local;
		}
		if (substr($tooltip, 0, 4) === 'LLL:') {
			$tooltip = $GLOBALS['LANG']->sL($tooltip);
		}
		if (substr($text, 0, 4) === 'LLL:') {
			$text = $GLOBALS['LANG']->sL($text);
		}

		$key = 'USER_' . count($this->buttons);
		$this->buttons[$key] = array(
			'icon'     => $icon,
			'callback' => $callback,
			'tooltip'  => $tooltip,
			'text'     => $text,
		);
		$this->userItems[] = $key;
	}

	/**
	 * Adds a separator.
	 *
	 * @return void
	 */
	public function addSeparator() {
		$this->addExtJSItem('{ xtype: "tbspacer" }');
	}

	/**
	 * Adds an arbitrary ExtJS toolbar item.
	 *
	 * @param string $item The ExtJS definition
	 * @return void
	 */
	public function addExtJSItem($item) {
		$this->userItems[] = $item;
	}

	/**
	 * Initializes all ExtJS elements that will be used when integrating the toolbar into a panel items collection.
	 *
	 * @param string $selfUrl An ExtJS variable containing module's self URL (not the URL itself!)
	 * @return void
	 */
	public function prepareToolbarRendering($selfUrl) {
		$this->prepareFunctionMenu($selfUrl);

		if (count($this->toolbarItems) > 0) {
			$this->toolbarItems[] = '{ xtype: "tbspacer" }';
		}

		$this->prepareButtons();

		if (count($this->toolbarItems) > 0) {
			$this->toolbarItems[] = '{ xtype: "tbspacer" }';
		}

		$this->prepareUserContent();
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
					fields: ["key", "title"],
					data: [' . implode(',', $menuEntries) . ']
				}),
				valueField: "key",
				displayField: "title",
				editable: false,
				listeners:{
					select:function(combo, record, index) {
						var url = ' . $selfUrl . ';
						var keyParts = record.data.key.split("->");

						// Rewrite url if "key" looks like "ControllerName->actionName"
						if (keyParts.length > 1) {
							var targetControllerName = keyParts[0];
							var urlParts = url.split("=");
							var currentControllerName = urlParts[urlParts.length - 1];

							if (currentControllerName != targetControllerName) {
								urlParts[urlParts.length - 1] = targetControllerName;
							}
							url = urlParts.join("=");
						}

						jumpToUrl(url + "&SET[function]=" + record.data.key);
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
			$defaultFunctionMenu = $this->getDefaultFunctionMenu();
			$currentFunction = $defaultFunctionMenu['title'];
		}

		$this->controller->addJsInlineCode('
			funcMenu.setValue("' . str_replace('"', '\\"', $currentFunction) . '");
		');

		$this->toolbarItems[] = 'funcMenu';
	}

	/**
	 * Returns the default function menu.
	 *
	 * @return array
	 */
	public function getDefaultFunctionMenu() {
		$keys = array_keys($this->functionMenu);

		return array(
			'key'   => $keys[0],
			'title' => $this->functionMenu[$keys[0]],
		);
	}

	/**
	 * Initializes the toolbar buttons.
	 *
	 * @return void
	 */
	protected function prepareButtons() {
		foreach ($this->buttons as $key => $button) {
			if (substr($key, 0, 5) !== 'USER_') {
				$this->appendButton($button);
			}
		}
	}

	/**
	 * Prepare user-defined items
	 *
	 * @return void
	 */
	protected function prepareUserContent() {
		foreach ($this->userItems as $item) {
			if (substr($item, 0, 5) === 'USER_') {
				$this->appendButton($this->buttons[$item]);
			} else {
				$this->toolbarItems[] = $item;
			}
		}
	}

	/**
	 * Appends a button to the toolbar.
	 *
	 * @param array $button
	 * @return void
	 */
	protected function appendButton($button) {
		if ($button['callback']) {
			if ($button['text']) {
				$this->toolbarItems[] = '
					{
						xtype: "tbbutton",
						cls: "x-btn-text-icon",
						icon: "'. $button['icon'] . '",
						text: "' . str_replace('"', '\\"', $button['text']) . '",
						tooltip: "' . str_replace('"', '\\"', $button['tooltip']) . '",
						tooltipType: "title",
						handler: function() { ' . $button['callback'] . ' }
					}
				';
			} else {
				$this->toolbarItems[] = '
					{
						xtype: "tbbutton",
						cls: "x-btn-icon",
						icon: "'. $button['icon'] . '",
						tooltip: "' . str_replace('"', '\\"', $button['tooltip']) . '",
						tooltipType: "title",
						handler: function() { ' . $button['callback'] . ' }
					}
				';
			}
		}
	}

}
?>