"use strict";

Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initOverviewPieChart = function() {

	var store;
	store = new Ext.data.JsonStore({
		storeId: 'overviewPieChart',
//		autoLoad: false,
		remoteSort: false,
		fields: ['label', 'total'],
		data: {}
	});

	// Add method to listener TYPO3.Newsletter.Store.Statistic.afterload
	// Basically the code bellow empties the data and replaces new ones
	// for the piechart's graph
	TYPO3.Newsletter.Store.Statistic.on(
		'TYPO3.Newsletter.Store.Statistic.afterload',
		function (records) {
			var record;
			record = records[0];

			// Empties records firstly
			this.removeAll();

			//  Adds records
			this.add(new Ext.data.Record({
					label: TYPO3.Newsletter.Language.not_sent,
					total: record.json.number_of_not_sent
				})
			);
			this.add(new Ext.data.Record({
					label: TYPO3.Newsletter.Language.sent,
					total: record.json.number_of_sent
				})
			);
			this.add(new Ext.data.Record({
					label: TYPO3.Newsletter.Language.opened,
					total: record.json.number_of_opened
				})
			);
			this.add(new Ext.data.Record({
					label: TYPO3.Newsletter.Language.bounced,
					total: record.json.number_of_bounced
				})
			);
		},
		store
	);
		
	return store;
};
