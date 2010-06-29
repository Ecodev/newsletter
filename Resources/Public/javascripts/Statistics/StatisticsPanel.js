Ext.ns("TYPO3.Newsletter.Statistics");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel
 * @namespace TYPO3.Newsletter.Statistics
 * @extends Ext.TabPanel
 *
 * Class for statistic tab panel
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel = Ext.extend(Ext.TabPanel, {

	initComponent: function() {

		this.on('afterrender', function(menu) {
			console.log(123);
//			menu.items.each(function(menuItem, i) {
//				menuItem.addListener('afterrender',	function() {
//					var task = new Ext.util.DelayedTask(function () {
//						this.el.fadeIn({
//							duration: .2
//						});
//					}, this);
//					task.delay(200 * i);
//				});
			},
			this
		);
			
		var config = {
			activeTab: 0,
//			hideBorders: true,
//			hideLabel: true,
//			bodyBorder: false,
			border: false,
			defaults: {
				autoHeight: true
			},
			items: [
				{
					title: TYPO3.Newsletter.Language.overview_tab,
					items: [
						{
							xtype: 'TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab',
							ref: 'overviewTab'
						}
					]
				},
				{
					title: TYPO3.Newsletter.Language.clickedlinks_tab,
					html: 'Another one<br>Another one<br>Another one<br>Another one<br>Another one<br>Another one<br>Another one<br>Another one<br>'
				},
				{
					title: TYPO3.Newsletter.Language.sentemails_tab,
					html: 'Another one'
				}
			]

		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel', TYPO3.Newsletter.Statistics.StatisticsPanel);