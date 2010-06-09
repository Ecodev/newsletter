Ext.ns("TYPO3.Newsletter.Store");

// TODO: DOKU FOR TYPO3.Newsletter.Store.viewport;

TYPO3.Newsletter.Store.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {
	initialize: function() { // TODO: Call like object lifecycle method in FLOW3!
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initStore, this);
	},
	initStore: function() {
		for (var api in Ext.app.ExtDirectAPI) {
			Ext.Direct.addProvider(Ext.app.ExtDirectAPI[api]);
		}

		TYPO3.Newsletter.LogStore = TYPO3.Newsletter.initLogStore()
//		TYPO3.Newsletter.LogStore.load();
		console.log(TYPO3.Newsletter.LogStore.data);

//		TYPO3.Newsletter.LogStore2.doRequest();
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.Store.Bootstrap);