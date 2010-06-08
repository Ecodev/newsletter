Ext.ns("TYPO3.Backend.Newsletter.Store");

// TODO: DOKU FOR TYPO3.Backend.Newsletter.Store.viewport;

TYPO3.Backend.Newsletter.Store.Bootstrap = Ext.apply(new TYPO3.Backend.Newsletter.Application.AbstractBootstrap, {
	initialize: function() { // TODO: Call like object lifecycle method in FLOW3!
		TYPO3.Backend.Newsletter.Application.on('TYPO3.Backend.Newsletter.Application.afterBootstrap', this.initStore, this);
	},
	initStore: function() {
		for (var api in Ext.app.ExtDirectAPI) {
			Ext.Direct.addProvider(Ext.app.ExtDirectAPI[api]);
		}

		TYPO3.Backend.Newsletter.LogStore = TYPO3.Backend.Newsletter.initLogStore()
//		TYPO3.Backend.Newsletter.LogStore.load();
		console.log(TYPO3.Backend.Newsletter.LogStore.data);

//		TYPO3.Backend.Newsletter.LogStore2.doRequest();
	}
});

TYPO3.Backend.Newsletter.Application.registerBootstrap(TYPO3.Backend.Newsletter.Store.Bootstrap);