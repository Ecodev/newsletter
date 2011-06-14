Ext.ns("Ext.ux.TYPO3.Newsletter.Module");
/**
 * @class Ext.ux.TYPO3.Newsletter.Module.MenuRegistry
 * @namespace Ext.ux.TYPO3.Newsletter.Module
 * @extends Ext.util.Observable
 *
 * The menu registry provides the structure of all menus used in the application.
 * 
 * @singleton
 */
Ext.ux.TYPO3.Newsletter.Module.MenuRegistry = Ext.apply(new Ext.util.Observable(), {

	/**
	 * Contains the menu architecture
	 *
	 */
	items: {},

	/**
	 * @event Ext.ux.TYPO3.Newsletter.Module.RootlineMenu.buttonUnpressed
	 * @param {Ext.ux.TYPO3.Newsletter.Module.RootlineMenu.Button} button the button being released
	 * Called if a button is unpressed.
	 */

	addMenuItems: function(path, items) {
		var menuName = path.shift();
		if (typeof this.items[menuName] == 'undefined') {
			this.items[menuName] = {};
		}
		if (path.length === 0) {
			this.items[menuName] = items;
		}
		else {
			var menuItems = this.items[menuName], t;
			Ext.each(path, function(pathEntry) {
				var found = false;
				Ext.each(menuItems, function(menuItem) {
					if (menuItem.itemId === pathEntry) {
						menuItem.items = menuItem.items || []; // items replaced children
						menuItems = menuItem.items; // items replaced children
						found = true;
					}
				});
				if (!found) {
					t = [];
					menuItems.push({
						itemId: pathEntry,
						items: t // items replaced children
					});
					menuItems = t;
				}
			}, this);

			menuItems.push.apply(menuItems, items);
		}
	}
});