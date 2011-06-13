"use strict";

Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			width: 400,
			style: "background-color: blue",
			items: [
				{
					xtype: 'button',
					text: 'asdf'
				}
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph', TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph);