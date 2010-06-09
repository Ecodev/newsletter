Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.Layout = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			renderTo: 't3-testing',
			items: [
			{
				xtype: 'TYPO3.Newsletter.UserInterface.TestingPanel',
//				ref: 'logPanel',
//				flex: 0
			},
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.Layout.superclass.initComponent.call(this);
	}
});