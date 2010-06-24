Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.SectionMenu = Ext.extend(Ext.TabPanel, {
	initComponent: function() {
		var config = {
			renderTo: 't3-newsletter-menu',
			border: false,
			style: {
				width: '300px',
				marginBottom: '10px'
	        },
			bodyStyle: {
				color: 'red',
				backgroundCol: '10px',
				marginBottom: '10px',
	        },

			cls: 'TYPO3-Newsletter-UserInterface-SectionMenu',
			items: this._getSectionMenuItems()
//			items: [
//				{
//					xtype: 'button',
//					text: 'asdf'
//				}
//			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.SectionMenu.superclass.initComponent.call(this);

//		this.on('tabchange', function(tabPanel, tab) {
//			TYPO3.Application.fireEvent('TYPO3.Newsletter.UserInterface.SectionMenu.activated', tab.itemId);
//		});
	},

//	getBubbleTarget: function() {
//		return TYPO3.Application.MenuRegistry;
//	},

	_getSectionMenuItems: function() {
		var modules = [];
		// TODO unset children properties and use only first level of array
		Ext.each(TYPO3.Newsletter.Application.MenuRegistry.items.mainMenu, function(menuItem) {
			console.log(menuItem);
			modules.push({
//				xtype: 'button',
//				text: 'asdf',

				xtype: 'container',
				layout: 'vbox',
				layoutConfig: {
					align: 'stretch'
				},
				itemId: menuItem.itemId,
				title: menuItem.title,
				tabCls: menuItem.tabCls,
//				items: [{
//					xtype: 'TYPO3.Newsletter.UserInterface.ModuleMenu',
//					ref: 'moduleMenu',
//					menuId: 'mainMenu',
//					itemId: menuItem.itemId,
//					menuConfig: menuItem.children,
//					flex: 0
//				}, {
//					xtype: 'TYPO3.Newsletter.UserInterface.ContentArea',
//					itemId: menuItem.itemId + '-contentArea',
//					ref: 'contentArea',
//					flex: 1
//				}]
			});
		});
		return modules;
	}
});
Ext.reg('TYPO3.Newsletter.UserInterface.SectionMenu', TYPO3.Newsletter.UserInterface.SectionMenu);