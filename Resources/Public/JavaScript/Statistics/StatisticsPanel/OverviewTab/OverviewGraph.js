"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph = Ext.extend(Ext.Container, {

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
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph);