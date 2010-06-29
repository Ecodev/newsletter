Ext.ns('TYPO3.Newsletter.UserInterface');

/**
 * @class TYPO3.Newsletter.UserInterface.SectionMenu
 * @namespace TYPO3.Newsletter.UserInterface
 * @extends Ext.Panel
 *
 * Class for the main menu
 *
 * $Id$
 */
TYPO3.Newsletter.UserInterface.SectionMenu = Ext.extend(Ext.Panel, {

	initComponent: function() {
		var config = {
			renderTo: 't3-newsletter-menu',
//			title: 'asdf',
			layout: 'hbox',
			width: 300,
//			layoutConfig: {
//				padding: '5px',
//				width: '200px'
//			},
			border: false,
			bodyStyle: 'background-color: #DADADA',
			items: this._getItems()
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.SectionMenu.superclass.initComponent.call(this);
	},

	/**
	 * Returns the section menu
	 *
	 * @access private
	 * @return array
	 */
	_getItems: function() {
		var modules = [];

		// Get menus
		Ext.each(TYPO3.Newsletter.Application.MenuRegistry.items.mainMenu, function(menuItem) {
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
					}
				}
			);
		});
		return modules;
	}

});
Ext.reg('TYPO3.Newsletter.UserInterface.SectionMenu', TYPO3.Newsletter.UserInterface.SectionMenu);