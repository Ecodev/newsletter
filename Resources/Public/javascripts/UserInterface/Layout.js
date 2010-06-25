Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.Layout = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			renderTo: 't3-newsletter-application',
			height: 700,
			// items are set dynamically through method handleNavigationToken() located in every bootstrapper
			// this method is called whenever event TYPO3.Newsletter.Application.navigate is fired (at least once when application is loaded)
			items: []
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.Layout.superclass.initComponent.call(this);
	}
});