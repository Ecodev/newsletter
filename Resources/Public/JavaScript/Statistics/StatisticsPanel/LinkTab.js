"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			layout:'fit',
			width: 'auto',
			layoutConfig: {
				columns: 1
			},
			items: [
				{
					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid',
					ref: 'linkGrid'
				},
			]
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab);