"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Module");

/**
 * @class Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap
 * @namespace Ext.ux.TYPO3.Newsletter.Module
 * @extends Ext.util.Observable
 *
 * Base class for all bootstrappers. This class provides convenience methods for extending the user interface.
 */
Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap = Ext.extend(Ext.util.Observable, {

	/**
	 * Add items to a menu.
	 *
	 * @param {Array} path The path where the menu items should be added. The first element is the menu-ID, the other elements are the itemIDs of the menu items.
	 * @param {Array} items The items to add
	 */
	addToMenu: function(path, items) {
		Ext.ux.TYPO3.Newsletter.Module.MenuRegistry.addMenuItems(path, items);
	},

	/**
	 * Returns an item menu corresponding to the passing item's name.
	 *
	 * @param {string} itemName the name of the item that will be retrieved from the array
	 * @return object: the found menu item
	 */
	getMenuItem: function(itemName) {
		var result = {};
		Ext.each(Ext.ux.TYPO3.Newsletter.Module.MenuRegistry.items.mainMenu, function(menuItem) {
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
		Ext.ux.TYPO3.Newsletter.Module.Application.on('Ext.ux.TYPO3.Newsletter.Module.Application.navigate', function(token) {
			var matches = token && token.match(regexp);
			if (matches) {

				// As a first step, hides every panel.
				Ext.iterate(Ext.ux.TYPO3.Newsletter.Module.contentArea.items.items, function (element) {
					element.setVisible(false)
				});
				
				// @understand what is the difference with the two lines bellow as it seems to be both working solution.
				// callback.call(this, matches); 
				callback.createDelegate(this, matches).call();
			}
		}, scope);
	}
});