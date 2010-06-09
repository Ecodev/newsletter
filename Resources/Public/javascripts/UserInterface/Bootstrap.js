Ext.ns("TYPO3.Newsletter.UserInterface");

// TODO: DOKU FOR TYPO3.Newsletter.UserInterface.viewport;

TYPO3.Newsletter.UserInterface.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {
	initialize: function() { // TODO: Call like object lifecycle method in FLOW3!
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initViewport, this);
	},
	initViewport: function() {
		TYPO3.Newsletter.UserInterface.viewport = new TYPO3.Newsletter.UserInterface.Layout();
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.UserInterface.Bootstrap);