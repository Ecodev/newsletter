"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGraph
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGraph = Ext.extend(Ext.Container, {

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
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGraph.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGraph', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGraph);