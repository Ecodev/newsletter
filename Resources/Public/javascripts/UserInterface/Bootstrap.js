Ext.ns("TYPO3.Backend.Newsletter.UserInterface");

// TODO: DOKU FOR TYPO3.Backend.Newsletter.UserInterface.viewport;

TYPO3.Backend.Newsletter.UserInterface.Bootstrap = Ext.apply(new TYPO3.Backend.Newsletter.Application.AbstractBootstrap, {
	initialize: function() { // TODO: Call like object lifecycle method in FLOW3!
		TYPO3.Backend.Newsletter.Application.on('TYPO3.Backend.Newsletter.Application.afterBootstrap', this.initViewport, this);
	},
	initViewport: function() {
		TYPO3.Backend.Newsletter.UserInterface.viewport = new TYPO3.Backend.Newsletter.UserInterface.Layout();
	}
});

TYPO3.Backend.Newsletter.Application.registerBootstrap(TYPO3.Backend.Newsletter.UserInterface.Bootstrap);