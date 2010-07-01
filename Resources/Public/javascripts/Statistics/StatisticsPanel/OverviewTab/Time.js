Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Time
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Time = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			width: 200,
			style: "background-color: green",
			items: [
				{
					xtype: 'button',
					text: 'asdf'
				},
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Time.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Time', TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Time);