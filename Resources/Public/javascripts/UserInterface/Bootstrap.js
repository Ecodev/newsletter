Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {
	initialize: function() {
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initMainContainer, this);
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initTopBar, this);
	},
	
	initMainContainer: function() {
		TYPO3.Newsletter.UserInterface.mainContainer = new TYPO3.Newsletter.UserInterface.Layout();
	},

	initTopBar: function() {
		TYPO3.Newsletter.UserInterface.topBar = new TYPO3.Newsletter.UserInterface.TopBar();
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.UserInterface.Bootstrap);