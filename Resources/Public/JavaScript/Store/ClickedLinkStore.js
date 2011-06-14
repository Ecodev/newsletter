"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Store");

Ext.ux.TYPO3.Newsletter.Store.initClickedLink = function() {

	var store;
	store = new Ext.data.JsonStore({
		storeId: 'clickedLink',
		remoteSort: false,
		fields: [
			{
				name: 'uid',
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
				name: 'number_of_recipients',
				type: 'int'
			},
			{
				name: 'url',
				type: 'int'
			},
		],
		data: {}
	});

	// Add method to listener Ext.ux.TYPO3.Newsletter.Store.Statistic.afterload
	// Basically the code bellow empties the data and replaces new records
	Ext.ux.TYPO3.Newsletter.Store.Statistic.on(
		'Ext.ux.TYPO3.Newsletter.Store.Statistic.afterload',
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
