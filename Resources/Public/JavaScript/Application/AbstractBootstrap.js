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