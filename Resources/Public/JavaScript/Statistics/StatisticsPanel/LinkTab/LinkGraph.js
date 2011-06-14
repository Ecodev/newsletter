"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGraph
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGraph = Ext.extend(Ext.Container, {

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
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGraph.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGraph', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGraph);