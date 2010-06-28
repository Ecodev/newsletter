Ext.ns('TYPO3.Newsletter.UserInterface');

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
			items: this._getSectionMenuItems()
		};
		console.log(this._getSectionMenuItems());
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.SectionMenu.superclass.initComponent.call(this);
	},

	/**
	 * Returns the section menu
	 *
	 * @access private
	 * @return array
	 */
	_getSectionMenuItems: function() {
		var modules = [];

		var mainMenu = TYPO3.Newsletter.Application.MenuRegistry.getMainMenu();

		Ext.each(mainMenu, function(menuItem) {
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