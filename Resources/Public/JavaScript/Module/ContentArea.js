Ext.ns("Ext.ux.TYPO3.Newsletter.Module");

/**
 * @class Ext.ux.TYPO3.Newsletter.Module.ContentArea
 * @namespace Ext.ux.TYPO3.Newsletter.Module
 * @extends Ext.Container
 *
 * Class for the main content
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Module.ContentArea = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			//height: 400,
			// items are set dynamically through method handleNavigationToken() located in every bootstrapper
			// this method is called whenever event Ext.ux.TYPO3.Newsletter.Module.navigate is fired (at least once when application is loaded)
			items: []
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Module.ContentArea.superclass.initComponent.call(this);
	}
});