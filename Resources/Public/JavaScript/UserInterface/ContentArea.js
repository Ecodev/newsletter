Ext.ns("TYPO3.Newsletter.UserInterface");

/**
 * @class TYPO3.Newsletter.UserInterface.ContentArea
 * @namespace TYPO3.Newsletter.UserInterface
 * @extends Ext.Container
 *
 * Class for the main content
 *
 * $Id$
 */
TYPO3.Newsletter.UserInterface.ContentArea = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			renderTo: 't3-newsletter-application',
			height: 700,
			// items are set dynamically through method handleNavigationToken() located in every bootstrapper
			// this method is called whenever event TYPO3.Newsletter.Application.navigate is fired (at least once when application is loaded)
			items: []
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.ContentArea.superclass.initComponent.call(this);
	}
});