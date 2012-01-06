"use strict";

Ext.ns('Ext.ux.TYPO3.Newsletter.Store');

Ext.ux.TYPO3.Newsletter.Store.TimelineChart = function() {
var timelineChartStore = null;
	
	var initialize = function() {
		if (timelineChartStore == null) {
			timelineChartStore = new Ext.data.DirectStore({
				storeId: 'Tx_Newsletter_Timeline_Chart',
				reader: new Ext.data.JsonReader({
					totalProperty: 'total',
					successProperty: 'success',
					idProperty: 'time',
					root: 'data',
					fields:[
					    {name: 'time', type: 'date', dateFormat: 'timestamp'},
					    {name: 'emailNotSentCount', type: 'float'},
					    {name: 'emailSentCount', type: 'float'},
					    {name: 'emailOpenedCount', type: 'float'},
					    {name: 'emailBouncedCount', type: 'float'},
					    {name: 'linkOpenedCount', type: 'float'},
					    {name: 'emailNotSentPercentage', type: 'float'},
					    {name: 'emailSentPercentage', type: 'float'},
					    {name: 'emailOpenedPercentage', type: 'float'},
					    {name: 'emailBouncedPercentage', type: 'float'},
					    {name: 'linkOpenedPercentage', type: 'float'}
					]
				}),
				api: {
					read: Ext.ux.TYPO3.Newsletter.Remote.NewsletterController.statisticsAction
				},
				paramOrder: {
					read: ['data']
				},
				restful: false,
				batch: false,
				remoteSort: false
			});
			
			
			// When a newsletter is selected, we update the data for the timeline chart
			Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_SelectedNewsletter').on(
				'datachanged',
				function (selectedNewsletterStore) {
					var newsletter = selectedNewsletterStore.getAt(0);
					this.loadData({
						data: newsletter.json.statistics
					});					

				},
				timelineChartStore
				);
		}
	}
	
	/**
	 * Public API of this singleton.
	 */
	return {
		initialize: initialize
	};
}();
