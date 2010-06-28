Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {
	initialize: function() {
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initMainContainer, this);
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initSectionMenu, this);
	},
	
	initMainContainer: function() {
		TYPO3.Newsletter.UserInterface.mainContainer = new TYPO3.Newsletter.UserInterface.Layout();
	},

	initSectionMenu: function() {
		TYPO3.Newsletter.UserInterface.sectionMenu = new TYPO3.Newsletter.UserInterface.SectionMenu();
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.UserInterface.Bootstrap);