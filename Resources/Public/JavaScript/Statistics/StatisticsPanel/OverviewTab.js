"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			layout:	'fit',
			layoutConfig: {
				columns: 2
			},
			items: [
				{
					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General',
					ref: 'general'
				},
//				{
//					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Time',
//					ref: 'general'
//				}
//				{
//					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.OverviewGraph',
//					ref: 'general',
//					colspan: 2
//				}
			]
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab);