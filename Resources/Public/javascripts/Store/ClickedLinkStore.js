"use strict";

Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initClickedLink = function() {

	var store;
	store = new Ext.data.JsonStore({
		storeId: 'clickedLink',
		remoteSort: false,
		fields: [
			{
				name: 'link_id',
				type: 'int'
			},
			{
				name: 'number_of_opened',
				type: 'int'
			},
			{
				name: 'percentage_of_opened',
				type: 'int'
			},
			{
				name: 'total_number_of_opened',
				type: 'int'
			},
			{
				name: 'url',
				type: 'int'
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
			Ext.each(records[0].json.clicked_links, function(dataset) {
				this.add(new Ext.data.Record(dataset));
			}, store)
		},
		store
	);

	return store;
};
