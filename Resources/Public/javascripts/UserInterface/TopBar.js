Ext.ns('TYPO3.Newsletter.UserInterface');

TYPO3.Newsletter.UserInterface.TopBar = Ext.extend(Ext.Panel, {
	
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
			items: [
				{
					xtype: 'button',
					text: TYPO3.Newsletter.Language.newsletter_button,
					iconCls: 't3-newsletter-button-newsletter',
					flex: 0
				},
				{
					xtype: 'button',
					text: TYPO3.Newsletter.Language.statistics_button,
					iconCls: 't3-newsletter-button-statistics',
					flex: 0,
				}
//				{
//					xtype: 'box',
//					width: 50,
//					flex: 0
//				},
//				{
//					xtype: 'TYPO3.Newsletter.UserInterface.DummyImage',
//					backgroundImage: 'dummys/topbar_message.png',
//					width: 230,
//					height: 25,
//					flex: 0
//				}
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.TopBar.superclass.initComponent.call(this);
	}

});
Ext.reg('TYPO3.Newsletter.UserInterface.TopBar', TYPO3.Newsletter.UserInterface.TopBar);