Ext.ns("TYPO3.Backend.Newsletter.UserInterface");

TYPO3.Backend.Newsletter.UserInterface.Layout = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			renderTo: 't3-testing',
			items: [
			{
				xtype: 'panel',
				title: 'testing panel. Cool it works!',
				ref: 'logPanel',
				flex: 0
			},
			]
		};
		Ext.apply(this, config);
		TYPO3.Backend.Newsletter.UserInterface.Layout.superclass.initComponent.call(this);
	}
});