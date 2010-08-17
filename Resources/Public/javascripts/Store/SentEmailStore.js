"use strict";

Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initSentEmail = function() {

	var store;
	store = new Ext.data.JsonStore({
		storeId: 'sentEmail',
		remoteSort: false,
		fields: [
			{
				name: 'recipient_id',
				type: 'int'
			},
			{
				name: 'email',
				type: 'string'
			},
		],
		data: {}
	});

	// Add method to listener TYPO3.Newsletter.Store.Statistic.afterload
	// Basically the code bellow empties the data and replaces new records
	TYPO3.Newsletter.Store.Statistic.on(
		'TYPO3.Newsletter.Store.Statistic.afterload',
		function (records) {

			// Empties records firstly
			this.removeAll();

			//  Adds records
			Ext.each(records[0].json.sent_emails, function(dataset) {
				this.add(new Ext.data.Record(dataset));
			}, store)
		},
		store
	);

	return store;
};
