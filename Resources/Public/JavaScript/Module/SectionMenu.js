Ext.ns('Ext.ux.TYPO3.Newsletter.Module');

/**
 * @class Ext.ux.TYPO3.Newsletter.Module.SectionMenu
 * @namespace Ext.ux.TYPO3.Newsletter.Module
 * @extends Ext.Panel
 *
 * Class for the main menu
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Module.SectionMenu = Ext.extend(Ext.Panel, {

	initComponent: function() {
		var config = {
			layout: 'hbox',
			bodyStyle: 'background-color: #DADADA',
			items: this._getMenuItems()
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Module.SectionMenu.superclass.initComponent.call(this);
	},

	/**
	 * Returns the section menu
	 *
	 * @access private
	 * @return array
	 */
	_getMenuItems: function() {
		var modules = [];

		// Get menus
		Ext.each(Ext.ux.TYPO3.Newsletter.Module.MenuRegistry.items.mainMenu, function(menuItem) {
			modules.push(
				{
					xtype: 'button',
//					itemId: menuItem.itemId,
					text: menuItem.text,
					iconCls: 't3-newsletter-button-' + menuItem.itemId,
					handler: function(){
						var token = menuItem.itemId;
						Ext.state.Manager.set('token', token);
						Ext.History.add(token);
						if (! menuItem.isLoaded) {
							Ext.ux.TYPO3.Newsletter.Module.Application.fireEvent('Ext.ux.TYPO3.Newsletter.Module.busy');
							menuItem.isLoaded = true;
						}
					}
				}
			);
		});
		return modules;
	}

});
Ext.reg('Ext.ux.TYPO3.Newsletter.Module.SectionMenu', Ext.ux.TYPO3.Newsletter.Module.SectionMenu);