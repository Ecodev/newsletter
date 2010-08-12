"use strict";

Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			layout:'table',
			layoutConfig: {
				columns: 2
			},
			items: [
				{
					xtype: 'TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General',
					ref: 'general'
				},
//				{
//					xtype: 'TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Time',
//					ref: 'general'
//				}
//				{
//					xtype: 'TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.Graph',
//					ref: 'general',
//					colspan: 2
//				}
//				{html:'2,2', },
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab', TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab);