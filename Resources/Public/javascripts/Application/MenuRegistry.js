Ext.ns("TYPO3.Newsletter.Application");
/**
 * @class TYPO3.Newsletter.Application.MenuRegistry
 * @namespace TYPO3.Newsletter.Application
 * @extends Ext.util.Observable
 *
 * The menu registry provides the structure of all menus used in the application.
 * 
 * @singleton
 */
TYPO3.Newsletter.Application.MenuRegistry = Ext.apply(new Ext.util.Observable, {

	/**
	 * Contains the menu architecture
	 *
	 */
	items: {},

	/**
	 * @event TYPO3.Newsletter.UserInterface.RootlineMenu.buttonUnpressed
	 * @param {TYPO3.Newsletter.UserInterface.RootlineMenu.Button} button the button being released
	 * Called if a button is unpressed.
	 */

	addMenuItems: function(path, items) {
		var menuName = path.shift();
		if (typeof this.items[menuName] == 'undefined') {
			this.items[menuName] = {};
		}
		if (path.length == 0) {
			this.items[menuName] = items;
		}
		else {
			var menuItems = this.items[menuName], t;
			Ext.each(path, function(pathEntry) {
				var found = false;
				Ext.each(menuItems, function(menuItem) {
					if (menuItem.itemId === pathEntry) {
						menuItem.children = menuItem.children || [];
						menuItems = menuItem.children;
						found = true;
					}
				});
				if (!found) {
					t = [];
					menuItems.push({
						itemId: pathEntry,
						children: t
					});
					menuItems = t;
				}
			}, this);

			menuItems.push.apply(menuItems, items);
		}
	}
});