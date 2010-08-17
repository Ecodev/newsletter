"use strict";

Ext.ns("TYPO3.Newsletter.Application");

/**
 * @class TYPO3.Newsletter.Application.AbstractBootstrap
 * @namespace TYPO3.Newsletter.Application
 * @extends Ext.util.Observable
 *
 * Base class for all bootstrappers. This class provides convenience methods for extending the user interface.
 */
TYPO3.Newsletter.Application.AbstractBootstrap = Ext.extend(Ext.util.Observable, {

	/**
	 * Add items to a menu.
	 *
	 * @param {Array} path The path where the menu items should be added. The first element is the menu-ID, the other elements are the itemIDs of the menu items.
	 * @param {Array} items The items to add
	 */
	addToMenu: function(path, items) {
		TYPO3.Newsletter.Application.MenuRegistry.addMenuItems(path, items);
	},

	/**
	 * Returns an item menu corresponding to the passing item's name.
	 *
	 * @param {string} itemName the name of the item that will be retrieved from the array
	 * @return object: the found menu item
	 */
	getMenuItem: function(itemName) {
		var result = {};
		Ext.each(TYPO3.Newsletter.Application.MenuRegistry.items.mainMenu, function(menuItem) {
			if (menuItem.itemId == itemName) {
				result = menuItem;
			}
		});
		return result;
	},

	/**
	 * Add a module dialog to a menu. A module dialog will be displayed in the header, and pushes the content area down.
	 *
	 * TODO: parameters
	 */
//	addModuleDialog: function(path, moduleDialogConfig, contentDialogConfig) {
//		this.handleButtonPress(path, function(button) {
//			var moduleDialog;
//			moduleDialog = button.findParentByType(TYPO3.Newsletter.UserInterface.ModuleMenu).showModuleDialog(moduleDialogConfig, contentDialogConfig);
//			// Untoggle button on module dialog destroy
//			moduleDialog.on('destroy', function() {
//				button.toggle(false);
//			});
//		});
//		this.handleButtonUnpress(path, function(button) {
//			button.findParentByType(TYPO3.Newsletter.UserInterface.ModuleMenu).removeModuleDialog();
//		});
//	},

	/**
	 * Handle generic button press of a menu button.
	 *
	 * @param {Array} path The path to the button
	 * @param {Function} callback the callback
	 * @param {Object} scope
	 */
//	handleButtonPress: function(path, callback, scope) {
//		var joinedPath = path.join('-');
//		TYPO3.Newsletter.Application.MenuRegistry.on('TYPO3.Newsletter.UserInterface.RootlineMenu.buttonPressed', function(button) {
//			if (button.getFullPath() === joinedPath) {
//				callback.call(scope, button);
//			}
//		});
//	},

	/**
	 * Handle generic button unpress of a menu button.
	 *
	 * @param {Array} path The path to the button
	 * @param {Function} callback the callback
	 * @param {Object} scope
	 */
//	handleButtonUnpress: function(path, callback, scope) {
//		var joinedPath = path.join('-');
//		TYPO3.Newsletter.Application.MenuRegistry.on('TYPO3.Newsletter.UserInterface.RootlineMenu.buttonUnpressed', function(button) {
//			if (button.getFullPath() === joinedPath) {
//				callback.call(scope, button);
//			}
//		});
//	},

	/**
	 * Add an element to the content area.
	 *
	 * TODO: parameters
	 */
//	addContentArea: function(sectionId, itemId, config) {
//		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.UserInterface.ContentArea.afterInit', function(contentArea) {
//			if (sectionId + '-contentArea' === contentArea.itemId) {
//				contentArea.add(Ext.apply(config, {
//					itemId: itemId
//				}));
//			}
//		});
//	},

	/**
	 * Handle a navigation token for the history manager.
	 *
	 * @param {RegExp} regexp the callback is called if the regexp matches
	 * @param {function} callback Callback to be called
	 * @param scope
	 */
	handleNavigationToken: function(regexp, callback, scope) {
		scope = scope || this;
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.navigate', function(token) {
			var matches = token && token.match(regexp);
			if (matches) {

				// As a first step, hides every panel.
				Ext.iterate(TYPO3.Newsletter.UserInterface.contentArea.items.items, function (element) {
					element.setVisible(false)
				});
				
				// @understand what is the difference with the two lines bellow as it seems to be both working solution.
				// callback.call(this, matches); 
				callback.createDelegate(this, matches).call();
			}
		}, scope);
	}
});