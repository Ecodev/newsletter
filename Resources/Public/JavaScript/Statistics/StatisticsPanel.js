"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics
 * @extends Ext.TabPanel
 *
 * Class for statistic tab panel
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel = Ext.extend(Ext.TabPanel, {

	initComponent: function() {
		
		var config = {
			activeTab: 0,
			border: false,
			items: [
				
				{
					title: Ext.ux.TYPO3.Newsletter.Language.overview_tab,
					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab',
					itemId: 'overviewTab'
				},
				{
					title: Ext.ux.TYPO3.Newsletter.Language.emails_tab,
					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab',
					itemId: 'emailTab'
				},
				{
					title: Ext.ux.TYPO3.Newsletter.Language.links_tab,
					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab',
					itemId: 'linkTab'
				}
			]
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.superclass.initComponent.call(this);
	}

});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel);