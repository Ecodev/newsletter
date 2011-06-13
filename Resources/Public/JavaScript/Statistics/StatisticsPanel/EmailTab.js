"use strict";

Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			layout:'table',
			width: 'auto',
			layoutConfig: {
				columns: 1
			},
			items: [
				{
					xtype: 'TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid',
					ref: 'emailGrid'
				},
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab', TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab);